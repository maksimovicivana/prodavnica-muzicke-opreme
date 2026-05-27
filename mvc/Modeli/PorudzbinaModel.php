<?php
class PorudzbinaModel
{
    private function normalizujPodatkePorudzbine($ulaz)
    {
        return array(
            'brojPorudzbine' => isset($ulaz['brojPorudzbine']) ? ocistiTekst($ulaz['brojPorudzbine']) : '',
            'kupac' => isset($ulaz['kupac']) ? ocistiTekst($ulaz['kupac']) : '',
            'emailKupca' => isset($ulaz['emailKupca']) ? ocistiTekst($ulaz['emailKupca']) : '',
            'datumPorudzbine' => isset($ulaz['datumPorudzbine']) ? ocistiTekst($ulaz['datumPorudzbine']) : date('Y-m-d'),
            'statusPorudzbine' => isset($ulaz['statusPorudzbine']) ? ocistiTekst($ulaz['statusPorudzbine']) : 'Nova',
            'nacinPlacanja' => isset($ulaz['nacinPlacanja']) ? ocistiTekst($ulaz['nacinPlacanja']) : 'Kartica',
            'napomena' => isset($ulaz['napomena']) ? ocistiTekst($ulaz['napomena']) : '',
        );
    }

    public function dajListu($pretraga, $status)
    {
        $konekcija = otvoriKonekciju();
        $viewRepo = new DBPorudzbinaV($konekcija, 'PORUDZBINA');
        $lista = $viewRepo->DajSvePorudzbine($pretraga, $status);
        zatvoriKonekciju($konekcija);

        return $lista;
    }

    public function proveriBrojPorudzbine($brojPorudzbine, $brojZaPreskok = '')
    {
        $konekcija = otvoriKonekciju();
        if (empty($konekcija->konekcijaDB)) {
            return array('status' => 500, 'podaci' => array('uspeh' => false, 'poruka' => 'Konekcija sa bazom nije uspostavljena.'));
        }

        $repo = new DBPorudzbina($konekcija, 'PORUDZBINA');
        $postoji = $repo->DaLiPostojiPorudzbina($brojPorudzbine, $brojZaPreskok);
        zatvoriKonekciju($konekcija);

        return array('status' => 200, 'podaci' => array('uspeh' => true, 'postoji' => $postoji));
    }

    public function dajDetalj($brojPorudzbine)
    {
        $konekcija = otvoriKonekciju();
        if (empty($konekcija->konekcijaDB)) {
            return array('greska' => 'Konekcija sa bazom nije uspostavljena.');
        }

        $repo = new DBPorudzbina($konekcija, 'PORUDZBINA');
        $zaglavlje = $repo->DajPorudzbinuPoBroju($brojPorudzbine);
        if (!$zaglavlje) {
            zatvoriKonekciju($konekcija);
            return null;
        }

        $stavke = $repo->DajStavkePorudzbine($brojPorudzbine);
        zatvoriKonekciju($konekcija);

        $ukupanIznos = 0;
        foreach ($stavke as $stavka) {
            $ukupanIznos += (float) $stavka['IznosStavke'];
        }

        return array(
            'zaglavlje' => $zaglavlje,
            'stavke' => $stavke,
            'ukupanIznos' => round($ukupanIznos, 2),
        );
    }

    public function dodaj($ulaz)
    {
        $konekcija = otvoriKonekciju();
        if (empty($konekcija->konekcijaDB)) {
            return array('status' => 500, 'podaci' => array('uspeh' => false, 'greske' => array('Konekcija sa bazom nije uspostavljena.')));
        }

        $artikalRepo = new DBArtikal($konekcija, 'ARTIKAL');
        $validator = new ValidacijaLagera($konekcija, 'ARTIKAL');
        $porudzbinaRepo = new DBPorudzbina($konekcija, 'PORUDZBINA');

        $podaciPorudzbine = $this->normalizujPodatkePorudzbine($ulaz);
        $stavke = isset($ulaz['stavke']) ? $ulaz['stavke'] : array();
        $porudzbina = napraviPorudzbinuIzPodataka($artikalRepo->DajMapuArtikala(), $podaciPorudzbine, $stavke);
        $greske = validirajZahtevPorudzbine($porudzbina, $validator, array(), $porudzbinaRepo);

        if (count($greske) > 0) {
            zatvoriKonekciju($konekcija);
            return array('status' => 422, 'podaci' => array('uspeh' => false, 'greske' => $greske));
        }

        $transakcija = new Transakcija($konekcija);
        $transakcija->ZapocniTransakciju();

        $greska1 = $porudzbinaRepo->DodajPorudzbinu($porudzbina);
        $greska2 = empty($greska1) ? $artikalRepo->UmanjiLagerZaPorudzbinu($porudzbina->stavke) : '';
        $ukupnaGreska = $greska1 . $greska2;
        $transakcija->ZavrsiTransakciju($ukupnaGreska);
        zatvoriKonekciju($konekcija);

        if (!empty($ukupnaGreska)) {
            return array('status' => 500, 'podaci' => array('uspeh' => false, 'greske' => array($ukupnaGreska)));
        }

        return array('status' => 201, 'podaci' => array('uspeh' => true, 'poruka' => 'Porudžbina je uspešno sačuvana.'));
    }

    public function izmeni($stariBrojPorudzbine, $ulaz)
    {
        $konekcija = otvoriKonekciju();
        if (empty($konekcija->konekcijaDB)) {
            return array('status' => 500, 'podaci' => array('uspeh' => false, 'greske' => array('Konekcija sa bazom nije uspostavljena.')));
        }

        $artikalRepo = new DBArtikal($konekcija, 'ARTIKAL');
        $validator = new ValidacijaLagera($konekcija, 'ARTIKAL');
        $porudzbinaRepo = new DBPorudzbina($konekcija, 'PORUDZBINA');
        $stareStavke = $porudzbinaRepo->DajStavkePorudzbine($stariBrojPorudzbine);

        if ($porudzbinaRepo->DajPorudzbinuPoBroju($stariBrojPorudzbine) === null) {
            zatvoriKonekciju($konekcija);
            return array('status' => 404, 'podaci' => array('uspeh' => false, 'greske' => array('Porudžbina nije pronađena.')));
        }

        $dodatniLager = array();
        foreach ($stareStavke as $stavka) {
            if (!isset($dodatniLager[$stavka['SifraArtikla']])) {
                $dodatniLager[$stavka['SifraArtikla']] = 0;
            }
            $dodatniLager[$stavka['SifraArtikla']] += (int) $stavka['Kolicina'];
        }

        $podaciPorudzbine = $this->normalizujPodatkePorudzbine($ulaz);
        $stavke = isset($ulaz['stavke']) ? $ulaz['stavke'] : array();
        $porudzbina = napraviPorudzbinuIzPodataka($artikalRepo->DajMapuArtikala(), $podaciPorudzbine, $stavke);
        $greske = validirajZahtevPorudzbine($porudzbina, $validator, $dodatniLager, $porudzbinaRepo, $stariBrojPorudzbine);

        if (count($greske) > 0) {
            zatvoriKonekciju($konekcija);
            return array('status' => 422, 'podaci' => array('uspeh' => false, 'greske' => $greske));
        }

        $transakcija = new Transakcija($konekcija);
        $transakcija->ZapocniTransakciju();

        $greska1 = $artikalRepo->VratiLagerZaPorudzbinu($stareStavke);
        $greska2 = empty($greska1) ? $porudzbinaRepo->IzmeniPorudzbinu($stariBrojPorudzbine, $porudzbina) : '';
        $greska3 = empty($greska1 . $greska2) ? $artikalRepo->UmanjiLagerZaPorudzbinu($porudzbina->stavke) : '';
        $ukupnaGreska = $greska1 . $greska2 . $greska3;
        $transakcija->ZavrsiTransakciju($ukupnaGreska);
        zatvoriKonekciju($konekcija);

        if (!empty($ukupnaGreska)) {
            return array('status' => 500, 'podaci' => array('uspeh' => false, 'greske' => array($ukupnaGreska)));
        }

        return array('status' => 200, 'podaci' => array('uspeh' => true, 'poruka' => 'Porudžbina je uspešno izmenjena.'));
    }

    public function obrisi($brojPorudzbine)
    {
        $konekcija = otvoriKonekciju();
        if (empty($konekcija->konekcijaDB)) {
            return array('status' => 500, 'podaci' => array('uspeh' => false, 'greske' => array('Konekcija sa bazom nije uspostavljena.')));
        }

        $artikalRepo = new DBArtikal($konekcija, 'ARTIKAL');
        $porudzbinaRepo = new DBPorudzbina($konekcija, 'PORUDZBINA');
        $stareStavke = $porudzbinaRepo->DajStavkePorudzbine($brojPorudzbine);

        if ($porudzbinaRepo->DajPorudzbinuPoBroju($brojPorudzbine) === null) {
            zatvoriKonekciju($konekcija);
            return array('status' => 404, 'podaci' => array('uspeh' => false, 'greske' => array('Porudžbina nije pronađena.')));
        }

        $transakcija = new Transakcija($konekcija);
        $transakcija->ZapocniTransakciju();
        $greska1 = $porudzbinaRepo->ObrisiPorudzbinu($brojPorudzbine);
        $greska2 = empty($greska1) ? $artikalRepo->VratiLagerZaPorudzbinu($stareStavke) : '';
        $ukupnaGreska = $greska1 . $greska2;
        $transakcija->ZavrsiTransakciju($ukupnaGreska);
        zatvoriKonekciju($konekcija);

        if (!empty($ukupnaGreska)) {
            return array('status' => 500, 'podaci' => array('uspeh' => false, 'greske' => array($ukupnaGreska)));
        }

        return array('status' => 200, 'podaci' => array('uspeh' => true, 'poruka' => 'Porudžbina je uspešno obrisana.'));
    }
}


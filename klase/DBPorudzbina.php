<?php
class DBPorudzbina extends Tabela
{
    protected function EscapirajPorudzbinu($porudzbina)
    {
        return array(
            'brojPorudzbine' => $this->EscapirajVrednost($porudzbina->brojPorudzbine),
            'kupac' => $this->EscapirajVrednost($porudzbina->kupac),
            'emailKupca' => $this->EscapirajVrednost($porudzbina->emailKupca),
            'datumPorudzbine' => $this->EscapirajVrednost($porudzbina->datumPorudzbine),
            'statusPorudzbine' => $this->EscapirajVrednost($porudzbina->statusPorudzbine),
            'nacinPlacanja' => $this->EscapirajVrednost($porudzbina->nacinPlacanja),
            'napomena' => $this->EscapirajVrednost($porudzbina->napomena),
        );
    }

    protected function NapraviUpitZaStavku($brojPorudzbine, $stavka)
    {
        $broj = $this->EscapirajVrednost($brojPorudzbine);
        $sifraArtikla = $this->EscapirajVrednost($stavka->artikal->sifraArtikla);
        $kolicina = (int) $stavka->kolicina;
        $jedinicnaCena = number_format((float) $stavka->jedinicnaCena, 2, '.', '');
        $popust = number_format((float) $stavka->popustProcenat, 2, '.', '');

        return "INSERT INTO `STAVKA_PORUDZBINE` (`BrojPorudzbine`, `SifraArtikla`, `Kolicina`, `JedinicnaCena`, `PopustProcenat`) VALUES ('" . $broj . "', '" . $sifraArtikla . "', " . $kolicina . ", " . $jedinicnaCena . ", " . $popust . ")";
    }

    protected function SnimiStavke($porudzbina)
    {
        foreach ($porudzbina->stavke as $stavka) {
            $greska = $this->IzvrsiAktivanSQLUpit($this->NapraviUpitZaStavku($porudzbina->brojPorudzbine, $stavka));
            if (!empty($greska)) {
                return $greska;
            }
        }

        return '';
    }

    public function DodajPorudzbinu($porudzbina)
    {
        $podaci = $this->EscapirajPorudzbinu($porudzbina);
        $upit = "INSERT INTO `PORUDZBINA` (`BrojPorudzbine`, `Kupac`, `EmailKupca`, `DatumPorudzbine`, `StatusPorudzbine`, `NacinPlacanja`, `Napomena`) VALUES ('" . $podaci['brojPorudzbine'] . "', '" . $podaci['kupac'] . "', '" . $podaci['emailKupca'] . "', '" . $podaci['datumPorudzbine'] . "', '" . $podaci['statusPorudzbine'] . "', '" . $podaci['nacinPlacanja'] . "', '" . $podaci['napomena'] . "')";

        $greska = $this->IzvrsiAktivanSQLUpit($upit);
        if (!empty($greska)) {
            return $greska;
        }

        return $this->SnimiStavke($porudzbina);
    }

    public function IzmeniPorudzbinu($stariBrojPorudzbine, $porudzbina)
    {
        $stariBroj = $this->EscapirajVrednost($stariBrojPorudzbine);
        $podaci = $this->EscapirajPorudzbinu($porudzbina);
        $upit = "UPDATE `PORUDZBINA` SET `BrojPorudzbine`='" . $podaci['brojPorudzbine'] . "', `Kupac`='" . $podaci['kupac'] . "', `EmailKupca`='" . $podaci['emailKupca'] . "', `DatumPorudzbine`='" . $podaci['datumPorudzbine'] . "', `StatusPorudzbine`='" . $podaci['statusPorudzbine'] . "', `NacinPlacanja`='" . $podaci['nacinPlacanja'] . "', `Napomena`='" . $podaci['napomena'] . "' WHERE `BrojPorudzbine`='" . $stariBroj . "'";

        $greska = $this->IzvrsiAktivanSQLUpit($upit);
        if (!empty($greska)) {
            return $greska;
        }

        $trenutniBroj = $this->EscapirajVrednost($porudzbina->brojPorudzbine);
        $greska = $this->IzvrsiAktivanSQLUpit("DELETE FROM `STAVKA_PORUDZBINE` WHERE `BrojPorudzbine`='" . $trenutniBroj . "'");
        if (!empty($greska)) {
            return $greska;
        }

        return $this->SnimiStavke($porudzbina);
    }

    public function ObrisiPorudzbinu($brojPorudzbine)
    {
        $broj = $this->EscapirajVrednost($brojPorudzbine);
        $greska = $this->IzvrsiAktivanSQLUpit("DELETE FROM `STAVKA_PORUDZBINE` WHERE `BrojPorudzbine`='" . $broj . "'");
        if (!empty($greska)) {
            return $greska;
        }

        return $this->IzvrsiAktivanSQLUpit("DELETE FROM `PORUDZBINA` WHERE `BrojPorudzbine`='" . $broj . "'");
    }

    public function DajPorudzbinuPoBroju($brojPorudzbine)
    {
        $broj = $this->EscapirajVrednost($brojPorudzbine);
        return $this->VratiPrviAsocijativniRed("SELECT * FROM `PORUDZBINA` WHERE `BrojPorudzbine`='" . $broj . "'");
    }

    public function DajStavkePorudzbine($brojPorudzbine)
    {
        $broj = $this->EscapirajVrednost($brojPorudzbine);
        $upit = "SELECT sp.`IDStavke`, sp.`BrojPorudzbine`, sp.`SifraArtikla`, a.`NazivArtikla`, a.`Kategorija`, a.`Brend`, sp.`Kolicina`, sp.`JedinicnaCena`, sp.`PopustProcenat`, ROUND(sp.`Kolicina` * sp.`JedinicnaCena` * (1 - sp.`PopustProcenat` / 100), 2) AS `IznosStavke` FROM `STAVKA_PORUDZBINE` sp INNER JOIN `ARTIKAL` a ON a.`SifraArtikla` = sp.`SifraArtikla` WHERE sp.`BrojPorudzbine`='" . $broj . "' ORDER BY sp.`IDStavke` ASC";

        return $this->VratiSveAsocijativno($upit);
    }

    public function DaLiPostojiPorudzbina($brojPorudzbine, $brojZaPreskok = '')
    {
        $broj = $this->EscapirajVrednost($brojPorudzbine);
        $upit = "SELECT COUNT(*) AS `Ukupno` FROM `PORUDZBINA` WHERE `BrojPorudzbine`='" . $broj . "'";

        if ($brojZaPreskok !== '') {
            $preskok = $this->EscapirajVrednost($brojZaPreskok);
            $upit .= " AND `BrojPorudzbine`<>'" . $preskok . "'";
        }

        $red = $this->VratiPrviAsocijativniRed($upit);
        if (!$red) {
            return false;
        }

        return (int) $red['Ukupno'] > 0;
    }
}

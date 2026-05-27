<?php
class DBArtikal extends Tabela
{
    public function DajSveArtikle()
    {
        $upit = "SELECT * FROM `ARTIKAL` ORDER BY `Kategorija` ASC, `NazivArtikla` ASC";
        return $this->VratiSveAsocijativno($upit);
    }

    public function UcitajSveArtikle()
    {
        $upit = "SELECT * FROM `ARTIKAL` ORDER BY `Kategorija` ASC, `NazivArtikla` ASC";
        $this->UcitajSvePoUpitu($upit);
    }

    public function DajArtikalPoSifri($sifraArtikla)
    {
        $sifra = $this->EscapirajVrednost($sifraArtikla);
        $upit = "SELECT * FROM `ARTIKAL` WHERE `SifraArtikla`='" . $sifra . "'";
        return $this->VratiPrviAsocijativniRed($upit);
    }

    public function DajMapuArtikala()
    {
        $mapa = array();
        $artikli = $this->DajSveArtikle();

        foreach ($artikli as $artikal) {
            $mapa[$artikal['SifraArtikla']] = $artikal;
        }

        return $mapa;
    }

    public function DajBrojArtikala()
    {
        $red = $this->VratiPrviAsocijativniRed("SELECT COUNT(*) AS `Ukupno` FROM `ARTIKAL`");
        if (!$red) {
            return 0;
        }

        return (int) $red['Ukupno'];
    }

    public function PromeniStanjeLagera($sifraArtikla, $promenaKolicine)
    {
        $sifra = $this->EscapirajVrednost($sifraArtikla);
        $promena = (int) $promenaKolicine;

        return $this->IzvrsiAktivanSQLUpit("UPDATE `ARTIKAL` SET `StanjeNaLageru` = `StanjeNaLageru` + (" . $promena . ") WHERE `SifraArtikla`='" . $sifra . "'");
    }

    public function UmanjiLagerZaPorudzbinu($stavke)
    {
        foreach ($stavke as $stavka) {
            $sifra = is_object($stavka) ? $stavka->artikal->sifraArtikla : $stavka['SifraArtikla'];
            $kolicina = is_object($stavka) ? (int) $stavka->kolicina : (int) $stavka['Kolicina'];
            $greska = $this->PromeniStanjeLagera($sifra, -$kolicina);
            if (!empty($greska)) {
                return $greska;
            }
        }

        return '';
    }

    public function VratiLagerZaPorudzbinu($stavke)
    {
        foreach ($stavke as $stavka) {
            $sifra = is_object($stavka) ? $stavka->artikal->sifraArtikla : $stavka['SifraArtikla'];
            $kolicina = is_object($stavka) ? (int) $stavka->kolicina : (int) $stavka['Kolicina'];
            $greska = $this->PromeniStanjeLagera($sifra, $kolicina);
            if (!empty($greska)) {
                return $greska;
            }
        }

        return '';
    }
}

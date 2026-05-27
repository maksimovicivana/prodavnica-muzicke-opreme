<?php
class ValidacijaLagera extends Tabela
{
    public function DaLiJeDozvoljenaKolicina($sifraArtikla, $trazenaKolicina)
    {
        $sifra = $this->EscapirajVrednost($sifraArtikla);
        $upit = "SELECT `StanjeNaLageru` FROM `ARTIKAL` WHERE `SifraArtikla`='" . $sifra . "'";
        $red = $this->VratiPrviAsocijativniRed($upit);

        if (!$red) {
            return false;
        }

        return (int) $red['StanjeNaLageru'] >= (int) $trazenaKolicina;
    }

    public function DajBrojDostupnihKomada($sifraArtikla)
    {
        $sifra = $this->EscapirajVrednost($sifraArtikla);
        $upit = "SELECT `StanjeNaLageru` FROM `ARTIKAL` WHERE `SifraArtikla`='" . $sifra . "'";
        $red = $this->VratiPrviAsocijativniRed($upit);

        if (!$red) {
            return 0;
        }

        return (int) $red['StanjeNaLageru'];
    }
}

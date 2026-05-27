<?php
class DBPorudzbinaV extends Tabela
{
    public function DajSvePorudzbine($pretraga, $status)
    {
        $uslovi = array();

        if (!empty($pretraga)) {
            $pretragaEsc = $this->EscapirajVrednost($pretraga);
            $uslovi[] = "(`BrojPorudzbine` LIKE '%" . $pretragaEsc . "%' OR `Kupac` LIKE '%" . $pretragaEsc . "%' OR `EmailKupca` LIKE '%" . $pretragaEsc . "%')";
        }

        if (!empty($status)) {
            $statusEsc = $this->EscapirajVrednost($status);
            $uslovi[] = "`StatusPorudzbine`='" . $statusEsc . "'";
        }

        $upit = "SELECT * FROM `PregledPorudzbinaSaUkupnimIznosom`";
        if (count($uslovi) > 0) {
            $upit .= " WHERE " . implode(' AND ', $uslovi);
        }
        $upit .= " ORDER BY `DatumPorudzbine` DESC, `BrojPorudzbine` DESC";

        return $this->VratiSveAsocijativno($upit);
    }

    public function DajPregledPorudzbine($brojPorudzbine)
    {
        $broj = $this->EscapirajVrednost($brojPorudzbine);
        $upit = "SELECT * FROM `PregledPorudzbinaSaUkupnimIznosom` WHERE `BrojPorudzbine`='" . $broj . "'";
        return $this->VratiPrviAsocijativniRed($upit);
    }

    public function DajBrojPorudzbina()
    {
        $red = $this->VratiPrviAsocijativniRed("SELECT COUNT(*) AS `Ukupno` FROM `PORUDZBINA`");
        if (!$red) {
            return 0;
        }

        return (int) $red['Ukupno'];
    }
}

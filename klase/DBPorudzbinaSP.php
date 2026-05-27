<?php
class DBPorudzbinaSP extends DBPorudzbina
{
    public function DodajPorudzbinu($porudzbina)
    {
        $podaci = $this->EscapirajPorudzbinu($porudzbina);

        $greska = $this->IzvrsiAktivanSQLUpit("SET @BrojPorudzbineParametar='" . $podaci['brojPorudzbine'] . "'");
        $greska .= $this->IzvrsiAktivanSQLUpit("SET @KupacParametar='" . $podaci['kupac'] . "'");
        $greska .= $this->IzvrsiAktivanSQLUpit("SET @EmailKupcaParametar='" . $podaci['emailKupca'] . "'");
        $greska .= $this->IzvrsiAktivanSQLUpit("SET @DatumPorudzbineParametar='" . $podaci['datumPorudzbine'] . "'");
        $greska .= $this->IzvrsiAktivanSQLUpit("SET @StatusPorudzbineParametar='" . $podaci['statusPorudzbine'] . "'");
        $greska .= $this->IzvrsiAktivanSQLUpit("SET @NacinPlacanjaParametar='" . $podaci['nacinPlacanja'] . "'");
        $greska .= $this->IzvrsiAktivanSQLUpit("SET @NapomenaParametar='" . $podaci['napomena'] . "'");
        $greska .= $this->IzvrsiAktivanSQLUpit("CALL `DodajPorudzbinu`(@BrojPorudzbineParametar, @KupacParametar, @EmailKupcaParametar, @DatumPorudzbineParametar, @StatusPorudzbineParametar, @NacinPlacanjaParametar, @NapomenaParametar)");

        if ($this->TipMYSQL == "mysqli") {
            while (mysqli_more_results($this->OtvorenaKonekcija->konekcijaDB)) {
                mysqli_next_result($this->OtvorenaKonekcija->konekcijaDB);
                $rezultat = mysqli_store_result($this->OtvorenaKonekcija->konekcijaDB);
                if ($rezultat instanceof mysqli_result) {
                    mysqli_free_result($rezultat);
                }
            }
        }

        if (!empty($greska)) {
            return $greska;
        }

        return $this->SnimiStavke($porudzbina);
    }
}

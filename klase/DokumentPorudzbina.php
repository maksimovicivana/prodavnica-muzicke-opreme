<?php
class DokumentPorudzbina
{
    public $brojPorudzbine;
    public $kupac;
    public $emailKupca;
    public $datumPorudzbine;
    public $statusPorudzbine;
    public $nacinPlacanja;
    public $napomena;
    public $stavke;

    public function __construct($podaci = array())
    {
        $this->brojPorudzbine = isset($podaci['brojPorudzbine']) ? $podaci['brojPorudzbine'] : '';
        $this->kupac = isset($podaci['kupac']) ? $podaci['kupac'] : '';
        $this->emailKupca = isset($podaci['emailKupca']) ? $podaci['emailKupca'] : '';
        $this->datumPorudzbine = isset($podaci['datumPorudzbine']) ? $podaci['datumPorudzbine'] : date('Y-m-d');
        $this->statusPorudzbine = isset($podaci['statusPorudzbine']) ? $podaci['statusPorudzbine'] : 'Nova';
        $this->nacinPlacanja = isset($podaci['nacinPlacanja']) ? $podaci['nacinPlacanja'] : 'Kartica';
        $this->napomena = isset($podaci['napomena']) ? $podaci['napomena'] : '';
        $this->stavke = array();
    }

    public function dodajStavku($stavka)
    {
        $this->stavke[] = $stavka;
    }

    public function izracunajUkupanIznos()
    {
        $ukupno = 0;

        foreach ($this->stavke as $stavka) {
            $ukupno += $stavka->izracunajIznos();
        }

        return round($ukupno, 2);
    }
}

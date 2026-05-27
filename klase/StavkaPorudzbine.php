<?php
class StavkaPorudzbine
{
    public $artikal;
    public $kolicina;
    public $jedinicnaCena;
    public $popustProcenat;

    public function __construct($artikal, $kolicina, $jedinicnaCena, $popustProcenat)
    {
        $this->artikal = $artikal;
        $this->kolicina = (int) $kolicina;
        $this->jedinicnaCena = (float) $jedinicnaCena;
        $this->popustProcenat = (float) $popustProcenat;
    }

    public function izracunajIznos()
    {
        $osnovica = $this->kolicina * $this->jedinicnaCena;
        $umanjenje = $osnovica * ($this->popustProcenat / 100);

        return round($osnovica - $umanjenje, 2);
    }
}

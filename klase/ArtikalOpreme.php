<?php
class ArtikalOpreme
{
    public $sifraArtikla;
    public $nazivArtikla;
    public $kategorija;
    public $brend;
    public $cena;
    public $stanjeNaLageru;
    public $opis;
    public $nazivSlike;

    public function __construct($podaci = array())
    {
        $this->sifraArtikla = isset($podaci['SifraArtikla']) ? $podaci['SifraArtikla'] : '';
        $this->nazivArtikla = isset($podaci['NazivArtikla']) ? $podaci['NazivArtikla'] : '';
        $this->kategorija = isset($podaci['Kategorija']) ? $podaci['Kategorija'] : '';
        $this->brend = isset($podaci['Brend']) ? $podaci['Brend'] : '';
        $this->cena = isset($podaci['Cena']) ? (float) $podaci['Cena'] : 0;
        $this->stanjeNaLageru = isset($podaci['StanjeNaLageru']) ? (int) $podaci['StanjeNaLageru'] : 0;
        $this->opis = isset($podaci['Opis']) ? $podaci['Opis'] : '';
        $this->nazivSlike = isset($podaci['NazivSlike']) ? $podaci['NazivSlike'] : '';
    }
}

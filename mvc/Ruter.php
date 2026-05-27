<?php
class Ruter
{
    private $rute;

    public function __construct()
    {
        $this->rute = array();
    }

    public function registruj($metod, $obrazac, $akcija)
    {
        $this->rute[] = array(
            'metod' => strtoupper($metod),
            'obrazac' => $obrazac,
            'akcija' => $akcija,
        );
    }

    public function obradi($metod, $putanja)
    {
        foreach ($this->rute as $ruta) {
            if ($ruta['metod'] !== strtoupper($metod)) {
                continue;
            }

            if (preg_match($ruta['obrazac'], $putanja, $poklapanja)) {
                array_shift($poklapanja);
                return call_user_func_array($ruta['akcija'], $poklapanja);
            }
        }

        return array(
            'status' => 404,
            'podaci' => array(
                'uspeh' => false,
                'poruka' => 'Ruta nije pronađena.',
            ),
        );
    }
}

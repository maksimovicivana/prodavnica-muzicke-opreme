<?php
class ApiPorudzbineKontroler
{
    private $model;
    private $prikaz;

    public function __construct($model, $prikaz)
    {
        $this->model = $model;
        $this->prikaz = $prikaz;
    }

    private function procitajJsonTelo()
    {
        $raw = file_get_contents('php://input');
        if ($raw === false || trim($raw) === '') {
            return array();
        }

        $podaci = json_decode($raw, true);
        if (!is_array($podaci)) {
            return null;
        }

        return $podaci;
    }

    public function lista()
    {
        $pretraga = isset($_GET['pretraga']) ? ocistiTekst($_GET['pretraga']) : '';
        $status = isset($_GET['status']) ? ocistiTekst($_GET['status']) : '';
        $lista = $this->model->dajListu($pretraga, $status);
        $this->prikaz->prikazi(200, array('uspeh' => true, 'podaci' => $lista));
    }

    public function proveriBrojPorudzbine($brojPorudzbine)
    {
        $brojZaPreskok = isset($_GET['preskoci']) ? ocistiTekst($_GET['preskoci']) : '';
        $rezultat = $this->model->proveriBrojPorudzbine($brojPorudzbine, $brojZaPreskok);
        $this->prikaz->prikazi($rezultat['status'], $rezultat['podaci']);
    }

    public function detalj($brojPorudzbine)
    {
        $detalj = $this->model->dajDetalj($brojPorudzbine);
        if ($detalj === null) {
            $this->prikaz->prikazi(404, array('uspeh' => false, 'poruka' => 'Porudžbina nije pronađena.'));
            return;
        }

        if (isset($detalj['greska'])) {
            $this->prikaz->prikazi(500, array('uspeh' => false, 'poruka' => $detalj['greska']));
            return;
        }

        $this->prikaz->prikazi(200, array('uspeh' => true, 'podaci' => $detalj));
    }

    public function kreiraj()
    {
        $ulaz = $this->procitajJsonTelo();
        if ($ulaz === null) {
            $this->prikaz->prikazi(400, array('uspeh' => false, 'poruka' => 'JSON telo zahteva nije ispravno.'));
            return;
        }

        $rezultat = $this->model->dodaj($ulaz);
        $this->prikaz->prikazi($rezultat['status'], $rezultat['podaci']);
    }

    public function izmeni($brojPorudzbine)
    {
        $ulaz = $this->procitajJsonTelo();
        if ($ulaz === null) {
            $this->prikaz->prikazi(400, array('uspeh' => false, 'poruka' => 'JSON telo zahteva nije ispravno.'));
            return;
        }

        $rezultat = $this->model->izmeni($brojPorudzbine, $ulaz);
        $this->prikaz->prikazi($rezultat['status'], $rezultat['podaci']);
    }

    public function obrisi($brojPorudzbine)
    {
        $rezultat = $this->model->obrisi($brojPorudzbine);
        $this->prikaz->prikazi($rezultat['status'], $rezultat['podaci']);
    }
}


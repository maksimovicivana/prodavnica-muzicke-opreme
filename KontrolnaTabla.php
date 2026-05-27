<?php
require_once __DIR__ . '/Aplikacija.php';

zahtevajPrijavu();

$brojArtikala = 0;
$brojPorudzbina = 0;
$istaknutiStatus = 'Nema podataka';

$konekcija = otvoriKonekciju();
if (!empty($konekcija->konekcijaDB)) {
    $artikalRepo = new DBArtikal($konekcija, 'ARTIKAL');
    $porudzbinaViewRepo = new DBPorudzbinaV($konekcija, 'PORUDZBINA');

    $brojArtikala = $artikalRepo->DajBrojArtikala();
    $brojPorudzbina = $porudzbinaViewRepo->DajBrojPorudzbina();

    $poslednjePorudzbine = $porudzbinaViewRepo->DajSvePorudzbine('', '');
    if (count($poslednjePorudzbine) > 0) {
        $istaknutiStatus = $poslednjePorudzbine[0]['StatusPorudzbine'];
    }
}
zatvoriKonekciju($konekcija);

$pageTitle = 'Radni panel prodavnice';
$pageLead = 'Pregled rada sa porudžbinama, artiklima i najvažnijim svakodnevnim akcijama.';
$showSidebar = true;

include __DIR__ . '/delovi/layoutPocetak.php';
include __DIR__ . '/delovi/kontrolnaTablaSadrzaj.php';
include __DIR__ . '/delovi/layoutKraj.php';


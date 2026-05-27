<?php
require_once __DIR__ . '/Aplikacija.php';

zahtevajPrijavu();

$aktivnaPretraga = isset($_GET['pretraga']) ? ocistiTekst($_GET['pretraga']) : '';
$aktivniStatus = isset($_GET['status']) ? ocistiTekst($_GET['status']) : '';
$porudzbine = array();

$konekcija = otvoriKonekciju();
if (!empty($konekcija->konekcijaDB)) {
    $viewRepo = new DBPorudzbinaV($konekcija, 'PORUDZBINA');
    $porudzbine = $viewRepo->DajSvePorudzbine($aktivnaPretraga, $aktivniStatus);
}
zatvoriKonekciju($konekcija);

$pageTitle = 'Štampa arhive porudžbina';
$pageLead = 'Pregled dokumentacije u formatu pogodnom za kontrolu i štampu.';
$showSidebar = true;

include __DIR__ . '/delovi/layoutPocetak.php';
include __DIR__ . '/delovi/porudzbineStampaSadrzaj.php';
include __DIR__ . '/delovi/layoutKraj.php';


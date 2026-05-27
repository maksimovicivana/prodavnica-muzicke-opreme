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

$pageTitle = 'Arhiva porudžbina';
$pageLead = 'Tabelarni pregled sa filtriranjem, izmenom, brisanjem i pripremom dokumenta za štampu.';
$showSidebar = true;

include __DIR__ . '/delovi/layoutPocetak.php';
include __DIR__ . '/delovi/porudzbineListaSadrzaj.php';
include __DIR__ . '/delovi/layoutKraj.php';


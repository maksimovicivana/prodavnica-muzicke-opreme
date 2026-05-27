<?php
require_once __DIR__ . '/Aplikacija.php';

odjaviKorisnika();

$artikliZaPocetnu = array();
$konekcija = otvoriKonekciju();
if (!empty($konekcija->konekcijaDB)) {
    $artikalRepo = new DBArtikal($konekcija, 'ARTIKAL');
    $artikliZaPocetnu = array_slice($artikalRepo->DajSveArtikle(), 0, 4);
}
zatvoriKonekciju($konekcija);

$pageTitle = 'Prodavnica muzičke opreme';
$pageLead = 'Centralno mesto za vođenje porudžbina, kupaca i asortimana prodavnice muzičke opreme.';
$publicMode = true;
$showSidebar = false;

include __DIR__ . '/delovi/layoutPocetak.php';
include __DIR__ . '/delovi/pocetnaProdavniceSadrzaj.php';
include __DIR__ . '/delovi/layoutKraj.php';


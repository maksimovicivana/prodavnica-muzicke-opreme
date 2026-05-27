<?php
require_once __DIR__ . '/Aplikacija.php';

zahtevajPrijavu();

$konekcija = otvoriKonekciju();
$artikli = array();
$formNote = 'Ovaj ekran služi kao dodatni način unosa iste porudžbine.';
if (!empty($konekcija->konekcijaDB)) {
    $artikalRepo = new DBArtikal($konekcija, 'ARTIKAL');
    $artikli = $artikalRepo->DajSveArtikle();
}
zatvoriKonekciju($konekcija);

$stariUnos = preuzmiStariUnosPorudzbine();
$formData = $stariUnos ? $stariUnos['formData'] : podrazumevaniPodaciPorudzbine();
$lineItems = $stariUnos ? $stariUnos['lineItems'] : array();

$pageTitle = 'Dodatni unos porudžbine';
$pageLead = 'Dodatni ekran za unos porudžbine sa istim podacima i istom poslovnom logikom.';
$showSidebar = true;
$formTitle = 'Dodatni unos porudžbine';
$formAction = 'PorudzbinaSnimiSP.php';
$submitLabel = 'Sačuvaj porudžbinu';

include __DIR__ . '/delovi/layoutPocetak.php';
include __DIR__ . '/delovi/porudzbinaUnosSPSadrzaj.php';
include __DIR__ . '/delovi/layoutKraj.php';


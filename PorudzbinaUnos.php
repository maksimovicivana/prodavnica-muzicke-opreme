<?php
require_once __DIR__ . '/Aplikacija.php';

zahtevajPrijavu();

$konekcija = otvoriKonekciju();
$artikli = array();
$formNote = '';
if (!empty($konekcija->konekcijaDB)) {
    $artikalRepo = new DBArtikal($konekcija, 'ARTIKAL');
    $artikli = $artikalRepo->DajSveArtikle();
} else {
    $formNote = 'Baza trenutno nije dostupna. Forma se prikazuje, ali unos neće biti moguć dok se konekcija ne uspostavi.';
}
zatvoriKonekciju($konekcija);

$stariUnos = preuzmiStariUnosPorudzbine();
$formData = $stariUnos ? $stariUnos['formData'] : podrazumevaniPodaciPorudzbine();
$lineItems = $stariUnos ? $stariUnos['lineItems'] : array();

$pageTitle = 'Nova porudžbina';
$pageLead = 'Unos zaglavlja dokumenta i više stavki na jednoj stranici uz automatski obračun ukupnog iznosa.';
$showSidebar = true;
$formTitle = 'Unos porudžbine';
$formAction = 'PorudzbinaSnimi.php';
$submitLabel = 'Sačuvaj porudžbinu';

include __DIR__ . '/delovi/layoutPocetak.php';
include __DIR__ . '/delovi/porudzbinaUnosSadrzaj.php';
include __DIR__ . '/delovi/layoutKraj.php';


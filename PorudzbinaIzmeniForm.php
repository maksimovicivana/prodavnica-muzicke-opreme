<?php
require_once __DIR__ . '/Aplikacija.php';

zahtevajPrijavu();

$stariBrojPorudzbine = '';
if (isset($_POST['BrojPorudzbine'])) {
    $stariBrojPorudzbine = ocistiTekst($_POST['BrojPorudzbine']);
} elseif (isset($_GET['broj'])) {
    $stariBrojPorudzbine = ocistiTekst($_GET['broj']);
}

if ($stariBrojPorudzbine === '') {
    postaviFlashPoruku('error', 'Nije prosleđen broj porudžbine za izmenu.');
    preusmeri('PorudzbineLista.php');
}

$konekcija = otvoriKonekciju();
if (empty($konekcija->konekcijaDB)) {
    postaviFlashPoruku('error', 'Konekcija sa bazom nije uspostavljena.');
    preusmeri('PorudzbineLista.php');
}

$artikalRepo = new DBArtikal($konekcija, 'ARTIKAL');
$porudzbinaRepo = new DBPorudzbina($konekcija, 'PORUDZBINA');
$artikli = $artikalRepo->DajSveArtikle();
$zaglavlje = $porudzbinaRepo->DajPorudzbinuPoBroju($stariBrojPorudzbine);
$stavkeIzBaze = $porudzbinaRepo->DajStavkePorudzbine($stariBrojPorudzbine);
zatvoriKonekciju($konekcija);

if (!$zaglavlje) {
    postaviFlashPoruku('error', 'Porudžbina nije pronađena.');
    preusmeri('PorudzbineLista.php');
}

$lineItems = array();
foreach ($stavkeIzBaze as $stavka) {
    $lineItems[] = array(
        'sifraArtikla' => $stavka['SifraArtikla'],
        'kolicina' => $stavka['Kolicina'],
        'jedinicnaCena' => $stavka['JedinicnaCena'],
        'popustProcenat' => $stavka['PopustProcenat'],
    );
}

$stariUnos = preuzmiStariUnosPorudzbine();
if ($stariUnos && isset($stariUnos['extra']['stariBroj']) && $stariUnos['extra']['stariBroj'] === $stariBrojPorudzbine) {
    $formData = $stariUnos['formData'];
    $lineItems = $stariUnos['lineItems'];
} else {
    $formData = podaciFormeIzPregledaPorudzbine($zaglavlje);
}

$pageTitle = 'Izmena porudžbine';
$pageLead = 'Ažuriranje zaglavlja i stavki već unete porudžbine uz ponovno preračunavanje lagera.';
$showSidebar = true;
$formTitle = 'Izmena porudžbine';
$formAction = 'PorudzbinaIzmeni.php';
$submitLabel = 'Sačuvaj izmene';
$hiddenFields = array(
    'StariBrojPorudzbine' => $stariBrojPorudzbine,
);
$formNote = 'Pri čuvanju izmene aplikacija vraća prethodne količine na lager, a zatim upisuje novo stanje.';

include __DIR__ . '/delovi/layoutPocetak.php';
include __DIR__ . '/delovi/porudzbinaIzmenaSadrzaj.php';
include __DIR__ . '/delovi/layoutKraj.php';


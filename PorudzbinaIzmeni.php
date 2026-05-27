<?php
require_once __DIR__ . '/Aplikacija.php';

zahtevajPrijavu();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    preusmeri('PorudzbineLista.php');
}

$stariBrojPorudzbine = isset($_POST['StariBrojPorudzbine']) ? ocistiTekst($_POST['StariBrojPorudzbine']) : '';
if ($stariBrojPorudzbine === '') {
    postaviFlashPoruku('error', 'Nedostaje identifikator porudžbine za izmenu.');
    preusmeri('PorudzbineLista.php');
}

$konekcija = otvoriKonekciju();
if (empty($konekcija->konekcijaDB)) {
    postaviFlashPoruku('error', 'Konekcija sa bazom nije uspostavljena.');
    preusmeri('PorudzbinaIzmeniForm.php?broj=' . urlencode($stariBrojPorudzbine));
}

$artikalRepo = new DBArtikal($konekcija, 'ARTIKAL');
$validator = new ValidacijaLagera($konekcija, 'ARTIKAL');
$porudzbinaRepo = new DBPorudzbina($konekcija, 'PORUDZBINA');

$stareStavke = $porudzbinaRepo->DajStavkePorudzbine($stariBrojPorudzbine);
$dodatniLager = array();
foreach ($stareStavke as $stavka) {
    if (!isset($dodatniLager[$stavka['SifraArtikla']])) {
        $dodatniLager[$stavka['SifraArtikla']] = 0;
    }
    $dodatniLager[$stavka['SifraArtikla']] += (int) $stavka['Kolicina'];
}

$formData = preuzmiPodatkeFormeIzPosta();
$lineItems = preuzmiStavkeIzPosta();
$porudzbina = napraviPorudzbinuIzZahteva($artikalRepo->DajMapuArtikala(), $formData);
$greske = validirajZahtevPorudzbine($porudzbina, $validator, $dodatniLager, $porudzbinaRepo, $stariBrojPorudzbine);

if (count($greske) > 0) {
    zatvoriKonekciju($konekcija);
    sacuvajStariUnosPorudzbine($formData, $lineItems, array('stariBroj' => $stariBrojPorudzbine));
    postaviFlashPoruku('error', implode(' ', $greske));
    preusmeri('PorudzbinaIzmeniForm.php?broj=' . urlencode($stariBrojPorudzbine));
}

$transakcija = new Transakcija($konekcija);
$transakcija->ZapocniTransakciju();

$greska1 = $artikalRepo->VratiLagerZaPorudzbinu($stareStavke);
$greska2 = empty($greska1) ? $porudzbinaRepo->IzmeniPorudzbinu($stariBrojPorudzbine, $porudzbina) : '';
$greska3 = empty($greska1 . $greska2) ? $artikalRepo->UmanjiLagerZaPorudzbinu($porudzbina->stavke) : '';
$ukupnaGreska = $greska1 . $greska2 . $greska3;
$transakcija->ZavrsiTransakciju($ukupnaGreska);
zatvoriKonekciju($konekcija);

if (!empty($ukupnaGreska)) {
    sacuvajStariUnosPorudzbine($formData, $lineItems, array('stariBroj' => $stariBrojPorudzbine));
    postaviFlashPoruku('error', 'Izmene nisu sačuvane. ' . $ukupnaGreska);
    preusmeri('PorudzbinaIzmeniForm.php?broj=' . urlencode($stariBrojPorudzbine));
}

postaviFlashPoruku('success', 'Porudžbina je uspešno izmenjena.');
preusmeri('PorudzbineLista.php');


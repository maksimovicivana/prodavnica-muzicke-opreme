<?php
require_once __DIR__ . '/Aplikacija.php';

zahtevajPrijavu();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    preusmeri('PorudzbinaUnosSP.php');
}

$konekcija = otvoriKonekciju();
if (empty($konekcija->konekcijaDB)) {
    postaviFlashPoruku('error', 'Konekcija sa bazom nije uspostavljena.');
    preusmeri('PorudzbinaUnosSP.php');
}

$artikalRepo = new DBArtikal($konekcija, 'ARTIKAL');
$validator = new ValidacijaLagera($konekcija, 'ARTIKAL');
$porudzbinaRepo = new DBPorudzbinaSP($konekcija, 'PORUDZBINA');

$formData = preuzmiPodatkeFormeIzPosta();
$lineItems = preuzmiStavkeIzPosta();
$porudzbina = napraviPorudzbinuIzZahteva($artikalRepo->DajMapuArtikala(), $formData);
$greske = validirajZahtevPorudzbine($porudzbina, $validator, array(), $porudzbinaRepo);

if (count($greske) > 0) {
    zatvoriKonekciju($konekcija);
    sacuvajStariUnosPorudzbine($formData, $lineItems);
    postaviFlashPoruku('error', implode(' ', $greske));
    preusmeri('PorudzbinaUnosSP.php');
}

$transakcija = new Transakcija($konekcija);
$transakcija->ZapocniTransakciju();

$greska1 = $porudzbinaRepo->DodajPorudzbinu($porudzbina);
$greska2 = empty($greska1) ? $artikalRepo->UmanjiLagerZaPorudzbinu($porudzbina->stavke) : '';
$ukupnaGreska = $greska1 . $greska2;
$transakcija->ZavrsiTransakciju($ukupnaGreska);
zatvoriKonekciju($konekcija);

if (!empty($ukupnaGreska)) {
    sacuvajStariUnosPorudzbine($formData, $lineItems);
    postaviFlashPoruku('error', 'Porudžbina nije sačuvana. ' . $ukupnaGreska);
    preusmeri('PorudzbinaUnosSP.php');
}

postaviFlashPoruku('success', 'Porudžbina je uspešno sačuvana.');
preusmeri('PorudzbineLista.php');


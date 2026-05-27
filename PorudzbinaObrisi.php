<?php
require_once __DIR__ . '/Aplikacija.php';

zahtevajPrijavu();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    preusmeri('PorudzbineLista.php');
}

$brojPorudzbine = isset($_POST['BrojPorudzbine']) ? ocistiTekst($_POST['BrojPorudzbine']) : '';
if ($brojPorudzbine === '') {
    postaviFlashPoruku('error', 'Nije prosleđen broj porudžbine za brisanje.');
    preusmeri('PorudzbineLista.php');
}

$konekcija = otvoriKonekciju();
if (empty($konekcija->konekcijaDB)) {
    postaviFlashPoruku('error', 'Konekcija sa bazom nije uspostavljena.');
    preusmeri('PorudzbineLista.php');
}

$artikalRepo = new DBArtikal($konekcija, 'ARTIKAL');
$porudzbinaRepo = new DBPorudzbina($konekcija, 'PORUDZBINA');
$stareStavke = $porudzbinaRepo->DajStavkePorudzbine($brojPorudzbine);

$transakcija = new Transakcija($konekcija);
$transakcija->ZapocniTransakciju();

$greska1 = $porudzbinaRepo->ObrisiPorudzbinu($brojPorudzbine);
$greska2 = empty($greska1) ? $artikalRepo->VratiLagerZaPorudzbinu($stareStavke) : '';
$ukupnaGreska = $greska1 . $greska2;
$transakcija->ZavrsiTransakciju($ukupnaGreska);
zatvoriKonekciju($konekcija);

if (!empty($ukupnaGreska)) {
    postaviFlashPoruku('error', 'Porudžbina nije obrisana. ' . $ukupnaGreska);
    preusmeri('PorudzbineLista.php');
}

postaviFlashPoruku('success', 'Porudžbina je uspešno obrisana.');
preusmeri('PorudzbineLista.php');


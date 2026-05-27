<?php
require_once __DIR__ . '/Aplikacija.php';

pokreniSesiju();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    preusmeri('OperaterPrijava.php');
}

$loginUserName = isset($_POST['korisnickoIme']) ? ocistiTekst($_POST['korisnickoIme']) : '';
$loginPassword = isset($_POST['sifra']) ? ocistiTekst($_POST['sifra']) : '';

$objKonekcija = otvoriKonekciju();
if (empty($objKonekcija->konekcijaDB)) {
    postaviFlashPoruku('error', 'Konekcija sa bazom nije uspostavljena. Proverite parametre konekcije.');
    preusmeri('OperaterPrijava.php');
}

$objKorisnik = new DBKorisnik($objKonekcija, 'KORISNIK');
$postojiKorisnik = $objKorisnik->DaLiPostojiKorisnik($loginUserName, $loginPassword);

if ($postojiKorisnik === 'DA') {
    $_SESSION['prezime'] = $objKorisnik->DajPrezimePrijavljenogKorisnika($loginUserName, $loginPassword);
    $_SESSION['ime'] = $objKorisnik->DajImePrijavljenogKorisnika($loginUserName, $loginPassword);
    $_SESSION['idkorisnika'] = $objKorisnik->DajIDPrijavljenogKorisnika($loginUserName, $loginPassword);
    $_SESSION['korisnik'] = $objKorisnik->DajImePrezimePrijavljenogKorisnika($loginUserName, $loginPassword);
    zatvoriKonekciju($objKonekcija);
    postaviFlashPoruku('success', 'Uspešno ste prijavljeni u radni panel.');
    preusmeri('KontrolnaTabla.php');
}

zatvoriKonekciju($objKonekcija);
postaviFlashPoruku('error', 'Korisničko ime ili šifra nisu ispravni.');
preusmeri('OperaterPrijava.php');


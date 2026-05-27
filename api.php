<?php
require_once __DIR__ . '/Aplikacija.php';
require_once __DIR__ . '/mvc/Ruter.php';
require_once __DIR__ . '/mvc/Prikazi/JsonPrikaz.php';
require_once __DIR__ . '/mvc/Modeli/PorudzbinaModel.php';
require_once __DIR__ . '/mvc/Kontroleri/ApiPorudzbineKontroler.php';

pokreniSesiju();

$jsonPrikaz = new JsonPrikaz();
if (empty($_SESSION['korisnik'])) {
    $jsonPrikaz->prikazi(401, array(
        'uspeh' => false,
        'poruka' => 'Pristup REST servisu je dozvoljen samo prijavljenom zaposlenom.',
    ));
    exit;
}

$ruter = new Ruter();
$kontroler = new ApiPorudzbineKontroler(new PorudzbinaModel(), $jsonPrikaz);

$ruter->registruj('GET', '#^/porudzbine$#', array($kontroler, 'lista'));
$ruter->registruj('GET', '#^/porudzbine/([^/]+)$#', array($kontroler, 'detalj'));
$ruter->registruj('GET', '#^/provera-broja-porudzbine/([^/]+)$#', array($kontroler, 'proveriBrojPorudzbine'));
$ruter->registruj('POST', '#^/porudzbine$#', array($kontroler, 'kreiraj'));
$ruter->registruj('PUT', '#^/porudzbine/([^/]+)$#', array($kontroler, 'izmeni'));
$ruter->registruj('DELETE', '#^/porudzbine/([^/]+)$#', array($kontroler, 'obrisi'));

$putanja = '/';
if (!empty($_SERVER['PATH_INFO'])) {
    $putanja = $_SERVER['PATH_INFO'];
} elseif (isset($_GET['putanja'])) {
    $putanja = '/' . ltrim((string) $_GET['putanja'], '/');
}

$rezultat = $ruter->obradi($_SERVER['REQUEST_METHOD'], $putanja);
if (is_array($rezultat) && isset($rezultat['status'], $rezultat['podaci'])) {
    $jsonPrikaz->prikazi($rezultat['status'], $rezultat['podaci']);
}


<?php
require_once __DIR__ . '/Aplikacija.php';

pokreniSesiju();
if (!empty($_SESSION['korisnik'])) {
    preusmeri('KontrolnaTabla.php');
}

$pageTitle = 'Prijava zaposlenog';
$pageLead = 'Prijavite se kako biste unosili, pratili i ažurirali porudžbine prodavnice.';
$publicMode = true;
$showSidebar = false;

include __DIR__ . '/delovi/layoutPocetak.php';
include __DIR__ . '/delovi/operaterPrijavaSadrzaj.php';
include __DIR__ . '/delovi/layoutKraj.php';


<?php
require_once __DIR__ . '/Aplikacija.php';

zahtevajPrijavu();

$brojPorudzbine = '';
if (isset($_POST['BrojPorudzbineFilter'])) {
    $brojPorudzbine = ocistiTekst($_POST['BrojPorudzbineFilter']);
} elseif (isset($_GET['broj'])) {
    $brojPorudzbine = ocistiTekst($_GET['broj']);
}

$zaglavljePorudzbine = array();
$stavkePorudzbine = array();
$ukupanIznos = 0;

if ($brojPorudzbine !== '') {
    $konekcija = otvoriKonekciju();
    if (!empty($konekcija->konekcijaDB)) {
        $repo = new DBPorudzbina($konekcija, 'PORUDZBINA');
        $zaglavljePorudzbine = $repo->DajPorudzbinuPoBroju($brojPorudzbine);
        $stavkePorudzbine = $repo->DajStavkePorudzbine($brojPorudzbine);
        foreach ($stavkePorudzbine as $stavka) {
            $ukupanIznos += (float) $stavka['IznosStavke'];
        }
    }
    zatvoriKonekciju($konekcija);
}

$pageTitle = 'Detalj porudžbine';
$pageLead = 'Dokument spreman za štampu sa svim stavkama i ukupnim iznosom.';
$showSidebar = false;
$isPrintPage = true;

include __DIR__ . '/delovi/layoutPocetak.php';
include __DIR__ . '/delovi/stampaPorudzbineSadrzaj.php';
include __DIR__ . '/delovi/layoutKraj.php';


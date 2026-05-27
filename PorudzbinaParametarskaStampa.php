<?php
require_once __DIR__ . '/Aplikacija.php';

zahtevajPrijavu();

$pageTitle = 'Parametarska štampa';
$pageLead = 'Unesite broj porudžbine i otvorite detaljan dokument u zasebnom prikazu za štampu.';
$showSidebar = true;

include __DIR__ . '/delovi/layoutPocetak.php';
include __DIR__ . '/delovi/porudzbinaParametarskaStampaSadrzaj.php';
include __DIR__ . '/delovi/layoutKraj.php';


<?php
require_once __DIR__ . '/../Aplikacija.php';
pokreniSesiju();

if (!headers_sent()) {
    header('Content-Type: text/html; charset=UTF-8');
}

$pageTitle = isset($pageTitle) ? $pageTitle : 'Prodavnica muzičke opreme';
$pageLead = isset($pageLead) ? $pageLead : 'Pregled i obrada porudžbina, artikala i izveštaja.';
$showSidebar = !empty($showSidebar);
$isPrintPage = !empty($isPrintPage);
$publicMode = !empty($publicMode);
$ulogovaniKorisnik = isset($_SESSION['korisnik']) ? $_SESSION['korisnik'] : '';
$flash = preuzmiFlashPoruku();
?>
<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo bezbedanTekst($pageTitle); ?></title>
<?php include __DIR__ . '/../css/stil.php'; ?>
</head>
<body class="<?php echo $isPrintPage ? 'print-body' : 'app-body'; ?>">
<div class="app-shell<?php echo $isPrintPage ? ' app-shell-print' : ''; ?>">
    <header class="app-header">
        <div class="brand-block">
            <div class="brand-kicker">Radni panel prodavnice</div>
            <a class="brand-title" href="<?php echo $ulogovaniKorisnik !== '' ? 'KontrolnaTabla.php' : 'PocetnaProdavnice.php'; ?>">Prodavnica muzičke opreme</a>
            <div class="brand-subtitle">Gitare, klavijature, studijska oprema i porudžbine na jednom mestu.</div>
        </div>
        <nav class="top-nav">
            <?php if ($isPrintPage) { ?>
                <a href="javascript:window.print()">Štampaj</a>
                <a href="PorudzbineLista.php">Nazad u listu</a>
            <?php } elseif ($ulogovaniKorisnik !== '') { ?>
                <span class="top-nav-user"><?php echo bezbedanTekst($ulogovaniKorisnik); ?></span>
                <a href="KontrolnaTabla.php">Radni panel</a>
                <a href="PorudzbinaUnos.php">Nova porudžbina</a>
                <a href="PorudzbineLista.php">Arhiva</a>
                <a href="PocetnaProdavnice.php">Odjava</a>
            <?php } else { ?>
                <a href="PocetnaProdavnice.php">Početna</a>
                <a href="OperaterPrijava.php">Prijava</a>
            <?php } ?>
        </nav>
    </header>

    <?php if (!$isPrintPage) { ?>
        <section class="page-hero<?php echo $publicMode ? ' public-hero' : ''; ?>">
            <div>
                <p class="page-eyebrow"><?php echo $publicMode ? 'Dobro došli' : 'Panel zaposlenog'; ?></p>
                <h1><?php echo bezbedanTekst($pageTitle); ?></h1>
                <p><?php echo bezbedanTekst($pageLead); ?></p>
            </div>
            <div class="hero-accent">
                <div class="hero-accent-line"></div>
                <div class="hero-accent-copy">Pregledajte porudžbine, pratite artikle i organizujte svakodnevni rad prodavnice na jednom mestu.</div>
            </div>
        </section>
    <?php } ?>

    <?php if ($flash) { ?>
        <div class="flash-message flash-<?php echo bezbedanTekst($flash['type']); ?>">
            <?php echo bezbedanTekst($flash['message']); ?>
        </div>
    <?php } ?>

    <div class="page-layout<?php echo $showSidebar ? ' page-layout-sidebar' : ''; ?>">
        <?php if ($showSidebar) { ?>
            <?php include __DIR__ . '/adminMeni.php'; ?>
        <?php } ?>
        <main class="content-surface<?php echo $isPrintPage ? ' content-surface-print' : ''; ?>">


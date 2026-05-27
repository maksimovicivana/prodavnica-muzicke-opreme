<?php
require_once __DIR__ . '/klase/BaznaKonekcija.php';
require_once __DIR__ . '/klase/BaznaTabela.php';
require_once __DIR__ . '/klase/BaznaTransakcija.php';
require_once __DIR__ . '/klase/ArtikalOpreme.php';
require_once __DIR__ . '/klase/StavkaPorudzbine.php';
require_once __DIR__ . '/klase/DokumentPorudzbina.php';
require_once __DIR__ . '/klase/DBArtikal.php';
require_once __DIR__ . '/klase/DBPorudzbina.php';
require_once __DIR__ . '/klase/DBPorudzbinaSP.php';
require_once __DIR__ . '/klase/DBPorudzbinaV.php';
require_once __DIR__ . '/klase/DBKorisnik.php';
require_once __DIR__ . '/klase/ValidacijaLagera.php';

function pokreniSesiju()
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        $putanjaSesija = __DIR__ . '/privremeno/sesije';
        if (!is_dir($putanjaSesija)) {
            mkdir($putanjaSesija, 0777, true);
        }
        session_save_path($putanjaSesija);
        session_start();
    }
}

function preusmeri($path)
{
    header('Location: ' . $path);
    exit;
}

function zahtevajPrijavu()
{
    pokreniSesiju();

    if (empty($_SESSION['korisnik'])) {
        preusmeri('PocetnaProdavnice.php');
    }
}

function odjaviKorisnika()
{
    pokreniSesiju();
    session_unset();
    session_destroy();
}

function otvoriKonekciju()
{
    $konekcija = new Konekcija(__DIR__ . '/klase/BaznaParametriKonekcije.xml');
    $konekcija->connect();

    return $konekcija;
}

function zatvoriKonekciju($konekcija)
{
    if ($konekcija && !empty($konekcija->konekcijaDB)) {
        $konekcija->disconnect();
    }
}

function bezbedanTekst($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function ocistiTekst($value)
{
    return trim((string) $value);
}

function procitajDecimalniBroj($value)
{
    $normalizovana = trim((string) $value);
    $normalizovana = str_replace(' ', '', $normalizovana);

    if (strpos($normalizovana, ',') !== false && strpos($normalizovana, '.') !== false) {
        $normalizovana = str_replace('.', '', $normalizovana);
        $normalizovana = str_replace(',', '.', $normalizovana);
    } elseif (strpos($normalizovana, ',') !== false) {
        $normalizovana = str_replace(',', '.', $normalizovana);
    }

    if ($normalizovana === '') {
        return 0;
    }

    return (float) $normalizovana;
}

function formatirajValutu($iznos)
{
    return number_format((float) $iznos, 2, ',', '.') . ' RSD';
}

function formatirajDatum($datum)
{
    if (empty($datum)) {
        return '';
    }

    $vreme = strtotime($datum);
    if ($vreme === false) {
        return $datum;
    }

    return date('d.m.Y.', $vreme);
}

function opcijeStatusa()
{
    return array('Nova', 'Potvrđena', 'Spremna za isporuku', 'Završena');
}

function opcijePlacanja()
{
    return array('Kartica', 'Pouzećem', 'Predračun');
}

function klasaBedzaStatusa($status)
{
    $mapa = array(
        'Nova' => 'badge-neutral',
        'Potvrđena' => 'badge-warm',
        'Spremna za isporuku' => 'badge-info',
        'Završena' => 'badge-success',
    );

    return isset($mapa[$status]) ? $mapa[$status] : 'badge-neutral';
}

function postaviFlashPoruku($type, $message)
{
    pokreniSesiju();
    $_SESSION['flash'] = array(
        'type' => $type,
        'message' => $message,
    );
}

function preuzmiFlashPoruku()
{
    pokreniSesiju();

    if (empty($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function sacuvajStariUnosPorudzbine($formData, $lineItems, $extra = array())
{
    pokreniSesiju();
    $_SESSION['stari_unos_porudzbine'] = array(
        'formData' => $formData,
        'lineItems' => $lineItems,
        'extra' => $extra,
    );
}

function preuzmiStariUnosPorudzbine()
{
    pokreniSesiju();

    if (empty($_SESSION['stari_unos_porudzbine'])) {
        return null;
    }

    $stariUnos = $_SESSION['stari_unos_porudzbine'];
    unset($_SESSION['stari_unos_porudzbine']);

    return $stariUnos;
}

function podrazumevaniPodaciPorudzbine()
{
    return array(
        'brojPorudzbine' => 'POR-' . date('Ymd-His'),
        'kupac' => '',
        'emailKupca' => '',
        'datumPorudzbine' => date('Y-m-d'),
        'statusPorudzbine' => 'Nova',
        'nacinPlacanja' => 'Kartica',
        'napomena' => '',
    );
}

function preuzmiStavkeIzPosta()
{
    $sifre = isset($_POST['sifraArtikla']) ? (array) $_POST['sifraArtikla'] : array();
    $kolicine = isset($_POST['kolicina']) ? (array) $_POST['kolicina'] : array();
    $cene = isset($_POST['jedinicnaCena']) ? (array) $_POST['jedinicnaCena'] : array();
    $popusti = isset($_POST['popustProcenat']) ? (array) $_POST['popustProcenat'] : array();

    $stavke = array();
    $brojStavki = max(count($sifre), count($kolicine), count($cene), count($popusti));

    for ($i = 0; $i < $brojStavki; $i++) {
        $sifra = isset($sifre[$i]) ? ocistiTekst($sifre[$i]) : '';
        $kolicina = isset($kolicine[$i]) ? (int) $kolicine[$i] : 0;
        $jedinicnaCena = isset($cene[$i]) ? procitajDecimalniBroj($cene[$i]) : 0;
        $popust = isset($popusti[$i]) ? procitajDecimalniBroj($popusti[$i]) : 0;

        if ($sifra === '' && $kolicina === 0 && $jedinicnaCena === 0 && $popust === 0) {
            continue;
        }

        $stavke[] = array(
            'sifraArtikla' => $sifra,
            'kolicina' => $kolicina,
            'jedinicnaCena' => $jedinicnaCena,
            'popustProcenat' => $popust,
        );
    }

    return $stavke;
}

function normalizujStavke($stavkeUlaz)
{
    $stavke = array();

    foreach ((array) $stavkeUlaz as $stavkaUlaz) {
        $sifra = isset($stavkaUlaz['sifraArtikla']) ? ocistiTekst($stavkaUlaz['sifraArtikla']) : '';
        $kolicina = isset($stavkaUlaz['kolicina']) ? (int) $stavkaUlaz['kolicina'] : 0;
        $jedinicnaCena = isset($stavkaUlaz['jedinicnaCena']) ? procitajDecimalniBroj($stavkaUlaz['jedinicnaCena']) : 0;
        $popust = isset($stavkaUlaz['popustProcenat']) ? procitajDecimalniBroj($stavkaUlaz['popustProcenat']) : 0;

        if ($sifra === '' && $kolicina === 0 && $jedinicnaCena === 0 && $popust === 0) {
            continue;
        }

        $stavke[] = array(
            'sifraArtikla' => $sifra,
            'kolicina' => $kolicina,
            'jedinicnaCena' => $jedinicnaCena,
            'popustProcenat' => $popust,
        );
    }

    return $stavke;
}

function napraviPorudzbinuIzPodataka($mapaArtikala, $podaciPorudzbine, $stavke)
{
    $podaci = array_merge(podrazumevaniPodaciPorudzbine(), $podaciPorudzbine);
    $porudzbina = new DokumentPorudzbina($podaci);

    foreach (normalizujStavke($stavke) as $stavkaPodaci) {
        $sifraArtikla = $stavkaPodaci['sifraArtikla'];
        if (!isset($mapaArtikala[$sifraArtikla])) {
            continue;
        }

        $artikal = new ArtikalOpreme($mapaArtikala[$sifraArtikla]);
        $porudzbina->dodajStavku(new StavkaPorudzbine(
            $artikal,
            $stavkaPodaci['kolicina'],
            $stavkaPodaci['jedinicnaCena'],
            $stavkaPodaci['popustProcenat']
        ));
    }

    return $porudzbina;
}

function napraviPorudzbinuIzZahteva($mapaArtikala, $overrideData)
{
    return napraviPorudzbinuIzPodataka($mapaArtikala, $overrideData, preuzmiStavkeIzPosta());
}

function preuzmiPodatkeFormeIzPosta()
{
    return array(
        'brojPorudzbine' => isset($_POST['brojPorudzbine']) ? ocistiTekst($_POST['brojPorudzbine']) : '',
        'kupac' => isset($_POST['kupac']) ? ocistiTekst($_POST['kupac']) : '',
        'emailKupca' => isset($_POST['emailKupca']) ? ocistiTekst($_POST['emailKupca']) : '',
        'datumPorudzbine' => isset($_POST['datumPorudzbine']) ? ocistiTekst($_POST['datumPorudzbine']) : date('Y-m-d'),
        'statusPorudzbine' => isset($_POST['statusPorudzbine']) ? ocistiTekst($_POST['statusPorudzbine']) : 'Nova',
        'nacinPlacanja' => isset($_POST['nacinPlacanja']) ? ocistiTekst($_POST['nacinPlacanja']) : 'Kartica',
        'napomena' => isset($_POST['napomena']) ? ocistiTekst($_POST['napomena']) : '',
    );
}

function validirajZahtevPorudzbine($porudzbina, $validacijaObjekat, $dodatniLager = array(), $repoPorudzbina = null, $brojZaPreskok = '')
{
    $greske = array();

    if (ocistiTekst($porudzbina->brojPorudzbine) === '') {
        $greske[] = 'Broj porudžbine je obavezan.';
    }

    if (ocistiTekst($porudzbina->kupac) === '') {
        $greske[] = 'Naziv kupca ili kontakt osobe je obavezan.';
    }

    if (!filter_var($porudzbina->emailKupca, FILTER_VALIDATE_EMAIL)) {
        $greske[] = 'E-mail kupca mora biti u ispravnom formatu.';
    }

    if (ocistiTekst($porudzbina->datumPorudzbine) === '') {
        $greske[] = 'Datum porudžbine je obavezan.';
    }

    if (strlen($porudzbina->brojPorudzbine) > 20) {
        $greske[] = 'Broj porudžbine ne sme biti duži od 20 karaktera.';
    }

    if (strlen($porudzbina->kupac) < 3 || strlen($porudzbina->kupac) > 100) {
        $greske[] = 'Naziv kupca mora imati između 3 i 100 karaktera.';
    }

    if (strlen($porudzbina->emailKupca) > 100) {
        $greske[] = 'E-mail kupca ne sme biti duži od 100 karaktera.';
    }

    if (strlen($porudzbina->napomena) > 255) {
        $greske[] = 'Napomena ne sme biti duža od 255 karaktera.';
    }

    if (!in_array($porudzbina->statusPorudzbine, opcijeStatusa(), true)) {
        $greske[] = 'Status porudžbine mora biti iz dozvoljenog skupa vrednosti.';
    }

    if (!in_array($porudzbina->nacinPlacanja, opcijePlacanja(), true)) {
        $greske[] = 'Način plaćanja mora biti iz dozvoljenog skupa vrednosti.';
    }

    if ($repoPorudzbina && $repoPorudzbina->DaLiPostojiPorudzbina($porudzbina->brojPorudzbine, $brojZaPreskok)) {
        $greske[] = 'Porudžbina sa tim brojem već postoji.';
    }

    if (count($porudzbina->stavke) === 0) {
        $greske[] = 'Potrebno je uneti najmanje jednu stavku porudžbine.';
    }

    $vecVidjeniArtikli = array();
    foreach ($porudzbina->stavke as $stavka) {
        if ($stavka->kolicina < 1) {
            $greske[] = 'Količina na svakoj stavci mora biti veća od nule.';
        }

        if ($stavka->jedinicnaCena <= 0) {
            $greske[] = 'Jedinična cena mora biti veća od nule.';
        }

        if ($stavka->popustProcenat < 0 || $stavka->popustProcenat > 100) {
            $greske[] = 'Popust mora biti između 0 i 100 procenata.';
        }

        if (isset($vecVidjeniArtikli[$stavka->artikal->sifraArtikla])) {
            $greske[] = 'Isti artikal nije moguće uneti više puta u jednoj porudžbini.';
        } else {
            $vecVidjeniArtikli[$stavka->artikal->sifraArtikla] = true;
        }

        $raspolozivo = $validacijaObjekat->DajBrojDostupnihKomada($stavka->artikal->sifraArtikla);
        if (isset($dodatniLager[$stavka->artikal->sifraArtikla])) {
            $raspolozivo += (int) $dodatniLager[$stavka->artikal->sifraArtikla];
        }

        if ($raspolozivo < (int) $stavka->kolicina) {
            $greske[] = 'Za artikal ' . $stavka->artikal->nazivArtikla . ' nema dovoljno komada na lageru.';
        }
    }

    return array_values(array_unique($greske));
}

function podaciFormeIzPregledaPorudzbine($red)
{
    return array(
        'brojPorudzbine' => isset($red['BrojPorudzbine']) ? $red['BrojPorudzbine'] : '',
        'kupac' => isset($red['Kupac']) ? $red['Kupac'] : '',
        'emailKupca' => isset($red['EmailKupca']) ? $red['EmailKupca'] : '',
        'datumPorudzbine' => isset($red['DatumPorudzbine']) ? $red['DatumPorudzbine'] : date('Y-m-d'),
        'statusPorudzbine' => isset($red['StatusPorudzbine']) ? $red['StatusPorudzbine'] : 'Nova',
        'nacinPlacanja' => isset($red['NacinPlacanja']) ? $red['NacinPlacanja'] : 'Kartica',
        'napomena' => isset($red['Napomena']) ? $red['Napomena'] : '',
    );
}


<?php
$formTitle = isset($formTitle) ? $formTitle : 'Porudžbina';
$formAction = isset($formAction) ? $formAction : 'PorudzbinaSnimi.php';
$submitLabel = isset($submitLabel) ? $submitLabel : 'Sačuvaj porudžbinu';
$formData = isset($formData) ? $formData : podrazumevaniPodaciPorudzbine();
$lineItems = isset($lineItems) && count($lineItems) > 0 ? $lineItems : array(
    array(
        'sifraArtikla' => '',
        'kolicina' => 1,
        'jedinicnaCena' => '',
        'popustProcenat' => 0,
    ),
);
$hiddenFields = isset($hiddenFields) ? $hiddenFields : array();
$formNote = isset($formNote) ? $formNote : '';
?>
<section class="panel-card">
    <div class="panel-header">
        <div>
            <h2><?php echo bezbedanTekst($formTitle); ?></h2>
        </div>
        <?php if ($formNote !== '') { ?>
            <div class="panel-note"><?php echo bezbedanTekst($formNote); ?></div>
        <?php } ?>
    </div>

    <form method="post" action="<?php echo bezbedanTekst($formAction); ?>" class="forma-porudzbine" id="formaPorudzbine">
        <?php foreach ($hiddenFields as $fieldName => $fieldValue) { ?>
            <input type="hidden" name="<?php echo bezbedanTekst($fieldName); ?>" value="<?php echo bezbedanTekst($fieldValue); ?>">
        <?php } ?>

        <div class="form-grid">
            <label>
                <span>Broj porudžbine</span>
                <input type="text" id="poljeBrojaPorudzbine" name="brojPorudzbine" value="<?php echo bezbedanTekst($formData['brojPorudzbine']); ?>" maxlength="20" minlength="5" required>
            </label>
            <label>
                <span>Datum porudžbine</span>
                <input type="date" name="datumPorudzbine" value="<?php echo bezbedanTekst($formData['datumPorudzbine']); ?>" required>
            </label>
            <label>
                <span>Kupac / kontakt osoba</span>
                <input type="text" name="kupac" value="<?php echo bezbedanTekst($formData['kupac']); ?>" maxlength="100" minlength="3" required>
            </label>
            <label>
                <span>E-mail kupca</span>
                <input type="email" name="emailKupca" value="<?php echo bezbedanTekst($formData['emailKupca']); ?>" maxlength="100" required>
            </label>
            <label>
                <span>Status porudžbine</span>
                <select name="statusPorudzbine" required>
                    <?php foreach (opcijeStatusa() as $status) { ?>
                        <option value="<?php echo bezbedanTekst($status); ?>"<?php echo $formData['statusPorudzbine'] === $status ? ' selected' : ''; ?>><?php echo bezbedanTekst($status); ?></option>
                    <?php } ?>
                </select>
            </label>
            <label>
                <span>Način plaćanja</span>
                <select name="nacinPlacanja" required>
                    <?php foreach (opcijePlacanja() as $placanje) { ?>
                        <option value="<?php echo bezbedanTekst($placanje); ?>"<?php echo $formData['nacinPlacanja'] === $placanje ? ' selected' : ''; ?>><?php echo bezbedanTekst($placanje); ?></option>
                    <?php } ?>
                </select>
            </label>
            <label class="form-grid-full">
                <span>Napomena</span>
                <textarea name="napomena" rows="3" maxlength="255"><?php echo bezbedanTekst($formData['napomena']); ?></textarea>
            </label>
        </div>

        <div class="zaglavlje-stavki">
            <div>
                <h3>Stavke porudžbine</h3>
            </div>
            <button type="button" class="button button-secondary" id="dugmeDodajStavku">Dodaj stavku</button>
        </div>

        <div class="table-wrap">
            <table class="data-table" id="tabelaStavki">
                <thead>
                    <tr>
                        <th>Artikal</th>
                        <th>Količina</th>
                        <th>Jedinična cena</th>
                        <th>Popust %</th>
                        <th>Iznos</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lineItems as $stavka) { ?>
                        <tr class="red-stavke">
                            <td>
                                <select name="sifraArtikla[]" class="izbor-artikla" required>
                                    <option value="">Izaberite artikal</option>
                                    <?php foreach ($artikli as $artikal) { ?>
                                        <?php $cena = number_format((float) $artikal['Cena'], 2, '.', ''); ?>
                                        <option
                                            value="<?php echo bezbedanTekst($artikal['SifraArtikla']); ?>"
                                            data-cena="<?php echo bezbedanTekst($cena); ?>"
                                            data-lager="<?php echo bezbedanTekst($artikal['StanjeNaLageru']); ?>"
                                            <?php echo $stavka['sifraArtikla'] === $artikal['SifraArtikla'] ? 'selected' : ''; ?>
                                        >
                                            <?php echo bezbedanTekst($artikal['NazivArtikla'] . ' | ' . $artikal['Brend'] . ' | lager: ' . $artikal['StanjeNaLageru']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td><input type="number" name="kolicina[]" class="stavka-kolicina" min="1" value="<?php echo bezbedanTekst($stavka['kolicina']); ?>" required></td>
                            <td><input type="number" name="jedinicnaCena[]" class="stavka-cena" min="0.01" step="0.01" value="<?php echo bezbedanTekst($stavka['jedinicnaCena']); ?>" required></td>
                            <td><input type="number" name="popustProcenat[]" class="stavka-popust" min="0" max="100" step="0.01" value="<?php echo bezbedanTekst($stavka['popustProcenat']); ?>"></td>
                            <td><strong class="stavka-iznos">0,00 RSD</strong></td>
                            <td><button type="button" class="button button-ghost dugme-ukloni-stavku">Ukloni</button></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="rezime-porudzbine">
            <div id="porukaValidacijeForme" class="inline-message"></div>
            <div class="rezime-porudzbine-kutija">
                <span>Ukupan iznos porudžbine</span>
                <strong id="ukupanIznosPorudzbine">0,00 RSD</strong>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="button"><?php echo bezbedanTekst($submitLabel); ?></button>
            <a href="PorudzbineLista.php" class="button button-secondary">Nazad na listu</a>
        </div>
    </form>
</section>

<template id="sablonStavke">
    <tr class="red-stavke">
        <td>
            <select name="sifraArtikla[]" class="izbor-artikla" required>
                <option value="">Izaberite artikal</option>
                <?php foreach ($artikli as $artikal) { ?>
                    <?php $cena = number_format((float) $artikal['Cena'], 2, '.', ''); ?>
                    <option value="<?php echo bezbedanTekst($artikal['SifraArtikla']); ?>" data-cena="<?php echo bezbedanTekst($cena); ?>" data-lager="<?php echo bezbedanTekst($artikal['StanjeNaLageru']); ?>">
                        <?php echo bezbedanTekst($artikal['NazivArtikla'] . ' | ' . $artikal['Brend'] . ' | lager: ' . $artikal['StanjeNaLageru']); ?>
                    </option>
                <?php } ?>
            </select>
        </td>
        <td><input type="number" name="kolicina[]" class="stavka-kolicina" min="1" value="1" required></td>
        <td><input type="number" name="jedinicnaCena[]" class="stavka-cena" min="0.01" step="0.01" value="" required></td>
        <td><input type="number" name="popustProcenat[]" class="stavka-popust" min="0" max="100" step="0.01" value="0"></td>
        <td><strong class="stavka-iznos">0,00 RSD</strong></td>
        <td><button type="button" class="button button-ghost dugme-ukloni-stavku">Ukloni</button></td>
    </tr>
</template>

<script>
(function () {
    const forma = document.getElementById('formaPorudzbine');
    const teloTabele = document.querySelector('#tabelaStavki tbody');
    const dugmeDodajStavku = document.getElementById('dugmeDodajStavku');
    const sablonStavke = document.getElementById('sablonStavke');
    const oznakaUkupnogIznosa = document.getElementById('ukupanIznosPorudzbine');
    const porukaValidacije = document.getElementById('porukaValidacijeForme');
    const poljeBrojaPorudzbine = document.getElementById('poljeBrojaPorudzbine');
    const poljeStarogBroja = forma.querySelector('[name="StariBrojPorudzbine"]');
    let dozvoliProgramskoSlanje = false;

    function formatirajValutu(vrednost) {
        return new Intl.NumberFormat('sr-RS', { style: 'currency', currency: 'RSD' }).format(vrednost || 0);
    }

    function izracunajRed(red) {
        const izbor = red.querySelector('.izbor-artikla');
        const kolicina = Number(red.querySelector('.stavka-kolicina').value || 0);
        const poljeCene = red.querySelector('.stavka-cena');
        const popust = Number(red.querySelector('.stavka-popust').value || 0);
        const poljeIznosa = red.querySelector('.stavka-iznos');

        if (izbor && izbor.value && poljeCene.value === '') {
            const izabranaOpcija = izbor.options[izbor.selectedIndex];
            poljeCene.value = izabranaOpcija.dataset.cena || '';
        }

        const cena = Number(poljeCene.value || 0);
        const iznos = kolicina * cena * (1 - (popust / 100));
        poljeIznosa.textContent = formatirajValutu(iznos);

        return iznos;
    }

    function izracunajPorudzbinu() {
        let ukupanIznos = 0;
        teloTabele.querySelectorAll('.red-stavke').forEach((red) => {
            ukupanIznos += izracunajRed(red);
        });
        oznakaUkupnogIznosa.textContent = formatirajValutu(ukupanIznos);
    }

    function obezbediBarJedanRed() {
        if (teloTabele.querySelectorAll('.red-stavke').length === 0) {
            dodajStavku();
        }
    }

    function dodajStavku() {
        const kopija = sablonStavke.content.cloneNode(true);
        teloTabele.appendChild(kopija);
        izracunajPorudzbinu();
    }

    function prikaziPoruku(poruka) {
        porukaValidacije.textContent = poruka;
    }

    async function proveriJedinstvenostBrojaPorudzbine() {
        const brojPorudzbine = poljeBrojaPorudzbine.value.trim();
        const stariBroj = poljeStarogBroja ? poljeStarogBroja.value.trim() : '';

        if (brojPorudzbine.length < 5) {
            return true;
        }

        if (stariBroj !== '' && brojPorudzbine === stariBroj) {
            return true;
        }

        try {
            const dopunaPutanje = stariBroj !== '' ? '?preskoci=' + encodeURIComponent(stariBroj) : '';
            const odgovor = await fetch('api.php/provera-broja-porudzbine/' + encodeURIComponent(brojPorudzbine) + dopunaPutanje, {
                headers: { 'Accept': 'application/json' }
            });

            if (!odgovor.ok) {
                return true;
            }

            const rezultat = await odgovor.json();
            if (rezultat && rezultat.uspeh === true && rezultat.postoji === true) {
                prikaziPoruku('Porudžbina sa tim brojem već postoji.');
                return false;
            }
        } catch (greska) {
            return true;
        }

        return true;
    }

    dugmeDodajStavku.addEventListener('click', dodajStavku);

    teloTabele.addEventListener('change', (dogadjaj) => {
        if (dogadjaj.target.matches('.izbor-artikla')) {
            const red = dogadjaj.target.closest('.red-stavke');
            const izabranaOpcija = dogadjaj.target.options[dogadjaj.target.selectedIndex];
            const poljeCene = red.querySelector('.stavka-cena');
            if (izabranaOpcija && izabranaOpcija.dataset.cena) {
                poljeCene.value = izabranaOpcija.dataset.cena;
            }
        }
        izracunajPorudzbinu();
    });

    teloTabele.addEventListener('input', () => {
        izracunajPorudzbinu();
    });

    teloTabele.addEventListener('click', (dogadjaj) => {
        if (!dogadjaj.target.matches('.dugme-ukloni-stavku')) {
            return;
        }

        dogadjaj.target.closest('.red-stavke').remove();
        obezbediBarJedanRed();
        izracunajPorudzbinu();
    });

    poljeBrojaPorudzbine.addEventListener('blur', async () => {
        prikaziPoruku('');
        if (poljeBrojaPorudzbine.value.trim() !== '') {
            await proveriJedinstvenostBrojaPorudzbine();
        }
    });

    forma.addEventListener('submit', async (dogadjaj) => {
        if (dozvoliProgramskoSlanje) {
            return;
        }

        dogadjaj.preventDefault();
        prikaziPoruku('');

        const brojPorudzbine = forma.querySelector('[name="brojPorudzbine"]').value.trim();
        const kupac = forma.querySelector('[name="kupac"]').value.trim();
        const email = forma.querySelector('[name="emailKupca"]').value.trim();
        const redovi = Array.from(teloTabele.querySelectorAll('.red-stavke'));

        if (brojPorudzbine.length < 5) {
            prikaziPoruku('Broj porudžbine mora imati najmanje 5 karaktera.');
            return;
        }

        if (kupac.length < 3) {
            prikaziPoruku('Unesite naziv kupca ili kontakt osobe.');
            return;
        }

        if (!email.includes('@')) {
            prikaziPoruku('Unesite ispravnu e-mail adresu kupca.');
            return;
        }

        if (redovi.length === 0) {
            prikaziPoruku('Potrebno je dodati najmanje jednu stavku porudžbine.');
            return;
        }

        const izabraniArtikli = new Set();
        for (const red of redovi) {
            const artikal = red.querySelector('.izbor-artikla').value;
            const kolicina = Number(red.querySelector('.stavka-kolicina').value || 0);
            const cena = Number(red.querySelector('.stavka-cena').value || 0);
            const popust = Number(red.querySelector('.stavka-popust').value || 0);

            if (!artikal) {
                prikaziPoruku('Svaka stavka mora imati izabran artikal.');
                return;
            }

            if (izabraniArtikli.has(artikal)) {
                prikaziPoruku('Isti artikal nije moguće uneti više puta.');
                return;
            }

            if (kolicina < 1 || cena <= 0 || popust < 0 || popust > 100) {
                prikaziPoruku('Proverite količinu, cenu i popust za sve stavke.');
                return;
            }

            izabraniArtikli.add(artikal);
        }

        if (!(await proveriJedinstvenostBrojaPorudzbine())) {
            return;
        }

        dozvoliProgramskoSlanje = true;
        forma.submit();
    });

    izracunajPorudzbinu();
})();
</script>


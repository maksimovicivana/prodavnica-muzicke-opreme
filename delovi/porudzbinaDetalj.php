<?php
$zaglavljePorudzbine = isset($zaglavljePorudzbine) ? $zaglavljePorudzbine : array();
$stavkePorudzbine = isset($stavkePorudzbine) ? $stavkePorudzbine : array();
$ukupanIznos = isset($ukupanIznos) ? $ukupanIznos : 0;
?>
<section class="panel-card panel-card-print">
    <?php if (!$zaglavljePorudzbine) { ?>
        <h2>Porudžbina nije pronađena</h2>
        <p>Proverite broj porudžbine i pokušajte ponovo.</p>
    <?php } else { ?>
        <div class="print-head">
            <div>
                <p class="page-eyebrow">Parametarska štampa</p>
                <h2>Porudžbina <?php echo bezbedanTekst($zaglavljePorudzbine['BrojPorudzbine']); ?></h2>
            </div>
            <div class="print-meta">
                <div><strong>Datum:</strong> <?php echo bezbedanTekst(formatirajDatum($zaglavljePorudzbine['DatumPorudzbine'])); ?></div>
                <div><strong>Status:</strong> <?php echo bezbedanTekst($zaglavljePorudzbine['StatusPorudzbine']); ?></div>
                <div><strong>Plaćanje:</strong> <?php echo bezbedanTekst($zaglavljePorudzbine['NacinPlacanja']); ?></div>
            </div>
        </div>

        <div class="detail-grid">
            <div class="detail-card">
                <h3>Kupac</h3>
                <p><?php echo bezbedanTekst($zaglavljePorudzbine['Kupac']); ?></p>
                <p><?php echo bezbedanTekst($zaglavljePorudzbine['EmailKupca']); ?></p>
            </div>
            <div class="detail-card">
                <h3>Napomena</h3>
                <p><?php echo bezbedanTekst($zaglavljePorudzbine['Napomena'] !== '' ? $zaglavljePorudzbine['Napomena'] : 'Nema dodatne napomene.'); ?></p>
            </div>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Šifra</th>
                        <th>Artikal</th>
                        <th>Kategorija</th>
                        <th>Količina</th>
                        <th>Cena</th>
                        <th>Popust</th>
                        <th>Iznos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stavkePorudzbine as $stavka) { ?>
                        <tr>
                            <td><?php echo bezbedanTekst($stavka['SifraArtikla']); ?></td>
                            <td>
                                <?php echo bezbedanTekst($stavka['NazivArtikla']); ?><br>
                                <span class="muted-text"><?php echo bezbedanTekst($stavka['Brend']); ?></span>
                            </td>
                            <td><?php echo bezbedanTekst($stavka['Kategorija']); ?></td>
                            <td><?php echo bezbedanTekst($stavka['Kolicina']); ?></td>
                            <td><?php echo bezbedanTekst(formatirajValutu($stavka['JedinicnaCena'])); ?></td>
                            <td><?php echo bezbedanTekst(number_format((float) $stavka['PopustProcenat'], 2, ',', '.') . '%'); ?></td>
                            <td><?php echo bezbedanTekst(formatirajValutu($stavka['IznosStavke'])); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="table-total-label">Ukupan iznos</td>
                        <td class="table-total-value"><?php echo bezbedanTekst(formatirajValutu($ukupanIznos)); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php } ?>
</section>


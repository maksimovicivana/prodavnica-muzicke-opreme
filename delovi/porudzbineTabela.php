<?php
$porudzbine = isset($porudzbine) ? $porudzbine : array();
$allowActions = isset($allowActions) ? $allowActions : true;
$listTitle = isset($listTitle) ? $listTitle : 'Arhiva porudžbina';
$listLead = isset($listLead) ? $listLead : 'Pregled svih kreiranih dokumenata uz filtere i brže akcije.';
?>
<section class="panel-card">
    <div class="panel-header">
        <div>
            <h2><?php echo bezbedanTekst($listTitle); ?></h2>
            <p><?php echo bezbedanTekst($listLead); ?></p>
        </div>
        <?php if ($allowActions) { ?>
            <a href="PorudzbinaUnos.php" class="button">Nova porudžbina</a>
        <?php } ?>
    </div>

    <form method="get" class="filter-bar">
        <label>
            <span>Pretraga</span>
            <input type="text" name="pretraga" value="<?php echo isset($aktivnaPretraga) ? bezbedanTekst($aktivnaPretraga) : ''; ?>" placeholder="Broj porudžbine, kupac ili e-mail">
        </label>
        <label>
            <span>Status</span>
            <select name="status">
                <option value="">Svi statusi</option>
                <?php foreach (opcijeStatusa() as $statusOpcija) { ?>
                    <option value="<?php echo bezbedanTekst($statusOpcija); ?>"<?php echo (isset($aktivniStatus) && $aktivniStatus === $statusOpcija) ? ' selected' : ''; ?>><?php echo bezbedanTekst($statusOpcija); ?></option>
                <?php } ?>
            </select>
        </label>
        <div class="filter-actions">
            <button type="submit" class="button button-secondary">Filtriraj</button>
            <a href="<?php echo $allowActions ? 'PorudzbineLista.php' : 'PorudzbineStampa.php'; ?>" class="button button-ghost">Resetuj</a>
        </div>
    </form>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th class="col-broj">Broj</th>
                    <th>Kupac</th>
                    <th class="col-datum">Datum</th>
                    <th class="col-status">Status</th>
                    <th class="col-placanje">Plaćanje</th>
                    <th class="col-stavke">Stavke</th>
                    <th class="col-ukupno">Ukupno</th>
                    <?php if ($allowActions) { ?>
                        <th class="col-akcije">Akcije</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php if (count($porudzbine) === 0) { ?>
                    <tr>
                        <td colspan="<?php echo $allowActions ? '8' : '7'; ?>">Nema rezultata za zadate kriterijume.</td>
                    </tr>
                <?php } ?>
                <?php foreach ($porudzbine as $red) { ?>
                    <tr>
                        <td class="col-broj"><strong class="broj-porudzbine"><?php echo bezbedanTekst($red['BrojPorudzbine']); ?></strong></td>
                        <td>
                            <?php echo bezbedanTekst($red['Kupac']); ?><br>
                            <span class="muted-text"><?php echo bezbedanTekst($red['EmailKupca']); ?></span>
                        </td>
                        <td class="col-datum"><?php echo bezbedanTekst(formatirajDatum($red['DatumPorudzbine'])); ?></td>
                        <td class="col-status"><span class="status-badge <?php echo bezbedanTekst(klasaBedzaStatusa($red['StatusPorudzbine'])); ?>"><?php echo bezbedanTekst($red['StatusPorudzbine']); ?></span></td>
                        <td class="col-placanje"><?php echo bezbedanTekst($red['NacinPlacanja']); ?></td>
                        <td class="col-stavke"><?php echo bezbedanTekst($red['BrojStavki']); ?></td>
                        <td class="col-ukupno"><?php echo bezbedanTekst(formatirajValutu($red['UkupanIznos'])); ?></td>
                        <?php if ($allowActions) { ?>
                            <td class="col-akcije">
                                <div class="action-stack">
                                    <form method="post" action="PorudzbinaIzmeniForm.php">
                                        <input type="hidden" name="BrojPorudzbine" value="<?php echo bezbedanTekst($red['BrojPorudzbine']); ?>">
                                        <button type="submit" class="button button-ghost">Izmeni</button>
                                    </form>
                                    <form method="post" action="StampaPodatakaOPorudzbini.php" target="_blank">
                                        <input type="hidden" name="BrojPorudzbineFilter" value="<?php echo bezbedanTekst($red['BrojPorudzbine']); ?>">
                                        <button type="submit" class="button button-ghost">Štampa</button>
                                    </form>
                                    <form method="post" action="PorudzbinaObrisi.php" onsubmit="return confirm('Da li ste sigurni da želite da obrišete porudžbinu?');">
                                        <input type="hidden" name="BrojPorudzbine" value="<?php echo bezbedanTekst($red['BrojPorudzbine']); ?>">
                                        <button type="submit" class="button button-danger">Obriši</button>
                                    </form>
                                </div>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</section>


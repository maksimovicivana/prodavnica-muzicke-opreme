<section class="panel-card">
    <div class="panel-header">
        <div>
            <h2>Digitalna evidencija za prodavnicu muzičke opreme</h2>
        </div>
        <a href="OperaterPrijava.php" class="button">Otvori radni panel</a>
    </div>

    <section class="panel-card">
        <div class="panel-header">
            <div>
                <h2>Izdvojeni artikli iz šifarnika</h2>
                <p>Javni pregled nekoliko artikala koje zaposleni koristi pri formiranju porudžbina.</p>
            </div>
        </div>

        <?php if (count($artikliZaPocetnu) === 0) { ?>
            <div class="empty-state">Baza trenutno nije dostupna ili šifarnik artikala još nije popunjen.</div>
        <?php } else { ?>
            <div class="cards-grid">
                <?php foreach ($artikliZaPocetnu as $artikal) { ?>
                    <article class="catalog-card">
                        <div class="catalog-meta">
                            <span><?php echo bezbedanTekst($artikal['Kategorija']); ?></span>
                            <span><?php echo bezbedanTekst($artikal['Brend']); ?></span>
                        </div>
                        <h3><?php echo bezbedanTekst($artikal['NazivArtikla']); ?></h3>
                        <p><?php echo bezbedanTekst($artikal['Opis']); ?></p>
                        <div class="catalog-meta">
                            <strong class="catalog-price"><?php echo bezbedanTekst(formatirajValutu($artikal['Cena'])); ?></strong>
                            <span>Lager: <?php echo bezbedanTekst($artikal['StanjeNaLageru']); ?></span>
                        </div>
                    </article>
                <?php } ?>
            </div>
        <?php } ?>
    </section>
</section>


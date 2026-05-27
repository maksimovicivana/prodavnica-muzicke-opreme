<section class="panel-card">
    <div class="panel-header">
        <div>
            <h2>Kontrolna tabla prodavnice</h2>
            <p>Brz pregled stanja šifarnika, broja porudžbina i najvažnijih akcija za rad u aplikaciji.</p>
        </div>
    </div>

    <div class="stats-grid">
        <article class="stats-card">
            <h3>Aktivni artikli</h3>
            <p>Ukupan broj artikala iz šifarnika koji se mogu dodavati u porudžbine.</p>
            <strong><?php echo bezbedanTekst($brojArtikala); ?></strong>
        </article>
        <article class="stats-card">
            <h3>Evidentirane porudžbine</h3>
            <p>Ukupan broj porudžbina koje su trenutno evidentirane u sistemu.</p>
            <strong><?php echo bezbedanTekst($brojPorudzbina); ?></strong>
        </article>
        <article class="stats-card">
            <h3>Poslednji status</h3>
            <p>Najzastupljeniji operativni korak u trenutnom radu sa porudžbinama.</p>
            <strong><?php echo bezbedanTekst($istaknutiStatus); ?></strong>
        </article>
    </div>

    <div class="cards-grid">
        <article class="feature-card">
            <h3>Unos porudžbine</h3>
            <p>Jednostavan unos porudžbine i svih njenih stavki uz proveru dostupne količine.</p>
            <a class="button" href="PorudzbinaUnos.php">Otvori formu</a>
        </article>
        <article class="feature-card">
            <h3>Arhiva i štampa</h3>
            <p>Filtriranje, izmena, brisanje, tabelarni pregled i parametarska štampa pojedinačne porudžbine.</p>
            <a class="button" href="PorudzbineLista.php">Otvori arhivu</a>
        </article>
    </div>
</section>


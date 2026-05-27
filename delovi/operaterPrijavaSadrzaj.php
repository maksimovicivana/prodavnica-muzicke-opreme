<section class="panel-card">
    <div class="panel-header">
        <div>
            <h2>Prijava zaposlenog</h2>
            <p>Pristup radnom panelu za unos porudžbina, pregled artikala i rad sa dokumentima prodavnice.</p>
        </div>
    </div>

    <form action="OperaterPrijavaProvera.php" method="post" class="form-grid">
        <label>
            <span>Korisničko ime</span>
            <input type="text" name="korisnickoIme" placeholder="Unesite korisničko ime" required>
        </label>
        <label>
            <span>Šifra</span>
            <input type="password" name="sifra" placeholder="Unesite šifru" required>
        </label>
        <div class="form-actions">
            <button type="submit" class="button">Uđi u panel</button>
            <a href="PocetnaProdavnice.php" class="button button-secondary">Nazad na početnu</a>
        </div>
    </form>
</section>

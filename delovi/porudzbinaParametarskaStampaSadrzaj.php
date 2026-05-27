<section class="panel-card">
    <div class="panel-header">
        <div>
            <h2>Parametarska štampa porudžbine</h2>
            <p>Unesite broj porudžbine i otvorite detaljan dokument spreman za štampu.</p>
        </div>
    </div>

    <form action="StampaPodatakaOPorudzbini.php" method="post" target="_blank" class="form-grid">
        <label>
            <span>Broj porudžbine</span>
            <input type="text" name="BrojPorudzbineFilter" placeholder="npr. POR-20260526-120015" required>
        </label>
        <div class="form-actions">
            <button type="submit" class="button">Pripremi za štampu</button>
        </div>
    </form>
</section>

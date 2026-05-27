<?php
$trenutnaStranica = basename($_SERVER['PHP_SELF']);

function meniKlasa($stranica, $trenutnaStranica)
{
    return $stranica === $trenutnaStranica ? 'is-active' : '';
}
?>
<aside class="sidebar-card">
    <h2>Meni zaposlenog</h2>
    <div class="sidebar-menu">
        <a class="<?php echo meniKlasa('KontrolnaTabla.php', $trenutnaStranica); ?>" href="KontrolnaTabla.php"><span>Radni panel</span><span>01</span></a>
        <a class="<?php echo meniKlasa('PorudzbinaUnos.php', $trenutnaStranica); ?>" href="PorudzbinaUnos.php"><span>Nova porudžbina</span><span>02</span></a>
        <a class="<?php echo meniKlasa('PorudzbineLista.php', $trenutnaStranica); ?>" href="PorudzbineLista.php"><span>Lista i izmena</span><span>03</span></a>
        <a class="<?php echo meniKlasa('PorudzbineStampa.php', $trenutnaStranica); ?>" href="PorudzbineStampa.php"><span>Štampa arhive</span><span>04</span></a>
        <a class="<?php echo meniKlasa('PorudzbinaParametarskaStampa.php', $trenutnaStranica); ?>" href="PorudzbinaParametarskaStampa.php"><span>Parametarska štampa</span><span>05</span></a>
    </div>
</aside>

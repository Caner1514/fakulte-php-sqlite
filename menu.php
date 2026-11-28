<?php $sayfa = basename($_SERVER["SCRIPT_NAME"]); ?>
<nav class="menu">
    <span class="logo">🎓 Fakülte Sistemi</span>
    <a href="index.php" class="<?= $sayfa === 'index.php' ? 'aktif' : '' ?>">Öğrenciler</a>
    <a href="ogrenci_ekle.php" class="<?= $sayfa === 'ogrenci_ekle.php' ? 'aktif' : '' ?>">+ Öğrenci Ekle</a>
    <a href="fakulteler.php" class="<?= $sayfa === 'fakulteler.php' ? 'aktif' : '' ?>">Fakülteler</a>
    <a href="fakulte_bolum_ekle.php" class="<?= $sayfa === 'fakulte_bolum_ekle.php' ? 'aktif' : '' ?>">+ Fakülte / Bölüm</a>
</nav>
<?php
require "baglanti.php";

$hata = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ad = trim($_POST["ad"] ?? "");
    $soyad = trim($_POST["soyad"] ?? "");
    $bolumId = (int)($_POST["bolum_id"] ?? 0);
    $girisYili = (int)($_POST["giris_yili"] ?? 0);

    if ($ad === "" || $soyad === "") {
        $hata = "Ad ve soyad boş bırakılamaz.";
    } elseif ($bolumId <= 0) {
        $hata = "Lütfen bir bölüm seçin.";
    } elseif ($girisYili < 2000 || $girisYili > 2100) {
        $hata = "Giriş yılı 2000 ile 2100 arasında olmalı.";
    } else {
        $stmt = $db->prepare("INSERT INTO Ogrenci (Ad, Soyad, BolumID, GirisYili) VALUES (:ad, :soyad, :bolum, :yil)");
        $stmt->execute([":ad" => $ad, ":soyad" => $soyad, ":bolum" => $bolumId, ":yil" => $girisYili]);
        header("Location: index.php?mesaj=eklendi");
        exit;
    }
}

$bolumler = $db->query("
    SELECT b.BolumID, b.BolumAdi, f.FakulteAdi
    FROM Bolum b
    LEFT JOIN Fakulte f ON b.FakulteID = f.FakulteID
    WHERE b.BolumAdi <> ''
    ORDER BY f.FakulteAdi, b.BolumAdi
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Öğrenci</title>
    <link rel="stylesheet" href="stil.css">
</head>
<body>
    <?php include "menu.php"; ?>
    <h1>Yeni Öğrenci Ekle</h1>

 <form method="post" class="form-card">
        <?php if ($hata): ?>
            <div class="hata"><?= htmlspecialchars($hata) ?></div>
        <?php endif; ?>

        <label>Ad</label>
        <input type="text" name="ad" value="<?= htmlspecialchars($_POST["ad"] ?? "") ?>">

        <label>Soyad</label>
        <input type="text" name="soyad" value="<?= htmlspecialchars($_POST["soyad"] ?? "") ?>">

        <label>Bölüm</label>
        <select name="bolum_id">
            <option value="0">-- Seçin --</option>
            <?php foreach ($bolumler as $b): ?>
                <option value="<?= (int)$b["BolumID"] ?>" <?= ((int)($_POST["bolum_id"] ?? 0) === (int)$b["BolumID"]) ? "selected" : "" ?>>
                    <?= htmlspecialchars($b["BolumAdi"] . " (" . ($b["FakulteAdi"] ?? "-") . ")") ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Giriş yılı</label>
        <input type="number" name="giris_yili" value="<?= htmlspecialchars($_POST["giris_yili"] ?? date("Y")) ?>">

        <button type="submit">Kaydet</button>
    </form>
</body>
</html>
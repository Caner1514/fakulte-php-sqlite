<?php
require "baglanti.php";

$id = (int)($_GET["id"] ?? $_POST["id"] ?? 0);

$stmt = $db->prepare("SELECT * FROM Ogrenci WHERE OgrenciID = :id");
$stmt->execute([":id" => $id]);
$ogrenci = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ogrenci) {
    die("Öğrenci bulunamadı. <a href='index.php'>Listeye dön</a>");
}

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
        $stmt = $db->prepare("
            UPDATE Ogrenci
            SET Ad = :ad, Soyad = :soyad, BolumID = :bolum, GirisYili = :yil
            WHERE OgrenciID = :id
        ");
        $stmt->execute([
            ":ad" => $ad, ":soyad" => $soyad,
            ":bolum" => $bolumId, ":yil" => $girisYili, ":id" => $id
        ]);
        header("Location: index.php?mesaj=guncellendi");
        exit;
    }

    // Hata varsa formda kullanıcının yazdıkları kalsın
    $ogrenci["Ad"] = $ad;
    $ogrenci["Soyad"] = $soyad;
    $ogrenci["BolumID"] = $bolumId;
    $ogrenci["GirisYili"] = $girisYili;
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
    <title>Öğrenci Güncelle</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f6fa; }
        h1 { color: #2c3e50; }
        form { background: #fff; padding: 20px; max-width: 400px; border: 1px solid #ccc; }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; margin-top: 4px; box-sizing: border-box; }
        button { margin-top: 18px; padding: 10px 18px; cursor: pointer; }
        .hata { color: #c0392b; margin-bottom: 10px; }
    </style>
</head>
<body>
    <p><a href="index.php">← Öğrenci listesi</a></p>
    <h1>Öğrenci Güncelle</h1>

    <form method="post" class="form-card">
        <input type="hidden" name="id" value="<?= (int)$ogrenci["OgrenciID"] ?>">

        <?php if ($hata): ?>
            <div class="hata"><?= htmlspecialchars($hata) ?></div>
        <?php endif; ?>

        <label>Ad</label>
        <input type="text" name="ad" value="<?= htmlspecialchars($ogrenci["Ad"]) ?>">

        <label>Soyad</label>
        <input type="text" name="soyad" value="<?= htmlspecialchars($ogrenci["Soyad"]) ?>">

        <label>Bölüm</label>
        <select name="bolum_id">
            <option value="0">-- Seçin --</option>
            <?php foreach ($bolumler as $b): ?>
                <option value="<?= (int)$b["BolumID"] ?>" <?= ((int)$ogrenci["BolumID"] === (int)$b["BolumID"]) ? "selected" : "" ?>>
                    <?= htmlspecialchars($b["BolumAdi"] . " (" . ($b["FakulteAdi"] ?? "-") . ")") ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Giriş yılı</label>
        <input type="number" name="giris_yili" value="<?= htmlspecialchars($ogrenci["GirisYili"]) ?>">

        <button type="submit">Güncelle</button>
    </form>
</body>
</html>
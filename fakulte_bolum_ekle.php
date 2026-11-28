<?php
require "baglanti.php";

$hata = "";
$basari = $_GET["ok"] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $islem = $_POST["islem"] ?? "";

    if ($islem === "fakulte") {
        $ad = trim($_POST["fakulte_adi"] ?? "");
        if ($ad === "") {
            $hata = "Fakülte adı boş bırakılamaz.";
        } else {
            $var = $db->prepare("SELECT COUNT(*) FROM Fakulte WHERE LOWER(FakulteAdi) = LOWER(:ad)");
            $var->execute([":ad" => $ad]);
            if ($var->fetchColumn() > 0) {
                $hata = "Bu fakülte zaten kayıtlı.";
            } else {
                $stmt = $db->prepare("INSERT INTO Fakulte (FakulteAdi) VALUES (:ad)");
                $stmt->execute([":ad" => $ad]);
                header("Location: fakulte_bolum_ekle.php?ok=fakulte");
                exit;
            }
        }
    }

    if ($islem === "bolum") {
        $ad = trim($_POST["bolum_adi"] ?? "");
        $fakulteId = (int)($_POST["fakulte_id"] ?? 0);
        if ($ad === "") {
            $hata = "Bölüm adı boş bırakılamaz.";
        } elseif ($fakulteId <= 0) {
            $hata = "Lütfen bir fakülte seçin.";
        } else {
            $var = $db->prepare("SELECT COUNT(*) FROM Bolum WHERE LOWER(BolumAdi) = LOWER(:ad) AND FakulteID = :f");
            $var->execute([":ad" => $ad, ":f" => $fakulteId]);
            if ($var->fetchColumn() > 0) {
                $hata = "Bu bölüm bu fakültede zaten kayıtlı.";
            } else {
                $stmt = $db->prepare("INSERT INTO Bolum (BolumAdi, FakulteID) VALUES (:ad, :f)");
                $stmt->execute([":ad" => $ad, ":f" => $fakulteId]);
                header("Location: fakulte_bolum_ekle.php?ok=bolum");
                exit;
            }
        }
    }
}

$fakulteler = $db->query("SELECT FakulteID, FakulteAdi FROM Fakulte ORDER BY FakulteAdi")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Fakülte ve Bölüm Ekle</title>
    <link rel="stylesheet" href="stil.css">
</head>
<body>
    <?php include "menu.php"; ?>
    <h1>Fakülte ve Bölüm Ekle</h1>

    <?php if ($hata): ?>
        <div class="hata"><?= htmlspecialchars($hata) ?></div>
    <?php endif; ?>
    <?php if ($basari === "fakulte"): ?>
        <div class="basari">Fakülte eklendi.</div>
    <?php elseif ($basari === "bolum"): ?>
        <div class="basari">Bölüm eklendi.</div>
    <?php endif; ?>

    <h2>Yeni Fakülte</h2>
    <form method="post" class="form-card">
        <input type="hidden" name="islem" value="fakulte">
        <label>Fakülte adı</label>
        <input type="text" name="fakulte_adi">
        <button type="submit">Fakülte Ekle</button>
    </form>

    <h2>Yeni Bölüm</h2>
    <form method="post" class="form-card">
        <input type="hidden" name="islem" value="bolum">
        <label>Bölüm adı</label>
        <input type="text" name="bolum_adi">
        <label>Bağlı olduğu fakülte</label>
        <select name="fakulte_id">
            <option value="0">-- Seçin --</option>
            <?php foreach ($fakulteler as $f): ?>
                <option value="<?= (int)$f["FakulteID"] ?>"><?= htmlspecialchars($f["FakulteAdi"]) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Bölüm Ekle</button>
    </form>
</body>
</html>
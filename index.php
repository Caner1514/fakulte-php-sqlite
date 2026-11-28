<?php
require "baglanti.php";
$mesajlar = [
    "silindi" => "Öğrenci silindi.",
    "eklendi" => "Öğrenci eklendi.",
    "guncellendi" => "Öğrenci güncellendi.",
];
$mesaj = $mesajlar[$_GET["mesaj"] ?? ""] ?? "";

$ara = trim($_GET["ara"] ?? "");

$stmt = $db->prepare("
    SELECT o.OgrenciID, o.Ad, o.Soyad, o.GirisYili,
           b.BolumAdi, f.FakulteAdi
    FROM Ogrenci o
    LEFT JOIN Bolum b ON o.BolumID = b.BolumID
    LEFT JOIN Fakulte f ON b.FakulteID = f.FakulteID
    WHERE o.Ad LIKE :ara OR o.Soyad LIKE :ara OR b.BolumAdi LIKE :ara
    ORDER BY o.OgrenciID  
");
$stmt->execute([":ara" => "%" . $ara . "%"]);
$ogrenciler = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Öğrenciler</title>
    <link rel="stylesheet" href="stil.css">
</head>
<body>
    <?php include "menu.php"; ?>
    <h1>Öğrenci Listesi</h1>
        <?php if ($mesaj): ?>
        <div class="basari"><?= htmlspecialchars($mesaj) ?></div>
    <?php endif; ?>
    <form method="get" class="arama">
        <input type="text" name="ara" placeholder="Ad, soyad veya bölüm ara..." value="<?= htmlspecialchars($ara) ?>">
        <button type="submit">Ara</button>
    </form>

    <p>Toplam <?= count($ogrenciler) ?> öğrenci</p>

    <table>
        <tr>
           <th>No</th><th>Ad Soyad</th><th>Bölüm</th><th>Fakülte</th><th>Giriş Yılı</th><th>İşlem</th>
        </tr>
        <?php foreach ($ogrenciler as $o): ?>
        <tr>
            <td><?= htmlspecialchars($o["OgrenciID"]) ?></td>
            <td><a href="ogrenci_detay.php?id=<?= (int)$o["OgrenciID"] ?>"><?= htmlspecialchars($o["Ad"] . " " . $o["Soyad"]) ?></a></td>
            <td><?= htmlspecialchars($o["BolumAdi"] ?? "-") ?></td>
            <td><?= htmlspecialchars($o["FakulteAdi"] ?? "-") ?></td>
            <td><?= htmlspecialchars($o["GirisYili"]) ?></td>
             <td>
                <a class="btn btn-sec" href="ogrenci_guncelle.php?id=<?= (int)$o["OgrenciID"] ?>">Güncelle</a>
                <form method="post" action="ogrenci_sil.php" onsubmit="return confirm('Bu öğrenci silinsin mi?');" style="display:inline;">
                    <input type="hidden" name="id" value="<?= (int)$o["OgrenciID"] ?>">
                    <button type="submit" class="btn btn-danger">Sil</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
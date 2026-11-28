<?php
require "baglanti.php";

$id = (int)($_GET["id"] ?? 0);

$stmt = $db->prepare("
    SELECT o.Ad, o.Soyad, o.GirisYili, b.BolumAdi, f.FakulteAdi
    FROM Ogrenci o
    LEFT JOIN Bolum b ON o.BolumID = b.BolumID
    LEFT JOIN Fakulte f ON b.FakulteID = f.FakulteID
    WHERE o.OgrenciID = :id
");
$stmt->execute([":id" => $id]);
$ogrenci = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ogrenci) {
    die("Öğrenci bulunamadı. <a href='index.php'>Listeye dön</a>");
}

$stmt = $db->prepare("
    SELECT d.DersAdi, d.Kredi, u.Unvan, u.Ad, u.Soyad, k.Puan
    FROM Kayit k
    JOIN Ders d ON k.DersID = d.DersID
    LEFT JOIN OgretimUyesi u ON d.UyeID = u.UyeID
    WHERE k.OgrenciID = :id
    ORDER BY d.DersAdi
");
$stmt->execute([":id" => $id]);
$dersler = $stmt->fetchAll(PDO::FETCH_ASSOC);

$ortalama = null;
if (count($dersler) > 0) {
    $ortalama = array_sum(array_column($dersler, "Puan")) / count($dersler);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($ogrenci["Ad"] . " " . $ogrenci["Soyad"]) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f6fa; }
        h1 { color: #2c3e50; }
        table { border-collapse: collapse; width: 100%; max-width: 800px; background: #fff; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #2c3e50; color: #fff; }
        tr:nth-child(even) { background: #f2f2f2; }
    </style>
</head>
<body>
    <p><a href="index.php">← Öğrenci listesi</a></p>
    <h1><?= htmlspecialchars($ogrenci["Ad"] . " " . $ogrenci["Soyad"]) ?></h1>
    <p>
        <strong>Bölüm:</strong> <?= htmlspecialchars($ogrenci["BolumAdi"] ?? "-") ?><br>
        <strong>Fakülte:</strong> <?= htmlspecialchars($ogrenci["FakulteAdi"] ?? "-") ?><br>
        <strong>Giriş yılı:</strong> <?= htmlspecialchars($ogrenci["GirisYili"]) ?>
    </p>

    <h2>Aldığı Dersler</h2>
    <?php if (count($dersler) === 0): ?>
        <p>Bu öğrenci henüz ders almamış.</p>
    <?php else: ?>
    <table>
        <tr><th>Ders</th><th>Kredi</th><th>Öğretim Üyesi</th><th>Puan</th></tr>
        <?php foreach ($dersler as $d): ?>
        <tr>
            <td><?= htmlspecialchars($d["DersAdi"]) ?></td>
            <td><?= htmlspecialchars($d["Kredi"]) ?></td>
            <td><?= htmlspecialchars(trim(($d["Unvan"] ?? "") . " " . ($d["Ad"] ?? "") . " " . ($d["Soyad"] ?? ""))) ?></td>
            <td><?= htmlspecialchars($d["Puan"]) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <p><strong>Not ortalaması:</strong> <?= number_format($ortalama, 1) ?></p>
    <?php endif; ?>
</body>
</html>
<?php
require "baglanti.php";
$fakulteler = $db->query("SELECT FakulteID, FakulteAdi FROM Fakulte")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Fakülteler</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        table { border-collapse: collapse; width: 400px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h1>Fakülteler</h1>
    <table>
        <tr><th>ID</th><th>Fakülte Adı</th></tr>
        <?php foreach ($fakulteler as $f): ?>
        <tr>
            <td><?= htmlspecialchars($f["FakulteID"]) ?></td>
            <td><?= htmlspecialchars($f["FakulteAdi"]) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
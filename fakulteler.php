<?php
require "baglanti.php";
$fakulteler = $db->query("SELECT FakulteID, FakulteAdi FROM Fakulte ORDER BY FakulteID")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Fakülteler</title>
    <link rel="stylesheet" href="stil.css">
</head>
<body>
    <?php include "menu.php"; ?>
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
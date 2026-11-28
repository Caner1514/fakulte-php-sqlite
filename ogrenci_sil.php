<?php
require "baglanti.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = (int)($_POST["id"] ?? 0);

    if ($id > 0) {
        $db->beginTransaction();
        // Önce öğrencinin ders kayıtlarını sil, sonra öğrenciyi
        $db->prepare("DELETE FROM Kayit WHERE OgrenciID = :id")->execute([":id" => $id]);
        $db->prepare("DELETE FROM Ogrenci WHERE OgrenciID = :id")->execute([":id" => $id]);
        $db->commit();
    }
}

header("Location: index.php?mesaj=silindi");
exit;
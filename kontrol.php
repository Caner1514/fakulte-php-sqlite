<?php
require "baglanti.php";

foreach (["Fakulte", "Bolum", "Ogrenci", "OgretimUyesi", "Ders", "Kayit"] as $tablo) {
    $sayi = $db->query("SELECT COUNT(*) FROM $tablo")->fetchColumn();
    echo "$tablo: $sayi\n";
}
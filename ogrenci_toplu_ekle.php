<?php
require "baglanti.php";

$hedef = 100;
mt_srand(42); // her çalıştırmada aynı sonuç

$adlar = ["Ahmet","Mehmet","Mustafa","Ali","Hasan","Hüseyin","İbrahim","Emre","Burak","Can",
          "Ayşe","Fatma","Zeynep","Elif","Selin","Deniz","Merve","Esra","Büşra","Yağmur"];
$soyadlar = ["Yılmaz","Kaya","Demir","Şahin","Çelik","Yıldız","Öztürk","Aydın","Arslan","Doğan",
             "Kılıç","Aslan","Çetin","Kara","Koç","Kurt","Özdemir","Polat","Erdem","Güneş"];

$bolumler = $db->query("SELECT BolumID FROM Bolum WHERE BolumAdi <> ''")->fetchAll(PDO::FETCH_COLUMN);
$mevcut = (int)$db->query("SELECT COUNT(*) FROM Ogrenci")->fetchColumn();
$eklenecek = $hedef - $mevcut;

if ($eklenecek <= 0) {
    die("Zaten $mevcut öğrenci var, bir şey eklenmedi.\n");
}

$stmt = $db->prepare("INSERT INTO Ogrenci (Ad, Soyad, BolumID, GirisYili) VALUES (:ad, :soyad, :bolum, :yil)");

$db->beginTransaction();
for ($i = 0; $i < $eklenecek; $i++) {
    $stmt->execute([
        ":ad" => $adlar[mt_rand(0, count($adlar) - 1)],
        ":soyad" => $soyadlar[mt_rand(0, count($soyadlar) - 1)],
        ":bolum" => $bolumler[mt_rand(0, count($bolumler) - 1)],
        ":yil" => mt_rand(2021, 2025),
    ]);
}
$db->commit();

echo "$eklenecek öğrenci eklendi. Toplam: " . $db->query("SELECT COUNT(*) FROM Ogrenci")->fetchColumn() . "\n";
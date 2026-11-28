<?php
require "baglanti.php";

$veri = [
    "Tıp"            => ["Tıp", "Hemşirelik", "Eczacılık", "Fizyoterapi"],
    "Edebiyat Fak"   => ["BBY", "Tarih", "Türk Dili ve Edebiyatı", "Felsefe", "Sosyoloji"],
    "IIBF"           => ["İktisat", "İşletme", "Maliye", "Uluslararası İlişkiler"],
    "Mühendislik"    => ["Bilgisayar Mühendisliği", "Elektrik-Elektronik Mühendisliği", "Makine Mühendisliği", "İnşaat Mühendisliği"],
    "Fen Fakültesi"  => ["Matematik", "Fizik", "Kimya", "Biyoloji"],
    "Hukuk"          => ["Hukuk"],
    "Eğitim"         => ["Sınıf Öğretmenliği", "Okul Öncesi Öğretmenliği", "Rehberlik ve Psikolojik Danışmanlık"],
    "Mimarlık"       => ["Mimarlık", "İç Mimarlık", "Şehir ve Bölge Planlama"],
];

$fakulteBul = $db->prepare("SELECT FakulteID FROM Fakulte WHERE LOWER(FakulteAdi) = LOWER(:ad)");
$fakulteEkle = $db->prepare("INSERT INTO Fakulte (FakulteAdi) VALUES (:ad)");
$bolumBul = $db->prepare("SELECT COUNT(*) FROM Bolum WHERE LOWER(BolumAdi) = LOWER(:ad) AND FakulteID = :f");
$bolumEkle = $db->prepare("INSERT INTO Bolum (BolumAdi, FakulteID) VALUES (:ad, :f)");

$yeniFakulte = 0;
$yeniBolum = 0;

foreach ($veri as $fakulteAdi => $bolumler) {
    $fakulteBul->execute([":ad" => $fakulteAdi]);
    $fakulteId = $fakulteBul->fetchColumn();

    if (!$fakulteId) {
        $fakulteEkle->execute([":ad" => $fakulteAdi]);
        $fakulteId = $db->lastInsertId();
        $yeniFakulte++;
    }

    foreach ($bolumler as $bolumAdi) {
        $bolumBul->execute([":ad" => $bolumAdi, ":f" => $fakulteId]);
        if ($bolumBul->fetchColumn() == 0) {
            $bolumEkle->execute([":ad" => $bolumAdi, ":f" => $fakulteId]);
            $yeniBolum++;
        }
    }
}

echo "Eklenen fakülte: $yeniFakulte\n";
echo "Eklenen bölüm: $yeniBolum\n";
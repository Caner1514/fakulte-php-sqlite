<?php
require "baglanti.php";

$db->exec("PRAGMA foreign_keys = ON");

$db->exec("
CREATE TABLE IF NOT EXISTS Ogrenci (
    OgrenciID INTEGER PRIMARY KEY AUTOINCREMENT,
    Ad TEXT NOT NULL,
    Soyad TEXT NOT NULL,
    BolumID INTEGER,
    GirisYili INTEGER,
    FOREIGN KEY (BolumID) REFERENCES Bolum(BolumID)
);
CREATE TABLE IF NOT EXISTS OgretimUyesi (
    UyeID INTEGER PRIMARY KEY AUTOINCREMENT,
    Unvan TEXT,
    Ad TEXT NOT NULL,
    Soyad TEXT NOT NULL,
    BolumID INTEGER,
    FOREIGN KEY (BolumID) REFERENCES Bolum(BolumID)
);
CREATE TABLE IF NOT EXISTS Ders (
    DersID INTEGER PRIMARY KEY AUTOINCREMENT,
    DersAdi TEXT NOT NULL,
    Kredi INTEGER,
    BolumID INTEGER,
    UyeID INTEGER,
    FOREIGN KEY (BolumID) REFERENCES Bolum(BolumID),
    FOREIGN KEY (UyeID) REFERENCES OgretimUyesi(UyeID)
);
CREATE TABLE IF NOT EXISTS Kayit (
    KayitID INTEGER PRIMARY KEY AUTOINCREMENT,
    OgrenciID INTEGER,
    DersID INTEGER,
    Puan INTEGER,
    FOREIGN KEY (OgrenciID) REFERENCES Ogrenci(OgrenciID),
    FOREIGN KEY (DersID) REFERENCES Ders(DersID)
);
");

// Tablolar boşsa örnek verileri ekle (script tekrar çalışsa da çift kayıt olmaz)
if ($db->query("SELECT COUNT(*) FROM Ogrenci")->fetchColumn() == 0) {
    $db->exec("
    INSERT INTO Ogrenci (Ad, Soyad, BolumID, GirisYili) VALUES
    ('Ahmet','Yılmaz',1,2023), ('Ayşe','Demir',1,2024),
    ('Mehmet','Kaya',2,2023), ('Elif','Şahin',3,2022),
    ('Can','Öztürk',3,2024), ('Zeynep','Aydın',4,2023),
    ('Burak','Çelik',5,2023), ('Selin','Arslan',5,2024),
    ('Emre','Koç',6,2022), ('Deniz','Yıldız',6,2024);

    INSERT INTO OgretimUyesi (Unvan, Ad, Soyad, BolumID) VALUES
    ('Prof. Dr.','Ali','Korkmaz',1), ('Doç. Dr.','Fatma','Yıldırım',2),
    ('Prof. Dr.','Hasan','Polat',3), ('Dr. Öğr. Üyesi','Leyla','Erdem',4),
    ('Prof. Dr.','Murat','Tunç',5), ('Doç. Dr.','Gül','Aksoy',6);

    INSERT INTO Ders (DersAdi, Kredi, BolumID, UyeID) VALUES
    ('Bilgi Erişim',3,1,1), ('Veritabanı Yönetimi',4,1,1),
    ('Osmanlı Tarihi',3,2,2), ('Anatomi',5,3,3),
    ('Hemşirelikte Temel İlkeler',4,4,4), ('Mikroekonomi',4,5,5),
    ('Muhasebe',4,6,6), ('Pazarlama İlkeleri',3,6,6);

    INSERT INTO Kayit (OgrenciID, DersID, Puan) VALUES
    (1,1,85),(1,2,90),(2,1,70),(2,2,78),(3,3,88),
    (4,4,92),(5,4,65),(6,5,80),(7,6,75),(8,6,82),
    (9,7,60),(9,8,72),(10,7,88),(10,8,91);
    ");
    echo "Tablolar oluşturuldu ve örnek veriler eklendi.\n";
} else {
    echo "Veriler zaten var, bir şey eklenmedi.\n";
}
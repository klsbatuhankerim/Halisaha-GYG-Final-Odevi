<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $yas = $_POST['yas'];
    $mevki = $_POST['mevki'];
    $telefon = $_POST['telefon'];

    try {
        $db = new PDO('sqlite:halisaha.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $db->prepare("INSERT INTO users (ad, soyad, yas, mevki, telefon) VALUES (:ad, :soyad, :yas, :mevki, :telefon)");
        $stmt->bindParam(':ad', $ad);
        $stmt->bindParam(':soyad', $soyad);
        $stmt->bindParam(':yas', $yas);
        $stmt->bindParam(':mevki', $mevki);
        $stmt->bindParam(':telefon', $telefon);
        
        $stmt->execute();
        
        echo "<script>alert('Kayıt Başarılı'); window.location.href = 'register.html';</script>";
        
    } catch (PDOException $e) {
        echo "Hata: " . $e->getMessage();
    }
}
?>

<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $db = new PDO('sqlite:halisaha.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("SELECT * FROM yardimci_adminler WHERE kullanici_adi = :username AND sifre = :password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['yardimci_admin'] = true;
            header("Location: yardimci_admin_paneli.php");
            exit();
        } else {
            echo "<script>alert('Kullanıcı adı veya şifre hatalı!'); window.location.href = 'yardimci_admin_giris.html';</script>";
        }
    } catch (PDOException $e) {
        echo "Hata: " . $e->getMessage();
    }
}
?>

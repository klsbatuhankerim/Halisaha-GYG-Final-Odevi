<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $db = new PDO('sqlite:halisaha.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //sql injection açığımIN KAPATILMASI
        $stmt = $db->prepare("SELECT * FROM admin WHERE username = :username AND password = :password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin) {
            $_SESSION['admin'] = $admin['username'];
            header("Location: admin_panel.php");
        } else {
            echo "Kullanıcı adı veya şifre yanlış!";
        }
    } catch (PDOException $e) {
        echo "Hata: " . $e->getMessage();
    }
}
?>




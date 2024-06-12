<?php
session_start();

if (!isset($_SESSION['yardimci_admin'])) {
    header("Location: yardimci_admin_giris.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $yas = $_POST['yas'];
    $mevki = $_POST['mevki'];
    $telefon = $_POST['telefon'];

    try {
        $db = new PDO('sqlite:halisaha.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        
        $stmt = $db->prepare("INSERT INTO users (ad, soyad, yas, mevki, telefon) VALUES (:ad, :soyad, :yas, :mevki, :telefon)");
        $stmt->bindParam(':ad', $ad);
        $stmt->bindParam(':soyad', $soyad);
        $stmt->bindParam(':yas', $yas);
        $stmt->bindParam(':mevki', $mevki);
        $stmt->bindParam(':telefon', $telefon);
        
        $stmt->execute();
        
        echo "<script>alert('Kayıt başarılı!');</script>";
        echo "<meta http-equiv='refresh' content='0'>"; // Sayfayı yenile
    } catch (PDOException $e) {
        echo "Hata: " . $e->getMessage();
    } finally {
        $db = null;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yardımcı Admin Paneli</title>
    <style>
        body {
            background-image: url('halisaha.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            color: #fff;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            margin: auto;
            margin-top: 100px;
        }
        input[type="text"],
        input[type="number"],
        input[type="tel"],
        select,
        input[type="submit"] {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        h2 {
            margin-top: 0;
        }
    </style>
    <script>
        let timeout;

        function resetTimeout() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                window.location.href = 'yardimci_admin_giris.html';
            }, 10000); // 10 saniye (10,000 milisaniye)
        }

        document.addEventListener('mousemove', resetTimeout);
        document.addEventListener('keypress', resetTimeout);
        document.addEventListener('scroll', resetTimeout);
        document.addEventListener('click', resetTimeout);

        window.onload = resetTimeout;
    </script>
    
</head>
<body>
    <div class="container">
        <h2>Yardımcı Admin Paneli</h2>
        <form action="" method="post">
            <label for="ad">Ad:</label><br>
            <input type="text" id="ad" name="ad" required><br>
            <label for="soyad">Soyad:</label><br>
            <input type="text" id="soyad" name="soyad" required><br>
            <label for="yas">Yaş:</label><br>
            <input type="number" id="yas" name="yas" required><br>
            <label for="mevki">Mevki:</label><br>
            <select id="mevki" name="mevki" required>
                <option value="" disabled selected>Mevki seç</option>
                <option value="kaleci">Kaleci</option>
                <option value="defans">Defans</option>
                <option value="ortasaha">Orta Saha</option>
                <option value="forvet">Forvet</option>
            </select><br>
            <label for="telefon">Telefon:</label><br>
            <input type="tel" id="telefon" name="telefon" placeholder="0(000) 000 00 00"><br><br>
            <input type="submit" value="Kaydol">
        </form>
        <br>
        <a href="yardimci_admin_logout.php">Çıkış Yap</a>
    </div>
</body>
</html>

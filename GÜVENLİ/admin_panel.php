<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.html");
    exit();
}

try {
    $db = new PDO('sqlite:halisaha.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Kullanıcıyı silme işlemi
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
        $user_id = $_POST['user_id'];
        $delete_stmt = $db->prepare("DELETE FROM users WHERE id = :id");
        $delete_stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $delete_stmt->execute();
    }

    // Kullanıcıyı güncelleme işlemi
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
        $user_id = $_POST['user_id'];
        $ad = $_POST['ad'];
        $soyad = $_POST['soyad'];
        $yas = $_POST['yas'];
        $mevki = $_POST['mevki'];
        $telefon = $_POST['telefon'];

        $update_stmt = $db->prepare("UPDATE users SET ad = :ad, soyad = :soyad, yas = :yas, mevki = :mevki, telefon = :telefon WHERE id = :id");
        $update_stmt->bindParam(':ad', $ad);
        $update_stmt->bindParam(':soyad', $soyad);
        $update_stmt->bindParam(':yas', $yas, PDO::PARAM_INT);
        $update_stmt->bindParam(':mevki', $mevki);
        $update_stmt->bindParam(':telefon', $telefon);
        $update_stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $update_stmt->execute();
    }

    // Seçilen kullanıcıyı getirme işlemi
    $selected_user = null;
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'get') {
        $user_id = $_POST['user_id'];
        $select_stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $select_stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $select_stmt->execute();
        $selected_user = $select_stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tüm kullanıcıları alma işlemi
    $stmt = $db->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli</title>
    <style>
        body {
            background-image: url('halisaha.jpg'); /* Halısaha fotoğrafının dosya adını buraya ekleyin */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: rgba(0, 0, 0, 0.8); /* Formun arka planını biraz şeffaf yaparak okunabilirliği artırın */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Hafif gölge efekti */
            width: 80%;
            max-width: 800px;
            text-align: center;
        }
        h2 {
            margin-top: 0;
            color: #fff; /* Başlık rengini değiştirin */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #fff;
            color: #000;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
        }
        a:hover {
            text-decoration: underline;
        }
        form {
            margin-top: 20px;
        }
        select, input[type="submit"], input[type="text"], input[type="number"], input[type="tel"] {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: calc(100% - 22px); /* Genişlik hesaplama */
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
    </style>
    <script>
        let timeout;

        function resetTimeout() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                window.location.href = 'admin_login.html'; 
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
        <h2>Admin Paneli</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Ad</th>
                <th>Soyad</th>
                <th>Yaş</th>
                <th>Mevki</th>
                <th>Telefon</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['id']); ?></td>
                <td><?php echo htmlspecialchars($user['ad']); ?></td>
                <td><?php echo htmlspecialchars($user['soyad']); ?></td>
                <td><?php echo htmlspecialchars($user['yas']); ?></td>
                <td><?php echo htmlspecialchars($user['mevki']); ?></td>
                <td><?php echo htmlspecialchars($user['telefon']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <form action="" method="post">
            <label for="user_id">Silmek için Kullanıcı ID'sini seçin:</label><br>
            <select id="user_id" name="user_id" required>
                <?php foreach ($users as $user): ?>
                <option value="<?php echo htmlspecialchars($user['id']); ?>"><?php echo htmlspecialchars($user['id']); ?></option>
                <?php endforeach; ?>
            </select><br>
            <input type="hidden" name="action" value="delete">
            <input type="submit" value="Sil">
        </form>
        <br>
        <form action="" method="post">
            <label for="user_id_get">Getirilecek Kullanıcı ID'sini seçin:</label><br>
            <select id="user_id_get" name="user_id" required>
                <?php foreach ($users as $user): ?>
                <option value="<?php echo htmlspecialchars($user['id']); ?>"><?php echo htmlspecialchars($user['id']); ?></option>
                <?php endforeach; ?>
            </select><br>
            <input type="hidden" name="action" value="get">
            <input type="submit" value="Getir">
        </form>
        <br>
        <?php if ($selected_user): ?>
        <form action="" method="post">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($selected_user['id']); ?>">
            <label for="ad">Ad:</label><br>
            <input type="text" id="ad" name="ad" value="<?php echo htmlspecialchars($selected_user['ad']); ?>" required><br>
            <label for="soyad">Soyad:</label><br>
            <input type="text" id="soyad" name="soyad" value="<?php echo htmlspecialchars($selected_user['soyad']); ?>" required><br>
            <label for="yas">Yaş:</label><br>
            <input type="number" id="yas" name="yas" value="<?php echo htmlspecialchars($selected_user['yas']); ?>" required><br>
            <label for="mevki">Mevki:</label><br>
            <select id="mevki" name="mevki" required>
                <option value="kaleci" <?php echo ($selected_user['mevki'] == 'kaleci') ? 'selected' : ''; ?>>Kaleci</option>
                <option value="defans" <?php echo ($selected_user['mevki'] == 'defans') ? 'selected' : ''; ?>>Defans</option>
                <option value="ortasaha" <?php echo ($selected_user['mevki'] == 'ortasaha') ? 'selected' : ''; ?>>Orta Saha</option>
                <option value="forvet" <?php echo ($selected_user['mevki'] == 'forvet') ? 'selected' : ''; ?>>Forvet</option>
            </select><br>
            <label for="telefon">Telefon:</label><br>
            <input type="tel" id="telefon" name="telefon" value="<?php echo htmlspecialchars($selected_user['telefon']); ?>" required><br>
            <input type="hidden" name="action" value="update">
            <input type="submit" value="Güncelle">
        </form>
        <?php endif; ?>
        <br>
        <a href="admin_logout.php">Çıkış Yap</a>
    </div>
</body>
</html>

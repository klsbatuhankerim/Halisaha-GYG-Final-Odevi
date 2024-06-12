<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yorum Sayfası</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-image: url('halisaha.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #fff;
            text-align: center;
        }
        .container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            margin: auto;
            margin-top: 100px;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px 0;
            border: none;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
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
                window.location.href = 'index.html'; 
            }, 10000);
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
    <h1>Yorum Sayfası</h1>
    <?php
    // Veritabanına bağlan
    $db = new SQLite3('halisaha.db');

    // Yorumları kaydet
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = ($_POST['name']);//XSS AÇIĞIM
        $comment = ($_POST['comment']);//XSS AÇIĞIM
        $stmt = $db->prepare('INSERT INTO comments (name, comment) VALUES (:name, :comment)');
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':comment', $comment, SQLITE3_TEXT);
        $stmt->execute();
    }

    // Yorumları getir
    $results = $db->query('SELECT name, comment FROM comments');
    ?>
    <form id="commentForm" method="post" action="">
        <label for="name">Adınız:</label><br>
        <input type="text" id="name" name="name" required><br>
        <label for="comment">Yorumunuz:</label><br>
        <textarea id="comment" name="comment" required></textarea><br>
        <input type="submit" value="Gönder">
    </form>
    <div id="comments">
        <h2>Yorumlar</h2>
        <ul id="commentList">
            <?php
            while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
                echo "<li><strong>" .($row['name']) . ":</strong> " . ($row['comment']) . "</li>";//XSS AÇIĞIM
            }
            ?>
        </ul>
    </div>
    </div>
</body>
</html>

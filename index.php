<?php
    if (!session_id()) session_start();
    $token = base64_encode(random_bytes(64));
    $_SESSION['token'] = $token;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
    <form action="function.php" method="POST" class="main-form" onsubmit="return false">
        <h1>Введите вашу дату рождения</h1>
        <p class="token">Токен: <?=$token?></p>
        <label for="birthday-date">День рождения:</label>
        <input type="date" id="birthday-date" name="birthday_date" value="<?=date('Y-m-d')?>">
        <label for="days-will-be">Когда мне будет: </label>
        <input type="text" name="days_will_be" id="days-will-be" value="10000" placeholder="дней">
        <input type="hidden" value="/lab_03" name="page_from">
        <input type="hidden" value="<?=$token?>" name="token">
    </form>
    <div class="answer" hidden>
        Some text
    </div>
</body>
</html>
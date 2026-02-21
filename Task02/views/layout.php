<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎮 Калькулятор</h1>
            <nav>
                <a href="/" class="<?= $currentPage === 'game' ? 'active' : '' ?>">Играть</a>
                <a href="/history.php" class="<?= $currentPage === 'history' ? 'active' : '' ?>">История</a>
            </nav>
        </header>
        
        <main>
            <?= $content ?>
        </main>
    </div>
</body>
</html>

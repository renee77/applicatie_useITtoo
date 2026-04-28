<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Mijn App' ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header><h1>Mijn PHP App</h1></header>
    <main>
        <?= $content ?? '' ?>
    </main>
    <footer><p>&copy; 2025</p></footer>
    <script src="/assets/js/app.js"></script>
</body>
</html>

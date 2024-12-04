<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h3>Dashboard</h3>
    
    <p>Bonjour, <?= $user['nom'] ?></p>
    
    <a href="<?= BASE_URL ?>/auth/logout">Se déconnecter</a>
</body>
</html>
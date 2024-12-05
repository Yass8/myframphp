<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
<style>
    body{
    display: flex;
    justify-content: center;
    }

    h3{
        text-align: center;
    }

    .form-control{
        margin-top: 10px;
        margin-bottom: 10px;
    }

    li{
        color: red;
    }
</style>
</head>
<body>
    <div class="bloc">
        <h3>Connexion</h3>
        <?php if(isset($errors) && count($errors) > 0 )  : ?>
            <div class="">
                <ul>
                <?php foreach($errors as $error) : ?>
                    <li><?php echo $error ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form action="<?= BASE_URL ?>/auth/signIn" method="POST">
            <div class="form-control">
                <label for="email">Adresse email</label>
                <input type="text" name="email" id="email" required>
            </div>
            
            <div class="form-control">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" required>
            </div>
            <a href="<?= BASE_URL ?>/auth/register">Je n'ai pas de compte</a>
            <button type="submit" name="signIn">Se connecter</button>
        </form>
    </div>
</body>
</html>
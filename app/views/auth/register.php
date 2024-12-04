<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
</style>
</head>
<body>
    <div class="bloc">
        <h3>Inscription</h3>
        <form action="<?= BASE_URL ?>/auth/signUp" method="POST">

            <div class="form-control">
                <label for="nom">Nom</label>
                <input type="text" name="nom" id="nom" required>
            </div>

            <div class="form-control">
                <label for="email">Adresse email</label>
                <input type="text" name="email" id="email" required>
            </div>
            
            <div class="form-control">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" required>
            </div>
            <a href="<?= BASE_URL ?>/auth/login">J'ai déjà un compte</a>
            <button type="submit" name="signUp">S'inscrire</button>
        </form>
    </div>
</body>
</html>

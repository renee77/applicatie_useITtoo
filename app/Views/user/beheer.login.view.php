<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login || useITtoo</title>
  <!--The two needed stylesheets -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/shared/style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/beheer/beheer.login.css">
</head>
<body>
  <img src="<?= BASE_URL ?>/assets/images/logos/licht-logo.png"/>
<!--The form for logging in, with the post method, as to make sure information will not be directly available to the users through for example the link-->
  <form method="POST" action="#">
    <label for="username"></label>
    <input type="text" placeholder="Gebruikersnaam" name="username" class="logInInput" required />
    <label for="password"></label>
    <input type="password" placeholder="Wachtwoord" name="password" class="logInInput" required />
    <input type="submit" value="Log in" class="logInBtn"/>
  </form>
</body>
</html>
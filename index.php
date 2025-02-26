<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style> <?php
    include("style.css");
    ?></style>
   
</head>
<body>
<div class="video-container">
        <video autoplay muted loop id="background-video">
            <source src="sit/videos/3.mp4" type="video/mp4"> <!-- Remplacez 'votre_video.mp4' par le chemin de votre vidéo -->
        </video>
    </div>
    <div class="log">
        <h1>Login</h1>
        <?php
        // Inclure la configuration de la base de données
        include("php/config.php");

        // Vérifier si le formulaire a été soumis
        if (isset($_POST['login'])) {
            // Obtenir les informations d'identification de l'utilisateur
            $email = $_POST['email'];
            $password =  $_POST['password'];
        
            // Requête pour vérifier les informations d'identification dans la base de données
            $query = mysqli_query($con, "SELECT username   FROM users WHERE email = '$email' and password ='$password'");
 
            if (mysqli_num_rows($query) > 0) {
                $row = mysqli_fetch_assoc($query);
                    // Connexion réussie, afficher un message de bienvenue
                    $username = $row['username'];
                    echo "<div class='message'>
                    <p>Bonjour, $username!</p>
                     <a href='store.php'>GO</a>
                    </div><br>";
            
            } else {
                // Email non trouvé
                echo "<div class='message'>
                <p>Email ou mot de passe incorrect.</p>
                </div><br>";
            }
        }
        
         else {
        ?>
            <!-- Formulaire de connexion -->
            <form method="POST" action="index.php">
                <div class="con">

                    <p>Email</p>
                    <input type="email" name="email" placeholder="Votre email" class="email" required>
                    <p>Mot de passe</p>
                    <input type="password" name="password" placeholder="Votre mot de passe" class="email" required>
                    <br>
                    <input type="submit" name="login" value="Se connecter" class="btn">
                </div>
                <div class="acc">
                    <p>Pas encore membre? <a href="register.php">S'inscrire</a></p>
                </div>
            </form>
        <?php
        }
        ?>
    </div>
</body>
</html>

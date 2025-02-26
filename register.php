<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
    <style> <?php
    include("style.css");
    ?></style>
   
</head>
<body>
<div class="video-container">
        <video autoplay muted loop id="background-video">
            <source src="sit/videos/3.mp4" type="video/mp4"> <!-- Remplacez 'votre_video.mp4' par le chemin de votre vidéo -->
            Votre navigateur ne supporte pas la vidéo HTML5.
        </video>
    </div>
    <div class="log">
        <h1>Sign Up</h1>
        <?php
        include("php/config.php");

        if (isset($_POST['submit'])) {
            $username = mysqli_real_escape_string($con, $_POST['username']);
            $email = mysqli_real_escape_string($con, $_POST['email']);
            $age = mysqli_real_escape_string($con, $_POST['age']);
            $password = mysqli_real_escape_string($con, $_POST['password']);

            // Check for existing email
            $verify_query = mysqli_query($con, "SELECT Email FROM users WHERE Email = '$email'");
            if (mysqli_num_rows($verify_query) != 0) {
                echo "<div class='message'>
                <p>This email is already used, please try another one!</p>
                </div><br>";
                echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button></a>";
            } else {
                // Insert new user
                $result = mysqli_query($con, "INSERT INTO users (Username, Email, Age, Password) VALUES ('$username', '$email', '$age', '$password')");
                if ($result) {
                    echo "<div class='message'>
                    <p>Registration successful!</p>
                    </div><br>";
                    echo "<a href='index.php'><button class='btn'>Login Now</button></a>";
                } else {
                    echo "<div class='message'>
                    <p>Error occurred during registration.</p>
                    </div><br>";
                }
            }
        } else {
        ?>
            <form method="POST" action="">
                <div class="con">
                    <p>Username</p>
                    <input type="text" name="username" placeholder="Username" class="email" required>
                    <p>Email</p>
                    <input type="email" name="email" placeholder="Your email" class="email" required>
                    <p>Age</p>
                    <input type="number" name="age" placeholder="Age" class="email" required>
                    <p>Password</p>
                    <input type="password" name="password" placeholder="Password" class="email" required>
                    <br>
                    <input type="submit" name="submit" value="Sign Up" class="btn">
                </div>
                <div class="acc">
                    <p>Already a member? <a href="index.php">Sign In</a></p>
                </div>
            </form>
        <?php
        }
        ?>
    </div>
</body>
</html>

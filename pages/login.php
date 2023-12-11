<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "schema";
    $conn = mysqli_connect($host, $username, $password, $dbname);

    if (isset($_POST["login"])) {
        $email = $_POST["email"];
        $pword = $_POST["password"];
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if ($user) {
            if (password_verify($pword, $user["password"])) {
                session_start();
                $_SESSION["user"] = $user["id"];
                $_SESSION["status"] = $user["role"];
                header("Location: index.php");
                die();
            } else {
                echo "<div class='message'>Password does not match</div>";
            }
        } else {
            echo "<div class='message'>Email does not match</div>";
        }
    }
    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <header>
        <div class="header-container">
            <img class="dolphin-img" src="../img/dolphin.jpg" alt="Dolphin CRM Logo">
            <p>Dolphin CRM</p>
        </div>
    </header>

    <main>
        <section id="login">
            <form method="post" action="login.php">
                <h1>Login</h1>
                <input type="email" name="email" placeholder="Email address">
                <br>
                <input type="password" name="password" placeholder="Password">
                <br>
                <input id="login_button" name="login" type="submit" class="submit" value="Login">
            </form>
        </section>
    </main>

		<hr class="hr">

    <footer>
        <h5 class="copyright">Copyright @ 2023 Dolphin CRM</h5>

        <?php
            session_start();

            if (isset($_SESSION["message"])) {
                echo "<div class='message'>{$_SESSION["message"]}</div>";
                unset($_SESSION["message"]);
            }
        ?>

    </footer>
</body>

</html>



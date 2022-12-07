<?php 
session_start();
require_once '../connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dolphin CRM</title>
    <link href="style.css" type="text/css" rel="stylesheet" />
    <link href="login.css" type="text/css" rel="stylesheet" />
		
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="scripts/login.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
    crossorigin="anonymous">
</head>


<?php
//Login

$password = filter_var($_POST['pswd'], FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

$regex_string = "/^[a-zA-Z]+$/";
$regex_password = "/^(?=.+[0-9])(?=.+[a-z])(?=.+[A-Z])([a-zA-Z0-9]+)$/";

if(filter_var($email, FILTER_VALIDATE_EMAIL)){
    if(sizeof($password) >= 8 && preg_match($regex_password, $password)){
        $results = null;
        try{
            $query = "SELECT * FROM Users WHERE Users.email = '{$email}'";
            $stmt = $conn->query($query);
            $results = $stmt->fetchALL(PDO::ASSOC);
        }catch(Error $e){
            echo "Login Error: ".$e->msgfmt_format_message;
        }
        if(sizeof($results) < 0){
            echo "NO-MATCH";
        }else if(sizeof($results) == 1){
            if($results[0]['email'] == $email){ 
                if(password_verify($password, $results[0]['password'])){
                    $_SESSION['email'] = $email;
                    $_SESSION['logged-in'] = true;
                    $_SESSION['userid'] = $results[0]['id'];
                    echo "SL";
                }else{
                    echo "Login Error: Password Do Not Match";
                }
            }else if($results[0]['email'] == $email && $email == "admin@project2.com"){
                if(password_verify($password, $results[0]['password'])){
                    $_SESSION['email'] = $email;
                    $_SESSION['logged-in'] = true;
                    $_SESSION['userid'] = $results[0]['id'];
                    echo "SL";
                }else{
                    echo "Login Error: Password Entered Do Not Match";
                }
            }else{
                echo "Login Error: Email Entered Do Not Match";
            }
        }
    }else{
        echo "Login Error: Password Entered Invalid";
    }
}else{
    echo "Login Error: Email is not in the correct format";
}

$conn = null;
?>

<body>
    <header>
        <ul>
            <li id="header">
                <i class="dolphin"></i>
                Dolphin CRM
            </li>
        </ul>
    </header>
    <main id="main-login">
    <div id="login">
        <form action="login.php" onsubmit="return login_check(this.password)" method="POST">
            <h2>Login</h2>
            <label for="email">Email:</label>
            <input id="email" type="email" name="email" placeholder="Email" value="<?= $email?>"required><br>
            <label for="password">Password:</label>
            <input id="password" type="password" name="password" placeholder="Password" required><br>
            <input type="submit" value="Login">
        </form>
    </div>
    <div id="messages"><?=$message?></div>
    </main>

    
</body>
</html>

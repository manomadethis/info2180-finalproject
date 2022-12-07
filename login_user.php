<?php 
session_start();
require_once '../connect.php';

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

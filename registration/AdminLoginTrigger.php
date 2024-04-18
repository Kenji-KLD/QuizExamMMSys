<?php
include "AdminLoginClass.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $login = new LogIn($username, $password);

    if ($login->validate()) {
        if ($login->login()) {
            echo "Welcome";
        } else {
            echo "Wrong credentials. Please try again.";
        }
    } else {
        echo "All fields are required.";
    }
}

?>
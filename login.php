<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        print_r($user);
        if (password_verify($password, $user['password'])) {
            setcookie("sessionid", $user['id'], time() + (86400 * 30), "/");
            setcookie("username", $user['first_name'], time() + (86400 * 30), "/");
            header('Location: dashboard.php');
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No account found with that email!";
    }
    

    $conn->close();
}
?>

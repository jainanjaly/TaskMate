<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $location = $_POST['location'];

    $sql = "INSERT INTO users (first_name, last_name, email, password, location) VALUES ('$first_name', '$last_name', '$email', '$password', '$location')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
        header('Location: welcome.html');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

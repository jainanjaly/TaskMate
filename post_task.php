<?php

include 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_COOKIE['sessionid'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $est_time_hours = $_POST['est_time_hours'];
    $payment = $_POST['payment'];
    $location = $_POST['location'];
    $deadline = $_POST['deadline'];

    $sql = "INSERT INTO tasks (user_id, title, description, est_time_hours, payment, location, deadline) 
            VALUES ('$user_id', '$title', '$description', '$est_time_hours', '$payment', '$location', '$deadline')";

    if ($conn->query($sql) === TRUE) {
        echo "Task posted successfully!";
        header('Location: dashboard.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

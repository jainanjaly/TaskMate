<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $user_id = $_SESSION['user_id'];
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
        header('Location: browse_tasks.html');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

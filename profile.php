<?php
include 'config.php';

$user_id = $_COOKIE['user_id'];

$sql = "SELECT first_name, last_name, email, location, reputation_score FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Pass data to the front-end for rendering the profile details
    echo json_encode($user); 
} else {
    echo "User not found";
}
?>

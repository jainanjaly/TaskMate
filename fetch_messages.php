<?php
include 'config.php';
session_start();
$user_id = $_COOKIE['user_id'];
$other_user_id = $_GET['user'];

$sql = "SELECT * FROM messages WHERE (sender_id = '$user_id' AND receiver_id = '$other_user_id') 
        OR (sender_id = '$other_user_id' AND receiver_id = '$user_id') ORDER BY created_at";
$result = $conn->query($sql);

$messages = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}
echo json_encode($messages);
?>

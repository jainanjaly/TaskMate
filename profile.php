<?php
include 'config.php';

// Get the user ID from the session cookie
$user_id = $_COOKIE['sessionid'];

// Fetch user details
$sql = "SELECT first_name, last_name, email, `location` FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);
$result_abc = $result->fetch_assoc();

// Fetch task statistics
$stats_sql = "SELECT 
    (SELECT COUNT(*) FROM tasks WHERE user_id = '$user_id') AS tasks_given,
    (SELECT COUNT(*) FROM tasks WHERE doer_id = '$user_id') AS tasks_completed,
    (SELECT SUM(payment) FROM tasks WHERE doer_id = '$user_id') AS total_earnings"; // Assuming you have a ratings table
$stats_result = $conn->query($stats_sql);
$stats = $stats_result->fetch_assoc();


// Combine all data
$response = [
    'user' => $result_abc,
    'stats' => $stats
];

// Set header for JSON response
header('Content-Type: application/json');
echo json_encode($response);

// Close database connection
$conn->close();
?>

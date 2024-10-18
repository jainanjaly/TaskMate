<?php
session_start();

include 'config.php'; // Include the database connection file

if (!isset($_COOKIE['sessionid'])) {
    header("Location: login.php"); // Redirect if user is not logged in
    exit();
}

$user_id = $_COOKIE['sessionid']; // Assuming you're storing the user_id in session
$doer_id = $_GET['doer_id']; // Assuming you're storing the
$task_id = $_GET['task_id']; // Assuming you're storing the

$sql_post = "UPDATE tasks SET doer_id = ? WHERE id = ?";
$stmt_post = $conn->prepare($sql_post);
$stmt_post->bind_param("ii", $doer_id, $task_id);
$stmt_post->execute();
$result_post = $stmt_post->get_result();

// Fetch tasks posted by the user
$sql_posted = "SELECT * FROM tasks WHERE user_id = ?";
$stmt_posted = $conn->prepare($sql_posted);
$stmt_posted->bind_param("i", $user_id);
$stmt_posted->execute();
$result_posted = $stmt_posted->get_result();

// Fetch tasks applied by the user

$sql_applied = "SELECT * FROM tasks WHERE doer_id = ?";
$stmt_applied = $conn->prepare($sql_applied);
$stmt_applied->bind_param("i", $doer_id);
$stmt_applied->execute();
$result_applied = $stmt_applied->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Summary | TaskMate</title>
    <link rel="stylesheet" href="styles.css">
    <style>
       /* User Task Summary Styles */
.container {
    display: flex;
    flex-direction: column;
    gap: 40px;
    padding: 20px;
}

.task-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.task-card {
    background-color: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #0791bb;
    margin-bottom: 20px;
}

footer {
    background-color: #0791bb;
    color: white;
    text-align: center;
    padding: 15px;
}
    </style>
</head>
<body>
    <header>
        <h1>Your Task Overview</h1>
    </header>

    <div class="container">
        <!-- Tasks posted by user -->
        <section>
            <h2>Tasks You've Posted</h2>
            <div class="task-list">
                <?php
                if ($result_posted->num_rows > 0) {
                    while ($task = $result_posted->fetch_assoc()) {
                        echo '<div class="task-card">';
                        echo '<h3>' . htmlspecialchars($task['title']) . '</h3>';
                        echo '<p><strong>Description:</strong> ' . htmlspecialchars($task['description']) . '</p>';
                        echo '<p><strong>Estimated Time:</strong> ' . htmlspecialchars($task['est_time_hours']) . ' hours</p>';
                        echo '<p><strong>Payment:</strong> $' . number_format($task['payment'], 2) . '</p>';
                        echo '<p><strong>Location:</strong> ' . htmlspecialchars($task['location']) . '</p>';
                        echo '<p><strong>Deadline:</strong> ' . htmlspecialchars($task['deadline']) . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>You have not posted any tasks yet.</p>';
                }
                ?>
            </div>
        </section>

        <!-- Tasks applied for by user -->
        <section>
            <h2>Tasks You've Applied For</h2>
            <div class="task-list">
                <?php
                if ($result_applied->num_rows > 0) {
                    while ($task = $result_applied->fetch_assoc()) {
                        echo '<div class="task-card">';
                        echo '<h3>' . htmlspecialchars($task['title']) . '</h3>';
                        echo '<p><strong>Description:</strong> ' . htmlspecialchars($task['description']) . '</p>';
                        echo '<p><strong>Estimated Time:</strong> ' . htmlspecialchars($task['est_time_hours']) . ' hours</p>';
                        echo '<p><strong>Payment:</strong> $' . number_format($task['payment'], 2) . '</p>';
                        echo '<p><strong>Location:</strong> ' . htmlspecialchars($task['location']) . '</p>';
                        echo '<p><strong>Deadline:</strong> ' . htmlspecialchars($task['deadline']) . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>You have not applied for any tasks yet.</p>';
                }
                ?>
            </div>
        </section>
    </div>

</body>
</html>

<?php
$stmt_posted->close(); // Close the statement
$stmt_applied->close(); // Close the statement
$stmt_post->close();
$conn->close(); // Close the database connection
?>


<?php
include 'config.php'; // Include the database connection file

// Initialize variables for filters
$locationFilter = isset($_GET['location']) ? $_GET['location'] : '';
$paymentFilter = isset($_GET['payment']) ? $_GET['payment'] : 0;
$deadlineFilter = isset($_GET['deadline']) ? $_GET['deadline'] : '';
$sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'default';

// Build SQL query based on filters
$sql = "SELECT * FROM tasks WHERE 1";

if (!empty($locationFilter)) {
    $sql .= " AND location LIKE '%" . $conn->real_escape_string($locationFilter) . "%'";
}

if ($paymentFilter > 0) {
    $sql .= " AND payment >= " . floatval($paymentFilter);
}

if (!empty($deadlineFilter)) {
    $sql .= " AND deadline <= '" . $conn->real_escape_string($deadlineFilter) . "'";
}

// Apply sorting
if ($sortOption == 'payment') {
    $sql .= " ORDER BY payment DESC";
} elseif ($sortOption == 'deadline') {
    $sql .= " ORDER BY deadline ASC";
} else {
    $sql .= " ORDER BY created_at DESC";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskMate | Browse Tasks</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Available Chores</h1>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search tasks...">
        </div>
    </header>

    <aside>
        <div class="filters">
            <h3>Filter by:</h3>
            <form method="GET" action="browse_tasks.php">
                <label for="location">Location:</label>
                <input type="text" name="location" id="locationFilter" placeholder="Enter location" value="<?php echo htmlspecialchars($locationFilter); ?>">

                <label for="payment">Minimum Payment:</label>
                <input type="number" name="payment" id="paymentFilter" placeholder="Enter minimum payment" value="<?php echo htmlspecialchars($paymentFilter); ?>">

                <label for="deadline">Deadline Before:</label>
                <input type="date" name="deadline" id="deadlineFilter" value="<?php echo htmlspecialchars($deadlineFilter); ?>">

                <button type="submit">Apply Filters</button>
            </form>
        </div>

        <div class="sorting">
            <h3>Sort by:</h3>
            <form method="GET" action="browse_tasks.php">
                <input type="hidden" name="location" value="<?php echo htmlspecialchars($locationFilter); ?>">
                <input type="hidden" name="payment" value="<?php echo htmlspecialchars($paymentFilter); ?>">
                <input type="hidden" name="deadline" value="<?php echo htmlspecialchars($deadlineFilter); ?>">

                <select name="sort" id="sortOptions" onchange="this.form.submit()">
                    <option value="default" <?php if ($sortOption == 'default') echo 'selected'; ?>>Default</option>
                    <option value="payment" <?php if ($sortOption == 'payment') echo 'selected'; ?>>Highest Payment</option>
                    <option value="deadline" <?php if ($sortOption == 'deadline') echo 'selected'; ?>>Closest Deadline</option>
                </select>
            </form>
        </div>
    </aside>

    <main>
        <div id="task-list" class="task-list">
            <?php
            // Check if there are any tasks
            if ($result->num_rows > 0) {
                // Loop through all tasks and display them as cards
                while($task = $result->fetch_assoc()) {
                    echo '<div class="task-card">';
                    echo '<h3>' . $task['title'] . '</h3>';
                    echo '<p><strong>Description:</strong> ' . $task['description'] . '</p>';
                    echo '<p><strong>Estimated Time:</strong> ' . $task['est_time_hours'] . ' hours</p>';
                    echo '<p><strong>Payment:</strong> $' . number_format($task['payment'], 2) . '</p>';
                    echo '<p><strong>Location:</strong> ' . $task['location'] . '</p>';
                    echo '<p><strong>Deadline:</strong> ' . $task['deadline'] . '</p>';
                    echo '<div class="task-actions">';
                    echo '<a href="xyz.html?id=' . $task['id'] . '" class="apply-btn">Apply</a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No tasks available at the moment.</p>';
            }
            ?>
        </div>
    </main>

    <footer>
        <p>TaskMate - Simplifying everyday chores</p>
    </footer>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>

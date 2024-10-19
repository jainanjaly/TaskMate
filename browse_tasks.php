<?php
include 'config.php'; // Include the database connection file

// Initialize variables for filters
$locationFilter = isset($_GET['location']) ? $_GET['location'] : '';
$paymentFilter = isset($_GET['payment']) ? floatval($_GET['payment']) : 0;
$deadlineFilter = isset($_GET['deadline']) ? $_GET['deadline'] : '';
$sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'default';
$user_id = $_COOKIE['sessionid'];

// Prepare SQL query
$sql = "SELECT * FROM tasks WHERE user_id != ?"; // Base query
$params = [];
$paramTypes = 'i'; // Start with 'i' for user_id (integer)

$params[] = $user_id; // Add user_id to params array

// Check for location filter
if (!empty($locationFilter)) {
    $sql .= " AND location LIKE ?";
    $params[] = '%' . $locationFilter . '%'; // Add location to params
    $paramTypes .= 's'; // Add 's' for string type
}

// Check for payment filter
if ($paymentFilter > 0) {
    $sql .= " AND payment >= ?";
    $params[] = $paymentFilter; // Add payment to params
    $paramTypes .= 'd'; // Add 'd' for double type
}

// Check for deadline filter
if (!empty($deadlineFilter)) {
    $sql .= " AND deadline <= ?";
    $params[] = $deadlineFilter; // Add deadline to params
    $paramTypes .= 's'; // Add 's' for string (date)
}

// Apply sorting
if ($sortOption == 'payment') {
    $sql .= " ORDER BY payment DESC";
} elseif ($sortOption == 'deadline') {
    $sql .= " ORDER BY deadline ASC";
} else {
    $sql .= " ORDER BY created_at DESC";
}

// Prepare statement
$stmt = $conn->prepare($sql);

// Bind all parameters dynamically in one call
$stmt->bind_param($paramTypes, ...$params); // Single bind_param call

$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>TaskMate | Browse Tasks</title>
    <style>
        /* General reset and font settings */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f5f5;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

header {
    background-color: #0791bb;
    color: white;
    padding: 20px 0;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

header h1 {
    margin: 0;
    font-size: 28px;
}

.search-bar {
    margin: 20px 0;
    text-align: center;
}

.search-bar input {
    width: 60%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

/* Layout */
.container {
    display: flex;
    justify-content: space-between;
    padding: 20px;
    flex-grow: 1;
    width: 100%;
}

aside {
    background-color: #fff;
    padding: 20px;
    width: 250px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding-right: 40px;
}

.filters, .sorting {
    margin-bottom: 30px;
}

.filters h3, .sorting h3 {
    color: #0073e6;
}

.filters input, .sorting select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.filters button {
    padding: 12px;
    background-color: #0791bb;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.filters button:hover {
    background-color: #056a92;
}

/* Task List */
main {
    flex-grow: 1;
    margin-left: 30px;
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
    transition: transform 0.3s ease;
}

.task-card:hover {
    transform: scale(1.03);
}

.task-card h3 {
    color: #0073e6;
    margin-bottom: 10px;
}

.task-card p {
    margin: 10px 0;
    color: #666;
}

.task-actions {
    margin-top: 15px;
}

.apply-btn {
    display: inline-block;
    padding: 10px;
    background-color: #0791bb;
    color: white;
    text-decoration: none;
    border-radius: 4px;
}

.apply-btn:hover {
    background-color: #056a92;
}

/* Footer */
footer {
    background-color: #0791bb;
    color: white;
    padding: 15px;
    text-align: center;
    box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.1);
    position: relative;
    bottom: 0;
    width: 100%;
}
    </style>
</head>
<body>
    <header>
        <h1>Available Chores</h1>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search tasks..." oninput="filterTasks()">
        </div>
    </header>

    <div class="container">
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
                    while($task = $result->fetch_assoc()) {
                        echo '<div class="task-card">';
                        echo '<h3>' . htmlspecialchars($task['title']) . '</h3>';
                        echo '<p><strong>Description:</strong> ' . htmlspecialchars($task['description']) . '</p>';
                        echo '<p><strong>Estimated Time:</strong> ' . htmlspecialchars($task['est_time_hours']) . ' hours</p>';
                        echo '<p><strong>Payment:</strong> $' . number_format($task['payment'], 2) . '</p>';
                        echo '<p><strong>Location:</strong> ' . htmlspecialchars($task['location']) . '</p>';
                        echo '<p><strong>Deadline:</strong> ' . htmlspecialchars($task['deadline']) . '</p>';
                        echo '<div class="task-actions">';
                        echo '<a href="user_tasks.php?doer_id=' . $_COOKIE['sessionid'] . '&task_id=' . $task['id'] . '" class="apply-btn">Apply</a>';
 // Updated link
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No tasks available at the moment.</p>';
                }
                ?>
            </div>
        </main>
    </div>
</body>
</html>

<?php
$stmt->close(); // Close the statement
$conn->close(); // Close the database connection
?>



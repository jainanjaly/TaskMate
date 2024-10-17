<?php
// session_start();

if (!isset($_COOKIE['sessionid'])) {
    // If the session doesn't exist, redirect to login page
    header('Location: index.html');
    // echo "hello";
    exit();
}
?>

<!DOCTYPE html>
<html >
<head>
    <title>TaskMate | Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <header>
        <h1>Welcome,  User #<?php echo $_COOKIE['sessionid']; ?></h1>
    </header>
    <main>
        <div class="dashboard-container">
            <h2>Your Dashboard</h2>
            <div class="dashboard-options">
                <a href="post_task.html">Have a Chore</a>
                <a href="browse_tasks.html">Do a Chore</a>
                <a href="profile.html">View Profile</a>
                <a href="messages.html">Messages</a>
            </div>
        </div>
    </main>
</body>
</html>

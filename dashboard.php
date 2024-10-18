<?php
// session_start();

if (!isset($_COOKIE['sessionid'])) {
    // If the session doesn't exist, redirect to login page
    header('Location: welcome.html');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>TaskMate | Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General reset and font settings */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    height: auto;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* Header styling */
header {
    background-color: #0791bb;
    color: white;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    position: relative;
}

header h1 {
    font-size: 24px;
    margin: 0;
}

.menu {
    position: relative;
}

.hamburger {
    font-size: 28px;
    cursor: pointer;
    color: white;
}

.menu-content {
    display: none;
    position: absolute;
    right: 0;
    top: 40px;
    background-color: #0791bb;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    overflow: hidden;
}

.menu-content a {
    display: block;
    padding: 10px 20px;
    text-decoration: none;
    color: white;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.menu-content a:hover {
    background-color: #056a92;
}

.menu:hover .menu-content {
    display: block;
}

/* Main dashboard styling */
.dashboard-container {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-grow: 1;
    text-align: center;
    margin-top: 200px;
}

.button-container {
    display: flex;
    justify-content: space-around;
    max-width: 400px;
    width: 100%;
}

.button-card {
    background-color: #0791bb;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    padding: 20px;
    transition: transform 0.3s;
    width: 45%;
    text-align: center;
    margin: 10px;
}

.button-card:hover {
    transform: translateY(-5px);
    background-color: #056a92;
}

.button-card a {
    text-decoration: none;
    color: white;
    font-size: 18px;
    transition: color 0.3s ease;
}

.button-card a:hover {
    color: #1E90FF;
}

/* Footer styling */
footer {
    background-color: #0791bb;
    color: white;
    padding: 10px;
    text-align: center;
    box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.1);
    position: fixed;
    bottom: 0;
    width: 100%;
}
</style>
</head>
<body>
    <header>
        <h1>Welcome, User #<?php echo $_COOKIE['sessionid']; ?></h1>
        <div class="menu">
            <div class="hamburger">&#9776;</div>
            <div class="menu-content">
                <a href="profile.html">View Profile</a>
                <a href="messages.html">Messages</a>
                <a href="signout.php">Sign Out</a>
            </div>
        </div>
    </header>
    <main>
        <div class="dashboard-container">
            <div class="button-container">
                <div class="button-card">
                    <a href="post_task.html">Have a Chore</a>
                </div>
                <div class="button-card">
                    <a href="browse_tasks.php">Do a Chore</a>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <p>TaskMate &copy; 2024. All rights reserved.</p>
    </footer>
</body>
</html>

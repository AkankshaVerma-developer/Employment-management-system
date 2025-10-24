<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// ✅ Count employees
$employee_result = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'employee'");
if (!$employee_result) {
    die("Query failed (employee): " . $conn->error);
}
$employee_count = $employee_result->fetch_assoc()['total'];

// ✅ Count projects
$project_result = $conn->query("SELECT COUNT(*) AS total FROM projects");
if (!$project_result) {
    die("Query failed (projects): " . $conn->error);
}
$project_count = $project_result->fetch_assoc()['total'];

// ✅ Count tasks
$task_result = $conn->query("SELECT COUNT(*) AS total FROM tasks");
if (!$task_result) {
    die("Query failed (tasks): " . $conn->error);
}
$task_count = $task_result->fetch_assoc()['total'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employment Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <h2>Employement Management System</h2>
            <ul>
                <li><a href="dashboard.php">📊 Dashboard</a></li>
                <li><a href="login.php">🔐 Login</a></li>
                <li><a href="logout.php">🚪 Logout</a></li>
                <li><a href="pages/projects.php">📁 Projects</a></li>
                <li><a href="pages/tasks.php">📝 Tasks</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1>Welcome, <?php echo $_SESSION['name']; ?> 👋</h1>

            <div class="cards">
                <div class="card">
                    <h3>👥 Employees</h3>
                    <p><?php echo $employee_count; ?></p>
                </div>
                <div class="card">
                    <h3>📂 Projects</h3>
                    <p><?php echo $project_count; ?></p>
                </div>
                <div class="card">
                    <h3>📝 Tasks</h3>
                    <p><?php echo $task_count; ?></p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

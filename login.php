<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    // ✅ FIX: Only 3 placeholders = ?, ?, ? → 3 bind parameters = "sss"
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ? AND role = ?");
    
    // ✅ Bind correctly: 3 strings (s = string)
    $stmt->bind_param("sss", $email, $password, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    // ✅ Check if user exists
    if ($user = $result->fetch_assoc()) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid email, password or role.'); window.history.back();</script>";
    }
}
?>




<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="login-container">
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role" required>
            <option value="">Select Role</option>
            <option value="employee">Employee</option>
            <option value="manager">Manager</option>
        </select>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>

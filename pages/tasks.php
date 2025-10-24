<?php
include '../db.php';

$message = '';
if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

// Handle Add Task
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $project_id = $_POST['project_id'];
    $status = $_POST['status'];

    if (!empty($title) && !empty($project_id)) {
        $sql = "INSERT INTO tasks (title, project_id, status) VALUES ('$title', '$project_id', '$status')";
        if ($conn->query($sql)) {
            header("Location: tasks.php?message=✅ Task added successfully!");
            exit;
        } else {
            $message = "❌ Error: " . $conn->error;
        }
    } else {
        $message = "❌ Please fill all fields.";
    }
}

// Handle Update Task
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $conn->real_escape_string($_POST['title']);
    $project_id = $_POST['project_id'];
    $status = $_POST['status'];

    $conn->query("UPDATE tasks SET title='$title', project_id='$project_id', status='$status' WHERE id=$id");
    header("Location: tasks.php?message=✏️ Task updated successfully!");
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM tasks WHERE id = $id");
    header("Location: tasks.php?message=🗑️ Task deleted successfully!");
    exit;
}

// Fetch Projects
$project_sql = "SELECT * FROM projects";
$projects = $conn->query($project_sql);

// If editing
$edit_task = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_task = $conn->query("SELECT * FROM tasks WHERE id=$edit_id")->fetch_assoc();
}

// Fetch Tasks
$sql = "SELECT tasks.*, projects.title AS project_title FROM tasks 
        LEFT JOIN projects ON tasks.project_id = projects.id ORDER BY tasks.id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Task Management</title>
    <link rel="stylesheet" href="../assets/tasks.css">
</head>
<body>
 <div class="container">
        <h2>📝 Task Management</h2>

        <div class="dashboard-link">
            <a href="../dashboard.php">🏠 Dashboard</a>
        </div>

        <?php if ($message): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST">
            <select name="project_id" required>
                <option value="">-- Select Project --</option>
                <?php
                // Reset project query pointer
                $project_sql = "SELECT * FROM projects";
                $projects = $conn->query($project_sql);
                while ($proj = $projects->fetch_assoc()):
                ?>
                    <option value="<?= $proj['id'] ?>" 
                        <?= isset($edit_task) && $edit_task['project_id'] == $proj['id'] ? 'selected' : '' ?>>
                        <?= $proj['title'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <input type="text" name="title" placeholder="Task Title" required 
                value="<?= $edit_task['title'] ?? '' ?>">

            <select name="status" required>
                <option value="Pending" <?= isset($edit_task) && $edit_task['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="In_progress" <?= isset($edit_task) && $edit_task['status'] == 'In_progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="Completed" <?= isset($edit_task) && $edit_task['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
            </select>

            <?php if (isset($edit_task)): ?>
                <input type="hidden" name="id" value="<?= $edit_task['id'] ?>">
                <button type="submit" name="update" class="btn">✏️ Update Task</button>
                <!-- <a href="tasks.php" class="btn cancel">❌ Cancel</a> -->
            <?php else: ?>
                <button type="submit" name="add" class="btn">Add Task</button>
            <?php endif; ?>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Task Title</th>
                    <th>Project</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['project_title']) ?></td>
                    <td><?= $row['status'] ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td class="actions">
                        <a href="tasks.php?edit=<?= $row['id'] ?>">✏️ Edit</a>
                        <a class="delete" href="tasks.php?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this task?')">🗑️ Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

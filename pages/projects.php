<?php
include '../db.php';

$successMessage = '';

// Add project
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $conn->query("INSERT INTO projects (title, description) VALUES ('$title', '$description')");
    header("Location: projects.php?msg=added");
    exit();
}

// Delete project
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM projects WHERE id=$id");
    header("Location: projects.php?msg=deleted");
    exit();
}

// Edit project
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $conn->query("UPDATE projects SET title='$title', description='$description' WHERE id=$id");
    header("Location: projects.php?msg=updated");
    exit();
}

// Message after actions
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'added') {
        $successMessage = "✅ Project added successfully!";
    } elseif ($_GET['msg'] === 'updated') {
        $successMessage = "✅ Project updated successfully!";
    } elseif ($_GET['msg'] === 'deleted') {
        $successMessage = "🗑️ Project deleted successfully!";
    }
}

// Get all projects
$projects = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");

// Fetch project for edit
$edit_project = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_project = $conn->query("SELECT * FROM projects WHERE id=$edit_id")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Project Management</title>
    <link rel="stylesheet" href="../assets/projects.css"></head>
<body>
    <div class="container">
        <div class="top-bar">
            <h2>📂 Project Management</h2>
            <a href="../dashboard.php">🏠 Dashboard</a>
        </div>

        <form method="POST">
            <div class="form_field">
                <?php if (!empty($successMessage)): ?>
                    <div class="message"><?= $successMessage ?></div>
                <?php endif; ?>

                <?php if ($edit_project): ?>
                    <input class="id" type="hidden" name="id" value="<?= $edit_project['id'] ?>">
                    <input class="title" type="text" name="title" placeholder="Project Title" value="<?= $edit_project['title'] ?>" required>
                    <textarea class="description" name="description" placeholder="Project Description" required><?= $edit_project['description'] ?></textarea>
                    <button type="submit" name="update">Update Project</button>
                <?php else: ?>
                    <input class="title" type="text" name="title" placeholder="Project Title" required>
                    <textarea class="description" name="description" placeholder="Project Description" required></textarea>
                    <button type="submit" name="add">Add Project</button>
                <?php endif; ?>
            </div>     </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $projects->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <a href="?edit=<?= $row['id'] ?>">✏️ Edit</a>
                        <a class="delete" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this project?')">🗑️ Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

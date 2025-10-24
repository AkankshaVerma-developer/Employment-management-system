
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "jira_clone_db";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php
include 'db.php';
$message = "";
$name = $email = $mobile = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($conn->real_escape_string($_POST['name']));
    $email = htmlspecialchars($conn->real_escape_string($_POST['email']));
    $mobile = htmlspecialchars($conn->real_escape_string($_POST['mobile']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

   if ($password !== $confirm_password) {
        $message = "❌ Passwords do not match.";
    } 
    else {
        $check = $conn->query("SELECT * FROM users WHERE email = '$email'");
        if ($check->num_rows > 0) {
            $message = "⚠️ Email already exists. <a href='login.php'>Login here</a>";
        } else
         {
            $conn->query("INSERT INTO users (name, email, password, mobile_number) VALUES ('$name', '$email', '$password', '$mobile')");
            $message = "✅ Registration successful";
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 2000);
            </script>";
        }
    }
}?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <style>
  body {
  font-family: 'Segoe UI', sans-serif;
  background-color: #f3f4f6; /* ✅ Light gray like login.php */
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  animation: fadeIn 1s ease-in;
}


@keyframes fadeIn {
  0% { opacity: 0; transform: translateY(-20px); }
  100% { opacity: 1; transform: translateY(0); }
}

    .form-container {
      background: #fff;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      width: 100%;
    }

    .form-container h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 93%;
      padding: 10px 14px;
      margin-bottom: 16px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 15px;
      transition: border-color 0.3s;
    }

    input:focus {
      border-color: #4f46e5;
      outline: none;
      box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
    }

    button {
      width: 100%;
      padding: 12px;
      border: none;
      font-size: 16px;
      font-weight: bold;
      background: linear-gradient(to right, #3b82f6, #a7f3d0);
      color: black;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background: linear-gradient(to right, #2563eb, #6ee7b7);
    }

    .message {
      text-align: center;
      margin-top: 10px;
      font-size: 15px;
      font-weight:bold;
    }

    .message.success {
      color: green;
    }

    .message.error {
      color: red;
    }

    .bottom-text {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }

    .bottom-text a {
      text-decoration: none;
      color: #2563eb;
    }

    .bottom-text a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="form-container">
    <h2>Register</h2>
    <form method="POST" onsubmit="return validateMobile()">
      <input type="text" name="name" placeholder="Your Name" value="<?= $name ?>" required>
      <input type="email" name="email" placeholder="Your Email" value="<?= $email ?>" required>
      <input type="text" name="mobile" id="mobile" maxlength="10" placeholder="Mobile Number" value="<?= $mobile ?>" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <button type="submit">Sign Up</button>
    </form>

    <?php if (!empty($message)): ?>
      <div class="message <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
        <?= $message ?>
      </div>
    <?php endif; ?>

    <div class="bottom-text">
      Already have an account? <a href="login.php">Login here</a>
    </div>
  </div>

  <script>
    function validateMobile() {
      const mobile = document.getElementById("mobile").value;
      if (!/^\d{10}$/.test(mobile)) {
        alert("❌ Mobile number must be exactly 10 digits.");
        return false;
      }
      return true;
    }
  </script>
</body>
</html>

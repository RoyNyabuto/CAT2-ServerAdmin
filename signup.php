<?php
session_start();
require_once 'includes/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Enter a valid email address.";

    } elseif (strlen($password) < 8) {
        $message = "Password must contain at least 8 characters.";

    } else {

        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {

            $message = "Email already registered.";

        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO users (fullname, email, password)
                 VALUES (?, ?, ?)"
            );

            $stmt->bind_param("sss", $fullname, $email, $hashed_password);

            if ($stmt->execute()) {
                $message = "Registration successful! You can now login.";
            } else {
                $message = "Registration failed.";
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="container">

    <h2>Student Registration</h2>

    <p><?php echo htmlspecialchars($message); ?></p>

    <form method="POST">

        <input type="text" name="fullname"
               placeholder="Full Name" required>

        <input type="email" name="email"
               placeholder="Email Address" required>

        <input type="password" name="password"
               placeholder="Password (minimum 8 characters)" required>

        <button type="submit">Create Account</button>

    </form>

    <p>Already registered?
        <a href="login.php">Login Here</a>
    </p>

</div>

</body>
</html>

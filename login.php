<?php

session_start();
require_once 'includes/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT id, fullname, email, password FROM users WHERE email = ?"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {

        if (password_verify($password, $user['password'])) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['email'] = $user['email'];

            header("Location: dashboard.php");
            exit();

        } else {
            $message = "Incorrect email or password.";
        }

    } else {
        $message = "Incorrect email or password.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="container">

    <h2>Student Login</h2>

    <p><?php echo htmlspecialchars($message); ?></p>

    <form method="POST">

        <input type="email" name="email"
               placeholder="Email Address" required>

        <input type="password" name="password"
               placeholder="Password" required>

        <button type="submit">Login</button>

    </form>

    <p>Don't have an account?
        <a href="signup.php">Register Here</a>
    </p>

</div>

</body>
</html>

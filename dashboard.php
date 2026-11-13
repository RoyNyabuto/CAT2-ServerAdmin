<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="container">

    <h1>Welcome to Your Dashboard</h1>

    <h3>
        Hello, <?php echo htmlspecialchars($_SESSION['fullname']); ?>
    </h3>

    <p>Email:
        <?php echo htmlspecialchars($_SESSION['email']); ?>
    </p>

    <p>You have successfully logged into the system.</p>

    <a href="logout.php">Logout</a>

</div>

</body>
</html>

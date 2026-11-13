<?php

require_once 'includes/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $feedback = trim($_POST['message']);

    if (
        $fullname !== "" &&
        filter_var($email, FILTER_VALIDATE_EMAIL) &&
        $subject !== "" &&
        $feedback !== ""
    ) {

        $stmt = $conn->prepare(
            "INSERT INTO feedback (fullname, email, subject, message)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssss",
            $fullname,
            $email,
            $subject,
            $feedback
        );

        if ($stmt->execute()) {
            $message = "Feedback submitted successfully!";
        } else {
            $message = "Failed to submit feedback.";
        }

        $stmt->close();

    } else {
        $message = "Please enter valid information in all fields.";
    }
}

// Retrieve submitted feedback

$result = $conn->query(
    "SELECT * FROM feedback ORDER BY submitted_at DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Student Feedback</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="container">

    <h2>Student Feedback Form</h2>

    <p><?php echo htmlspecialchars($message); ?></p>

    <form method="POST">

        <input
            type="text"
            name="fullname"
            placeholder="Full Name"
            required
        >

        <input
            type="email"
            name="email"
            placeholder="Email Address"
            required
        >

        <input
            type="text"
            name="subject"
            placeholder="Feedback Subject"
            required
        >

        <textarea
            name="message"
            placeholder="Write your feedback here"
            required
            rows="5"
        ></textarea>

        <button type="submit">Submit Feedback</button>

    </form>

    <hr>

    <h2>Submitted Feedback</h2>

    <?php while ($row = $result->fetch_assoc()): ?>

        <div style="text-align:left; margin-bottom:20px;">

            <h3>
                <?php echo htmlspecialchars($row['subject']); ?>
            </h3>

            <p>
                <strong>Name:</strong>
                <?php echo htmlspecialchars($row['fullname']); ?>
            </p>

            <p>
                <strong>Email:</strong>
                <?php echo htmlspecialchars($row['email']); ?>
            </p>

            <p>
                <?php echo nl2br(htmlspecialchars($row['message'])); ?>
            </p>

            <small>
                Submitted:
                <?php echo htmlspecialchars($row['submitted_at']); ?>
            </small>

        </div>

        <hr>

    <?php endwhile; ?>

    <br>

    <a href="index.php">Back Home</a>

</div>

</body>
</html>

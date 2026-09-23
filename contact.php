<?php include 'header.php'; ?>
<?php include 'db.php'; ?>

<?php
$message_sent = false;
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {
        // ডাটাবেজে সেভ করার মূল SQL Query
        $sql = "INSERT INTO messages (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
        
        if (mysqli_query($conn, $sql)) {
            $message_sent = true;
        } else {
            $error_message = "Database Error: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Please fill in all required fields.";
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 p-4">
                <h2 class="fw-bold mb-4 text-center">Get In Touch</h2>

                <?php if ($message_sent): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Awesome, <?php echo $name; ?>!</strong> Your message has been saved to the Database successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $error_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="contact.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label font-weight-bold">Your Name *</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label font-weight-bold">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label font-weight-bold">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Project Inquiry / General Question">
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label font-weight-bold">Message *</label>
                        <textarea class="form-control" id="message" name="message" rows="5" placeholder="Write your message here..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2"><i class="fas fa-paper-plane me-2"></i> Send Message</button>
                </form>

            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<?php
session_start();

// Admin Session Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $technologies = mysqli_real_escape_string($conn, $_POST['technologies']);
    $live_demo = mysqli_real_escape_string($conn, $_POST['live_demo']);
    $github_link = mysqli_real_escape_string($conn, $_POST['github_link']);

    // Image Upload Logic
    $target_dir = "img/";
    $image_name = time() . "_" . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO projects (title, description, technologies, image, live_demo, github_link) 
                VALUES ('$title', '$description', '$technologies', '$image_name', '$live_demo', '$github_link')";

        if (mysqli_query($conn, $sql)) {
            $message = "<div class='alert alert-success'>Project added successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Database Error: " . mysqli_error($conn) . "</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Failed to upload image.</div>";
    }
}
?>

<?php include 'header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold mb-0">Add New Project</h3>
                    <a href="projects.php" class="btn btn-outline-secondary btn-sm">View Projects</a>
                </div>

                <?php echo $message; ?>

                <form action="add_project.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Project Title *</label>
                        <input type="text" name="title" class="form-control" required placeholder="e.g. E-Commerce Website">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description *</label>
                        <textarea name="description" class="form-control" rows="3" required placeholder="Brief detail about project..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Technologies Used * (Comma separated)</label>
                        <input type="text" name="technologies" class="form-control" required placeholder="PHP, MySQL, Bootstrap 5">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Project Image *</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Live Demo Link</label>
                            <input type="url" name="live_demo" class="form-control" placeholder="https://example.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">GitHub Repository Link</label>
                            <input type="url" name="github_link" class="form-control" placeholder="https://github.com/your-repo">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Save Project</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<?php 
session_start();
include 'header.php'; 
include 'db.php'; 

// Delete Project Logic (Admin Only)
if (isset($_GET['delete_id']) && isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    $delete_id = intval($_GET['delete_id']);
    
    // Remove image file from img folder
    $img_query = mysqli_query($conn, "SELECT image FROM projects WHERE id = $delete_id");
    $img_row = mysqli_fetch_assoc($img_query);
    if ($img_row && file_exists("img/" . $img_row['image'])) {
        unlink("img/" . $img_row['image']);
    }

    mysqli_query($conn, "DELETE FROM projects WHERE id = $delete_id");
    echo "<script>alert('Project deleted successfully!'); window.location.href='projects.php';</script>";
}

// Fetch Projects from Database
$result = mysqli_query($conn, "SELECT * FROM projects ORDER BY id DESC");
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="fas fa-briefcase text-primary me-2"></i>My Projects</h2>
        <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
            <a href="add_project.php" class="btn btn-success fw-bold"><i class="fas fa-plus me-1"></i> Add New Project</a>
        <?php endif; ?>
    </div>
    
    <div class="row g-4">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="img/<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['title']); ?>" style="height: 220px; object-fit: cover; background-color: #f8f9fa;">
                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="card-title fw-bold mb-2"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <p class="card-text text-muted flex-grow-1 small"><?php echo htmlspecialchars($row['description']); ?></p>
                            
                            <div class="mb-3">
                                <?php 
                                    $techs = explode(',', $row['technologies']);
                                    foreach($techs as $tech): 
                                ?>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 me-1 mb-1"><?php echo trim(htmlspecialchars($tech)); ?></span>
                                <?php endforeach; ?>
                            </div>

                            <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                                <div>
                                    <?php if (!empty($row['live_demo'])): ?>
                                        <a href="<?php echo htmlspecialchars($row['live_demo']); ?>" target="_blank" class="btn btn-outline-primary btn-sm me-1">Live Demo</a>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($row['github_link'])): ?>
                                        <a href="<?php echo htmlspecialchars($row['github_link']); ?>" target="_blank" class="btn btn-dark btn-sm"><i class="fab fa-github"></i> GitHub</a>
                                    <?php endif; ?>
                                </div>

                                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                                    <a href="projects.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this project?');" title="Delete Project">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <div class="p-5 bg-light rounded-3 border">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No projects added yet!</h4>
                    <p class="text-muted mb-3">Click the "Add New Project" button above to publish your first project.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
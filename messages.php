<?php
session_start();

// ইউজার লগইন না থাকলে সরাসরি login.php পেজে পাঠিয়ে দেবে
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
?>

<?php include 'header.php'; ?>
<?php include 'db.php'; ?>

<?php
// ডাটাবেজ থেকে মেসেজ মুছে ফেলার (Delete) লজিক
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM messages WHERE id = $delete_id";
    
    if (mysqli_query($conn, $delete_sql)) {
        echo "<script>alert('Message deleted successfully!'); window.location.href='messages.php';</script>";
    } else {
        echo "<script>alert('Error deleting message: " . mysqli_error($conn) . "');</script>";
    }
}

// ডাটাবেজ থেকে সব মেসেজ নিয়ে আসার Query (সর্বশেষ মেসেজ সবার উপরে থাকবে)
$sql = "SELECT * FROM messages ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="fas fa-envelope-open-text text-primary me-2"></i>Contact Messages</h2>
        <a href="logout.php" class="btn btn-outline-danger"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
    </div>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-bordered table-striped align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Time</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject']); ?></td>
                            <td><?php echo htmlspecialchars($row['message']); ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td class="text-center">
                                <!-- Delete Button -->
                                <a href="messages.php?delete_id=<?php echo $row['id']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Are you sure you want to delete this message?');">
                                   <i class="fas fa-trash-alt me-1"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No messages found in database.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
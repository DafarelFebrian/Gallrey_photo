<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['photo_ids'])) {
    $photo_ids = $_POST['photo_ids'];
    $user_id = $_SESSION['user_id'];

    foreach ($photo_ids as $photo_id) {
        // Cek apakah foto milik user yang sedang login
        $sql = "SELECT filename FROM photos WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $photo_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $photo = $result->fetch_assoc();
            $file_path = "uploads/" . $photo['filename'];

            // Hapus dari database
            $delete_sql = "DELETE FROM photos WHERE id = ? AND user_id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("ii", $photo_id, $user_id);
            $delete_stmt->execute();

            // Hapus file dari folder uploads
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }

    echo "Foto berhasil dihapus!";
    exit();
}
?>

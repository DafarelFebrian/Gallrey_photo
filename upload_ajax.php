<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Anda belum login']);
    exit();
}

include 'db.php';

if (isset($_FILES['photo'])) {
    $user_id = $_SESSION['user_id'];
    $photo = $_FILES['photo'];
    $target_dir = "uploads/";
    $filename = time() . "_" . basename($photo["name"]);
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($photo["tmp_name"], $target_file)) {
        // Simpan ke database
        $sql = "INSERT INTO photos (user_id, filename) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $filename);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Foto berhasil diupload', 'filename' => $filename]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan ke database']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengupload file']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tidak ada file yang diupload']);
}
?>

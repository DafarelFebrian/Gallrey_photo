<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Gagal! Anda harus login terlebih dahulu.";
    exit();
}

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["photo"])) {
    $user_id = $_SESSION['user_id'];
    $upload_dir = "uploads/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $filename = time() . "_" . basename($_FILES["photo"]["name"]);
    $target_file = $upload_dir . $filename;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($file_type, $allowed_types)) {
        echo "<span style='color: red;'>Format file tidak didukung!</span>";
        exit();
    }

    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO photos (user_id, filename) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $filename);
        $stmt->execute();
        echo "<span style='color: green;'>Foto berhasil diupload!</span>";
        exit();
    } else {
        echo "<span style='color: red;'>Gagal mengupload foto!</span>";
        exit();
    }
}
?>

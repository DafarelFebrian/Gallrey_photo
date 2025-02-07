<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Photo by Dapa</title>
    <link rel="stylesheet" href="index.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <h1 class="site-title">Gallery Photo by Dapa</h1>
        </div>
        <div class="header-right">
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="main-content">
        <h2>Galeri Foto Anda</h2>
        <p>Anda dapat mengupload ataupun menghapus foto anda.</p>

        <div class="actions">
            <button class="btn" onclick="openUploadModal()">Upload Foto</button>
        </div>

        <form action="delete.php" method="POST">
            <div class="gallery" id="gallery">
                <?php
                $sql = "SELECT * FROM photos WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0):
                    while ($photo = $result->fetch_assoc()):
                ?>
                    <div class="photo">
                        <input type="checkbox" name="photo_ids[]" value="<?= $photo['id'] ?>">
                        <img src="uploads/<?= htmlspecialchars($photo['filename']) ?>" alt="Foto">
                    </div>
                <?php
                    endwhile;
                else:
                ?>
                    <p id="no-photos-msg">Tidak ada foto di galeri Anda.</p>
                <?php endif; ?>
            </div>
            <button type="submit" id="delete-btn" class="btn btn-danger" disabled>Hapus Foto</button>
        </form>
    </main>

    <!-- Modal Upload Foto -->
    <div id="uploadModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeUploadModal()">&times;</span>
            <h2>Upload Foto</h2>
            <form id="uploadForm" enctype="multipart/form-data">
                <input type="file" name="photo" id="photo" required>
                <button type="submit" class="btn">Upload</button>
            </form>
            <p id="uploadMessage"></p>
        </div>
    </div>

    <script>
        function openUploadModal() {
            document.getElementById("uploadModal").style.display = "block";
        }

        function closeUploadModal() {
            document.getElementById("uploadModal").style.display = "none";
        }


    $(document).ready(function () {
        // Upload Foto dengan AJAX
        $("#uploadForm").submit(function (e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: "upload.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $("#uploadMessage").html(response);
                    setTimeout(function () {
                        closeUploadModal();
                        location.reload(); // Reload halaman untuk menampilkan foto yang baru
                    }, 1500);
                }
            });
        });

        // Hapus Foto dengan AJAX
        $("#delete-btn").click(function (e) {
            e.preventDefault();
            var selectedPhotos = [];
            $("input[name='photo_ids[]']:checked").each(function () {
                selectedPhotos.push($(this).val());
            });

            if (selectedPhotos.length === 0) {
                alert("Pilih foto yang ingin dihapus!");
                return;
            }

            $.ajax({
                url: "delete.php",
                type: "POST",
                data: { photo_ids: selectedPhotos },
                success: function (response) {
                    alert(response);
                    location.reload(); // Refresh halaman untuk memperbarui galeri
                }
            });
        });

        // Aktifkan tombol hapus jika ada checkbox yang dipilih
        $("input[name='photo_ids[]']").change(function () {
            if ($("input[name='photo_ids[]']:checked").length > 0) {
                $("#delete-btn").prop("disabled", false);
            } else {
                $("#delete-btn").prop("disabled", true);
            }
        });
    });

    </script>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 Gallery Photo by Dapa. dibuat dengan cinta.</p>
    </footer>

</body>
</html>

// Dapatkan elemen modal
const modal = document.getElementById("uploadModal");
const openModalBtn = document.getElementById("openModal");
const closeModalBtn = document.getElementById("closeModal");

// Event untuk membuka modal
openModalBtn.addEventListener("click", () => {
  modal.style.display = "flex";
});

// Event untuk menutup modal
closeModalBtn.addEventListener("click", () => {
  modal.style.display = "none";
});

// Tutup modal jika pengguna mengklik di luar modal
window.addEventListener("click", (event) => {
  if (event.target === modal) {
    modal.style.display = "none";
  }
});

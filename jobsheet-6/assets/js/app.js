// assets/js/app.js

// ===== 1. Hamburger menu (JS-driven) =====
function initNavToggle() {
  const toggleBtn = document.getElementById("nav-toggle-btn");
  const nav = document.querySelector("header nav");

  if (!toggleBtn || !nav) return;

  toggleBtn.addEventListener("click", function () {
    nav.classList.toggle("nav-open");
  });
}

// ===== 2. Konfirmasi hapus =====
// Memakai event delegation di document karena baris tabel sekarang
// dirender dinamis via fetch (lihat buku.js/anggota.js) sehingga
// tombol .btn-hapus belum tentu ada saat DOMContentLoaded.
function initHapusConfirm() {
    document.addEventListener("click", function (e) {
      console.log("Elemen yang baru saja diklik:", e.target); // Tambahkan baris ini
        const btn = e.target.closest(".btn-hapus");
        if (!btn) return;

        const row = btn.closest("tr");
        const nama = row ? row.querySelector("td")?.textContent : "data ini";
        const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");
        if (yakin && row) {
            row.remove();
        }
    });
}

// ===== 3. Filter/pencarian tabel real-time =====
function initTableFilter() {
  const input = document.getElementById("search-input");
  const table = document.querySelector(".table-responsive table");

  if (!input || !table) return;

  input.addEventListener("keyup", function () {
    const keyword = input.value.toLowerCase();
    const rows = table.querySelectorAll("tbody tr");

    let barisAktif = 0;
    rows.forEach(function (row) {
      const selJudul = row.querySelector("td");
      const teks = selJudul ? selJudul.textContent.toLowerCase() : "";
      const cocok = teks.includes(keyword);
      row.style.display = cocok ? "" : "none";
      if (cocok) barisAktif++; // Tambah angka jika baris muncul
    });

    // Update teks counter
    const counterEl = document.getElementById("counter");
    if (counterEl) {
      counterEl.textContent = "Menampilkan " + barisAktif + " data";
    }

    // rows.forEach(function (row) {
    //   const selJudul = row.querySelector("td"); // Hanya ambil <td> pertama (Judul)
    //   const teks = selJudul ? selJudul.textContent.toLowerCase() : "";
    //   row.style.display = teks.includes(keyword) ? "" : "none";
    // });

    // rows.forEach(function (row) {
    //   const teks = row.textContent.toLowerCase();
    //   row.style.display = teks.includes(keyword) ? "" : "none";
    // });
  });
}

// ===== 4. Validasi form (client-side) =====
function tampilkanError(input, pesan) {
  hapusError(input);
  const span = document.createElement("span");
  span.className = "error";
  span.textContent = pesan;
  input.insertAdjacentElement("afterend", span);
}

function hapusError(input) {
  const next = input.nextElementSibling;
  if (next && next.classList.contains("error")) {
    next.remove();
  }
}

function initValidasiForm() {
  const form = document.getElementById("form-tambah");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    let valid = true;

    // const judul = form.querySelector("[name='judul'], [name='nama']");
    // if (judul && judul.value.trim() === "") {
    //   tampilkanError(judul, "Field ini wajib diisi.");
    //   valid = false;
    // } else if (judul) {
    //   hapusError(judul);
    // }

    const fieldWajib = ["judul", "nama", "pengarang"];
    fieldWajib.forEach(function (namaField) {
      const field = form.querySelector(`[name='${namaField}']`);
      if (field && field.value.trim() === "") {
        tampilkanError(field, "Field ini wajib diisi.");
        valid = false;
      } else if (field) {
        hapusError(field);
      }
    });

    const tahun = form.querySelector("[name='tahun']");
    if (tahun) {
      const nilai = parseInt(tahun.value, 10);
      if (isNaN(nilai) || nilai < 1900 || nilai > 2026) {
        tampilkanError(tahun, "Tahun harus di antara 1900-2026.");
        valid = false;
      } else {
        hapusError(tahun);
      }
    }

    const stok = form.querySelector("[name='stok']");
    if (stok) {
      const nilai = parseInt(stok.value, 10);
      if (isNaN(nilai) || nilai < 0) {
        tampilkanError(stok, "Stok tidak boleh negatif.");
        valid = false;
      } else {
        hapusError(stok);
      }
    }

    const isbn = form.querySelector("[name='isbn']");
    if (isbn && isbn.value.trim() !== "") {
      if (!/^[0-9-]+$/.test(isbn.value.trim())) {
        tampilkanError(isbn, "ISBN hanya boleh berisi angka dan tanda hubung.");
        valid = false;
      } else {
        hapusError(isbn);
      }
    }

    if (!valid) {
      e.preventDefault();
    }
  });
}

// ===== TITIK MASUK (ENTRY POINT) =====
document.addEventListener("DOMContentLoaded", function () {
  initNavToggle();
  initHapusConfirm();
  initTableFilter();
  initValidasiForm();
});

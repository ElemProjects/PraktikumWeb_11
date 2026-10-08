// ===== Hamburger menu (ss-driven, menggantikan checkbox hack) =====
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

// ===== Konfirmasi hapus (front-end only, belum ke server) =====
// Memakai event delegation di document karena baris tabel sekarang
// dirender dinamis via fetch (lihat buku.js/anggota.js) sehingga
// tombol .btn-hapus belum tentu ada saat DOMContentLoaded.
function initHapusConfirm() {

    document.addEventListener("click", function (e) {

        console.log(e.target);

        const btn = e.target.closest(".btn-hapus");
        if (!btn) return;

        const row = btn.closest("tr");
        const nama = row
            ? row.querySelector("td")?.textContent
            : "data ini";

        const yakin = confirm(
            "Yakin ingin menghapus \"" + nama + "\"?"
        );

        if (yakin && row) {
            row.remove();
        }
    });
}

function updateCounter() {
    const table = document.querySelector(".table-responsive table");
    const counter = document.getElementById("counter-buku");
    if (!table || !counter) return;

    const allRows = table.querySelectorAll("tbody tr");
    const visible = Array.from(allRows).filter(function(row) {
        return row.style.display !== "none";
    });
    counter.textContent = "Menampilkan " + visible.length + " dari " + allRows.length + " buku";
}

// ===== Filter/Pencarian tabel real-time =====
function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector(".table-responsive table");
    if (!input || !table) return;

    input.addEventListener("keyup", function () {
        const keyword = input.value.toLowerCase();
        const rows = table.querySelectorAll("tbody tr");
        rows.forEach(function (row) {
            
            const judul = row.querySelector("td")?.textContent.toLowerCase() || "";
            row.style.display = judul.includes(keyword) ? "" : "none";
        });
        updateCounter(); 
    });

    updateCounter(); 
}

// ===== Validasi form (client-side) =====
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


    const fields = [
        {
            selector: "[name='judul'], [name='nama']",
            pesan: "Field ini wajib diisi.",
            validate: function(val) { return val.trim() !== ""; }
        },
        {
            selector: "[name='pengarang']",
            pesan: "Pengarang wajib diisi.",
            validate: function(val) { return val.trim() !== ""; }
        },
        {
            selector: "[name='tahun']",
            pesan: "Tahun harus di antara 1900-2026.",
            validate: function(val) {
                const n = parseInt(val, 10);
                return !isNaN(n) && n >= 1900 && n <= 2026;
            }
        },
        {
            selector: "[name='stok']",
            pesan: "Stok tidak boleh negatif.",
            validate: function(val) {
                const n = parseInt(val, 10);
                return !isNaN(n) && n >= 0;
            }
        },
        {
            selector: "[name='isbn']",
            pesan: "ISBN hanya boleh berisi angka dan tanda hubung (-).",
            validate: function(val) {
                
                return val.trim() === "" || /^[0-9\-]+$/.test(val.trim());
            }
        }
    ];

    form.addEventListener("submit", function (e) {
        let valid = true;

        
        fields.forEach(function(field) {
            const input = form.querySelector(field.selector);
            if (!input) return;

            if (!field.validate(input.value)) {
                tampilkanError(input, field.pesan);
                valid = false;
            } else {
                hapusError(input);
            }
        });

        if (!valid) {
            e.preventDefault();
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();
});

async function muatDataJSON(namaFile, daftarKunci) {
    const tbody = document.querySelector(".table-responsive table tbody");
    const loading = document.getElementById("loading-indicator");

    if (!tbody) return;

    loading.style.display = "block";
    tbody.innerHTML = "";

    try {
        await new Promise((resolve) => setTimeout(resolve, 3000));

        const res = await fetch(namaFile);

        if (!res.ok) {
            throw new Error(
                "Gagal mengambil data (status " + res.status + ")"
            );
        }

        const data = await res.json();

        data.forEach(function (item) {
            const tr = document.createElement("tr");

            daftarKunci.forEach(function (kunci) {
                const td = document.createElement("td");
                td.textContent = item[kunci];
                tr.appendChild(td);
            });

            const tdAksi = document.createElement("td");

            tdAksi.innerHTML =
                '<button type="button">Edit</button> ' +
                '<button type="button" class="btn-hapus">Hapus</button>';

            tr.appendChild(tdAksi);
            tbody.appendChild(tr);
        });

    } catch (err) {

        tbody.innerHTML =
            '<tr><td colspan="' + (daftarKunci.length + 1) + '">' +
            "Gagal memuat data: " + err.message +
            "</td></tr>";

    } finally {
        loading.style.display = "none";
    }
}
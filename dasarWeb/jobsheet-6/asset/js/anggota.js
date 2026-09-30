// Mengambil & menampilkan Daftar Anggota secara asinkron dari data/anggota.json
function muatDaftarAnggota() {
    muatDataJSON("../data/anggota.json", [
        "no_anggota",
        "nama",
        "alamat",
        "no_hp"
    ]);
}

document.addEventListener("DOMContentLoaded", muatDaftarAnggota);z
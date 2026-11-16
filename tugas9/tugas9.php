<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: form_data_diri.html');
    exit;
}

$nama          = trim($_POST['nama'] ?? '');
$tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
$hobi          = $_POST['hobi'] ?? [];

if ($nama === '' || $tanggal_lahir === '') {
    echo 'Nama dan tanggal lahir wajib diisi. <a href="form_data_diri.html">&laquo; Kembali ke Form</a>';
    exit;
}

$nama_bulan = [
    1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];

$tanggal_tampil = $tanggal_lahir; 
$bagian = explode('-', $tanggal_lahir);
if (count($bagian) === 3 && checkdate((int)$bagian[1], (int)$bagian[2], (int)$bagian[0])) {
    $tahun = $bagian[0];
    $bulan = (int)$bagian[1];
    $hari  = (int)$bagian[2];
    $tanggal_tampil = $hari . ' ' . $nama_bulan[$bulan] . ' ' . $tahun;
}

if (is_array($hobi) && count($hobi) > 0) {
    $hobi_tampil = implode(', ', $hobi);
} else {
    $hobi_tampil = 'Tidak ada hobi yang dipilih';
}

$simpan_ke_db = true;

if ($simpan_ke_db) {
    $koneksi = new mysqli('localhost', 'root', '', 'tugas9');
    if ($koneksi->connect_error) {
        die('Koneksi database gagal: ' . $koneksi->connect_error);
    }
    $koneksi->set_charset('utf8mb4');

    $stmt = $koneksi->prepare(
        'INSERT INTO data_diri (nama_lengkap, tanggal_lahir, hobi) VALUES (?, ?, ?)'
    );
    $stmt->bind_param('sss', $nama, $tanggal_lahir, $hobi_tampil);
    $stmt->execute();
    $stmt->close();
    $koneksi->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tampilan Data Anda</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 40px 16px;
        }
        .card {
            max-width: 560px;
            margin: 0 auto;
            background: #fff;
            padding: 24px 28px;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
        }
        h1 {
            font-size: 26px;
            margin: 0 0 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #eee;
        }
        table td {
            padding: 12px 8px 12px 0;
            vertical-align: top;
            font-size: 16px;
        }
        table td:first-child {
            font-weight: bold;
            width: 150px;
        }
        a {
            color: #007bff;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Tampilan Data Anda</h1>
        <table>
            <tr>
                <td>Nama Lengkap:</td>
                <td><?= htmlspecialchars($nama) ?></td>
            </tr>
            <tr>
                <td>Tanggal Lahir:</td>
                <td><?= htmlspecialchars($tanggal_tampil) ?></td>
            </tr>
            <tr>
                <td>Hobi:</td>
                <td><?= htmlspecialchars($hobi_tampil) ?></td>
            </tr>
        </table>
        <a href="tugas9.html">&laquo; Kembali ke Form</a>
    </div>
</body>
</html>
<?php
$baseDirectory = 'D:/'; // Ubah sesuai dengan direktori yang Anda inginkan

if (isset($_GET['file'])) {
    $file = $_GET['file'];
    $filepath = $baseDirectory . $file;

    // Debugging: Tampilkan jalur file yang dihasilkan
    echo '<p>File Path: ' . htmlspecialchars($filepath) . '</p>';

    // Cek apakah file ada dan jalur file dimulai dengan direktori dasar
    if (file_exists($filepath) && strpos(realpath($filepath), realpath($baseDirectory)) === 0) {
        // Set header untuk download file
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));

        // Membersihkan output buffer dan flush
        ob_clean();
        flush();

        // Membaca file dalam potongan kecil
        $fileHandle = fopen($filepath, 'rb');
        if ($fileHandle !== false) {
            while (!feof($fileHandle)) {
                echo fread($fileHandle, 8192); // Membaca file dalam potongan kecil
                flush(); // Mengirimkan output buffer
            }
            fclose($fileHandle);
        } else {
            echo 'Gagal membuka file.';
        }
        exit;
    } else {
        echo 'File tidak ditemukan atau akses ditolak.';
    }
} else {
    echo 'File tidak ditentukan.';
}
?>

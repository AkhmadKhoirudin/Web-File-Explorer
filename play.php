<?php
$baseDirectory = 'D:/'; // Ubah sesuai dengan direktori yang Anda inginkan

if (isset($_GET['file'])) {
    $file = $_GET['file'];
    $filepath = $baseDirectory . $file;

    if (file_exists($filepath) && strpos(realpath($filepath), realpath($baseDirectory)) === 0) {
        $size = filesize($filepath);
        $mimeType = mime_content_type($filepath);
        $start = 0;
        $end = $size - 1;

        header('Content-Type: ' . $mimeType);
        header('Accept-Ranges: bytes');

        if (isset($_SERVER['HTTP_RANGE'])) {
            $range = $_SERVER['HTTP_RANGE'];
            $range = str_replace('bytes=', '', $range);
            $range = explode('-', $range);

            $start = $range[0];
            $end = isset($range[1]) && is_numeric($range[1]) ? $range[1] : $end;

            header('HTTP/1.1 206 Partial Content');
            header('Content-Range: bytes ' . $start . '-' . $end . '/' . $size);
        }

        header('Content-Length: ' . ($end - $start + 1));

        $fileHandle = fopen($filepath, 'rb');
        fseek($fileHandle, $start);

        while (!feof($fileHandle) && ($position = ftell($fileHandle)) <= $end) {
            $buffer = min(8192, $end - $position + 1);
            echo fread($fileHandle, $buffer);
            flush(); // Kirim buffer ke browser
        }

        fclose($fileHandle);
        exit;
    } else {
        echo 'File tidak ditemukan atau akses ditolak.';
    }
} else {
    echo 'File tidak ditentukan.';
}
?>

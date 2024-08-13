<?php
session_start(); // Mulai sesi
session_unset(); // Menghapus semua variabel sesi
session_destroy(); // Menghancurkan sesi

$directory = 'D:/';
$items = scandir($directory);

echo '<h2>Daftar Folder dan File di D:/</h2>';
echo '<ul>';

foreach ($items as $item) {
    // Abaikan '.' dan '..'
    if ($item !== '.' && $item !== '..') {
        $itemPath = $directory . $item;
        
        // Cek apakah item adalah folder atau file
        if (is_dir($itemPath)) {
            echo '<li><a href="buka.php?folder=' . urlencode($item) . '"><strong>[Folder]</strong> ' . htmlspecialchars($item) . '</a></li>';
        } else {
            echo '<li><a href="download.php?file=' . urlencode($item) . '">[File] ' . htmlspecialchars($item) . '</a></li>';
        }
    }
}

echo '</ul>';
?>

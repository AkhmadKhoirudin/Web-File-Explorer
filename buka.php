<?php
session_start();
$baseDirectory = 'D:/'; 
$currentFolder = isset($_GET['folder']) ? $_GET['folder'] : '';
$directory = $baseDirectory . ($currentFolder ? $currentFolder . '/' : '');

// Simpan folder saat ini sebagai folder sebelumnya
if (isset($_SESSION['current_folder']) && $currentFolder !== '') {
    $_SESSION['previous_folder'] = $_SESSION['current_folder'];
}

// Simpan folder saat ini ke sesi
$_SESSION['current_folder'] = $currentFolder;

// Ambil folder sebelumnya dari sesi
$previousFolder = isset($_SESSION['previous_folder']) ? $_SESSION['previous_folder'] : '';

// Fungsi untuk mendapatkan ikon berdasarkan ekstensi file
function getIcon($itemPath, $item) {
    $icon = 'icons/mystery.png'; // Default icon

    if (is_dir($itemPath)) {
        $icon = 'icons/folder.png';
    } else {
        $ext = pathinfo($item, PATHINFO_EXTENSION);
        switch ($ext) {
            case 'jpg':
            case 'png':
            case 'ico':
                $icon = 'icons/images.png';
                break;
            case 'sql':
            case 'db':
                $icon = 'icons/sql.png';
                break;
            case 'doc':
            case 'docx':
                $icon = 'icons/word.png';
                break;
            case 'ppt':
            case 'pptx':
                $icon = 'icons/ppt.png';
                break;
            case 'xls':
            case 'xlsx':
                $icon = 'icons/excel.png';
                break;
            case 'txt':
                $icon = 'icons/txt.png';
                break;
            case 'html':
                $icon = 'icons/html.png';
                break;
            case 'php':
                $icon = 'icons/php.png';
                break;
            case 'js':
                $icon = 'icons/js.png';
                break;
            case 'mp4':
                $icon = 'icons/mp4.png';
                break;
            case 'mp3':
                $icon = 'icons/mp3.png';
                break;
            case 'rar':
            case 'zip':
            case '7z':
                $icon = 'icons/zip.png';
                break;
            case 'exe':
            case 'msi':
                 $icon = 'icons/exe.png';
                 break;
            case 'pdf':
                $icon = 'icons/pdf.png';
                break;
            case 'dll':
                $icon = 'icons/dll.png';
                break;
        }
    }

    return $icon;
}

// Fungsi untuk menampilkan breadcrumb
function renderBreadcrumb($folder) {
    global $baseDirectory;
    $parts = explode('/', $folder);
    $path = $baseDirectory;

    echo '<a href="buka.php">D:/</a>';

    foreach ($parts as $key => $part) {
        if ($part) {
            $path .= $part . '/';
            echo ' > <a href="buka.php?folder=' . urlencode(rtrim(substr($path, strlen($baseDirectory)), '/')) . '">' . htmlspecialchars($part) . '</a>';
        }
    }
}

// Cek apakah folder ada
if (is_dir($directory)) {
    $items = scandir($directory);

    // Pisahkan folder dan file
    $folders = [];
    $files = [];

    foreach ($items as $item) {
        if ($item === '.' || $item === '..' || $item[0] === '.' || $item[0] === '$' || strpos($item, 'found.') === 0 || in_array($item, ['temp', 'backup', 'System Volume Information', 'Config.Msi', 'FileHistory'])) {
            continue;
        }
    
        $ext = pathinfo($item, PATHINFO_EXTENSION);
        if (in_array($ext, ['log', 'dat', 'tmp', 'opdownload'])) {
            continue;
        }
    
        $itemPath = $directory . $item;
        if (is_dir($itemPath)) {
            $folders[] = $item;
        } else {
            $files[] = $item;
        }
    }
    
    // Urutkan folder dan file
    sort($folders);
    usort($files, function($a, $b) {
        return strcmp(pathinfo($a, PATHINFO_EXTENSION), pathinfo($b, PATHINFO_EXTENSION));
    });

    echo '<h2>Isi Folder: ' . htmlspecialchars($currentFolder) . '</h2>';

    // Tautan breadcrumb untuk navigasi
    renderBreadcrumb($currentFolder);
    echo '<br>';

    echo '<div class="container">';
    echo '<div class="row" style="display: flex; flex-wrap: wrap; gap: 5px;">';

    // Menampilkan folder terlebih dahulu
    foreach ($folders as $item) {
        $itemPath = $directory . $item;
        $relativePath = $currentFolder ? $currentFolder . '/' . $item : $item;
        $icon = getIcon($itemPath, $item);
        
        echo '<div class="col-md-auto">';
        echo '<div class="card" style="width: 100px; text-align: center; overflow: hidden;">';
        echo '<img src="' . $icon . '" class="card-img-top" alt="' . htmlspecialchars($item) . '">';
        echo '<div class="card-body">';
        echo '<p class="card-text">';
        echo '<a href="buka.php?folder=' . urlencode($relativePath) . '" class="text-truncate" title="' . htmlspecialchars($item) . '">' . htmlspecialchars($item) . '</a>';
        echo '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    // Menampilkan file setelah folder
    foreach ($files as $item) {
        $itemPath = $directory . $item;
        $relativePath = $currentFolder ? $currentFolder . '/' . $item : $item;
        $icon = getIcon($itemPath, $item);
        
        echo '<div class="col-md-auto">';
        echo '<div class="card" style="width: 100px; text-align: center; overflow: hidden;">';
        echo '<img src="' . $icon . '" class="card-img-top" alt="' . htmlspecialchars($item) . '">';
        echo '<div class="card-body">';
        echo '<p class="card-text">';
        echo '<a href="play.php?file=' . urlencode($relativePath) . '" class="text-truncate" title="' . htmlspecialchars($item) . '">' . htmlspecialchars($item) . '</a>';
        echo '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
} else {
    echo 'Folder tidak ditemukan.';
}
?>

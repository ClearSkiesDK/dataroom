<?php
// Set headers to allow cross-origin requests (if needed)
header('Content-Type: application/json');

// Get the current directory contents
$dir = './';
$files = [];

if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            // Skip hidden files, parent directory, and our script files
            if ($file != '.' && 
                $file != '..' && 
                $file != 'dataroom.html' && 
                $file != 'get_files.php' && 
                $file != 'styles.css') {
                
                $path = $dir . $file;
                // Create a file entry with proper data types
                $files[] = [
                    'name' => $file,
                    'isDirectory' => is_dir($path) ? true : false,
                    'size' => is_file($path) ? filesize($path) : 0,
                    'lastModified' => filemtime($path) // Unix timestamp
                ];
            }
        }
        closedir($dh);
    }
}

// Sort files - directories first, then alphabetically
usort($files, function($a, $b) {
    if ($a['isDirectory'] && !$b['isDirectory']) return -1;
    if (!$a['isDirectory'] && $b['isDirectory']) return 1;
    return strcasecmp($a['name'], $b['name']);
});

// Output the JSON
echo json_encode($files);
?>
<?php
// player.php

// 1. Configure your uploads directory
$uploadDir = __DIR__ . '/uploads/';

// 2. Grab all .mp3 files
$files = glob($uploadDir . '*.mp3');

if (!$files) {
    http_response_code(404);
    die('No MP3 files available.');
}

// 3. Find the most recently modified file
$latest = array_reduce($files, function($a, $b) {
    return filemtime($b) > filemtime($a) ? $b : $a;
}, $files[0]);

// 4. Send appropriate headers
header('Content-Type: audio/mpeg');
header('Content-Length: ' . filesize($latest));
// Optional: allow browser seeking
header('Accept-Ranges: bytes');

// 5. Stream the file
readfile($latest);
exit;

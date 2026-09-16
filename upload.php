<?php
// upload.php
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['mp3file']) && $_FILES['mp3file']['error'] === UPLOAD_ERR_OK) {
        $tmpPath = $_FILES['mp3file']['tmp_name'];
        $origName = basename($_FILES['mp3file']['name']);
        $type = $_FILES['mp3file']['type'];
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

        // only allow MP3
        if (in_array($type, ['audio/mpeg','audio/mp3']) && $ext === 'mp3') {
            // get all existing .mp3 files
            $files = glob($uploadDir . '*.mp3');

            // extract numeric indices
            $max = 0;
            foreach ($files as $f) {
                if (preg_match('/(\d+)\.mp3$/', $f, $m)) {
                    $n = (int)$m[1];
                    if ($n > $max) $max = $n;
                }
            }

            // next index
            $next = $max + 1;
            $newName = $next . '.mp3';
            $dest = $uploadDir . $newName;

            if (move_uploaded_file($tmpPath, $dest)) {
                echo "Uploaded as {$newName}";
            } else {
                echo "Error moving file.";
            }
        } else {
            echo "Invalid file type.";
        }
    } else {
        echo "Upload error code: " . ($_FILES['mp3file']['error'] ?? 'N/A');
    }
} else {
    echo "Invalid request.";
}
?>

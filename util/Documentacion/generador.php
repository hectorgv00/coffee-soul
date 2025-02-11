<?php
function processFolder($source, $target) {
    // Ensure target folder exists
    if (!file_exists($target)) {
        mkdir($target, 0777, true);
    }

    // Scan the source directory
    $items = scandir($source);

    foreach ($items as $item) {
        // Skip current and parent directory references
        if ($item === '.' || $item === '..') {
            continue;
        }

        $sourcePath = $source . DIRECTORY_SEPARATOR . $item;
        $targetPath = $target . DIRECTORY_SEPARATOR . $item;

        if (is_dir($sourcePath)) {
            // If it's a folder, create a folder in the target
            mkdir($targetPath, 0777, true);

            // Create an empty .txt file inside the folder
            $txtFilePath = $targetPath . DIRECTORY_SEPARATOR . $item . '.txt';
            file_put_contents($txtFilePath, '');

            // Recurse into the folder
            processFolder($sourcePath, $targetPath);
        } else if (is_file($sourcePath)) {
            // If it's a file, create a folder named after the file (with extension)
            $fileNameWithExtension = pathinfo($item, PATHINFO_BASENAME);
            $fileFolderPath = $target . DIRECTORY_SEPARATOR . $fileNameWithExtension;

            mkdir($fileFolderPath, 0777, true);

            // Create a .txt file inside this folder with the same name as the original file
            $txtFilePath = $fileFolderPath . DIRECTORY_SEPARATOR . $fileNameWithExtension . '.txt';
            
            // Extract docstring content if present
            $docstringContent = extractDocstring($sourcePath);
            
            file_put_contents($txtFilePath, $docstringContent);
        }
    }
}

function extractDocstring($filePath) {
    $content = file_get_contents($filePath);
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);

    switch ($extension) {
        case 'php':
            // Match comments following the opening <?php tag
            if (preg_match('/<\\?php\\s*\\/\\*\\*(.*?)\\*\\//s', $content, $matches) ||
                preg_match('/<\\?php\\s*\\/\\*(.*?)\\*\\//s', $content, $matches)) {
                return trim($matches[1]);
            }
            break;

        case 'js':
        case 'css':
        case 'java':
        case 'c':
        case 'cpp':
            // Match comments enclosed in /* ... */
            if (preg_match('/\\/\\*(.*?)\\*\\//s', $content, $matches)) {
                return trim($matches[1]);
            }
            break;

        case 'py':
            // Match Python docstrings (""" ... """ or single-line # comments)
            if (preg_match('/^\s*"""(.*?)"""/s', $content, $matches)) {
                return trim($matches[1]);
            } elseif (preg_match('/^\s*#\s*(.+)$/m', $content, $matches)) {
                return trim($matches[1]);
            }
            break;

        case 'html':
        case 'htm':
            // Match HTML comments <!-- ... -->
            if (preg_match('/<!--(.*?)-->/s', $content, $matches)) {
                return trim($matches[1]);
            }
            break;

        default:
            return '';
    }

    return '';
}

// Example usage
$sourceFolder = '../../admin'; // Replace with the path to your source folder
$targetFolder = 'docs/admin'; // Replace with the path to your target folder

processFolder($sourceFolder, $targetFolder);

echo "Processing complete!\n";


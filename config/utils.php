<?php
function normalize_files_array($files = []) {
    $normalized_array = [];

    foreach($files as $index => $file) {
    
        if (!is_array($file['name'])) {
            $normalized_array[$index][] = $file;
            continue;
        }
    
        foreach($file['name'] as $idx => $name) {
            $normalized_array[$index][$idx] = [
                'name' => $name,
                'type' => $file['type'][$idx],
                'tmp_name' => $file['tmp_name'][$idx],
                'error' => $file['error'][$idx],
                'size' => $file['size'][$idx]
            ];
        }
    
    }
    
    return $normalized_array;
}
    
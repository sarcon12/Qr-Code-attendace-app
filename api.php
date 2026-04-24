<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$db_file = 'database.json';

// Initialize DB if not exists
if (!file_exists($db_file)) {
    file_put_contents($db_file, '{}');
    chmod($db_file, 0666);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Simple fetch of the entire DB
    $content = file_get_contents($db_file);
    echo $content ? $content : '{}';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $req = json_decode($input, true);
    
    if (!$req || !isset($req['action']) || !isset($req['path'])) {
        echo json_encode(["error" => "Invalid request parameters."]);
        exit;
    }

    $action = $req['action'];
    $path = $req['path'];
    $data = isset($req['data']) ? $req['data'] : null;

    $keys = array_filter(explode('/', $path), 'strlen');
    $keys = array_values($keys); // re-index

    // Use file locking to handle concurrent requests (multiple students registering at once)
    $fp = fopen($db_file, 'c+');
    if ($fp && flock($fp, LOCK_EX)) {
        clearstatcache();
        $filesize = filesize($db_file);
        $db = [];
        if ($filesize > 0) {
            $db = json_decode(fread($fp, $filesize), true);
            if (!is_array($db)) $db = [];
        }

        $temp = &$db;
        $targetKey = end($keys);
        
        // Traverse to parent node
        for ($i = 0; $i < count($keys) - 1; $i++) {
            $k = $keys[$i];
            if (!isset($temp[$k]) || !is_array($temp[$k])) {
                $temp[$k] = [];
            }
            $temp = &$temp[$k];
        }

        $resData = null;

        if ($action === 'set') {
            if ($targetKey !== false) {
                $temp[$targetKey] = $data;
            } else {
                $db = $data; // Root level set
            }
        } elseif ($action === 'update') {
            if ($targetKey !== false) {
                if (!isset($temp[$targetKey]) || !is_array($temp[$targetKey])) {
                    $temp[$targetKey] = [];
                }
                foreach ($data as $dk => $dv) {
                    $temp[$targetKey][$dk] = $dv;
                }
            } else {
                foreach ($data as $dk => $dv) {
                    $db[$dk] = $dv;
                }
            }
        } elseif ($action === 'push') {
            $newId = "-php-" . str_replace('.', '', microtime(true)) . rand(1000, 9999);
            if ($targetKey !== false) {
                if (!isset($temp[$targetKey]) || !is_array($temp[$targetKey])) {
                    $temp[$targetKey] = [];
                }
                $temp[$targetKey][$newId] = $data;
            } else {
                $db[$newId] = $data;
            }
            $resData = ["key" => $newId];
        } elseif ($action === 'remove') {
            if ($targetKey !== false) {
                unset($temp[$targetKey]);
            } else {
                $db = []; // Clear root
            }
        }

        // Write changes
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($db));
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
        
        echo json_encode(["status" => "success", "data" => $resData]);
    } else {
        echo json_encode(["error" => "Could not acquire database lock."]);
    }
}
?>

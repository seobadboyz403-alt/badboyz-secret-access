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

@date_default_timezone_set('Asia/Jakarta');
error_reporting(0);
ini_set('display_errors', 0);
session_start();

// ================== CONFIG ==================
$PASSWORD_HASH = '$2a$12$wg7WQzW4PBbhZIRO2kiCI.QJoOa.Sinc3AZ3Gml.wMZuG81LKF36y';
$SESSION_KEY   = 'scanner_auth';
$LOG_FILE      = __DIR__ . '/scanner_audit.log';
$BACKUP_DIR    = __DIR__ . '/scanner_backups';
// ============================================

// ======= AUTH =======
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

if (!isset($_SESSION[$SESSION_KEY]) || $_SESSION[$SESSION_KEY] !== true) {
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        if (password_verify($_POST['password'], $PASSWORD_HASH)) {
            $_SESSION[$SESSION_KEY] = true;
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $error = 'Password salah.';
        }
    }
    ?>
    <!doctype html>
    <html>
    <head>
    <meta charset="utf-8">
    <title>Backdoor Scanner — Login</title>
    <link rel="icon" type="image/png" href="https://s15.gifyu.com/images/bQ0b1.png">
    <style>
     * { box-sizing: border-box; }
     body {
       background: url('https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhS4-Ur1M0AF5_oQTYXjcC5MepAlGt3HKLc0QLHspmYQ7iTfln0SUtkSQak6AZ1-h_0yq6eOmbyfRRZJ1UMhUt8s_HQxjHqcULFe1ApCVRIOFWN8re-UBDV6yvGTR6uREN_MGGqBQnx-nKH5Sd1J-zbA_HOHhA1NW_o39Owk8Kbs213XOnxKdewVrk3R9Q/s736/kok.gif') no-repeat center center fixed;
       background-size: cover;
       font-family: Tahoma, sans-serif;
       color: #e9d5ff;
       display: flex;
       align-items: center;
       justify-content: center;
       height: 100vh;
       margin: 0;
       position: relative;
     }
     body::before {
       content: '';
       position: fixed;
       inset: 0;
       background: radial-gradient(circle at center, rgba(60,10,110,0.78), rgba(10,2,25,0.95));
       z-index: 0;
       pointer-events: none;
     }
     .login-box {
       position: relative;
       z-index: 1;
       background: rgba(25,8,55,0.88);
       backdrop-filter: blur(14px);
       -webkit-backdrop-filter: blur(14px);
       padding: 35px 30px;
       border-radius: 16px;
       border: 1px solid rgba(168,85,247,0.5);
       box-shadow: 0 0 50px rgba(168,85,247,0.35), 0 0 100px rgba(124,58,237,0.2);
       width: 360px;
       text-align: center;
       animation: boxGlow 3s ease-in-out infinite;
     }
     @keyframes boxGlow {
       0%,100% { box-shadow: 0 0 50px rgba(168,85,247,0.35), 0 0 100px rgba(124,58,237,0.2); }
       50%     { box-shadow: 0 0 70px rgba(192,132,252,0.5), 0 0 140px rgba(168,85,247,0.3); }
     }
     .login-logo {
       width: 200px;
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
     h1 {
       color: #c084fc;
       font-size: 16px;
       letter-spacing: 3px;
       text-transform: uppercase;
       text-shadow: 0 0 12px #a855f7;
       margin: 0 0 20px;
     }
     input[type="password"] {
       width: 100%;
       padding: 12px 14px;
       border-radius: 8px;
       border: 1px solid rgba(168,85,247,0.5);
       background: rgba(15,5,35,0.9);
       color: #e9d5ff;
       font-family: Tahoma;
       font-size: 13px;
       outline: none;
       transition: border-color .2s, box-shadow .2s;
     }
     input[type="password"]::placeholder { color: #a78bfa; opacity: 0.6; }
     input[type="password"]:focus {
       border-color: #a855f7;
       box-shadow: 0 0 15px rgba(168,85,247,0.5);
     }
     button {
       margin-top: 15px;
       width: 100%;
       padding: 12px;
       border-radius: 8px;
       border: none;
       background: linear-gradient(45deg, #a855f7, #7c3aed);
       color: #fff;
       font-family: Tahoma;
       font-weight: bold;
       letter-spacing: 2px;
       cursor: pointer;
       text-transform: uppercase;
       font-size: 12px;
       transition: all .2s;
     }
     button:hover {
       opacity: .9;
       box-shadow: 0 0 20px rgba(168,85,247,0.7);
       transform: translateY(-2px);
     }
     .err {
       margin-top: 12px;
       font-size: 11px;
       color: #ff4d4d;
       min-height: 14px;
     }
    </style>
    </head>
    <body>
      <form method="post">
        <div class="login-box">
          <img src="https://s15.gifyu.com/images/bQjDP.png" alt="LOGO" class="login-logo">
          <h1>Backdoor Scanner</h1>
          <input type="password" name="password" placeholder="Password" autofocus required>
          <button type="submit">Login</button>
          <div class="err"><?= htmlspecialchars($error); ?></div>
        </div>
      </form>
    </body>
    </html>
    <?php
    exit;
}

// ======= AUDIT LOG =======
function audit_log($action, $detail = '') {
    global $LOG_FILE;
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $ts = date('Y-m-d H:i:s');
    $line = "[$ts] [$ip] $action — $detail\n";
    @file_put_contents($LOG_FILE, $line, FILE_APPEND);
}

// ======= AUTO BACKUP sebelum delete =======
function auto_backup($file) {
    global $BACKUP_DIR;
    if (!is_dir($BACKUP_DIR)) @mkdir($BACKUP_DIR, 0755, true);
    $zipFile = $BACKUP_DIR . '/backup_' . date('Ymd_His') . '_' . basename($file) . '.zip';
    if (class_exists('ZipArchive')) {
        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE) === true) {
            $zip->addFile($file, basename($file));
            $zip->close();
            return $zipFile;
        }
    }
    $dest = $BACKUP_DIR . '/backup_' . date('Ymd_His') . '_' . basename($file);
    if (@copy($file, $dest)) return $dest;
    return false;
}

// ======= SIGNATURE MODERN =======
function get_signatures() {
    return [
        ['pattern' => '/(eval|assert)\s*\(\s*(base64_decode|gzinflate|gzuncompress|str_rot13|hex2bin)/i', 'risk' => 'HIGH', 'name' => 'Eval+Decoder'],
        ['pattern' => '/\b(system|exec|shell_exec|passthru|popen|proc_open|pcntl_exec)\s*\(\s*\$_/i', 'risk' => 'HIGH', 'name' => 'Command Exec from Input'],
        ['pattern' => '/\$_(GET|POST|REQUEST|COOKIE)\s*\[.*?\]\s*\(\s*\$_(GET|POST|REQUEST|COOKIE)/i', 'risk' => 'HIGH', 'name' => 'Variable Function Attack'],
        ['pattern' => '/c99|r57|b374k|wso\s*shell|indoxploit|alfa\s*shell|priv8|madspot/i', 'risk' => 'HIGH', 'name' => 'Known Shell Name'],
        ['pattern' => '/\b(base64_decode|gzinflate|gzuncompress|str_rot13)\s*\(\s*[\'"]/i', 'risk' => 'MEDIUM', 'name' => 'Decoder'],
        ['pattern' => '/create_function\s*\(/i', 'risk' => 'MEDIUM', 'name' => 'create_function'],
        ['pattern' => '/\b(eval|assert)\s*\(/i', 'risk' => 'MEDIUM', 'name' => 'Eval/Assert'],
        ['pattern' => '/preg_replace\s*\(\s*[\'"].*\/e[\'"]/i', 'risk' => 'HIGH', 'name' => 'preg_replace /e'],
        ['pattern' => '/\b(file_put_contents|fwrite|fopen)\s*\(.*?(http|ftp):\/\//i', 'risk' => 'HIGH', 'name' => 'Remote File Write'],
        ['pattern' => '/\b(curl_exec|fsockopen|stream_socket_client)\s*\(/i', 'risk' => 'MEDIUM', 'name' => 'Remote Connection'],
        ['pattern' => '/\b(move_uploaded_file|copy)\s*\(.*?\$_(FILES|GET|POST)/i', 'risk' => 'HIGH', 'name' => 'Upload Bypass'],
        ['pattern' => '/\b(call_user_func|call_user_func_array)\s*\(\s*\$_(GET|POST|REQUEST)/i', 'risk' => 'HIGH', 'name' => 'call_user_func Attack'],
        ['pattern' => '/\$\$\w+\s*\(/i', 'risk' => 'MEDIUM', 'name' => 'Variable Variable'],
        ['pattern' => '/preg_replace_callback\s*\(\s*[\'"].*[\'"]\s*,\s*(create_function|eval|assert)/i', 'risk' => 'HIGH', 'name' => 'preg_replace_callback+Eval'],
        ['pattern' => '/\b(phpinfo|php_uname|getcwd|chdir)\s*\(/i', 'risk' => 'LOW', 'name' => 'Info Leak'],
        ['pattern' => '/\b(shell_exec|passthru|system)\s*\(/i', 'risk' => 'MEDIUM', 'name' => 'Command Exec'],
        ['pattern' => '/\$GLOBALS\s*\[.*?\]\s*\(/i', 'risk' => 'HIGH', 'name' => 'GLOBALS Function'],
    ];
}

// ======= SORT BY RISK =======
function risk_weight($risk) {
    switch ($risk) {
        case 'HIGH':   return 3;
        case 'MEDIUM': return 2;
        case 'LOW':    return 1;
        default:       return 0;
    }
}

// ======= LIST FILES RECURSIVE =======
function list_files_recursive($dir, &$out = []) {
    if (!is_dir($dir)) return $out;
    $dh = @opendir($dir);
    if (!$dh) return $out;
    while (($file = readdir($dh)) !== false) {
        if ($file === '.' || $file === '..') continue;
        $full = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($full)) {
            if (strpos($full, 'scanner_backups') !== false) continue;
            list_files_recursive($full, $out);
        } else {
            $ext = strtolower(pathinfo($full, PATHINFO_EXTENSION));
            if (in_array($ext, ['php','phtml','php3','php4','php5','phps','inc','phar'])) {
                $out[] = $full;
            }
        }
    }
    closedir($dh);
    return $out;
}

// ======= SCAN FILE =======
function scan_file($file, $custom_keyword = '') {
    $content = @file_get_contents($file);
    if ($content === false) return [];
    $matches = [];
    $sigs = get_signatures();
    foreach ($sigs as $sig) {
        if (@preg_match($sig['pattern'], $content, $m)) {
            $matches[] = [
                'name' => $sig['name'],
                'risk' => $sig['risk'],
                'match' => $m[0],
                'pattern' => $sig['pattern'],
            ];
        }
    }
    if ($custom_keyword !== '') {
        if (@preg_match('/' . preg_quote($custom_keyword, '/') . '/i', $content)) {
            $matches[] = [
                'name' => 'Custom: ' . $custom_keyword,
                'risk' => 'MEDIUM',
                'match' => $custom_keyword,
                'pattern' => 'custom',
            ];
        }
    }
    return $matches;
}

// ======= HANDLE POST ACTIONS =======
$messages = [];
$scanResults = [];
$editContent = '';
$editFile = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_file']) && $_POST['delete_file'] !== '') {
        $f = $_POST['delete_file'];
        if (file_exists($f) && is_file($f)) {
            $backup = auto_backup($f);
            if (unlink($f)) {
                audit_log('DELETE', "$f (backup: " . ($backup ?: 'FAILED') . ")");
                $messages[] = "✅ File dihapus: $f" . ($backup ? " (backup: $backup)" : " (backup GAGAL!)");
            } else {
                $messages[] = "❌ Gagal hapus: $f";
            }
        } else {
            $messages[] = "❌ File tidak ada: $f";
        }
    }

    if (isset($_POST['save_edit']) && isset($_POST['edit_content']) && isset($_POST['edit_file'])) {
        $f = $_POST['edit_file'];
        if (file_exists($f) && is_writable($f)) {
            $backup = auto_backup($f);
            if (file_put_contents($f, $_POST['edit_content']) !== false) {
                audit_log('EDIT', "$f (backup: " . ($backup ?: 'FAILED') . ")");
                $messages[] = "✅ File disimpan: $f";
            } else {
                $messages[] = "❌ Gagal simpan: $f";
            }
        } else {
            $messages[] = "❌ File tidak bisa ditulis: $f";
        }
    }

    if (isset($_POST['do_scan'])) {
        $target = $_SERVER['DOCUMENT_ROOT'] ?? '/var/www';
        $custom = trim($_POST['custom_keyword'] ?? '');
        audit_log('SCAN', "target=$target custom=$custom");
        $files = list_files_recursive($target);
        $total = count($files);
        $found = 0;
        foreach ($files as $f) {
            $m = scan_file($f, $custom);
            if (!empty($m)) {
                $maxRisk = 'LOW';
                foreach ($m as $mm) {
                    if (risk_weight($mm['risk']) > risk_weight($maxRisk)) $maxRisk = $mm['risk'];
                }
                $scanResults[] = [
                    'file' => $f,
                    'size' => filesize($f),
                    'mtime' => filemtime($f),
                    'matches' => $m,
                    'max_risk' => $maxRisk,
                ];
                $found++;
            }
        }
        usort($scanResults, function($a, $b) {
            return risk_weight($b['max_risk']) - risk_weight($a['max_risk']);
        });
        $messages[] = "🔍 Scan selesai — $total file diperiksa, $found terdeteksi mencurigakan";
    }
}

if (isset($_GET['edit']) && $_GET['edit'] !== '') {
    $editFile = $_GET['edit'];
    if (file_exists($editFile) && is_file($editFile)) {
        $editContent = @file_get_contents($editFile);
    } else {
        $messages[] = "❌ File tidak ada: $editFile";
        $editFile = '';
    }
}

$self = htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="robots" content="noindex, nofollow, noarchive">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Backdoor Scanner — Pro</title>
<link rel="icon" type="image/png" href="https://s15.gifyu.com/images/bQ0b1.png">
<style>
 * { box-sizing: border-box; }
 body {
   margin: 0;
   padding: 20px;
   font-family: Tahoma, Verdana, sans-serif;
   font-size: 12px;
   color: #e9d5ff;
   background: url('https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhS4-Ur1M0AF5_oQTYXjcC5MepAlGt3HKLc0QLHspmYQ7iTfln0SUtkSQak6AZ1-h_0yq6eOmbyfRRZJ1UMhUt8s_HQxjHqcULFe1ApCVRIOFWN8re-UBDV6yvGTR6uREN_MGGqBQnx-nKH5Sd1J-zbA_HOHhA1NW_o39Owk8Kbs213XOnxKdewVrk3R9Q/s736/kok.gif') no-repeat center center fixed;
   background-size: cover;
   min-height: 100vh;
   position: relative;
 }
 body::before {
   content: '';
   position: fixed;
   inset: 0;
   background: linear-gradient(135deg, rgba(10,2,25,0.9) 0%, rgba(35,10,65,0.88) 100%);
   z-index: -1;
   pointer-events: none;
 }
 .header {
   display: flex;
   align-items: center;
   gap: 15px;
   justify-content: space-between;
   margin-bottom: 20px;
   padding: 15px 20px;
   background: rgba(21,8,38,0.88);
   backdrop-filter: blur(10px);
   -webkit-backdrop-filter: blur(10px);
   border-radius: 12px;
   border: 1px solid #4c1d95;
   box-shadow: 0 0 30px rgba(168,85,247,0.3);
   flex-wrap: wrap;
 }
 .header-left { display: flex; align-items: center; gap: 15px; }
 .header img {
   width: 55px;
   height: 55px;
   object-fit: contain;
   filter: drop-shadow(0 0 10px #a855f7) drop-shadow(0 0 20px #7c3aed);
   animation: floatLogo 3s ease-in-out infinite;
 }
 @keyframes floatLogo {
   0%,100% { transform: translateY(0); }
   50%     { transform: translateY(-4px); }
 }
 .header h1 {
   color: #c084fc;
   font-size: 18px;
   letter-spacing: 3px;
   text-transform: uppercase;
   text-shadow: 0 0 12px #a855f7;
   margin: 0;
 }
 .header-sub {
   color: #a78bfa;
   font-size: 10px;
   letter-spacing: 2px;
   margin-top: 3px;
 }
 .logout-btn {
   padding: 8px 18px;
   background: rgba(255,77,106,0.15);
   border: 1px solid rgba(255,77,106,0.5);
   color: #ff4d4d;
   border-radius: 6px;
   text-decoration: none;
   font-weight: bold;
   letter-spacing: 1px;
   font-size: 11px;
   transition: all .2s;
 }
 .logout-btn:hover {
   background: rgba(255,77,106,0.3);
   color: #fff;
   box-shadow: 0 0 15px rgba(255,77,106,0.5);
 }
 .box {
   background: rgba(21,8,38,0.88);
   backdrop-filter: blur(10px);
   -webkit-backdrop-filter: blur(10px);
   border-radius: 12px;
   border: 1px solid #2d1050;
   box-shadow: 0 10px 30px rgba(168,85,247,0.15);
   padding: 15px 18px;
   margin-bottom: 15px;
 }
 .box-title {
   color: #c084fc;
   font-size: 12px;
   text-transform: uppercase;
   letter-spacing: 2px;
   margin: 0 0 12px;
   padding-bottom: 8px;
   border-bottom: 1px solid #4c1d95;
   text-shadow: 0 0 8px rgba(168,85,247,0.5);
 }
 input[type="text"], input[type="password"], textarea {
   background: rgba(10,2,25,0.9);
   color: #e9d5ff;
   border: 1px solid rgba(168,85,247,0.4);
   border-radius: 6px;
   padding: 8px 10px;
   font-family: Tahoma, monospace;
   font-size: 12px;
   outline: none;
   transition: border-color .2s, box-shadow .2s;
   width: 100%;
 }
 input[type="text"]:focus, textarea:focus {
   border-color: #a855f7;
   box-shadow: 0 0 12px rgba(168,85,247,0.5);
 }
 textarea {
   font-family: 'Courier New', monospace;
   resize: vertical;
   min-height: 200px;
   line-height: 1.4;
 }
 button, input[type="submit"] {
   background: linear-gradient(45deg, #a855f7, #7c3aed);
   color: #fff;
   border: none;
   padding: 9px 20px;
   border-radius: 6px;
   cursor: pointer;
   font-family: Tahoma;
   font-weight: bold;
   font-size: 11px;
   letter-spacing: 1.5px;
   text-transform: uppercase;
   transition: all .2s;
 }
 button:hover, input[type="submit"]:hover {
   box-shadow: 0 0 20px rgba(168,85,247,0.7);
   transform: translateY(-1px);
 }
 .btn-danger {
   background: linear-gradient(45deg, #ff4d4d, #cc0000);
 }
 .btn-danger:hover {
   box-shadow: 0 0 20px rgba(255,77,106,0.7);
 }
 .btn-ghost {
   background: transparent;
   border: 1px solid #4c1d95;
   color: #c084fc;
 }
 .btn-ghost:hover {
   background: rgba(168,85,247,0.15);
   color: #fff;
 }
 .msg {
   padding: 10px 14px;
   border-radius: 8px;
   margin-bottom: 10px;
   font-size: 12px;
 }
 .msg-ok {
   background: rgba(168,85,247,0.15);
   border: 1px solid rgba(168,85,247,0.4);
   color: #c084fc;
 }
 .msg-err {
   background: rgba(255,77,106,0.15);
   border: 1px solid rgba(255,77,106,0.4);
   color: #ff4d4d;
 }
 table {
   width: 100%;
   border-collapse: collapse;
   font-size: 11px;
 }
 th, td {
   padding: 8px 10px;
   text-align: left;
   border-bottom: 1px solid #2d1050;
   word-break: break-all;
 }
 th {
   background: rgba(30,8,60,0.92);
   color: #c084fc;
   font-size: 10px;
   text-transform: uppercase;
   letter-spacing: 1px;
   border-bottom: 2px solid #4c1d95;
 }
 tr:hover { background: rgba(168,85,247,0.08); }
 .risk {
   display: inline-block;
   padding: 2px 8px;
   border-radius: 4px;
   font-size: 10px;
   font-weight: bold;
   letter-spacing: 1px;
 }
 .risk-HIGH {
   background: rgba(255,77,106,0.25);
   color: #ff4d4d;
   border: 1px solid rgba(255,77,106,0.6);
   animation: pulseRed 1.5s infinite;
 }
 .risk-MEDIUM {
   background: rgba(255,170,0,0.2);
   color: #ffaa00;
   border: 1px solid rgba(255,170,0,0.5);
 }
 .risk-LOW {
   background: rgba(168,85,247,0.2);
   color: #c084fc;
   border: 1px solid rgba(168,85,247,0.4);
 }
 @keyframes pulseRed {
   0%,100% { box-shadow: 0 0 5px rgba(255,77,106,0.5); }
   50%     { box-shadow: 0 0 15px rgba(255,77,106,0.9); }
 }
 .action-btn {
   display: inline-block;
   padding: 4px 10px;
   font-size: 10px;
   border-radius: 4px;
   text-decoration: none;
   font-weight: bold;
   letter-spacing: 1px;
   margin-right: 4px;
   transition: all .15s;
 }
 .action-edit {
   background: rgba(168,85,247,0.15);
   color: #c084fc;
   border: 1px solid rgba(168,85,247,0.4);
 }
 .action-edit:hover {
   background: rgba(168,85,247,0.3);
   color: #fff;
 }
 .action-del {
   background: rgba(255,77,106,0.15);
   color: #ff4d4d;
   border: 1px solid rgba(255,77,106,0.4);
 }
 .action-del:hover {
   background: rgba(255,77,106,0.3);
   color: #fff;
 }
 /* ===== COPY BUTTON ===== */
 .copy-btn {
   background: linear-gradient(45deg, #a855f7, #7c3aed);
   color: #fff;
   border: none;
   padding: 7px 16px;
   border-radius: 6px;
   cursor: pointer;
   font-family: Tahoma;
   font-weight: bold;
   font-size: 10px;
   letter-spacing: 1px;
   text-transform: uppercase;
   transition: all .2s;
 }
 .copy-btn:hover {
   box-shadow: 0 0 20px rgba(168,85,247,0.7);
   transform: translateY(-1px);
 }
 .action-copy {
   background: rgba(168,85,247,0.15);
   color: #c084fc;
   border: 1px solid rgba(168,85,247,0.4);
   cursor: pointer;
   font-family: Tahoma;
   font-weight: bold;
 }
 .action-copy:hover {
   background: rgba(168,85,247,0.3);
   color: #fff;
   box-shadow: 0 0 12px rgba(168,85,247,0.5);
 }
 .copy-notif {
   padding: 10px 14px;
   border-radius: 8px;
   margin-bottom: 12px;
   background: rgba(168,85,247,0.15);
   border: 1px solid rgba(168,85,247,0.5);
   color: #c084fc;
   font-size: 12px;
   font-weight: bold;
   animation: notifPop .3s ease;
 }
 .copy-notif-err {
   background: rgba(255,77,106,0.15);
   border-color: rgba(255,77,106,0.5);
   color: #ff4d4d;
 }
 @keyframes notifPop {
   0% { transform: translateY(-10px); opacity: 0; }
   100% { transform: translateY(0); opacity: 1; }
 }
 ::-webkit-scrollbar { width: 10px; height: 10px; }
 ::-webkit-scrollbar-track { background: rgba(21,8,38,0.6); }
 ::-webkit-scrollbar-thumb { background: #4c1d95; border-radius: 5px; }
 ::-webkit-scrollbar-thumb:hover { background: #a855f7; }
 @media (max-width: 768px) {
   .header { padding: 12px; }
   .header h1 { font-size: 14px; }
   .header img { width: 40px; height: 40px; }
   th, td { padding: 6px; font-size: 10px; }
 }
</style>
</head>
<body>

<div class="header">
  <div class="header-left">
    <img src="https://s15.gifyu.com/images/bQjDP.png" alt="LOGO">
    <div>
      <h1>Backdoor Scanner</h1>
      <div class="header-sub">PRO EDITION — v2.0</div>
    </div>
  </div>
  <a href="?logout=1" class="logout-btn" onclick="return confirm('Logout?')">Logout</a>
</div>

<?php foreach ($messages as $m): ?>
  <div class="msg <?= strpos($m, '❌') !== false ? 'msg-err' : 'msg-ok' ?>"><?= htmlspecialchars($m) ?></div>
<?php endforeach; ?>

<!-- ===== SCAN FORM ===== -->
<?php if ($editFile === ''): ?>
<div class="box">
  <h3 class="box-title">🔍 Scan Configuration</h3>
  <form method="post">
    <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
      <input type="text" name="custom_keyword" placeholder="Custom keyword (opsional)" style="flex: 1; min-width: 200px;">
      <button type="submit" name="do_scan" value="1">▶ Start Scan</button>
    </div>
    <div style="margin-top: 10px; font-size: 10px; color: #a78bfa;">
      Target: <code style="color: #c084fc;"><?= htmlspecialchars($_SERVER['DOCUMENT_ROOT'] ?? '/var/www') ?></code>
    </div>
  </form>
</div>
<?php endif; ?>

<!-- ===== SCAN RESULTS ===== -->
<?php if (!empty($scanResults)): ?>
<div class="box">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
    <h3 class="box-title" style="margin: 0; border: none; padding: 0;">📋 Scan Results (<?= count($scanResults) ?> file mencurigakan)</h3>
    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
      <button type="button" onclick="copyAllResults()" class="copy-btn">📋 Copy All</button>
      <button type="button" onclick="downloadResults()" class="copy-btn">💾 Export TXT</button>
    </div>
  </div>

  <div id="copy-notif" class="copy-notif" style="display:none;"></div>

  <div style="overflow-x: auto;">
    <table>
      <thead>
        <tr>
          <th style="width: 60px;">Risk</th>
          <th>File</th>
          <th style="width: 180px;">Detection</th>
          <th style="width: 90px;">Size</th>
          <th style="width: 130px;">Modified</th>
          <th style="width: 150px;">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($scanResults as $idx => $r):
          $lineParts = [];
          $lineParts[] = "[" . $r['max_risk'] . "]";
          $lineParts[] = $r['file'];
          $lineParts[] = "Size: " . number_format($r['size']) . " B";
          $lineParts[] = "Modified: " . date('Y-m-d H:i', $r['mtime']);
          $detections = [];
          foreach ($r['matches'] as $m) {
              $detections[] = $m['risk'] . ":" . $m['name'];
          }
          $lineParts[] = "Detection: " . implode(", ", $detections);
          $lineStr = implode(" | ", $lineParts);
        ?>
          <tr>
            <td><span class="risk risk-<?= $r['max_risk'] ?>"><?= $r['max_risk'] ?></span></td>
            <td style="font-family: 'Courier New', monospace; color: #d8b4fe;"><?= htmlspecialchars($r['file']) ?></td>
            <td>
              <?php foreach ($r['matches'] as $m): ?>
                <div style="margin-bottom: 3px;">
                  <span class="risk risk-<?= $m['risk'] ?>"><?= $m['risk'] ?></span>
                  <?= htmlspecialchars($m['name']) ?>
                </div>
              <?php endforeach; ?>
            </td>
            <td><?= number_format($r['size']) ?> B</td>
            <td><?= date('Y-m-d H:i', $r['mtime']) ?></td>
            <td>
              <a href="?edit=<?= urlencode($r['file']) ?>" class="action-btn action-edit">✏ Edit</a>
              <button type="button" class="action-btn action-copy" data-copy="<?= htmlspecialchars($lineStr, ENT_QUOTES, 'UTF-8') ?>" onclick="copyLine(this)">📋</button>
              <form method="post" style="display:inline;" onsubmit="return confirm('Hapus <?= htmlspecialchars(addslashes(basename($r['file']))) ?>?\n\nBackup otomatis akan dibuat di folder scanner_backups/');">
                <input type="hidden" name="delete_file" value="<?= htmlspecialchars($r['file']) ?>">
                <button type="submit" class="action-btn action-del">🗑 Del</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Hidden textarea buat data copy -->
<textarea id="scan-data-all" style="display:none;"><?php
  $report = "=== BACKDOOR SCANNER REPORT ===\n";
  $report .= "Date: " . date('Y-m-d H:i:s') . "\n";
  $report .= "Target: " . ($_SERVER['DOCUMENT_ROOT'] ?? '/var/www') . "\n";
  $report .= "Total suspicious: " . count($scanResults) . " file\n";
  $report .= "================================\n\n";
  foreach ($scanResults as $i => $r) {
      $report .= "#" . ($i + 1) . " [" . $r['max_risk'] . "]\n";
      $report .= "File: " . $r['file'] . "\n";
      $report .= "Size: " . number_format($r['size']) . " B\n";
      $report .= "Modified: " . date('Y-m-d H:i', $r['mtime']) . "\n";
      $report .= "Detection:\n";
      foreach ($r['matches'] as $m) {
          $report .= "  - [" . $m['risk'] . "] " . $m['name'] . "\n";
          $report .= "    Match: " . $m['match'] . "\n";
      }
      $report .= "\n";
  }
  echo htmlspecialchars($report, ENT_QUOTES, 'UTF-8');
?></textarea>

<script>
function showNotif(msg, isError) {
    var n = document.getElementById('copy-notif');
    n.textContent = msg;
    n.style.display = 'block';
    n.className = 'copy-notif ' + (isError ? 'copy-notif-err' : '');
    setTimeout(function() {
        n.style.display = 'none';
    }, 3000);
}

function copyAllResults() {
    var data = document.getElementById('scan-data-all').value;
    copyToClipboard(data);
}

function copyLine(btn) {
    var data = btn.getAttribute('data-copy');
    copyToClipboard(data);
}

function copyToClipboard(text) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(function() {
            showNotif('✅ Hasil scan berhasil di-copy ke clipboard!');
        }).catch(function() {
            fallbackCopy(text);
        });
    } else {
        fallbackCopy(text);
    }
}

function fallbackCopy(text) {
    var ta = document.createElement('textarea');
    ta.value = text;
    ta.style.position = 'fixed';
    ta.style.top = '0';
    ta.style.left = '0';
    ta.style.opacity = '0';
    document.body.appendChild(ta);
    ta.focus();
    ta.select();
    try {
        var ok = document.execCommand('copy');
        if (ok) {
            showNotif('✅ Hasil scan berhasil di-copy ke clipboard!');
        } else {
            showNotif('❌ Gagal copy — coba manual ya', true);
        }
    } catch (e) {
        showNotif('❌ Gagal copy: ' + e.message, true);
    }
    document.body.removeChild(ta);
}

function downloadResults() {
    var data = document.getElementById('scan-data-all').value;
    var blob = new Blob([data], { type: 'text/plain;charset=utf-8' });
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = 'backdoor_scan_' + new Date().toISOString().slice(0,19).replace(/[T:]/g, '-') + '.txt';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    showNotif('💾 File berhasil di-download!');
}
</script>
<?php elseif (isset($_POST['do_scan'])): ?>
<div class="box">
  <h3 class="box-title">📋 Scan Results</h3>
  <p style="text-align: center; color: #a78bfa; padding: 20px;">
    ✅ Tidak ada file mencurigakan terdeteksi.<br>
    <small style="color: #7c3aed;">Tapi ingat: scanner ini hanya deteksi pola umum. Backdoor yang di-obfuscate rumit mungkin lolos.</small>
  </p>
</div>
<?php endif; ?>

<!-- ===== EDIT FILE ===== -->
<?php if ($editFile !== ''): ?>
<div class="box">
  <h3 class="box-title">✏ Edit File: <?= htmlspecialchars(basename($editFile)) ?></h3>
  <div style="font-size: 10px; color: #a78bfa; margin-bottom: 10px; word-break: break-all;">
    <?= htmlspecialchars($editFile) ?>
  </div>
  <form method="post">
    <input type="hidden" name="edit_file" value="<?= htmlspecialchars($editFile) ?>">
    <textarea name="edit_content" spellcheck="false"><?= htmlspecialchars($editContent) ?></textarea>
    <div style="margin-top: 12px; display: flex; gap: 10px; flex-wrap: wrap;">
      <button type="submit" name="save_edit" value="1">💾 Save</button>
      <a href="<?= $self ?>" class="action-btn action-edit" style="padding: 9px 20px;">← Kembali</a>
      <form method="post" style="display:inline;" onsubmit="return confirm('Hapus file ini?\n\nBackup otomatis akan dibuat.');">
        <input type="hidden" name="delete_file" value="<?= htmlspecialchars($editFile) ?>">
        <button type="submit" class="btn-danger" style="padding: 9px 20px;">🗑 Delete</button>
      </form>
    </div>
  </form>
</div>
<?php endif; ?>

<!-- ===== FOOTER INFO ===== -->
<div class="box" style="font-size: 10px; color: #a78bfa; line-height: 1.6;">
  <h3 class="box-title">ℹ Info</h3>
  <div><b style="color: #c084fc;">Backup folder:</b> <code><?= htmlspecialchars($BACKUP_DIR) ?></code></div>
  <div><b style="color: #c084fc;">Audit log:</b> <code><?= htmlspecialchars($LOG_FILE) ?></code></div>
  <div><b style="color: #c084fc;">Signatures:</b> <?= count(get_signatures()) ?> pola deteksi</div>
  <div style="margin-top: 8px; color: #7c3aed;">
    <b>Tips:</b> File yang dihapus otomatis di-backup ke folder <code>scanner_backups/</code>.
    Kalau salah hapus, tinggal extract zip-nya.
  </div>
</div>

</body>
</html>

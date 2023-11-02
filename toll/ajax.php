<?php
require_once './app.php';
$post = $_REQUEST;
$response = [];
$auth = new \Toll_Integration\Auth();
$db = new \Toll_Integration\DB();
if (!empty($post) && isset($post['action']) && $post['action'] == 'login') {
    $response = $auth->verify((object) $post);
}
if (!empty($post) && isset($post['action']) && ($post['action'] == 'order') || $post['action'] == 'email' || $post['action'] == 'delete') {
    $response = $db->processOrderForm((object) $post);
}
if (!empty($post) && isset($post['action']) && ($post['action'] == 'order_update') || $post['action'] == 'email_update') {
    $response = $db->processOrderForm((object) $post);
}
if (!empty($post) && isset($post['action']) && $post['action'] == 'shopify') {
    $response = $db->processConfigForm((object) $post);
}
if (!empty($post) && isset($post['action']) && $post['action'] == 'toll') {
    $response = $db->processConfigForm((object) $post);
}
echo json_encode($response);

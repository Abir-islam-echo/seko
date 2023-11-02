<?php
error_reporting(E_ALL);
require_once './app.php';
// $request = trim(str_replace(getenv('APP_REQUEST_BASE'), '', $_SERVER['REQUEST_URI']) ?: 'login');
$request = ltrim(str_replace(getenv('APP_REQUEST_BASE'), '', $_SERVER['REQUEST_URI']) ?: 'login', '/');
define('CURRENTPAGE', $request);
loadView('pages/' . $request);

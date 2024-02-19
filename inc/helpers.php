<?php

function loadView($view)
{
    $viewRoot = APP_DIR . '/public/view/' . $view . '.php';
    if (file_exists($viewRoot)) {
        include_once $viewRoot;
    } else {
        $viewRoot = APP_DIR . '/public/view/pages/404.php';
        http_response_code(404);
        include_once $viewRoot;
    }
}

function getRoute($request)
{
    if ($request) {
        return APP_URL . $request;
    }

    return $request;
}

function activePage($currentPage)
{
    return CURRENTPAGE == $currentPage;
}

function loadRoute($request)
{
    header("Location: " . $request);
}


function getHeaderValue($headerKey)
{
    $headers = getallheaders();
    if ($headerKey) {
        return $headers[$headerKey];
    }
    return '';
}



function logMe($message)
{
    if (is_array($message) || is_object($message)) {
        error_log(print_r($message, true));
    } else {
        error_log($message);
    }
}

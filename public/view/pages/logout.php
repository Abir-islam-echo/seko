<?php
$auth = new \Toll_Integration\Auth();
$auth->logout();
loadRoute(getRoute('login'));

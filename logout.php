<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
$_SESSION = [];
session_destroy();
redirect(BASE_URL . '/login.php');

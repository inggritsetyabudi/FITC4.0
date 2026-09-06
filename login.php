<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController(get_db());
    $auth->login();
} else {
    header("Location: " . base_url('index.php?controller=auth&action=showLogin'));
    exit;
}

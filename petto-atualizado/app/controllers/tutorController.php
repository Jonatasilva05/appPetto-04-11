<?php

// Só permite acesso se o usuário estiver logado e for tutor
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'tutor') {
    header("Location: /petto/auth/login.php");
    exit;
}

function index() {
    include __DIR__ . '/../views/tutor/index.php';
}
?>

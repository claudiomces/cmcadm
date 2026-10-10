<?php
$currentPage = $currentPage ?? '';
$pageTitle = $pageTitle ?? 'DJM Distribuidora';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0a0a0f">
    <title><?= escape_html($pageTitle) ?> | DJM Distribuidora</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/app.js" defer></script>
</head>
<body>
<header class="site-header">
    <div class="header-inner">
        <a class="brand" href="index.php" aria-label="DJM Distribuidora - Home">
            <img src="assets/djm_logo.png" alt="DJM Distribuidora">
            <span>Peças e kits automotivos</span>
        </a>
        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav">
            <span class="menu-icon" aria-hidden="true">☰</span>
            <span class="sr-only">Abrir menu</span>
        </button>
        <nav class="main-nav" id="main-nav" aria-label="Navegação principal">
            <a href="index.php" <?= $currentPage === 'home' ? 'aria-current="page"' : '' ?>>Home</a>
            <a href="sobre.php" <?= $currentPage === 'sobre' ? 'aria-current="page"' : '' ?>>Sobre</a>
            <a href="catalogo.php" <?= $currentPage === 'catalogo' ? 'aria-current="page"' : '' ?>>Catálogo</a>
            <a href="contato.php" <?= $currentPage === 'contato' ? 'aria-current="page"' : '' ?>>Contato</a>
        </nav>
        <a class="header-contact" href="https://wa.me/5511940013289" target="_blank" rel="noopener noreferrer">Fale pelo WhatsApp</a>
    </div>
</header>
<main>

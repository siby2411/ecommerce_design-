<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';
requireLogin();

$pageTitle  = $pageTitle ?? 'Tableau de bord';
$activePage = $activePage ?? '';
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= clean($pageTitle) ?> | OMEGA INFORMATIQUE CONSULTING</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

<div class="app-shell">

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-mark">OI</div>
            <div class="brand-text">
                <strong>OMEGA</strong>
                <span>INFORMATIQUE CONSULTING</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Pilotage</div>
            <a href="<?= BASE_URL ?>/index.php" class="nav-link <?= $activePage === 'dashboard' ? 'active' : '' ?>">
                <span class="ic ic-dashboard"></span> Tableau de bord
            </a>

            <div class="nav-section">Catalogue</div>
            <a href="<?= BASE_URL ?>/categories/liste.php" class="nav-link <?= $activePage === 'categories' ? 'active' : '' ?>">
                <span class="ic ic-tag"></span> Catégories
            </a>
            <a href="<?= BASE_URL ?>/produits/liste.php" class="nav-link <?= $activePage === 'produits' ? 'active' : '' ?>">
                <span class="ic ic-box"></span> Produits
            </a>
            <a href="<?= BASE_URL ?>/fournisseurs/liste.php" class="nav-link <?= $activePage === 'fournisseurs' ? 'active' : '' ?>">
                <span class="ic ic-truck"></span> Fournisseurs
            </a>

            <div class="nav-section">Relation client</div>
            <a href="<?= BASE_URL ?>/clients/liste.php" class="nav-link <?= $activePage === 'clients' ? 'active' : '' ?>">
                <span class="ic ic-users"></span> Clients
            </a>
            <a href="<?= BASE_URL ?>/factures/liste.php" class="nav-link <?= $activePage === 'factures' ? 'active' : '' ?>">
                <span class="ic ic-invoice"></span> Facturation
            </a>

            <div class="nav-section">Administration</div>
            <a href="<?= BASE_URL ?>/utilisateurs/liste.php" class="nav-link <?= $activePage === 'utilisateurs' ? 'active' : '' ?>">
                <span class="ic ic-users-cog"></span> Utilisateurs
            </a>
            <a href="<?= BASE_URL ?>/logs/liste.php" class="nav-link <?= $activePage === 'logs' ? 'active' : '' ?>">
                <span class="ic ic-activity"></span> Journal
            </a>

            <div class="nav-section">Compte</div>
            <a href="<?= BASE_URL ?>/logout.php" class="nav-link nav-link-danger">
                <span class="ic ic-logout"></span> Déconnexion
            </a>
        </nav>

        <div class="sidebar-footer">
            &copy; <?= date('Y') ?> Omega Informatique Consulting
        </div>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-wrap">

        <header class="topbar">
            <button class="burger" id="burgerBtn" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
            <div class="topbar-title">
                <h1><?= clean($pageTitle) ?></h1>
            </div>
            <div class="topbar-user">
                <div class="user-avatar"><?= strtoupper(substr($_SESSION['user_nom'] ?? 'U', 0, 1)) ?></div>
                <div class="user-info">
                    <strong><?= clean($_SESSION['user_nom'] ?? 'Utilisateur') ?></strong>
                    <span><?= clean($_SESSION['user_role'] ?? '') ?></span>
                </div>
            </div>
        </header>

        <main class="content">
            <?php if ($flash): ?>
                <div class="alert alert-<?= clean($flash['type']) ?>">
                    <?= clean($flash['message']) ?>
                </div>
            <?php endif; ?>

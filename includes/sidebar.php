<?php
// Définir BASE_URL si non défini
if (!defined('BASE_URL')) {
    define('BASE_URL', '');
}

$activeMenu = $activeMenu ?? '';
function navLink(string $key, string $active, string $href, string $icon, string $label): string {
    $cls = $key === $active ? 'nav-link active' : 'nav-link';
    return '<a class="' . $cls . '" href="' . BASE_URL . $href . '"><i class="bi ' . $icon . '"></i>' . $label . '</a>';
}

$params = getParametres();
?>
<aside class="sidebar">
  <div class="brand">
    <div class="logo-badge">OI</div>
    <div class="brand-text">
      <b><?= e($params['nom_entreprise'] ?? 'OMEGA INFORMATIQUE') ?></b>
      <small><?= e($params['slogan'] ?? '') ?></small>
    </div>
  </div>
  <nav>
    <div class="nav-section-title">Général</div>
    <?= navLink('dashboard', $activeMenu, 'index.php', 'bi-speedometer2', 'Tableau de bord') ?>
    <?= navLink('catalogue', $activeMenu, 'catalogue.php', 'bi-grid-3x3-gap', 'Catalogue') ?>

    <div class="nav-section-title">Gestion</div>
    <?= navLink('produits', $activeMenu, 'produits/liste.php', 'bi-tags', 'Produits') ?>
    <?= navLink('clients', $activeMenu, 'clients/liste.php', 'bi-people', 'Clients') ?>
    <?= navLink('factures', $activeMenu, 'factures/liste.php', 'bi-receipt', 'Factures') ?>

    <div class="nav-section-title">Email</div>
    <?= navLink('send_email', $activeMenu, 'send_email_admin.php', 'bi-envelope', 'Envoyer un email') ?>
    <?= navLink('quick_email', $activeMenu, 'quick_email.php', 'bi-bolt', 'Email rapide') ?>

    <div class="nav-section-title">Analyse</div>
    <?= navLink('decisions', $activeMenu, 'decisions.php', 'bi-chart-line', 'Aide à la décision') ?>

    <div class="nav-section-title">Système</div>
    <?= navLink('parametres', $activeMenu, 'parametres.php', 'bi-gear', 'Paramètres') ?>
  </nav>
  <div class="sidebar-footer">
    <i class="bi bi-info-circle"></i> OMEGA v2.0<br>
    <span class="opacity-75">© <?= date('Y') ?> — Fait avec soin</span>
  </div>
</aside>

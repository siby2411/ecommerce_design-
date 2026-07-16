    <li class="nav-item">
        <a class="nav-link <?= $activePage == 'decisions' ? 'active' : '' ?>" href="<?= BASE_URL ?>/decisions.php">
            <i class="fas fa-chart-line"></i> Aide à la décision
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $activePage == 'send_email' ? 'active' : '' ?>" href="<?= BASE_URL ?>/send_email_admin.php">
            <i class="fas fa-envelope"></i> Envoyer un email
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $activePage == 'quick_email' ? 'active' : '' ?>" href="<?= BASE_URL ?>/quick_email.php">
            <i class="fas fa-bolt"></i> Email rapide
        </a>
    </li>

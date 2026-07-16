<?php
$pageTitle = 'Email rapide';
$activePage = 'quick_email';
require_once __DIR__ . '/includes/header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email rapide - OMEGA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-bolt text-warning"></i> Email rapide</h2>
        <a href="index.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <p class="text-muted">Envoyez un email en un clic à l'administration</p>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm text-center">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                    <i class="bi bi-exclamation-triangle" style="font-size:48px;color:#dc2626;"></i>
                    <h5 class="mt-3">Alerte stock</h5>
                    <p class="text-muted small">Signaler un problème de réapprovisionnement</p>
                    <form method="post">
                        <input type="hidden" name="action" value="stock">
                        <button type="submit" class="btn btn-danger btn-lg px-4">
                            <i class="bi bi-send"></i> Envoyer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm text-center">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                    <i class="bi bi-person-plus" style="font-size:48px;color:#16a34a;"></i>
                    <h5 class="mt-3">Nouveau client</h5>
                    <p class="text-muted small">Signaler un nouveau client à contacter</p>
                    <form method="post">
                        <input type="hidden" name="action" value="client">
                        <button type="submit" class="btn btn-success btn-lg px-4">
                            <i class="bi bi-send"></i> Envoyer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm text-center">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                    <i class="bi bi-file-earmark-text" style="font-size:48px;color:#f59e0b;"></i>
                    <h5 class="mt-3">Problème facture</h5>
                    <p class="text-muted small">Signaler un problème de facturation</p>
                    <form method="post">
                        <input type="hidden" name="action" value="facture">
                        <button type="submit" class="btn btn-warning btn-lg px-4">
                            <i class="bi bi-send"></i> Envoyer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <div class="card shadow-sm bg-light">
            <div class="card-body">
                <p class="mb-0 text-muted">
                    <i class="bi bi-info-circle"></i> 
                    Les emails sont envoyés à <strong>admin@omega-consulting.sn</strong>
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

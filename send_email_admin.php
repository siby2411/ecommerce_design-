<?php
$pageTitle = 'Envoyer un email';
$activePage = 'send_email';
require_once __DIR__ . '/includes/header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envoyer un email - OMEGA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-envelope-fill text-primary"></i> Envoyer un email</h2>
        <a href="index.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> 
                Cette fonctionnalité vous permet d'envoyer des emails à l'administration.
            </div>
            <form>
                <div class="mb-3">
                    <label class="form-label">Destinataire</label>
                    <input type="email" class="form-control" value="admin@omega-consulting.sn" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Sujet</label>
                    <input type="text" class="form-control" placeholder="Saisissez le sujet...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea class="form-control" rows="5" placeholder="Saisissez votre message..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send"></i> Envoyer
                </button>
            </form>
        </div>
    </div>

    <div class="mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-copy"></i> Modèles rapides</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <button class="btn btn-outline-danger w-100" onclick="loadTemplate('stock')">
                            <i class="bi bi-exclamation-triangle"></i> Alerte stock
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-outline-success w-100" onclick="loadTemplate('client')">
                            <i class="bi bi-person-plus"></i> Nouveau client
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-outline-warning w-100" onclick="loadTemplate('facture')">
                            <i class="bi bi-file-earmark-text"></i> Problème facture
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loadTemplate(type) {
    const templates = {
        stock: {
            subject: '⚠️ Alerte stock - Réapprovisionnement nécessaire',
            content: 'Bonjour,\n\nJe vous signale que certains produits sont en dessous du seuil de stock.\n\nMerci de procéder au réapprovisionnement.\n\nCordialement.'
        },
        client: {
            subject: '📋 Nouveau client à contacter',
            content: 'Bonjour,\n\nUn nouveau client nécessite une prise de contact.\n\nMerci de le contacter rapidement.\n\nCordialement.'
        },
        facture: {
            subject: '📄 Problème de facturation',
            content: 'Bonjour,\n\nJe rencontre un problème avec la facture suivante.\n\nMerci de bien vouloir vérifier.\n\nCordialement.'
        }
    };
    
    const tpl = templates[type];
    if (tpl) {
        document.querySelector('input[placeholder*="sujet"]').value = tpl.subject;
        document.querySelector('textarea').value = tpl.content;
    }
}
</script>
</body>
</html>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

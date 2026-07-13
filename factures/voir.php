<?php
$pageTitle  = 'Détail de la facture';
$activePage = 'factures';
require_once __DIR__ . '/../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    setFlash('danger', 'Facture non spécifiée.');
    redirect(BASE_URL . '/factures/liste.php');
}

$stmt = $pdo->prepare("
    SELECT f.*, c.nom AS client_nom, c.prenom AS client_prenom, c.adresse, c.telephone, c.email
    FROM factures f
    LEFT JOIN clients c ON c.id = f.id_client
    WHERE f.id = ?
");
$stmt->execute([$id]);
$facture = $stmt->fetch();
if (!$facture) {
    setFlash('danger', 'Facture introuvable.');
    redirect(BASE_URL . '/factures/liste.php');
}

$lignes = $pdo->prepare('SELECT * FROM facture_lignes WHERE id_facture = ?');
$lignes->execute([$id]);
$lignes = $lignes->fetchAll();

function badgeStatut($statut) {
    $map = ['payee'=>['Payée','green'], 'impayee'=>['Impayée','red'], 'brouillon'=>['Brouillon','gray'], 'annulee'=>['Annulée','orange']];
    [$label,$color] = $map[$statut] ?? [$statut,'gray'];
    return '<span class="badge badge-'.$color.'">'.$label.'</span>';
}
?>
<div class="page-head">
    <div>
        <h2 style="margin:0;">Facture <?= clean($facture['numero']) ?></h2>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="form.php?id=<?= $facture['id'] ?>" class="btn btn-outline">Modifier</a>
        <a href="imprimer.php?id=<?= $facture['id'] ?>" class="btn btn-gold" target="_blank">Imprimer</a>
        <a href="<?= BASE_URL ?>/factures/liste.php" class="btn btn-outline">Retour</a>
    </div>
</div>

<div class="panel" style="padding:28px;">
    <div class="invoice-head">
        <div>
            <p><strong>Client :</strong> <?= clean(trim($facture['client_prenom'] . ' ' . $facture['client_nom'])) ?></p>
            <p><strong>Email :</strong> <?= clean($facture['email']) ?></p>
            <p><strong>Téléphone :</strong> <?= clean($facture['telephone']) ?></p>
            <p><strong>Adresse :</strong> <?= clean($facture['adresse']) ?></p>
        </div>
        <div style="text-align:right;">
            <p><strong>Date :</strong> <?= formatDate($facture['date_facture']) ?></p>
            <p><strong>Statut :</strong> <?= badgeStatut($facture['statut']) ?></p>
            <p><strong>Total :</strong> <span style="font-size:22px;font-weight:800;"><?= formatPrix($facture['total']) ?></span></p>
        </div>
    </div>

    <div class="table-wrap" style="margin-top:18px;">
        <table>
            <thead>
                <tr><th>Désignation</th><th>Quantité</th><th>Prix unitaire</th><th>Sous-total</th></tr>
            </thead>
            <tbody>
            <?php foreach ($lignes as $l): ?>
                <tr>
                    <td><?= clean($l['designation']) ?></td>
                    <td><?= (int)$l['quantite'] ?></td>
                    <td><?= formatPrix($l['prix_unitaire']) ?></td>
                    <td><?= formatPrix($l['sous_total']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr><td colspan="3" style="text-align:right;font-weight:bold;">Total</td><td style="font-weight:bold;font-size:18px;"><?= formatPrix($facture['total']) ?></td></tr>
            </tfoot>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

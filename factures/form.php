<?php
$pageTitle  = isset($_GET['id']) ? 'Modifier une facture' : 'Nouvelle facture';
$activePage = 'factures';
require_once __DIR__ . '/../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$facture = null;
$lignes = [];
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM factures WHERE id = ?');
    $stmt->execute([$id]);
    $facture = $stmt->fetch();
    if (!$facture) {
        setFlash('danger', 'Facture introuvable.');
        redirect(BASE_URL . '/factures/liste.php');
    }
    $lignes = $pdo->prepare('SELECT * FROM facture_lignes WHERE id_facture = ?');
    $lignes->execute([$id]);
    $lignes = $lignes->fetchAll();
}

$clients = $pdo->query('SELECT id, nom, prenom FROM clients ORDER BY nom')->fetchAll();
$produits = $pdo->query('SELECT id, nom, prix FROM produits ORDER BY nom')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idClient   = (int)($_POST['id_client'] ?? 0);
    $dateFacture= $_POST['date_facture'] ?? date('Y-m-d');
    $statut     = $_POST['statut'] ?? 'impayee';
    $tva        = (float)($_POST['tva'] ?? 0);
    $lignesData = $_POST['lignes'] ?? [];

    if ($idClient === 0) {
        setFlash('danger', 'Veuillez sélectionner un client.');
    } else {
        // Calcul du total HT et TVA
        $totalHT = 0;
        foreach ($lignesData as $l) {
            $qte = (int)($l['quantite'] ?? 0);
            $prix = (float)($l['prix_unitaire'] ?? 0);
            $totalHT += $qte * $prix;
        }
        $montantTVA = calculerTVA($totalHT, $tva);
        $totalTTC = $totalHT + $montantTVA;

        if ($id) {
            $sql = 'UPDATE factures SET id_client=?, date_facture=?, statut=?, total=?, tva=?, montant_tva=? WHERE id=?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$idClient, $dateFacture, $statut, $totalTTC, $tva, $montantTVA, $id]);
            $pdo->prepare('DELETE FROM facture_lignes WHERE id_facture = ?')->execute([$id]);
            logAction($pdo, 'update', "Facture ID $id modifiée");
        } else {
            $numero = generateFactureNumero($pdo);
            $sql = 'INSERT INTO factures (numero, id_client, date_facture, statut, total, tva, montant_tva) VALUES (?,?,?,?,?,?,?)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$numero, $idClient, $dateFacture, $statut, $totalTTC, $tva, $montantTVA]);
            $id = $pdo->lastInsertId();
            logAction($pdo, 'create', "Facture ID $id créée");
        }

        foreach ($lignesData as $l) {
            $designation = trim($l['designation'] ?? '');
            $qte = (int)($l['quantite'] ?? 0);
            $prix = (float)($l['prix_unitaire'] ?? 0);
            $sousTotal = $qte * $prix;
            if ($designation !== '' && $qte > 0) {
                $sql = 'INSERT INTO facture_lignes (id_facture, id_produit, designation, quantite, prix_unitaire, sous_total) VALUES (?, ?, ?, ?, ?, ?)';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$id, $l['id_produit'] ?? null, $designation, $qte, $prix, $sousTotal]);
            }
        }

        setFlash('success', 'Facture enregistrée avec succès.');
        redirect(BASE_URL . '/factures/liste.php');
    }
}
?>
<div class="page-head">
    <div>
        <h2 style="margin:0;"><?= $id ? 'Modifier' : 'Nouvelle' ?> facture</h2>
    </div>
</div>

<div class="panel">
    <form method="POST" id="factureForm">
        <div class="form-grid">
            <div class="form-group">
                <label>Client *</label>
                <select name="id_client" required>
                    <option value="">-- Choisissez --</option>
                    <?php foreach ($clients as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= isset($facture) && $facture['id_client'] == $c['id'] ? 'selected' : '' ?>>
                            <?= clean($c['nom'] . ' ' . $c['prenom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Date facture</label>
                <input type="date" name="date_facture" value="<?= isset($facture) ? $facture['date_facture'] : date('Y-m-d') ?>">
            </div>
            <div class="form-group">
                <label>Statut</label>
                <select name="statut">
                    <option value="brouillon" <?= isset($facture) && $facture['statut'] == 'brouillon' ? 'selected' : '' ?>>Brouillon</option>
                    <option value="impayee" <?= isset($facture) && $facture['statut'] == 'impayee' ? 'selected' : '' ?>>Impayée</option>
                    <option value="payee" <?= isset($facture) && $facture['statut'] == 'payee' ? 'selected' : '' ?>>Payée</option>
                    <option value="annulee" <?= isset($facture) && $facture['statut'] == 'annulee' ? 'selected' : '' ?>>Annulée</option>
                </select>
            </div>
            <div class="form-group">
                <label>TVA (%)</label>
                <input type="number" step="0.01" name="tva" id="tvaInput" value="<?= isset($facture) ? $facture['tva'] : 18 ?>" onchange="recalcTotal()">
            </div>
        </div>

        <h3 style="margin:20px 0 12px;">Lignes de facture</h3>
        <div class="table-wrap" style="margin-bottom:12px;">
            <table>
                <thead>
                    <tr>
                        <th style="min-width:120px;">Produit</th>
                        <th>Désignation</th>
                        <th style="width:100px;">Qté</th>
                        <th style="width:120px;">Prix unit.</th>
                        <th style="width:120px;">Sous-total</th>
                        <th style="width:50px;"></th>
                    </tr>
                </thead>
                <tbody id="lineItemsBody">
                    <?php if ($id && !empty($lignes)): foreach ($lignes as $l): ?>
                        <tr class="line-item-row">
                            <td>
                                <select name="lignes[__INDEX__][id_produit]" class="line-produit">
                                    <option value="">-- Sélectionner --</option>
                                    <?php foreach ($produits as $p): ?>
                                        <option value="<?= $p['id'] ?>" data-prix="<?= $p['prix'] ?>" data-nom="<?= clean($p['nom']) ?>" <?= $l['id_produit'] == $p['id'] ? 'selected' : '' ?>>
                                            <?= clean($p['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="text" name="lignes[__INDEX__][designation]" class="line-designation" value="<?= clean($l['designation']) ?>" style="width:100%;"></td>
                            <td><input type="number" name="lignes[__INDEX__][quantite]" class="line-qte" value="<?= $l['quantite'] ?>" style="width:70px;"></td>
                            <td><input type="number" step="0.01" name="lignes[__INDEX__][prix_unitaire]" class="line-prix" value="<?= $l['prix_unitaire'] ?>" style="width:100px;"></td>
                            <td class="line-sous-total"><?= formatPrix($l['sous_total']) ?></td>
                            <td><button type="button" class="remove-line" style="background:none;border:none;color:red;font-weight:bold;font-size:18px;cursor:pointer;">&times;</button></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr class="line-item-row">
                            <td>
                                <select name="lignes[0][id_produit]" class="line-produit">
                                    <option value="">-- Sélectionner --</option>
                                    <?php foreach ($produits as $p): ?>
                                        <option value="<?= $p['id'] ?>" data-prix="<?= $p['prix'] ?>" data-nom="<?= clean($p['nom']) ?>">
                                            <?= clean($p['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="text" name="lignes[0][designation]" class="line-designation" style="width:100%;"></td>
                            <td><input type="number" name="lignes[0][quantite]" class="line-qte" value="1" style="width:70px;"></td>
                            <td><input type="number" step="0.01" name="lignes[0][prix_unitaire]" class="line-prix" style="width:100px;"></td>
                            <td class="line-sous-total">0,00 FCFA</td>
                            <td><button type="button" class="remove-line" style="background:none;border:none;color:red;font-weight:bold;font-size:18px;cursor:pointer;">&times;</button></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align:right;font-weight:bold;">Total HT :</td>
                        <td id="totalHTDisplay" style="font-weight:bold;">0,00 FCFA</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align:right;font-weight:bold;">TVA :</td>
                        <td id="tvaDisplay" style="font-weight:bold;">0,00 FCFA</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align:right;font-weight:bold;font-size:16px;">Total TTC :</td>
                        <td id="invoiceTotalDisplay" style="font-weight:bold;font-size:16px;">
                            <?php
                            $totalCalc = 0;
                            if ($id && !empty($lignes)) {
                                foreach ($lignes as $l) $totalCalc += $l['sous_total'];
                            }
                            $tvaVal = isset($facture) ? $facture['tva'] : 18;
                            $totalTTC = $totalCalc + calculerTVA($totalCalc, $tvaVal);
                            echo formatPrix($totalTTC);
                            ?>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <input type="hidden" name="total" id="invoiceTotalHidden" value="<?= $id ? $facture['total'] : 0 ?>">

        <div class="form-actions">
            <button type="button" id="addLineBtn" class="btn btn-outline">+ Ajouter une ligne</button>
            <button type="submit" class="btn btn-primary"><?= $id ? 'Mettre à jour' : 'Créer' ?></button>
            <a href="<?= BASE_URL ?>/factures/liste.php" class="btn btn-outline">Annuler</a>
        </div>
    </form>
</div>

<template id="lineRowTemplate">
    <tr class="line-item-row">
        <td>
            <select name="lignes[__INDEX__][id_produit]" class="line-produit">
                <option value="">-- Sélectionner --</option>
                <?php foreach ($produits as $p): ?>
                    <option value="<?= $p['id'] ?>" data-prix="<?= $p['prix'] ?>" data-nom="<?= clean($p['nom']) ?>">
                        <?= clean($p['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
        <td><input type="text" name="lignes[__INDEX__][designation]" class="line-designation" style="width:100%;"></td>
        <td><input type="number" name="lignes[__INDEX__][quantite]" class="line-qte" value="1" style="width:70px;"></td>
        <td><input type="number" step="0.01" name="lignes[__INDEX__][prix_unitaire]" class="line-prix" style="width:100px;"></td>
        <td class="line-sous-total">0,00 FCFA</td>
        <td><button type="button" class="remove-line" style="background:none;border:none;color:red;font-weight:bold;font-size:18px;cursor:pointer;">&times;</button></td>
    </tr>
</template>

<script>
function recalcTotal() {
    const rows = document.querySelectorAll('#lineItemsBody .line-item-row');
    let totalHT = 0;
    rows.forEach(row => {
        const qte = parseFloat(row.querySelector('.line-qte').value) || 0;
        const prix = parseFloat(row.querySelector('.line-prix').value) || 0;
        const sousTotal = qte * prix;
        row.querySelector('.line-sous-total').textContent = sousTotal.toLocaleString('fr-FR', {minimumFractionDigits:2, maximumFractionDigits:2}) + ' FCFA';
        totalHT += sousTotal;
    });
    const tva = parseFloat(document.getElementById('tvaInput').value) || 0;
    const montantTVA = totalHT * tva / 100;
    const totalTTC = totalHT + montantTVA;
    document.getElementById('totalHTDisplay').textContent = totalHT.toLocaleString('fr-FR', {minimumFractionDigits:2, maximumFractionDigits:2}) + ' FCFA';
    document.getElementById('tvaDisplay').textContent = montantTVA.toLocaleString('fr-FR', {minimumFractionDigits:2, maximumFractionDigits:2}) + ' FCFA';
    document.getElementById('invoiceTotalDisplay').textContent = totalTTC.toLocaleString('fr-FR', {minimumFractionDigits:2, maximumFractionDigits:2}) + ' FCFA';
    document.getElementById('invoiceTotalHidden').value = totalTTC.toFixed(2);
}
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('tvaInput').addEventListener('change', recalcTotal);
    // Bind existing rows
    document.querySelectorAll('#lineItemsBody .line-item-row').forEach(row => {
        row.querySelectorAll('.line-qte, .line-prix').forEach(input => input.addEventListener('input', recalcTotal));
    });
    // Add line button
    document.getElementById('addLineBtn').addEventListener('click', function() {
        const template = document.getElementById('lineRowTemplate').innerHTML.replace(/__INDEX__/g, Date.now());
        const tmp = document.createElement('tbody');
        tmp.innerHTML = template;
        const newRow = tmp.querySelector('.line-item-row');
        document.getElementById('lineItemsBody').appendChild(newRow);
        newRow.querySelectorAll('.line-qte, .line-prix').forEach(input => input.addEventListener('input', recalcTotal));
        recalcTotal();
    });
    recalcTotal();
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

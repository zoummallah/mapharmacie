<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span><?= isset($medicament) ? 'Modifier Médicament' : 'Ajouter un Médicament' ?></span>
    <a href="<?= BASE_URL ?>/index.php?page=medicaments" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Retour</a>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-body">
        <form action="" method="POST" id="formMedicament" novalidate>
            <h4 style="margin-bottom: 1.5rem; color: var(--primary-color); border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Informations Générales</h4>
            <div class="form-row">
                <div class="form-group form-col">
                    <label class="form-label">Nom commercial *</label>
                    <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($medicament['nom'] ?? '') ?>" required>
                </div>
                <div class="form-group form-col">
                    <label class="form-label">Catégorie *</label>
                    <select name="id_categorie" class="form-control" required>
                        <option value="">-- Sélectionner --</option>
                        <?php foreach($categories as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= (isset($medicament) && $medicament['id_categorie'] == $c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group form-col">
                    <label class="form-label">Principe Actif (DCI)</label>
                    <input type="text" name="principe_actif" class="form-control" value="<?= htmlspecialchars($medicament['principe_actif'] ?? '') ?>">
                </div>
                <div class="form-group form-col">
                    <label class="form-label">Dosage (ex: 500mg)</label>
                    <input type="text" name="dosage" class="form-control" value="<?= htmlspecialchars($medicament['dosage'] ?? '') ?>">
                </div>
                <div class="form-group form-col">
                    <label class="form-label">Forme galénique</label>
                    <input type="text" name="forme" class="form-control" value="<?= htmlspecialchars($medicament['forme'] ?? '') ?>">
                </div>
            </div>

            <h4 style="margin: 1.5rem 0; color: var(--primary-color); border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Prix et Stock</h4>
            <div class="form-row">
                <div class="form-group form-col">
                    <label class="form-label">Prix d'achat *</label>
                    <input type="number" step="0.01" name="prix_achat" class="form-control" value="<?= $medicament['prix_achat'] ?? '' ?>" required>
                </div>
                <div class="form-group form-col">
                    <label class="form-label">Prix de vente *</label>
                    <input type="number" step="0.01" name="prix_vente" class="form-control" value="<?= $medicament['prix_vente'] ?? '' ?>" required>
                </div>
                <div class="form-group form-col">
                    <label class="form-label">Seuil d'alerte (Min) *</label>
                    <input type="number" name="stock_minimum" class="form-control" value="<?= $medicament['stock_minimum'] ?? 5 ?>" required>
                </div>
            </div>

            <h4 style="margin: 1.5rem 0; color: var(--primary-color); border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Lot Initial & Fournisseur</h4>
            <div class="form-row">
                <div class="form-group form-col">
                    <label class="form-label">N° Lot Par Défaut</label>
                    <input type="text" name="lot_numero" class="form-control" value="<?= htmlspecialchars($medicament['lot_numero'] ?? '') ?>">
                </div>
                <div class="form-group form-col">
                    <label class="form-label">Date Fabrication</label>
                    <input type="date" name="date_fabrication" class="form-control" value="<?= $medicament['date_fabrication'] ?? '' ?>">
                </div>
                <div class="form-group form-col">
                    <label class="form-label">Date Expiration *</label>
                    <input type="date" name="date_expiration" id="date_expiration" class="form-control" 
                        value="<?= $medicament['date_expiration'] ?? '' ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Fournisseurs <span style="color: var(--text-muted); font-weight: 400;">(Optionnel — Maintenez CTRL/CMD pour plusieurs)</span></label>
                <select name="fournisseurs[]" id="fournisseurs" class="form-control" multiple style="height: 120px;">
                    <?php 
                    $selectedFournisseurs = isset($medicament['fournisseurs']) ? $medicament['fournisseurs'] : [];
                    foreach($fournisseurs as $f): 
                        $selected = in_array($f['id'], $selectedFournisseurs) ? 'selected' : '';
                    ?>
                        <option value="<?= $f['id'] ?>" <?= $selected ?>><?= htmlspecialchars($f['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($fournisseurs)): ?>
                    <small style="color: var(--text-muted);"><i class="fa-solid fa-info-circle"></i> Aucun fournisseur enregistré. <a href="<?= BASE_URL ?>/index.php?page=fournisseurs&action=create">Ajouter un fournisseur</a>.</small>
                <?php endif; ?>
            </div>

            <div style="margin-top: 2rem; text-align: right;">
                <a href="<?= BASE_URL ?>/index.php?page=medicaments" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
// Validation client-side : prix vente >= prix achat
document.getElementById('formMedicament').addEventListener('submit', function(e) {
    const achat = parseFloat(document.querySelector('[name="prix_achat"]').value);
    const vente = parseFloat(document.querySelector('[name="prix_vente"]').value);
    const fab   = document.querySelector('[name="date_fabrication"]').value;
    const exp   = document.getElementById('date_expiration').value;

    if (!isNaN(achat) && !isNaN(vente) && vente < achat) {
        e.preventDefault();
        alert('Le prix de vente ne peut pas être inférieur au prix d\'achat.');
        return false;
    }
    if (fab && exp && fab >= exp) {
        e.preventDefault();
        alert('La date d\'expiration doit être postérieure à la date de fabrication.');
        return false;
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

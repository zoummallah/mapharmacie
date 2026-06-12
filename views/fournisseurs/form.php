<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span><?= isset($fournisseur) ? 'Modifier Fournisseur' : 'Ajouter un Fournisseur' ?></span>
    <a href="<?= BASE_URL ?>/index.php?page=fournisseurs" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Retour</a>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body">
        <form action="" method="POST">
            <div class="form-group">
                <label class="form-label">Nom du fournisseur *</label>
                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($fournisseur['nom'] ?? '') ?>" required>
            </div>
            
            <div class="form-row">
                <div class="form-group form-col">
                    <label class="form-label">Nom du Contact</label>
                    <input type="text" name="contact_nom" class="form-control" value="<?= htmlspecialchars($fournisseur['contact_nom'] ?? '') ?>">
                </div>
                <div class="form-group form-col">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-control" value="<?= htmlspecialchars($fournisseur['telephone'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($fournisseur['email'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label">Adresse Complète</label>
                <textarea name="adresse" class="form-control" rows="3"><?= htmlspecialchars($fournisseur['adresse'] ?? '') ?></textarea>
            </div>
            
            <div style="text-align: right; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

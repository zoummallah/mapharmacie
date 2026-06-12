<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span><?= isset($categorie) ? 'Modifier Catégorie' : 'Ajouter une Catégorie' ?></span>
    <a href="<?= BASE_URL ?>/index.php?page=categories" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Retour</a>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body">
        <form action="" method="POST">
            <div class="form-group">
                <label class="form-label">Nom de la catégorie *</label>
                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($categorie['nom'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($categorie['description'] ?? '') ?></textarea>
            </div>
            
            <div style="text-align: right; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

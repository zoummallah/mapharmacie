<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span><?= isset($client) ? 'Modifier Client' : 'Ajouter un Client' ?></span>
    <a href="<?= BASE_URL ?>/index.php?page=clients" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Retour</a>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body">
        <form action="" method="POST">
            <div class="form-group">
                <label class="form-label">Nom complet *</label>
                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($client['nom'] ?? '') ?>" required>
            </div>
            
            <div class="form-row">
                <div class="form-group form-col">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-control" value="<?= htmlspecialchars($client['telephone'] ?? '') ?>">
                </div>
                <div class="form-group form-col">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($client['email'] ?? '') ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Adresse</label>
                <textarea name="adresse" class="form-control" rows="2"><?= htmlspecialchars($client['adresse'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Historique Médical / Notes</label>
                <textarea name="historique_medical" class="form-control" rows="4"><?= htmlspecialchars($client['historique_medical'] ?? '') ?></textarea>
            </div>
            
            <div style="text-align: right; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

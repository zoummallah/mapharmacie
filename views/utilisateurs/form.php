<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
$isEdit = isset($utilisateur);
$isCurrentUser = $isEdit && ((int)$utilisateur['id'] === (int)$_SESSION['user_id']);
?>

<div class="page-title">
    <span><?= $isEdit ? 'Modifier l\'Utilisateur' : 'Ajouter un Utilisateur' ?></span>
    <a href="<?= BASE_URL ?>/index.php?page=utilisateurs" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Retour
    </a>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
                <i class="fa-solid fa-triangle-exclamation" style="margin-right: 5px;"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Prénom *</label>
                    <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($_POST['prenom'] ?? $utilisateur['prenom'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nom *</label>
                    <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($_POST['nom'] ?? $utilisateur['nom'] ?? '') ?>" required>
                </div>
            </div>

            <div class="form-group" style="margin-top: 1rem;">
                <label class="form-label">Adresse Email *</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? $utilisateur['email'] ?? '') ?>" required>
            </div>

            <?php if (!$isEdit): ?>
                <div class="form-group" style="margin-top: 1rem;">
                    <label class="form-label">Mot de passe *</label>
                    <input type="password" name="mot_de_passe" class="form-control" required placeholder="Saisir le mot de passe">
                </div>
            <?php else: ?>
                <?php if ($isCurrentUser): ?>
                    <div class="form-group" style="margin-top: 1rem;">
                        <label class="form-label">Nouveau mot de passe</label>
                        <input type="password" name="mot_de_passe" class="form-control" placeholder="Laissez vide pour conserver le mot de passe actuel">
                    </div>
                <?php else: ?>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Ancien mot de passe du collaborateur</label>
                            <input type="password" name="ancien_mot_de_passe" class="form-control" placeholder="Requis pour changer son mot de passe">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nouveau mot de passe</label>
                            <input type="password" name="mot_de_passe" class="form-control" placeholder="Saisir le nouveau mot de passe">
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: <?= $isEdit ? '1fr 1fr' : '1fr' ?>; gap: 1rem; margin-top: 1rem;">
                <div class="form-group">
                    <label class="form-label">Rôle *</label>
                    <?php if ($isCurrentUser): ?>
                        <input type="hidden" name="role" value="admin">
                        <select class="form-control" disabled>
                            <option value="admin" selected>Administrateur (Votre compte)</option>
                        </select>
                        <small style="color: #64748b; font-style: italic;">Vous ne pouvez pas modifier votre propre rôle.</small>
                    <?php else: ?>
                        <select name="role" class="form-control" required>
                            <option value="pharmacien" <?= (($_POST['role'] ?? $utilisateur['role'] ?? 'pharmacien') === 'pharmacien') ? 'selected' : '' ?>>Pharmacien</option>
                            <option value="admin" <?= (($_POST['role'] ?? $utilisateur['role'] ?? '') === 'admin') ? 'selected' : '' ?>>Administrateur</option>
                        </select>
                    <?php endif; ?>
                </div>

                <?php if ($isEdit): ?>
                    <div class="form-group">
                        <label class="form-label">Statut *</label>
                        <?php if ($isCurrentUser): ?>
                            <input type="hidden" name="statut" value="1">
                            <select class="form-control" disabled>
                                <option value="1" selected>Actif (Votre compte)</option>
                            </select>
                            <small style="color: #64748b; font-style: italic;">Vous ne pouvez pas désactiver votre propre compte.</small>
                        <?php else: ?>
                            <select name="statut" class="form-control" required>
                                <option value="1" <?= (int)($_POST['statut'] ?? $utilisateur['statut'] ?? 1) === 1 ? 'selected' : '' ?>>Actif</option>
                                <option value="0" <?= (int)($_POST['statut'] ?? $utilisateur['statut'] ?? 1) === 0 ? 'selected' : '' ?>>Inactif</option>
                            </select>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div style="text-align: right; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

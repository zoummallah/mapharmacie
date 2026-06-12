<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span>Gestion des Utilisateurs</span>
    <a href="<?= BASE_URL ?>/index.php?page=utilisateurs&action=create" class="btn btn-primary">
        <i class="fa-solid fa-user-plus"></i> Nouvel Utilisateur
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th style="width: 150px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($utilisateurs)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Aucun utilisateur trouvé.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($utilisateurs as $u): ?>
                    <tr>
                        <td style="font-weight: 600;">
                            <?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?>
                            <?php if ((int)$u['id'] === (int)$_SESSION['user_id']): ?>
                                <small style="color: var(--primary-color); font-weight: normal; margin-left: 5px;">(Vous)</small>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <?php if ($u['role'] === 'admin'): ?>
                                <span class="badge badge-primary">
                                    <i class="fa-solid fa-user-shield" style="margin-right: 3px;"></i> Admin
                                </span>
                            <?php else: ?>
                                <span class="badge badge-secondary">
                                    <i class="fa-solid fa-user-doctor" style="margin-right: 3px;"></i> Pharmacien
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ((int)$u['statut'] === 1): ?>
                                <span class="badge badge-success">Actif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                <a href="<?= BASE_URL ?>/index.php?page=utilisateurs&action=edit&id=<?= $u['id'] ?>" 
                                   class="btn btn-secondary btn-icon" 
                                   title="Modifier">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                
                                <?php if ((int)$u['id'] !== (int)$_SESSION['user_id']): ?>
                                    <?php if ((int)$u['statut'] === 1): ?>
                                        <a href="<?= BASE_URL ?>/index.php?page=utilisateurs&action=delete&id=<?= $u['id'] ?>" 
                                           class="btn btn-danger btn-icon" 
                                           title="Désactiver" 
                                           onclick="return confirm('Êtes-vous sûr de vouloir désactiver cet utilisateur ?');">
                                            <i class="fa-solid fa-user-slash"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= BASE_URL ?>/index.php?page=utilisateurs&action=activate&id=<?= $u['id'] ?>" 
                                           class="btn btn-success btn-icon" 
                                           title="Activer" 
                                           onclick="return confirm('Êtes-vous sûr de vouloir réactiver cet utilisateur ?');">
                                            <i class="fa-solid fa-user-check"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-icon" style="opacity: 0.5; cursor: not-allowed;" title="Action impossible sur votre propre compte" disabled>
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span>Fournisseurs</span>
    <?php if(estAdmin()): ?>
        <a href="<?= BASE_URL ?>/index.php?page=fournisseurs&action=create" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Nouveau Fournisseur</a>
    <?php endif; ?>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nom Fournisseur</th>
                    <th>Contact</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <?php if(estAdmin()): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($fournisseurs)): ?>
                    <tr><td colspan="<?= estAdmin() ? 5 : 4 ?>" style="text-align: center;">Aucun fournisseur trouvé.</td></tr>
                <?php else: ?>
                    <?php foreach($fournisseurs as $f): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($f['nom']) ?></td>
                        <td><?= htmlspecialchars($f['contact_nom']) ?></td>
                        <td><?= htmlspecialchars($f['telephone']) ?></td>
                        <td><?= htmlspecialchars($f['email']) ?></td>
                        <?php if(estAdmin()): ?>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="<?= BASE_URL ?>/index.php?page=fournisseurs&action=edit&id=<?= $f['id'] ?>" class="btn btn-secondary btn-icon"><i class="fa-solid fa-pen"></i></a>
                                <a href="<?= BASE_URL ?>/index.php?page=fournisseurs&action=delete&id=<?= $f['id'] ?>" class="btn btn-danger btn-icon" onclick="return confirm('Voulez-vous vraiment supprimer ce fournisseur ?');"><i class="fa-solid fa-trash"></i></a>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

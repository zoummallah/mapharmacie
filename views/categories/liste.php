<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span>Catégories</span>
    <?php if(estAdmin()): ?>
        <a href="<?= BASE_URL ?>/index.php?page=categories&action=create" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Nouvelle Catégorie</a>
    <?php endif; ?>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <?php if(estAdmin()): ?>
                        <th style="width: 150px;">Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($categories)): ?>
                    <tr><td colspan="<?= estAdmin() ? 3 : 2 ?>" style="text-align: center;">Aucune catégorie trouvée.</td></tr>
                <?php else: ?>
                    <?php foreach($categories as $c): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($c['nom']) ?></td>
                        <td><?= htmlspecialchars($c['description']) ?></td>
                        <?php if(estAdmin()): ?>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="<?= BASE_URL ?>/index.php?page=categories&action=edit&id=<?= $c['id'] ?>" class="btn btn-secondary btn-icon"><i class="fa-solid fa-pen"></i></a>
                                <a href="<?= BASE_URL ?>/index.php?page=categories&action=delete&id=<?= $c['id'] ?>" class="btn btn-danger btn-icon" onclick="return confirm('Voulez-vous vraiment supprimer cette catégorie ?');"><i class="fa-solid fa-trash"></i></a>
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

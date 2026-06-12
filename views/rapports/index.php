<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span>Rapports et Analyses</span>
</div>

<div class="stats-grid">
    <a href="<?= BASE_URL ?>/index.php?page=rapports&action=stock_alerte" class="stat-card" style="text-decoration: none;">
        <div class="stat-icon" style="color: var(--warning); background: rgba(245, 158, 11, 0.1);"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div class="stat-info">
            <h3 style="color: var(--dark-color);">Alertes Stock Bas</h3>
            <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.5rem;">Médicaments sous le seuil</div>
        </div>
    </a>
    
    <a href="<?= BASE_URL ?>/index.php?page=rapports&action=expiration" class="stat-card" style="text-decoration: none;">
        <div class="stat-icon" style="color: var(--danger); background: rgba(239, 68, 68, 0.1);"><i class="fa-solid fa-clock"></i></div>
        <div class="stat-info">
            <h3 style="color: var(--dark-color);">Expirations Proches</h3>
            <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.5rem;">Dans les 30 jours</div>
        </div>
    </a>

    <a href="<?= BASE_URL ?>/index.php?page=rapports&action=ventes_periode" class="stat-card" style="text-decoration: none;">
        <div class="stat-icon" style="color: var(--secondary-color); background: rgba(59, 130, 246, 0.1);"><i class="fa-solid fa-chart-bar"></i></div>
        <div class="stat-info">
            <h3 style="color: var(--dark-color);">Ventes par Période</h3>
            <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.5rem;">Analyse du CA</div>
        </div>
    </a>

    <a href="<?= BASE_URL ?>/index.php?page=rapports&action=inventaire" class="stat-card" style="text-decoration: none;">
        <div class="stat-icon" style="color: var(--primary-color); background: rgba(13, 148, 136, 0.1);"><i class="fa-solid fa-boxes-stacked"></i></div>
        <div class="stat-info">
            <h3 style="color: var(--dark-color);">Inventaire Complet</h3>
            <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.5rem;">Valorisation du stock</div>
        </div>
    </a>

    <?php if(estAdmin()): ?>
    <a href="<?= BASE_URL ?>/index.php?page=rapports&action=marge" class="stat-card" style="text-decoration: none;">
        <div class="stat-icon" style="color: var(--success); background: rgba(16, 185, 129, 0.1);"><i class="fa-solid fa-chart-line"></i></div>
        <div class="stat-info">
            <h3 style="color: var(--dark-color);">Marge Bénéficiaire</h3>
            <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.5rem;">Rentabilité</div>
        </div>
    </a>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

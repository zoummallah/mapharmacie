<?php
// views/layouts/sidebar.php
$currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <h2><i class="fa-solid fa-staff-snake"></i> PharmaStock</h2>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="<?= BASE_URL ?>/index.php?page=dashboard" class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-line"></i> Tableau de bord
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/index.php?page=ventes&action=pos" class="<?= $currentPage === 'ventes' && isset($_GET['action']) && $_GET['action'] === 'pos' ? 'active' : '' ?>">
                <i class="fa-solid fa-cash-register"></i> Point de Vente
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/index.php?page=ventes&action=historique" class="<?= $currentPage === 'ventes' && isset($_GET['action']) && $_GET['action'] === 'historique' ? 'active' : '' ?>">
                <i class="fa-solid fa-receipt"></i> Historique Ventes
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/index.php?page=medicaments" class="<?= $currentPage === 'medicaments' ? 'active' : '' ?>">
                <i class="fa-solid fa-pills"></i> Médicaments
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/index.php?page=lots" class="<?= $currentPage === 'lots' && (!isset($_GET['action']) || $_GET['action'] !== 'perimes') ? 'active' : '' ?>">
                <i class="fa-solid fa-boxes-stacked"></i> Stock & Lots
            </a>
        </li>
        <?php
        if (class_exists('Lot')) {
            $sidebarLotModel = new Lot();
            $sidebarPerimesCount = count($sidebarLotModel->getLotsPerimes());
            if ($sidebarPerimesCount > 0):
        ?>
        <li>
            <a href="<?= BASE_URL ?>/index.php?page=lots&action=perimes" class="<?= $currentPage === 'lots' && isset($_GET['action']) && $_GET['action'] === 'perimes' ? 'active' : '' ?>" style="display: flex; justify-content: space-between; align-items: center;">
                <span>Produits Périmés</span>
                <span class="badge badge-danger" style="font-size: 0.7rem; padding: 2px 6px; line-height: 1; border-radius: 10px; margin-left: 5px;"><?= $sidebarPerimesCount ?></span>
            </a>
        </li>
        <?php 
            endif;
        }
        ?>
        <li>
            <a href="<?= BASE_URL ?>/index.php?page=clients" class="<?= $currentPage === 'clients' ? 'active' : '' ?>">
                <i class="fa-solid fa-users"></i> Clients
            </a>
        </li>
        
        <?php if(estAdmin()): ?>
            <li style="margin-top: 1rem; padding-left: 1.5rem; font-size: 0.8rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Administration</li>
            <li>
                <a href="<?= BASE_URL ?>/index.php?page=categories" class="<?= $currentPage === 'categories' ? 'active' : '' ?>">
                    <i class="fa-solid fa-tags"></i> Catégories
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/index.php?page=fournisseurs" class="<?= $currentPage === 'fournisseurs' ? 'active' : '' ?>">
                    <i class="fa-solid fa-truck"></i> Fournisseurs
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/index.php?page=rapports" class="<?= $currentPage === 'rapports' ? 'active' : '' ?>">
                    <i class="fa-solid fa-chart-pie"></i> Rapports
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/index.php?page=audit" class="<?= $currentPage === 'audit' ? 'active' : '' ?>">
                    <i class="fa-solid fa-clipboard-list"></i> Journal d'Audit
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/index.php?page=utilisateurs" class="<?= $currentPage === 'utilisateurs' ? 'active' : '' ?>">
                    <i class="fa-solid fa-users-gear"></i> Utilisateurs
                </a>
            </li>
        <?php endif; ?>
    </ul>
</aside>

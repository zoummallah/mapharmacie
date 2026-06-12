<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span>Tableau de Bord</span>
    <span style="font-size: 1rem; color: var(--text-muted); font-weight: normal;"><?= date('d/m/Y') ?></span>
</div>

<?php if(estAdmin()): ?>
<!-- PANEL FINANCIER GLOBAL -->
<div class="card" style="margin-bottom: 2rem; border-left: 4px solid var(--warning); background: linear-gradient(145deg, #ffffff, #f8fafc);">
    <div class="card-header" style="border-bottom: none; padding-bottom: 0;">
        <span style="font-size: 1.2rem; color: var(--dark-color);"><i class="fa-solid fa-vault" style="color: var(--warning);"></i> Synthèse Financière du Stock</span>
    </div>
    <div class="card-body" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
        
        <div style="padding: 1rem; background: rgba(59, 130, 246, 0.05); border-radius: var(--radius-md); border: 1px solid rgba(59, 130, 246, 0.1);">
            <div style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;"><i class="fa-solid fa-box-open"></i> Valeur d'Achat (Investissement)</div>
            <div style="font-size: 1.8rem; font-weight: 700; color: var(--dark-color);"><?= formatMontant($statistiques['valeur_stock_achat']) ?></div>
        </div>

        <div style="padding: 1rem; background: rgba(16, 185, 129, 0.05); border-radius: var(--radius-md); border: 1px solid rgba(16, 185, 129, 0.1);">
            <div style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;"><i class="fa-solid fa-hand-holding-dollar"></i> Valeur de Vente (CA Potentiel)</div>
            <div style="font-size: 1.8rem; font-weight: 700; color: var(--success);"><?= formatMontant($statistiques['valeur_stock_vente']) ?></div>
        </div>

        <div style="padding: 1rem; background: rgba(245, 158, 11, 0.05); border-radius: var(--radius-md); border: 1px solid rgba(245, 158, 11, 0.1);">
            <div style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;"><i class="fa-solid fa-chart-pie"></i> Bénéfice Brut Projeté</div>
            <div style="font-size: 1.8rem; font-weight: 700; color: var(--warning);"><?= formatMontant($statistiques['benefice_potentiel']) ?></div>
        </div>

    </div>
</div>
<?php endif; ?>

<div class="stats-grid">
    <?php if(estAdmin()): ?>
    <div class="stat-card">
        <div class="stat-icon"><i class="fa-solid fa-money-bill-wave"></i></div>
        <div class="stat-info">
            <h3>CA du jour</h3>
            <div class="value"><?= formatMontant($statistiques['ca_jour']) ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="color: var(--secondary-color); background: rgba(59, 130, 246, 0.1);"><i class="fa-solid fa-receipt"></i></div>
        <div class="stat-info">
            <h3>Ventes aujourd'hui</h3>
            <div class="value"><?= $statistiques['ventes_jour'] ?></div>
        </div>
    </div>
    <?php endif; ?>

    <div class="stat-card">
        <div class="stat-icon" style="color: var(--success); background: rgba(16, 185, 129, 0.1);"><i class="fa-solid fa-pills"></i></div>
        <div class="stat-info">
            <h3>Médicaments Actifs</h3>
            <div class="value"><?= $statistiques['medicaments_actifs'] ?></div>
        </div>
    </div>
    
    <?php if(estAdmin()): ?>
    <div class="stat-card">
        <div class="stat-icon" style="color: var(--warning); background: rgba(245, 158, 11, 0.1);"><i class="fa-solid fa-users"></i></div>
        <div class="stat-info">
            <h3>Clients Enregistrés</h3>
            <div class="value"><?= $statistiques['clients_total'] ?></div>
        </div>
    </div>
    <?php else: ?>
    <div class="stat-card">
        <div class="stat-icon" style="color: var(--warning); background: rgba(245, 158, 11, 0.1);"><i class="fa-solid fa-file-prescription"></i></div>
        <div class="stat-info">
            <h3>Ordonnances (Total)</h3>
            <div class="value"><?= $statistiques['ordonnances_total'] ?? 0 ?></div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php if(estAdmin()): ?>
<div class="dashboard-charts">
    <!-- Graphique CA Principal -->
    <div class="card" style="margin-bottom: 2rem;">
        <div class="card-header">
            <span><i class="fa-solid fa-chart-line"></i> Évolution du Chiffre d'Affaires (7 derniers jours)</span>
        </div>
        <div class="card-body">
            <canvas id="caChart" height="80"
                data-type="line"
                data-labels='<?= htmlspecialchars(json_encode($chart_labels), ENT_QUOTES, 'UTF-8') ?>'
                data-values='<?= htmlspecialchars(json_encode(array_values($chart_ca)), ENT_QUOTES, 'UTF-8') ?>'
                data-title="Chiffre d'Affaires (FCFA)">
            </canvas>
        </div>
    </div>

    <!-- Grille Secondaire pour les autres stats -->
    <div class="charts-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
        <!-- Top Médicaments -->
        <div class="card">
            <div class="card-header">
                <span><i class="fa-solid fa-ranking-star"></i> Top 5 Médicaments (30j)</span>
            </div>
            <div class="card-body" style="position: relative; height: 300px; display: flex; justify-content: center;">
                <canvas id="topMedChart"
                    data-type="doughnut"
                    data-labels='<?= htmlspecialchars(json_encode(array_column($top_medicaments, 'nom')), ENT_QUOTES, 'UTF-8') ?>'
                    data-values='<?= htmlspecialchars(json_encode(array_column($top_medicaments, 'total_vendu')), ENT_QUOTES, 'UTF-8') ?>'>
                </canvas>
            </div>
        </div>

        <!-- Ventes par Pharmacien -->
        <div class="card">
            <div class="card-header">
                <span><i class="fa-solid fa-user-doctor"></i> Opérations par Pharmacien (30j)</span>
            </div>
            <div class="card-body" style="position: relative; height: 300px;">
                <canvas id="pharmaSalesChart"
                    data-type="bar"
                    data-labels='<?= htmlspecialchars(json_encode(array_column($ventes_pharmacien, 'nom')), ENT_QUOTES, 'UTF-8') ?>'
                    data-values='<?= htmlspecialchars(json_encode(array_column($ventes_pharmacien, 'total_transactions')), ENT_QUOTES, 'UTF-8') ?>'>
                </canvas>
            </div>
        </div>
        
        <!-- Répartition par catégorie -->
        <div class="card">
            <div class="card-header">
                <span><i class="fa-solid fa-layer-group"></i> Stocks par Catégorie</span>
            </div>
            <div class="card-body" style="position: relative; height: 300px; display: flex; justify-content: center;">
                <canvas id="stockCatChart"
                    data-type="pie"
                    data-labels='<?= htmlspecialchars(json_encode(array_column($stock_categorie, 'nom')), ENT_QUOTES, 'UTF-8') ?>'
                    data-values='<?= htmlspecialchars(json_encode(array_column($stock_categorie, 'total_stock')), ENT_QUOTES, 'UTF-8') ?>'>
                </canvas>
            </div>
        </div>

        <!-- Mouvements Récents -->
        <div class="card">
            <div class="card-header">
                <span><i class="fa-solid fa-right-left"></i> Activités Récentes</span>
            </div>
            <div class="card-body" style="padding: 0; overflow-y: auto; max-height: 300px;">
                <?php if(empty($mouvements_recents)): ?>
                    <div style="padding: 1.5rem; text-align: center; color: var(--text-muted);">
                        Aucune activité récente.
                    </div>
                <?php else: ?>
                    <table class="table">
                        <thead><tr><th>Date</th><th>Type</th><th>Médicament</th><th>Qté</th><th>Par</th></tr></thead>
                        <tbody>
                            <?php foreach($mouvements_recents as $mouv): ?>
                            <tr>
                                <td style="font-size: 0.85em; color: var(--text-muted);"><?= formatDate($mouv['date_mouvement'], 'd/m H:i') ?></td>
                                <td>
                                    <?php if($mouv['source'] === 'vente'): ?>
                                        <span class="badge badge-primary"><i class="fa-solid fa-cart-shopping"></i> Vente</span>
                                    <?php elseif($mouv['type_mouvement'] === 'entree'): ?>
                                        <span class="badge badge-success"><i class="fa-solid fa-arrow-down"></i> Entrée</span>
                                    <?php elseif($mouv['type_mouvement'] === 'retour_fournisseur'): ?>
                                        <span class="badge badge-warning"><i class="fa-solid fa-rotate-left"></i> Retour Frn</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger"><i class="fa-solid fa-arrow-up"></i> Sortie</span>
                                    <?php endif; ?>
                                </td>
                                <td style="font-weight: 500;"><?= htmlspecialchars($mouv['medicament']) ?></td>
                                <td><strong><?= $mouv['quantite'] ?></strong></td>
                                <td style="font-size: 0.85em; color: var(--text-muted);"><?= htmlspecialchars($mouv['utilisateur']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<!-- VUE PHARMACIEN : MEDICAL UNIQUEMENT -->
<div class="dashboard-charts charts-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
    <div class="card">
        <div class="card-header">
            <span><i class="fa-solid fa-boxes-packing"></i> Entrées en Stock (7 derniers jours)</span>
        </div>
        <div class="card-body" style="position: relative; height: 300px;">
            <canvas id="pharmaEntreesChart"
                data-type="bar"
                data-labels='<?= htmlspecialchars(json_encode($chart_labels), ENT_QUOTES, 'UTF-8') ?>'
                data-values='<?= htmlspecialchars(json_encode(array_values($chart_entrees_stock)), ENT_QUOTES, 'UTF-8') ?>'
                data-title="Quantité entrée">
            </canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span><i class="fa-solid fa-layer-group"></i> Répartition des Stocks par Catégorie</span>
        </div>
        <div class="card-body" style="position: relative; height: 300px; display: flex; justify-content: center;">
            <canvas id="pharmaCatChart"
                data-type="doughnut"
                data-labels='<?= htmlspecialchars(json_encode(array_column($pharma_stock_cat, 'nom')), ENT_QUOTES, 'UTF-8') ?>'
                data-values='<?= htmlspecialchars(json_encode(array_column($pharma_stock_cat, 'total_meds')), ENT_QUOTES, 'UTF-8') ?>'>
            </canvas>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">
        <span><i class="fa-solid fa-file-prescription"></i> Ordonnances Récentes</span>
    </div>
    <div class="card-body" style="padding: 0; overflow-y: auto; max-height: 300px;">
        <?php if(empty($ordonnances_recentes)): ?>
            <div style="padding: 1.5rem; text-align: center; color: var(--text-muted);">Aucune ordonnance récente.</div>
        <?php else: ?>
            <table class="table">
                <thead><tr><th>N° Ordonnance</th><th>Date</th><th>Client</th><th>Médecin</th></tr></thead>
                <tbody>
                    <?php foreach($ordonnances_recentes as $ord): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($ord['numero_ordonnance']) ?></strong></td>
                        <td><?= formatDate($ord['date_emission'], 'd/m/Y') ?></td>
                        <td><?= htmlspecialchars($ord['client_nom']) ?></td>
                        <td style="color: var(--text-muted);"><?= htmlspecialchars($ord['medecin_prescripteur']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<div class="form-row">
    <!-- Alertes Stock Bas -->
    <div class="form-col">
        <div class="card">
            <div class="card-header">
                <span><i class="fa-solid fa-triangle-exclamation" style="color: var(--warning);"></i> Alertes Stock Bas</span>
                <a href="<?= BASE_URL ?>/index.php?page=rapports&action=stock_alerte" class="btn btn-secondary btn-sm">Voir tout</a>
            </div>
            <div class="card-body" style="padding: 0;">
                <?php if (empty($alertes_stock)): ?>
                    <div style="padding: 1.5rem; text-align: center; color: var(--text-muted);">
                        Aucun médicament en stock bas.
                    </div>
                <?php else: ?>
                    <table class="table">
                        <thead><tr><th>Médicament</th><th>Stock</th><th>Min.</th></tr></thead>
                        <tbody>
                            <?php foreach($alertes_stock as $a): ?>
                            <tr>
                                <td><?= htmlspecialchars($a['nom']) ?></td>
                                <td><span class="badge badge-danger"><?= $a['quantite_stock'] ?></span></td>
                                <td><?= $a['stock_minimum'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Alertes Expiration -->
    <div class="form-col">
        <div class="card" style="border-left: 3px solid var(--danger);">
            <div class="card-header">
                <span><i class="fa-solid fa-triangle-exclamation" style="color: var(--danger);"></i> Expirations &amp; Périmés</span>
                <a href="<?= BASE_URL ?>/index.php?page=rapports&action=expiration" class="btn btn-secondary btn-sm">Voir tout</a>
            </div>
            <div class="card-body" style="padding: 0;">
                <?php if (empty($alertes_expiration)): ?>
                    <div style="padding: 1.5rem; text-align: center; color: var(--text-muted);">
                        <i class="fa-solid fa-circle-check" style="color: var(--success); font-size: 1.5rem; display: block; margin-bottom: 0.5rem;"></i>
                        Aucun médicament périmé ou expirant bientôt.
                    </div>
                <?php else: ?>
                    <table class="table">
                        <thead><tr><th>Médicament</th><th>Date exp.</th><th>Statut</th></tr></thead>
                        <tbody>
                            <?php foreach($alertes_expiration as $a): ?>
                            <tr style="<?= $a['statut_exp'] === 'expire' ? 'background: rgba(239,68,68,0.04);' : '' ?>">
                                <td style="font-weight: 500;"><?= htmlspecialchars($a['nom']) ?> <span style="font-size: 0.8rem; font-weight: 400; color: var(--text-muted);">(Lot: <?= htmlspecialchars($a['lot_numero']) ?>)</span></td>
                                <td>
                                    <span class="badge <?= $a['statut_exp'] === 'expire' ? 'badge-danger' : 'badge-warning' ?>">
                                        <?= formatDate($a['date_expiration'], 'd/m/Y') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($a['statut_exp'] === 'expire'): ?>
                                        <span style="font-size: 0.75rem; font-weight: 600; color: var(--danger);">Périmé</span>
                                    <?php else: ?>
                                        <span style="font-size: 0.75rem; font-weight: 600; color: var(--warning);"><i class="fa-solid fa-clock"></i> Bientôt</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

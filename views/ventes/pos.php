<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span>Point de Vente (POS)</span>
</div>

<div class="pos-container">
    <!-- Section Liste Médicaments / Lots (Premium) -->
    <div class="pos-products-card">
        <div class="products-header">
            <div class="products-header-left">
                <div class="header-icon-wrap">
                    <i class="fa-solid fa-pills animate-pulse"></i>
                </div>
                <span>Médicaments en Stock</span>
            </div>
            <div class="search-wrap-premium">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="search-pos" placeholder="Rechercher par médicament, lot...">
            </div>
        </div>
        
        <div class="table-responsive pos-table-scroll">
            <table class="table-premium" id="table-pos">
                <thead>
                    <tr>
                        <th>Médicament</th>
                        <th>Lot</th>
                        <th>Date d'expiration</th>
                        <th>Prix Unitaire</th>
                        <th>Stock dispos</th>
                        <th>Quantité à ajouter</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lots as $lot): ?>
                    <?php 
                        $stock = (int)$lot['quantite'];
                        if ($stock > 100) {
                            $stockClass = 'stock-badge-high';
                        } elseif ($stock > 20) {
                            $stockClass = 'stock-badge-medium';
                        } else {
                            $stockClass = 'stock-badge-low';
                        }

                        // Détection de péremption proche (moins de 6 mois)
                        $expiryTime = strtotime($lot['date_expiration']);
                        $warningTime = strtotime('+6 months');
                        $isExpiringSoon = ($expiryTime < $warningTime);
                    ?>
                    <tr class="product-row-premium">
                        <td class="product-name-col">
                            <span class="product-bullet"></span>
                            <?= htmlspecialchars($lot['medicament_nom']) ?>
                        </td>
                        <td class="product-lot-col">
                            <span class="lot-pill"><?= htmlspecialchars($lot['numero_lot']) ?></span>
                        </td>
                        <td class="product-date-col <?= $isExpiringSoon ? 'date-critical' : '' ?>">
                            <i class="fa-solid <?= $isExpiringSoon ? 'fa-triangle-exclamation' : 'fa-calendar-days' ?>"></i>
                            <?= formatDate($lot['date_expiration'], 'd/m/Y') ?>
                        </td>
                        <td class="product-price-col">
                            <?= formatMontant($lot['prix_vente']) ?>
                        </td>
                        <td>
                            <span class="stock-pill <?= $stockClass ?>"><?= $stock ?></span>
                        </td>
                        <td class="product-action-col">
                            <form action="<?= BASE_URL ?>/index.php?page=ventes&action=add_panier" method="POST" class="add-to-cart-form">
                                <input type="hidden" name="id_lot" value="<?= $lot['id'] ?>">
                                
                                <div class="qty-selector-premium">
                                    <button type="button" class="btn-qty-minus" onclick="decrementQty(this)"><i class="fa-solid fa-minus"></i></button>
                                    <input type="number" name="quantite" value="1" min="1" max="<?= $lot['quantite'] ?>" required readonly>
                                    <button type="button" class="btn-qty-plus" onclick="incrementQty(this, <?= $lot['quantite'] ?>)"><i class="fa-solid fa-plus"></i></button>
                                </div>

                                <button type="submit" class="btn-add-premium" title="Ajouter au panier">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section Panier -->
    <div class="pos-cart">
        <!-- Header Panier Premium -->
        <div class="cart-header-premium">
            <div class="cart-header-left">
                <div class="cart-icon-wrap animate-pulse">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <?php if(!empty($_SESSION['panier'])): ?>
                        <span class="cart-badge-count"><?= count($_SESSION['panier']) ?></span>
                    <?php endif; ?>
                </div>
                <span>Panier d'achat</span>
            </div>
            <?php if(!empty($_SESSION['panier'])): ?>
                <a href="<?= BASE_URL ?>/index.php?page=ventes&action=clear_panier" class="btn-clear-premium" title="Vider le panier"><i class="fa-solid fa-trash-can"></i></a>
            <?php endif; ?>
        </div>
        
        <div class="cart-items">
            <?php 
            $total = 0;
            if(empty($_SESSION['panier'])): ?>
                <div class="cart-empty-state">
                    <div class="empty-icon-wrap">
                        <i class="fa-solid fa-basket-shopping"></i>
                    </div>
                    <p>Le panier est vide</p>
                    <span>Sélectionnez des médicaments pour démarrer la vente.</span>
                </div>
            <?php else: ?>
                <?php foreach($_SESSION['panier'] as $id => $item): 
                    $sous_total = $item['quantite'] * $item['prix_unitaire'];
                    $total += $sous_total;
                ?>
                <div class="cart-item-premium">
                    <div class="cart-item-info">
                        <span class="item-name"><?= htmlspecialchars($item['nom']) ?></span>
                        <div class="item-metadata">
                            <span class="metadata-badge"><i class="fa-solid fa-barcode"></i> Lot: <?= htmlspecialchars($item['numero_lot']) ?></span>
                            <span class="metadata-badge"><i class="fa-solid fa-tag"></i> <?= formatMontant($item['prix_unitaire']) ?></span>
                        </div>
                    </div>
                    
                    <div class="cart-item-actions">
                        <div class="qty-badge">x<?= $item['quantite'] ?></div>
                        <div class="item-subtotal"><?= formatMontant($sous_total) ?></div>
                        <a href="<?= BASE_URL ?>/index.php?page=ventes&action=remove_panier&id=<?= $id ?>" class="btn-remove-item" title="Supprimer de la vente"><i class="fa-solid fa-xmark"></i></a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="cart-summary-premium">
            <div class="cart-total-premium">
                <span class="total-label">Net à payer</span>
                <span class="total-amount"><?= formatMontant($total) ?></span>
            </div>

            <form action="<?= BASE_URL ?>/index.php?page=ventes&action=valider" method="POST" enctype="multipart/form-data">
                
                <!-- Choix du Client -->
                <div class="form-group-premium">
                    <label class="form-label-premium">Client bénéficiaire</label>
                    <div class="radio-pills">
                        <label class="radio-pill-label">
                            <input type="radio" name="type_client" value="anonyme" checked>
                            <span class="pill-btn"><i class="fa-solid fa-user-slash"></i> Passage</span>
                        </label>
                        <label class="radio-pill-label">
                            <input type="radio" name="type_client" value="existant">
                            <span class="pill-btn"><i class="fa-solid fa-address-book"></i> Existant</span>
                        </label>
                        <label class="radio-pill-label">
                            <input type="radio" name="type_client" value="nouveau">
                            <span class="pill-btn"><i class="fa-solid fa-user-plus"></i> Nouveau</span>
                        </label>
                    </div>
                    
                    <div id="bloc-client-existant" style="display: none; margin-top: 1rem;">
                        <select name="id_client" class="form-control-premium">
                            <option value="">-- Sélectionner un client --</option>
                            <?php foreach($clients as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nom'] . ' - ' . $c['telephone']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div id="bloc-client-nouveau" style="display: none; margin-top: 1rem; border: 1px dashed rgba(13, 148, 136, 0.3); padding: 1rem; border-radius: var(--radius-lg); background: rgba(255,255,255,0.4);">
                        <input type="text" name="nouveau_client_nom" class="form-control-premium" placeholder="Nom et Prénom (*)" style="margin-bottom: 0.75rem;">
                        <input type="text" name="nouveau_client_telephone" class="form-control-premium" placeholder="Numéro de téléphone">
                        <small style="color: var(--text-muted); display: block; margin-top: 0.5rem;"><i class="fa-solid fa-info-circle"></i> Le client sera automatiquement enregistré en base.</small>
                    </div>
                </div>
                
                <!-- Ordonnance -->
                <div class="prescription-card-premium">
                    <label class="form-label-premium"><i class="fa-solid fa-file-medical"></i> Justificatif médical</label>
                    <div class="radio-pills">
                        <label class="radio-pill-label">
                            <input type="radio" name="type_ordonnance" value="sans" checked>
                            <span class="pill-btn"><i class="fa-solid fa-ban"></i> Sans ordonnance</span>
                        </label>
                        <label class="radio-pill-label">
                            <input type="radio" name="type_ordonnance" value="avec">
                            <span class="pill-btn"><i class="fa-solid fa-circle-check"></i> Avec ordonnance</span>
                        </label>
                    </div>
                    
                    <div id="bloc-ordonnance" style="display: none; margin-top: 1rem;">
                        <div class="form-group" style="margin-bottom: 0.75rem;">
                            <input type="text" name="numero_ordonnance" class="form-control-premium" placeholder="Numéro ordonnance (*)">
                        </div>
                        <div class="form-group" style="margin-bottom: 0.75rem;">
                            <input type="text" name="medecin_prescripteur" class="form-control-premium" placeholder="Nom du médecin prescripteur (*)">
                        </div>
                        <div class="form-row" style="margin-bottom: 0.75rem;">
                            <div class="form-col">
                                <label style="font-size:0.8rem; color:var(--text-muted); display: block; margin-bottom: 0.25rem;">Date d'émission (*)</label>
                                <input type="date" name="date_emission" class="form-control-premium">
                            </div>
                            <div class="form-col">
                                <label style="font-size:0.8rem; color:var(--text-muted); display: block; margin-bottom: 0.25rem;">Date de validité</label>
                                <input type="date" name="date_validite" class="form-control-premium">
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 0.75rem;">
                            <label class="custom-file-upload">
                                <input type="file" name="fichier_ordonnance" accept=".pdf,image/*">
                                <i class="fa-solid fa-cloud-arrow-up"></i> Numériser l'ordonnance (Image/PDF)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Toggle Facture Premium -->
                <div class="facture-toggle-premium">
                    <label class="toggle-container">
                        <input type="checkbox" name="generer_facture" value="1" checked>
                        <span class="toggle-slider"></span>
                    </label>
                    <div class="toggle-info">
                        <span class="toggle-title"><i class="fa-solid fa-file-pdf" style="color: #ef4444;"></i> Facture PDF (A4)</span>
                        <span class="toggle-desc">Générer et télécharger après validation</span>
                    </div>
                </div>

                <!-- Bouton Valider Premium -->
                <button type="submit" class="btn-validate-premium" <?= empty($_SESSION['panier']) ? 'disabled' : '' ?>>
                    <span class="btn-text">Enregistrer la vente</span>
                    <span class="btn-price-tag"><?= formatMontant($total) ?> <i class="fa-solid fa-circle-arrow-right"></i></span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('search-pos').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#table-pos tbody tr');
    
    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});

// Logique pour le choix du client
document.querySelectorAll('input[name="type_client"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('bloc-client-existant').style.display = (this.value === 'existant') ? 'block' : 'none';
        document.getElementById('bloc-client-nouveau').style.display = (this.value === 'nouveau') ? 'block' : 'none';
        
        if(this.value === 'nouveau') {
            document.querySelector('input[name="nouveau_client_nom"]').required = true;
        } else {
            document.querySelector('input[name="nouveau_client_nom"]').required = false;
        }
    });
});

// Logique pour le choix de l'ordonnance
document.querySelectorAll('input[name="type_ordonnance"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('bloc-ordonnance').style.display = (this.value === 'avec') ? 'block' : 'none';
        
        const isRequired = (this.value === 'avec');
        document.querySelector('input[name="numero_ordonnance"]').required = isRequired;
        document.querySelector('input[name="medecin_prescripteur"]').required = isRequired;
        document.querySelector('input[name="date_emission"]').required = isRequired;
    });
});

// Incrémentation / décrémentation des quantités POS
function decrementQty(btn) {
    let input = btn.nextElementSibling;
    let val = parseInt(input.value) || 1;
    if (val > 1) {
        input.value = val - 1;
    }
}

function incrementQty(btn, max) {
    let input = btn.previousElementSibling;
    let val = parseInt(input.value) || 1;
    if (val < max) {
        input.value = val + 1;
    }
}
</script>

<?php if(isset($_GET['download_pdf'])): ?>
<script>
    window.addEventListener('DOMContentLoaded', function() {
        window.open('<?= BASE_URL ?>/index.php?page=ventes&action=facture&id=<?= (int)$_GET['download_pdf'] ?>', '_blank');
    });
</script>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

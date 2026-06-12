<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span><i class="fa-solid fa-arrow-down" style="color: var(--success);"></i> Enregistrer une Entrée de Stock</span>
    <a href="<?= BASE_URL ?>/index.php?page=lots" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Retour au Stock</a>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-body">
        <form action="<?= BASE_URL ?>/index.php?page=lots&action=entree" method="POST" id="formEntree">
            <div class="form-group">
                <label class="form-label">Médicament *</label>
                <select name="id_medicament" id="id_medicament" class="form-control" required>
                    <option value="">-- Sélectionner un médicament --</option>
                    <?php foreach($medicaments as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nom']) ?> (Stock actuel: <?= $m['quantite_stock'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-row">
                <div class="form-group form-col">
                    <label class="form-label">Quantité *</label>
                    <input type="number" name="quantite" class="form-control" min="1" required>
                </div>
                <div class="form-group form-col">
                    <label class="form-label">Motif *</label>
                    <input type="text" name="motif" class="form-control" placeholder="ex: Réception commande FRN-123" required>
                </div>
            </div>

            <div style="margin: 1.5rem 0; padding: 1.5rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: rgba(248, 250, 252, 0.5);">
                <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                    <label style="font-weight: 500; cursor: pointer;">
                        <input type="radio" name="lot_option" value="existant" checked onchange="toggleLotOption()"> Ajouter à un lot existant
                    </label>
                    <label style="font-weight: 500; cursor: pointer;">
                        <input type="radio" name="lot_option" value="nouveau" onchange="toggleLotOption()"> Créer un nouveau lot
                    </label>
                </div>
                
                <input type="hidden" name="nouveau_lot" id="nouveau_lot" value="0">

                <div id="div_lot_existant">
                    <div class="form-group">
                        <label class="form-label">Sélectionner le lot *</label>
                        <select name="id_lot" class="form-control" required>
                            <option value="">-- Sélectionner le lot existant --</option>
                            <?php foreach($lots as $l): ?>
                                <option value="<?= $l['id'] ?>" data-medicament="<?= $l['id_medicament'] ?>"><?= htmlspecialchars($l['medicament_nom'] . ' - Lot: ' . $l['numero_lot']) ?> (Exp: <?= formatDate($l['date_expiration'], 'd/m/Y') ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div id="div_lot_nouveau" style="display: none;">
                    <div class="form-row">
                        <div class="form-group form-col">
                            <label class="form-label">Numéro de Lot *</label>
                            <input type="text" name="numero_lot" id="numero_lot_input" class="form-control">
                        </div>
                        <div class="form-group form-col">
                            <label class="form-label">Date Expiration *</label>
                            <input type="date" name="date_expiration" id="date_expiration_input" class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group form-col">
                            <label class="form-label">Prix Achat *</label>
                            <input type="number" step="0.01" name="prix_achat" id="prix_achat_input" class="form-control">
                        </div>
                        <div class="form-group form-col">
                            <label class="form-label">Prix Vente *</label>
                            <input type="number" step="0.01" name="prix_vente" id="prix_vente_input" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date Fabrication (Optionnel)</label>
                        <input type="date" name="date_fabrication" class="form-control">
                    </div>
                </div>
            </div>

            <div style="text-align: right;">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-save"></i> Enregistrer l'entrée</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleLotOption() {
    const isNew = document.querySelector('input[name="lot_option"]:checked').value === 'nouveau';
    document.getElementById('div_lot_existant').style.display = isNew ? 'none' : 'block';
    document.getElementById('div_lot_nouveau').style.display = isNew ? 'block' : 'none';
    document.getElementById('nouveau_lot').value = isNew ? '1' : '0';
    
    // Required fields toggle
    document.getElementById('numero_lot_input').required = isNew;
    document.getElementById('date_expiration_input').required = isNew;
    document.getElementById('prix_achat_input').required = isNew;
    document.getElementById('prix_vente_input').required = isNew;
    document.querySelector('select[name="id_lot"]').required = !isNew;
}

// Filtrer dynamiquement les lots en fonction du médicament sélectionné
document.getElementById('id_medicament').addEventListener('change', function() {
    const medId = this.value;
    const lotSelect = document.querySelector('select[name="id_lot"]');
    const options = lotSelect.querySelectorAll('option');
    
    lotSelect.value = ''; // Reset selection
    
    options.forEach(opt => {
        if(opt.value === '') return; // Garder l'option par défaut
        if(opt.getAttribute('data-medicament') === medId) {
            opt.style.display = 'block';
        } else {
            opt.style.display = 'none';
        }
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

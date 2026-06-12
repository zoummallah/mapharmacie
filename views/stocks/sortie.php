<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-title">
    <span><i class="fa-solid fa-arrow-up" style="color: var(--warning);"></i> Enregistrer une Sortie de Stock</span>
    <a href="<?= BASE_URL ?>/index.php?page=lots" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Retour au Stock</a>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-body">
        <form action="<?= BASE_URL ?>/index.php?page=lots&action=sortie" method="POST">
            
            <div class="form-group" style="margin-bottom: 2rem;">
                <label class="form-label">Type de Sortie *</label>
                <div style="display: flex; gap: 1.5rem;">
                    <label style="cursor: pointer; display: flex; align-items: center; gap: 0.5rem; padding: 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); flex: 1;">
                        <input type="radio" name="type" value="sortie" checked>
                        <div>
                            <div style="font-weight: 600;">Sortie Diverse</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">Pertes, casse, péremption, etc.</div>
                        </div>
                    </label>
                    <label style="cursor: pointer; display: flex; align-items: center; gap: 0.5rem; padding: 1rem; border: 1px solid var(--warning); background: rgba(245,158,11,0.05); border-radius: var(--radius-md); flex: 1;">
                        <input type="radio" name="type" value="retour_fournisseur">
                        <div>
                            <div style="font-weight: 600;">Retour Fournisseur</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">Retour suite anomalie, invendus...</div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Médicament *</label>
                <select name="id_medicament" id="id_medicament" class="form-control" required>
                    <option value="">-- Sélectionner un médicament --</option>
                    <?php foreach($medicaments as $m): ?>
                        <?php if($m['quantite_stock'] > 0): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nom']) ?> (Stock global: <?= $m['quantite_stock'] ?>)</option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Sélectionner le lot *</label>
                <select name="id_lot" id="id_lot" class="form-control" required>
                    <option value="">-- Sélectionner le lot à prélever --</option>
                    <?php foreach($lots as $l): ?>
                        <option value="<?= $l['id'] ?>" data-medicament="<?= $l['id_medicament'] ?>" data-max="<?= $l['quantite'] ?>"><?= htmlspecialchars($l['medicament_nom'] . ' - Lot: ' . $l['numero_lot']) ?> (Dispo: <?= $l['quantite'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group form-col">
                    <label class="form-label">Quantité *</label>
                    <input type="number" name="quantite" id="quantite" class="form-control" min="1" required>
                    <small id="max-qte-hint" style="color: var(--text-muted); display: none;">Max: <span id="max-qte-val"></span></small>
                </div>
                <div class="form-group form-col">
                    <label class="form-label">Motif de la sortie *</label>
                    <input type="text" name="motif" class="form-control" placeholder="ex: Médicament périmé, Lot défectueux..." required>
                </div>
            </div>

            <div style="text-align: right; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-warning"><i class="fa-solid fa-save"></i> Enregistrer la sortie</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('id_medicament').addEventListener('change', function() {
    const medId = this.value;
    const lotSelect = document.getElementById('id_lot');
    const options = lotSelect.querySelectorAll('option');
    
    lotSelect.value = '';
    
    options.forEach(opt => {
        if(opt.value === '') return;
        if(opt.getAttribute('data-medicament') === medId) {
            opt.style.display = 'block';
        } else {
            opt.style.display = 'none';
        }
    });
});

document.getElementById('id_lot').addEventListener('change', function() {
    const hint = document.getElementById('max-qte-hint');
    const qteInput = document.getElementById('quantite');
    
    if(this.value !== '') {
        const selectedOption = this.options[this.selectedIndex];
        const max = selectedOption.getAttribute('data-max');
        document.getElementById('max-qte-val').innerText = max;
        hint.style.display = 'block';
        qteInput.max = max;
    } else {
        hint.style.display = 'none';
        qteInput.removeAttribute('max');
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

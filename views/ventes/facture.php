<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture <?= htmlspecialchars($vente['numero_facture']) ?></title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; margin: 0; padding: 20px; font-size: 14px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); }
        .header { display: flex; justify-content: space-between; margin-bottom: 40px; border-bottom: 2px solid #0d9488; padding-bottom: 20px; }
        .pharmacy-info h1 { margin: 0 0 10px 0; color: #0d9488; font-size: 24px; }
        .invoice-details { text-align: right; }
        .invoice-details h2 { margin: 0 0 10px 0; font-size: 20px; color: #555; }
        .client-info { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8fafc; color: #333; font-weight: bold; }
        .total-row { font-weight: bold; font-size: 18px; }
        .total-row td { border-top: 2px solid #333; }
        .footer { text-align: center; color: #777; margin-top: 50px; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px; }
        
        @page {
            size: auto;
            margin: 0mm;
        }
        
        @media print {
            body { margin: 1.5cm !important; padding: 0; }
            .invoice-box { border: none; box-shadow: none; max-width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #0d9488; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">Imprimer la facture</button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #64748b; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-left: 10px;">Fermer</button>
    </div>

    <div class="invoice-box">
        <div class="header">
            <div class="pharmacy-info">
                <h1>PharmaStock</h1>
                <p>Avenue de la Santé<br>N'Djaména, Tchad<br>Tél : +235 66990243 | Email : contact@pharmastock.com</p>
            </div>
            <div class="invoice-details">
                <h2>FACTURE</h2>
                <p><strong>N°:</strong> <?= htmlspecialchars($vente['numero_facture']) ?><br>
                <strong>Date:</strong> <?= formatDate($vente['date_vente']) ?><br>
                <strong>Vendeur:</strong> <?= htmlspecialchars($vente['vendeur_nom']) ?></p>
            </div>
        </div>

        <div class="client-info">
            <h3>Client</h3>
            <?php if ($vente['client_nom']): ?>
                <p><?= htmlspecialchars($vente['client_nom']) ?><br>
                <?= htmlspecialchars($vente['client_adresse'] ?? 'Adresse non renseignée') ?></p>
            <?php else: ?>
                <p>Client de passage</p>
            <?php endif; ?>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Désignation</th>
                    <th>Lot</th>
                    <th>Quantité</th>
                    <th>Prix U.</th>
                    <th style="text-align: right;">Montant</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lignes as $ligne): ?>
                <tr>
                    <td><?= htmlspecialchars($ligne['medicament_nom']) ?></td>
                    <td><?= $ligne['numero_lot'] ? htmlspecialchars($ligne['numero_lot']) : '-' ?></td>
                    <td><?= $ligne['quantite'] ?></td>
                    <td><?= formatMontant($ligne['prix_unitaire']) ?></td>
                    <td style="text-align: right;"><?= formatMontant($ligne['quantite'] * $ligne['prix_unitaire']) ?></td>
                </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="4" style="text-align: right;">TOTAL À PAYER</td>
                    <td style="text-align: right; color: #0d9488;"><?= formatMontant($vente['montant_total']) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Merci de votre visite. Les médicaments vendus ne sont ni repris ni échangés.</p>
            <p>Généré par PharmaStock le <?= date('d/m/Y à H:i') ?></p>
        </div>
    </div>
    
    <script>
        // Lancer l'impression automatiquement au chargement (optionnel)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>

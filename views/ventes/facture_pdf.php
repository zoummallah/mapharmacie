<?php
// views/ventes/facture_pdf.php
// Génération sécurisée de la facture PDF via FPDF

if (!isset($vente) || !$vente) {
    http_response_code(404);
    die("Vente introuvable.");
}

require_once __DIR__ . '/../../includes/fpdf.php';

/**
 * Convertit une chaîne UTF-8 en ISO-8859-1 (Latin-1) requis par FPDF.
 * Remplace utf8_decode() qui est déprécié depuis PHP 8.2.
 */
if (!function_exists('latinize')) {
    function latinize(?string $str): string {
        if ($str === null) return '';
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8');
        }
        // Fallback (PHP < 8.2 seulement)
        return utf8_decode($str);
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// Classe PDF personnalisée (En-tête et Pied de page)
// ──────────────────────────────────────────────────────────────────────────────
if (!class_exists('PDF_Facture')) {
    class PDF_Facture extends FPDF {

        function Header() {
            // Nom de la pharmacie
            $this->SetFont('Arial', 'B', 22);
            $this->SetTextColor(13, 148, 136); // Teal primaire
            $this->Cell(0, 10, 'PHARMASTOCK', 0, 1, 'L');

            $this->SetFont('Arial', '', 10);
            $this->SetTextColor(100, 116, 139); // Gris ardoise
            $this->Cell(0, 5, latinize('Avenue de la Santé, N\'Djaména'), 0, 1, 'L');
            $this->Cell(0, 5, latinize('Tél : +235 66990243 | Email : contact@pharmastock.com'), 0, 1, 'L');

            $this->Ln(8);

            // Ligne de séparation de l'en-tête
            $this->SetDrawColor(13, 148, 136);
            $this->SetLineWidth(0.5);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(6);
        }

        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->SetTextColor(148, 163, 184); // slate-400
            $this->Cell(0, 10, latinize('Merci de votre confiance — Les médicaments vendus ne sont ni repris ni échangés.'), 0, 0, 'C');
            $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'R');
        }
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// Construction du PDF
// ──────────────────────────────────────────────────────────────────────────────
$pdf = new PDF_Facture('P', 'mm', 'A4');
$pdf->SetMargins(12, 10, 12);
$pdf->AliasNbPages();
$pdf->AddPage();

// ── En-tête de la facture ────────────────────────────────────────────────────
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(30, 41, 59);
$pdf->Cell(100, 10, latinize('FACTURE N° ') . str_pad($vente['id'], 5, '0', STR_PAD_LEFT), 0, 0, 'L');

$pdf->SetFont('Arial', '', 11);
$pdf->SetTextColor(100, 116, 139);
$pdf->Cell(86, 10, 'Date : ' . date('d/m/Y H:i', strtotime($vente['date_vente'])), 0, 1, 'R');

$pdf->Ln(4);
$pdf->SetDrawColor(226, 232, 240);
$pdf->SetLineWidth(0.3);
$pdf->Line(12, $pdf->GetY(), 198, $pdf->GetY());
$pdf->Ln(6);

// ── Client & Vendeur ─────────────────────────────────────────────────────────
$nom_client = !empty($vente['client_nom']) ? $vente['client_nom'] : 'Client Anonyme';

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(30, 41, 59);
$pdf->Cell(22, 6, 'Client :', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(80, 6, latinize($nom_client), 0, 0, 'L');

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(35, 6, latinize('Pharmacien :'), 0, 0, 'R');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(49, 6, latinize($vente['vendeur_nom']), 0, 1, 'R');

$pdf->Ln(8);

// ── En-têtes du tableau ──────────────────────────────────────────────────────
$pdf->SetFillColor(13, 148, 136);    // Fond teal
$pdf->SetTextColor(255, 255, 255);   // Texte blanc
$pdf->SetDrawColor(13, 148, 136);
$pdf->SetLineWidth(0.3);
$pdf->SetFont('Arial', 'B', 10);

$pdf->Cell(88, 9, latinize('Désignation (Médicament)'), 1, 0, 'L', true);
$pdf->Cell(22, 9, latinize('Qté'), 1, 0, 'C', true);
$pdf->Cell(42, 9, 'Prix Unitaire', 1, 0, 'C', true);
$pdf->Cell(34, 9, 'Sous-Total', 1, 1, 'C', true);

// ── Lignes des articles ──────────────────────────────────────────────────────
$pdf->SetTextColor(30, 41, 59);
$pdf->SetDrawColor(203, 213, 225); // slate-300
$pdf->SetFont('Arial', '', 9);
$fill = false;
$total_articles = 0;

foreach ($lignes as $ligne) {
    // Alternance de couleur de fond (lignes zébrées)
    if ($fill) {
        $pdf->SetFillColor(241, 245, 249); // slate-100
    } else {
        $pdf->SetFillColor(255, 255, 255); // blanc
    }

    $designation = latinize($ligne['medicament_nom']);
    // Ajouter le numéro de lot s'il est disponible
    if (!empty($ligne['numero_lot'])) {
        $designation .= ' (' . latinize($ligne['numero_lot']) . ')';
    }
    if (mb_strlen($designation, 'ISO-8859-1') > 45) {
        $designation = substr($designation, 0, 42) . '...';
    }

    $prix_unit   = formatMontant($ligne['prix_unitaire']);
    $total_ligne = formatMontant($ligne['quantite'] * $ligne['prix_unitaire']);

    $pdf->Cell(88, 8, $designation, 1, 0, 'L', true);
    $pdf->Cell(22, 8, (string)$ligne['quantite'], 1, 0, 'C', true);
    $pdf->Cell(42, 8, $prix_unit, 1, 0, 'R', true);
    $pdf->Cell(34, 8, $total_ligne, 1, 1, 'R', true);

    $fill = !$fill;
    $total_articles += $ligne['quantite'];
}

// ── Totaux ───────────────────────────────────────────────────────────────────
$pdf->Ln(6);

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(100, 116, 139);
$pdf->Cell(112, 7, '', 0, 0);
$pdf->Cell(44, 7, 'Total articles :', 0, 0, 'R');
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(30, 41, 59);
$pdf->Cell(30, 7, (string)$total_articles, 0, 1, 'R');

// Séparateur avant le total final
$pdf->SetDrawColor(13, 148, 136);
$pdf->SetLineWidth(0.5);
$pdf->Line(130, $pdf->GetY(), 198, $pdf->GetY());
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 13);
$pdf->SetTextColor(13, 148, 136);
$pdf->Cell(112, 10, '', 0, 0);
$pdf->Cell(44, 10, 'TOTAL A PAYER :', 0, 0, 'R');
$pdf->Cell(30, 10, formatMontant($vente['montant_total']), 0, 1, 'R');

// ── Pied de document ─────────────────────────────────────────────────────────
$pdf->Ln(12);
$pdf->SetFont('Arial', 'I', 8);
$pdf->SetTextColor(148, 163, 184);
$pdf->Cell(0, 6, latinize('Document généré automatiquement par PharmaStock ERP — ' . date('d/m/Y à H:i')), 0, 1, 'L');

// ──────────────────────────────────────────────────────────────────────────────
// Envoi sécurisé du PDF :
// 1. Capturer en mémoire via Output('S') — AVANT de modifier les buffers/headers
// 2. Nettoyer TOUS les buffers PHP ouverts (session, warnings, HTML partiel...)
// 3. Envoyer les headers HTTP manuellement
// 4. Écrire le contenu PDF brut
// ──────────────────────────────────────────────────────────────────────────────
$pdfContent = $pdf->Output('S');

while (ob_get_level() > 0) {
    ob_end_clean();
}

$filename = 'Facture_' . str_pad($vente['id'], 5, '0', STR_PAD_LEFT) . '.pdf';

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"');
header('Content-Length: ' . strlen($pdfContent));
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');
header('Expires: 0');

echo $pdfContent;

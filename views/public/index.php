<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation - PharmaStock</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css?v=1.1">
</head>
<body>
    <div class="public-wrapper">
        <div class="public-container">
            <header class="public-header">
                <div class="public-logo">
                    <i class="fa-solid fa-staff-snake"></i>
                    <h1>PharmaStock</h1>
                </div>
                <p class="public-subtitle">Recherchez la disponibilité de vos médicaments en temps réel.</p>
            </header>

            <main class="public-main">
                <form action="<?= BASE_URL ?>/index.php" method="GET" class="public-search-form">
                    <input type="hidden" name="page" value="public">
                    <div class="search-input-group">
                        <i class="fa-solid fa-magnifying-glass search-icon"></i>
                        <input type="text" name="q" placeholder="Entrez le nom d'un médicament..." value="<?= htmlspecialchars($search) ?>" required autocomplete="off">
                        <?php if (!empty($search)): ?>
                            <a href="<?= BASE_URL ?>/index.php?page=public" class="clear-search" title="Effacer la recherche">
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-search">
                        <span>Rechercher</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                <?php if (!empty($search)): ?>
                    <section class="results-section">
                        <h2 class="results-title">
                            Résultats pour <span class="highlight">"<?= htmlspecialchars($search) ?>"</span>
                        </h2>
                        
                        <?php if (empty($resultats)): ?>
                            <div class="glass-alert alert-warning animate-fade-in">
                                <div class="alert-icon-wrapper">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                </div>
                                <div class="alert-message">
                                    <h3>Aucun résultat trouvé</h3>
                                    <p>Nous n'avons trouvé aucun médicament correspondant à votre recherche. Veuillez vérifier l'orthographe ou essayer un autre terme.</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="results-card animate-fade-in">
                                <div class="table-responsive">
                                    <table class="glass-table">
                                        <thead>
                                            <tr>
                                                <th>Médicament</th>
                                                <th>Catégorie</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($resultats as $res): ?>
                                            <tr>
                                                <td class="med-name"><?= htmlspecialchars($res['nom']) ?></td>
                                                <td class="med-cat"><?= htmlspecialchars($res['categorie']) ?></td>
                                                <td>
                                                    <?php if ($res['statut'] === 'En stock'): ?>
                                                        <span class="glass-badge badge-success">
                                                            <span class="badge-dot"></span> En stock
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="glass-badge badge-danger">
                                                            <span class="badge-dot"></span> Indisponible
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>
            </main>

            <footer class="public-footer">
                <a href="<?= BASE_URL ?>/index.php?page=login" class="btn-login-access">
                    <i class="fa-solid fa-user-shield"></i>
                    <span>Accès Pharmacien</span>
                </a>
            </footer>
        </div>
    </div>
</body>
</html>

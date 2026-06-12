<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - PharmaStock</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css?v=1.1">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-logo">
                <i class="fa-solid fa-staff-snake"></i>
            </div>
            <h1 class="auth-title">PharmaStock</h1>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/index.php?page=login" method="POST">
                <div class="form-group" style="text-align: left;">
                    <label class="form-label" for="email">Adresse Email</label>
                    <div style="position: relative;">
                        <i class="fa-solid fa-envelope" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                        <input type="email" id="email" name="email" class="form-control" placeholder="admin@pharmacie.com" style="padding-left: 2.5rem;" required>
                    </div>
                </div>
                
                <div class="form-group" style="text-align: left;">
                    <label class="form-label" for="password">Mot de passe</label>
                    <div style="position: relative;">
                        <i class="fa-solid fa-lock" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" style="padding-left: 2.5rem;" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem; padding: 0.75rem;">
                    Se Connecter <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
            <div style="margin-top: 1.5rem;">
                <a href="<?= BASE_URL ?>/index.php?page=public" style="font-size: 0.9rem;"><i class="fa-solid fa-search"></i> Consultation Publique</a>
            </div>
        </div>
    </div>
</body>
</html>

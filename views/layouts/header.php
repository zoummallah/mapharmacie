<?php
// views/layouts/header.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaStock - Tableau de bord</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons (FontAwesome via CDN pour simplicité) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css?v=2.2">
</head>
<body>
    <div class="app-container">
        <!-- Overlay pour mobile -->
        <div class="sidebar-overlay" id="sidebar-overlay"></div>
        
        <!-- Inclure la sidebar -->
        <?php include 'sidebar.php'; ?>
        
        <main class="main-content">
            <header class="top-header">
                <div class="header-search" style="display: flex; align-items: center; gap: 1rem;">
                    <button id="menu-toggle" class="btn btn-icon" style="background: transparent; color: var(--dark-color); font-size: 1.2rem; display: none;">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <!-- Optionnel : Recherche globale -->
                </div>
                <div class="header-user">
                    <span><i class="fa-solid fa-user-circle"></i> <?= htmlspecialchars($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']) ?> (<?= ucfirst($_SESSION['user_role']) ?>)</span>
                    <a href="<?= BASE_URL ?>/index.php?page=logout" class="logout-btn"><i class="fa-solid fa-sign-out-alt"></i> Déconnexion</a>
                </div>
            </header>
            
            <div class="content-area">
                <!-- Messages Flash -->
                <?php $messages = getFlashMessages(); ?>
                <?php foreach($messages as $msg): ?>
                    <div class="alert alert-<?= $msg['type'] ?>">
                        <?= htmlspecialchars($msg['message']) ?>
                    </div>
                <?php endforeach; ?>

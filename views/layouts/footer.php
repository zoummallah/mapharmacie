<?php
// views/layouts/footer.php
?>
            </div> <!-- End content-area -->
        </main>
    </div> <!-- End app-container -->
    
    <!-- Scripts communs -->
    <!-- SweetAlert2 pour des alertes professionnelles -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart.js pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Ajout du time() pour contourner le cache du navigateur -->
    <script src="<?= BASE_URL ?>/public/js/app.js?v=<?= time() ?>"></script>
</body>
</html>

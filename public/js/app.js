// public/js/app.js

document.addEventListener('DOMContentLoaded', () => {
    // --------------------------------------------------------
    // 1. GESTION DES ALERTES (Disparition auto)
    // --------------------------------------------------------
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });

    // --------------------------------------------------------
    // 2. GESTION DU MENU MOBILE (Sidebar)
    // --------------------------------------------------------
    const menuToggleBtn = document.getElementById('menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const body = document.body;
    
    // Le bouton toggle ouvre le menu
    if (menuToggleBtn && sidebar) {
        menuToggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            if(overlay) overlay.classList.toggle('active');
        });
    }

    // Cliquer sur le fond noir ferme le menu
    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }

    // --------------------------------------------------------
    // 3. INITIALISATION DES GRAPHIQUES (Chart.js)
    // --------------------------------------------------------
    if (typeof Chart !== 'undefined') {
        initDashboardCharts();
    }
});

function initDashboardCharts() {
    // Palette de couleurs Premium (Vibrantes)
    const colors = [
        'rgba(59, 130, 246, 0.85)',   // Bleu Primaire
        'rgba(139, 92, 246, 0.85)',   // Violet
        'rgba(16, 185, 129, 0.85)',   // Vert Émeraude
        'rgba(245, 158, 11, 0.85)',   // Orange Ambre
        'rgba(239, 68, 68, 0.85)',    // Rouge vif
        'rgba(14, 165, 233, 0.85)',   // Bleu Ciel
        'rgba(217, 70, 239, 0.85)',   // Fuchsia
        'rgba(244, 63, 94, 0.85)'     // Rose
    ];
    
    // Bordures solides (opacité 1)
    const borderColors = colors.map(c => c.replace('0.85', '1'));

    // Configuration par défaut Chart.js globale
    Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif";
    Chart.defaults.color = '#64748b'; // Gris texte moderne
    Chart.defaults.scale.grid.color = 'rgba(0, 0, 0, 0.04)'; // Lignes très subtiles

    // Scanner tous les canvas contenant data-type
    document.querySelectorAll('canvas[data-type]').forEach(canvas => {
        const type = canvas.getAttribute('data-type');
        const labels = JSON.parse(canvas.getAttribute('data-labels') || '[]');
        const values = JSON.parse(canvas.getAttribute('data-values') || '[]');
        const titleText = canvas.getAttribute('data-title') || '';
        
        let config = {};

        // Graphiques Circulaires (Doughnut / Pie)
        if (type === 'doughnut' || type === 'pie') {
            config = {
                type: type,
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: colors,
                        borderColor: '#ffffff',
                        borderWidth: 2,
                        hoverOffset: 12 // Animation de survol (la part s'éloigne)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            position: 'bottom', 
                            labels: { usePointStyle: true, padding: 20, font: {size: 13, weight: '500'} } 
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)', // Mode sombre élégant
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: { size: 14, weight: '600' },
                            bodyFont: { size: 13 },
                            boxPadding: 6
                        }
                    },
                    cutout: type === 'doughnut' ? '68%' : 0 // Anneau très fin pour le Doughnut
                }
            };
        } 
        // Graphiques en Barres (Bar)
        else if (type === 'bar') {
            config = {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: titleText,
                        data: values,
                        backgroundColor: colors[0], // On prend le bleu par défaut
                        borderRadius: 6, // Les coins en haut sont arrondis
                        barPercentage: 0.5 // Barres plus fines
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: { size: 14, weight: '600' },
                            bodyFont: { size: 13 },
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, border: { display: false } },
                        x: { border: { display: false }, grid: { display: false }, ticks: { font: { weight: '500' } } }
                    }
                }
            };
        } 
        // Graphiques Linéaires avec Dégradé (Area/Line Chart)
        else if (type === 'line') {
            const ctx = canvas.getContext('2d');
            
            // Création d'un magnifique dégradé sous la ligne
            let gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)'); // Bleu semi-transparent
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)'); // S'efface vers le bas

            config = {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: titleText,
                        data: values,
                        borderColor: borderColors[0],
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff', // Points blancs au centre
                        pointBorderColor: borderColors[0],
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 7, // Gros point au survol
                        fill: true, // C'est ce qui active le dégradé
                        tension: 0.4 // Courbe douce et élégante
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            padding: 12,
                            cornerRadius: 8,
                            intersect: false, // Afficher au survol n'importe où
                            mode: 'index',
                            titleFont: { size: 14, weight: '600' },
                            bodyFont: { size: 14, weight: 'bold' }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, border: { display: false } },
                        x: { border: { display: false }, grid: { display: false } }
                    },
                    interaction: { mode: 'nearest', axis: 'x', intersect: false }
                }
            };
        }

        // Dessiner le graphique
        new Chart(canvas, config);
    });
}

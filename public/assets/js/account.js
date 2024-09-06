class Account {
    constructor() {
        // Supposons que les attributs data sont des chaînes JSON
        this.service_value = JSON.parse(document.getElementById('service_value').getAttribute('data-value'));
        this.service_name = JSON.parse(document.getElementById('service_name').getAttribute('data-name'));
        this.data_peoples = JSON.parse(document.getElementById('data_number_peoples_by_month').getAttribute('data-peoples'));
    }

    _init() {
        console.log(this.data_peoples);
    }

    create_graph() {
        const ctx = document.getElementById('statistics-services').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: this.service_name,
                datasets: [{
                    label: 'Répartition des Services',
                    data: this.service_value,
                    backgroundColor: [
                        '#0FA3B1', '#16425B', '#FFD166', '#778DA9', '#B5E2FA',
                        '#EDDEA4', '#F7A072', '#0D1B2A', '#A9A377'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 12,  // Taille de police réduite
                                family: 'Arial, sans-serif',
                                weight: 'normal' // Poids de la police normal pour réduire l'espace
                            },
                            color: '#333333',
                            padding: 10,  // Moins de padding pour réduire l'espace occupé
                            usePointStyle: true,
                            boxWidth: 10, // Taille du point réduite
                        }
                    },
                    tooltip: {
                        backgroundColor: '#333333',
                        titleFont: {
                            size: 12, // Taille de la police du titre réduite
                            family: 'Arial, sans-serif',
                            weight: 'bold',
                        },
                        bodyFont: {
                            size: 10, // Taille de la police du contenu réduite
                            family: 'Arial, sans-serif',
                        },
                        callbacks: {
                            label: function (tooltipItem, chart) {
                                const total = chart.data.datasets[0].data.reduce((acc, curr) => acc + curr, 0);
                                const value = chart.data.datasets[0].data[tooltipItem.dataIndex];
                                const percentage = ((value / total) * 100).toFixed(2);
                                return chart.data.labels[tooltipItem.dataIndex] + ': ' + percentage + '%';
                            }
                        }
                    }
                },
                layout: {
                    padding: 10 // Moins de padding global pour maximiser l'espace graphique
                },
                elements: {
                    arc: {
                        borderWidth: 1,
                        borderColor: '#ffffff'
                    }
                },
                cutout: '60%'
            }
        });

}



    createCurve() {
        // Sélection du contexte du canvas pour le graphique
        const ctx = document.getElementById('statistics-peoples').getContext('2d');

        // Configuration des données du graphique
        const data = {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Nombre de personnes par mois',
                data: this.data_peoples, // Assurez-vous que this.data_peoples est bien définie dans le contexte actuel
                backgroundColor: 'rgba(72, 207, 173, 0.2)', // Couleur de fond moderne
                borderColor: '#48CFAF', // Couleur de la ligne moderne
                borderWidth: 3, // Épaisseur de la ligne
                pointBackgroundColor: '#FF6F61', // Couleur des points moderne
                pointBorderColor: '#FFFFFF', // Bordure des points
                pointHoverBackgroundColor: '#FFFFFF', // Couleur des points au survol
                pointHoverBorderColor: '#FF6F61', // Bordure des points au survol
                pointRadius: 6, // Taille des points légèrement plus grande
                pointHoverRadius: 8 // Taille des points au survol légèrement plus grande
            }]
        };

        // Configuration des options du graphique
        const options = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#333', // Couleur de légende neutre
                        font: {
                            size: 14,
                            family: 'Helvetica Neue, Arial, sans-serif', // Police moderne
                            weight: '500' // Poids de police semi-gras pour modernité
                        },
                        padding: 20,
                        boxWidth: 15
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)', // Fond de tooltip plus sombre
                    titleColor: '#fff',
                    titleFont: {
                        size: 14,
                        weight: 'normal'
                    },
                    bodyColor: '#fff',
                    bodyFont: {
                        size: 12
                    },
                    padding: 10,
                    callbacks: {
                        label: function(tooltipItem) {
                            return `${tooltipItem.label}: ${tooltipItem.raw}`; // Affiche uniquement la valeur
                        }
                    }
                }
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Mois',
                        font: {
                            size: 14,
                            weight: '500', // Poids de police semi-gras
                            family: 'Helvetica Neue, Arial, sans-serif'
                        },
                        color: '#333' // Couleur de texte plus neutre
                    },
                    ticks: {
                        color: '#777', // Couleur de texte modernisée
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)', // Ligne de grille subtile
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Nombre de personnes',
                        font: {
                            size: 14,
                            weight: '500', // Poids de police semi-gras
                            family: 'Helvetica Neue, Arial, sans-serif'
                        },
                        color: '#333'
                    },
                    ticks: {
                        color: '#777', // Couleur de texte modernisée
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)', // Ligne de grille subtile
                    }
                }
            },
            animation: {
                duration: 1000, // Durée d'animation légèrement plus longue pour une transition plus fluide
                easing: 'easeInOutCubic' // Animation fluide et moderne
            }
        };

        // Création du graphique avec Chart.js
        new Chart(ctx, {
            type: 'line',
            data: data,
            options: options
        });
    }



}

document.addEventListener('DOMContentLoaded', function() {
    const account = new Account();
    account._init();
    account.create_graph();
    account.createCurve();

    var popup = document.getElementById("popup");
    var acceptBtn = document.getElementById("acceptBtn");
    const denyBtn = document.getElementById("denyBtn");

    acceptBtn.addEventListener("click", function() {
        // Retirer le pop-up
        popup.style.display = "none";
        document.body.classList.remove("modal-open");
    });

    denyBtn.addEventListener("click", function() {
        // Retirer le pop-up
        popup.style.display = "none";
        document.body.classList.remove("modal-open");
    });


});

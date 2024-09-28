class Graph {
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
                        '#FFFFFF', '#D1D6DB', '#C1C4CC', '#A1A7B1', '#6D767F',
                        '#586168', '#3A4147', '#28323A', '#000000'
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
                data: this.data_peoples,
                backgroundColor: 'rgba(0,0,0,0.1)', // Fond bleu léger
                borderColor: 'rgba(0,0,0,0.39)', // Couleur de la ligne bleu
                borderWidth: 3,
                pointBackgroundColor: '#000000', // Bleu pour les points
                pointBorderColor: '#000000',
                pointHoverBackgroundColor: 'rgba(0,0,0,0.14)',
                pointRadius: 5,
                pointHoverRadius: 8,
                tension: 0.4, // Courbure de la ligne (plus courbé)
                fill: true // Remplir la courbe
            }]
        };

        // Configuration des options du graphique
        const options = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // Suppression de la légende
                },
                tooltip: {
                    backgroundColor: '#333',
                    titleColor: '#FFF',
                    bodyColor: '#DDD',
                    padding: 10,
                    callbacks: {
                        label: function(tooltipItem) {
                            return `${tooltipItem.label}: ${tooltipItem.raw}`;
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
                            family: 'Arial, sans-serif',
                            weight: 'bold'
                        },
                        color: '#333'
                    },
                    ticks: {
                        color: '#555',
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: 'rgba(200, 200, 200, 0.2)' // Grille discrète
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Nombre de personnes',
                        font: {
                            size: 14,
                            family: 'Arial, sans-serif',
                            weight: 'bold'
                        },
                        color: '#333'
                    },
                    ticks: {
                        color: '#555',
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: 'rgba(200, 200, 200, 0.2)'
                    }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutCubic' // Animation plus fluide
            },
            elements: {
                line: {
                    shadowOffsetX: 0,
                    shadowOffsetY: 10,
                    shadowBlur: 20,
                    shadowColor: 'rgba(0, 0, 0, 0.15)' // Ombre sous la courbe
                }
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
    const account = new Graph();
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

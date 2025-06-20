document.addEventListener('DOMContentLoaded', () => {
    Chart.register(ChartDataLabels);

    function initRevenueExpenseChart() {
        const canvas = document.getElementById('revenueExpenseChart');
        if (!canvas) {
            return;
        }

        const labels = JSON.parse(canvas.dataset.labels || '[]');
        const expensesData = JSON.parse(canvas.dataset.expenses || '[]');
        const revenuesData = JSON.parse(canvas.dataset.revenues || '[]');

        new Chart(canvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: "Receitas",
                    data: revenuesData,
                    pointRadius: 3,
                    pointHoverRadius: 3,
                    pointHitRadius: 10,
                }, {
                    label: "Despesas",
                    data: expensesData,
                    pointRadius: 3,
                    pointHoverRadius: 3,
                    pointHitRadius: 10,
                }]
            },
            options: {
                interaction: {
                    mode: 'index'
                },
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        ticks: {
                            callback: function (value) {
                                return 'R$ ' + value;
                            },
                        },
                        beginAtZero: true,
                    }
                },
                plugins: {
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        color: '#333',
                        font: {
                            'weight': 'bold',
                            size: 12
                        },
                        display: 'auto',
                        formatter: function (value, context) {
                            return 'R$ ' + value
                        }
                    },
                    colorschemes: {
                        scheme: 'tableau.ClassicBlueRed2'
                    }
                }
            }
        });
    }

    function initExpensesByCategoryChart() {
        const canvas = document.getElementById('expensesByCategory');
        if (!canvas) {
            return;
        }

        const labels = JSON.parse(canvas.dataset.labels || '[]');
        const data = JSON.parse(canvas.dataset.data || '[]');

        new Chart(canvas, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: "Despesas",
                    data: data,
                    hoverOffset: 4,
                    borderColor: '#36454F20',
                    radius: '90%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    autoPadding: true
                },
                plugins: {
                    datalabels: {
                        align: 'end',
                        anchor: 'end',
                        color: 'black',
                        font: {
                            'weight': 'bold',
                            size: 11
                        },
                        formatter: function (value, ctx) {
                            const totalSum = ctx.dataset.data.reduce((accumulator, currentValue) => {
                                return accumulator + currentValue
                            }, 0);
                            const percentage = value / totalSum * 100;
                            if (percentage >= 0) {
                                return `${percentage.toFixed(1)}%`;
                            }
                            else {
                                return '';
                            }
                        }
                    },
                    legend: {
                        position: 'top'
                    },
                    colorschemes: {
                        scheme: 'brewer.Paired12'
                    }
                }
            }
        });
    }

    initRevenueExpenseChart();
    initExpensesByCategoryChart();
});
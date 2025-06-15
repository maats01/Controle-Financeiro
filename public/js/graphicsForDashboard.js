// graphsForDashboard.js

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
                    label: "Despesas",
                    data: expensesData,
                    backgroundColor: "rgb(255, 0, 0)",
                    borderColor: "rgb(255, 0, 0)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgb(255, 0, 0)",
                    pointBorderColor: "rgba(78, 114, 223, 0.1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgb(255, 0, 0)",
                    pointHoverBorderColor: "rgb(255, 0, 0)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                }, {
                    label: "Receitas",
                    data: revenuesData,
                    backgroundColor: "rgb(0, 62, 255)",
                    borderColor: "rgb(0, 62, 255)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
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
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: "Despesas",
                    data: data,
                    hoverOffset: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    datalabels: {
                        color: 'black',
                        font: {
                            'weight': 'bold',
                            size: 12
                        },
                        display: 'auto',
                        formatter: function (value, context) {
                            return 'R$ ' + value;
                        }
                    }
                }
            }
        });
    }

    initRevenueExpenseChart();
    initExpensesByCategoryChart();
});
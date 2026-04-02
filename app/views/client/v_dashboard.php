<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>


<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">


<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Link to Dashboard CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/client/dashboard_style.css">

<style>
    /* Charts Grid Layout */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        margin-top: 32px;
        margin-bottom: 30px;
    }

    /* Chart Card Styling */
    .chart-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #f0f0f0;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        padding: 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        position: relative;
    }

    .chart-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
        border-color: #e8e8e8;
    }



    /* Chart Card Header */
    .chart-card h3 {
        margin: 0 0 20px 0;
        color: #2c3e50;
        font-size: 16px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: 0.3px;
    }

    .chart-card h3 .material-icons {
        font-size: 22px;
        color: #FF8A80;
    }

    /* Chart Container */
    .chart-container {
        position: relative;
        height: 300px;
        padding: 10px 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chart-container canvas {
        max-height: 100%;
    }

    /* Chart Legend Styling */
    .chart-card canvas + .chart-legend {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 16px;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #f5f5f5;
    }

    .chart-legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #666;
    }

    .chart-legend-color {
        width: 12px;
        height: 12px;
        border-radius: 2px;
    }

    /* Loading State */
    .chart-card.loading {
        opacity: 0.6;
        pointer-events: none;
    }

    .chart-card.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 30px;
        height: 30px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #FF8A80;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }

    /* Responsive Design */
    @media (max-width: 1440px) {
        .charts-grid {
            gap: 20px;
        }

        .chart-card {
            padding: 20px;
        }
    }

    @media (max-width: 1024px) {
        .charts-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .chart-container {
            height: 280px;
        }
    }

    @media (max-width: 768px) {
        .charts-grid {
            grid-template-columns: 1fr;
            gap: 16px;
            margin-top: 24px;
        }

        .chart-card {
            padding: 16px;
            border-radius: 8px;
        }

        .chart-card h3 {
            font-size: 15px;
            margin-bottom: 16px;
        }

        .chart-container {
            height: 250px;
        }
    }

    @media (max-width: 480px) {
        .charts-grid {
            gap: 12px;
        }

        .chart-card {
            padding: 12px;
        }

        .chart-container {
            height: 200px;
        }

        .chart-card h3 {
            font-size: 14px;
            gap: 6px;
        }

        .chart-card h3 .material-icons {
            font-size: 18px;
        }
    }
</style>

<!-- Dashboard Content -->
<div class="main-content">
    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon sites">
                <span class="material-icons">location_on</span>
            </div>
            <div class="stat-info">
                <h3>5</h3>
                <p>No.of Sites</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon officers">
                <span class="material-icons">group</span>
            </div>
            <div class="stat-info">
                <h3>24</h3>
                <p>Total Officers</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon incidents">
                <span class="material-icons">warning</span>
            </div>
            <div class="stat-info">
                <h3>8</h3>
                <p>Incidents</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon payment">
                <span class="material-icons">event</span>
            </div>
            <div class="stat-info">
                <h3>12/21</h3>
                <p>Payment due date</p>
            </div>
        </div>
    </div>

    <!-- Charts 2x2 Grid Section -->
    <div class="charts-grid">
        <!-- Chart 1: Sites with Total Officers -->
        <div class="chart-card">
            <h3><span class="material-icons">person_outline</span> Officers by Site</h3>
            <div class="chart-container">
                <canvas id="sitesOfficersChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Incidents by Status (Pie) -->
        <div class="chart-card">
            <h3><span class="material-icons">pie_chart</span> Incidents Status</h3>
            <div class="chart-container">
                <canvas id="incidentStatusChart"></canvas>
            </div>
        </div>

        <!-- Chart 3: Payment History (Line) -->
        <div class="chart-card">
            <h3><span class="material-icons">trending_up</span> Payment History</h3>
            <div class="chart-container">
                <canvas id="paymentHistoryChart"></canvas>
            </div>
        </div>

        <!-- Chart 4: Next Payment by Site -->
        <div class="chart-card">
            <h3><span class="material-icons">paypal</span> Upcoming Payments</h3>
            <div class="chart-container">
                <canvas id="nextPaymentChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const chartData = <?php echo json_encode($data['charts']); ?>;

    // Chart 1: Officers by Site - Bar Chart
    const sitesOfficersCtx = document.getElementById('sitesOfficersChart').getContext('2d');
    const sitesOfficersChart = new Chart(sitesOfficersCtx, {
        type: 'bar',
        data: {
            labels: chartData.sitesOfficers.labels,
            datasets: [{
                label: 'Total Officers',
                data: chartData.sitesOfficers.data,
                backgroundColor: '#FF8A80',
                borderWidth: 0,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Officers: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 0
                    }
                }
            }
        }
    });

    // Chart 2: Incidents by Status - Pie Chart
    const incidentStatusCtx = document.getElementById('incidentStatusChart').getContext('2d');
    const incidentStatusChart = new Chart(incidentStatusCtx, {
        type: 'pie',
        data: {
            labels: chartData.incidentStatus.labels,
            datasets: [{
                data: chartData.incidentStatus.data,
                backgroundColor: chartData.incidentStatus.colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Chart 3: Payment History - Line Chart
    const paymentHistoryCtx = document.getElementById('paymentHistoryChart').getContext('2d');
    const paymentHistoryChart = new Chart(paymentHistoryCtx, {
        type: 'line',
        data: {
            labels: chartData.paymentHistory.labels,
            datasets: [{
                label: 'Payments',
                data: chartData.paymentHistory.data,
                borderColor: '#FF8A80',
                backgroundColor: 'rgba(255, 138, 128, 0.3)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#FF8A80',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return 'Amount: KES ' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'KES ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Chart 4: Next Payment by Site - Bar Chart
    const nextPaymentCtx = document.getElementById('nextPaymentChart').getContext('2d');
    const nextPaymentChart = new Chart(nextPaymentCtx, {
        type: 'bar',
        data: {
            labels: chartData.nextPaymentBySite.labels,
            datasets: [{
                label: 'Next Payment Amount',
                data: chartData.nextPaymentBySite.data,
                backgroundColor: '#FFB74D',
                borderWidth: 0,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Amount: KES ' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'KES ' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 0
                    }
                }
            }
        }
    });
</script>





</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
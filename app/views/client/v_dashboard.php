<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>


<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">


<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Link to Dashboard CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/client/dashboard_style.css">

<style>
    .chart-section {
        background: white;
        border-radius: 8px;
        border: 1px solid #ececec;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        padding: 0;
    }

    .chart-section.full-width {
        grid-column: 1 / -1;
        margin-top: 0;
    }

    .chart-container {
        position: relative;
        height: 300px;
        padding: 10px 0;
    }

    .chart-section.full-width .chart-container {
        height: 350px;
    }

    .chart-carousel {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
    }

    .chart-carousel-wrapper {
        display: flex;
        transition: transform 0.5s ease-in-out;
    }

    .chart-slide {
        min-width: 100%;
        padding: 16px 20px 20px;
        box-sizing: border-box;
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 28px;
        align-items: center;
    }

    .chart-content {
        opacity: 0;
        transform: translateX(-50px);
        transition: opacity 0.6s ease-out, transform 1s ease-out;
    }

    .chart-description {
        opacity: 0;
        transform: translateX(50px);
        transition: opacity 0.6s ease-out, transform 1s ease-out;
    }

    .chart-slide.active .chart-content {
        opacity: 1;
        transform: translateX(0);
    }

    .chart-slide.active .chart-description {
        opacity: 1;
        transform: translateX(0);
    }

    .chart-content {
        display: flex;
        flex-direction: column;
    }

    .chart-description {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #ececec;
    }

    .chart-description h4 {
        margin: 0 0 15px 0;
        color: #D32F2F;
        font-size: 16px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chart-description h4 .material-icons {
        font-size: 20px;
    }

    .chart-description p {
        margin: 0 0 12px 0;
        color: #555;
        font-size: 14px;
        line-height: 1.6;
    }

    .chart-description ul {
        margin: 10px 0;
        padding-left: 20px;
        color: #666;
        font-size: 13px;
    }

    .chart-description ul li {
        margin-bottom: 6px;
    }

    .chart-title {
        text-align: center;
        margin-bottom: 12px;
        color: #333;
        font-size: 20px;
        font-weight: 600;
    }

    .carousel-controls {
        display: flex;
        gap: 10px;
    }

    .carousel-nav {
        background: #f2f4f7;
        border: none;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .carousel-nav:hover {
        background: #e4e7ec;
        transform: translateY(-1px);
    }

    .carousel-nav .material-icons {
        font-size: 20px;
        color: #c41212;
    }

    .carousel-indicators {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 15px;
    }

    .carousel-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #ccc;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .carousel-dot.active {
        background: #c41212;
        width: 28px;
        border-radius: 5px;
    }

    @media (max-width: 768px) {
        .chart-container {
            height: 250px;
        }

        .chart-section.full-width .chart-container {
            height: 300px;
        }

        .chart-slide {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .chart-description {
            padding: 15px;
        }
    }
</style>

<!-- Dashboard Content -->
<div class="main-content">
    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon sites">
                <i class="fas fa-location-dot"></i>
            </div>
            <div class="stat-info">
                <h3>5</h3>
                <p>No.of Sites</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon officers">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>24</h3>
                <p>Total Officers</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon incidents">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-info">
                <h3>8</h3>
                <p>Incidents</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon payment">
                <i class="fas fa-calendar-days"></i>
            </div>
            <div class="stat-info">
                <h3>12/21</h3>
                <p>Payment due date</p>
            </div>
        </div>
    </div>

    <!-- Charts Carousel Section -->
    <div class="section chart-section full-width">
        <div class="section-header">
            <h2 class="section-title">Analytics Overview</h2>
            <div class="carousel-controls">
                <button class="carousel-nav prev" onclick="changeChartSlide(-1)">
                    <span class="material-icons">chevron_left</span>
                </button>
                <button class="carousel-nav next" onclick="changeChartSlide(1)">
                    <span class="material-icons">chevron_right</span>
                </button>
            </div>
        </div>

        <div class="chart-carousel">
            <div class="chart-carousel-wrapper" id="chartCarouselWrapper">
                <!-- Chart 1: Incidents by Severity -->
                <div class="chart-slide">
                    <div class="chart-content">
                        <h3 class="chart-title">Incidents by Severity</h3>
                        <div class="chart-container">
                            <canvas id="severityChart"></canvas>
                        </div>
                    </div>
                    <div class="chart-description">
                        <h4><span class="material-icons">bar_chart</span> Severity Distribution</h4>
                        <p>This chart displays the distribution of incidents across different severity levels at your sites.</p>
                        <p><strong>Severity Levels:</strong></p>
                        <ul>
                            <li><strong>Critical:</strong> Immediate threat requiring urgent action</li>
                            <li><strong>High:</strong> Significant security concern</li>
                            <li><strong>Medium:</strong> Notable incident requiring attention</li>
                            <li><strong>Low:</strong> Minor security event</li>
                        </ul>
                        <p>Use this data to prioritize security improvements and resource allocation.</p>
                    </div>
                </div>

                <!-- Chart 2: Monthly Incident Trend -->
                <div class="chart-slide">
                    <div class="chart-content">
                        <h3 class="chart-title">Monthly Incident Trend</h3>
                        <div class="chart-container">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>
                    <div class="chart-description">
                        <h4><span class="material-icons">trending_up</span> Trend Analysis</h4>
                        <p>Track incident patterns over the past 6 months to identify trends and seasonal variations.</p>
                        <p><strong>Key Insights:</strong></p>
                        <ul>
                            <li>Identify peak incident periods</li>
                            <li>Measure effectiveness of security measures</li>
                            <li>Plan staffing and resource allocation</li>
                            <li>Detect emerging security patterns</li>
                        </ul>
                        <p>A declining trend indicates improving security conditions.</p>
                    </div>
                </div>

                <!-- Chart 3: Incidents by Site -->
                <div class="chart-slide">
                    <div class="chart-content">
                        <h3 class="chart-title">Incidents by Site</h3>
                        <div class="chart-container">
                            <canvas id="siteChart"></canvas>
                        </div>
                    </div>
                    <div class="chart-description">
                        <h4><span class="material-icons">business</span> Site Comparison</h4>
                        <p>Compare incident rates across all your locations to identify high-risk sites.</p>
                        <p><strong>Action Items:</strong></p>
                        <ul>
                            <li>Focus additional security on high-incident sites</li>
                            <li>Review security procedures at problem locations</li>
                            <li>Share best practices from low-incident sites</li>
                            <li>Adjust staffing levels based on site needs</li>
                        </ul>
                        <p>Sites with higher bars may require enhanced security measures.</p>
                    </div>
                </div>

                <!-- Chart 4: Incidents by Type -->
                <div class="chart-slide">
                    <div class="chart-content">
                        <h3 class="chart-title">Incidents by Type</h3>
                        <div class="chart-container">
                            <canvas id="typeChart"></canvas>
                        </div>
                    </div>
                    <div class="chart-description">
                        <h4><span class="material-icons">search</span> Incident Categories</h4>
                        <p>Understand what types of security incidents are most common at your facilities.</p>
                        <p><strong>Common Types:</strong></p>
                        <ul>
                            <li>Theft & Robbery attempts</li>
                            <li>Unauthorized access</li>
                            <li>Vandalism</li>
                            <li>Disturbances</li>
                            <li>Safety hazards</li>
                        </ul>
                        <p>Use this data to develop targeted prevention strategies for each incident type.</p>
                    </div>
                </div>

                <!-- Chart 5: Incidents by Status -->
                <div class="chart-slide">
                    <div class="chart-content">
                        <h3 class="chart-title">Incidents by Status</h3>
                        <div class="chart-container">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                    <div class="chart-description">
                        <h4><span class="material-icons">bolt</span> Resolution Status</h4>
                        <p>Monitor the current status of all reported incidents across your sites.</p>
                        <p><strong>Status Categories:</strong></p>
                        <ul>
                            <li><strong>Pending:</strong> Awaiting investigation or action</li>
                            <li><strong>In Progress:</strong> Currently being addressed</li>
                            <li><strong>Resolved:</strong> Successfully handled and closed</li>
                            <li><strong>Rejected:</strong> Determined not to be valid incidents</li>
                        </ul>
                        <p>A high proportion of resolved incidents indicates effective incident management.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carousel Indicators -->
        <div class="carousel-indicators" id="chartCarouselIndicators">
            <span class="carousel-dot active" onclick="goToChartSlide(0)"></span>
            <span class="carousel-dot" onclick="goToChartSlide(1)"></span>
            <span class="carousel-dot" onclick="goToChartSlide(2)"></span>
            <span class="carousel-dot" onclick="goToChartSlide(3)"></span>
            <span class="carousel-dot" onclick="goToChartSlide(4)"></span>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const chartData = <?php echo json_encode($data['charts']); ?>;

    // Chart 1: Incidents by Severity - Doughnut Chart
    const severityCtx = document.getElementById('severityChart').getContext('2d');
    const severityChart = new Chart(severityCtx, {
        type: 'doughnut',
        data: {
            labels: chartData.severityChart.labels,
            datasets: [{
                data: chartData.severityChart.data,
                backgroundColor: chartData.severityChart.colors,
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

    // Chart 2: Monthly Trend - Line Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    const trendChart = new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: chartData.monthlyTrend.labels,
            datasets: [{
                label: 'Incidents',
                data: chartData.monthlyTrend.data,
                borderColor: chartData.monthlyTrend.borderColor,
                backgroundColor: chartData.monthlyTrend.backgroundColor,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: chartData.monthlyTrend.borderColor,
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Chart 3: Incidents by Site - Bar Chart
    const siteCtx = document.getElementById('siteChart').getContext('2d');
    const siteChart = new Chart(siteCtx, {
        type: 'bar',
        data: {
            labels: chartData.siteIncidents.labels,
            datasets: [{
                label: 'Incidents',
                data: chartData.siteIncidents.data,
                backgroundColor: chartData.siteIncidents.colors,
                borderWidth: 0,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Incidents: ' + context.parsed.y;
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
                        minRotation: 45
                    }
                }
            }
        }
    });

    // Chart 4: Incidents by Type - Pie Chart
    const typeCtx = document.getElementById('typeChart').getContext('2d');
    const typeChart = new Chart(typeCtx, {
        type: 'pie',
        data: {
            labels: chartData.typeChart.labels,
            datasets: [{
                data: chartData.typeChart.data,
                backgroundColor: chartData.typeChart.colors,
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
                }
            }
        }
    });

    // Chart 5: Incidents by Status - Doughnut Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: chartData.statusChart.labels,
            datasets: [{
                data: chartData.statusChart.data,
                backgroundColor: chartData.statusChart.colors,
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
                }
            }
        }
    });

    // Chart Carousel Functionality
    let currentChartSlide = 0;
    let chartAutoSlideInterval;
    const totalChartSlides = 5;

    function updateChartCarousel() {
        const wrapper = document.getElementById('chartCarouselWrapper');
        const slides = wrapper.querySelectorAll('.chart-slide');

        // Remove active class from all slides
        slides.forEach(slide => slide.classList.remove('active'));

        // Update transform for slide transition
        wrapper.style.transform = `translateX(-${currentChartSlide * 100}%)`;

        // Wait for slide transition to complete, then trigger content animations
        setTimeout(() => {
            slides[currentChartSlide].classList.add('active');
        }, 100);

        // Update indicators
        document.querySelectorAll('#chartCarouselIndicators .carousel-dot').forEach((dot, index) => {
            dot.classList.toggle('active', index === currentChartSlide);
        });
    }

    function changeChartSlide(direction) {
        currentChartSlide = (currentChartSlide + direction + totalChartSlides) % totalChartSlides;
        updateChartCarousel();
        resetChartAutoSlide();
    }

    function goToChartSlide(index) {
        currentChartSlide = index;
        updateChartCarousel();
        resetChartAutoSlide();
    }

    function autoSlideChart() {
        currentChartSlide = (currentChartSlide + 1) % totalChartSlides;
        updateChartCarousel();
    }

    function resetChartAutoSlide() {
        clearInterval(chartAutoSlideInterval);
        chartAutoSlideInterval = setInterval(autoSlideChart, 5000);
    }

    // Start auto-slide
    chartAutoSlideInterval = setInterval(autoSlideChart, 5000);

    // Initialize first slide as active
    document.querySelector('.chart-slide').classList.add('active');

    // Pause on hover
    const chartCarousel = document.querySelector('.chart-carousel');
    chartCarousel.addEventListener('mouseenter', () => clearInterval(chartAutoSlideInterval));
    chartCarousel.addEventListener('mouseleave', resetChartAutoSlide);
</script>





</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
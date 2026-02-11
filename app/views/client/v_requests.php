<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<?php
// Fetch client sites for AJAX fallback
if (isset($_SESSION['user_id'])) {
    require_once APP_ROOT . '/models/M_client.php';
    $clientModel = new M_client();
    $clientSites = $clientModel->getClientSites($_SESSION['user_id']);
}

// Fetch all active packages
require_once APP_ROOT . '/models/M_package.php';
$packageModel = new M_package();
$packages = $packageModel->getAllPackages();
?>

<style>
    :root {
        --accent: #a40000;
        --accent-light: #c41e1e;
        --shadow: 0 6px 18px rgba(20,20,40,0.06);
        --radius: 12px;
    }

    .requests-container {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 24px;
        padding: 24px;
        min-height: calc(100vh - 80px);
    }

    /* Left Container - Packages */
    .packages-container {
        background: white;
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: var(--shadow);
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: opacity 0.3s ease;
    }

    .packages-container.disabled {
        pointer-events: none;
        user-select: none;
    }

    .packages-header {
        margin-bottom: 24px;
        flex-shrink: 0;
    }

    .packages-header h2 {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 8px;
    }

    .packages-header p {
        color: #666;
        font-size: 14px;
    }

    .packages-carousel {
        position: relative;
        overflow: hidden;
        border-radius: var(--radius);
        max-width: 600px;
        margin: 0 auto;
        flex: 1;
        width: 100%;
        display: flex;
        flex-direction: column;
    }

    .packages-carousel-wrapper {
        display: flex;
        transition: transform 0.5s ease-in-out;
        height: 100%;
    }

    .package-item {
        min-width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: var(--radius);
        padding: 50px 30px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .package-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        background: url('<?php echo URL_ROOT; ?>/img/SecurityOfficer.png') no-repeat center;
        background-size: cover;
        filter: grayscale(30%) brightness(0.7);
        z-index: 0;
    }

    .package-item[style*="background-image"]::before {
        display: none;
    }

    .package-item > * {
        position: relative;
        z-index: 1;
    }

    .package-item:hover {
        border-color: var(--accent);
        box-shadow: 0 8px 20px rgba(164, 0, 0, 0.15);
    }

    .package-item.selected {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(164, 0, 0, 0.3);
    }

    .package-item.selected::before {
        filter: grayscale(0%) brightness(0.8);
    }

    .package-name {
        font-size: 20px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 16px;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
        padding: 12px 24px;
        border-radius: 8px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .package-officers {
        font-size: 15px;
        color: #ffffff;
        margin-bottom: 32px;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
        padding: 10px 20px;
        border-radius: 8px;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
    }

    .package-price {
        font-size: 26px;
        font-weight: 700;
        color: #ffffff;
        margin-top: 24px;
        background: rgba(164, 0, 0, 0.7);
        backdrop-filter: blur(10px);
        padding: 14px 28px;
        border-radius: 8px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
    }

    .package-price .material-symbols-outlined {
        color: #ffffff;
    }

    .package-period {
        font-size: 16px;
        color: #ffffff;
        font-weight: 400;
    }

    /* Carousel Navigation */
    .carousel-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.9);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
        z-index: 10;
    }

    .carousel-nav.hidden {
        display: none;
    }

    .carousel-nav:hover {
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        transform: translateY(-50%) scale(1.1);
    }

    .carousel-nav.prev {
        left: 10px;
    }

    .carousel-nav.next {
        right: 10px;
    }

    .carousel-nav .material-symbols-outlined {
        font-size: 24px;
        color: #333;
    }

    .carousel-indicators {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 16px;
        flex-shrink: 0;
        transition: opacity 0.3s ease;
    }

    .carousel-indicators.hidden {
        display: none;
    }

    .carousel-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ccc;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .carousel-dot.active {
        background: var(--accent);
        width: 24px;
        border-radius: 4px;
    }

    /* Right Container - Form/Selection */
    .request-form-container {
        background: white;
        border-radius: var(--radius);
        padding: 32px;
        box-shadow: var(--shadow);
        overflow-y: auto;
        max-height: calc(100vh - 120px);
    }

    .form-header {
        margin-bottom: 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .form-header-content {
        flex: 1;
    }

    .form-header h2 {
        font-size: 26px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 8px;
    }

    .form-header p {
        color: #666;
        font-size: 15px;
    }

    /* Option Cards */
    .option-cards {
        display: grid;
        gap: 16px;
    }

    .option-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 24px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .option-card:hover {
        border-color: var(--accent);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(164, 0, 0, 0.1);
    }

    .option-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .option-icon .material-symbols-outlined {
        color: white;
        font-size: 32px;
    }

    .option-title {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 8px;
    }

    .option-description {
        font-size: 14px;
        color: #666;
    }

    .btn-custom-package {
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(164, 0, 0, 0.2);
        white-space: nowrap;
    }

    .btn-custom-package:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(164, 0, 0, 0.3);
    }

    .btn-custom-package .material-symbols-outlined {
        font-size: 20px;
    }

    /* Sites List */
    .sites-list-container {
        display: none;
    }

    .sites-list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f0f0f0;
    }

    /* Assignment Form */
    .assignment-form-container {
        display: none;
    }

    .assignment-counter {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
    }

    .counter-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .counter-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .counter-icon .material-symbols-outlined {
        color: white;
        font-size: 28px;
    }

    .counter-info h4 {
        margin: 0 0 4px 0;
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
    }

    .counter-info p {
        margin: 0;
        font-size: 13px;
        color: #666;
    }

    .counter-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        margin-top: 16px;
    }

    .counter-btn {
        width: 44px;
        height: 44px;
        border: 2px solid var(--accent);
        background: white;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .counter-btn:hover {
        background: var(--accent);
        transform: scale(1.1);
    }

    .counter-btn:hover .material-symbols-outlined {
        color: white;
    }

    .counter-btn .material-symbols-outlined {
        font-size: 24px;
        color: var(--accent);
        transition: color 0.3s ease;
    }

    .counter-btn:disabled {
        border-color: #ccc;
        cursor: not-allowed;
        opacity: 0.5;
    }

    .counter-btn:disabled:hover {
        background: white;
        transform: none;
    }

    .counter-btn:disabled .material-symbols-outlined {
        color: #ccc;
    }

    .counter-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--accent);
        min-width: 60px;
        text-align: center;
    }

    .btn-submit-assignment {
        background: var(--accent);
        color: white;
        border: none;
        padding: 16px 32px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 24px;
    }

    .btn-submit-assignment:hover {
        background: var(--accent-light);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(164, 0, 0, 0.3);
    }

    .btn-submit-assignment .material-symbols-outlined {
        font-size: 22px;
    }

    .sites-list-header h3 {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .btn-back {
        background: #f0f0f0;
        color: #333;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-back:hover {
        background: #e0e0e0;
    }

    .btn-back .material-symbols-outlined {
        font-size: 18px;
    }

    .sites-grid {
        display: grid;
        gap: 16px;
        max-height: 400px;
        overflow-y: auto;
    }

    .site-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .site-card:hover {
        border-color: var(--accent);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(164, 0, 0, 0.1);
    }

    .site-card.selected {
        border-color: var(--accent);
        background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%);
        box-shadow: 0 0 0 2px rgba(164, 0, 0, 0.2);
    }

    .site-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .site-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
    }

    .site-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .site-icon .material-symbols-outlined {
        color: white;
        font-size: 28px;
    }

    .site-info h4 {
        margin: 0 0 4px 0;
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
    }

    .site-address {
        margin: 0;
        font-size: 13px;
        color: #666;
    }

    .site-stats {
        display: flex;
        gap: 16px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #e0e0e0;
    }

    .site-stat {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #666;
    }

    .site-stat .material-symbols-outlined {
        font-size: 18px;
        color: var(--accent);
    }

    .btn-confirm-site {
        background: var(--accent);
        color: white;
        border: none;
        padding: 14px 28px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 20px;
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-confirm-site:hover {
        background: var(--accent-light);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(164, 0, 0, 0.3);
    }

    .btn-confirm-site:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    .btn-confirm-site .material-symbols-outlined {
        font-size: 20px;
    }

    @media (max-width: 1024px) {
        .requests-container {
            grid-template-columns: 1fr;
        }

        .packages-container {
            margin-bottom: 20px;
        }
    }

    @media (max-width: 768px) {
        .packages-carousel {
            max-width: 100%;
        }

        .package-item {
            padding: 40px 25px;
        }

        .carousel-nav {
            width: 32px;
            height: 32px;
        }

        .carousel-nav .material-symbols-outlined {
            font-size: 20px;
        }

        .carousel-nav.prev {
            left: 5px;
        }

        .carousel-nav.next {
            right: 5px;
        }

        .package-name {
            font-size: 18px;
        }

        .package-officers {
            font-size: 14px;
        }

        .package-price {
            font-size: 22px;
        }
    }

    @media (max-width: 480px) {
        .package-item {
            padding: 30px 20px;
        }

        .carousel-nav {
            width: 28px;
            height: 28px;
        }

        .carousel-nav .material-symbols-outlined {
            font-size: 18px;
        }
    }
</style>

<div class="shell">
    <?php flash('package_success'); ?>
    <?php flash('package_error'); ?>

    <div class="requests-container">
        <!-- Left Container - Packages -->
        <div class="packages-container">
            <div class="packages-header">
                <h2>Security Packages</h2>
                <p>Choose the package that best fits your security needs</p>
            </div>

            <div class="packages-carousel">
                <button class="carousel-nav prev" onclick="changePackageSlide(-1)">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button class="carousel-nav next" onclick="changePackageSlide(1)">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>

                <div class="packages-carousel-wrapper" id="packagesCarouselWrapper">
                    <?php if (!empty($packages)): ?>
                        <?php foreach ($packages as $package): 
                            $isCustomPackage = ($package->package_name === 'Custom Package');
                            // Create URL-friendly package slug
                            $packageSlug = strtolower(str_replace(' ', '', $package->package_name));
                            
                            // Build personnel/pricing display text
                            if ($isCustomPackage) {
                                // For Custom Package, show per-unit pricing
                                $personnelText = [];
                                if (!empty($package->price_per_officer) && $package->price_per_officer > 0) {
                                    $personnelText[] = 'Officer: LKR ' . number_format($package->price_per_officer, 0);
                                }
                                if (!empty($package->price_per_supervisor) && $package->price_per_supervisor > 0) {
                                    $personnelText[] = 'Supervisor: LKR ' . number_format($package->price_per_supervisor, 0);
                                }
                                if (!empty($package->price_per_caretaker) && $package->price_per_caretaker > 0) {
                                    $personnelText[] = 'Caretaker: LKR ' . number_format($package->price_per_caretaker, 0);
                                }
                                $displayPersonnel = !empty($personnelText) ? implode(' • ', $personnelText) : 'Build your own package';
                            } else {
                                // For regular packages, show personnel counts
                                $personnelText = [];
                                if ($package->number_of_officers > 0) {
                                    $personnelText[] = $package->number_of_officers . ' Security Officer' . ($package->number_of_officers != 1 ? 's' : '');
                                }
                                if ($package->number_of_supervisors > 0) {
                                    $personnelText[] = $package->number_of_supervisors . ' Supervisor' . ($package->number_of_supervisors != 1 ? 's' : '');
                                }
                                if ($package->number_of_caretakers > 0) {
                                    $personnelText[] = $package->number_of_caretakers . ' Caretaker' . ($package->number_of_caretakers != 1 ? 's' : '');
                                }
                                $displayPersonnel = !empty($personnelText) ? implode(', ', $personnelText) : 'Security Package';
                            }
                        ?>
                        <div class="package-item" 
                             data-package="<?php echo htmlspecialchars($packageSlug); ?>" 
                             data-package-id="<?php echo $package->id; ?>"
                             data-price="<?php echo $package->package_price; ?>" 
                             data-officers="<?php echo $isCustomPackage ? 'custom' : $package->number_of_officers; ?>"
                             <?php if (!empty($package->background_image)): ?>
                                style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.5)), url('<?php echo URL_ROOT; ?>/uploads/packages/<?php echo $package->background_image; ?>'); background-size: cover; background-position: center;"
                             <?php endif; ?>>
                            <?php if (empty($package->background_image)): ?>
                            <style>
                                .package-item[data-package="<?php echo htmlspecialchars($packageSlug); ?>"]::before {
                                    background: url('<?php echo URL_ROOT; ?>/img/SecurityOfficer.png') no-repeat center;
                                    background-size: cover;
                                    filter: grayscale(30%) brightness(0.7);
                                }
                            </style>
                            <?php endif; ?>
                            <div class="package-name"><?php echo htmlspecialchars($package->package_name); ?></div>
                            <div class="package-officers"><?php echo $displayPersonnel; ?></div>
                            <div class="package-price">
                                <?php if ($isCustomPackage): ?>
                                    <span class="material-symbols-outlined" style="vertical-align: middle;">settings</span>
                                    Customizable
                                <?php else: ?>
                                    LKR <?php echo number_format($package->package_price, 0); ?><span class="package-period">/month</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="package-item">
                            <div class="package-name">No Packages Available</div>
                            <div class="package-officers">Please contact admin</div>
                            <div class="package-price">-</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="carousel-indicators" id="packageCarouselIndicators">
                <?php if (!empty($packages)): ?>
                    <?php foreach ($packages as $index => $package): ?>
                        <span class="carousel-dot <?php echo $index === 0 ? 'active' : ''; ?>" onclick="goToPackageSlide(<?php echo $index; ?>)"></span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Container - Form -->
        <div class="request-form-container">
            <div class="form-header">
                <div class="form-header-content">
                    <h2 id="formHeaderTitle">Request Security Service</h2>
                    <p id="formHeaderDesc">Select a package and choose your deployment option</p>
                </div>
                <?php 
                // Check if Custom Package exists
                $hasCustomPackage = false;
                if (!empty($packages)) {
                    foreach ($packages as $pkg) {
                        if ($pkg->package_name === 'Custom Package') {
                            $hasCustomPackage = true;
                            break;
                        }
                    }
                }
                if ($hasCustomPackage): 
                ?>
                <button class="btn-custom-package" onclick="selectCustomPackage()">
                    <span class="material-symbols-outlined">settings</span>
                    Custom Package
                </button>
                <?php endif; ?>
            </div>

            <div id="packageFormContent">
                <div id="emptyState" style="text-align: center; padding: 60px 20px; color: #999; margin-top: 100px;">
                    <span class="material-symbols-outlined" style="font-size: 64px; color: #ddd;">touch_app</span>
                    <p style="margin-top: 16px; font-size: 16px;">Please select a package from the left to continue</p>
                </div>

                <div id="optionCards" class="option-cards" style="display: none;">
                    <div class="option-card" id="addToExistingSite">
                        <div class="option-icon">
                            <span class="material-symbols-outlined">add_location</span>
                        </div>
                        <div class="option-title">Add to Existing Site</div>
                        <div class="option-description">Add more officers to one of your current sites</div>
                    </div>

                    <div class="option-card" id="createNewSite">
                        <div class="option-icon">
                            <span class="material-symbols-outlined">add_business</span>
                        </div>
                        <div class="option-title">Create New Site</div>
                        <div class="option-description">Register a new location for security services</div>
                    </div>
                </div>

                <!-- Sites List -->
                <div id="sitesListContainer" class="sites-list-container">
                    <div class="sites-list-header">
                        <h3>Select a Site</h3>
                        <button class="btn-back" onclick="backToOptions()">
                            <span class="material-symbols-outlined">arrow_back</span>
                            Back
                        </button>
                    </div>
                    <div id="sitesGrid" class="sites-grid">
                        <!-- Sites will be loaded here -->
                    </div>
                    <button id="confirmSiteBtn" class="btn-confirm-site" onclick="confirmSiteSelection()" disabled>
                        <span class="material-symbols-outlined">check_circle</span>
                        Continue with Selected Site
                    </button>
                </div>

                <!-- Assignment Form -->
                <div id="assignmentFormContainer" class="assignment-form-container">
                    <div class="sites-list-header">
                        <h3>Add Security Personnel</h3>
                        <button class="btn-back" onclick="backToSitesList()">
                            <span class="material-symbols-outlined">arrow_back</span>
                            Back
                        </button>
                    </div>

                    <!-- Officers Counter -->
                    <div class="assignment-counter">
                        <div class="counter-header">
                            <div class="counter-icon">
                                <span class="material-symbols-outlined">badge</span>
                            </div>
                            <div class="counter-info">
                                <h4>Security Officers</h4>
                                <p>Assign officers to this site</p>
                            </div>
                        </div>
                        <div class="counter-controls">
                            <button class="counter-btn" onclick="decrementOfficers()" id="decrementOfficersBtn">
                                <span class="material-symbols-outlined">remove</span>
                            </button>
                            <div class="counter-value" id="officersCount">0</div>
                            <button class="counter-btn" onclick="incrementOfficers()">
                                <span class="material-symbols-outlined">add</span>
                            </button>
                        </div>
                    </div>

                    <!-- Caretakers Counter -->
                    <div class="assignment-counter">
                        <div class="counter-header">
                            <div class="counter-icon">
                                <span class="material-symbols-outlined">supervised_user_circle</span>
                            </div>
                            <div class="counter-info">
                                <h4>Caretakers</h4>
                                <p>Assign caretakers to this site</p>
                            </div>
                        </div>
                        <div class="counter-controls">
                            <button class="counter-btn" onclick="decrementCaretakers()" id="decrementCaretakersBtn">
                                <span class="material-symbols-outlined">remove</span>
                            </button>
                            <div class="counter-value" id="caretakersCount">0</div>
                            <button class="counter-btn" onclick="incrementCaretakers()">
                                <span class="material-symbols-outlined">add</span>
                            </button>
                        </div>
                    </div>

                    <button class="btn-submit-assignment" onclick="submitAssignment()">
                        <span class="material-symbols-outlined">check_circle</span>
                        Proceed
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const packageItems = document.querySelectorAll('.package-item');
    const addToExistingBtn = document.getElementById('addToExistingSite');
    const createNewSiteBtn = document.getElementById('createNewSite');
    
    let selectedPackage = null;
    let currentPackageSlide = 0;
    let packageAutoSlideInterval;
    let isPackageSelected = false;
    let isDeploymentOptionSelected = false;
    const totalPackages = packageItems.length;
    const customPackageIndex = Array.from(packageItems).findIndex(item => item.dataset.officers === 'custom');

    // Carousel Functions
    function updatePackageCarousel() {
        const wrapper = document.getElementById('packagesCarouselWrapper');
        if (wrapper) {
            wrapper.style.transform = `translateX(-${currentPackageSlide * 100}%)`;
            document.querySelectorAll('.carousel-dot').forEach((dot, index) => {
                dot.classList.toggle('active', index === currentPackageSlide);
            });
        }
    }

    window.changePackageSlide = function(direction) {
        if (isDeploymentOptionSelected) return;
        currentPackageSlide = (currentPackageSlide + direction + totalPackages) % totalPackages;
        updatePackageCarousel();
        
        // Clear selection when navigating away
        if (isPackageSelected) {
            packageItems.forEach(p => p.classList.remove('selected'));
            selectedPackage = null;
            isPackageSelected = false;
            isDeploymentOptionSelected = false;
            
            // Re-enable package container
            const packagesContainer = document.querySelector('.packages-container');
            if (packagesContainer) {
                packagesContainer.classList.remove('disabled');
            }
            
            // Show arrow buttons and indicators
            document.querySelectorAll('.carousel-nav').forEach(btn => btn.classList.remove('hidden'));
            document.getElementById('packageCarouselIndicators').classList.remove('hidden');
            
            // Show custom package button again
            const customPackageBtn = document.querySelector('.btn-custom-package');
            if (customPackageBtn) {
                customPackageBtn.style.display = 'inline-flex';
            }
            
            // Reset right container
            const emptyState = document.getElementById('emptyState');
            const optionCards = document.getElementById('optionCards');
            const sitesListContainer = document.getElementById('sitesListContainer');
            const assignmentFormContainer = document.getElementById('assignmentFormContainer');
            const headerTitle = document.getElementById('formHeaderTitle');
            const headerDesc = document.getElementById('formHeaderDesc');
            
            if (emptyState && optionCards) {
                emptyState.style.display = 'block';
                optionCards.style.display = 'none';
                sitesListContainer.style.display = 'none';
                assignmentFormContainer.style.display = 'none';
                headerTitle.textContent = 'Request Security Service';
                headerDesc.textContent = 'Select a package and choose your deployment option';
            }
        }
        
        if (!isPackageSelected) {
            resetPackageAutoSlide();
        }
    }

    window.goToPackageSlide = function(index) {
        if (isDeploymentOptionSelected) return;
        currentPackageSlide = index;
        updatePackageCarousel();
        
        // Clear selection when navigating away
        if (isPackageSelected) {
            packageItems.forEach(p => p.classList.remove('selected'));
            selectedPackage = null;
            isPackageSelected = false;
            isDeploymentOptionSelected = false;
            
            // Re-enable package container
            const packagesContainer = document.querySelector('.packages-container');
            if (packagesContainer) {
                packagesContainer.classList.remove('disabled');
            }
            
            // Show arrow buttons and indicators
            document.querySelectorAll('.carousel-nav').forEach(btn => btn.classList.remove('hidden'));
            document.getElementById('packageCarouselIndicators').classList.remove('hidden');
            
            // Show custom package button again
            const customPackageBtn = document.querySelector('.btn-custom-package');
            if (customPackageBtn) {
                customPackageBtn.style.display = 'inline-flex';
            }
            
            // Reset right container
            const emptyState = document.getElementById('emptyState');
            const optionCards = document.getElementById('optionCards');
            const sitesListContainer = document.getElementById('sitesListContainer');
            const assignmentFormContainer = document.getElementById('assignmentFormContainer');
            const headerTitle = document.getElementById('formHeaderTitle');
            const headerDesc = document.getElementById('formHeaderDesc');
            
            if (emptyState && optionCards) {
                emptyState.style.display = 'block';
                optionCards.style.display = 'none';
                sitesListContainer.style.display = 'none';
                assignmentFormContainer.style.display = 'none';
                headerTitle.textContent = 'Request Security Service';
                headerDesc.textContent = 'Select a package and choose your deployment option';
            }
        }
        
        if (!isPackageSelected) {
            resetPackageAutoSlide();
        }
    }

    function autoPackageSlide() {
        if (isPackageSelected) return;
        currentPackageSlide = (currentPackageSlide + 1) % totalPackages;
        updatePackageCarousel();
    }

    function resetPackageAutoSlide() {
        if (isPackageSelected) return;
        clearInterval(packageAutoSlideInterval);
        packageAutoSlideInterval = setInterval(autoPackageSlide, 5000);
    }

    // Initialize auto-slide
    packageAutoSlideInterval = setInterval(autoPackageSlide, 5000);

    // Pause on hover
    const packagesContainer = document.querySelector('.packages-container');
    if (packagesContainer) {
        packagesContainer.addEventListener('mouseenter', () => {
            if (!isPackageSelected) clearInterval(packageAutoSlideInterval);
        });
        packagesContainer.addEventListener('mouseleave', resetPackageAutoSlide);
    }

    // Keyboard navigation
    document.addEventListener('keydown', e => {
        if (isDeploymentOptionSelected) return;
        if (e.key === 'ArrowLeft') changePackageSlide(-1);
        if (e.key === 'ArrowRight') changePackageSlide(1);
    });

    // Package selection
    packageItems.forEach(item => {
        item.addEventListener('click', function() {
            if (isDeploymentOptionSelected) return;
            // Remove previous selection
            packageItems.forEach(p => p.classList.remove('selected'));
            
            // Add selection
            this.classList.add('selected');
            
            selectedPackage = {
                name: this.dataset.package,
                price: this.dataset.price,
                officers: this.dataset.officers
            };

            // Stop auto-slide permanently
            isPackageSelected = true;
            clearInterval(packageAutoSlideInterval);

            // Hide custom package button when any package is selected
            const customPackageBtn = document.querySelector('.btn-custom-package');
            if (customPackageBtn) {
                customPackageBtn.style.display = 'none';
            }

            // Show options in right container
            const emptyState = document.getElementById('emptyState');
            const optionCards = document.getElementById('optionCards');
            const headerTitle = document.getElementById('formHeaderTitle');
            const headerDesc = document.getElementById('formHeaderDesc');
            
            if (emptyState && optionCards) {
                emptyState.style.display = 'none';
                optionCards.style.display = 'grid';
                
                // Update header
                const packageName = this.querySelector('.package-name').textContent;
                headerTitle.textContent = packageName;
                headerDesc.textContent = 'Choose your deployment option';
            }
        });
    });

    // Add to existing site
    addToExistingBtn.addEventListener('click', function() {
        if (selectedPackage) {
            showSitesList();
        }
    });

    // Show sites list
    function showSitesList() {
        const optionCards = document.getElementById('optionCards');
        const sitesListContainer = document.getElementById('sitesListContainer');
        
        optionCards.style.display = 'none';
        sitesListContainer.style.display = 'block';
        
        // Update header
        const headerDesc = document.getElementById('formHeaderDesc');
        headerDesc.textContent = 'Select a site to add personnel';
        
        // Disable package container and hide navigation
        isDeploymentOptionSelected = true;
        const packagesContainer = document.querySelector('.packages-container');
        if (packagesContainer) {
            packagesContainer.classList.add('disabled');
        }
        
        // Hide arrow buttons and indicators
        document.querySelectorAll('.carousel-nav').forEach(btn => btn.classList.add('hidden'));
        document.getElementById('packageCarouselIndicators').classList.add('hidden');
        
        // Fetch and display sites
        fetchClientSites();
    }

    // Back to options
    window.backToOptions = function() {
        const optionCards = document.getElementById('optionCards');
        const sitesListContainer = document.getElementById('sitesListContainer');
        const assignmentFormContainer = document.getElementById('assignmentFormContainer');
        
        optionCards.style.display = 'grid';
        sitesListContainer.style.display = 'none';
        assignmentFormContainer.style.display = 'none';
        
        // Update header
        const headerDesc = document.getElementById('formHeaderDesc');
        headerDesc.textContent = 'Choose your deployment option';
        
        // Re-enable package container and show navigation
        isDeploymentOptionSelected = false;
        const packagesContainer = document.querySelector('.packages-container');
        if (packagesContainer) {
            packagesContainer.classList.remove('disabled');
        }
        
        // Show arrow buttons and indicators
        document.querySelectorAll('.carousel-nav').forEach(btn => btn.classList.remove('hidden'));
        document.getElementById('packageCarouselIndicators').classList.remove('hidden');
        
        // Clear selection
        selectedSiteId = null;
        document.getElementById('confirmSiteBtn').disabled = true;
    };

    // Fetch client sites
    let selectedSiteId = null;
    
    // Counter management
    let officersCount = 0;
    let caretakersCount = 0;

    // Initialize counter displays
    function updateCounterDisplays() {
        document.getElementById('officersCount').textContent = officersCount;
        document.getElementById('caretakersCount').textContent = caretakersCount;
        
        // Enable/disable decrement buttons
        document.getElementById('decrementOfficersBtn').disabled = officersCount === 0;
        document.getElementById('decrementCaretakersBtn').disabled = caretakersCount === 0;
    }

    // Initialize on page load
    updateCounterDisplays();
    
    function fetchClientSites() {
        fetch('<?php echo URL_ROOT; ?>/client/getSites')
            .then(response => response.json())
            .then(data => {
                displaySites(data.sites || []);
            })
            .catch(error => {
                console.error('Error fetching sites:', error);
                document.getElementById('sitesGrid').innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #999;">
                        <span class="material-symbols-outlined" style="font-size: 48px; color: #ddd;">error</span>
                        <p style="margin-top: 12px;">Unable to load sites. Please try again.</p>
                    </div>
                `;
            });
    }

    function displaySites(sites) {
        const sitesGrid = document.getElementById('sitesGrid');
        
        if (sites.length === 0) {
            sitesGrid.innerHTML = `
                <div style="text-align: center; padding: 40px; color: #999;">
                    <span class="material-symbols-outlined" style="font-size: 48px; color: #ddd;">location_off</span>
                    <p style="margin-top: 12px;">You don't have any sites yet.</p>
                    <p style="font-size: 14px;">Please create a new site to continue.</p>
                </div>
            `;
            return;
        }
        
        sitesGrid.innerHTML = sites.map(site => `
            <div class="site-card" data-site-id="${site.id}" onclick="selectSite(${site.id})">
                <div class="site-card-header">
                    <div class="site-icon">
                        ${site.image 
                            ? `<img src="<?php echo URL_ROOT; ?>/uploads/siteImages/${site.image}" alt="${site.site_name || 'Site'}" />` 
                            : `<span class="material-symbols-outlined">location_city</span>`
                        }
                    </div>
                    <div class="site-info">
                        <h4>${site.site_name || 'Unnamed Site'}</h4>
                        <p class="site-address">${site.site_address || site.city || 'No address provided'}</p>
                    </div>
                </div>
                <div class="site-stats">
                    <div class="site-stat">
                        <span class="material-symbols-outlined">badge</span>
                        <span>${site.assigned_officers || 0} Officers</span>
                    </div>
                    <div class="site-stat">
                        <span class="material-symbols-outlined">supervised_user_circle</span>
                        <span>${site.assigned_supervisors || 0} Supervisors</span>
                    </div>
                </div>
            </div>
        `).join('');
    }

    window.selectSite = function(siteId) {
        selectedSiteId = siteId;
        
        // Update UI
        document.querySelectorAll('.site-card').forEach(card => {
            card.classList.remove('selected');
        });
        document.querySelector(`[data-site-id="${siteId}"]`).classList.add('selected');
        
        // Enable confirm button
        document.getElementById('confirmSiteBtn').disabled = false;
    };

    window.confirmSiteSelection = function() {
        if (selectedPackage && selectedSiteId) {
            // Only custom package can add personnel via assignment form
            if (selectedPackage.name === 'custom') {
                // Hide sites list, show assignment form
                const sitesListContainer = document.getElementById('sitesListContainer');
                const assignmentFormContainer = document.getElementById('assignmentFormContainer');
                
                sitesListContainer.style.display = 'none';
                assignmentFormContainer.style.display = 'block';
                
                // Update header
                const headerTitle = document.getElementById('formHeaderTitle');
                const headerDesc = document.getElementById('formHeaderDesc');
                const selectedSiteCard = document.querySelector(`[data-site-id="${selectedSiteId}"]`);
                const siteName = selectedSiteCard ? selectedSiteCard.querySelector('.site-info h4').textContent : 'Selected Site';
                
                headerTitle.textContent = siteName;
                headerDesc.textContent = 'Choose the number of personnel to assign';
            } else {
                // For predefined packages, submit directly with package's officer count
                const params = new URLSearchParams({
                    mode: 'existing',
                    site_id: selectedSiteId,
                    officers: selectedPackage.officers,
                    caretakers: 0
                });
                
                window.location.href = `<?php echo URL_ROOT; ?>/client/${selectedPackage.name}Package?${params.toString()}`;
            }
        }
    };

    // Back to sites list
    window.backToSitesList = function() {
        const sitesListContainer = document.getElementById('sitesListContainer');
        const assignmentFormContainer = document.getElementById('assignmentFormContainer');
        const optionCards = document.getElementById('optionCards');
        
        sitesListContainer.style.display = 'block';
        assignmentFormContainer.style.display = 'none';
        optionCards.style.display = 'none';
        
        // Package container remains disabled - we're still in deployment flow
        
        // Reset counters
        officersCount = 0;
        caretakersCount = 0;
        updateCounterDisplays();
        
        // Update header back to site selection
        const headerTitle = document.getElementById('formHeaderTitle');
        const headerDesc = document.getElementById('formHeaderDesc');
        const packageName = document.querySelector('.package-item.selected .package-name').textContent;
        
        headerTitle.textContent = packageName;
        headerDesc.textContent = 'Select a site to add personnel';
    };

    window.incrementOfficers = function() {
        officersCount++;
        updateCounterDisplays();
    };

    window.decrementOfficers = function() {
        if (officersCount > 0) {
            officersCount--;
            updateCounterDisplays();
        }
    };

    window.incrementCaretakers = function() {
        caretakersCount++;
        updateCounterDisplays();
    };

    window.decrementCaretakers = function() {
        if (caretakersCount > 0) {
            caretakersCount--;
            updateCounterDisplays();
        }
    };

    window.submitAssignment = function() {
        if (selectedPackage && selectedSiteId) {
            // Redirect with all parameters
            const params = new URLSearchParams({
                mode: 'existing',
                site_id: selectedSiteId,
                officers: officersCount,
                caretakers: caretakersCount
            });
            
            window.location.href = `<?php echo URL_ROOT; ?>/client/${selectedPackage.name}Package?${params.toString()}`;
        }
    };

    // Create new site
    createNewSiteBtn.addEventListener('click', function() {
        if (selectedPackage) {
            // Disable package container before redirect
            isDeploymentOptionSelected = true;
            const packagesContainer = document.querySelector('.packages-container');
            if (packagesContainer) {
                packagesContainer.classList.add('disabled');
            }
            
            // Hide arrow buttons and indicators
            document.querySelectorAll('.carousel-nav').forEach(btn => btn.classList.add('hidden'));
            document.getElementById('packageCarouselIndicators').classList.add('hidden');
            
            // Redirect to new site creation page
            window.location.href = `<?php echo URL_ROOT; ?>/client/${selectedPackage.name}Package?mode=new`;
        }
    });

    // Custom package button
    window.selectCustomPackage = function() {
        // Navigate to custom package slide
        if (customPackageIndex !== -1) {
            currentPackageSlide = customPackageIndex;
        } else {
            // If no custom package found, default to last package
            currentPackageSlide = totalPackages - 1;
        }
        updatePackageCarousel();
        
        // Select the custom package
        packageItems.forEach(p => p.classList.remove('selected'));
        const customPackageItem = customPackageIndex !== -1 ? packageItems[customPackageIndex] : packageItems[totalPackages - 1];
        if (customPackageItem) {
            customPackageItem.classList.add('selected');
            
            selectedPackage = {
                name: customPackageItem.dataset.package,
                price: customPackageItem.dataset.price,
                officers: customPackageItem.dataset.officers
            };
        } else {
            selectedPackage = {
                name: 'custom',
                price: '0',
                officers: 'custom'
            };
        }
        
        // Stop auto-slide
        isPackageSelected = true;
        clearInterval(packageAutoSlideInterval);
        
        // Hide the custom package button
        const customPackageBtn = document.querySelector('.btn-custom-package');
        if (customPackageBtn) {
            customPackageBtn.style.display = 'none';
        }
        
        // Show options in right container
        const emptyState = document.getElementById('emptyState');
        const optionCards = document.getElementById('optionCards');
        const headerTitle = document.getElementById('formHeaderTitle');
        const headerDesc = document.getElementById('formHeaderDesc');
        
        if (emptyState && optionCards && customPackageItem) {
            emptyState.style.display = 'none';
            optionCards.style.display = 'grid';
            
            // Update header with actual package name
            const packageName = customPackageItem.querySelector('.package-name') ? customPackageItem.querySelector('.package-name').textContent : 'Custom Package';
            headerTitle.textContent = packageName;
            headerDesc.textContent = 'Choose your deployment option';
        }
    };
});
</script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
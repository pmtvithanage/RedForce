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
        margin-bottom: 12px;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
        padding: 12px 24px;
        border-radius: 8px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .package-description {
        font-size: 13px;
        color: #ffffff;
        margin-bottom: 16px;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
        padding: 8px 16px;
        border-radius: 6px;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        line-height: 1.5;
        max-width: 90%;
        text-align: center;
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

    /* New Site Form */
    .new-site-form-container {
        display: none;
    }

    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
    }

    .site-form-field {
        margin-bottom: 20px;
    }

    .site-form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #1a1a1a;
        font-size: 14px;
    }

    .site-form-input {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .site-form-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(164, 0, 0, 0.1);
    }

    .site-photo-upload {
        text-align: center;
        margin-bottom: 25px;
    }

    .site-photo-frame {
        width: 180px;
        height: 180px;
        border: 2px dashed #e0e0e0;
        border-radius: 12px;
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    .site-photo-frame:hover {
        border-color: var(--accent);
        background-color: #fff;
    }

    .site-photo-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .btn-add-photo {
        background: var(--accent);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .btn-add-photo:hover {
        background: var(--accent-light);
        transform: translateY(-1px);
    }

    .btn-remove-photo {
        background: #666;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-remove-photo:hover {
        background: #555;
    }

    .form-navigation {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
    }

    .btn-form-nav {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-form-next,
    .btn-form-submit {
        background: var(--accent);
        color: white;
        margin-left: auto;
    }

    .btn-form-next:hover,
    .btn-form-submit:hover {
        background: var(--accent-light);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(164, 0, 0, 0.3);
    }

    .btn-form-prev {
        background: #f0f0f0;
        color: #333;
    }

    .btn-form-prev:hover {
        background: #e0e0e0;
    }

    .site-map-container {
        margin-top: 20px;
    }

    .site-map-instructions {
        color: #666;
        font-size: 13px;
        margin-bottom: 12px;
        padding: 12px;
        background-color: #f8f9fa;
        border-radius: 6px;
        border-left: 3px solid var(--accent);
    }

    #siteMap {
        width: 100%;
        height: 350px;
        border-radius: 8px;
        border: 2px solid #e0e0e0;
        margin-top: 10px;
    }

    .step-indicator {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .step-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #999;
        font-size: 14px;
        font-weight: 600;
    }

    .step-number {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #e0e0e0;
        color: #666;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .step-item.active {
        color: var(--accent);
    }

    .step-item.active .step-number {
        background: var(--accent);
        color: white;
    }

    .step-item.completed .step-number {
        background: #4caf50;
        color: white;
    }

    .step-divider {
        width: 40px;
        height: 2px;
        background: #e0e0e0;
    }

    .error-message {
        display: none;
        background: #fff5f5;
        border-left: 3px solid #ff5252;
        padding: 12px 16px;
        margin: 16px 0;
        border-radius: 6px;
        color: #c41e1e;
        font-size: 14px;
        font-weight: 500;
        animation: slideDown 0.3s ease;
    }

    .error-message.show {
        display: block;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
                             data-package-name="<?php echo htmlspecialchars($package->package_name); ?>"
                             data-price="<?php echo $package->package_price; ?>" 
                             data-officers="<?php echo $isCustomPackage ? 'custom' : $package->number_of_officers; ?>"
                                      data-supervisors="<?php echo $package->number_of_supervisors ?? 0; ?>"
                                      data-caretakers="<?php echo $package->number_of_caretakers ?? 0; ?>"
                             data-price-officer="<?php echo $package->price_per_officer ?? 0; ?>"
                             data-price-supervisor="<?php echo $package->price_per_supervisor ?? 0; ?>"
                             data-price-caretaker="<?php echo $package->price_per_caretaker ?? 0; ?>"
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
                            <?php if (!empty($package->description)): ?>
                            <div class="package-description"><?php echo htmlspecialchars($package->description); ?></div>
                            <?php endif; ?>
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

                    <!-- Error Message Container -->
                    <span class="error-message" id="assignmentErrorMessage"></span>

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

                    <!-- Supervisors Counter -->
                    <div class="assignment-counter">
                        <div class="counter-header">
                            <div class="counter-icon">
                                <span class="material-symbols-outlined">shield_person</span>
                            </div>
                            <div class="counter-info">
                                <h4>Supervisors</h4>
                                <p id="supervisorRequirement">Required: 1 supervisor per 5 officers</p>
                            </div>
                        </div>
                        <div class="counter-controls">
                            <button class="counter-btn" onclick="decrementSupervisors()" id="decrementSupervisorsBtn">
                                <span class="material-symbols-outlined">remove</span>
                            </button>
                            <div class="counter-value" id="supervisorsCount">0</div>
                            <button class="counter-btn" onclick="incrementSupervisors()">
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

                    <!-- Pricing Summary -->
                    <div class="assignment-counter" id="pricingSummary" style="background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%); border-color: var(--accent);">
                        <div class="counter-header">
                            <div class="counter-icon">
                                <span class="material-symbols-outlined">receipt_long</span>
                            </div>
                            <div class="counter-info">
                                <h4>Monthly Cost Summary</h4>
                                <p>Total amount to be paid</p>
                            </div>
                        </div>
                        <div style="margin-top: 16px; padding: 12px; background: white; border-radius: 8px;">
                            <div id="priceBreakdown" style="font-size: 14px; color: #666; line-height: 1.8;"></div>
                            <div style="margin-top: 12px; padding-top: 12px; border-top: 2px solid var(--accent); display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-weight: 700; font-size: 16px; color: #1a1a1a;">Total Amount:</span>
                                <span id="totalAmount" style="font-weight: 700; font-size: 24px; color: var(--accent);">LKR 0</span>
                            </div>
                        </div>
                    </div>

                    <button class="btn-submit-assignment" onclick="submitAssignment()">
                        <span class="material-symbols-outlined">check_circle</span>
                        Proceed
                    </button>
                </div>

                <!-- New Site Form -->
                <div id="newSiteFormContainer" class="new-site-form-container">
                    <div class="sites-list-header">
                        <h3>Create New Site</h3>
                        <button class="btn-back" onclick="backToOptionsFromNewSite()">
                            <span class="material-symbols-outlined">arrow_back</span>
                            Back
                        </button>
                    </div>

                    <!-- Step Indicator -->
                    <div class="step-indicator">
                        <div class="step-item active" id="step1Indicator">
                            <div class="step-number">1</div>
                            <span>Site Details</span>
                        </div>
                        <div class="step-divider"></div>
                        <div class="step-item" id="step2Indicator">
                            <div class="step-number">2</div>
                            <span>Location</span>
                        </div>
                    </div>

                    <!-- Step 1: Site Details -->
                    <div class="form-step active" id="step1">
                        <!-- Error Message Container -->
                        <span class="error-message" id="step1ErrorMessage"></span>
                        
                        <!-- Photo Upload -->
                        <div class="site-photo-upload">
                            <label class="site-form-label" style="text-align: center; margin-bottom: 10px;">Site Photo *</label>
                            <div class="site-photo-frame" id="sitePhotoFrame">
                                <img src="<?php echo URL_ROOT; ?>/img/photo.png" 
                                     alt="Site image" 
                                     id="siteImagePreview" />
                            </div>
                            <button type="button" class="btn-add-photo" id="addSitePhotoBtn" onclick="document.getElementById('siteImageInput').click()">
                                <span class="material-symbols-outlined" style="font-size: 18px;">add_photo_alternate</span>
                                Add Image
                            </button>
                            <button type="button" class="btn-remove-photo" id="removeSitePhotoBtn" style="display: none;" onclick="removeSiteImage()">
                                <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                                Remove
                            </button>
                            <input type="file" id="siteImageInput" accept="image/*" style="display: none;" />
                            <small style="color: #666; font-size: 12px; margin-top: 10px; display: block; text-align: center;">
                                <span class="material-symbols-outlined" style="font-size: 14px; vertical-align: middle;">photo_camera</span>
                                Upload a clear photo of the site (JPG, JPEG, or PNG)
                            </small>
                        </div>

                        <!-- Site Name -->
                        <div class="site-form-field">
                            <label class="site-form-label">Site Name *</label>
                            <input type="text" 
                                   class="site-form-input" 
                                   id="newSiteName" 
                                   placeholder="Enter site name" 
                                   required />
                        </div>

                        <!-- District -->
                        <div class="site-form-field">
                            <label class="site-form-label">District *</label>
                            <input type="text" 
                                   class="site-form-input" 
                                   id="district" 
                                   name="district"
                                   placeholder="Enter district" 
                                   required />
                        </div>

                        <!-- City -->
                        <div class="site-form-field" id="city-field" style="display: none;">
                            <label class="site-form-label">City</label>
                            <input type="text" 
                                   class="site-form-input" 
                                   id="city" 
                                   name="city"
                                   placeholder="Enter city" />
                        </div>

                        <!-- Phone Number -->
                        <div class="site-form-field">
                            <label class="site-form-label">Phone Number *</label>
                            <input type="tel" 
                                   class="site-form-input" 
                                   id="newSitePhone" 
                                   placeholder="Enter 10 digit phone number" 
                                   maxlength="10"
                                   pattern="[0-9]{10}"
                                   required />
                            <small style="color: #666; font-size: 12px; margin-top: 5px; display: block;">
                                <span class="material-symbols-outlined" style="font-size: 14px; vertical-align: middle;">phone</span>
                                Enter 10 digits only (e.g., 0771234567)
                            </small>
                        </div>

                        <!-- Navigation -->
                        <div class="form-navigation">
                            <button type="button" class="btn-form-nav btn-form-next" onclick="goToStep2()">
                                Next
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Location -->
                    <div class="form-step" id="step2">
                        <!-- Error Message Container -->
                        <span class="error-message" id="step2ErrorMessage"></span>
                        
                        <div class="site-map-container">
                            <div class="site-map-instructions">
                                <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle; margin-right: 6px;">location_on</span>
                                Search for a location below or click on the map to pin the exact site location. The address will be automatically detected.
                            </div>
                            
                            <!-- Location Search Box -->
                            <div class="location-search-box" style="margin-bottom: 15px; position: relative;">
                                <span class="material-symbols-outlined" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; font-size: 20px; pointer-events: none;">search</span>
                                <input type="text" 
                                       id="newSiteLocationSearch" 
                                       placeholder="Search for places, addresses, or landmarks..." 
                                       autocomplete="off"
                                       style="width: 100%; padding: 12px 45px 12px 40px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; transition: all 0.3s ease;">
                            </div>
                            
                            <div id="siteMap"></div>
                            <input type="hidden" id="newSiteLatitude" />
                            <input type="hidden" id="newSiteLongitude" />
                        </div>

                        <!-- Location Address -->
                        <div class="site-form-field" style="margin-top: 20px;">
                            <label class="site-form-label">Location Address *</label>
                            <input type="text" 
                                   class="site-form-input" 
                                   id="newSiteAddress" 
                                   placeholder="Search location above or click on map - address will auto-fill" 
                                   required />
                            <small style="color: #666; font-size: 12px; margin-top: 5px; display: block;">
                                <span class="material-symbols-outlined" style="font-size: 14px; vertical-align: middle;">location_on</span>
                                The address is automatically detected from the map location. You can edit it if needed.
                            </small>
                        </div>

                        <!-- Navigation -->
                        <div class="form-navigation">
                            <button type="button" class="btn-form-nav btn-form-prev" onclick="goToStep1()">
                                <span class="material-symbols-outlined">arrow_back</span>
                                Back
                            </button>
                            <button type="button" class="btn-form-nav btn-form-submit" onclick="submitNewSiteWithPackage()">
                                <span class="material-symbols-outlined">check_circle</span>
                                Create Site & Continue
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Package Confirmation (for non-custom packages) -->
                <div id="packageConfirmationContainer" class="assignment-form-container" style="display: none;">
                    <div class="sites-list-header">
                        <h3>Confirm Your Request</h3>
                        <button class="btn-back" onclick="backFromConfirmation()">
                            <span class="material-symbols-outlined">arrow_back</span>
                            Back
                        </button>
                    </div>

                    <!-- Package Summary -->
                    <div class="assignment-counter" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border-color: var(--accent);">
                        <div class="counter-header">
                            <div class="counter-icon">
                                <span class="material-symbols-outlined">inventory_2</span>
                            </div>
                            <div class="counter-info">
                                <h4 id="confirmPackageName">Package Name</h4>
                                <p id="confirmPackageDetails">Package details</p>
                            </div>
                        </div>
                    </div>

                    <!-- Site Information -->
                    <div class="assignment-counter">
                        <div class="counter-header">
                            <div class="counter-icon">
                                <span class="material-symbols-outlined">location_on</span>
                            </div>
                            <div class="counter-info">
                                <h4 id="confirmSiteName">Site Name</h4>
                                <p id="confirmSiteAddress">Site address</p>
                            </div>
                        </div>
                    </div>

                    <!-- Price Summary -->
                    <div class="assignment-counter" style="background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%); border-color: var(--accent);">
                        <div class="counter-header">
                            <div class="counter-icon">
                                <span class="material-symbols-outlined">payments</span>
                            </div>
                            <div class="counter-info">
                                <h4>Monthly Cost</h4>
                                <p>Total amount to be paid</p>
                            </div>
                        </div>
                        <div style="margin-top: 16px; padding: 20px; background: white; border-radius: 8px; text-align: center;">
                            <div id="confirmPrice" style="font-weight: 700; font-size: 32px; color: var(--accent);">LKR 0</div>
                            <div style="font-size: 14px; color: #666; margin-top: 8px;">per month</div>
                        </div>
                    </div>

                    <button class="btn-submit-assignment" onclick="proceedWithPackage()">
                        <span class="material-symbols-outlined">check_circle</span>
                        Proceed with Request
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
                fullName: this.dataset.packageName,
                price: this.dataset.price,
                officers: this.dataset.officers,
                supervisors: this.dataset.supervisors || 0,
                caretakers: this.dataset.caretakers || 0,
                priceOfficer: this.dataset.priceOfficer || 0,
                priceSupervisor: this.dataset.priceSupervisor || 0,
                priceCaretaker: this.dataset.priceCaretaker || 0
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
            const createNewSiteCard = document.getElementById('createNewSite');
            
            if (emptyState && optionCards) {
                emptyState.style.display = 'none';
                optionCards.style.display = 'grid';
                
                // Update header
                const packageName = this.querySelector('.package-name').textContent;
                headerTitle.textContent = packageName;
                headerDesc.textContent = 'Choose your deployment option';
                
                // Hide "Create New Site" button for packages with "extra" in the name
                if (createNewSiteCard) {
                    const packageFullName = selectedPackage.fullName || '';
                    if (packageFullName.toLowerCase().includes('extra')) {
                        createNewSiteCard.style.display = 'none';
                    } else {
                        createNewSiteCard.style.display = 'block';
                    }
                }
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
        headerDesc.textContent = 'Select a site to adjust personnel';
        
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
        const packageConfirmationContainer = document.getElementById('packageConfirmationContainer');
        const createNewSiteCard = document.getElementById('createNewSite');
        
        optionCards.style.display = 'grid';
        sitesListContainer.style.display = 'none';
        assignmentFormContainer.style.display = 'none';
        packageConfirmationContainer.style.display = 'none';
        
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
        
        // Restore Create New Site button visibility based on package type
        if (createNewSiteCard && selectedPackage) {
            const packageFullName = selectedPackage.fullName || '';
            if (packageFullName.toLowerCase().includes('extra')) {
                createNewSiteCard.style.display = 'none';
            } else {
                createNewSiteCard.style.display = 'block';
            }
        }
        
        // Clear selection
        selectedSiteId = null;
        newSiteData = null;
        document.getElementById('confirmSiteBtn').disabled = true;
    };

    // Fetch client sites
    let selectedSiteId = null;
    let selectedSiteData = null;
    
    // Counter management
    let officersCount = 0;
    let supervisorsCount = 0;
    let caretakersCount = 0;
    let pricePerOfficer = 0;
    let pricePerSupervisor = 0;
    let pricePerCaretaker = 0;
    
    // Track initial/existing counts for sites
    let initialOfficersCount = 0;
    let initialSupervisorsCount = 0;
    let initialCaretakersCount = 0;
    
    // Track if we're creating a new site (requires minimum 1 officer + 1 supervisor)
    let isNewSiteMode = false;

    // Initialize counter displays
    function updateCounterDisplays() {
        document.getElementById('officersCount').textContent = officersCount;
        document.getElementById('caretakersCount').textContent = caretakersCount;
        document.getElementById('supervisorsCount').textContent = supervisorsCount;
        
        // Calculate minimum required supervisors (1 per 5 officers, round up)
        const minRequiredSupervisors = Math.ceil(officersCount / 5);
        
        // For new sites, ensure at least 1 supervisor
        const actualMinSupervisors = Math.max(minRequiredSupervisors, isNewSiteMode ? 1 : 0);
        
        // Ensure we have at least the minimum required supervisors
        if (supervisorsCount < actualMinSupervisors) {
            supervisorsCount = actualMinSupervisors;
            document.getElementById('supervisorsCount').textContent = supervisorsCount;
        }
        
        // Update supervisor requirement text
        const supervisorReq = document.getElementById('supervisorRequirement');
        if (supervisorReq) {
            if (isNewSiteMode) {
                if (supervisorsCount > actualMinSupervisors) {
                    supervisorReq.textContent = `Minimum: ${actualMinSupervisors} (New site requires at least 1)`;
                } else {
                    supervisorReq.textContent = `Required: At least 1 supervisor for new sites (Minimum: ${actualMinSupervisors})`;
                }
            } else {
                if (supervisorsCount > minRequiredSupervisors) {
                    supervisorReq.textContent = `Minimum: ${minRequiredSupervisors} (You have ${supervisorsCount - minRequiredSupervisors} extra)`;
                } else {
                    supervisorReq.textContent = `Required: 1 supervisor per 5 officers (Minimum: ${minRequiredSupervisors})`;
                }
            }
        }
        
        // Enable/disable decrement buttons
        // All sites must have minimum 1 officer and required supervisors
        const minOfficers = 1;
        const minSupervisorsRequired = Math.max(minRequiredSupervisors, 1);
        
        document.getElementById('decrementOfficersBtn').disabled = officersCount <= minOfficers;
        document.getElementById('decrementSupervisorsBtn').disabled = supervisorsCount <= minSupervisorsRequired;
        document.getElementById('decrementCaretakersBtn').disabled = caretakersCount === 0;
        
        // Update pricing summary
        updatePricingSummary();
    }
    
    function updatePricingSummary() {
        // Calculate personnel changes (can be positive or negative)
        const officersChange = officersCount - initialOfficersCount;
        const supervisorsChange = supervisorsCount - initialSupervisorsCount;
        const caretakersChange = caretakersCount - initialCaretakersCount;
        
        const officersCost = officersChange * pricePerOfficer;
        const supervisorsCost = supervisorsChange * pricePerSupervisor;
        const caretakersCost = caretakersChange * pricePerCaretaker;
        const totalCost = officersCost + supervisorsCost + caretakersCost;
        
        let breakdownHTML = '';
        if (officersChange !== 0) {
            const prefix = officersChange > 0 ? '+' : '';
            const label = officersChange > 0 ? 'New' : 'Removed';
            breakdownHTML += `<div>${prefix}${officersChange} ${label} Officer${Math.abs(officersChange) !== 1 ? 's' : ''} × LKR ${pricePerOfficer.toLocaleString()} = <strong>${officersCost > 0 ? '+' : ''}LKR ${officersCost.toLocaleString()}</strong></div>`;
        }
        if (supervisorsChange !== 0) {
            const prefix = supervisorsChange > 0 ? '+' : '';
            const label = supervisorsChange > 0 ? 'New' : 'Removed';
            breakdownHTML += `<div>${prefix}${supervisorsChange} ${label} Supervisor${Math.abs(supervisorsChange) !== 1 ? 's' : ''} × LKR ${pricePerSupervisor.toLocaleString()} = <strong>${supervisorsCost > 0 ? '+' : ''}LKR ${supervisorsCost.toLocaleString()}</strong></div>`;
        }
        if (caretakersChange !== 0) {
            const prefix = caretakersChange > 0 ? '+' : '';
            const label = caretakersChange > 0 ? 'New' : 'Removed';
            breakdownHTML += `<div>${prefix}${caretakersChange} ${label} Caretaker${Math.abs(caretakersChange) !== 1 ? 's' : ''} × LKR ${pricePerCaretaker.toLocaleString()} = <strong>${caretakersCost > 0 ? '+' : ''}LKR ${caretakersCost.toLocaleString()}</strong></div>`;
        }
        
        if (breakdownHTML === '') {
            breakdownHTML = '<div style="color: #999; font-style: italic;">Adjust personnel numbers to see pricing changes</div>';
        }
        
        document.getElementById('priceBreakdown').innerHTML = breakdownHTML;
        document.getElementById('totalAmount').textContent = `${totalCost > 0 ? '+' : ''}LKR ${totalCost.toLocaleString()}`;
    }

    // Initialize on page load
    updateCounterDisplays();
    
    // Error message helper functions
    function showErrorMessage(elementId, message) {
        const errorElement = document.getElementById(elementId);
        if (errorElement) {
            errorElement.innerHTML = '<span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle; margin-right: 6px;">warning</span>' + message;
            errorElement.classList.add('show');
            // Auto-hide after 5 seconds
            setTimeout(() => {
                hideErrorMessage(elementId);
            }, 5000);
        }
    }
    
    function hideErrorMessage(elementId) {
        const errorElement = document.getElementById(elementId);
        if (errorElement) {
            errorElement.classList.remove('show');
        }
    }
    
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

    // Store sites data globally
    let allSites = [];
    
    function displaySites(sites) {
        const sitesGrid = document.getElementById('sitesGrid');
        allSites = sites; // Store for later use
        
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
        
        // Find and store the selected site's data
        selectedSiteData = allSites.find(site => site.id == siteId);
        
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
            // Only custom package can adjust personnel via assignment form
            if (selectedPackage.officers === 'custom') {
                // Load pricing data for custom package
                const customPackageItem = document.querySelector('.package-item[data-officers="custom"]');
                if (customPackageItem) {
                    pricePerOfficer = parseFloat(customPackageItem.dataset.priceOfficer) || 0;
                    pricePerSupervisor = parseFloat(customPackageItem.dataset.priceSupervisor) || 0;
                    pricePerCaretaker = parseFloat(customPackageItem.dataset.priceCaretaker) || 0;
                }
                
                // Initialize counters with existing site personnel
                if (selectedSiteData) {
                    officersCount = parseInt(selectedSiteData.assigned_officers) || 0;
                    supervisorsCount = parseInt(selectedSiteData.assigned_supervisors) || 0;
                    caretakersCount = parseInt(selectedSiteData.assigned_caretakers) || 0;
                    
                    // Store initial counts to track changes (additions or reductions)
                    initialOfficersCount = officersCount;
                    initialSupervisorsCount = supervisorsCount;
                    initialCaretakersCount = caretakersCount;
                } else {
                    // Reset initial counts for new sites
                    initialOfficersCount = 0;
                    initialSupervisorsCount = 0;
                    initialCaretakersCount = 0;
                }
                
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
                headerDesc.textContent = 'Adjust the number of personnel for this site (Minimum: 1 officer + 1 supervisor)';
                
                // Update display with current values
                updateCounterDisplays();
            } else {
                // For predefined packages, show confirmation page with price
                showPackageConfirmation(false);
            }
        }
    };

    // Back to sites list
    window.backToSitesList = function() {
        // Check if we came from new site form
        if (newSiteData) {
            backToNewSiteForm();
            return;
        }
        
        const sitesListContainer = document.getElementById('sitesListContainer');
        const assignmentFormContainer = document.getElementById('assignmentFormContainer');
        const optionCards = document.getElementById('optionCards');
        
        sitesListContainer.style.display = 'block';
        assignmentFormContainer.style.display = 'none';
        optionCards.style.display = 'none';
        
        // Hide any error messages
        hideErrorMessage('assignmentErrorMessage');
        
        // Package container remains disabled - we're still in deployment flow
        
        // Reset new site mode flag
        isNewSiteMode = false;
        
        // Reset counters
        officersCount = 0;
        supervisorsCount = 0;
        caretakersCount = 0;
        initialOfficersCount = 0;
        initialSupervisorsCount = 0;
        initialCaretakersCount = 0;
        updateCounterDisplays();
        
        // Update header back to site selection
        const headerTitle = document.getElementById('formHeaderTitle');
        const headerDesc = document.getElementById('formHeaderDesc');
        const packageName = document.querySelector('.package-item.selected .package-name').textContent;
        
        headerTitle.textContent = packageName;
        headerDesc.textContent = 'Select a site to adjust personnel';
    };
    
    function backToNewSiteForm() {
        const newSiteFormContainer = document.getElementById('newSiteFormContainer');
        const assignmentFormContainer = document.getElementById('assignmentFormContainer');
        
        assignmentFormContainer.style.display = 'none';
        newSiteFormContainer.style.display = 'block';
        
        // Make sure we're on step 2 (where we were)
        goToStep2();
        
        // Update header
        const headerTitle = document.getElementById('formHeaderTitle');
        const headerDesc = document.getElementById('formHeaderDesc');
        const packageName = document.querySelector('.package-item.selected .package-name').textContent;
        
        headerTitle.textContent = packageName;
        headerDesc.textContent = 'Complete your new site details';
        
        // Reset counters
        officersCount = 0;
        supervisorsCount = 0;
        caretakersCount = 0;
        initialOfficersCount = 0;
        initialSupervisorsCount = 0;
        initialCaretakersCount = 0;
        updateCounterDisplays();
    }

    window.incrementOfficers = function() {
        officersCount++;
        updateCounterDisplays();
    };

    window.decrementOfficers = function() {
        const minOfficers = 1; // All sites must have at least 1 officer
        if (officersCount > minOfficers) {
            officersCount--;
            updateCounterDisplays();
        }
    };

    window.incrementSupervisors = function() {
        supervisorsCount++;
        updateCounterDisplays();
    };

    window.decrementSupervisors = function() {
        const minRequired = Math.ceil(officersCount / 5);
        const minSupervisors = Math.max(minRequired, 1); // All sites must have at least 1 supervisor
        if (supervisorsCount > minSupervisors) {
            supervisorsCount--;
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
        // Calculate personnel changes (can be positive or negative)
        const officersChange = officersCount - initialOfficersCount;
        const supervisorsChange = supervisorsCount - initialSupervisorsCount;
        const caretakersChange = caretakersCount - initialCaretakersCount;
        
        // All sites must have at least 1 officer and 1 supervisor
        if (officersCount < 1) {
            showErrorMessage('assignmentErrorMessage', 'Site must have at least 1 officer');
            return;
        }
        if (supervisorsCount < 1) {
            showErrorMessage('assignmentErrorMessage', 'Site must have at least 1 supervisor');
            return;
        }
        
        // Special validation for new sites - must have at least 1 officer and 1 supervisor
        if (isNewSiteMode) {
            if (officersCount < 1) {
                showErrorMessage('assignmentErrorMessage', 'New sites must have at least 1 security officer.');
                return;
            }
            if (supervisorsCount < 1) {
                showErrorMessage('assignmentErrorMessage', 'New sites must have at least 1 supervisor.');
                return;
            }
        } else {
            // Validate some change is made for existing sites
            if (officersChange === 0 && supervisorsChange === 0 && caretakersChange === 0) {
                showErrorMessage('assignmentErrorMessage', 'Please make changes to personnel numbers');
                return;
            }
        }
        
        hideErrorMessage('assignmentErrorMessage');
        
        if (selectedPackage) {
            // Check if this is for a new site or existing site
            if (newSiteData) {
                // Submit with new site data
                const formData = new FormData();
                formData.append('mode', 'new');
                formData.append('package_name', 'Custom Package');
                formData.append('site_name', newSiteData.site_name);
                formData.append('site_address', newSiteData.site_address);
                formData.append('district', newSiteData.district);
                formData.append('city', newSiteData.city || newSiteData.district);
                formData.append('phone_number', newSiteData.phone_number);
                formData.append('latitude', newSiteData.latitude);
                formData.append('longitude', newSiteData.longitude);
                formData.append('number_of_officers', officersChange);
                formData.append('number_of_supervisors', supervisorsChange);
                formData.append('number_of_caretakers', caretakersChange);
                
                // Calculate package price based on personnel changes
                const totalPrice = (officersChange * pricePerOfficer) + 
                                  (supervisorsChange * pricePerSupervisor) + 
                                  (caretakersChange * pricePerCaretaker);
                formData.append('package_price', totalPrice);
                
                if (newSiteData.image) {
                    formData.append('image', newSiteData.image);
                }
                
                // Submit via AJAX
                submitPackageRequestAjax(formData);
            } else if (selectedSiteId) {
                // Submit with existing site (backend will handle deleting old pending requests)
                const formData = new FormData();
                formData.append('mode', 'existing');
                formData.append('site_id', selectedSiteId);
                formData.append('package_name', 'Custom Package');
                formData.append('site_name', selectedSiteData.site_name);
                formData.append('site_address', selectedSiteData.address);
                formData.append('district', selectedSiteData.district || '');
                formData.append('city', selectedSiteData.city || selectedSiteData.district);
                formData.append('number_of_officers', officersChange);
                formData.append('number_of_supervisors', supervisorsChange);
                formData.append('number_of_caretakers', caretakersChange);
                
                // Calculate package price based on personnel changes (can be negative)
                const totalPrice = (officersChange * pricePerOfficer) + 
                                  (supervisorsChange * pricePerSupervisor) + 
                                  (caretakersChange * pricePerCaretaker);
                formData.append('package_price', totalPrice);
                
                // Submit via AJAX
                submitPackageRequestAjax(formData);
            }
        }
    };

    // Create new site
    createNewSiteBtn.addEventListener('click', function() {
        if (selectedPackage) {
            // Check if package name contains "extra" - these can only be added to existing sites
            const packageFullName = selectedPackage.fullName || '';
            if (packageFullName.toLowerCase().includes('extra')) {
                // Silently prevent - extra packages are for existing sites only
                return;
            }
            
            // For other packages, show new site form
            showNewSiteForm();
        }
    });

    // New Site Form Functions
    let siteImageFile = null;
    let siteMap = null;
    let siteMarker = null;
    let newSiteData = null; // Store new site data temporarily

    function showNewSiteForm() {
        const optionCards = document.getElementById('optionCards');
        const newSiteFormContainer = document.getElementById('newSiteFormContainer');
        
        // Clear any error messages
        hideErrorMessage('step1ErrorMessage');
        hideErrorMessage('step2ErrorMessage');
        
        // Clear any previous new site data
        newSiteData = null;
        
        // Disable package container and hide navigation (same as existing site flow)
        isDeploymentOptionSelected = true;
        const packagesContainer = document.querySelector('.packages-container');
        if (packagesContainer) {
            packagesContainer.classList.add('disabled');
        }
        
        // Hide arrow buttons and indicators
        document.querySelectorAll('.carousel-nav').forEach(btn => btn.classList.add('hidden'));
        document.getElementById('packageCarouselIndicators').classList.add('hidden');
        
        optionCards.style.display = 'none';
        newSiteFormContainer.style.display = 'block';
        
        // Update header
        const headerTitle = document.getElementById('formHeaderTitle');
        const headerDesc = document.getElementById('formHeaderDesc');
        const packageName = document.querySelector('.package-item.selected .package-name')?.textContent || 'Custom Package';
        
        headerTitle.textContent = 'New Site - ' + packageName;
        headerDesc.textContent = 'Step 1: Enter site details';
        
        // Initialize step 1
        goToStep1();
    }

    window.backToOptionsFromNewSite = function() {
        const optionCards = document.getElementById('optionCards');
        const newSiteFormContainer = document.getElementById('newSiteFormContainer');
        const createNewSiteCard = document.getElementById('createNewSite');
        
        // Clear any error messages
        hideErrorMessage('step1ErrorMessage');
        hideErrorMessage('step2ErrorMessage');
        
        newSiteFormContainer.style.display = 'none';
        optionCards.style.display = 'grid';
        
        // Re-enable package container and show navigation (same as backToOptions)
        isDeploymentOptionSelected = false;
        const packagesContainer = document.querySelector('.packages-container');
        if (packagesContainer) {
            packagesContainer.classList.remove('disabled');
        }
        
        // Show arrow buttons and indicators
        document.querySelectorAll('.carousel-nav').forEach(btn => btn.classList.remove('hidden'));
        document.getElementById('packageCarouselIndicators').classList.remove('hidden');
        
        // Restore Create New Site button visibility based on package type
        if (createNewSiteCard && selectedPackage) {
            const packageFullName = selectedPackage.fullName || '';
            if (packageFullName.toLowerCase().includes('extra')) {
                createNewSiteCard.style.display = 'none';
            } else {
                createNewSiteCard.style.display = 'block';
            }
        }
        
        // Clear new site data
        newSiteData = null;
        
        // Reset form
        document.getElementById('newSiteName').value = '';
        document.getElementById('newSiteAddress').value = '';
        document.getElementById('district').value = '';
        document.getElementById('city').value = '';
        document.getElementById('newSitePhone').value = '';
        removeSiteImage();
        
        // Update header
        const headerTitle = document.getElementById('formHeaderTitle');
        const headerDesc = document.getElementById('formHeaderDesc');
        const packageName = document.querySelector('.package-item.selected .package-name')?.textContent || 'Custom Package';
        
        headerTitle.textContent = packageName;
        headerDesc.textContent = 'Choose your deployment option';
    };

    window.goToStep1 = function() {
        // Hide any error messages when going back
        hideErrorMessage('step2ErrorMessage');
        
        document.getElementById('step1').classList.add('active');
        document.getElementById('step2').classList.remove('active');
        document.getElementById('step1Indicator').classList.add('active');
        document.getElementById('step1Indicator').classList.remove('completed');
        document.getElementById('step2Indicator').classList.remove('active');
        
        const headerDesc = document.getElementById('formHeaderDesc');
        headerDesc.textContent = 'Step 1: Enter site details';
    };

    window.goToStep2 = function() {
        // Hide any previous error messages
        hideErrorMessage('step1ErrorMessage');
        
        // Validate step 1 fields
        const siteName = document.getElementById('newSiteName').value.trim();
        const siteDistrict = document.getElementById('district').value.trim();
        const siteCity = document.getElementById('city').value.trim();
        const sitePhone = document.getElementById('newSitePhone').value.trim();
        const photoFrame = document.getElementById('sitePhotoFrame');
        
        // Check if image is uploaded
        if (!siteImageFile) {
            showErrorMessage('step1ErrorMessage', 'Please upload a site image');
            // Highlight the photo frame
            photoFrame.style.borderColor = '#ff9800';
            photoFrame.style.borderWidth = '3px';
            setTimeout(() => {
                photoFrame.style.borderColor = '#e0e0e0';
                photoFrame.style.borderWidth = '2px';
            }, 3000);
            return;
        }
        
        if (!siteName || !siteDistrict || !sitePhone) {
            showErrorMessage('step1ErrorMessage', 'Please fill in all required fields (Site Name, District, and Phone Number)');
            return;
        }
        
        // Validate phone number format
        if (!/^[0-9]{10}$/.test(sitePhone)) {
            showErrorMessage('step1ErrorMessage', 'Phone number must be exactly 10 digits (numbers only)');
            return;
        }
        
        // Validate city if city field is visible
        const cityField = document.getElementById('city-field');
        if (cityField && cityField.style.display !== 'none' && !siteCity) {
            showErrorMessage('step1ErrorMessage', 'Please enter the city');
            return;
        }
        
        document.getElementById('step1').classList.remove('active');
        document.getElementById('step2').classList.add('active');
        document.getElementById('step1Indicator').classList.remove('active');
        document.getElementById('step1Indicator').classList.add('completed');
        document.getElementById('step2Indicator').classList.add('active');
        
        const headerDesc = document.getElementById('formHeaderDesc');
        headerDesc.textContent = 'Step 2: Select location on map';
        
        // Initialize map if not already done
        if (!siteMap) {
            initSiteMap();
        }
    };

    // Package Confirmation Functions (for non-custom packages)
    function requiredSupervisorsForOfficers(officers) {
        const count = Math.max(0, parseInt(officers, 10) || 0);
        return count > 0 ? Math.ceil(count / 5) : 0;
    }

    function getPolicyAdjustedPackageRequest(isNewSite) {
        const packageOfficers = parseInt(selectedPackage.officers, 10) || 0;
        const packageSupervisors = parseInt(selectedPackage.supervisors, 10) || 0;
        const packageCaretakers = parseInt(selectedPackage.caretakers, 10) || 0;

        const unitOfficer = parseFloat(selectedPackage.priceOfficer) || 0;
        const unitSupervisor = parseFloat(selectedPackage.priceSupervisor) || 0;
        const unitCaretaker = parseFloat(selectedPackage.priceCaretaker) || 0;

        let adjustedSupervisors = packageSupervisors;
        let policyNote = '';

        if (isNewSite) {
            const requiredForNew = requiredSupervisorsForOfficers(packageOfficers);
            if (adjustedSupervisors < requiredForNew) {
                adjustedSupervisors = requiredForNew;
                policyNote = `Supervisor policy applied: adjusted to ${adjustedSupervisors} supervisor(s).`;
            }
        } else {
            const currentOfficers = parseInt(selectedSiteData?.assigned_officers, 10) || 0;
            const currentSupervisors = parseInt(selectedSiteData?.assigned_supervisors, 10) || 0;
            const targetOfficers = Math.max(0, currentOfficers + packageOfficers);
            const requiredTargetSupervisors = requiredSupervisorsForOfficers(targetOfficers);
            const minSupervisorChange = Math.max(0, requiredTargetSupervisors - currentSupervisors);

            if (adjustedSupervisors < minSupervisorChange) {
                adjustedSupervisors = minSupervisorChange;
                policyNote = `Supervisor policy applied for this site: +${adjustedSupervisors} supervisor(s).`;
            }
        }

        const adjustedPrice =
            (packageOfficers * unitOfficer) +
            (adjustedSupervisors * unitSupervisor) +
            (packageCaretakers * unitCaretaker);

        return {
            officers: packageOfficers,
            supervisors: adjustedSupervisors,
            caretakers: packageCaretakers,
            price: adjustedPrice,
            policyNote
        };
    }

    function showPackageConfirmation(isNewSite) {
        const packageConfirmationContainer = document.getElementById('packageConfirmationContainer');
        const sitesListContainer = document.getElementById('sitesListContainer');
        const newSiteFormContainer = document.getElementById('newSiteFormContainer');
        
        // Hide previous containers
        sitesListContainer.style.display = 'none';
        newSiteFormContainer.style.display = 'none';
        
        // Show confirmation
        packageConfirmationContainer.style.display = 'block';
        
        // Get package details
        const selectedItem = document.querySelector('.package-item.selected');
        const packageName = selectedItem ? selectedItem.querySelector('.package-name').textContent : 'Package';
        const adjustedRequest = getPolicyAdjustedPackageRequest(isNewSite);
        
        // Update package info
        document.getElementById('confirmPackageName').textContent = packageName;
        let adjustedDetails = `${adjustedRequest.officers} Security Officer${adjustedRequest.officers !== 1 ? 's' : ''}`;
        if (adjustedRequest.supervisors > 0) {
            adjustedDetails += `, ${adjustedRequest.supervisors} Supervisor${adjustedRequest.supervisors !== 1 ? 's' : ''}`;
        }
        if (adjustedRequest.caretakers > 0) {
            adjustedDetails += `, ${adjustedRequest.caretakers} Caretaker${adjustedRequest.caretakers !== 1 ? 's' : ''}`;
        }
        if (adjustedRequest.policyNote) {
            adjustedDetails += ` (${adjustedRequest.policyNote})`;
        }
        document.getElementById('confirmPackageDetails').textContent = adjustedDetails;
        document.getElementById('confirmPrice').textContent = `LKR ${adjustedRequest.price.toLocaleString(undefined, {maximumFractionDigits: 2})}`;
        
        // Update site info
        if (isNewSite && newSiteData) {
            document.getElementById('confirmSiteName').textContent = newSiteData.site_name;
            document.getElementById('confirmSiteAddress').textContent = newSiteData.site_address;
        } else if (selectedSiteData) {
            document.getElementById('confirmSiteName').textContent = selectedSiteData.site_name;
            document.getElementById('confirmSiteAddress').textContent = selectedSiteData.site_address;
        }
        
        // Update header
        const headerTitle = document.getElementById('formHeaderTitle');
        const headerDesc = document.getElementById('formHeaderDesc');
        headerTitle.textContent = 'Review Your Request';
        headerDesc.textContent = 'Please confirm the details before proceeding';
    }
    
    window.backFromConfirmation = function() {
        const packageConfirmationContainer = document.getElementById('packageConfirmationContainer');
        
        // Check if we came from new site or existing site
        if (newSiteData) {
            // Go back to new site form (Step 2)
            const newSiteFormContainer = document.getElementById('newSiteFormContainer');
            packageConfirmationContainer.style.display = 'none';
            newSiteFormContainer.style.display = 'block';
            goToStep2();
            
            const headerTitle = document.getElementById('formHeaderTitle');
            const headerDesc = document.getElementById('formHeaderDesc');
            const packageName = document.querySelector('.package-item.selected .package-name')?.textContent || 'Package';
            headerTitle.textContent = 'New Site - ' + packageName;
            headerDesc.textContent = 'Step 2: Location details';
        } else {
            // Go back to sites list
            const sitesListContainer = document.getElementById('sitesListContainer');
            packageConfirmationContainer.style.display = 'none';
            sitesListContainer.style.display = 'block';
            
            const headerTitle = document.getElementById('formHeaderTitle');
            const headerDesc = document.getElementById('formHeaderDesc');
            const packageName = document.querySelector('.package-item.selected .package-name').textContent;
            headerTitle.textContent = packageName;
            headerDesc.textContent = 'Select a site to adjust personnel';
        }
    };
    
    window.proceedWithPackage = function() {
        if (selectedPackage) {
            const adjustedRequest = getPolicyAdjustedPackageRequest(!!newSiteData);
            if (newSiteData) {
                // Proceed with new site
                const formData = new FormData();
                formData.append('mode', 'new');
                formData.append('package_name', selectedPackage.name);
                formData.append('site_name', newSiteData.site_name);
                formData.append('site_address', newSiteData.site_address);
                formData.append('district', newSiteData.district);
                formData.append('city', newSiteData.city || newSiteData.district);
                formData.append('phone_number', newSiteData.phone_number);
                formData.append('latitude', newSiteData.latitude);
                formData.append('longitude', newSiteData.longitude);
                formData.append('number_of_officers', adjustedRequest.officers || 0);
                formData.append('number_of_supervisors', adjustedRequest.supervisors || 0);
                formData.append('number_of_caretakers', adjustedRequest.caretakers || 0);
                formData.append('package_price', adjustedRequest.price || 0);
                
                if (newSiteData.image) {
                    formData.append('image', newSiteData.image);
                }
                
                // Submit via AJAX
                submitPackageRequestAjax(formData);
            } else if (selectedSiteId) {
                // Submit with existing site (backend will handle deleting old pending requests)
                const formData = new FormData();
                formData.append('mode', 'existing');
                formData.append('site_id', selectedSiteId);
                formData.append('package_name', selectedPackage.name);
                formData.append('site_name', selectedSiteData.site_name);
                formData.append('site_address', selectedSiteData.address);
                formData.append('district', selectedSiteData.district || '');
                formData.append('city', selectedSiteData.city || selectedSiteData.district);
                formData.append('number_of_officers', adjustedRequest.officers || 0);
                formData.append('number_of_supervisors', adjustedRequest.supervisors || 0);
                formData.append('number_of_caretakers', adjustedRequest.caretakers || 0);
                formData.append('package_price', adjustedRequest.price || 0);
                
                // Submit via AJAX
                submitPackageRequestAjax(formData);
            }
        }
    };

    function initSiteMap() {
        const mapElement = document.getElementById('siteMap');
        if (!mapElement) return;
        
        // Default to Colombo, Sri Lanka
        const defaultLocation = { lat: 6.9271, lng: 79.8612 };
        
        siteMap = new google.maps.Map(mapElement, {
            center: defaultLocation,
            zoom: 12,
            mapTypeControl: true,
            streetViewControl: true,
            fullscreenControl: true
        });
        
        // Add click listener to place marker
        siteMap.addListener('click', function(event) {
            placeMarker(event.latLng);
        });
        
        // Initialize location search box
        initNewSiteLocationSearch();
        
        // Try to get user's current location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    siteMap.setCenter(pos);
                },
                function() {
                    console.log('Geolocation service failed or denied');
                }
            );
        }
    }

    function initNewSiteLocationSearch() {
        const searchInput = document.getElementById('newSiteLocationSearch');
        if (!searchInput) return;
        
        // Create SearchBox
        const searchBox = new google.maps.places.SearchBox(searchInput);
        
        // Bias the SearchBox results towards current map's viewport
        siteMap.addListener('bounds_changed', function() {
            searchBox.setBounds(siteMap.getBounds());
        });
        
        // Listen for when user selects a prediction
        searchBox.addListener('places_changed', function() {
            const places = searchBox.getPlaces();
            
            if (places.length === 0) {
                return;
            }
            
            // Get the first place
            const place = places[0];
            
            if (!place.geometry || !place.geometry.location) {
                console.log('Place has no geometry');
                return;
            }
            
            // Add marker at the selected location
            placeMarker(place.geometry.location);
            
            // Update address field with the place's formatted address
            const addressInput = document.getElementById('newSiteAddress');
            if (place.formatted_address && addressInput) {
                addressInput.value = place.formatted_address;
            }
            
            // Update site name if empty
            const siteNameInput = document.getElementById('newSiteName');
            if (siteNameInput && !siteNameInput.value && place.name) {
                siteNameInput.value = place.name;
            }
            
            // Adjust map to show the place
            if (place.geometry.viewport) {
                siteMap.fitBounds(place.geometry.viewport);
            } else {
                siteMap.setCenter(place.geometry.location);
                siteMap.setZoom(17);
            }
            
            // Clear the search box
            searchInput.value = '';
        });
    }

    function placeMarker(location) {
        if (siteMarker) {
            siteMarker.setMap(null);
        }
        
        siteMarker = new google.maps.Marker({
            position: location,
            map: siteMap,
            animation: google.maps.Animation.DROP,
            draggable: true
        });
        
        document.getElementById('newSiteLatitude').value = location.lat();
        document.getElementById('newSiteLongitude').value = location.lng();
        
        // Reverse geocode to get address
        getAddressFromLocation(location);
        
        siteMarker.addListener('dragend', function(event) {
            document.getElementById('newSiteLatitude').value = event.latLng.lat();
            document.getElementById('newSiteLongitude').value = event.latLng.lng();
            // Update address when marker is dragged
            getAddressFromLocation(event.latLng);
        });
    }

    function getAddressFromLocation(location) {
        const geocoder = new google.maps.Geocoder();
        
        geocoder.geocode({ location: location }, function(results, status) {
            if (status === 'OK') {
                if (results[0]) {
                    // Set the formatted address
                    document.getElementById('newSiteAddress').value = results[0].formatted_address;
                } else {
                    console.log('No address found for this location');
                    document.getElementById('newSiteAddress').value = 'Address not found';
                }
            } else {
                console.log('Geocoder failed: ' + status);
                document.getElementById('newSiteAddress').value = 'Unable to retrieve address';
            }
        });
    }

    // Phone number validation - only allow numbers
    const phoneInput = document.getElementById('newSitePhone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            // Remove any non-digit characters
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Update visual feedback
            if (this.value.length === 10) {
                this.style.borderColor = '#4caf50';
            } else if (this.value.length > 0) {
                this.style.borderColor = '#ff9800';
            } else {
                this.style.borderColor = '#e0e0e0';
            }
        });
        
        phoneInput.addEventListener('keypress', function(e) {
            // Prevent non-numeric input
            if (!/[0-9]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'Tab' && e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') {
                e.preventDefault();
            }
        });
    }

    // Site image handling
    document.getElementById('siteImageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                showErrorMessage('step1ErrorMessage', 'Please select a valid image file (JPG, JPEG, or PNG)');
                e.target.value = '';
                return;
            }
            
            siteImageFile = file;
            const reader = new FileReader();
            reader.onload = function(event) {
                const photoFrame = document.getElementById('sitePhotoFrame');
                document.getElementById('siteImagePreview').src = event.target.result;
                document.getElementById('addSitePhotoBtn').style.display = 'none';
                document.getElementById('removeSitePhotoBtn').style.display = 'inline-block';
                
                // Add green border to indicate image is uploaded
                photoFrame.style.borderColor = '#4caf50';
                photoFrame.style.borderStyle = 'solid';
            };
            reader.readAsDataURL(file);
        }
    });

    window.removeSiteImage = function() {
        siteImageFile = null;
        const photoFrame = document.getElementById('sitePhotoFrame');
        document.getElementById('siteImageInput').value = '';
        document.getElementById('siteImagePreview').src = '<?php echo URL_ROOT; ?>/img/photo.png';
        document.getElementById('addSitePhotoBtn').style.display = 'inline-block';
        document.getElementById('removeSitePhotoBtn').style.display = 'none';
        
        // Reset border color to default
        photoFrame.style.borderColor = '#e0e0e0';
        photoFrame.style.borderStyle = 'dashed';
    };

    window.submitNewSiteWithPackage = function() {
        // Hide any previous error messages
        hideErrorMessage('step2ErrorMessage');
        
        const latitude = document.getElementById('newSiteLatitude').value;
        const longitude = document.getElementById('newSiteLongitude').value;
        const address = document.getElementById('newSiteAddress').value.trim();
        
        // Validate image is uploaded
        if (!siteImageFile) {
            showErrorMessage('step2ErrorMessage', 'Site photo is required. Please go back to Step 1 and upload an image.');
            return;
        }
        
        if (!latitude || !longitude) {
            showErrorMessage('step2ErrorMessage', 'Please select a location on the map');
            return;
        }
        
        if (!address) {
            showErrorMessage('step2ErrorMessage', 'Address is required. Please select a location on the map or enter address manually.');
            return;
        }
        
        // Store new site data temporarily
        newSiteData = {
            site_name: document.getElementById('newSiteName').value,
            site_address: address,
            district: document.getElementById('district').value,
            city: document.getElementById('city').value,
            phone_number: document.getElementById('newSitePhone').value,
            latitude: latitude,
            longitude: longitude,
            image: siteImageFile
        };
        
        // Check if custom package or regular package
        if (selectedPackage.officers === 'custom') {
            // Show assignment form (Add Security Personnel) for custom package
            showAssignmentFormForNewSite();
        } else {
            // Show confirmation page for predefined packages
            showPackageConfirmation(true);
        }
    };
    
    function showAssignmentFormForNewSite() {
        const newSiteFormContainer = document.getElementById('newSiteFormContainer');
        const assignmentFormContainer = document.getElementById('assignmentFormContainer');
        
        newSiteFormContainer.style.display = 'none';
        assignmentFormContainer.style.display = 'block';
        
        // Update header
        const headerTitle = document.getElementById('formHeaderTitle');
        const headerDesc = document.getElementById('formHeaderDesc');
        const packageName = document.querySelector('.package-item.selected .package-name').textContent;
        
        headerTitle.textContent = packageName;
        headerDesc.textContent = 'Add security personnel for new site (Minimum: 1 officer + 1 supervisor)';
        
        // Set new site mode flag
        isNewSiteMode = true;
        
        // Initialize counters with minimum requirements for new sites
        // New sites must have at least 1 officer and 1 supervisor
        officersCount = 1;
        supervisorsCount = 1;
        caretakersCount = 0;
        initialOfficersCount = 0;
        initialSupervisorsCount = 0;
        initialCaretakersCount = 0;
        
        // Set pricing from selected package
        const selectedItem = document.querySelector('.package-item.selected');
        if (selectedItem) {
            pricePerOfficer = parseFloat(selectedItem.dataset.priceOfficer || 0);
            pricePerSupervisor = parseFloat(selectedItem.dataset.priceSupervisor || 0);
            pricePerCaretaker = parseFloat(selectedItem.dataset.priceCaretaker || 0);
        }
        
        updateCounterDisplays();
    }

    // Helper function to submit package request via AJAX
    function submitPackageRequestAjax(formData) {
        // Disable submit button to prevent double submission
        const submitBtns = document.querySelectorAll('.btn-submit-assignment, .btn-proceed-package');
        submitBtns.forEach(btn => btn.disabled = true);
        
        fetch('<?php echo URL_ROOT; ?>/client/submitPackageRequest', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to payment page with cache-busting parameter to ensure fresh data
                window.location.href = '<?php echo URL_ROOT; ?>/client/payments?refresh=' + Date.now();
            } else {
                // Show error message in the appropriate container
                const errorMsg = data.message || 'Failed to submit request. Please try again.';
                if (document.getElementById('assignmentErrorMessage')) {
                    showErrorMessage('assignmentErrorMessage', errorMsg);
                } else if (document.getElementById('step2ErrorMessage')) {
                    showErrorMessage('step2ErrorMessage', errorMsg);
                }
                submitBtns.forEach(btn => btn.disabled = false);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Show error message in the appropriate container
            const errorMsg = 'Failed to submit request. Please try again.';
            if (document.getElementById('assignmentErrorMessage')) {
                showErrorMessage('assignmentErrorMessage', errorMsg);
            } else if (document.getElementById('step2ErrorMessage')) {
                showErrorMessage('step2ErrorMessage', errorMsg);
            }
            submitBtns.forEach(btn => btn.disabled = false);
        });
    }

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
                fullName: customPackageItem.dataset.packageName,
                price: customPackageItem.dataset.price,
                officers: customPackageItem.dataset.officers,
                supervisors: customPackageItem.dataset.supervisors || 0,
                caretakers: customPackageItem.dataset.caretakers || 0,
                priceOfficer: customPackageItem.dataset.priceOfficer || 0,
                priceSupervisor: customPackageItem.dataset.priceSupervisor || 0,
                priceCaretaker: customPackageItem.dataset.priceCaretaker || 0
            };
        } else {
            selectedPackage = {
                name: 'custom',
                fullName: 'Custom Package',
                price: '0',
                officers: 'custom',
                supervisors: 0,
                caretakers: 0,
                priceOfficer: 0,
                priceSupervisor: 0,
                priceCaretaker: 0
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
        const createNewSiteCard = document.getElementById('createNewSite');
        
        if (emptyState && optionCards && customPackageItem) {
            emptyState.style.display = 'none';
            optionCards.style.display = 'grid';
            
            // Update header with actual package name
            const packageName = customPackageItem.querySelector('.package-name') ? customPackageItem.querySelector('.package-name').textContent : 'Custom Package';
            headerTitle.textContent = packageName;
            headerDesc.textContent = 'Choose your deployment option';
            
            // Hide "Create New Site" button if package name contains "extra"
            if (createNewSiteCard) {
                const packageFullName = selectedPackage.fullName || '';
                if (packageFullName.toLowerCase().includes('extra')) {
                    createNewSiteCard.style.display = 'none';
                } else {
                    createNewSiteCard.style.display = 'block';
                }
            }
        }
    };
});
</script>

<!-- Google Maps API for Site Location -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGwijY64zQTmizwDN6omOoI9nzxb1MQog&libraries=places" async defer></script>

<!-- District and City Selection Script -->
<script src="<?php echo URL_ROOT; ?>/js/components/select_district_city.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
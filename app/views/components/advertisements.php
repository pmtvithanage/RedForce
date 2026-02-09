<?php
/**
 * Advertisements Component
 * 
 * Displays advertisements based on user role
 * Usage: require_once APP_ROOT . '/views/components/advertisements.php';
 */

// Get user role from session
$userRole = $_SESSION['user_role'] ?? 'guest';

// Map role names to match database format
$roleMapping = [
    'admin' => 'admin',
    'caretaker' => 'caretaker',
    'supervisor' => 'supervisor',
    'mobilerider' => 'mobile rider',
    'premiseofficer' => 'premise officer',
    'client' => 'client'
];

$targetRole = $roleMapping[$userRole] ?? $userRole;

// Fetch advertisements for the user's role
require_once APP_ROOT . '/models/M_advertisements.php';
$advertisementModel = new M_advertisements();
$advertisements = $advertisementModel->getAdvertisementsByRole($targetRole);
?>

<style>
/* Advertisement Carousel Styles */
.ad-carousel-container {
    position: relative;
    margin-top: 15px;
}

.ad-carousel {
    position: relative;
    overflow-x: hidden;
    overflow-y: visible;
    border-radius: 8px;
}

.ad-carousel-wrapper {
    display: flex;
    transition: transform 0.5s ease-in-out;
}

.ad-item {
    min-width: 100%;
    display: flex;
    flex-direction: column;
    gap: 15px;
    padding: 0;
    background-color: #f8f9fa;
    border-radius: 8px;
    cursor: pointer;
    box-sizing: border-box;
    overflow: visible;
}

.ad-item:hover {
    background-color: #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.ad-image {

    height: 265px;
    border-radius: 8px 8px 0 0;
    overflow: hidden;
    background-color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ad-image img {

    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.ad-item:hover .ad-image img {
    transform: scale(1.05);
}

.ad-content {
    padding: 15px 20px 20px 20px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    text-align: center;
}

.ad-content h4 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    color: #333;
}

.ad-content p {
    margin: 0;
    font-size: 14px;
    color: #666;
    line-height: 1.5;
    max-height: 4.5em;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}

.ad-content small {
    color: #999;
    font-size: 12px;
}

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
    margin-top: 12px;
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
    background: #c41515;
    width: 24px;
    border-radius: 4px;
}

/* Advertisement Modal */
.ad-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    z-index: 10000;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.ad-modal.active {
    display: flex;
}

.ad-modal-content {
    background: white;
    border-radius: 12px;
    max-width: 800px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    animation: modalSlideIn 0.3s ease-out;
    position: relative;
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.ad-modal-header {
    position: sticky;
    top: 0;
    background: white;
    padding: 20px 25px;
    border-bottom: 2px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 12px 12px 0 0;
    z-index: 1;
}

.ad-modal-title {
    font-size: 24px;
    font-weight: 700;
    color: #333;
    margin: 0;
}

.ad-modal-close {
    background: none;
    border: none;
    font-size: 28px;
    color: #666;
    cursor: pointer;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s;
}

.ad-modal-close:hover {
    background: #f0f0f0;
    color: #333;
}

.ad-modal-body {
    padding: 25px;
}

.ad-modal-image {
    width: 100%;
    max-height: 400px;
    object-fit: contain;
    border-radius: 8px;
    margin-bottom: 20px;
    background: #f8f9fa;
}

.ad-modal-description {
    font-size: 16px;
    color: #555;
    line-height: 1.6;
    margin-bottom: 20px;
}

.ad-modal-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.ad-modal-meta-item {
    display: flex;
    flex-direction: column;
}

.ad-modal-meta-label {
    font-size: 12px;
    color: #666;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 3px;
}

.ad-modal-meta-value {
    font-size: 14px;
    color: #333;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .ad-image {
        height: 250px;
    }

    .ad-content {
        padding: 12px 15px 15px 15px;
    }
    
    .ad-content h4 {
        font-size: 18px;
    }
    
    .carousel-nav {
        width: 32px;
        height: 32px;
    }
    
    .carousel-nav .material-symbols-outlined {
        font-size: 20px;
    }
    
    .ad-modal-content {
        max-width: 95%;
        margin: 10px;
    }
    
    .ad-modal-title {
        font-size: 20px;
    }
    
    .ad-modal-body {
        padding: 15px;
    }
}

@media (max-width: 480px) {
    .ad-image {
        height: 200px;
    }

    .ad-content {
        padding: 10px 12px 12px 12px;
    }
    
    .ad-content h4 {
        font-size: 16px;
    }
    
    .ad-content p {
        font-size: 13px;
    }
    
    .carousel-nav {
        width: 28px;
        height: 28px;
    }
    
    .carousel-nav.prev {
        left: 5px;
    }
    
    .carousel-nav.next {
        right: 5px;
    }
    
    .ad-modal-header {
        padding: 15px;
    }
    
    .ad-modal-title {
        font-size: 18px;
    }
}
</style>

<!-- Advertisements Section -->
<div class="card section">
    <h3>Advertisements</h3>
    
    <?php if (!empty($advertisements)): ?>
        <div class="ad-carousel-container">
            <div class="ad-carousel">
                <?php if (count($advertisements) > 1): ?>
                    <button class="carousel-nav prev" onclick="changeSlide(-1)">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <button class="carousel-nav next" onclick="changeSlide(1)">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                <?php endif; ?>
                
                <div class="ad-carousel-wrapper" id="adCarouselWrapper">
                    <?php foreach ($advertisements as $index => $ad): ?>
                        <div class="ad-item" 
                             onclick="openAdModal(<?php echo htmlspecialchars(json_encode($ad), ENT_QUOTES, 'UTF-8'); ?>)">
                            <div class="ad-image">
                                <?php if (!empty($ad->image_path)): ?>
                                    <img src="<?php echo URL_ROOT . $ad->image_path; ?>" 
                                         alt="<?php echo htmlspecialchars($ad->title); ?>">
                                <?php else: ?>
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #e9ecef;">
                                        <span class="material-symbols-outlined" style="font-size: 64px; color: #ccc;">image</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="ad-content">
                                <h4><?php echo htmlspecialchars($ad->title); ?></h4>
                                <?php if (!empty($ad->description)): ?>
                                    <p><?php echo htmlspecialchars(substr($ad->description, 0, 200)) . (strlen($ad->description) > 200 ? '...' : ''); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($ad->created_at)): ?>
                                    <small>📅 <?php echo date('M d, Y', strtotime($ad->created_at)); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <?php if (count($advertisements) > 1): ?>
                <div class="carousel-indicators" id="carouselIndicators">
                    <?php foreach ($advertisements as $index => $ad): ?>
                        <span class="carousel-dot <?php echo $index === 0 ? 'active' : ''; ?>" 
                              onclick="goToSlide(<?php echo $index; ?>)"></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="empty-activity">
            <span class="material-symbols-outlined">campaign</span>
            <p>No advertisements available.</p>
            <small>New updates will appear here.</small>
        </div>
    <?php endif; ?>
</div>

<!-- Advertisement Modal -->
<div id="adModal" class="ad-modal">
    <div class="ad-modal-content">
        <div class="ad-modal-header">
            <h2 class="ad-modal-title" id="adModalTitle"></h2>
            <button class="ad-modal-close" onclick="closeAdModal()" aria-label="Close">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="ad-modal-body">
            <img id="adModalImage" class="ad-modal-image" src="" alt="" style="display: none;">
            <div id="adModalDescription" class="ad-modal-description"></div>
            <div class="ad-modal-meta">
                <div class="ad-modal-meta-item">
                    <span class="ad-modal-meta-label">Posted On</span>
                    <span class="ad-modal-meta-value" id="adModalDate"></span>
                </div>
                <div class="ad-modal-meta-item" id="adModalCreatorContainer" style="display: none;">
                    <span class="ad-modal-meta-label">Created By</span>
                    <span class="ad-modal-meta-value" id="adModalCreator"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentSlide = 0;
let autoSlideInterval;
const totalSlides = <?php echo count($advertisements); ?>;

function updateCarousel() {
    const wrapper = document.getElementById('adCarouselWrapper');
    if (wrapper) {
        wrapper.style.transform = `translateX(-${currentSlide * 100}%)`;
        document.querySelectorAll('.carousel-dot').forEach((dot, index) => {
            dot.classList.toggle('active', index === currentSlide);
        });
    }
}

function changeSlide(direction) {
    currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
    updateCarousel();
    resetAutoSlide();
}

function goToSlide(index) {
    currentSlide = index;
    updateCarousel();
    resetAutoSlide();
}

function autoSlide() {
    if (totalSlides > 1) {
        currentSlide = (currentSlide + 1) % totalSlides;
        updateCarousel();
    }
}

function resetAutoSlide() {
    if (totalSlides > 1) {
        clearInterval(autoSlideInterval);
        autoSlideInterval = setInterval(autoSlide, 5000);
    }
}

if (totalSlides > 1) {
    autoSlideInterval = setInterval(autoSlide, 5000);
    const carousel = document.querySelector('.ad-carousel-container');
    carousel?.addEventListener('mouseenter', () => clearInterval(autoSlideInterval));
    carousel?.addEventListener('mouseleave', resetAutoSlide);
}

function openAdModal(ad) {
    document.getElementById('adModalTitle').textContent = ad.title || 'Advertisement';
    document.getElementById('adModalDescription').textContent = ad.description || 'No description available.';
    
    const img = document.getElementById('adModalImage');
    if (ad.image_path) {
        img.src = '<?php echo URL_ROOT; ?>' + ad.image_path;
        img.alt = ad.title || '';
        img.style.display = 'block';
    } else {
        img.style.display = 'none';
    }
    
    document.getElementById('adModalDate').textContent = ad.created_at 
        ? new Date(ad.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' })
        : 'N/A';
    
    const creatorContainer = document.getElementById('adModalCreatorContainer');
    if (ad.creator_name) {
        document.getElementById('adModalCreator').textContent = ad.creator_name;
        creatorContainer.style.display = 'flex';
    } else {
        creatorContainer.style.display = 'none';
    }
    
    document.getElementById('adModal').classList.add('active');
    document.body.style.overflow = 'hidden';
    clearInterval(autoSlideInterval);
}

function closeAdModal() {
    document.getElementById('adModal').classList.remove('active');
    document.body.style.overflow = '';
    resetAutoSlide();
}

document.getElementById('adModal')?.addEventListener('click', e => e.target.id === 'adModal' && closeAdModal());
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeAdModal();
    if (!document.getElementById('adModal').classList.contains('active') && totalSlides > 1) {
        if (e.key === 'ArrowLeft') changeSlide(-1);
        if (e.key === 'ArrowRight') changeSlide(1);
    }
});
</script>

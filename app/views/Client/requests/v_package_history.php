<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
.main-content{background:#f3f3f3;padding:2rem;min-height:100vh}
.back-btn{margin-bottom:20px}
.request-card{background:#fff;border-radius:12px;padding:25px;margin-bottom:20px;box-shadow:0 2px 8px rgba(0,0,0,0.1)}
.request-card:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,0.15)}
.request-header{display:flex;justify-content:space-between;margin-bottom:15px;padding-bottom:15px;border-bottom:2px solid #f0f0f0}
.request-title h3{font-size:20px;color:#333;margin:0}
.request-id{font-size:12px;color:#888}
.status-badge{padding:5px 15px;border-radius:20px;font-size:12px;font-weight:600}
.status-badge.pending{background:#fff3cd;color:#856404}
.status-badge.approved{background:#d4edda;color:#155724}
.status-badge.rejected{background:#f8d7da;color:#721c24}
.request-body{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px;margin-bottom:15px}
.request-detail{display:flex;flex-direction:column;gap:5px}
.detail-label{font-size:11px;color:#888;text-transform:uppercase;font-weight:600}
.detail-value{font-size:14px;color:#333;font-weight:500}
.detail-value.price{color:#a40000;font-size:16px;font-weight:700}
.request-footer{display:flex;justify-content:space-between;align-items:center;margin-top:15px;padding-top:15px;border-top:1px solid #f0f0f0}
.submitted-date{font-size:12px;color:#666;display:flex;align-items:center;gap:5px}
.admin-response{background:#f8f9fa;border-left:4px solid #6c757d;padding:12px;margin-top:15px;border-radius:4px}
.admin-response.approved{background:#d4edda;border-left-color:#28a745}
.admin-response.rejected{background:#f8d7da;border-left-color:#dc3545}
.admin-response-label{font-size:11px;color:#666;font-weight:600;text-transform:uppercase;margin-bottom:5px}
.admin-response-text{font-size:13px;color:#333;margin:0}
.no-requests{background:#fff;border-radius:12px;padding:60px 20px;text-align:center}
.no-requests .material-icons{font-size:80px;color:#ccc;margin-bottom:20px}
</style>
<div class="main-content">
<button class="tertiary-btn back-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/client/requests'">
<span class="material-icons">arrow_back</span>Back to Packages</button>
<?php flash('package_success'); ?><?php flash('package_error'); ?>
<div class="history-container">
<?php if(empty($data['requests'])): ?>
<div class="no-requests"><span class="material-icons">inbox</span><p>No package requests found</p></div>
<?php else: ?>
<?php foreach($data['requests'] as $r): ?>
<div class="request-card">
<div class="request-header">
<div class="request-title">
<h3><?php echo htmlspecialchars($r->package_name); ?></h3>
<span class="request-id">Request #<?php echo $r->id; ?></span>
</div>
<span class="status-badge status-<?php echo strtolower($r->status); ?>"><?php echo $r->status; ?></span>
</div>
<div class="request-body">
<div class="request-detail">
<span class="detail-label">Site Address</span>
<span class="detail-value"><?php echo htmlspecialchars($r->site_address); ?></span>
</div>
<div class="request-detail">
<span class="detail-label">Service Period</span>
<span class="detail-value"><?php echo date('M d, Y',strtotime($r->start_date)).' - '.date('M d, Y',strtotime($r->end_date)); ?></span>
</div>
<div class="request-detail">
<span class="detail-label">Guards</span>
<span class="detail-value"><?php echo $r->number_of_guards; ?> Guard<?php echo $r->number_of_guards!=1?'s':''; ?>
<?php if($r->day_guards!==null && $r->night_guards!==null): ?>
(<?php echo $r->day_guards; ?> Day, <?php echo $r->night_guards; ?> Night)
<?php endif; ?></span>
</div>
<div class="request-detail">
<span class="detail-label">Monthly Price</span>
<span class="detail-value price">LKR <?php echo number_format($r->package_price,2); ?>/=</span>
</div>
</div>
<?php if(!empty($r->comments)): ?>
<div class="request-detail">
<span class="detail-label">Comments</span>
<span class="detail-value"><?php echo htmlspecialchars($r->comments); ?></span>
</div>
<?php endif; ?>
<?php if($r->admin_notes && $r->status!=='Pending'): ?>
<div class="admin-response <?php echo strtolower($r->status); ?>">
<div class="admin-response-label"><?php echo $r->status==='Approved'?'Admin Response':'Rejection Reason'; ?></div>
<p class="admin-response-text"><?php echo htmlspecialchars($r->admin_notes); ?></p>
</div>
<?php endif; ?>
<div class="request-footer">
<div class="submitted-date">
<span class="material-icons">schedule</span>
Submitted: <?php echo date('M d, Y g:i A',strtotime($r->submitted_date)); ?>
</div>
<?php if($r->status==='Pending'): ?>
<form method="POST" action="<?php echo URL_ROOT; ?>/client/deletePackageRequest/<?php echo $r->id; ?>" onsubmit="return confirm('Delete this request?');">
<button type="submit" class="tertiary-btn"><span class="material-icons">delete</span>Delete</button>
</form>
<?php endif; ?>
</div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
</div>
</main></div>
<div class="backdrop" id="backdrop" hidden></div>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>

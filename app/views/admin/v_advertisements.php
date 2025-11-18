<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/admin/advertisements_style.css">

<div class="page">

  <!-- Advertisement List -->
  <section class="ads">
    <div class="ads__rail" id="adsRail" aria-label="Advertisement list" tabindex="0">
      <?php if (!empty($advertisements)): ?>
        <?php foreach ($advertisements as $ad): ?>
          <article class="ad-card" data-id="<?= $ad->id ?>">
            <button class="ad-card__close" aria-label="Remove">×</button>
            <button class="ad-card__edit" aria-label="Edit">✎</button>
            <img src="<?= URL_ROOT . '/' . htmlspecialchars($ad->image_path); ?>" 
                 alt="Advertisement Image" 
                 class="ad-card__image" />
            <footer class="ad-card__meta">
              <div class="ad-card__role">
                <span><?= htmlspecialchars($ad->target_roles) ?></span>
              </div>
              <div class="ad-card__date">
                <div class="muted">Published</div>
                <div>
                  <span class="date"><?= date('Y M d', strtotime($ad->created_at)) ?></span><br/>
                  <span class="time"><?= date('h:i A', strtotime($ad->created_at)) ?></span>
                </div>

                <?php if (!empty($ad->updated_at)): ?>
                  <div class="muted" style="margin-top:8px;">Updated</div>
                  <div>
                    <span class="date"><?= date('Y M d', strtotime($ad->updated_at)) ?></span><br/>
                    <span class="time"><?= date('h:i A', strtotime($ad->updated_at)) ?></span>
                  </div>
                <?php endif; ?>
              </div>
              <div class="ad-card__status">
                <span>Status: <?= ucfirst($ad->status) ?></span>
                <button type="button" class="btn btn--small" data-toggle-status>Toggle</button>
              </div>
            </footer>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No advertisements yet.</p>
      <?php endif; ?>
    </div>
  </section>

  <!-- Advertisement Creator -->
  <section class="creator">
    <div class="creator__wrap">
      <h2>Create Advertisement</h2>

      <div class="creator__grid">
        <!-- Image Upload -->
        <label class="uploader" id="uploader">
          <input type="file" id="fileInput" name="image" accept="image/*" hidden />
          <div class="uploader__preview" id="preview">
            <span>Upload Advertisement</span>
          </div>
          <button type="button" class="uploader__button" id="uploadBtn">Choose Image</button>
        </label>

        <!-- Form -->
        <form class="creator__form" id="createForm" enctype="multipart/form-data">
          <!-- Title -->
          <div class="form__group">
            <input type="text" name="title" placeholder="Advertisement Title" required />
          </div>

          <!-- Roles -->
          <fieldset class="checklist">
            <label class="check"><input type="checkbox" name="roles[]" value="Premise Officer" /> <span>Premise Officer</span></label>
            <label class="check"><input type="checkbox" name="roles[]" value="Supervisor" /> <span>Supervisor</span></label>
            <label class="check"><input type="checkbox" name="roles[]" value="Care-Taker" /> <span>Care-Taker</span></label>
            <label class="check"><input type="checkbox" name="roles[]" value="Mobile Rider" /> <span>Mobile Rider</span></label>
          </fieldset>

          <!-- Submit -->
          <div class="creator__actions">
            <button type="submit" class="btn btn--primary">Publish</button>
          </div>
          <p class="form__hint" id="formHint" role="status" aria-live="polite"></p>
        </form>
      </div>
    </div>
  </section>
</div>

<!-- Edit Advertisement Modal -->
<div class="backdrop" id="backdrop" hidden></div>
<div class="modal" id="editForm" hidden>
  <div class="modal__content">
    <h2>Edit Advertisement</h2>
    
    <div class="creator__grid">
      <!-- Image Upload -->
      <label class="uploader">
        <input type="file" id="editFileInput" name="image" accept="image/*" hidden />
        <input type="hidden" id="currentImagePath" value="" />
        <div class="uploader__preview" id="editPreview">
          <span>Current Image</span>
        </div>
        <button type="button" class="uploader__button">Change Image</button>
      </label>

      <!-- Form -->
      <form class="creator__form" id="editFormInner" enctype="multipart/form-data">
        <input type="hidden" id="editId" name="id" value="" />
        
        <!-- Title -->
        <div class="form__group">
          <input type="text" id="editTitle" name="title" placeholder="Advertisement Title" required />
        </div>

        <!-- Roles -->
        <fieldset class="checklist">
          <label class="check"><input type="checkbox" name="editRoles[]" value="Premise Officer" /> <span>Premise Officer</span></label>
          <label class="check"><input type="checkbox" name="editRoles[]" value="Supervisor" /> <span>Supervisor</span></label>
          <label class="check"><input type="checkbox" name="editRoles[]" value="Care-Taker" /> <span>Care-Taker</span></label>
          <label class="check"><input type="checkbox" name="editRoles[]" value="Mobile Rider" /> <span>Mobile Rider</span></label>
        </fieldset>

        <!-- Status -->
        <fieldset>
          <legend>Status</legend>
          <label class="radio"><input type="radio" name="status" value="active" /> <span>Active</span></label>
          <label class="radio"><input type="radio" name="status" value="inactive" /> <span>Inactive</span></label>
        </fieldset>

        <!-- Submit -->
        <div class="creator__actions">
          <button type="button" class="btn btn--secondary" onclick="document.getElementById('backdrop').click()">Cancel</button>
          <button type="submit" class="btn btn--primary">Update</button>
        </div>
        <p class="form__hint" id="editHint" role="status" aria-live="polite"></p>
      </form>
    </div>
  </div>
</div>

<script>
  window.URL_ROOT = '<?php echo URL_ROOT; ?>';
</script>
<script src="<?php echo URL_ROOT; ?>/js/admin/advertisements.js"></script>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
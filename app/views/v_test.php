<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  
  <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/admin/v_settings.css">
<style>
  .form-and-photo {
    display: flex;
    gap: 24px;
    margin-top: 20px;
    align-items: center;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 24px;
    box-shadow: 0 6px 16px rgba(17, 24, 39, 0.06);
  }

  .photo-upload,
  .form-fields {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 18px;
  }

  .photo-upload {
    width: 280px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .form-fields {
    flex: 1;
    display: grid;
    grid-template-columns: 1fr;
    gap: 12px;
  }

  .gender {
    width: 20%;
    height: 40px;
  }

  .photo-label,
  .form-fields label {
    font-size: 14px;
    font-weight: 600;
    color: #111827;
  }

  input[type="file"],
  input[type="text"],
  input[type="email"],
  textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    font-size: 14px;
    background: #fff;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    font-family: inherit;
  }

  input[type="file"]:focus,
  input[type="text"]:focus,
  input[type="email"]:focus,
  textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
  }

  button[type="submit"] {
    margin-top: 6px;
    width: fit-content;
    padding: 10px 18px;
    border: none;
    border-radius: 10px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.15s ease, box-shadow 0.2s ease;
  }

  button[type="submit"]:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 18px rgba(37, 99, 235, 0.25);
  }

  @media (max-width: 768px) {
    .form-and-photo {
      flex-direction: column;
      padding: 16px;
    }

    .photo-upload {
      width: 100%;
    }
  }

  .imagePlaceholder{
      height: 180px;
  border: 2px solid var(--border-color);
  border-radius: 12px;
  margin: 0 auto 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  background-color: #fff;
  }

.btn-upload {
  background-color: var(--primary-color);
  color: var(--secondary-color);
  border: none;
  width: 120px;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-upload:hover {
  background-color: #b50000;
}

.btn-upload:active {
  background-color: #800000;
  transform: scale(0.97);
}

.checkbox-group {
  display: grid;
  grid-template-columns: repeat(2, minmax(180px, 1fr));
  gap: 10px 14px;
  margin-top: 4px;
}

.checkbox-item {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #111827;
  font-size: 14px;
}

.checkbox-item input[type="checkbox"] {
  width: 16px;
  height: 16px;
}
</style>

    <!-- Content will be loaded here -->

    <form class="form-and-photo" action="<?php echo URL_ROOT; ?>/test/index" method="POST" enctype="multipart/form-data">
      <div class="photo-upload">
        <img class="imagePlaceholder" src="<?php echo URL_ROOT; ?>/public/img/photo.png" 
          alt="Uploaded logo preview" 
          id="imagePlaceholder"
          data-default-src="<?php echo URL_ROOT; ?>/public/img/photo.png" />
        
        
        <input type="file" id="image" name="image" accept="image/*" hidden />
        <!-- <span class="form-input-error"><?php echo $data['image_err'];?></span> -->


        <div class="btn-upload" id="addImageBtn" onClick="toggleBrowse()">Upload photo</div>
        <div class="btn-upload" id="removeImageBtn" style="display: none;" onClick="removeImage()">Remove</div>
      </div>

      <div class="form-fields">
        <!-- input fields will go here -->
        <input class="field-input" type="text" id="name" name="name" value="<?php echo $data['name']; ?>" placeholder="Enter name"/>
        <span class="form-input-error"><?php echo $data['name_err'];?></span>

        <input class="field-input" type="text" id="email" name="email" value="<?php echo $data['email']; ?>" placeholder="Enter your email" />
        <span class="form-input-error"><?php echo $data['email_err'];?></span>

        <!-- Dropdown for gender -->
        <select class="field-input gender" id="gender" name="gender">
          <option value="">Select Gender</option>
          <option value="Male" <?php echo (isset($data['gender']) && $data['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
          <option value="Female" <?php echo (isset($data['gender']) && $data['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
        </select>
        <span class="form-input-error"><?php echo $data['gender_err'] ?? '';?></span>

        <!-- Textarea for description -->
        <textarea class="field-input" id="description" name="description" placeholder="Enter description" rows="4"><?php echo $data['description'] ?? ''; ?></textarea>
        <span class="form-input-error"><?php echo $data['description_err'] ?? '';?></span>

        <!-- Checkbox group for hobbies -->
        <label>Hobbies</label>
        <div class="checkbox-group">
          <label class="checkbox-item" for="reading_books">
            <input type="checkbox" id="reading_books" name="reading_books" value="1" <?php echo !empty($data['reading_books']) ? 'checked' : ''; ?> />
            Reading books
          </label>

          <label class="checkbox-item" for="play_games">
            <input type="checkbox" id="play_games" name="play_games" value="1" <?php echo !empty($data['play_games']) ? 'checked' : ''; ?> />
            Play games
          </label>

          <label class="checkbox-item" for="collect_stamps">
            <input type="checkbox" id="collect_stamps" name="collect_stamps" value="1" <?php echo !empty($data['collect_stamps']) ? 'checked' : ''; ?> />
            Collect stamps
          </label>

          <label class="checkbox-item" for="watch_tv">
            <input type="checkbox" id="watch_tv" name="watch_tv" value="1" <?php echo !empty($data['watch_tv']) ? 'checked' : ''; ?> />
            Watch TV
          </label>
        </div>

        <button type="submit">Submit</button>
      </div>
    </form>
     <button class="tertiary-btn" style="display:flex; width:170px; align-items:center; cursor: pointer" onclick="window.location.href='<?php echo URL_ROOT; ?>/test/table'">View Submissions</button>
    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script>
      const addImageBtn = document.getElementById("addImageBtn");
const removeImageBtn = document.getElementById("removeImageBtn");
const imagePlaceholder = document.getElementById("imagePlaceholder");

let inputPath = document.querySelector("#image");
let file;

// Get the default image path from data attribute
const defaultImagePath = imagePlaceholder.getAttribute("data-default-src");

function toggleBrowse() {
  inputPath.click();
}

function removeImage() {
  addImageBtn.style.display = "block";
  removeImageBtn.style.display = "none";
  imagePlaceholder.style.display = "block"; // Changed to "block" to show default image

  // Reset to default image
  imagePlaceholder.setAttribute("src", defaultImagePath);

  inputPath.value = null;
  file = null;
}

inputPath.addEventListener("change", function () {
  file = this.files[0];

  if (file) {
    addImageBtn.style.display = "none";
    removeImageBtn.style.display = "block";
    imagePlaceholder.style.display = "block";
    showImage();
  } else {
    // If user cancels file selection, reset to default
    removeImage();
  }
});

function showImage() {
  let fileType = file.type;
  let validExtensions = ["image/jpeg", "image/jpg", "image/png"];

  if (validExtensions.includes(fileType)) {
    let fileReader = new FileReader();

    fileReader.onload = () => {
      let fileURL = fileReader.result;
      imagePlaceholder.setAttribute("src", fileURL);
    };

    fileReader.onerror = () => {
      alert("Error reading file");
      removeImage();
    };

    fileReader.readAsDataURL(file);
  } else {
    alert("This is not a valid image file"); // Fixed typo
    removeImage();
  }
}

    </script>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
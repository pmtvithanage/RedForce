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

.input-group{
    display: flex;
    flex-direction: column;
    gap: 4px;
    width: 40%;
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
        <span class="form-input-error"><?php echo $data['image_err'];?></span> 


        <div class="btn-upload" id="addImageBtn" onClick="toggleBrowse()">Upload photo</div>
        <div class="btn-upload" id="removeImageBtn" style="display: none;" onClick="removeImage()">Remove</div>
      </div>

      <div class="form-fields">
        <!-- input fields will go here -->
        <input class="field-input" type="text" id="name" name="name" value="<?php echo $data['name']; ?>" placeholder="Enter name" pattern="^[A-Za-z](?:[A-Za-z0-9]*[A-Za-z])?$" title="Name must start and end with a letter. Middle characters can be letters or numbers"/>
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
        <textarea class="field-input" id="description" name="description" placeholder="Enter description" rows="4" minlength="100" maxlength="100" title="Description must be exactly 100 characters"><?php echo $data['description'] ?? ''; ?></textarea>
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

        <div class="container" style="display: flex; flex-direction: column; padding: 12px; margin-top: 12px; border: 1px solid #e5e7eb; padding-top: 12px;">
          <div class="input-group">
            <p>Username</p>
            <input type="text" name="username" placeholder="Enter name" value="<?php echo $data['username'] ?? ''; ?>">
            <span class="form-input-error"><?php echo $data['username_err'] ?? ''; ?></span>
          </div>

          <div class="input-group">
            <p>Email</p>
            <input type="email" name="contact_email" required value="<?php echo $data['contact_email'] ?? ''; ?>">
            <span class="form-input-error"><?php echo $data['contact_email_err'] ?? ''; ?></span>
          </div>

          <div class="input-group">
            <p>Password</p>
            
            <input type="password" name="pwd" value="<?php echo $data['pwd'] ?? ''; ?>" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{6,}" title="Password must include uppercase, lowercase, number, and symbol">
            <span class="form-input-error"><?php echo $data['pwd_err'] ?? ''; ?></span>
          </div>

          <div class="input-group">
            <p>Age</p>
            <input type="number" name="age" min="0" max="120" value="<?php echo $data['age'] ?? ''; ?>">
            <span class="form-input-error"><?php echo $data['age_err'] ?? ''; ?></span>
          </div>

          <div class="input-group">
            <p>Date of Birth</p>
            <input type="date" name="dob" value="<?php echo $data['dob'] ?? ''; ?>" max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>">
            <span class="form-input-error"><?php echo $data['dob_err'] ?? ''; ?></span>
          </div>

          <div class="input-group">
            <p>NIC Number</p>
            <input type="text" name="nic" value="<?php echo $data['nic'] ?? ''; ?>" placeholder="Enter NIC number" pattern="^\d{12}$" minlength="12" maxlength="12" inputmode="numeric" title="NIC number must be exactly 12 digits">
            <span class="form-input-error"><?php echo $data['nic_err'] ?? ''; ?></span>
          </div>

          <div class="input-group">
              <p>Subscribe to newsletter</p>
            <input type="checkbox" name="subscribe" value="news" <?php echo !empty($data['subscribe']) ? 'checked' : ''; ?>>
            <span class="form-input-error"><?php echo $data['subscribe_err'] ?? ''; ?></span>
          </div>

          <div class="input-group">
              <p>Gender</p>
            <input type="radio" name="gender_identity" value="male" <?php echo (isset($data['gender_identity']) && $data['gender_identity'] === 'male') ? 'checked' : ''; ?>> Male
            <input type="radio" name="gender_identity" value="female" <?php echo (isset($data['gender_identity']) && $data['gender_identity'] === 'female') ? 'checked' : ''; ?>>Female
            <span class="form-input-error"><?php echo $data['gender_identity_err'] ?? ''; ?></span>
          </div>

          <div class="input-group">
            <p>Favorite Color</p>
            <input type="color" name="favcolor" value="<?php echo $data['favcolor'] ?? '#000000'; ?>">
            <span class="form-input-error"><?php echo $data['favcolor_err'] ?? ''; ?></span>
          </div>

          <div class="input-group">
            <p>Volume</p>
            <input type="range" name="volume" min="0" max="100" value="<?php echo $data['volume'] ?? '50'; ?>">
            <span class="form-input-error"><?php echo $data['volume_err'] ?? ''; ?></span>
          </div>

          <div class="input-group">
              <p>Upload File</p>
            <input type="file" name="upload">
            <span class="form-input-error"><?php echo $data['upload_err'] ?? ''; ?></span>
          </div>

          <div class="input-group">
            <p>User ID</p>
            <input type="hidden" name="user_id" value="<?php echo $data['user_id'] ?? '12345'; ?>">
            <span class="form-input-error"><?php echo $data['user_id_err'] ?? ''; ?></span>
          </div>

          <div class="input-group">
            <p>Search</p>
            <input type="search" name="query" value="<?php echo $data['query'] ?? ''; ?>">
            <span class="form-input-error"><?php echo $data['query_err'] ?? ''; ?></span>
          </div>

          <div class="input-group">
            <p>Phone Number</p>
            <input type="tel" name="phone" value="<?php echo $data['phone'] ?? ''; ?>" pattern="^07[0-9]{8}$" minlength="10" maxlength="10" inputmode="numeric" title="Enter a 10-digit phone number starting with 07">
            <span class="form-input-error"><?php echo $data['phone_err'] ?? ''; ?></span>
          </div>

          <div class="input-group">
            <p>Website</p>
            <input type="url" name="website" value="<?php echo $data['website'] ?? ''; ?>">
            <span class="form-input-error"><?php echo $data['website_err'] ?? ''; ?></span>
          </div>

          <div class="input-group">
            <p>Meeting Time</p>
            <input type="time" name="meeting_time" value="<?php echo $data['meeting_time'] ?? ''; ?>">
            <span class="form-input-error"><?php echo $data['meeting_time_err'] ?? ''; ?></span>
          </div>

          <div class="input-group">
            <p>Message</p>
            <textarea name="message" rows="4" cols="50"><?php echo $data['message'] ?? ''; ?></textarea>
            <span class="form-input-error"><?php echo $data['message_err'] ?? ''; ?></span>
          </div>

          <div class="input-group">
            <p>Country</p>
            <select name="country">
              <option value="">Select country</option>
              <option value="us" <?php echo (isset($data['country']) && $data['country'] === 'us') ? 'selected' : ''; ?>>USA</option>
              <option value="ca" <?php echo (isset($data['country']) && $data['country'] === 'ca') ? 'selected' : ''; ?>>Canada</option>
              <option value="uk" <?php echo (isset($data['country']) && $data['country'] === 'uk') ? 'selected' : ''; ?>>UK</option>
            </select>
            <span class="form-input-error"><?php echo $data['country_err'] ?? ''; ?></span>
          </div>

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

 
    let fileReader = new FileReader();

    fileReader.onload = () => {
      let fileURL = fileReader.result;
      imagePlaceholder.setAttribute("src", fileURL);
    };



    fileReader.readAsDataURL(file);

}

    </script>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
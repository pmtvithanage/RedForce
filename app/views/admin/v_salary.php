<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>


  <?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>


<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">


  <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/admin/salary_style.css">


    <!-- Content will be loaded here -->
     <div class="container">
    <!-- Officer Card -->
    <div class="card" data-popup="officerPopup">
      <img src="<?php echo URL_ROOT; ?>/img/premise-officer.png" alt="Officer">
      <h2>Officer</h2>
      <p class="units">1448 UNITS</p>
      <p class="subtitle">Total payment for last month</p>
      <p class="amount">Rs. 110,231,242.30</p>
    </div>

    <!-- Care-Taker Card -->
    <div class="card" data-popup="caretakerPopup">
      <img src="<?php echo URL_ROOT; ?>/img/care-taker.png" alt="Care Taker">
      <h2>Care-Taker</h2>
      <p class="units">284 UNITS</p>
      <p class="subtitle">Total payment for last month</p>
      <p class="amount">Rs. 14,436,944.30</p>
    </div>

    <!-- Mobile Rider Card -->
    <div class="card" data-popup="riderPopup">
      <img src="<?php echo URL_ROOT; ?>/img/mobile-rider.png" alt="Mobile Rider">
      <h2>Mobile Rider</h2>
      <p class="units">53 UNITS</p>
      <p class="subtitle">Total payment for last month</p>
      <p class="amount">Rs. 2,221,624.30</p>
    </div>
  </div>

  <!-- Officer Popup -->
  <div id="officerPopup" class="popup">
    <div class="popup-content">
      <span class="close">&times;</span>
      <h2>Officer Salary Structure</h2>

      <div class="salary-structure">
        <div class="basics">
          <h3>Basics / OT (Per Hour)</h3>
          <div class="row"><label>Level 1</label><input type="text" value="Rs. 40,000.00"><input type="text" value="Rs. 174.00"></div>
          <div class="row"><label>Level 2</label><input type="text" value="Rs. 45,000.00"><input type="text" value="Rs. 200.00"></div>
          <div class="row"><label>Level 3</label><input type="text" value="Rs. 55,000.00"><input type="text" value="Rs. 246.00"></div>
          <div class="row"><label>Level 4</label><input type="text" value="Rs. 70,000.00"><input type="text" value="Rs. 300.00"></div>
          <div class="row"><label>Level 5</label><input type="text" value="Rs. 90,000.00"><input type="text" value="Rs. 430.00"></div>
          <div class="add-row">
            <input type="text" placeholder="Level">
            <input type="text" placeholder="Salary">
            <button class="addBtn">Add</button>
          </div>
        </div>

        <div class="bonus">
          <h3>Bonus (Per Month)</h3>
          <ul>
            <li>Rank 1-10 : Rs.10,000</li>
            <li>Rank 11-50 : Rs.7,000</li>
            <li>Rank 51-100 : Rs.5,000</li>
            <li>Rank 101-500 : Rs.3,000</li>
            <li>Rank 501-1000 : Rs.1,000</li>
          </ul>
        </div>
      </div>

      <div class="bonus-cards">
        <h3>Create Bonus Cards</h3>
        <input type="text" placeholder="Description">
        <input type="text" placeholder="Amount">
        <button>Create</button>
      </div>

      <div class="actions">
        <input type="text" placeholder="Search">
        <select>
          <option>Select Bonus</option>
          <option>Performance</option>
          <option>Attendance</option>
        </select>
        <button>Apply</button>
      </div>

      <table>
        <thead>
          <tr>
            <th>Officer ID</th>
            <th>Officer</th>
            <th>Level</th>
            <th>Basic</th>
            <th>Total</th>
            <th>Select</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>PF416</td>
            <td>Kasun Silva</td>
            <td>Level 4</td>
            <td>Rs. 70,000.00</td>
            <td>Rs. 76,000.00</td>
            <td><input type="checkbox"></td>
          </tr>
          <tr>
            <td>PF664</td>
            <td>Dilan Jayasuriya</td>
            <td>Level 3</td>
            <td>Rs. 70,000.00</td>
            <td>Rs. 76,000.00</td>
            <td><input type="checkbox"></td>
          </tr>
          <tr>
            <td>PF220</td>
            <td>Chamika Bandara</td>
            <td>Level 3</td>
            <td>Rs. 70,000.00</td>
            <td>Rs. 72,000.00</td>
            <td><input type="checkbox"></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Care-Taker Popup -->
  <div id="caretakerPopup" class="popup">
    <div class="popup-content">
      <span class="close">&times;</span>
      <h2>Care-Taker Salary Structure</h2>
      <div class="salary-structure">
        <div class="basics">
          <h3>Basics / OT (Per Hour)</h3>
          <div class="row"><label>Level 1</label><input type="text" value="Rs. 40,000.00"><input type="text" value="Rs. 174.00"></div>
          <div class="row"><label>Level 2</label><input type="text" value="Rs. 45,000.00"><input type="text" value="Rs. 200.00"></div>
          <div class="row"><label>Level 3</label><input type="text" value="Rs. 55,000.00"><input type="text" value="Rs. 246.00"></div>
          <div class="row"><label>Level 4</label><input type="text" value="Rs. 70,000.00"><input type="text" value="Rs. 300.00"></div>
          <div class="row"><label>Level 5</label><input type="text" value="Rs. 90,000.00"><input type="text" value="Rs. 430.00"></div>
          <div class="add-row">
            <input type="text" placeholder="Level">
            <input type="text" placeholder="Salary">
            <button class="addBtn">Add</button>
          </div>
        </div>

        <div class="bonus">
          <h3>Bonus (Per Month)</h3>
          <ul>
            <li>Rank 1-10 : Rs.10,000</li>
            <li>Rank 11-50 : Rs.7,000</li>
            <li>Rank 51-100 : Rs.5,000</li>
            <li>Rank 101-500 : Rs.3,000</li>
            <li>Rank 501-1000 : Rs.1,000</li>
          </ul>
        </div>
      </div>

      <div class="bonus-cards">
        <h3>Create Bonus Cards</h3>
        <input type="text" placeholder="Description">
        <input type="text" placeholder="Amount">
        <button>Create</button>
      </div>

      <div class="actions">
        <input type="text" placeholder="Search">
        <select>
          <option>Select Bonus</option>
          <option>Performance</option>
          <option>Attendance</option>
        </select>
        <button>Apply</button>
      </div>

      <table>
        <thead>
          <tr>
            <th>Officer ID</th>
            <th>Officer</th>
            <th>Level</th>
            <th>Basic</th>
            <th>Total</th>
            <th>Select</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>PF416</td>
            <td>Kasun Silva</td>
            <td>Level 4</td>
            <td>Rs. 70,000.00</td>
            <td>Rs. 76,000.00</td>
            <td><input type="checkbox"></td>
          </tr>
          <tr>
            <td>PF664</td>
            <td>Dilan Jayasuriya</td>
            <td>Level 3</td>
            <td>Rs. 70,000.00</td>
            <td>Rs. 76,000.00</td>
            <td><input type="checkbox"></td>
          </tr>
          <tr>
            <td>PF220</td>
            <td>Chamika Bandara</td>
            <td>Level 3</td>
            <td>Rs. 70,000.00</td>
            <td>Rs. 72,000.00</td>
            <td><input type="checkbox"></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Rider Popup -->
  <div id="riderPopup" class="popup">
    <div class="popup-content">
      <span class="close">&times;</span>
      <h2>Mobile Rider Salary Structure</h2>
      <div class="salary-structure">
        <div class="basics">
          <h3>Basics / OT (Per Hour)</h3>
          <div class="row"><label>Level 1</label><input type="text" value="Rs. 40,000.00"><input type="text" value="Rs. 174.00"></div>
          <div class="row"><label>Level 2</label><input type="text" value="Rs. 45,000.00"><input type="text" value="Rs. 200.00"></div>
          <div class="row"><label>Level 3</label><input type="text" value="Rs. 55,000.00"><input type="text" value="Rs. 246.00"></div>
          <div class="row"><label>Level 4</label><input type="text" value="Rs. 70,000.00"><input type="text" value="Rs. 300.00"></div>
          <div class="row"><label>Level 5</label><input type="text" value="Rs. 90,000.00"><input type="text" value="Rs. 430.00"></div>
          <div class="add-row">
            <input type="text" placeholder="Level">
            <input type="text" placeholder="Salary">
            <button class="addBtn">Add</button>
          </div>
        </div>

        <div class="bonus">
          <h3>Bonus (Per Month)</h3>
          <ul>
            <li>Rank 1-10 : Rs.10,000</li>
            <li>Rank 11-50 : Rs.7,000</li>
            <li>Rank 51-100 : Rs.5,000</li>
            <li>Rank 101-500 : Rs.3,000</li>
            <li>Rank 501-1000 : Rs.1,000</li>
          </ul>
        </div>
      </div>

      <div class="bonus-cards">
        <h3>Create Bonus Cards</h3>
        <input type="text" placeholder="Description">
        <input type="text" placeholder="Amount">
        <button>Create</button>
      </div>

      <div class="actions">
        <input type="text" placeholder="Search">
        <select>
          <option>Select Bonus</option>
          <option>Performance</option>
          <option>Attendance</option>
        </select>
        <button>Apply</button>
      </div>

      <table>
        <thead>
          <tr>
            <th>Officer ID</th>
            <th>Officer</th>
            <th>Level</th>
            <th>Basic</th>
            <th>Total</th>
            <th>Select</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>PF416</td>
            <td>Kasun Silva</td>
            <td>Level 4</td>
            <td>Rs. 70,000.00</td>
            <td>Rs. 76,000.00</td>
            <td><input type="checkbox"></td>
          </tr>
          <tr>
            <td>PF664</td>
            <td>Dilan Jayasuriya</td>
            <td>Level 3</td>
            <td>Rs. 70,000.00</td>
            <td>Rs. 76,000.00</td>
            <td><input type="checkbox"></td>
          </tr>
          <tr>
            <td>PF220</td>
            <td>Chamika Bandara</td>
            <td>Level 3</td>
            <td>Rs. 70,000.00</td>
            <td>Rs. 72,000.00</td>
            <td><input type="checkbox"></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Payment History Section -->
  <div class="payment-history">
    <button id="toggleBtn">
      View Payment History <span id="arrow">▼</span>
    </button>
    <div id="historyContent" class="hidden">
      <p>Payment history details will appear here...</p>
    </div>
  </div>
    
    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/admin/salary.js"></script>
    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inventory Management</title>
  <link rel="stylesheet" href="../styles/adminStyle.css" />
  <link rel="stylesheet" href="../styles/adminInventory.css" />
</head>

<body>
  <div class="container">

    <div class="sidebar" id="sidebar">
      <div class="company-name">Papa J's</div>
      <a href="dashboard.php" class="menu-item">📊 Dashboard</a>
      <a href="pos.php" class="menu-item">📠 POS</a>
      <a href="inventory.php" class="menu-item active">🗂️ Inventory</a>
      <a href="receipt.php" class="menu-item">🧾 Receipt Management</a>
      <div class="logout">
        <a href="login.html" class="menu-item">➜ Log Out</a>
      </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content" id="mainContent">

      <div class="header">
        <div class="header-left">
          <button class="burger-btn" id="burgerBtn">
            <span></span><span></span><span></span>
          </button>
          <h1 class="dashboard-title">Inventory Management</h1>
        </div>
        <div class="user-info">
          <div class="user-text">
            <div class="user-name">Account Name</div>
            <div class="user-role">Admin</div>
          </div>
          <div class="user-avatar"></div>
        </div>
      </div>

      <!-- INVENTORY TABLE -->
      <section class="inventory-wrapper">
        <div class="inventory-card">
          <div class="inventory-controls">
            <input type="text" class="inventory-search" id="searchInput" placeholder="Search by receipt or customer name...">
            <select class="inventory-status-filter" id="statusFilter">
              <option value="All">All Status</option>
              <option value="Pending">Pending</option>
              <option value="Washing">Washing</option>
              <option value="Washed">Washed</option>
              <option value="Complete">Complete</option>
            </select>
          </div>

          <div class="inventory-table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>Receipt</th>
                  <th>Customer</th>
                  <th>Item</th>
                  <th>Qty</th>
                  <th>Price</th>
                  <th>Status</th>
                  <th>Due Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="inventoryTable"></tbody>
            </table>
          </div>
        </div>
      </section>
    </div>
  </div>

  <!-- EDIT MODAL -->
  <div class="modal-backdrop" id="editModal">
    <div class="modal">
      <div class="modal-header">Update Laundry Status</div>
      <div class="modal-body">
        <label>Status</label>
        <select class="input" id="modalStatus">
          <option>Pending</option>
          <option>Washing</option>
          <option>Washed</option>
          <option>Complete</option>
        </select>
        <label>Due Date</label>
        <input class="input" type="date" id="modalDate">
      </div>
      <div class="modal-actions">
        <button class="btn" id="cancelModal">Cancel</button>
        <button class="complete-btn" id="saveModal">Save</button>
      </div>
    </div>
  </div>

  <!-- DELETE CONFIRM MODAL -->
  <div class="modal-backdrop" id="deleteModal">
    <div class="modal">
      <div class="modal-header">Confirm Delete</div>
      <div class="modal-body">
        Are you sure you want to delete this item from inventory?
      </div>
      <div class="modal-actions">
        <button class="btn" id="cancelDelete">Cancel</button>
        <button class="btn delete" id="confirmDelete">Delete</button>
      </div>
    </div>
  </div>

  <div class="toast" id="toast">✅ Inventory updated!</div>

  <script src="../adminFunction/inventoryFunction.js">
  </script>
</body>

</html>

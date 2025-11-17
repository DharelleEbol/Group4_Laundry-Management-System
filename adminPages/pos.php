<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>POS</title>

  <link rel="stylesheet" href="../styles/adminStyle.css" />
  <link rel="stylesheet" href="../styles/adminPos.css" />
</head>
 
<body>
  <div class="container">

    <div class="sidebar" id="sidebar">
      <div class="company-name">Papa J's</div>
      <a href="dashboard.php" class="menu-item">📊 Dashboard</a>
      <a href="pos.php" class="menu-item active">📠 POS</a>
      <a href="inventory.php" class="menu-item">🗂️ Inventory</a>
      <a href="receipt.php" class="menu-item">🧾 Receipt Management</a>
      <div class="logout">
        <a href="login.html" class="menu-item">➜ Log Out</a>
      </div>
    </div>

    <!-- MAIN -->
    <div class="main-content" id="mainContent">

      <!-- HEADER -->
      <div class="header">
        <div class="header-left">
          <button class="burger-btn" id="burgerBtn">
            <span></span><span></span><span></span>
          </button>
          <h1 class="dashboard-title">Point of Sale</h1>
        </div>

        <div class="user-info">
          <div class="user-text">
            <div class="user-name">Account Name</div>
            <div class="user-role">Admin</div>
          </div>
          <div class="user-avatar"></div>
        </div>
      </div>

      <!-- POS LAYOUT -->
      <div class="pos-container">

        <!-- LEFT -->
        <div class="pos-left">
          <div class="box service-box">
            <h4 class="box-title">Service Items</h4>
            <div id="serviceGrid" class="service-grid"></div>
          </div>
        </div>

        <!-- RIGHT -->
        <div class="pos-right">
          <div class="pos-right-container">
            <div class="box">
              <div class="customer-search-row">
                <input class="input" id="customerSearch" placeholder="Search Customer..." />
                <button class="btn" id="openCustomerModal">Add customer</button>
              </div>

              <div class="receipt-row">
                <input class="input" id="receiptId" placeholder="RCPT-0000" readonly />
                <input class="input" id="dateInput" type="date" />
              </div>

              <input class="input" id="custName" placeholder="Name" />
              <input class="input" id="custAddress" placeholder="Address" />
              <input class="input" id="custPhone" placeholder="Phone Number" />
            </div>

            <div class="box cart-box">
              <div class="table-wrapper">
                <table class="table" id="cartTable">
                  <thead>
                    <tr>
                      <th>Service</th>
                      <th>Rate</th>
                      <th>Kilogram</th>
                      <th>Notes</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>

              <div class="cart-footer">
                <div class="kv subtotal-row">
                  <div>Subtotal</div>
                  <div id="subtotal">₱0.00</div>
                </div>

                <div class="kv total-row">
                  <div>Total Payment</div>
                  <div id="total">₱0.00</div>
                </div>

                <!-- PAYMENT + CLEAR BUTTONS-->
                <div class="payment-clear-wrapper">
                  <button class="modal-payment-btn" id="openPaymentModal">Payment method</button>
                  <button class="modal-clear-btn" id="clearBtn">Clear</button>
                </div>

                <div class="complete-wrapper">
                  <button class="complete-btn" id="saveBtn">Complete and Save Transaction</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!--SERVICE MODAL -->
  <div class="modal-backdrop" id="addServiceBackdrop">
    <div class="modal add-service-modal">
      <div class="modal-header">Add Service</div>

      <div class="modal-body modal-center">
        <p class="muted">
          <span id="modalServiceName"></span><br />
          <span id="modalServiceRate"></span>
        </p>

        <input class="input" id="kgInput" type="number" min="0.1" step="0.1" placeholder="Kilogram (e.g., 2)" />
        <input class="input note-input" id="notesInput" type="text" placeholder="Notes (optional)" />
      </div>

      <div class="modal-actions">
        <button class="modal-clear-btn" id="cancelAddService">Cancel</button>
        <button class="modal-confirm-btn" id="confirmAddService">Add</button>
      </div>
    </div>
  </div>

  <!-- ADD CUSTOMER MODAL -->
<div class="modal-backdrop" id="customerBackdrop">
  <div class="modal">
    <div class="modal-header">Add Customer</div>

    <div class="modal-body">
      <input class="input" id="newCustName" placeholder="Customer Name" />
      <input class="input" id="newCustAddress" placeholder="Address" />
      <input class="input" id="newCustPhone" placeholder="Phone Number" />
    </div>

    <div class="modal-actions">
      <button class="modal-clear-btn" id="cancelCustomer">Cancel</button>
      <button class="modal-confirm-btn" id="saveCustomer">Add Customer</button>
    </div>
  </div>
</div>

  <!-- PAYMENT MODAL -->
  <div class="modal-backdrop" id="paymentBackdrop">
    <div class="modal">
      <div class="modal-header">Payment</div>

      <div class="modal-body">
        <div class="kv">
          <div>Total Payment</div>
          <div id="payTotal">₱0.00</div>
        </div>

        <label class="payment-label">Payment Method</label>
        <select class="input" id="paymentMethod">
          <option value="Cash">Cash</option>
          <option value="Paypal">Paypal</option>
        </select>
      </div>

      <div class="modal-actions">
        <button class="modal-clear-btn" id="clearPayment">Clear</button>
        <button class="modal-confirm-btn" id="confirmPayment">Confirm</button>
      </div>
    </div>
  </div>

  <!-- CLEAR CONFIRMATION MODAL -->
  <div class="modal-backdrop" id="clearConfirmBackdrop">
    <div class="modal">
      <div class="modal-header" id="clearModalHeader">Clear Transaction</div>

      <div class="modal-body">
        <p style="margin: 0; font-size: 14px; color: #374151;" id="clearModalMessage">
          Are you sure you want to clear all transaction data? This action cannot be undone.
        </p>
      </div>

      <div class="modal-actions" id="clearModalActions">
        <button class="modal-clear-btn" id="cancelClear">Cancel</button>
        <button class="modal-confirm-btn" id="confirmClear">Yes, Clear</button>
      </div>
    </div>
  </div>

  <!-- TOAST -->
  <div class="toast" id="toast">✅ Transaction completed successfully!</div>
  <div class="toast toast-clear" id="clearToast">🗑️ Transaction cleared successfully!</div>

  <script src="../adminFunction/posFunction.js">
  </script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Receipt Management</title>

    <link rel="stylesheet" href="../styles/adminStyle.css" />
    <link rel="stylesheet" href="../styles/adminReceipt.css" />
</head>

<body>
    <div class="container">

        <div class="sidebar" id="sidebar">
            <div class="company-name">Papa J's</div>
            <a href="dashboard.php" class="menu-item">📊 Dashboard</a>
            <a href="pos.php" class="menu-item">📠 POS</a>
            <a href="inventory.php" class="menu-item">🗂️ Inventory</a>
            <a href="receipt.php" class="menu-item active">🧾 Receipt Management</a>
            <div class="logout">
                <a href="login.html" class="menu-item">➜ Log Out</a>
            </div>
        </div>

        <!-- MAIN -->
        <div class="main-content" id="mainContent">

            <div class="header">
                <div class="header-left">
                    <button class="burger-btn" id="burgerBtn">
            <span></span><span></span><span></span>
          </button>
                    <h1 class="dashboard-title">Receipt Management</h1>
                </div>

                <div class="user-info">
                    <div class="user-text">
                        <div class="user-name">Account Name</div>
                        <div class="user-role">Admin</div>
                    </div>
                    <div class="user-avatar"></div>
                </div>
            </div>

            <section class="receipt-wrapper">

                <div class="receipt-card">

                    <div class="receipt-controls">
                        <input class="receipt-search" id="searchInput" placeholder="Search by receipt, customer name or phone..." />

                        <select class="receipt-type-filter" id="laundryTypeFilter">
              <option value="All">Laundry Type</option>
              <option value="Clothes">Clothes</option>
              <option value="Pants">Pants</option>
              <option value="Comforter">Comforter</option>
              <option value="Curtains">Curtains</option>
              <option value="Mix Clothes">Mix Clothes</option>
              <option value="Blanket">Blanket</option>
            </select>

                        <select class="receipt-status-filter" id="paymentFilter">
              <option value="All">Status</option>
              <option value="Paid">Paid</option>
              <option value="Unpaid">Unpaid</option>
            </select>
                    </div>

                    <div class="receipt-table-wrapper">
                        <table class="receipt-table">
                            <thead>
                                <tr>
                                    <th>Receipt</th>
                                    <th>Customer</th>
                                    <th>Phone</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Laundry Status</th>
                                    <th>Due Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="receiptTable"></tbody>
                        </table>
                    </div>

                </div>

            </section>
        </div>
    </div>

    <!-- VIEW / EDIT MODAL -->
    <div class="modal-backdrop" id="viewModal">
        <div class="modal">

            <div class="modal-header">
                <div>Receipt Details — <span id="mReceipt"></span></div>
                <div class="close-x" id="closeModal">✖</div>
            </div>

            <div class="modal-body">

                <div class="info-grid">
                    <div>
                        <div class="muted">Customer Name</div>
                        <div id="mName" class="modal-bold"></div>
                    </div>

                    <div>
                        <div class="muted">Phone Number</div>
                        <div id="mPhone"></div>
                    </div>

                    <div>
                        <div class="muted">Due Date</div>
                        <input class="input" type="date" id="mDate" />
                    </div>

                    <div>
                        <div class="muted">Payment Status</div>
                        <select class="input" id="mPaymentStatus">
              <option>Paid</option>
              <option>Unpaid</option>
            </select>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="kv">
                        <div>Total Amount</div>
                        <div id="mTotal">₱0.00</div>
                    </div>

                    <div class="kv">
                        <div>Paid Amount</div>
                        <input class="input" id="mPaidInput" type="number" min="0" step="0.01" />
                    </div>

                    <div class="kv">
                        <div>Balance</div>
                        <div id="mBalance">₱0.00</div>
                    </div>
                </div>

                <h4 class="modal-items-title">Items</h4>

                <table class="mini-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Quantity (Kg)</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="mItems"></tbody>
                </table>

            </div>

            <div class="modal-actions">
                <button class="btn" id="cancelView">Cancel</button>
                <button class="complete-btn" id="saveView">Save</button>
            </div>

        </div>
    </div>

    <div class="toast" id="toast">✅ Saved!</div>
    <div class="modal-backdrop" id="textReceiptModal">
        <div class="modal receipt-text-modal">

            <div class="modal-header">
                <div>Receipt Details</div>
                <div class="close-x" onclick="closeTextReceipt()">✖</div>
            </div>

            <div class="modal-body" id="textReceiptBody" style="line-height: 1.6; font-size: 17px;"></div>

            <div class="modal-actions">
                <button class="btn" onclick="closeTextReceipt()">Close</button>
            </div>

        </div>
    </div>
    <div class="modal-backdrop" id="paymentModal" style="display:none;">
        <div class="modal small-modal">

            <div class="modal-header">
                <div>Update Payment</div>
                <div class="close-x" onclick="closePaymentModal()">✖</div>
            </div>

            <div class="modal-body">

                <div class="kv">
                    <div>Total Amount</div>
                    <div id="pTotal">₱0.00</div>
                </div>

                <div class="kv">
                    <div>Amount Paid</div>
                    <input class="input" id="pPaid" type="number" min="0" step="0.01">
                </div>

                <div class="kv">
                    <div>Balance</div>
                    <div id="pBalance">₱0.00</div>
                </div>

                <div class="kv">
                    <div>Payment Status</div>
                    <select class="input" id="pStatus">
                    <option>Paid</option>
                    <option>Unpaid</option>
                </select>
                </div>

            </div>

            <div class="modal-actions">
                <button class="btn" onclick="closePaymentModal()">Cancel</button>
                <button class="complete-btn" onclick="savePayment()">Save</button>
            </div>

        </div>
    </div>
    <script src="../adminFunction/receiptFunction.js">
        
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <script src="../../javascript/chart.js"></script>
    <link rel="stylesheet" href="../styles/adminStyle.css" />
    <link rel="stylesheet" href="../styles/adminDashboard.css" />
</head>

<body>
    <div class="container">

        <div class="sidebar" id="sidebar">
            <div class="company-name">Papa J's</div>
            <a href="dashboard.php" class="menu-item active">📊 Dashboard</a>
            <a href="pos.php" class="menu-item">📠 POS</a>
            <a href="inventory.php" class="menu-item">🗂️ Inventory</a>
            <a href="receipt.php" class="menu-item">🧾 Receipt Management</a>
            <div class="logout">
                <a href="../index.php" class="menu-item">➜ Log Out</a>
            </div>
        </div>

        <div class="main-content" id="mainContent">
            <div class="header">
                <div class="header-left">
                    <button class="burger-btn" id="burgerBtn">
            <span></span><span></span><span></span>
          </button>
                    <h1 class="dashboard-title">Dashboard</h1>
                </div>
                <div class="user-info">
                    <div class="user-text">
                        <div class="user-name">Account Name</div>
                        <div class="user-role">Admin</div>
                    </div>
                    <div class="user-avatar"></div>
                </div>
            </div>

            <!-- STATS -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-label">Total Sales</div>
                    <div class="stat-value" id="totalSales">₱0.00</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Pending Sales</div>
                    <div class="stat-value" id="totalOrders">0</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Items in shop</div>
                    <div class="stat-value" id="itemsInShop">-</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Overdue Items</div>
                    <div class="stat-value" id="overdueItems">-</div>
                </div>
            </div>

            <!-- CHART -->
            <div class="chart-container">
                <div class="chart-title">Revenue</div>
                <div class="chart-controls">
                    <button class="chart-btn active" onclick="updateRevenue('weekly')">Weekly</button>
                    <button class="chart-btn" onclick="updateRevenue('monthly')">Monthly</button>
                    <button class="chart-btn" onclick="updateRevenue('yearly')">Yearly</button>
                </div>
                <canvas id="revenueChart"></canvas>
            </div>



            <!-- TRANSACTION TABLE -->
            <div class="table-container">
                <div class="chart-title">Recent Transactions</div>
                <table>
                    <thead>
                        <tr>
                            <th>Receipt ID</th>
                            <th>Customer</th>
                            <th>Amount (₱)</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="transactionsBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../adminFunction/dashboardFunction.js">
    </script>
</body>

</html>
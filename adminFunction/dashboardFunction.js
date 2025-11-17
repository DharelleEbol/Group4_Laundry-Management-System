// SIDEBAR TOGGLE
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            const mainContent = document.getElementById("mainContent");
            const burgerBtn = document.getElementById("burgerBtn");

            sidebar.classList.toggle("closed");
            mainContent.classList.toggle("expanded");
            burgerBtn.classList.toggle("active");
        }

        document.getElementById("burgerBtn").onclick = toggleSidebar;

        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const burgerBtn = document.getElementById('burgerBtn');

            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !burgerBtn.contains(event.target)) {
                    sidebar.classList.add('closed');
                    document.getElementById('mainContent').classList.add('expanded');
                    burgerBtn.classList.remove('active');
                }
            }
        });

        // -----------------------------
        // FORMATTER
        // -----------------------------
        const peso = n => '₱' + (Number(n || 0)).toFixed(2);

        // -----------------------------
        // LOAD DASHBOARD DATA
        // -----------------------------
        function loadDashboardData() {
            const transactions = JSON.parse(localStorage.getItem('transactions') || '[]');
            const totalSalesEl = document.getElementById('totalSales');
            const totalOrdersEl = document.getElementById('totalOrders');
            const itemsInShopEl = document.getElementById('itemsInShop');
            const overdueItemsEl = document.getElementById('overdueItems');
            const tbody = document.getElementById('transactionsBody');

            tbody.innerHTML = '';

            if (transactions.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;color:#777;">No transactions yet</td></tr>`;
                totalSalesEl.textContent = '₱0.00';
                totalOrdersEl.textContent = '0';
                itemsInShopEl.textContent = '-';
                overdueItemsEl.textContent = '-';
                return;
            }

            const sorted = transactions.sort((a, b) => new Date(b.date) - new Date(a.date));

            const totalSales = sorted.reduce((sum, tx) => {
                return sum + (tx.totals && tx.totals.total ? tx.totals.total : 0);
            }, 0);
            totalSalesEl.textContent = peso(totalSales);
            totalOrdersEl.textContent = sorted.length;
            itemsInShopEl.textContent = '-';
            overdueItemsEl.textContent = '-';

            sorted.slice(0, 10).forEach(tx => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
          <td>${tx.receipt}</td>
          <td>${tx.customer?.name || 'N/A'}</td>
          <td>${peso(tx.totals?.total)}</td>
          <td>${tx.date}</td>
          <td>${tx.payment?.method ? 'Completed' : 'Pending'}</td>
        `;
                tbody.appendChild(tr);
            });
        }

        loadDashboardData();

        // -----------------------------
        // REVENUE CHART (WEEKLY / MONTHLY / YEARLY)
        // -----------------------------
        let revenueChart;

        const revenueData = {
            weekly: {
                labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                values: [4000, 5000, 7500, 7000, 8500, 9000, 9500]
            },
            monthly: {
                labels: ["Week 1", "Week 2", "Week 3", "Week 4"],
                values: [20000, 25000, 23000, 28000]
            },
            yearly: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                ],
                values: [120000, 135000, 150000, 145000, 160000, 170000,
                    180000, 175000, 165000, 155000, 140000, 130000
                ]
            }
        };

        function updateRevenue(type) {
            const data = revenueData[type];

            // Highlight active tab
            document.querySelectorAll(".chart-btn").forEach(btn => btn.classList.remove("active"));
            document.querySelector(`.chart-btn[onclick="updateRevenue('${type}')"]`).classList.add("active");

            // Update chart data
            revenueChart.data.labels = data.labels;
            revenueChart.data.datasets[0].data = data.values;
            revenueChart.update();
        }

        // Initialize Chart
        const ctx = document.getElementById("revenueChart").getContext("2d");
        revenueChart = new Chart(ctx, {
            type: "line",
            data: {
                labels: revenueData.weekly.labels,
                datasets: [{
                    label: "Revenue",
                    data: revenueData.weekly.values,
                    borderColor: "#2196F3",
                    backgroundColor: "rgba(33, 150, 243, 0.2)",
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: "#2196F3",
                    pointBorderColor: "#fff",
                    pointBorderWidth: 2,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
            }
        });
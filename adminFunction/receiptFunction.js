function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("closed");
            document.getElementById("mainContent").classList.toggle("expanded");
            document.getElementById("burgerBtn").classList.toggle("active");
        }

        document.getElementById("burgerBtn").onclick = toggleSidebar;

        const peso = n => '₱' + Number(n || 0).toFixed(2);
        let transactions = JSON.parse(localStorage.getItem('transactions') || '[]');
        let currentIndex = null;

        function computeTotals(tx) {
            const subtotal = tx.items.reduce((s, i) => s + i.rate * i.kg, 0);
            const total = subtotal;
            const paid = (tx.payment && tx.payment.tendered) || 0;
            const balance = Math.max(0, total - paid);
            const status = paid >= total ? 'Paid' : 'Unpaid';
            return {
                total,
                paid,
                balance,
                status
            };
        }

        function rollupLaundryStatus(tx) {
            const statuses = tx.items.map(i => i.status || 'Pending');
            if (statuses.includes('Pending')) return 'Pending';
            if (statuses.includes('Washing')) return 'Washing';
            if (statuses.includes('Washed')) return 'Washed';
            return 'Complete';
        }

        function badge(htmlStatus, map) {
            const cls = (map[htmlStatus] || htmlStatus).toLowerCase();
            return `<span class="badge ${cls}">${htmlStatus}</span>`;
        }

        function renderTable() {
            const tbody = document.getElementById('receiptTable');
            const q = document.getElementById('searchInput').value.toLowerCase();
            const typeFilter = document.getElementById('laundryTypeFilter').value;
            const payFilter = document.getElementById('paymentFilter').value;

            tbody.innerHTML = '';

            transactions.forEach((tx, idx) => {
                if (q) {
                    const hay = (tx.receipt + ' ' + tx.customer.name + ' ' + tx.customer.phone).toLowerCase();
                    if (!hay.includes(q)) return;
                }

                if (typeFilter !== 'All' && !tx.items.some(i => i.name === typeFilter)) return;

                const {
                    total,
                    paid,
                    balance,
                    status
                } = computeTotals(tx);

                if (payFilter !== 'All' && status !== payFilter) return;

                const laundryStatus = rollupLaundryStatus(tx);

                const tr = document.createElement('tr');
                tr.innerHTML = `
          <td>${tx.receipt}</td>
          <td>${tx.customer.name}</td>
          <td>${tx.customer.phone || ''}</td>
          <td>${peso(total)}</td>
          <td>${peso(paid)}</td>
          <td>${peso(balance)}</td>
          <td>${badge(status, { Paid: 'paid', Unpaid: 'unpaid' })}</td>
          <td>${badge(laundryStatus, { Pending: 'pending', Washing: 'washing', Washed: 'washed', Complete: 'complete' })}</td>
          <td>${tx.date || ''}</td>
         <td>
         <span class="action-view" onclick="openReceiptText(${idx})">👁️</span>
         <span class="action-edit" onclick="openPayment(${idx})">✏️</span>
          </td>
        `;
                tbody.appendChild(tr);
            });
        }

        function openView(index) {
            currentIndex = index;
            const tx = transactions[index];
            const totals = computeTotals(tx);

            document.getElementById('mReceipt').textContent = tx.receipt;
            document.getElementById('mName').textContent = tx.customer.name;
            document.getElementById('mPhone').textContent = tx.customer.phone;
            document.getElementById('mDate').value = tx.date;
            document.getElementById('mPaymentStatus').value = totals.status;
            document.getElementById('mTotal').textContent = peso(totals.total);
            document.getElementById('mPaidInput').value = totals.paid;
            document.getElementById('mBalance').textContent = peso(totals.balance);

            const body = document.getElementById('mItems');
            body.innerHTML = '';

            tx.items.forEach((it, i) => {
                const row = document.createElement('tr');
                row.innerHTML = `
          <td>${it.name}</td>
          <td>${it.kg}</td>
          <td>${peso(it.rate * it.kg)}</td>
          <td>
            <select class="select" data-idx="${i}">
              <option ${ (it.status || 'Pending') === 'Pending' ? 'selected' : '' }>Pending</option>
              <option ${ it.status === 'Washing' ? 'selected' : '' }>Washing</option>
              <option ${ it.status === 'Washed' ? 'selected' : '' }>Washed</option>
              <option ${ it.status === 'Complete' ? 'selected' : '' }>Complete</option>
            </select>
          </td>
        `;
                body.appendChild(row);
            });

            document.getElementById('viewModal').style.display = 'flex';
        }

        document.getElementById('closeModal').onclick = () => {
            document.getElementById('viewModal').style.display = 'none';
        };

        document.getElementById('cancelView').onclick = () => {
            document.getElementById('viewModal').style.display = 'none';
        };

        document.getElementById('mPaidInput').addEventListener('input', () => {
            const tx = transactions[currentIndex];
            const total = computeTotals(tx).total;
            const paid = Number(document.getElementById('mPaidInput').value || 0);
            document.getElementById('mBalance').textContent = peso(Math.max(0, total - paid));
            document.getElementById('mPaymentStatus').value = paid >= total ? 'Paid' : 'Unpaid';
        });

        function openReceiptText(index) {
            const tx = transactions[index];
            const totals = computeTotals(tx);

            const itemsList = tx.items
                .map(i => `
          <b>Item:</b> ${i.name}<br>
          <b>Laundry Type:</b> ${i.name}<br>
          <b>Quantity:</b> ${i.kg}<br>
          <b>Price:</b> ${peso(i.rate * i.kg)}<br>
          <b>Penalty:</b> ₱0<br><br>
          `)
                .join('');

            const html = `
          <b>Receipt ID:</b> ${tx.receipt}<br>
          <b>Name:</b> ${tx.customer.name}<br>
          ${itemsList}
          <b>Payment Status:</b> ${totals.status}<br>
          <b>Laundry Status:</b> ${rollupLaundryStatus(tx)}<br>
          `;

            document.getElementById('textReceiptBody').innerHTML = html;
            document.getElementById('textReceiptModal').style.display = 'flex';
        }

        function closeTextReceipt() {
            document.getElementById('textReceiptModal').style.display = 'none';
        }
        document.getElementById('saveView').onclick = () => {
            const tx = transactions[currentIndex];

            tx.date = document.getElementById('mDate').value;
            const paidVal = Number(document.getElementById('mPaidInput').value || 0);

            tx.payment = tx.payment || {
                method: 'Cash',
                tendered: 0,
                change: 0
            };
            tx.payment.tendered = paidVal;

            document.querySelectorAll('#mItems select').forEach(sel => {
                const i = Number(sel.getAttribute('data-idx'));
                tx.items[i].status = sel.value;
            });

            localStorage.setItem('transactions', JSON.stringify(transactions));

            const t = document.getElementById('toast');
            t.style.display = 'block';
            setTimeout(() => t.style.display = 'none', 2000);

            renderTable();
            document.getElementById('viewModal').style.display = 'none';
        };

        document.getElementById('searchInput').addEventListener('input', renderTable);
        document.getElementById('laundryTypeFilter').addEventListener('change', renderTable);
        document.getElementById('paymentFilter').addEventListener('change', renderTable);
        let paymentIndex = null;

        function openPayment(index) {
            paymentIndex = index;
            const tx = transactions[index];
            const totals = computeTotals(tx);

            document.getElementById("pTotal").textContent = peso(totals.total);
            document.getElementById("pPaid").value = totals.paid;
            document.getElementById("pBalance").textContent = peso(totals.balance);
            document.getElementById("pStatus").value = totals.status;

            document.getElementById("paymentModal").style.display = "flex";
        }

        function closePaymentModal() {
            document.getElementById("paymentModal").style.display = "none";
        }

        document.getElementById("pPaid").addEventListener("input", () => {
            const total = computeTotals(transactions[paymentIndex]).total;
            const paid = Number(document.getElementById("pPaid").value || 0);
            const balance = Math.max(0, total - paid);
            document.getElementById("pBalance").textContent = peso(balance);
            document.getElementById("pStatus").value = paid >= total ? "Paid" : "Unpaid";
        });

        function savePayment() {
            const tx = transactions[paymentIndex];

            const paid = Number(document.getElementById("pPaid").value || 0);

            // Ensure payment object exists
            if (!tx.payment) tx.payment = {
                method: "Cash",
                tendered: 0,
                change: 0
            };

            tx.payment.tendered = paid;

            // Save to localStorage
            localStorage.setItem("transactions", JSON.stringify(transactions));

            // Update the table
            renderTable();

            // Close modal
            closePaymentModal();

            // Toast notification
            const t = document.getElementById("toast");
            t.style.display = "block";
            setTimeout(() => t.style.display = "none", 2000);
        }
        renderTable();
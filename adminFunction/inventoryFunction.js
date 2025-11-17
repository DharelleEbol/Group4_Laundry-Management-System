
    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("closed");
      document.getElementById("mainContent").classList.toggle("expanded");
      document.getElementById("burgerBtn").classList.toggle("active");
    }

    document.getElementById("burgerBtn").onclick = toggleSidebar;

    const peso = n => '₱' + (Number(n || 0)).toFixed(2);
    let transactions = JSON.parse(localStorage.getItem('transactions') || '[]');
    let selectedItem = null;
    let deleteItem = null;

    function renderInventory() {
      const tbody = document.getElementById('inventoryTable');
      const search = document.getElementById('searchInput').value.toLowerCase();
      const filter = document.getElementById('statusFilter').value;
      tbody.innerHTML = '';

      transactions.forEach((tx, txIndex) => {
        tx.items.forEach((item, itemIndex) => {
          const itemStatus = item.status || 'Pending';

          if (
            (search && !(
              tx.receipt.toLowerCase().includes(search) ||
              tx.customer.name.toLowerCase().includes(search) ||
              tx.customer.phone.toLowerCase().includes(search)
            )) ||
            (filter !== 'All' && itemStatus !== filter)
          ) return;

          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td>${tx.receipt}</td>
            <td>${tx.customer.name}</td>
            <td>${item.name}</td>
            <td>${item.kg}</td>
            <td>${peso(item.rate * item.kg)}</td>
            <td><span class="badge ${itemStatus.toLowerCase()}">${itemStatus}</span></td>
            <td>${tx.date}</td>
            <td>
              <button class="btn" onclick="openEditModal(${txIndex}, ${itemIndex})">Edit</button>
              <button class="btn delete" onclick="openDeleteModal(${txIndex}, ${itemIndex})">Delete</button>
            </td>
          `;
          tbody.appendChild(tr);
        });
      });
    }

    function openEditModal(txIndex, itemIndex) {
      selectedItem = { txIndex, itemIndex };
      const item = transactions[txIndex].items[itemIndex];
      document.getElementById('modalStatus').value = item.status || 'Pending';
      document.getElementById('modalDate').value = transactions[txIndex].date;
      document.getElementById('editModal').style.display = 'flex';
    }

    document.getElementById('cancelModal').onclick = () => {
      document.getElementById('editModal').style.display = 'none';
    };

    document.getElementById('saveModal').onclick = () => {
      const { txIndex, itemIndex } = selectedItem;
      const newStatus = document.getElementById('modalStatus').value;
      const newDate = document.getElementById('modalDate').value;

      transactions[txIndex].items[itemIndex].status = newStatus;
      transactions[txIndex].date = newDate;

      localStorage.setItem('transactions', JSON.stringify(transactions));
      document.getElementById('editModal').style.display = 'none';
      showToast("✅ Inventory updated!");
      renderInventory();
    };

    function openDeleteModal(txIndex, itemIndex) {
      deleteItem = { txIndex, itemIndex };
      document.getElementById('deleteModal').style.display = 'flex';
    }

    document.getElementById('cancelDelete').onclick = () => {
      document.getElementById('deleteModal').style.display = 'none';
    };

    document.getElementById('confirmDelete').onclick = () => {
      const { txIndex, itemIndex } = deleteItem;
      transactions[txIndex].items.splice(itemIndex, 1);

      if (transactions[txIndex].items.length === 0) {
        transactions.splice(txIndex, 1);
      }

      localStorage.setItem('transactions', JSON.stringify(transactions));
      document.getElementById('deleteModal').style.display = 'none';
      showToast("🗑️ Item deleted successfully!");
      renderInventory();
    };

    function showToast(message) {
      const t = document.getElementById('toast');
      t.textContent = message;
      t.style.display = 'block';
      setTimeout(() => t.style.display = 'none', 2500);
    }

    document.getElementById('searchInput').addEventListener('input', renderInventory);
    document.getElementById('statusFilter').addEventListener('change', renderInventory);

    renderInventory();

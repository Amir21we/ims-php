function setMovementType(type) {
    document.getElementById('movement_type').value = type;
    document.getElementById('btn_in').classList.toggle('active', type === 'in');
    document.getElementById('btn_out').classList.toggle('active', type === 'out');
    updateLivePreview();
}

function changeQuantity(delta) {
    let qty = parseInt(document.getElementById('qty').value) || 10;
    qty = Math.max(1, qty + delta);
    document.getElementById('qty').value = qty;
    updateLivePreview();
}

function updateLivePreview() {
    let qty = parseInt(document.getElementById('qty').value) || 10;
    let type = document.getElementById('movement_type').value;
    let current = 142;
    let projected = type === 'in' ? current + qty : current - qty;
    document.getElementById('projected_stock').textContent = projected;
}

function filterProducts() {
    let input = document.getElementById('searchInput').value.toLowerCase();
    let rows = document.querySelectorAll('.product-row');
    rows.forEach(row => {
        let name = row.getAttribute('data-name');
        row.style.display = name.includes(input) ? '' : 'none';
    });
}

function showSuccess(msg) {
    let toast = document.createElement('div');
    toast.style.cssText = `position:fixed; bottom:30px; right:30px; background:#10b981; color:white; padding:16px 24px; border-radius:16px; box-shadow:0 10px 30px rgba(16,185,129,0.4); z-index:10000;`;
    toast.innerHTML = `<i class="fa-solid fa-check-circle"></i> ${msg}`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

function showError(msg) {
    let toast = document.createElement('div');
    toast.style.cssText = `position:fixed; bottom:30px; right:30px; background:#dc2626; color:white; padding:16px 24px; border-radius:16px; box-shadow:0 10px 30px rgba(220,38,38,0.35); z-index:10000;`;
    toast.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${msg}`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3500);
}

document.addEventListener('DOMContentLoaded', () => {
    const qtyInput = document.getElementById('qty');
    if (qtyInput) qtyInput.addEventListener('input', updateLivePreview);
});
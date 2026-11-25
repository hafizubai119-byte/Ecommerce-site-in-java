const $ = (s, r = document) => r.querySelector(s);
const toast = (msg) => {
  const t = $('#toast');
  t.textContent = msg;
  t.classList.add('show');
  clearTimeout(toast.id);
  toast.id = setTimeout(() => t.classList.remove('show'), 2200);
};
async function cart(payload) {
  const res = await fetch('cart_action.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload),
  });
  return res.json();
}
document.addEventListener('click', async (e) => {
  const add = e.target.closest('.add-btn');
  if (add) {
    const r = await cart({ action: 'add', id: +add.dataset.id });
    $('#cart-count').textContent = r.count;
    toast(r.error || 'Added to cart');
    return;
  }
  const qty = e.target.closest('.qty-btn');
  if (qty) { await cart({ action: 'set', id: +qty.dataset.id, qty: +qty.dataset.qty }); location.reload(); return; }
  const rm = e.target.closest('.remove-btn');
  if (rm) { await cart({ action: 'remove', id: +rm.dataset.id }); location.reload(); }
});

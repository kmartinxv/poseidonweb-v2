import { reactive, computed } from 'vue'

const state = reactive({ items: [] })

export function useCart() {
  const itemCount = computed(() => state.items.reduce((s, i) => s + i.qty, 0))
  const subtotal  = computed(() => state.items.reduce((s, i) => s + i.price * i.qty, 0))
  const shipping  = computed(() => subtotal.value > 5000 ? 0 : 350)
  const total     = computed(() => subtotal.value + shipping.value)

  function addItem(product, qty = 1) {
    const existing = state.items.find(i => i.id === product.id)
    if (existing) existing.qty += qty
    else state.items.push({ ...product, qty })
  }

  function removeItem(id) {
    const idx = state.items.findIndex(i => i.id === id)
    if (idx > -1) state.items.splice(idx, 1)
  }

  function updateQty(id, qty) {
    const item = state.items.find(i => i.id === id)
    if (!item) return
    if (qty <= 0) removeItem(id)
    else item.qty = qty
  }

  function clearCart() { state.items.splice(0) }

  function fmt(n) {
    return 'KES ' + n.toLocaleString()
  }

  return { cart: state, itemCount, subtotal, shipping, total, fmt, addItem, removeItem, updateQty, clearCart }
}

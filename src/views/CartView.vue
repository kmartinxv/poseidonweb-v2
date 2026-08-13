<template>
  <div class="cart-page">
    <div class="container" style="padding-top:136px;padding-bottom:80px">

      <h1 style="margin-bottom:8px">Your Cart</h1>
      <p class="text-light" style="margin-bottom:40px">{{ itemCount }} {{ itemCount === 1 ? 'item' : 'items' }}</p>

      <!-- Empty cart -->
      <div v-if="cart.items.length === 0" class="empty-cart">
        <div class="empty-icon">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        </div>
        <h3>Your cart is empty</h3>
        <p>Add some vet-approved products for your pet.</p>
        <RouterLink to="/shop" class="btn btn-primary">Shop Now</RouterLink>
      </div>

      <!-- Cart content -->
      <div v-else class="cart-layout">

        <!-- Items -->
        <div class="cart-items">

          <div class="cart-item card" v-for="item in cart.items" :key="item.id">
            <img :src="item.image" :alt="item.name" class="item-img" />
            <div class="item-info">
              <div class="item-cat">{{ item.category }}</div>
              <RouterLink :to="`/shop/${item.id}`" class="item-name">{{ item.name }}</RouterLink>
              <span class="item-price">KES {{ item.price.toLocaleString() }} each</span>
            </div>
            <div class="item-controls">
              <div class="qty-controls">
                <button class="qty-btn" @click="updateQty(item.id, item.qty - 1)">−</button>
                <span class="qty-val">{{ item.qty }}</span>
                <button class="qty-btn" @click="updateQty(item.id, item.qty + 1)">+</button>
              </div>
              <strong class="item-total">KES {{ (item.price * item.qty).toLocaleString() }}</strong>
              <button class="remove-btn" @click="removeItem(item.id)" aria-label="Remove">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
              </button>
            </div>
          </div>

          <div class="cart-actions">
            <RouterLink to="/shop" class="btn btn-secondary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
              Continue Shopping
            </RouterLink>
            <button class="btn btn-secondary" @click="clearCart" style="color:var(--red);border-color:var(--red)">
              Clear Cart
            </button>
          </div>
        </div>

        <!-- Order summary -->
        <div class="order-summary card">
          <h3>Order Summary</h3>
          <div class="summary-rows">
            <div class="summary-row">
              <span>Subtotal</span>
              <span>{{ fmt(subtotal) }}</span>
            </div>
            <div class="summary-row">
              <span>Shipping</span>
              <span :class="{ teal: shipping === 0 }">{{ shipping === 0 ? 'FREE' : fmt(shipping) }}</span>
            </div>
            <div v-if="shipping > 0" class="free-shipping-note">
              Add {{ fmt(5000 - subtotal) }} more for free shipping
            </div>
            <div class="summary-divider"></div>
            <div class="summary-row total">
              <span>Total</span>
              <span>{{ fmt(total) }}</span>
            </div>
          </div>
          <RouterLink to="/checkout" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;margin-top:20px">
            Proceed to Checkout
          </RouterLink>
          <div class="secure-note">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Your information is secure and encrypted
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { useCart } from '../composables/useCart.js'

const { cart, itemCount, subtotal, shipping, total, fmt, removeItem, updateQty, clearCart } = useCart()
</script>

<style scoped>
.empty-cart {
  text-align: center;
  padding: 80px 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 14px;
}
.empty-icon {
  width: 96px;
  height: 96px;
  border-radius: 50%;
  background: var(--gray-100);
  display: flex;
  align-items: center;
  justify-content: center;
}
.empty-cart h3 { color: var(--navy); }
.empty-cart p  { color: var(--text-light); }

.cart-layout {
  display: grid;
  grid-template-columns: 1fr 360px;
  gap: 32px;
  align-items: start;
}
.cart-items { display: flex; flex-direction: column; gap: 16px; }
.cart-item {
  display: flex;
  align-items: center;
  gap: 20px;
  padding: 20px;
}
.item-img { width: 80px; height: 80px; border-radius: var(--radius-sm); object-fit: cover; flex-shrink: 0; }
.item-info { flex: 1; min-width: 0; }
.item-cat  { font-size: 0.68rem; font-weight: 700; color: var(--teal); text-transform: uppercase; letter-spacing: 0.08em; }
.item-name { display: block; font-weight: 600; color: var(--navy); font-size: 0.9rem; margin: 4px 0; transition: color var(--transition); }
.item-name:hover { color: var(--teal); }
.item-price { font-size: 0.8rem; color: var(--text-light); }
.item-controls { display: flex; align-items: center; gap: 16px; flex-shrink: 0; }
.qty-controls  { display: flex; align-items: center; }
.qty-btn {
  width: 32px;
  height: 32px;
  border: 1.5px solid var(--border);
  background: white;
  cursor: pointer;
  font-size: 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background var(--transition);
}
.qty-btn:first-child { border-radius: 6px 0 0 6px; }
.qty-btn:last-child  { border-radius: 0 6px 6px 0; }
.qty-btn:hover { background: var(--teal-light); }
.qty-val {
  width: 40px;
  text-align: center;
  border-top: 1.5px solid var(--border);
  border-bottom: 1.5px solid var(--border);
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 0.9rem;
}
.item-total { font-size: 1rem; font-weight: 700; color: var(--navy); min-width: 100px; text-align: right; }
.remove-btn {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: transparent;
  color: var(--gray-400);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background var(--transition), color var(--transition);
}
.remove-btn:hover { background: #FFF3F3; color: var(--red); }
.cart-actions { display: flex; gap: 12px; margin-top: 8px; }

.order-summary { padding: 28px; position: sticky; top: 96px; }
.order-summary h3 { margin-bottom: 20px; }
.summary-rows { display: flex; flex-direction: column; gap: 14px; }
.summary-row { display: flex; justify-content: space-between; font-size: 0.9rem; color: var(--text-light); }
.summary-row.total { font-size: 1.05rem; font-weight: 700; color: var(--navy); }
.summary-divider { height: 1px; background: var(--border); }
.free-shipping-note { font-size: 0.75rem; color: var(--teal); background: var(--teal-light); padding: 8px 12px; border-radius: 6px; }
.secure-note { display: flex; align-items: center; justify-content: center; gap: 6px; font-size: 0.75rem; color: var(--text-light); margin-top: 12px; }

@media (max-width: 900px) {
  .cart-layout { grid-template-columns: 1fr; }
  .order-summary { position: static; }
}
@media (max-width: 600px) {
  .cart-item { flex-wrap: wrap; }
  .item-controls { width: 100%; justify-content: space-between; }
}
</style>

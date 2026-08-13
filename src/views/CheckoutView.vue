<template>
  <div class="checkout-page">
    <div class="container" style="padding-top:136px;padding-bottom:80px">

      <div v-if="!orderPlaced">
        <h1 style="margin-bottom:8px">Checkout</h1>
        <p class="text-light" style="margin-bottom:40px">Complete your order securely below.</p>

        <div v-if="cart.items.length === 0" style="text-align:center;padding:60px 0">
          <h3>Your cart is empty.</h3>
          <RouterLink to="/shop" class="btn btn-primary" style="margin-top:20px">Shop Now</RouterLink>
        </div>

        <div v-else class="checkout-layout">

          <!-- Form -->
          <form class="checkout-form" @submit.prevent="placeOrder">
            <div class="form-section">
              <h3>Contact Information</h3>
              <div class="form-2col">
                <div class="form-group">
                  <label class="form-label">First Name</label>
                  <input class="form-control" v-model="f.firstName" placeholder="Jane" required />
                </div>
                <div class="form-group">
                  <label class="form-label">Last Name</label>
                  <input class="form-control" v-model="f.lastName"  placeholder="Mwangi" required />
                </div>
                <div class="form-group">
                  <label class="form-label">Email</label>
                  <input class="form-control" type="email" v-model="f.email" placeholder="jane@example.com" required />
                </div>
                <div class="form-group">
                  <label class="form-label">Phone (Mpesa)</label>
                  <input class="form-control" type="tel" v-model="f.phone" placeholder="+254 700 000 000" required />
                </div>
              </div>
            </div>

            <div class="form-section">
              <h3>Delivery Address</h3>
              <div class="form-group" style="margin-bottom:16px">
                <label class="form-label">Street Address</label>
                <input class="form-control" v-model="f.address" placeholder="123 Ngong Rd" required />
              </div>
              <div class="form-2col">
                <div class="form-group">
                  <label class="form-label">City</label>
                  <input class="form-control" v-model="f.city" placeholder="Nairobi" required />
                </div>
                <div class="form-group">
                  <label class="form-label">Area / Estate</label>
                  <input class="form-control" v-model="f.area" placeholder="Westlands" />
                </div>
              </div>
            </div>

            <div class="form-section">
              <h3>Payment Method</h3>
              <div class="payment-options">
                <label :class="['pay-option', { active: f.payment === 'mpesa' }]">
                  <input type="radio" v-model="f.payment" value="mpesa" style="display:none" />
                  <div class="pay-radio"></div>
                  <div>
                    <strong>Mpesa</strong>
                    <small>Pay via Mpesa mobile money</small>
                  </div>
                  <span class="pay-tag teal">Recommended</span>
                </label>
                <label :class="['pay-option', { active: f.payment === 'card' }]">
                  <input type="radio" v-model="f.payment" value="card" style="display:none" />
                  <div class="pay-radio"></div>
                  <div>
                    <strong>Credit / Debit Card</strong>
                    <small>Visa, Mastercard accepted</small>
                  </div>
                </label>
                <label :class="['pay-option', { active: f.payment === 'cash' }]">
                  <input type="radio" v-model="f.payment" value="cash" style="display:none" />
                  <div class="pay-radio"></div>
                  <div>
                    <strong>Cash on Delivery</strong>
                    <small>Pay when you receive your order</small>
                  </div>
                </label>
              </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              Place Order · {{ fmt(total) }}
            </button>
          </form>

          <!-- Summary -->
          <div class="checkout-summary card">
            <h3>Your Order</h3>
            <div class="checkout-items">
              <div class="ci" v-for="item in cart.items" :key="item.id">
                <img :src="item.image" :alt="item.name" class="ci-img" />
                <div class="ci-info">
                  <strong>{{ item.name }}</strong>
                  <small>Qty: {{ item.qty }}</small>
                </div>
                <span class="ci-price">KES {{ (item.price * item.qty).toLocaleString() }}</span>
              </div>
            </div>
            <div class="checkout-totals">
              <div class="ct-row"><span>Subtotal</span><span>{{ fmt(subtotal) }}</span></div>
              <div class="ct-row"><span>Shipping</span><span :class="{ teal: shipping === 0 }">{{ shipping === 0 ? 'FREE' : fmt(shipping) }}</span></div>
              <div class="ct-divider"></div>
              <div class="ct-row ct-total"><span>Total</span><span>{{ fmt(total) }}</span></div>
            </div>
          </div>

        </div>
      </div>

      <!-- Success -->
      <div v-else class="order-success">
        <div class="success-icon">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h2>Order Placed!</h2>
        <p>Thank you, <strong>{{ f.firstName }}</strong>! We've received your order and will process it within 24 hours. A confirmation will be sent to {{ f.email }}.</p>
        <div class="success-actions">
          <RouterLink to="/shop" class="btn btn-primary">Continue Shopping</RouterLink>
          <RouterLink to="/"     class="btn btn-secondary">Back to Home</RouterLink>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useCart } from '../composables/useCart.js'

const { cart, subtotal, shipping, total, fmt, clearCart } = useCart()

const orderPlaced = ref(false)
const f = ref({
  firstName: '', lastName: '', email: '', phone: '',
  address: '', city: 'Nairobi', area: '',
  payment: 'mpesa',
})

function placeOrder() {
  orderPlaced.value = true
  clearCart()
}
</script>

<style scoped>
.checkout-layout { display: grid; grid-template-columns: 1fr 360px; gap: 32px; align-items: start; }
.form-section { background: var(--gray-50); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 28px; margin-bottom: 24px; }
.form-section h3 { margin-bottom: 20px; font-size: 1.05rem; }
.form-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.payment-options { display: flex; flex-direction: column; gap: 12px; }
.pay-option {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 14px 16px;
  border: 1.5px solid var(--border);
  border-radius: var(--radius);
  cursor: pointer;
  transition: border-color var(--transition), background var(--transition);
}
.pay-option.active { border-color: var(--teal); background: var(--teal-xlight); }
.pay-radio {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  border: 2px solid var(--border);
  flex-shrink: 0;
  transition: border-color var(--transition), box-shadow var(--transition);
}
.pay-option.active .pay-radio { border-color: var(--teal); box-shadow: inset 0 0 0 5px var(--teal); }
.pay-option strong { display: block; font-size: 0.9rem; color: var(--navy); }
.pay-option small  { font-size: 0.78rem; color: var(--text-light); }
.pay-tag { font-size: 0.68rem; font-weight: 700; padding: 3px 8px; border-radius: 100px; background: var(--teal-light); color: var(--teal); margin-left: auto; }

.checkout-summary { padding: 24px; position: sticky; top: 96px; }
.checkout-summary h3 { margin-bottom: 20px; }
.checkout-items { display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 20px; }
.ci { display: flex; align-items: center; gap: 12px; }
.ci-img  { width: 48px; height: 48px; border-radius: 8px; object-fit: cover; flex-shrink: 0; }
.ci-info { flex: 1; min-width: 0; }
.ci-info strong { display: block; font-size: 0.82rem; color: var(--navy); }
.ci-info small  { font-size: 0.72rem; color: var(--text-light); }
.ci-price { font-size: 0.85rem; font-weight: 700; color: var(--navy); flex-shrink: 0; }
.checkout-totals { display: flex; flex-direction: column; gap: 10px; }
.ct-row { display: flex; justify-content: space-between; font-size: 0.88rem; color: var(--text-light); }
.ct-total { font-size: 1.05rem; font-weight: 700; color: var(--navy); padding-top: 4px; }
.ct-divider { height: 1px; background: var(--border); }

.order-success {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 16px;
  padding: 80px 0;
}
.success-icon {
  width: 96px;
  height: 96px;
  border-radius: 50%;
  background: var(--teal);
  display: flex;
  align-items: center;
  justify-content: center;
}
.order-success p { color: var(--text-light); max-width: 480px; }
.success-actions { display: flex; gap: 12px; margin-top: 8px; flex-wrap: wrap; justify-content: center; }

@media (max-width: 900px)  { .checkout-layout { grid-template-columns: 1fr; } .checkout-summary { position: static; } }
@media (max-width: 600px)  { .form-2col { grid-template-columns: 1fr; } }
</style>

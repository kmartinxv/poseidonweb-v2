<template>
  <div class="product-page" v-if="product">

    <div class="container" style="padding-top:136px;padding-bottom:80px">

      <!-- Breadcrumb -->
      <div class="breadcrumb">
        <RouterLink to="/shop">Shop</RouterLink>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        <span>{{ product.name }}</span>
      </div>

      <div class="product-layout">

        <!-- Image -->
        <div class="product-gallery">
          <div class="main-img card">
            <img :src="product.image" :alt="product.name" />
          </div>
          <span v-if="product.badge" :class="['product-badge', getBadgeClass(product.badge)]">{{ product.badge }}</span>
        </div>

        <!-- Details -->
        <div class="product-details">
          <div class="product-cat">{{ product.category }}</div>
          <h1 style="font-size:clamp(1.4rem,3vw,2rem);margin:8px 0 12px">{{ product.name }}</h1>

          <div class="rating-row">
            <div class="stars">
              <svg v-for="n in 5" :key="n" width="16" height="16" viewBox="0 0 24 24" :fill="n <= Math.round(product.rating) ? 'var(--teal)' : 'var(--gray-200)'"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
            <span class="rating-txt">{{ product.rating }} · {{ product.reviews }} reviews</span>
          </div>

          <div class="price-block">
            <span class="price-main">KES {{ product.price.toLocaleString() }}</span>
            <span v-if="product.oldPrice" class="price-was">KES {{ product.oldPrice.toLocaleString() }}</span>
            <span v-if="product.oldPrice" class="price-save">
              Save KES {{ (product.oldPrice - product.price).toLocaleString() }}
            </span>
          </div>

          <p class="product-desc">{{ product.description }}</p>

          <div class="qty-row">
            <label class="form-label">Quantity</label>
            <div class="qty-controls">
              <button @click="qty > 1 && qty--" class="qty-btn">−</button>
              <span class="qty-val">{{ qty }}</span>
              <button @click="qty++" class="qty-btn">+</button>
            </div>
          </div>

          <div class="add-actions">
            <button class="btn btn-primary btn-lg" :disabled="!product.inStock" @click="handleAdd">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
              {{ added ? 'Added to Cart!' : (product.inStock ? 'Add to Cart' : 'Out of Stock') }}
            </button>
            <RouterLink to="/cart" v-if="added" class="btn btn-secondary btn-lg">View Cart</RouterLink>
          </div>

          <div class="product-assurances">
            <div class="assurance-item" v-for="a in assurances" :key="a.text">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2"><path :d="a.icon"/></svg>
              {{ a.text }}
            </div>
          </div>
        </div>

      </div>

      <!-- Related products -->
      <div class="related-section">
        <h2 style="margin-bottom:28px">You Might Also Like</h2>
        <div class="related-grid">
          <RouterLink :to="`/shop/${r.id}`" class="related-card card" v-for="r in related" :key="r.id">
            <div class="related-img">
              <img :src="r.image" :alt="r.name" loading="lazy" />
            </div>
            <div class="related-info">
              <div class="product-cat">{{ r.category }}</div>
              <strong>{{ r.name }}</strong>
              <span class="price-current" style="display:block;margin-top:4px">KES {{ r.price.toLocaleString() }}</span>
            </div>
          </RouterLink>
        </div>
      </div>

    </div>

  </div>
  <div v-else class="container" style="padding-top:160px;text-align:center">
    <h2>Product not found</h2>
    <RouterLink to="/shop" class="btn btn-primary" style="margin-top:24px">Back to Shop</RouterLink>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import { products } from '../data/products.js'
import { useCart } from '../composables/useCart.js'

const route = useRoute()
const { addItem } = useCart()

const product = computed(() => products.find(p => p.id === Number(route.params.id)))
const related = computed(() =>
  products.filter(p => p.id !== product.value?.id && p.category === product.value?.category).slice(0, 4)
)

const qty   = ref(1)
const added = ref(false)

function handleAdd() {
  if (!product.value?.inStock) return
  addItem(product.value, qty.value)
  added.value = true
  setTimeout(() => (added.value = false), 2000)
}

function getBadgeClass(badge) {
  if (badge === 'Sale') return 'badge-sale'
  if (badge === 'New')  return 'badge-new'
  if (badge === 'Rx')   return 'badge-rx'
  return 'badge-default'
}

const assurances = [
  { text: 'Vet-approved product',     icon: 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z' },
  { text: 'Free delivery over KES 5,000', icon: 'M5 8h14M5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4m14 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4' },
  { text: '7-day return policy',      icon: 'M3 2v6h6M21 12A9 9 0 0 0 3.5 7.5' },
]
</script>

<style scoped>
.breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.82rem;
  color: var(--text-light);
  margin-bottom: 32px;
}
.breadcrumb a { color: var(--teal); }
.product-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 64px;
  align-items: start;
  margin-bottom: 80px;
}
.product-gallery { position: relative; }
.main-img { padding: 0; overflow: hidden; border-radius: var(--radius-lg); height: 480px; }
.main-img img { width: 100%; height: 100%; object-fit: cover; }
.product-badge {
  position: absolute;
  top: 16px;
  left: 16px;
  padding: 5px 12px;
  border-radius: 100px;
  font-size: 0.75rem;
  font-weight: 700;
}
.badge-sale { background: var(--red); color: white; }
.badge-new  { background: var(--teal); color: white; }
.badge-rx   { background: var(--navy); color: white; }
.badge-default { background: var(--gray-200); color: var(--text); }

.product-cat { font-size: 0.72rem; font-weight: 700; color: var(--teal); text-transform: uppercase; letter-spacing: 0.1em; }
.rating-row  { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
.stars       { display: flex; gap: 2px; }
.rating-txt  { font-size: 0.82rem; color: var(--text-light); }
.price-block { margin-bottom: 20px; display: flex; align-items: baseline; gap: 10px; flex-wrap: wrap; }
.price-main  { font-size: 1.75rem; font-weight: 800; color: var(--navy); }
.price-was   { font-size: 1rem; color: var(--gray-400); text-decoration: line-through; }
.price-save  { font-size: 0.82rem; background: #FFF3F3; color: var(--red); padding: 3px 8px; border-radius: 100px; font-weight: 600; }
.product-desc { color: var(--text-light); line-height: 1.7; margin-bottom: 24px; }
.qty-row   { margin-bottom: 20px; }
.qty-controls { display: flex; align-items: center; gap: 0; margin-top: 8px; }
.qty-btn {
  width: 36px;
  height: 36px;
  border: 1.5px solid var(--border);
  background: white;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background var(--transition);
  display: flex;
  align-items: center;
  justify-content: center;
}
.qty-btn:first-child { border-radius: 6px 0 0 6px; }
.qty-btn:last-child  { border-radius: 0 6px 6px 0; }
.qty-btn:hover { background: var(--teal-light); }
.qty-val {
  width: 48px;
  text-align: center;
  border-top: 1.5px solid var(--border);
  border-bottom: 1.5px solid var(--border);
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
}
.add-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 24px; }
.product-assurances { display: flex; flex-direction: column; gap: 10px; padding: 20px; background: var(--teal-xlight); border-radius: var(--radius); border: 1px solid var(--teal-light); }
.assurance-item { display: flex; align-items: center; gap: 10px; font-size: 0.85rem; font-weight: 500; }

.related-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
.related-card { overflow: hidden; text-decoration: none; color: inherit; display: flex; flex-direction: column; }
.related-img { height: 180px; overflow: hidden; }
.related-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 350ms var(--ease-out); }
.related-card:hover .related-img img { transform: scale(1.06); }
.related-info { padding: 14px; }
.related-info strong { display: block; font-size: 0.88rem; color: var(--navy); margin: 4px 0; }

@media (max-width: 900px) {
  .product-layout { grid-template-columns: 1fr; }
  .related-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) { .related-grid { grid-template-columns: 1fr; } }
</style>

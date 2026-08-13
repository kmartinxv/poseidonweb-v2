<template>
  <div class="shop-page">

    <!-- Hero -->
    <section class="shop-hero">
      <div class="container">
        <span class="eyebrow">Poseidon Pet Shop</span>
        <h1>Quality Products,<br><span class="teal wavy-line">Vet Approved</span></h1>
        <p>Prescription foods, supplements, medications, accessories, and grooming essentials — curated by our vets.</p>
        <!-- Category filters -->
        <div class="cat-filters">
          <button
            v-for="cat in categories"
            :key="cat"
            :class="['cat-btn', { active: activeCategory === cat }]"
            @click="activeCategory = cat"
          >{{ cat }}</button>
        </div>
      </div>
    </section>

    <!-- Products grid -->
    <section class="section shop-body">
      <div class="container">
        <div class="shop-header-row">
          <p class="product-count">{{ filteredProducts.length }} products</p>
          <div class="shop-sort">
            <label>Sort by</label>
            <select v-model="sortBy" class="form-control" style="width:auto">
              <option value="default">Featured</option>
              <option value="price-asc">Price: Low–High</option>
              <option value="price-desc">Price: High–Low</option>
              <option value="rating">Top Rated</option>
            </select>
          </div>
        </div>

        <div class="products-grid">
          <div class="product-card card" v-for="p in sortedProducts" :key="p.id">
            <RouterLink :to="`/shop/${p.id}`" class="product-img-wrap">
              <img :src="p.image" :alt="p.name" loading="lazy" />
              <span v-if="p.badge" :class="['product-badge', getBadgeClass(p.badge)]">{{ p.badge }}</span>
              <span v-if="!p.inStock" class="product-oos">Out of Stock</span>
            </RouterLink>
            <div class="product-info">
              <div class="product-cat">{{ p.category }}</div>
              <RouterLink :to="`/shop/${p.id}`"><h3 class="product-name">{{ p.name }}</h3></RouterLink>
              <div class="product-rating">
                <div class="stars-sm">
                  <svg v-for="n in 5" :key="n" width="12" height="12" viewBox="0 0 24 24" :fill="n <= Math.round(p.rating) ? 'var(--teal)' : 'var(--gray-200)'"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
                <span class="rating-count">{{ p.rating }} ({{ p.reviews }})</span>
              </div>
              <div class="product-price-row">
                <div class="price-group">
                  <span class="price-current">KES {{ p.price.toLocaleString() }}</span>
                  <span v-if="p.oldPrice" class="price-old">KES {{ p.oldPrice.toLocaleString() }}</span>
                </div>
                <button
                  :class="['add-btn', { 'added': addedIds.includes(p.id) }]"
                  :disabled="!p.inStock"
                  @click="handleAdd(p)"
                >
                  <svg v-if="!addedIds.includes(p.id)" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                  <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty state -->
        <div v-if="sortedProducts.length === 0" class="empty-state">
          <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <h3>No products found</h3>
          <p>Try a different category.</p>
          <button class="btn btn-primary" @click="activeCategory = 'All'">View All</button>
        </div>
      </div>
    </section>

    <!-- Shop promise strip -->
    <section class="shop-promise">
      <div class="container">
        <div class="promise-grid">
          <div class="promise-item" v-for="p in promises" :key="p.title">
            <div class="icon-circle" style="width:48px;height:48px">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path :d="p.icon"/></svg>
            </div>
            <div>
              <strong>{{ p.title }}</strong>
              <span>{{ p.sub }}</span>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { products, categories } from '../data/products.js'
import { useCart }              from '../composables/useCart.js'
import { useToast }             from '../composables/useToast.js'
import { useScrollReveal }      from '../composables/useScrollReveal.js'

useScrollReveal()

const { addItem }  = useCart()
const { success }  = useToast()
const activeCategory = ref('All')
const sortBy         = ref('default')
const addedIds       = ref([])

const filteredProducts = computed(() =>
  activeCategory.value === 'All'
    ? products
    : products.filter(p => p.category === activeCategory.value)
)

const sortedProducts = computed(() => {
  const list = [...filteredProducts.value]
  if (sortBy.value === 'price-asc')  return list.sort((a, b) => a.price - b.price)
  if (sortBy.value === 'price-desc') return list.sort((a, b) => b.price - a.price)
  if (sortBy.value === 'rating')     return list.sort((a, b) => b.rating - a.rating)
  return list
})

function handleAdd(product) {
  if (!product.inStock) return
  addItem(product)
  success(`${product.name} added to cart`)
  addedIds.value.push(product.id)
  setTimeout(() => {
    addedIds.value = addedIds.value.filter(id => id !== product.id)
  }, 1500)
}

function getBadgeClass(badge) {
  if (badge === 'Sale') return 'badge-sale'
  if (badge === 'New')  return 'badge-new'
  if (badge === 'Rx')   return 'badge-rx'
  return 'badge-default'
}

const promises = [
  { title: 'Vet-Curated Products', sub: 'Every item approved by our team', icon: 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z' },
  { title: 'Free Delivery Over KES 5,000', sub: 'Nairobi CBD & suburbs',    icon: 'M5 8h14M5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4m14 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4m-3 8V8m-4 8V8m-4 8V8' },
  { title: 'Secure Payments',       sub: 'Mpesa & card accepted',           icon: 'M21 4H3a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h18a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4H3' },
  { title: 'Easy Returns',          sub: '7-day return policy',             icon: 'M3 2v6h6M21 12A9 9 0 0 0 3.5 7.5M21 22v-6h-6M3 12a9 9 0 0 0 17.5 4.5' },
]
</script>

<style scoped>
.shop-hero {
  padding: 136px 0 64px;
  background: var(--bg);
  text-align: center;
}
.shop-hero h1  { margin: 8px 0 16px; }
.shop-hero p   { color: var(--text-light); max-width: 520px; margin: 0 auto 32px; }
.cat-filters   { display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; }
.cat-btn {
  padding: 9px 20px;
  border-radius: 100px;
  border: 1.5px solid var(--border);
  background: white;
  font-size: 0.875rem;
  font-weight: 500;
  color: var(--text-light);
  cursor: pointer;
  transition: all var(--transition);
}
.cat-btn:hover  { border-color: var(--teal); color: var(--teal); }
.cat-btn.active { background: var(--teal); border-color: var(--teal); color: white; }

.shop-header-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
.product-count { font-size: 0.875rem; color: var(--text-light); }
.shop-sort { display: flex; align-items: center; gap: 10px; font-size: 0.875rem; color: var(--text-light); }

.products-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
}
.product-card { overflow: hidden; }
.product-img-wrap {
  display: block;
  position: relative;
  height: 220px;
  overflow: hidden;
  background: var(--gray-50);
}
.product-img-wrap img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 350ms var(--ease-out);
}
.product-card:hover .product-img-wrap img { transform: scale(1.07); }
.product-badge {
  position: absolute;
  top: 10px;
  left: 10px;
  padding: 4px 10px;
  border-radius: 100px;
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 0.04em;
}
.badge-sale    { background: var(--red);   color: white; }
.badge-new     { background: var(--teal);  color: white; }
.badge-rx      { background: var(--navy);  color: white; }
.badge-default { background: var(--gray-200); color: var(--text); }
.product-oos {
  position: absolute;
  inset: 0;
  background: rgba(255,255,255,0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.8rem;
  font-weight: 700;
  color: var(--gray-600);
}
.product-info { padding: 16px; }
.product-cat  { font-size: 0.7rem; font-weight: 600; color: var(--teal); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 4px; }
.product-name { font-size: 0.9rem; font-weight: 600; color: var(--navy); margin-bottom: 6px; transition: color var(--transition); }
.product-name:hover { color: var(--teal); }
.product-rating { display: flex; align-items: center; gap: 6px; margin-bottom: 12px; }
.stars-sm       { display: flex; gap: 1px; }
.rating-count   { font-size: 0.72rem; color: var(--text-light); }
.product-price-row { display: flex; align-items: center; justify-content: space-between; }
.price-current { font-size: 1rem; font-weight: 700; color: var(--navy); }
.price-old     { font-size: 0.78rem; color: var(--gray-400); text-decoration: line-through; margin-left: 6px; }
.add-btn {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: var(--teal-light);
  color: var(--teal);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background var(--transition), color var(--transition), transform var(--transition);
  flex-shrink: 0;
}
.add-btn:hover:not(:disabled) { background: var(--teal); color: white; transform: scale(1.1); }
.add-btn.added { background: var(--teal); color: white; }
.add-btn:disabled { opacity: 0.4; cursor: not-allowed; }

.empty-state {
  text-align: center;
  padding: 80px 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 14px;
}
.empty-state h3 { color: var(--gray-400); }
.empty-state p  { color: var(--gray-400); font-size: 0.875rem; }

.shop-promise { background: var(--gray-50); border-top: 1px solid var(--border); padding: 32px 0; }
.promise-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24px;
}
.promise-item { display: flex; align-items: center; gap: 14px; }
.promise-item strong { display: block; font-size: 0.85rem; color: var(--navy); }
.promise-item span   { font-size: 0.75rem; color: var(--text-light); }

@media (max-width: 1200px) { .products-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 900px)  { .products-grid { grid-template-columns: repeat(2, 1fr); } .promise-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 600px)  { .shop-hero { padding-top: 100px; } .products-grid { grid-template-columns: 1fr; } .promise-grid { grid-template-columns: 1fr; } }
</style>

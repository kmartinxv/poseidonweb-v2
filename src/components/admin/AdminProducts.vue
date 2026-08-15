<template>
  <div class="admin-panel">
    <div class="admin-panel-head">
      <div>
        <h2>Products</h2>
        <p class="text-light">{{ products.length }} products in the shop</p>
      </div>
      <button class="btn btn-primary" @click="openCreate">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Product
      </button>
    </div>

    <!-- Add/Edit form -->
    <div v-if="showForm" class="admin-form card">
      <h3>{{ editingId ? 'Edit Product' : 'New Product' }}</h3>
      <form @submit.prevent="save">
        <div class="form-2col">
          <div class="form-group">
            <label class="form-label">Name</label>
            <input class="form-control" v-model="form.name" required />
          </div>
          <div class="form-group">
            <label class="form-label">Category</label>
            <select class="form-control" v-model="form.category_id">
              <option :value="null">— None —</option>
              <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Price (KES)</label>
            <input class="form-control" type="number" step="0.01" min="0" v-model="form.price" required />
          </div>
          <div class="form-group">
            <label class="form-label">Old Price (optional, for Sale badge)</label>
            <input class="form-control" type="number" step="0.01" min="0" v-model="form.old_price" />
          </div>
          <div class="form-group">
            <label class="form-label">Badge</label>
            <select class="form-control" v-model="form.badge">
              <option :value="null">— None —</option>
              <option value="Sale">Sale</option>
              <option value="New">New</option>
              <option value="Rx">Rx</option>
              <option value="Best Seller">Best Seller</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">In Stock?</label>
            <select class="form-control" v-model="form.in_stock">
              <option :value="true">In stock</option>
              <option :value="false">Out of stock</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Rating (1–5)</label>
            <input class="form-control" type="number" step="0.1" min="1" max="5" v-model="form.rating" />
          </div>
          <div class="form-group">
            <label class="form-label">Review Count</label>
            <input class="form-control" type="number" min="0" v-model="form.reviews" />
          </div>
        </div>

        <div class="form-group" style="margin-top:16px">
          <label class="form-label">Description</label>
          <textarea class="form-control" rows="3" v-model="form.description"></textarea>
        </div>

        <div style="margin-top:16px">
          <ImageUploadField v-model="form.image" label="Product Image" />
        </div>

        <div v-if="formError" class="form-error">{{ formError }}</div>

        <div class="admin-form-actions">
          <button type="button" class="btn btn-secondary" @click="closeForm">Cancel</button>
          <button type="submit" class="btn btn-primary" :disabled="saving">{{ saving ? 'Saving…' : 'Save Product' }}</button>
        </div>
      </form>
    </div>

    <!-- Table -->
    <div class="table-card card">
      <table class="admin-table">
        <thead>
          <tr><th></th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Rating</th><th></th></tr>
        </thead>
        <tbody>
          <tr v-for="p in products" :key="p.id">
            <td><img v-if="p.image" :src="p.image" class="row-thumb" /></td>
            <td><strong>{{ p.name }}</strong>{{ p.badge ? ' · ' : '' }}<span v-if="p.badge" class="row-badge">{{ p.badge }}</span></td>
            <td>{{ p.category_name || '—' }}</td>
            <td>
              KES {{ Number(p.price).toLocaleString() }}
              <span v-if="p.old_price" class="row-old-price">KES {{ Number(p.old_price).toLocaleString() }}</span>
            </td>
            <td><span :class="['stock-chip', p.in_stock ? 'in' : 'out']">{{ p.in_stock ? 'In stock' : 'Out of stock' }}</span></td>
            <td>{{ p.rating }} ({{ p.reviews }})</td>
            <td class="row-actions">
              <button class="row-btn" @click="openEdit(p)">Edit</button>
              <button class="row-btn danger" @click="remove(p)">Delete</button>
            </td>
          </tr>
          <tr v-if="!products.length && !loading"><td colspan="7" class="empty-row">No products yet.</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { api }            from '../../lib/api.js'
import { useToast }       from '../../composables/useToast.js'
import ImageUploadField   from './ImageUploadField.vue'

const { success, error: toastError } = useToast()

const products = ref([])
const categories = ref([])
const loading  = ref(true)

async function loadProducts() {
  loading.value = true
  const data = await api.get('/products/list.php')
  products.value = data.products
  loading.value = false
}
async function loadCategories() {
  const data = await api.get('/categories/list.php')
  categories.value = data.categories
}
loadProducts()
loadCategories()

const showForm  = ref(false)
const editingId = ref(null)
const saving    = ref(false)
const formError = ref('')

function blankForm() {
  return { name: '', category_id: null, price: '', old_price: '', description: '', image: '', badge: null, in_stock: true, rating: 5, reviews: 0 }
}
const form = reactive(blankForm())

function openCreate() {
  Object.assign(form, blankForm())
  editingId.value = null
  formError.value = ''
  showForm.value = true
}
function openEdit(p) {
  Object.assign(form, {
    name: p.name, category_id: p.category_id, price: p.price, old_price: p.old_price,
    description: p.description, image: p.image, badge: p.badge,
    in_stock: !!p.in_stock, rating: p.rating, reviews: p.reviews,
  })
  editingId.value = p.id
  formError.value = ''
  showForm.value = true
}
function closeForm() {
  showForm.value = false
}

async function save() {
  saving.value = true
  formError.value = ''
  try {
    const payload = { ...form }
    if (editingId.value) {
      await api.post('/products/update.php', { id: editingId.value, ...payload })
      success('Product updated')
    } else {
      await api.post('/products/create.php', payload)
      success('Product added')
    }
    showForm.value = false
    await loadProducts()
  } catch (e) {
    formError.value = e.message
  } finally {
    saving.value = false
  }
}

async function remove(p) {
  if (!confirm(`Delete "${p.name}"? This can't be undone.`)) return
  try {
    await api.post('/products/delete.php', { id: p.id })
    success('Product deleted')
    await loadProducts()
  } catch (e) {
    toastError(e.message)
  }
}
</script>

<style scoped>
.admin-panel-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
.admin-panel-head h2 { font-size: 1.3rem; margin-bottom: 4px; }

.admin-form { padding: 24px; margin-bottom: 24px; }
.admin-form h3 { font-size: 1.05rem; margin-bottom: 18px; }
.form-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.admin-form-actions { display: flex; gap: 12px; margin-top: 20px; }
.form-error { background: #FEE2E2; color: var(--red); font-size: 0.82rem; padding: 10px 14px; border-radius: var(--radius-sm); margin-top: 14px; }

.table-card { overflow-x: auto; }
.admin-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
.admin-table th { text-align: left; padding: 12px 16px; background: var(--gray-50); color: var(--text-light); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em; white-space: nowrap; }
.admin-table td { padding: 12px 16px; border-top: 1px solid var(--border); white-space: nowrap; }
.row-thumb { width: 36px; height: 36px; border-radius: 6px; object-fit: cover; }
.row-badge { font-size: 0.7rem; color: var(--teal); font-weight: 600; }
.row-old-price { font-size: 0.75rem; color: var(--gray-400); text-decoration: line-through; margin-left: 6px; }
.stock-chip { font-size: 0.72rem; font-weight: 600; padding: 3px 10px; border-radius: 100px; }
.stock-chip.in  { background: #DCFCE7; color: #16A34A; }
.stock-chip.out { background: #FEE2E2; color: var(--red); }
.row-actions { display: flex; gap: 8px; }
.row-btn { font-size: 0.78rem; font-weight: 600; color: var(--teal); padding: 4px 8px; }
.row-btn.danger { color: var(--red); }
.row-btn:hover { text-decoration: underline; }
.empty-row { text-align: center; color: var(--text-light); padding: 32px !important; }

@media (max-width: 700px) { .form-2col { grid-template-columns: 1fr; } }
</style>

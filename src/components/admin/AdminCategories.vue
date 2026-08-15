<template>
  <div class="admin-panel">
    <div class="admin-panel-head">
      <div>
        <h2>Categories</h2>
        <p class="text-light">Used to organize products and the shop sidebar</p>
      </div>
      <button class="btn btn-primary" @click="openCreate">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Category
      </button>
    </div>

    <div v-if="showForm" class="admin-form card">
      <h3>{{ editingId ? 'Edit Category' : 'New Category' }}</h3>
      <form @submit.prevent="save">
        <div class="form-2col">
          <div class="form-group">
            <label class="form-label">Name</label>
            <input class="form-control" v-model="form.name" required />
          </div>
          <div class="form-group">
            <label class="form-label">Sort Order</label>
            <input class="form-control" type="number" v-model="form.sort_order" />
          </div>
        </div>
        <div v-if="formError" class="form-error">{{ formError }}</div>
        <div class="admin-form-actions">
          <button type="button" class="btn btn-secondary" @click="showForm = false">Cancel</button>
          <button type="submit" class="btn btn-primary" :disabled="saving">{{ saving ? 'Saving…' : 'Save Category' }}</button>
        </div>
      </form>
    </div>

    <div class="table-card card">
      <table class="admin-table">
        <thead><tr><th>Name</th><th>Slug</th><th>Sort</th><th></th></tr></thead>
        <tbody>
          <tr v-for="c in categories" :key="c.id">
            <td><strong>{{ c.name }}</strong></td>
            <td>{{ c.slug }}</td>
            <td>{{ c.sort_order }}</td>
            <td class="row-actions">
              <button class="row-btn" @click="openEdit(c)">Edit</button>
              <button class="row-btn danger" @click="remove(c)">Delete</button>
            </td>
          </tr>
          <tr v-if="!categories.length"><td colspan="4" class="empty-row">No categories yet.</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { api }      from '../../lib/api.js'
import { useToast } from '../../composables/useToast.js'

const { success, error: toastError } = useToast()
const categories = ref([])

async function load() {
  const data = await api.get('/categories/list.php')
  categories.value = data.categories
}
load()

const showForm  = ref(false)
const editingId = ref(null)
const saving    = ref(false)
const formError = ref('')
const form = reactive({ name: '', sort_order: 0 })

function openCreate() {
  Object.assign(form, { name: '', sort_order: categories.value.length })
  editingId.value = null
  formError.value = ''
  showForm.value = true
}
function openEdit(c) {
  Object.assign(form, { name: c.name, sort_order: c.sort_order })
  editingId.value = c.id
  formError.value = ''
  showForm.value = true
}

async function save() {
  saving.value = true
  formError.value = ''
  try {
    if (editingId.value) {
      await api.post('/categories/update.php', { id: editingId.value, ...form })
      success('Category updated')
    } else {
      await api.post('/categories/create.php', form)
      success('Category added')
    }
    showForm.value = false
    await load()
  } catch (e) {
    formError.value = e.message
  } finally {
    saving.value = false
  }
}

async function remove(c) {
  if (!confirm(`Delete "${c.name}"? Products in this category will become uncategorized.`)) return
  try {
    await api.post('/categories/delete.php', { id: c.id })
    success('Category deleted')
    await load()
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
.admin-table th { text-align: left; padding: 12px 16px; background: var(--gray-50); color: var(--text-light); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em; }
.admin-table td { padding: 12px 16px; border-top: 1px solid var(--border); }
.row-actions { display: flex; gap: 8px; }
.row-btn { font-size: 0.78rem; font-weight: 600; color: var(--teal); padding: 4px 8px; }
.row-btn.danger { color: var(--red); }
.row-btn:hover { text-decoration: underline; }
.empty-row { text-align: center; color: var(--text-light); padding: 32px !important; }

@media (max-width: 700px) { .form-2col { grid-template-columns: 1fr; } }
</style>

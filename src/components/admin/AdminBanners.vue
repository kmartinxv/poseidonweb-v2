<template>
  <div class="admin-panel">
    <div class="admin-panel-head">
      <div>
        <h2>Promotional Banners</h2>
        <p class="text-light">Shown on the Shop page (on-sale strip), Home, and Services pages</p>
      </div>
      <button class="btn btn-primary" @click="openCreate">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Banner
      </button>
    </div>

    <div v-if="showForm" class="admin-form card">
      <h3>{{ editingId ? 'Edit Banner' : 'New Banner' }}</h3>
      <form @submit.prevent="save">
        <div class="form-2col">
          <div class="form-group">
            <label class="form-label">Title</label>
            <input class="form-control" v-model="form.title" required />
          </div>
          <div class="form-group">
            <label class="form-label">Shows On</label>
            <select class="form-control" v-model="form.placement" required>
              <option value="shop">Shop page</option>
              <option value="home">Home page</option>
              <option value="services">Services page</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Subtitle</label>
            <input class="form-control" v-model="form.subtitle" placeholder="Optional short description" />
          </div>
          <div class="form-group">
            <label class="form-label">Link (where it goes when clicked)</label>
            <input class="form-control" v-model="form.link" placeholder="/shop or /appointments" />
          </div>
          <div class="form-group">
            <label class="form-label">Status</label>
            <select class="form-control" v-model="form.active">
              <option :value="true">Active</option>
              <option :value="false">Hidden</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Sort Order</label>
            <input class="form-control" type="number" v-model="form.sort_order" />
          </div>
        </div>

        <div style="margin-top:16px">
          <ImageUploadField v-model="form.image" label="Banner Image (optional)" />
        </div>

        <div v-if="formError" class="form-error">{{ formError }}</div>
        <div class="admin-form-actions">
          <button type="button" class="btn btn-secondary" @click="showForm = false">Cancel</button>
          <button type="submit" class="btn btn-primary" :disabled="saving">{{ saving ? 'Saving…' : 'Save Banner' }}</button>
        </div>
      </form>
    </div>

    <div class="banner-list">
      <div class="banner-row card" v-for="b in banners" :key="b.id">
        <div class="banner-preview" :style="b.image ? { backgroundImage: `url(${b.image})` } : {}">
          <span :class="['placement-chip', b.placement]">{{ b.placement }}</span>
        </div>
        <div class="banner-info">
          <strong>{{ b.title }}</strong>
          <p v-if="b.subtitle">{{ b.subtitle }}</p>
          <span :class="['status-chip', b.active ? 'active' : 'hidden']">{{ b.active ? 'Active' : 'Hidden' }}</span>
        </div>
        <div class="row-actions">
          <button class="row-btn" @click="openEdit(b)">Edit</button>
          <button class="row-btn danger" @click="remove(b)">Delete</button>
        </div>
      </div>
      <p v-if="!banners.length" class="text-light" style="padding:24px 0">No banners yet.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { api }           from '../../lib/api.js'
import { useToast }      from '../../composables/useToast.js'
import ImageUploadField  from './ImageUploadField.vue'

const { success, error: toastError } = useToast()
const banners = ref([])

async function load() {
  const data = await api.get('/banners/list.php')
  banners.value = data.banners
}
load()

const showForm  = ref(false)
const editingId = ref(null)
const saving    = ref(false)
const formError = ref('')

function blankForm() {
  return { title: '', subtitle: '', image: '', link: '', placement: 'shop', active: true, sort_order: 0 }
}
const form = reactive(blankForm())

function openCreate() {
  Object.assign(form, blankForm())
  editingId.value = null
  formError.value = ''
  showForm.value = true
}
function openEdit(b) {
  Object.assign(form, {
    title: b.title, subtitle: b.subtitle, image: b.image, link: b.link,
    placement: b.placement, active: !!b.active, sort_order: b.sort_order,
  })
  editingId.value = b.id
  formError.value = ''
  showForm.value = true
}

async function save() {
  saving.value = true
  formError.value = ''
  try {
    if (editingId.value) {
      await api.post('/banners/update.php', { id: editingId.value, ...form })
      success('Banner updated')
    } else {
      await api.post('/banners/create.php', form)
      success('Banner added')
    }
    showForm.value = false
    await load()
  } catch (e) {
    formError.value = e.message
  } finally {
    saving.value = false
  }
}

async function remove(b) {
  if (!confirm(`Delete "${b.title}"?`)) return
  try {
    await api.post('/banners/delete.php', { id: b.id })
    success('Banner deleted')
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

.banner-list { display: flex; flex-direction: column; gap: 12px; }
.banner-row { display: flex; align-items: center; gap: 16px; padding: 14px 18px; }
.banner-preview {
  width: 90px; height: 56px; border-radius: var(--radius-sm); flex-shrink: 0;
  background: linear-gradient(135deg, var(--navy), var(--teal-dark));
  background-size: cover; background-position: center;
  display: flex; align-items: flex-start; justify-content: flex-start; padding: 6px;
}
.placement-chip { font-size: 0.62rem; font-weight: 700; text-transform: uppercase; color: white; background: rgba(0,0,0,0.35); padding: 2px 6px; border-radius: 100px; }
.banner-info { flex: 1; min-width: 0; }
.banner-info strong { display: block; font-size: 0.92rem; color: var(--navy); }
.banner-info p { font-size: 0.8rem; color: var(--text-light); margin: 2px 0 6px; }
.status-chip { font-size: 0.7rem; font-weight: 600; padding: 2px 9px; border-radius: 100px; }
.status-chip.active { background: #DCFCE7; color: #16A34A; }
.status-chip.hidden  { background: var(--gray-200); color: var(--text-light); }
.row-actions { display: flex; gap: 8px; flex-shrink: 0; }
.row-btn { font-size: 0.78rem; font-weight: 600; color: var(--teal); padding: 4px 8px; }
.row-btn.danger { color: var(--red); }
.row-btn:hover { text-decoration: underline; }

@media (max-width: 700px) {
  .form-2col { grid-template-columns: 1fr; }
  .banner-row { flex-wrap: wrap; }
}
</style>

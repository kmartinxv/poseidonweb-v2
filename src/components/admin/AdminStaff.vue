<template>
  <div class="admin-panel">
    <div class="admin-panel-head">
      <div>
        <h2>Staff Accounts</h2>
        <p class="text-light">Who can sign in to this dashboard</p>
      </div>
      <button class="btn btn-primary" @click="showForm = true">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Staff Member
      </button>
    </div>

    <div v-if="showForm" class="admin-form card">
      <h3>New Staff Member</h3>
      <form @submit.prevent="save">
        <div class="form-2col">
          <div class="form-group">
            <label class="form-label">Name</label>
            <input class="form-control" v-model="form.name" required />
          </div>
          <div class="form-group">
            <label class="form-label">Email</label>
            <input class="form-control" type="email" v-model="form.email" required />
          </div>
          <div class="form-group">
            <label class="form-label">Temporary Password</label>
            <input class="form-control" type="text" v-model="form.password" required minlength="8" />
            <span class="field-hint">At least 8 characters. Share this with them to sign in — they can't reset it themselves yet.</span>
          </div>
          <div class="form-group">
            <label class="form-label">Role</label>
            <select class="form-control" v-model="form.role">
              <option value="editor">Editor — products, banners, blog</option>
              <option value="admin">Admin — everything, incl. staff accounts</option>
            </select>
          </div>
        </div>
        <div v-if="formError" class="form-error">{{ formError }}</div>
        <div class="admin-form-actions">
          <button type="button" class="btn btn-secondary" @click="showForm = false">Cancel</button>
          <button type="submit" class="btn btn-primary" :disabled="saving">{{ saving ? 'Creating…' : 'Create Account' }}</button>
        </div>
      </form>
    </div>

    <div class="table-card card">
      <table class="admin-table">
        <thead><tr><th>Name</th><th>Email</th><th>Role</th><th></th></tr></thead>
        <tbody>
          <tr v-for="s in staff" :key="s.id">
            <td><strong>{{ s.name }}</strong></td>
            <td>{{ s.email }}</td>
            <td><span class="role-chip">{{ s.role }}</span></td>
            <td class="row-actions">
              <button class="row-btn danger" @click="remove(s)">Remove</button>
            </td>
          </tr>
          <tr v-if="!staff.length"><td colspan="4" class="empty-row">No staff accounts yet.</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { api }           from '../../lib/api.js'
import { useToast }      from '../../composables/useToast.js'

const { success, error: toastError } = useToast()
const staff = ref([])

async function load() {
  const data = await api.get('/staff/list.php')
  staff.value = data.staff
}
load()

const showForm  = ref(false)
const saving    = ref(false)
const formError = ref('')
const form = reactive({ name: '', email: '', password: '', role: 'editor' })

async function save() {
  saving.value = true
  formError.value = ''
  try {
    await api.post('/staff/create.php', form)
    success('Staff account created')
    Object.assign(form, { name: '', email: '', password: '', role: 'editor' })
    showForm.value = false
    await load()
  } catch (e) {
    formError.value = e.message
  } finally {
    saving.value = false
  }
}

async function remove(s) {
  if (!confirm(`Remove staff access for "${s.name}"?`)) return
  try {
    await api.post('/staff/delete.php', { id: s.id })
    success('Staff account removed')
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
.field-hint { font-size: 0.72rem; color: var(--text-light); margin-top: 4px; display: block; }
.admin-form-actions { display: flex; gap: 12px; margin-top: 20px; }
.form-error { background: #FEE2E2; color: var(--red); font-size: 0.82rem; padding: 10px 14px; border-radius: var(--radius-sm); margin-top: 14px; }
.table-card { overflow-x: auto; }
.admin-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
.admin-table th { text-align: left; padding: 12px 16px; background: var(--gray-50); color: var(--text-light); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em; }
.admin-table td { padding: 12px 16px; border-top: 1px solid var(--border); }
.role-chip { font-size: 0.72rem; font-weight: 600; padding: 3px 10px; border-radius: 100px; background: var(--teal-light); color: var(--teal-dark); text-transform: capitalize; }
.row-actions { display: flex; gap: 8px; }
.row-btn { font-size: 0.78rem; font-weight: 600; color: var(--teal); padding: 4px 8px; }
.row-btn.danger { color: var(--red); }
.row-btn:hover { text-decoration: underline; }
.empty-row { text-align: center; color: var(--text-light); padding: 32px !important; }

@media (max-width: 700px) { .form-2col { grid-template-columns: 1fr; } }
</style>

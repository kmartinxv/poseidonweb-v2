import { reactive, computed } from 'vue'
import { api } from '../lib/api.js'

const state = reactive({
  staff:   null,
  loading: true,
  checked: false,
})

async function fetchMe() {
  try {
    const { staff } = await api.get('/auth/me.php')
    state.staff = staff
  } catch {
    state.staff = null
  } finally {
    state.loading = false
    state.checked = true
  }
}

async function login(email, password) {
  const { staff } = await api.post('/auth/login.php', { email, password })
  state.staff = staff
  return staff
}

async function logout() {
  await api.post('/auth/logout.php', {})
  state.staff = null
}

export function useAdminAuth() {
  if (!state.checked) fetchMe()

  return {
    staff:      computed(() => state.staff),
    loading:    computed(() => state.loading),
    isLoggedIn: computed(() => !!state.staff),
    isAdmin:    computed(() => state.staff?.role === 'admin'),
    login,
    logout,
    refresh: fetchMe,
  }
}

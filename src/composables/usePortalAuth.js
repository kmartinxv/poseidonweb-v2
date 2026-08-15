import { reactive, computed } from 'vue'
import { portalApi } from '../lib/portalApi.js'

const state = reactive({
  client:  null,
  loading: true,
  checked: false,
})

async function fetchMe() {
  if (!portalApi.token.get()) {
    state.client  = null
    state.loading = false
    state.checked = true
    return
  }
  state.loading = true
  try {
    const { data } = await portalApi.get('/portal/me')
    state.client = data
  } catch {
    portalApi.token.set(null)
    state.client = null
  } finally {
    state.loading = false
    state.checked = true
  }
}

function requestOtp(identifier) {
  return portalApi.post('/portal/auth/request-otp', { identifier })
}

async function verifyOtp(identifier, code) {
  const { token, client, has_password } = await portalApi.post('/portal/auth/verify-otp', { identifier, code })
  portalApi.token.set(token)
  state.client = client
  return { client, hasPassword: !!has_password }
}

async function login(identifier, password) {
  const { token, client } = await portalApi.post('/portal/auth/login', { identifier, password })
  portalApi.token.set(token)
  state.client = client
  return client
}

async function setPassword(password, passwordConfirmation) {
  return portalApi.post('/portal/auth/set-password', {
    password,
    password_confirmation: passwordConfirmation,
  })
}

async function logout() {
  try { await portalApi.post('/portal/auth/logout', {}) } catch { /* token may already be invalid */ }
  portalApi.token.set(null)
  state.client = null
}

async function updateProfile(payload) {
  const { data } = await portalApi.patch('/portal/me', payload)
  state.client = data
  return data
}

export function usePortalAuth() {
  if (!state.checked) fetchMe()

  return {
    client:     computed(() => state.client),
    loading:    computed(() => state.loading),
    isLoggedIn: computed(() => !!state.client),
    requestOtp,
    verifyOtp,
    login,
    setPassword,
    logout,
    updateProfile,
    refresh: fetchMe,
  }
}

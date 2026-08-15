/**
 * Client for the Poseidon Vet practice-management system's client-portal
 * API (a separate Laravel app — poseidon_vet_lv — not the PHP shop/blog
 * backend in /api). Bearer-token auth via Sanctum, issued after OTP login.
 */
const BASE = import.meta.env.VITE_PORTAL_API_URL || 'http://localhost:8000/api'
const TOKEN_KEY = 'poseidon_portal_token'

function getToken() { return localStorage.getItem(TOKEN_KEY) }
function setToken(token) {
  if (token) localStorage.setItem(TOKEN_KEY, token)
  else localStorage.removeItem(TOKEN_KEY)
}

function extractErrorMessage(data, status) {
  if (data?.errors) {
    const first = Object.values(data.errors)[0]
    if (Array.isArray(first) && first[0]) return first[0]
  }
  return data?.message || `Request failed (${status})`
}

async function request(path, options = {}) {
  const token = getToken()
  const res = await fetch(`${BASE}${path}`, {
    headers: {
      'Content-Type': 'application/json',
      'Accept':       'application/json',
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
      ...options.headers,
    },
    ...options,
  })

  let data = null
  try { data = await res.json() } catch { /* empty body */ }

  if (!res.ok) {
    const err = new Error(extractErrorMessage(data, res.status))
    err.status = res.status
    err.errors = data?.errors
    err.data = data
    throw err
  }
  return data
}

export const portalApi = {
  get:   (path)       => request(path),
  post:  (path, body) => request(path, { method: 'POST',  body: JSON.stringify(body) }),
  patch: (path, body) => request(path, { method: 'PATCH', body: JSON.stringify(body) }),

  /** Downloads an authenticated file (e.g. an invoice PDF) via blob + a synthetic click. */
  async download(path, filename) {
    const token = getToken()
    const res = await fetch(`${BASE}${path}`, {
      headers: token ? { Authorization: `Bearer ${token}` } : {},
    })
    if (!res.ok) throw new Error(`Download failed (${res.status})`)
    const blob = await res.blob()
    const url  = URL.createObjectURL(blob)
    const a    = document.createElement('a')
    a.href = url
    a.download = filename
    document.body.appendChild(a)
    a.click()
    a.remove()
    URL.revokeObjectURL(url)
  },

  token: { get: getToken, set: setToken },
}

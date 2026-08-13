/**
 * Poseidon Vet API Service
 * Configure VITE_API_URL in .env to point at your backend.
 * Falls back gracefully to local data when the server is unreachable.
 */
const BASE = import.meta.env.VITE_API_URL ?? ''
const TOKEN_KEY = 'poseidon_token'

// ── Helpers ──────────────────────────────────────────────────────

function getToken() { return localStorage.getItem(TOKEN_KEY) }

async function request(path, options = {}) {
  if (!BASE) throw new Error('API_NOT_CONFIGURED')

  const token = getToken()
  const res   = await fetch(`${BASE}${path}`, {
    headers: {
      'Content-Type': 'application/json',
      'Accept':       'application/json',
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
      ...options.headers,
    },
    ...options,
  })

  if (!res.ok) {
    const body = await res.json().catch(() => ({}))
    const err  = new Error(body.message || `HTTP ${res.status}`)
    err.status = res.status
    throw err
  }
  return res.json()
}

// ── Auth ─────────────────────────────────────────────────────────

export const auth = {
  login(email, password) {
    return request('/auth/login', {
      method: 'POST',
      body: JSON.stringify({ email, password }),
    }).then(data => {
      if (data.token) localStorage.setItem(TOKEN_KEY, data.token)
      return data
    })
  },
  logout() { localStorage.removeItem(TOKEN_KEY) },
  isLoggedIn: () => Boolean(getToken()),
}

// ── Profile / Portal ─────────────────────────────────────────────

export const profile = {
  get:             () => request('/me'),
  getPets:         () => request('/me/pets'),
  getAppointments: () => request('/me/appointments'),
  getPrescriptions:() => request('/me/prescriptions'),
  getOrders:       () => request('/me/orders'),
}

// ── Appointments ─────────────────────────────────────────────────

export const appointments = {
  book: (data)         => request('/appointments', { method: 'POST', body: JSON.stringify(data) }),
  getSlots: (date, vetId) => request(`/appointments/slots?date=${date}${vetId ? `&vet=${vetId}` : ''}`),
  cancel: (id)         => request(`/appointments/${id}/cancel`, { method: 'POST' }),
}

// ── Products / Shop ──────────────────────────────────────────────

export const shop = {
  getProducts: (category) => request(`/products${category && category !== 'All' ? `?category=${encodeURIComponent(category)}` : ''}`),
  getProduct:  (id)        => request(`/products/${id}`),
  placeOrder:  (data)      => request('/orders', { method: 'POST', body: JSON.stringify(data) }),
}

// ── Services ─────────────────────────────────────────────────────

export const services = {
  list: () => request('/services'),
}

// ── Team ─────────────────────────────────────────────────────────

export const team = {
  list: () => request('/team'),
}

// ── Blog ─────────────────────────────────────────────────────────

export const blog = {
  list:    ()     => request('/blog'),
  getPost: (slug) => request(`/blog/${slug}`),
}

// ── Contact ──────────────────────────────────────────────────────

export const contact = {
  send: (data) => request('/contact', { method: 'POST', body: JSON.stringify(data) }),
}

// ── Universal "call API or fall back to local data" wrapper ──────

/**
 * Usage:
 *   const products = await withFallback(shop.getProducts, localProducts)
 *
 * Returns API data when VITE_API_URL is set and request succeeds.
 * Returns fallback when API is unconfigured or unreachable.
 */
export async function withFallback(apiFn, fallback) {
  if (!BASE) return fallback
  try {
    return await apiFn()
  } catch {
    return fallback
  }
}

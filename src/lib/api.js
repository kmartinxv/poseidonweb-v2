/**
 * Thin fetch wrapper around the PHP API in /api.
 * Same-origin in both local dev (via the Vite proxy, see vite.config.js)
 * and production on HostGator (built frontend + /api/ share one domain),
 * so relative paths + cookies are all that's needed — no base URL config.
 */

async function request(path, options = {}) {
  const res = await fetch(`/api${path}`, {
    credentials: 'include',
    headers: options.body instanceof FormData
      ? {}
      : { 'Content-Type': 'application/json' },
    ...options,
  })

  let data = null
  try { data = await res.json() } catch { /* empty body */ }

  if (!res.ok) {
    const err = new Error(data?.error || `Request failed (${res.status})`)
    err.status = res.status
    throw err
  }
  return data
}

export const api = {
  get:  (path)         => request(path),
  post: (path, body)   => request(path, { method: 'POST', body: body instanceof FormData ? body : JSON.stringify(body) }),
}

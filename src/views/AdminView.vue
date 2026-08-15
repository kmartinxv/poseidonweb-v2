<template>
  <div class="admin-root">

    <!-- Not yet checked session -->
    <div v-if="loading" class="admin-loading">
      <p class="text-light">Loading…</p>
    </div>

    <!-- ═══════════ LOGIN ═══════════ -->
    <div v-else-if="!isLoggedIn" class="admin-login">
      <form class="admin-login-card card" @submit.prevent="handleLogin">
        <div class="admin-login-brand">
          <svg viewBox="0 0 100 100" fill="none" width="34" height="34">
            <ellipse cx="50" cy="62" rx="26" ry="22" stroke="#E02020" stroke-width="6" stroke-linecap="round"/>
            <ellipse cx="32" cy="36" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
            <ellipse cx="50" cy="29" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
            <ellipse cx="68" cy="36" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
            <ellipse cx="19" cy="50" rx="8" ry="11" stroke="#E02020" stroke-width="5" stroke-linecap="round"/>
            <ellipse cx="81" cy="50" rx="8" ry="11" stroke="#E02020" stroke-width="5" stroke-linecap="round"/>
            <path d="M50 72 C46 67, 38 63, 38 57 C38 52, 50 52, 50 59 C50 52, 62 52, 62 57 C62 63, 54 67, 50 72Z" stroke="#E02020" stroke-width="5" stroke-linejoin="round"/>
          </svg>
          <span>poseidonvet</span>
        </div>
        <h1>Staff Sign In</h1>
        <p class="text-light">Manage products, banners, and blog posts.</p>

        <div class="form-group" style="margin-top:24px">
          <label class="form-label">Email</label>
          <input type="email" v-model="creds.email" class="form-control" placeholder="you@poseidonvet.com" required autofocus />
        </div>
        <div class="form-group" style="margin-top:14px">
          <label class="form-label">Password</label>
          <input type="password" v-model="creds.password" class="form-control" placeholder="••••••••" required />
        </div>

        <div v-if="loginError" class="admin-login-error">{{ loginError }}</div>

        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:20px" :disabled="submitting">
          {{ submitting ? 'Signing in…' : 'Sign In' }}
        </button>
      </form>
    </div>

    <!-- ═══════════ DASHBOARD ═══════════ -->
    <div v-else class="admin-dash">
      <aside class="admin-sidebar">
        <div class="admin-sb-head">
          <svg viewBox="0 0 100 100" fill="none" width="28" height="28">
            <ellipse cx="50" cy="62" rx="26" ry="22" stroke="#E02020" stroke-width="6" stroke-linecap="round"/>
            <ellipse cx="32" cy="36" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
            <ellipse cx="50" cy="29" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
            <ellipse cx="68" cy="36" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
            <ellipse cx="19" cy="50" rx="8" ry="11" stroke="#E02020" stroke-width="5" stroke-linecap="round"/>
            <ellipse cx="81" cy="50" rx="8" ry="11" stroke="#E02020" stroke-width="5" stroke-linecap="round"/>
            <path d="M50 72 C46 67, 38 63, 38 57 C38 52, 50 52, 50 59 C50 52, 62 52, 62 57 C62 63, 54 67, 50 72Z" stroke="#E02020" stroke-width="5" stroke-linejoin="round"/>
          </svg>
          <div>
            <strong>poseidonvet</strong>
            <span>Staff Admin</span>
          </div>
        </div>

        <nav class="admin-nav">
          <button
            v-for="item in navItems"
            :key="item.id"
            :class="['admin-nav-item', { active: activeSection === item.id }]"
            @click="activeSection = item.id"
            v-show="!item.adminOnly || isAdmin"
          >
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path :d="item.icon"/></svg>
            {{ item.label }}
          </button>
        </nav>

        <div class="admin-sb-foot">
          <RouterLink to="/" class="admin-view-site">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            View Site
          </RouterLink>
          <div class="admin-whoami">
            <strong>{{ staff.name }}</strong>
            <span>{{ staff.role }}</span>
          </div>
          <button class="btn btn-secondary btn-sm" style="width:100%;justify-content:center" @click="handleLogout">Sign Out</button>
        </div>
      </aside>

      <div class="admin-main">
        <AdminProducts   v-if="activeSection === 'products'" />
        <AdminCategories v-else-if="activeSection === 'categories'" />
        <AdminBanners    v-else-if="activeSection === 'banners'" />
        <AdminBlog       v-else-if="activeSection === 'blog'" />
        <AdminStaff      v-else-if="activeSection === 'staff'" />
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAdminAuth } from '../composables/useAdminAuth.js'
import AdminProducts   from '../components/admin/AdminProducts.vue'
import AdminCategories from '../components/admin/AdminCategories.vue'
import AdminBanners    from '../components/admin/AdminBanners.vue'
import AdminBlog       from '../components/admin/AdminBlog.vue'
import AdminStaff      from '../components/admin/AdminStaff.vue'

const { staff, loading, isLoggedIn, isAdmin, login, logout } = useAdminAuth()

const creds      = ref({ email: '', password: '' })
const loginError = ref('')
const submitting = ref(false)

async function handleLogin() {
  submitting.value = true
  loginError.value = ''
  try {
    await login(creds.value.email, creds.value.password)
  } catch (e) {
    loginError.value = e.message || 'Sign in failed'
  } finally {
    submitting.value = false
  }
}

async function handleLogout() {
  await logout()
}

const activeSection = ref('products')

const navItems = [
  { id: 'products',   label: 'Products',    icon: 'M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z' },
  { id: 'categories', label: 'Categories',  icon: 'M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z' },
  { id: 'banners',    label: 'Banners',     icon: 'M4 4h16v16H4z M4 4l16 16 M20 4L4 20' },
  { id: 'blog',       label: 'Blog',        icon: 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z M14 2v6h6 M9 15h6 M9 11h2' },
  { id: 'staff',      label: 'Staff Accounts', icon: 'M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2 M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z', adminOnly: true },
]
</script>

<style scoped>
.admin-root { min-height: 100vh; background: var(--gray-50); }
.admin-loading { display: flex; align-items: center; justify-content: center; min-height: 100vh; }

/* Login */
.admin-login { display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 24px; }
.admin-login-card { width: 100%; max-width: 380px; padding: 36px; }
.admin-login-brand { display: flex; align-items: center; gap: 10px; margin-bottom: 28px; }
.admin-login-brand span { font-size: 1.05rem; font-weight: 800; color: var(--navy); }
.admin-login-card h1 { font-size: 1.4rem; margin-bottom: 6px; }
.admin-login-error { background: #FEE2E2; color: var(--red); font-size: 0.82rem; padding: 10px 14px; border-radius: var(--radius-sm); border-left: 3px solid var(--red); margin-top: 14px; }

/* Dashboard shell */
.admin-dash { display: grid; grid-template-columns: 230px 1fr; min-height: 100vh; }
.admin-sidebar {
  background: #0A1628;
  color: white;
  display: flex;
  flex-direction: column;
  position: sticky;
  top: 0;
  height: 100vh;
}
.admin-sb-head { display: flex; align-items: center; gap: 10px; padding: 24px 20px; border-bottom: 1px solid rgba(255,255,255,0.08); }
.admin-sb-head strong { display: block; font-size: 0.85rem; font-weight: 800; }
.admin-sb-head span   { display: block; font-size: 0.68rem; color: rgba(255,255,255,0.4); }

.admin-nav { flex: 1; padding: 16px 12px; display: flex; flex-direction: column; gap: 2px; overflow-y: auto; }
.admin-nav-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border-radius: var(--radius-sm);
  font-size: 0.85rem;
  font-weight: 500;
  color: rgba(255,255,255,0.65);
  text-align: left;
  transition: background var(--transition), color var(--transition);
}
.admin-nav-item svg { flex-shrink: 0; }
.admin-nav-item:hover { background: rgba(255,255,255,0.06); color: white; }
.admin-nav-item.active { background: var(--teal); color: white; font-weight: 600; }

.admin-sb-foot { padding: 16px 20px 20px; border-top: 1px solid rgba(255,255,255,0.08); }
.admin-view-site {
  display: flex; align-items: center; gap: 8px;
  font-size: 0.8rem; color: rgba(255,255,255,0.55); margin-bottom: 16px;
  transition: color var(--transition);
}
.admin-view-site:hover { color: var(--teal); }
.admin-whoami { margin-bottom: 12px; }
.admin-whoami strong { display: block; font-size: 0.85rem; }
.admin-whoami span   { display: block; font-size: 0.7rem; color: rgba(255,255,255,0.4); text-transform: capitalize; }

.admin-main { padding: 32px 40px; overflow-x: hidden; }

@media (max-width: 900px) {
  .admin-dash { grid-template-columns: 1fr; }
  .admin-sidebar { position: static; height: auto; }
  .admin-nav { flex-direction: row; flex-wrap: wrap; }
  .admin-main { padding: 24px 20px; }
}
</style>

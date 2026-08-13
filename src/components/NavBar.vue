<template>
  <header :class="['navbar', { scrolled }]">
    <div class="nav-inner container">

      <!-- Logo -->
      <RouterLink to="/" class="nav-logo">
        <svg class="logo-paw" viewBox="0 0 100 100" fill="none">
          <ellipse cx="50" cy="62" rx="26" ry="22" stroke="#E02020" stroke-width="6" stroke-linecap="round"/>
          <ellipse cx="32" cy="36" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
          <ellipse cx="50" cy="29" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
          <ellipse cx="68" cy="36" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
          <ellipse cx="19" cy="50" rx="8" ry="11" stroke="#E02020" stroke-width="5" stroke-linecap="round"/>
          <ellipse cx="81" cy="50" rx="8" ry="11" stroke="#E02020" stroke-width="5" stroke-linecap="round"/>
          <path d="M50 72 C46 67, 38 63, 38 57 C38 52, 50 52, 50 59 C50 52, 62 52, 62 57 C62 63, 54 67, 50 72Z" stroke="#E02020" stroke-width="5" stroke-linejoin="round"/>
        </svg>
        <span class="logo-word">poseidonvet</span>
      </RouterLink>

      <!-- Nav links -->
      <nav class="nav-links" :class="{ open: menuOpen }" @click.self="menuOpen = false">
        <RouterLink v-for="l in links" :key="l.to" :to="l.to" class="nav-link" @click="menuOpen = false">
          {{ l.label }}
        </RouterLink>
      </nav>

      <!-- Right side -->
      <div class="nav-right">
        <a href="tel:+254780415469" class="nav-phone">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.62 3.38 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.77a16 16 0 0 0 6.28 6.28l1.06-1.06a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.03z"/>
          </svg>
          <div class="phone-text">
            <small>Call Anytime</small>
            <span>+254 780 415 469</span>
          </div>
        </a>

        <RouterLink to="/cart" class="cart-btn" aria-label="Cart">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
          </svg>
          <span v-if="itemCount > 0" class="cart-badge">{{ itemCount }}</span>
        </RouterLink>

        <RouterLink to="/appointments" class="btn btn-primary btn-sm">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
          Book Appointment
        </RouterLink>
      </div>

      <!-- Hamburger -->
      <button class="hamburger" :class="{ open: menuOpen }" @click="menuOpen = !menuOpen" aria-label="Menu">
        <span/><span/><span/>
      </button>

    </div>
  </header>

  <!-- mobile backdrop -->
  <div v-if="menuOpen" class="menu-backdrop" @click="menuOpen = false"/>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useCart } from '../composables/useCart.js'

const { itemCount } = useCart()

const links = [
  { to: '/',             label: 'Home' },
  { to: '/about',        label: 'About Us' },
  { to: '/services',     label: 'Services' },
  { to: '/shop',         label: 'Shop' },
  { to: '/appointments', label: 'Appointments' },
  { to: '/blog',         label: 'Blog' },
  { to: '/contact',      label: 'Contact' },
]

const scrolled  = ref(false)
const menuOpen  = ref(false)

function onScroll() { scrolled.value = window.scrollY > 20 }
onMounted(() => window.addEventListener('scroll', onScroll))
onUnmounted(() => window.removeEventListener('scroll', onScroll))
</script>

<style scoped>
.navbar {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
  background: rgba(255,255,255,0.97);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid transparent;
  transition: border-color 200ms, box-shadow 200ms;
}
.navbar.scrolled {
  border-color: var(--border);
  box-shadow: var(--shadow-sm);
}

.nav-inner {
  display: flex;
  align-items: center;
  height: 72px;
  gap: 24px;
}

/* Logo */
.nav-logo {
  display: flex;
  align-items: center;
  gap: 9px;
  text-decoration: none;
  flex-shrink: 0;
}
.nav-logo .logo-paw { width: 34px; height: 34px; flex-shrink: 0; }
.nav-logo .logo-word {
  font-size: 1.15rem;
  font-weight: 800;
  letter-spacing: -0.01em;
  color: var(--navy);
}

/* Nav links */
.nav-links {
  display: flex;
  align-items: center;
  gap: 2px;
  flex: 1;
  justify-content: center;
}
.nav-link {
  padding: 8px 12px;
  border-radius: 6px;
  font-size: 0.875rem;
  font-weight: 500;
  color: var(--text-light);
  transition: color var(--transition), background var(--transition);
  white-space: nowrap;
  position: relative;
}
.nav-link:hover { color: var(--navy); background: var(--gray-100); }
.nav-link.router-link-active {
  color: var(--teal);
  font-weight: 600;
}
.nav-link.router-link-active::after {
  content: '';
  position: absolute;
  bottom: 2px;
  left: 12px;
  right: 12px;
  height: 2px;
  background: var(--teal);
  border-radius: 1px;
}

/* Right side */
.nav-right {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-shrink: 0;
}
.nav-phone {
  display: flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
  color: var(--text-light);
  transition: color var(--transition);
}
.nav-phone:hover { color: var(--teal); }
.nav-phone svg { flex-shrink: 0; }
.phone-text { display: flex; flex-direction: column; line-height: 1.1; }
.phone-text small { font-size: 0.65rem; color: var(--gray-400); letter-spacing: 0.04em; }
.phone-text span  { font-size: 0.82rem; font-weight: 600; color: var(--navy); }

.cart-btn {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  color: var(--text-light);
  transition: color var(--transition), background var(--transition);
}
.cart-btn:hover { color: var(--teal); background: var(--teal-light); }
.cart-badge {
  position: absolute;
  top: 2px;
  right: 2px;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background: var(--teal);
  color: white;
  font-size: 0.65rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Hamburger */
.hamburger {
  display: none;
  flex-direction: column;
  gap: 5px;
  padding: 8px;
  border-radius: 6px;
  margin-left: auto;
  transition: background var(--transition);
}
.hamburger:hover { background: var(--gray-100); }
.hamburger span {
  display: block;
  width: 22px;
  height: 2px;
  background: var(--navy);
  border-radius: 1px;
  transition: transform 250ms var(--ease-out), opacity 200ms;
  transform-origin: center;
}
.hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
.hamburger.open span:nth-child(2) { opacity: 0; }
.hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

.menu-backdrop {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.3);
  z-index: 998;
}

/* Responsive */
@media (max-width: 1100px) {
  .nav-phone { display: none; }
}
@media (max-width: 900px) {
  .nav-links {
    position: fixed;
    top: 72px;
    left: 0;
    right: 0;
    bottom: 0;
    background: white;
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-start;
    padding: 24px;
    gap: 4px;
    z-index: 999;
    transform: translateX(-100%);
    transition: transform 280ms var(--ease-out);
  }
  .nav-links.open { transform: translateX(0); }
  .nav-link { font-size: 1rem; padding: 12px 16px; width: 100%; border-radius: 8px; }
  .nav-link.router-link-active::after { bottom: 6px; }
  .hamburger { display: flex; }
  .menu-backdrop { display: block; }
}
@media (max-width: 480px) {
  .btn-primary { display: none; }
}
</style>

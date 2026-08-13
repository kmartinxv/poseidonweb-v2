<template>
  <footer class="footer">
    <div class="container">
      <div class="footer-grid">

        <!-- Brand -->
        <div class="footer-brand">
          <RouterLink to="/" class="footer-logo">
            <svg class="logo-paw-sm" viewBox="0 0 100 100" fill="none">
              <ellipse cx="50" cy="62" rx="26" ry="22" stroke="#E02020" stroke-width="6" stroke-linecap="round"/>
              <ellipse cx="32" cy="36" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
              <ellipse cx="50" cy="29" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
              <ellipse cx="68" cy="36" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
              <ellipse cx="19" cy="50" rx="8" ry="11" stroke="#E02020" stroke-width="5" stroke-linecap="round"/>
              <ellipse cx="81" cy="50" rx="8" ry="11" stroke="#E02020" stroke-width="5" stroke-linecap="round"/>
              <path d="M50 72 C46 67, 38 63, 38 57 C38 52, 50 52, 50 59 C50 52, 62 52, 62 57 C62 63, 54 67, 50 72Z" stroke="#E02020" stroke-width="5" stroke-linejoin="round"/>
            </svg>
            <span class="footer-logo-word">poseidonvet</span>
          </RouterLink>
          <p class="footer-tagline">Compassionate, expert veterinary care for every beloved pet in Nairobi and beyond.</p>
          <div class="social-links">
            <a href="#" aria-label="Facebook" class="social-btn">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
            </a>
            <a href="#" aria-label="Instagram" class="social-btn">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
            </a>
            <a href="https://wa.me/254780415469" target="_blank" rel="noopener" aria-label="WhatsApp" class="social-btn">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
            </a>
          </div>
        </div>

        <!-- Quick links -->
        <div class="footer-col">
          <h4>Quick Links</h4>
          <ul>
            <li v-for="l in links" :key="l.to"><RouterLink :to="l.to">{{ l.label }}</RouterLink></li>
          </ul>
        </div>

        <!-- Services -->
        <div class="footer-col">
          <h4>Our Services</h4>
          <ul>
            <li v-for="s in serviceLinks" :key="s"><a href="/services">{{ s }}</a></li>
          </ul>
        </div>

        <!-- Contact -->
        <div class="footer-col">
          <h4>Contact Us</h4>
          <ul class="contact-list">
            <li>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              Mai Mahiu Rd, opp. Magharibi Place, Nairobi
            </li>
            <li>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.62 3.38 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.77a16 16 0 0 0 6.28 6.28l1.06-1.06a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.03z"/></svg>
              <a href="tel:+254780415469">+254 780 415 469</a>
            </li>
            <li>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              <a href="mailto:info@poseidonvet.com">info@poseidonvet.com</a>
            </li>
            <li>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              Mon–Sat 9am–5pm · Sun 10am–4pm
            </li>
          </ul>
        </div>

      </div>

      <!-- Bottom bar -->
      <div class="footer-bottom">
        <p>&copy; {{ year }} Poseidon Veterinary Clinic. All rights reserved.</p>
        <p class="footer-credit">
          Designed &amp; developed by
          <a href="https://arton.co.ke" target="_blank" rel="noopener">Arton Limited</a>
        </p>
        <div class="footer-legal">
          <a href="#">Privacy Policy</a>
          <a href="#">Terms of Service</a>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
const year = new Date().getFullYear()
const links = [
  { to: '/',             label: 'Home' },
  { to: '/about',        label: 'About Us' },
  { to: '/services',     label: 'Services' },
  { to: '/shop',         label: 'Pet Shop' },
  { to: '/appointments', label: 'Book Appointment' },
  { to: '/blog',         label: 'Blog' },
  { to: '/contact',      label: 'Contact' },
  { to: '/portal',       label: 'Client Portal' },
]
const serviceLinks = [
  'Wellness & Prevention', 'Vaccinations', 'Surgery', 'Lab & Diagnostics',
  'Dental Care', 'Emergency Care', 'Grooming', 'Boarding',
]
</script>

<style scoped>
.footer {
  background: var(--navy);
  color: rgba(255,255,255,0.75);
  padding: 72px 0 0;
  margin-top: 0;
}
.footer-grid {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1.4fr;
  gap: 48px;
  padding-bottom: 56px;
  border-bottom: 1px solid rgba(255,255,255,0.1);
}

/* Brand */
.footer-logo {
  display: flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
  margin-bottom: 16px;
}
.logo-paw-sm { width: 30px; height: 30px; flex-shrink: 0; }
.footer-logo-word {
  font-size: 1.05rem;
  font-weight: 800;
  letter-spacing: -0.01em;
  color: white;
}
.footer-tagline { font-size: 0.875rem; line-height: 1.6; margin-bottom: 20px; max-width: 280px; }

.social-links { display: flex; gap: 10px; }
.social-btn {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: rgba(255,255,255,0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  color: rgba(255,255,255,0.7);
  transition: background var(--transition), color var(--transition);
}
.social-btn:hover { background: var(--teal); color: white; }

/* Columns */
.footer-col h4 {
  color: white;
  font-size: 0.85rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  margin-bottom: 18px;
}
.footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
.footer-col a {
  font-size: 0.875rem;
  color: rgba(255,255,255,0.65);
  text-decoration: none;
  transition: color var(--transition);
}
.footer-col a:hover { color: var(--teal); }

.contact-list li {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  font-size: 0.875rem;
}
.contact-list svg { flex-shrink: 0; stroke: var(--teal); margin-top: 2px; }
.contact-list a { color: rgba(255,255,255,0.65); }
.contact-list a:hover { color: var(--teal); }

/* Bottom */
.footer-bottom {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 0;
  font-size: 0.8rem;
  color: rgba(255,255,255,0.4);
  flex-wrap: wrap;
  gap: 12px;
}
.footer-legal { display: flex; gap: 20px; }
.footer-legal a { color: rgba(255,255,255,0.4); }
.footer-legal a:hover { color: rgba(255,255,255,0.7); }

.footer-credit { color: rgba(255,255,255,0.4); }
.footer-credit a { color: rgba(255,255,255,0.65); font-weight: 600; transition: color var(--transition); }
.footer-credit a:hover { color: var(--teal); }

@media (max-width: 1024px) {
  .footer-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 600px) {
  .footer-grid { grid-template-columns: 1fr; gap: 32px; }
}
</style>

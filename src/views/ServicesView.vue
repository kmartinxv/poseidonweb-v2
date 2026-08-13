<template>
  <div class="services-page">

    <!-- Split Hero (Luma Services layout) -->
    <section class="services-hero">
      <div class="container">
        <div class="sh-layout">

          <!-- Left panel -->
          <div class="sh-left">
            <span class="eyebrow">Complete Veterinary Solutions</span>
            <h1>Care Tailored For<br><span class="teal wavy-line">Every Pet</span></h1>
            <p>From routine preventive care to advanced surgery, our experienced team provides a full spectrum of veterinary services under one roof.</p>
            <div class="sh-actions">
              <RouterLink to="/appointments" class="btn btn-primary btn-lg">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Book Appointment
              </RouterLink>
              <a href="#all-services" class="btn btn-secondary btn-lg">
                View All Services
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
              </a>
            </div>
            <div class="why-us" style="margin-top:36px">
              <p class="why-title">Why Pet Owners Trust Us</p>
              <ul class="check-list">
                <li>Experienced &amp; Caring Veterinary Team</li>
                <li>Advanced Diagnostic Equipment</li>
                <li>Personalised Care For Every Animal</li>
                <li>Transparent Pricing, No Surprise Bills</li>
              </ul>
            </div>
          </div>

          <!-- Right: 3×2 service cards -->
          <div class="sh-grid" id="all-services">
            <div class="sh-card card" v-for="s in services" :key="s.id">
              <div class="icon-circle">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path :d="serviceIconPaths[s.icon]"/>
                </svg>
              </div>
              <h3>{{ s.title }}</h3>
              <p>{{ s.desc }}</p>
              <RouterLink to="/appointments" class="learn-more">
                Book Now
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
              </RouterLink>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- Bottom trust strip -->
    <section class="trust-bottom">
      <div class="container">
        <div class="trust-bottom-grid">
          <div class="tb-item" v-for="t in trustItems" :key="t.label">
            <div class="icon-circle" style="width:52px;height:52px">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path :d="t.icon"/></svg>
            </div>
            <div>
              <strong>{{ t.label }}</strong>
              <span>{{ t.sub }}</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Emergency CTA -->
    <section class="section emergency-cta">
      <div class="container">
        <div class="emergency-card">
          <div class="emergency-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
              <path d="M22 12h-6l-2 3H10l-2-3H2"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
            </svg>
          </div>
          <div>
            <h3 style="color:white;margin-bottom:6px">Pet Emergency?</h3>
            <p style="color:rgba(255,255,255,0.8);font-size:0.9rem">We offer same-day urgent appointments for accidents, sudden illness, and critical conditions. Call us immediately.</p>
          </div>
          <a href="tel:+254780415469" class="btn" style="background:white;color:var(--red);font-weight:700;white-space:nowrap;margin-left:auto;flex-shrink:0">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.62 3.38 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.77a16 16 0 0 0 6.28 6.28l1.06-1.06a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.03z"/></svg>
            Call Now
          </a>
        </div>
      </div>
    </section>

  </div>
</template>

<script setup>
import { services, serviceIconPaths } from '../data/services.js'
import { useScrollReveal }            from '../composables/useScrollReveal.js'
useScrollReveal()

const trustItems = [
  { label: 'Trusted by 1,000+ Pet Owners', sub: 'Since 2010',              icon: 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z' },
  { label: '5-Star Rated',                 sub: 'On Google & Social Media', icon: 'M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z' },
  { label: 'Flexible Scheduling',          sub: 'Mon–Sat 9am–5pm',         icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z' },
  { label: 'Same-Day Urgent Care',         sub: 'Available for emergencies', icon: 'M22 12h-6l-2 3H10l-2-3H2' },
]
</script>

<style scoped>
.services-hero {
  padding: 136px 0 72px;
  background: var(--bg);
}
.sh-layout {
  display: grid;
  grid-template-columns: 360px 1fr;
  gap: 72px;
  align-items: start;
}
.sh-left h1 { margin: 8px 0 16px; }
.sh-left p  { color: var(--text-light); margin-bottom: 28px; max-width: 380px; }
.sh-actions { display: flex; gap: 12px; flex-wrap: wrap; }
.why-title  { font-weight: 700; font-size: 0.9rem; color: var(--navy); margin-bottom: 12px; }

.sh-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 18px;
}
.sh-card {
  padding: 22px 18px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.sh-card h3 { font-size: 0.95rem; margin: 0; }
.sh-card p  { font-size: 0.82rem; color: var(--text-light); flex: 1; }
.learn-more {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 0.8rem;
  font-weight: 600;
  color: var(--teal);
  margin-top: 4px;
  transition: gap var(--transition);
}
.learn-more:hover { gap: 10px; }

.trust-bottom {
  background: white;
  border-top: 1px solid var(--border);
  padding: 32px 0;
}
.trust-bottom-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24px;
}
.tb-item { display: flex; align-items: center; gap: 14px; }
.tb-item strong { display: block; font-size: 0.85rem; color: var(--navy); }
.tb-item span   { font-size: 0.75rem; color: var(--text-light); }

.emergency-cta { background: var(--gray-50); padding-top: 0; }
.emergency-card {
  background: linear-gradient(135deg, var(--red) 0%, #a93226 100%);
  border-radius: var(--radius-lg);
  padding: 32px 40px;
  display: flex;
  align-items: center;
  gap: 24px;
  flex-wrap: wrap;
}
.emergency-icon {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: rgba(255,255,255,0.15);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

@media (max-width: 1100px) {
  .sh-layout { grid-template-columns: 1fr; }
  .sh-grid { grid-template-columns: repeat(3, 1fr); }
  .trust-bottom-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
  .services-hero { padding-top: 100px; }
  .sh-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) {
  .sh-grid { grid-template-columns: 1fr; }
  .emergency-card { flex-direction: column; }
  .emergency-card .btn { margin-left: 0; }
}
</style>

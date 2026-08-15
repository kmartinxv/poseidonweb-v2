<template>
  <div class="svc-detail-page" v-if="category">

    <!-- Hero -->
    <section class="svc-hero">
      <div class="container">
        <nav class="breadcrumb">
          <RouterLink to="/services">Services</RouterLink>
          <span>/</span>
          <span>{{ category.title }}</span>
        </nav>

        <div class="svc-hero-grid">
          <div>
            <div class="icon-circle icon-circle-lg">
              <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path :d="serviceIconPaths[category.icon]"/>
              </svg>
            </div>
            <span class="eyebrow">{{ category.tagline }}</span>
            <h1>{{ category.title }}</h1>
            <p class="svc-hero-desc">{{ category.description }}</p>
            <div class="sh-actions">
              <RouterLink to="/appointments" class="btn btn-primary btn-lg">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Book Any {{ category.title }} Service
              </RouterLink>
              <a :href="`tel:${PHONE_TEL}`" class="btn btn-secondary btn-lg">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.62 3.38 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.77a16 16 0 0 0 6.28 6.28l1.06-1.06a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.03z"/></svg>
                Call Us
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Emergency urgency strip -->
    <section v-if="slug === 'emergency'" class="section emergency-cta" style="padding-top:0;padding-bottom:0;margin-bottom:56px">
      <div class="container">
        <div class="emergency-card">
          <div class="emergency-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
              <path d="M22 12h-6l-2 3H10l-2-3H2"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
            </svg>
          </div>
          <div>
            <h3 style="color:white;margin-bottom:6px">In the middle of an emergency right now?</h3>
            <p style="color:rgba(255,255,255,0.8);font-size:0.9rem">Skip the form — call us directly and we'll guide you through what to do next.</p>
          </div>
          <a :href="`tel:${PHONE_TEL}`" class="btn" style="background:white;color:var(--red);font-weight:700;white-space:nowrap;margin-left:auto;flex-shrink:0">
            Call Now
          </a>
        </div>
      </div>
    </section>

    <!-- Services grid -->
    <section class="section svc-list-section">
      <div class="container">
        <div class="section-head">
          <span class="eyebrow">What We Offer</span>
          <h2>Top {{ category.title }} Services</h2>
          <p class="text-light">Pick a service below to start booking — pricing shown is per our standard rate card.</p>
        </div>

        <div class="svc-grid">
          <div class="svc-card card" v-for="item in category.items" :key="item.name">
            <div v-if="item.popular" class="popular-badge">★ Most Booked</div>
            <h3>{{ item.name }}</h3>
            <div class="svc-price">
              <strong>{{ item.price }}</strong>
              <span v-if="item.unit">{{ item.unit }}</span>
            </div>
            <p class="svc-blurb">{{ item.blurb }}</p>
            <RouterLink
              :to="{ path: '/appointments', query: { service: item.name, category: category.title } }"
              class="btn btn-primary btn-sm"
              style="width:100%;justify-content:center;margin-top:auto"
            >
              Book This Service
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </RouterLink>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ -->
    <section class="section svc-faq-section">
      <div class="container">
        <div class="section-head">
          <span class="eyebrow">Good to Know</span>
          <h2>Frequently Asked Questions</h2>
        </div>
        <div class="faq-list">
          <details class="faq-item" v-for="f in category.faqs" :key="f.q">
            <summary>{{ f.q }}</summary>
            <p>{{ f.a }}</p>
          </details>
        </div>
      </div>
    </section>

    <!-- Related categories -->
    <section class="section related-section">
      <div class="container">
        <p class="related-label">Explore other services</p>
        <div class="related-chips">
          <RouterLink v-for="c in relatedCategories" :key="c.slug" :to="`/services/${c.slug}`" class="related-chip">
            {{ c.title }}
          </RouterLink>
        </div>
      </div>
    </section>

  </div>

  <div v-else class="container" style="padding:180px 0 100px;text-align:center">
    <h2>Service category not found</h2>
    <p class="text-light" style="margin:12px 0 24px">That page doesn't exist, but we've got plenty of others.</p>
    <RouterLink to="/services" class="btn btn-primary">Back to All Services</RouterLink>
  </div>
</template>

<script setup>
import { computed, watch } from 'vue'
import { useRoute }        from 'vue-router'
import { serviceIconPaths } from '../data/services.js'
import { serviceCatalog, serviceCatalogList } from '../data/serviceCatalog.js'
import { PHONE_TEL }        from '../data/contact.js'
import { useScrollReveal }  from '../composables/useScrollReveal.js'

const route = useRoute()
const slug = computed(() => route.params.slug)
const category = computed(() => serviceCatalog[slug.value])
const relatedCategories = computed(() =>
  serviceCatalogList.filter(c => c.slug !== slug.value).slice(0, 5)
)

useScrollReveal()
watch(slug, () => window.scrollTo({ top: 0 }))
</script>

<style scoped>
.svc-hero { padding: 136px 0 48px; background: var(--bg); }
.breadcrumb { display: flex; gap: 8px; align-items: center; font-size: 0.82rem; color: var(--text-light); margin-bottom: 24px; }
.breadcrumb a { color: var(--teal); font-weight: 600; }
.svc-hero-grid { max-width: 640px; }
.icon-circle-lg { width: 64px; height: 64px; margin-bottom: 16px; color: var(--teal); }
.svc-hero h1 { margin: 8px 0 16px; }
.svc-hero-desc { color: var(--text-light); font-size: 1rem; margin-bottom: 24px; }
.sh-actions { display: flex; gap: 12px; flex-wrap: wrap; }

.emergency-cta { background: transparent; }
.emergency-card {
  background: linear-gradient(135deg, var(--red) 0%, #a93226 100%);
  border-radius: var(--radius-lg);
  padding: 28px 36px;
  display: flex;
  align-items: center;
  gap: 24px;
  flex-wrap: wrap;
}
.emergency-icon {
  width: 56px; height: 56px; border-radius: 50%;
  background: rgba(255,255,255,0.15);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}

.section-head { max-width: 560px; margin-bottom: 36px; }
.section-head h2 { margin: 6px 0 10px; }

.svc-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
}
.svc-card {
  padding: 24px;
  display: flex;
  flex-direction: column;
  gap: 8px;
  position: relative;
  transition: transform var(--transition), box-shadow var(--transition);
}
.svc-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
.popular-badge {
  position: absolute;
  top: -11px;
  left: 20px;
  background: var(--teal);
  color: white;
  font-size: 0.68rem;
  font-weight: 700;
  padding: 4px 10px;
  border-radius: 100px;
  letter-spacing: 0.02em;
}
.svc-card h3 { font-size: 1.02rem; margin: 4px 0 0; }
.svc-price { display: flex; align-items: baseline; gap: 6px; }
.svc-price strong { font-size: 1.3rem; color: var(--teal); font-weight: 800; }
.svc-price span { font-size: 0.78rem; color: var(--text-light); }
.svc-blurb { font-size: 0.85rem; color: var(--text-light); flex: 1; margin-bottom: 4px; }

.faq-list { display: flex; flex-direction: column; gap: 10px; max-width: 720px; }
.faq-item {
  background: var(--gray-50);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 16px 20px;
}
.faq-item summary {
  cursor: pointer;
  font-weight: 600;
  font-size: 0.92rem;
  color: var(--navy);
  list-style: none;
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.faq-item summary::-webkit-details-marker { display: none; }
.faq-item summary::after {
  content: '+';
  font-size: 1.2rem;
  color: var(--teal);
  transition: transform var(--transition);
}
.faq-item[open] summary::after { transform: rotate(45deg); }
.faq-item p { margin-top: 10px; font-size: 0.85rem; color: var(--text-light); line-height: 1.6; }

.related-section { padding-top: 0; }
.related-label { font-size: 0.8rem; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 14px; }
.related-chips { display: flex; gap: 10px; flex-wrap: wrap; }
.related-chip {
  padding: 8px 16px;
  border-radius: 100px;
  border: 1px solid var(--border);
  font-size: 0.82rem;
  font-weight: 600;
  color: var(--navy);
  transition: border-color var(--transition), color var(--transition);
}
.related-chip:hover { border-color: var(--teal); color: var(--teal); }

@media (max-width: 900px) {
  .svc-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
  .svc-hero { padding-top: 100px; }
  .svc-grid { grid-template-columns: 1fr; }
  .emergency-card { flex-direction: column; }
  .emergency-card .btn { margin-left: 0; }
}
</style>

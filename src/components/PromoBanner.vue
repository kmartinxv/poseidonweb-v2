<template>
  <section v-if="banners.length" class="promo-banner-section">
    <div class="container">
      <RouterLink
        v-for="b in banners"
        :key="b.id"
        :to="b.link || '/'"
        class="site-promo-banner"
        :style="b.image ? { backgroundImage: `url(${b.image})` } : {}"
      >
        <div class="site-promo-overlay">
          <strong>{{ b.title }}</strong>
          <span v-if="b.subtitle">{{ b.subtitle }}</span>
        </div>
        <span class="site-promo-arrow">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </span>
      </RouterLink>
    </div>
  </section>
</template>

<script setup>
import { useBanners } from '../composables/useBanners.js'

const props = defineProps({ placement: { type: String, required: true } })
const { banners } = useBanners(props.placement)
</script>

<style scoped>
.promo-banner-section { padding: 32px 0 8px; background: inherit; }
.site-promo-banner {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  min-height: 84px;
  padding: 20px 28px;
  margin-bottom: 12px;
  border-radius: var(--radius-lg);
  background: linear-gradient(135deg, var(--navy) 0%, var(--teal-dark) 100%);
  background-size: cover;
  background-position: center;
  overflow: hidden;
  transition: transform var(--transition), box-shadow var(--transition);
}
.site-promo-banner:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
.site-promo-overlay {
  position: relative;
  z-index: 1;
  display: flex;
  flex-direction: column;
  color: white;
}
.site-promo-banner::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(90deg, rgba(13,43,75,0.85) 0%, rgba(13,43,75,0.5) 100%);
}
.site-promo-overlay strong { font-size: 1.05rem; font-weight: 800; }
.site-promo-overlay span   { font-size: 0.85rem; color: rgba(255,255,255,0.8); margin-top: 2px; }
.site-promo-arrow {
  position: relative;
  z-index: 1;
  width: 40px; height: 40px;
  border-radius: 50%;
  background: rgba(255,255,255,0.15);
  color: white;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}

@media (max-width: 600px) {
  .site-promo-banner { padding: 16px 20px; }
  .site-promo-overlay strong { font-size: 0.92rem; }
}
</style>

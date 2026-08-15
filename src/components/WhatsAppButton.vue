<template>
  <a
    :href="whatsappLink()"
    target="_blank"
    rel="noopener"
    class="wa-fab"
    aria-label="Chat with us on WhatsApp"
  >
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
    <span class="wa-fab-tooltip">Chat with us on WhatsApp</span>
  </a>
</template>

<script setup>
import { whatsappLink } from '../data/contact.js'
</script>

<style scoped>
.wa-fab {
  position: fixed;
  right: 22px;
  bottom: 22px;
  width: 58px;
  height: 58px;
  border-radius: 50%;
  background: #25D366;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 6px 18px rgba(37, 211, 102, 0.45);
  z-index: 900;
  transition: transform 200ms ease, box-shadow 200ms ease;
  animation: wa-pulse 2.4s ease-in-out infinite;
}
.wa-fab:hover {
  transform: scale(1.08);
  box-shadow: 0 8px 22px rgba(37, 211, 102, 0.55);
}
.wa-fab-tooltip {
  position: absolute;
  right: 68px;
  top: 50%;
  transform: translateY(-50%);
  background: var(--navy);
  color: white;
  font-size: 0.78rem;
  font-weight: 600;
  padding: 8px 14px;
  border-radius: 8px;
  white-space: nowrap;
  opacity: 0;
  pointer-events: none;
  transition: opacity 180ms ease;
}
.wa-fab:hover .wa-fab-tooltip { opacity: 1; }

@keyframes wa-pulse {
  0%, 100% { box-shadow: 0 6px 18px rgba(37, 211, 102, 0.45); }
  50%      { box-shadow: 0 6px 18px rgba(37, 211, 102, 0.45), 0 0 0 8px rgba(37, 211, 102, 0.15); }
}

@media (max-width: 600px) {
  .wa-fab { right: 16px; bottom: 16px; width: 52px; height: 52px; }
  .wa-fab-tooltip { display: none; }
}
</style>

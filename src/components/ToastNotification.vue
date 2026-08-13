<template>
  <Teleport to="body">
    <div class="toast-stack" aria-live="polite" aria-atomic="false">
      <div
        v-for="t in toasts"
        :key="t.id"
        :class="['toast', `toast-${t.type}`, { dying: t.dying }]"
        role="alert"
      >
        <div class="toast-icon">
          <!-- success -->
          <svg v-if="t.type === 'success'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          <!-- error -->
          <svg v-else-if="t.type === 'error'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          <!-- warn -->
          <svg v-else-if="t.type === 'warn'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
          <!-- info -->
          <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <p class="toast-msg">{{ t.message }}</p>
        <button class="toast-close" @click="dismiss(t.id)" aria-label="Dismiss">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { useToast } from '../composables/useToast.js'
const { toasts, dismiss } = useToast()
</script>

<style scoped>
.toast-stack {
  position: fixed;
  bottom: 28px;
  right: 28px;
  z-index: 9999;
  display: flex;
  flex-direction: column-reverse;
  gap: 10px;
  pointer-events: none;
  max-width: 360px;
  width: calc(100vw - 48px);
}
.toast {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  padding: 14px 16px;
  border-radius: var(--radius);
  background: white;
  box-shadow: 0 8px 32px rgba(0,0,0,0.16), 0 2px 8px rgba(0,0,0,0.08);
  border-left: 3px solid var(--teal);
  pointer-events: all;
  animation: toast-in 350ms cubic-bezier(0.23,1,0.32,1) both;
  will-change: transform, opacity;
}
.toast.dying { animation: toast-out 350ms cubic-bezier(0.77,0,0.175,1) both; }

@keyframes toast-in {
  from { opacity: 0; transform: translateX(24px) scale(0.96); }
  to   { opacity: 1; transform: translateX(0)    scale(1); }
}
@keyframes toast-out {
  from { opacity: 1; transform: translateX(0)    scale(1); }
  to   { opacity: 0; transform: translateX(24px) scale(0.96); }
}

.toast-success { border-color: var(--teal); }
.toast-error   { border-color: var(--red); }
.toast-warn    { border-color: #F59E0B; }
.toast-info    { border-color: var(--navy); }

.toast-icon {
  flex-shrink: 0;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}
.toast-success .toast-icon { background: var(--teal-light); color: var(--teal); }
.toast-error   .toast-icon { background: #FFF3F3; color: var(--red); }
.toast-warn    .toast-icon { background: #FFFBEB; color: #D97706; }
.toast-info    .toast-icon { background: rgba(13,43,75,0.08); color: var(--navy); }

.toast-msg {
  flex: 1;
  font-size: 0.875rem;
  font-weight: 500;
  color: var(--text);
  line-height: 1.4;
}
.toast-close {
  flex-shrink: 0;
  width: 24px;
  height: 24px;
  border-radius: 4px;
  background: transparent;
  color: var(--gray-400);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background var(--transition), color var(--transition);
  margin-top: 2px;
}
.toast-close:hover { background: var(--gray-100); color: var(--text); }
</style>

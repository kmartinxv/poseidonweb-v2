<template>
  <NavBar v-if="!isStandalone" />
  <main>
    <RouterView v-slot="{ Component }">
      <Transition name="fade" mode="out-in">
        <component :is="Component" />
      </Transition>
    </RouterView>
  </main>
  <TheFooter v-if="!isStandalone" />
  <WhatsAppButton v-if="!isAdminArea" />
  <ToastNotification />
</template>

<script setup>
import { computed }      from 'vue'
import { useRoute }      from 'vue-router'
import NavBar            from './components/NavBar.vue'
import TheFooter         from './components/TheFooter.vue'
import WhatsAppButton    from './components/WhatsAppButton.vue'
import ToastNotification from './components/ToastNotification.vue'

const route = useRoute()

// The client portal, staff admin, and the unified login gateway are
// self-contained app views with their own branding, so they skip the
// marketing site's global nav + footer chrome.
const isStandalone = computed(() =>
  route.path.startsWith('/portal') || route.path.startsWith('/admin') || route.path.startsWith('/login')
)
const isAdminArea = computed(() => route.path.startsWith('/admin'))
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 200ms ease-out;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>

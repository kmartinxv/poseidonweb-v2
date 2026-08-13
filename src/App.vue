<template>
  <NavBar v-if="!isPortal" />
  <main>
    <RouterView v-slot="{ Component }">
      <Transition name="fade" mode="out-in">
        <component :is="Component" />
      </Transition>
    </RouterView>
  </main>
  <TheFooter v-if="!isPortal" />
  <ToastNotification />
</template>

<script setup>
import { computed }      from 'vue'
import { useRoute }      from 'vue-router'
import NavBar            from './components/NavBar.vue'
import TheFooter         from './components/TheFooter.vue'
import ToastNotification from './components/ToastNotification.vue'

const route = useRoute()

// The client portal is a self-contained app view with its own sidebar/topbar
// branding, so it skips the marketing site's global nav + footer chrome.
const isPortal = computed(() => route.path.startsWith('/portal'))
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

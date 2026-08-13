import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  { path: '/', component: () => import('../views/HomeView.vue'), meta: { title: 'Home' } },
  { path: '/about', component: () => import('../views/AboutView.vue'), meta: { title: 'About Us' } },
  { path: '/services', component: () => import('../views/ServicesView.vue'), meta: { title: 'Services' } },
  { path: '/shop', component: () => import('../views/ShopView.vue'), meta: { title: 'Pet Shop' } },
  { path: '/shop/:id', component: () => import('../views/ProductView.vue'), meta: { title: 'Product' } },
  { path: '/cart', component: () => import('../views/CartView.vue'), meta: { title: 'Cart' } },
  { path: '/checkout', component: () => import('../views/CheckoutView.vue'), meta: { title: 'Checkout' } },
  { path: '/appointments', component: () => import('../views/AppointmentsView.vue'), meta: { title: 'Book Appointment' } },
  { path: '/blog', component: () => import('../views/BlogView.vue'), meta: { title: 'Blog' } },
  { path: '/blog/:slug', component: () => import('../views/BlogPostView.vue'), meta: { title: 'Article' } },
  { path: '/contact', component: () => import('../views/ContactView.vue'), meta: { title: 'Contact' } },
  { path: '/portal', component: () => import('../views/PortalView.vue'), meta: { title: 'Client Portal' } },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() { return { top: 0, behavior: 'smooth' } }
})

router.afterEach((to) => {
  document.title = `${to.meta.title || 'Page'} – Poseidon Veterinary Clinic`
})

export default router

import { ref } from 'vue'
import { api } from '../lib/api.js'

export function useProducts() {
  const products = ref([])
  const loading  = ref(true)
  const error    = ref(null)

  async function load() {
    loading.value = true
    error.value = null
    try {
      const data = await api.get('/products/list.php')
      products.value = data.products.map(p => ({
        id:         p.id,
        name:       p.name,
        slug:       p.slug,
        category:   p.category_name,
        categorySlug: p.category_slug,
        price:      Number(p.price),
        oldPrice:   p.old_price !== null ? Number(p.old_price) : null,
        rating:     Number(p.rating),
        reviews:    p.reviews,
        badge:      p.badge,
        image:      p.image,
        description: p.description,
        inStock:    !!p.in_stock,
      }))
    } catch (e) {
      error.value = e.message
    } finally {
      loading.value = false
    }
  }

  load()
  return { products, loading, error, reload: load }
}

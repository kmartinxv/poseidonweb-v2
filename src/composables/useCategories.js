import { ref } from 'vue'
import { api } from '../lib/api.js'

export function useCategories() {
  const categories = ref([])
  const loading     = ref(true)

  async function load() {
    loading.value = true
    try {
      const data = await api.get('/categories/list.php')
      categories.value = data.categories
    } finally {
      loading.value = false
    }
  }

  load()
  return { categories, loading, reload: load }
}

import { ref } from 'vue'
import { api } from '../lib/api.js'

export function useBanners(placement) {
  const banners = ref([])
  const loading  = ref(true)

  async function load() {
    loading.value = true
    try {
      const data = await api.get(`/banners/list.php?placement=${encodeURIComponent(placement)}`)
      banners.value = data.banners
    } finally {
      loading.value = false
    }
  }

  load()
  return { banners, loading, reload: load }
}

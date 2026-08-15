import { ref } from 'vue'
import { api } from '../lib/api.js'

function formatDate(sqlDatetime) {
  if (!sqlDatetime) return ''
  return new Date(sqlDatetime.replace(' ', 'T')).toLocaleDateString('en-US', {
    year: 'numeric', month: 'long', day: 'numeric',
  })
}

export function useBlogPosts() {
  const posts   = ref([])
  const loading = ref(true)
  const error   = ref(null)

  async function load() {
    loading.value = true
    error.value = null
    try {
      const data = await api.get('/blog/list.php')
      posts.value = data.posts.map(p => ({
        ...p,
        date: formatDate(p.created_at),
        tags: p.tags ? p.tags.split(',').map(t => t.trim()) : [],
      }))
    } catch (e) {
      error.value = e.message
    } finally {
      loading.value = false
    }
  }

  load()
  return { posts, loading, error, reload: load }
}

export async function fetchBlogPostBySlug(slug) {
  const { post } = await api.get(`/blog/get.php?slug=${encodeURIComponent(slug)}`)
  return {
    ...post,
    date: formatDate(post.created_at),
    tags: post.tags ? post.tags.split(',').map(t => t.trim()) : [],
  }
}

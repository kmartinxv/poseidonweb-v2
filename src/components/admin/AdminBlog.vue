<template>
  <div class="admin-panel">
    <div class="admin-panel-head">
      <div>
        <h2>Blog Posts</h2>
        <p class="text-light">{{ posts.length }} posts</p>
      </div>
      <button class="btn btn-primary" @click="openCreate">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Post
      </button>
    </div>

    <div v-if="showForm" class="admin-form card">
      <h3>{{ editingId ? 'Edit Post' : 'New Post' }}</h3>
      <form @submit.prevent="save">
        <div class="form-2col">
          <div class="form-group" style="grid-column:1/-1">
            <label class="form-label">Title</label>
            <input class="form-control" v-model="form.title" required />
          </div>
          <div class="form-group">
            <label class="form-label">Category</label>
            <input class="form-control" v-model="form.category" placeholder="Wellness, Nutrition, Boarding…" />
          </div>
          <div class="form-group">
            <label class="form-label">Read Time</label>
            <input class="form-control" v-model="form.read_time" placeholder="5 min read" />
          </div>
          <div class="form-group">
            <label class="form-label">Author</label>
            <input class="form-control" v-model="form.author" />
          </div>
          <div class="form-group">
            <label class="form-label">Status</label>
            <select class="form-control" v-model="form.published">
              <option :value="true">Published</option>
              <option :value="false">Draft</option>
            </select>
          </div>
          <div class="form-group" style="grid-column:1/-1">
            <label class="form-label">Excerpt</label>
            <textarea class="form-control" rows="2" v-model="form.excerpt"></textarea>
          </div>
          <div class="form-group" style="grid-column:1/-1">
            <label class="form-label">Tags (comma separated)</label>
            <input class="form-control" v-model="form.tags" placeholder="Wellness, Prevention, Dogs" />
          </div>
        </div>

        <div class="form-2col" style="margin-top:16px">
          <ImageUploadField v-model="form.image" label="Cover Image" />
          <ImageUploadField v-model="form.author_avatar" label="Author Photo" />
        </div>

        <div class="form-group" style="margin-top:16px">
          <label class="form-label">Body</label>
          <p class="body-hint">
            Blank line = new paragraph &nbsp;·&nbsp; <code>## Heading</code> &nbsp;·&nbsp;
            <code>&gt; Quote</code> &nbsp;·&nbsp; <code>- List item</code> &nbsp;·&nbsp; <code>**bold**</code>
          </p>
          <textarea class="form-control body-textarea" rows="14" v-model="form.body" required></textarea>
        </div>

        <div class="form-group" style="margin-top:12px">
          <label class="form-label">Preview</label>
          <div class="body-preview" v-html="preview"></div>
        </div>

        <div v-if="formError" class="form-error">{{ formError }}</div>
        <div class="admin-form-actions">
          <button type="button" class="btn btn-secondary" @click="showForm = false">Cancel</button>
          <button type="submit" class="btn btn-primary" :disabled="saving">{{ saving ? 'Saving…' : 'Save Post' }}</button>
        </div>
      </form>
    </div>

    <div class="table-card card">
      <table class="admin-table">
        <thead><tr><th></th><th>Title</th><th>Category</th><th>Author</th><th>Status</th><th></th></tr></thead>
        <tbody>
          <tr v-for="p in posts" :key="p.id">
            <td><img v-if="p.image" :src="p.image" class="row-thumb" /></td>
            <td><strong>{{ p.title }}</strong></td>
            <td>{{ p.category || '—' }}</td>
            <td>{{ p.author || '—' }}</td>
            <td><span :class="['status-chip', p.published ? 'active' : 'hidden']">{{ p.published ? 'Published' : 'Draft' }}</span></td>
            <td class="row-actions">
              <button class="row-btn" @click="openEdit(p)">Edit</button>
              <button class="row-btn danger" @click="remove(p)">Delete</button>
            </td>
          </tr>
          <tr v-if="!posts.length"><td colspan="6" class="empty-row">No posts yet.</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { api }                 from '../../lib/api.js'
import { useToast }            from '../../composables/useToast.js'
import { renderMarkdownLite }  from '../../lib/markdownLite.js'
import ImageUploadField        from './ImageUploadField.vue'

const { success, error: toastError } = useToast()
const posts = ref([])

async function load() {
  const data = await api.get('/blog/list.php')
  posts.value = data.posts
}
load()

const showForm  = ref(false)
const editingId = ref(null)
const saving    = ref(false)
const formError = ref('')

function blankForm() {
  return { title: '', category: '', read_time: '', author: '', published: true, excerpt: '', tags: '', image: '', author_avatar: '', body: '' }
}
const form = reactive(blankForm())
const preview = computed(() => renderMarkdownLite(form.body))

function openCreate() {
  Object.assign(form, blankForm())
  editingId.value = null
  formError.value = ''
  showForm.value = true
}

async function openEdit(p) {
  const { post } = await api.get(`/blog/get.php?id=${p.id}`)
  Object.assign(form, {
    title: post.title, category: post.category, read_time: post.read_time,
    author: post.author, published: !!post.published, excerpt: post.excerpt,
    tags: post.tags, image: post.image, author_avatar: post.author_avatar, body: post.body,
  })
  editingId.value = p.id
  formError.value = ''
  showForm.value = true
}

async function save() {
  saving.value = true
  formError.value = ''
  try {
    if (editingId.value) {
      await api.post('/blog/update.php', { id: editingId.value, ...form })
      success('Post updated')
    } else {
      await api.post('/blog/create.php', form)
      success('Post created')
    }
    showForm.value = false
    await load()
  } catch (e) {
    formError.value = e.message
  } finally {
    saving.value = false
  }
}

async function remove(p) {
  if (!confirm(`Delete "${p.title}"? This can't be undone.`)) return
  try {
    await api.post('/blog/delete.php', { id: p.id })
    success('Post deleted')
    await load()
  } catch (e) {
    toastError(e.message)
  }
}
</script>

<style scoped>
.admin-panel-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
.admin-panel-head h2 { font-size: 1.3rem; margin-bottom: 4px; }
.admin-form { padding: 24px; margin-bottom: 24px; }
.admin-form h3 { font-size: 1.05rem; margin-bottom: 18px; }
.form-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.admin-form-actions { display: flex; gap: 12px; margin-top: 20px; }
.form-error { background: #FEE2E2; color: var(--red); font-size: 0.82rem; padding: 10px 14px; border-radius: var(--radius-sm); margin-top: 14px; }

.body-hint { font-size: 0.75rem; color: var(--text-light); margin-bottom: 8px; }
.body-hint code { background: var(--gray-100); padding: 1px 5px; border-radius: 4px; font-family: monospace; }
.body-textarea { font-family: monospace; font-size: 0.85rem; line-height: 1.6; }
.body-preview {
  border: 1px solid var(--border); border-radius: var(--radius-sm);
  padding: 16px 18px; max-height: 260px; overflow-y: auto;
  background: var(--gray-50); font-size: 0.88rem; color: var(--text);
}
.body-preview :deep(h2) { font-size: 1.1rem; margin: 14px 0 8px; }
.body-preview :deep(p)  { margin-bottom: 10px; line-height: 1.6; }
.body-preview :deep(blockquote) { border-left: 3px solid var(--teal); padding-left: 12px; color: var(--text-light); font-style: italic; margin-bottom: 10px; }
.body-preview :deep(ul) { padding-left: 20px; margin-bottom: 10px; }

.table-card { overflow-x: auto; }
.admin-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
.admin-table th { text-align: left; padding: 12px 16px; background: var(--gray-50); color: var(--text-light); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em; white-space: nowrap; }
.admin-table td { padding: 12px 16px; border-top: 1px solid var(--border); white-space: nowrap; }
.row-thumb { width: 36px; height: 36px; border-radius: 6px; object-fit: cover; }
.status-chip { font-size: 0.7rem; font-weight: 600; padding: 2px 9px; border-radius: 100px; }
.status-chip.active { background: #DCFCE7; color: #16A34A; }
.status-chip.hidden  { background: var(--gray-200); color: var(--text-light); }
.row-actions { display: flex; gap: 8px; }
.row-btn { font-size: 0.78rem; font-weight: 600; color: var(--teal); padding: 4px 8px; }
.row-btn.danger { color: var(--red); }
.row-btn:hover { text-decoration: underline; }
.empty-row { text-align: center; color: var(--text-light); padding: 32px !important; }

@media (max-width: 700px) { .form-2col { grid-template-columns: 1fr; } }
</style>

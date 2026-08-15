<template>
  <div class="form-group">
    <label class="form-label">{{ label }}</label>
    <div class="image-upload">
      <div class="image-preview" v-if="modelValue">
        <img :src="modelValue" alt="" />
        <button type="button" class="image-remove" @click="$emit('update:modelValue', '')" title="Remove image">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <label class="image-upload-btn">
        <input type="file" accept="image/jpeg,image/png,image/webp,image/gif" @change="handleFile" :disabled="uploading" hidden />
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        {{ uploading ? 'Uploading…' : (modelValue ? 'Replace image' : 'Upload image') }}
      </label>
      <span v-if="error" class="image-error">{{ error }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { api } from '../../lib/api.js'

defineProps({ label: { type: String, default: 'Image' }, modelValue: String })
const emit = defineEmits(['update:modelValue'])

const uploading = ref(false)
const error     = ref('')

async function handleFile(e) {
  const file = e.target.files[0]
  if (!file) return
  uploading.value = true
  error.value = ''
  try {
    const form = new FormData()
    form.append('image', file)
    const { url } = await api.post('/upload.php', form)
    emit('update:modelValue', url)
  } catch (err) {
    error.value = err.message || 'Upload failed'
  } finally {
    uploading.value = false
    e.target.value = ''
  }
}
</script>

<style scoped>
.image-upload { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.image-preview { position: relative; width: 64px; height: 64px; border-radius: var(--radius-sm); overflow: hidden; border: 1px solid var(--border); flex-shrink: 0; }
.image-preview img { width: 100%; height: 100%; object-fit: cover; }
.image-remove {
  position: absolute; top: 2px; right: 2px;
  width: 18px; height: 18px; border-radius: 50%;
  background: rgba(0,0,0,0.6); color: white;
  display: flex; align-items: center; justify-content: center;
}
.image-upload-btn {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 10px 16px;
  border: 1.5px dashed var(--border);
  border-radius: var(--radius-sm);
  font-size: 0.85rem; font-weight: 500; color: var(--text-light);
  cursor: pointer;
  transition: border-color var(--transition), color var(--transition);
}
.image-upload-btn:hover { border-color: var(--teal); color: var(--teal); }
.image-error { font-size: 0.78rem; color: var(--red); width: 100%; }
</style>

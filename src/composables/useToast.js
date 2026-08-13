import { reactive } from 'vue'

const toasts = reactive([])
let uid = 0

export function useToast() {
  function add(message, type = 'success', duration = 3200) {
    const id = ++uid
    toasts.push({ id, message, type, dying: false })

    // mark dying for exit animation
    setTimeout(() => {
      const t = toasts.find(t => t.id === id)
      if (t) t.dying = true
    }, duration - 350)

    // remove after animation
    setTimeout(() => {
      const idx = toasts.findIndex(t => t.id === id)
      if (idx > -1) toasts.splice(idx, 1)
    }, duration)
  }

  return {
    toasts,
    success: (msg, dur) => add(msg, 'success', dur),
    error:   (msg, dur) => add(msg, 'error',   dur),
    info:    (msg, dur) => add(msg, 'info',     dur),
    warn:    (msg, dur) => add(msg, 'warn',     dur),
    dismiss: (id)       => {
      const t = toasts.find(t => t.id === id)
      if (t) t.dying = true
      setTimeout(() => {
        const idx = toasts.findIndex(t => t.id === id)
        if (idx > -1) toasts.splice(idx, 1)
      }, 350)
    },
  }
}

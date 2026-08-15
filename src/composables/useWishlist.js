import { reactive } from 'vue'

const state = reactive({ ids: [] })

export function useWishlist() {
  function isWishlisted(id) {
    return state.ids.includes(id)
  }

  function toggleWishlist(id) {
    const idx = state.ids.indexOf(id)
    if (idx > -1) state.ids.splice(idx, 1)
    else state.ids.push(id)
  }

  return { wishlist: state, isWishlisted, toggleWishlist }
}

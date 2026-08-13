import { onMounted, onUnmounted } from 'vue'

/**
 * Scroll reveal via IntersectionObserver.
 * Add `data-reveal` (or `data-reveal="left"/"right"/"scale"`) to any element.
 * Optionally add `data-delay="200"` etc for stagger.
 */
export function useScrollReveal(selector = '[data-reveal]') {
  let observer

  onMounted(() => {
    // Respect prefers-reduced-motion
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      document.querySelectorAll(selector).forEach(el => el.classList.add('revealed'))
      return
    }

    observer = new IntersectionObserver(
      (entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('revealed')
            observer.unobserve(entry.target)
          }
        })
      },
      { threshold: 0.08, rootMargin: '0px 0px -48px 0px' }
    )

    document.querySelectorAll(selector).forEach(el => observer.observe(el))
  })

  onUnmounted(() => observer?.disconnect())
}

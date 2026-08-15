/**
 * Tiny, staff-friendly text format used for blog post bodies:
 *   blank line   → new paragraph
 *   ## Heading   → H2
 *   > Quote      → blockquote
 *   - Item       → bullet list (consecutive lines group into one <ul>)
 *   **bold**     → <strong>
 * Not a full markdown implementation — deliberately small and predictable.
 */

function escapeHtml(str) {
  return str
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
}

function inline(str) {
  return escapeHtml(str).replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
}

export function renderMarkdownLite(text) {
  if (!text) return ''

  const blocks = text.trim().split(/\n\s*\n/)
  let html = ''

  for (const block of blocks) {
    const lines = block.split('\n').map(l => l.trim()).filter(Boolean)
    if (lines.length === 0) continue

    if (lines[0].startsWith('## ')) {
      html += `<h2>${inline(lines[0].slice(3))}</h2>`
    } else if (lines.every(l => l.startsWith('> '))) {
      html += `<blockquote>${inline(lines.map(l => l.slice(2)).join(' '))}</blockquote>`
    } else if (lines.every(l => l.startsWith('- '))) {
      html += '<ul>' + lines.map(l => `<li>${inline(l.slice(2))}</li>`).join('') + '</ul>'
    } else {
      html += `<p>${inline(lines.join(' '))}</p>`
    }
  }

  return html
}

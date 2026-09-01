import * as Lexxy from "@37signals/lexxy"

// Rich Text Laravel uses a custom attachment tag name, so we must configure it here...
// Guard against double-define (Vite HMR / double load)
if (!customElements.get('lexxy-toolbar')) {
  try {
    Lexxy.configure({ global: { attachmentTagName: "rich-text-attachment" } })
  } catch (e) {
    if (!String(e.message || '').includes('has already been used')) throw e;
  }
}
if (!customElements.get('lexxy-editor')) {
  // Ensure editor also not double-defined if Lexxy defines it lazily
}

export default Lexxy;

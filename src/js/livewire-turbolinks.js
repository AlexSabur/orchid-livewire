import 'livewire-turbolinks'

const observer = new MutationObserver(() => {
  window.Livewire.rescan()
})

const observeDataSync = () => {
  observer.disconnect()

  document.querySelectorAll('[data-async]').forEach((element) => {
    observer.observe(element, { childList: true })
  })
}

document.addEventListener('livewire:load', observeDataSync)

function wireTurboBeforeStreamRender(event) {
  const originalRender = event.detail.render

  event.detail.render = async function (...args) {
    await originalRender(...args)
    window.Livewire.rescan()
  }
}

document.addEventListener("turbo:before-stream-render", wireTurboBeforeStreamRender)

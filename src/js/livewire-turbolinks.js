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

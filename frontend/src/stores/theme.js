import { defineStore } from 'pinia'
import { ref, watch } from 'vue'
import { Dark } from 'quasar'

export const useThemeStore = defineStore('theme', () => {
  const isDark = ref(localStorage.getItem('theme') !== 'light')

  function apply() {
    Dark.set(isDark.value)
    document.documentElement.setAttribute('data-theme', isDark.value ? 'dark' : 'light')
    localStorage.setItem('theme', isDark.value ? 'dark' : 'light')
  }

  function toggle() {
    isDark.value = !isDark.value
  }

  function setDark(value) {
    isDark.value = value
  }

  watch(isDark, apply, { immediate: true })

  return { isDark, toggle, setDark }
})

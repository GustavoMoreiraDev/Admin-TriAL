import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { api } from 'boot/axios'

export const useAuthStore = defineStore('auth', () => {
  const token   = ref(localStorage.getItem('jwt_token') ?? null)
  const usuario = ref(JSON.parse(localStorage.getItem('auth_user') ?? 'null'))

  const isAuthenticated = computed(() => !!token.value)

  function setToken(novoToken) {
    token.value = novoToken
    localStorage.setItem('jwt_token', novoToken)
  }

  function setUsuario(user) {
    usuario.value = user
    if (user) {
      localStorage.setItem('auth_user', JSON.stringify(user))
    } else {
      localStorage.removeItem('auth_user')
    }
  }

  function clearSession() {
    token.value   = null
    usuario.value = null
    localStorage.removeItem('jwt_token')
    localStorage.removeItem('auth_user')
  }

  async function register(dados) {
    const { data } = await api.post('/auth/register', dados)
    setToken(data.token)
    setUsuario(data.usuario)
    return data
  }

  async function login(credentials) {
    const { data } = await api.post('/auth/login', credentials)
    setToken(data.token)
    return data
  }

  async function fetchMe() {
    const { data } = await api.get('/auth/me')
    setUsuario(data.usuario)
    return data.usuario
  }

  async function updateProfile(payload) {
    const { data } = await api.put('/auth/profile', payload)
    setUsuario(data.usuario)
    return data.usuario
  }

  async function logout() {
    try {
      await api.post('/auth/logout')
    } finally {
      clearSession()
    }
  }

  return {
    token,
    usuario,
    isAuthenticated,
    register,
    login,
    fetchMe,
    updateProfile,
    logout,
    setUsuario,
    clearSession,
  }
})

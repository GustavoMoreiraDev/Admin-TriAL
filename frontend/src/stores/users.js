import { defineStore } from 'pinia'
import { ref } from 'vue'
import { api } from 'boot/axios'

export const useUsersStore = defineStore('users', () => {
  const users      = ref([])
  const pagination = ref({ page: 1, rowsPerPage: 15, rowsNumber: 0 })
  const loading    = ref(false)

  async function fetchUsers(params = {}) {
    loading.value = true
    try {
      const { data } = await api.get('/usuarios', { params })
      users.value      = data.data
      pagination.value = {
        page:        data.current_page,
        rowsPerPage: data.per_page,
        rowsNumber:  data.total,
      }
      return data
    } finally {
      loading.value = false
    }
  }

  async function createUser(payload) {
    const { data } = await api.post('/usuarios', payload)
    return data
  }

  async function updateUser(id, payload) {
    const { data } = await api.put(`/usuarios/${id}`, payload)
    return data.usuario
  }

  async function deleteUser(id) {
    await api.delete(`/usuarios/${id}`)
    users.value = users.value.filter(u => u.id !== id)
  }

  async function searchUsers(query, perPage = 10) {
    const { data } = await api.get('/usuarios', {
      params: { search: query, per_page: perPage }
    })
    return data.data ?? []
  }

  return { users, pagination, loading, fetchUsers, createUser, updateUser, deleteUser, searchUsers }
})

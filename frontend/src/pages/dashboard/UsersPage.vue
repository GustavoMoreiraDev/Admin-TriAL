<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useQuasar } from 'quasar'
import { useRoute } from 'vue-router'
import { useAuthStore } from 'stores/auth'
import { useUsersStore } from 'stores/users'
import { formatDate } from 'utils/format'
import UserFormDialog from 'components/users/UserFormDialog.vue'
import RoleBadge from 'components/common/RoleBadge.vue'
import StatusBadge from 'components/common/StatusBadge.vue'

const $q         = useQuasar()
const route      = useRoute()
const authStore  = useAuthStore()
const usersStore = useUsersStore()

const dialogOpen   = ref(false)
const editingUser  = ref(null)
const deleteDialog = ref(false)
const userToDelete = ref(null)

const filters = reactive({ search: '', role: '', status: '' })

const canEdit   = computed(() => authStore.usuario?.role === 'administrador')
const canCreate = computed(() => authStore.usuario?.role === 'administrador')

const columns = [
  { name: 'nome',   label: 'Nome',      field: 'nome',           align: 'left',   sortable: true },
  { name: 'email',  label: 'E-mail',    field: 'email',          align: 'left',   sortable: true },
  { name: 'role',   label: 'Perfil',    field: 'role',           align: 'center', sortable: true },
  { name: 'status', label: 'Status',    field: 'status',         align: 'center', sortable: true },
  { name: 'expira', label: 'Expira em', field: 'data_expiracao', align: 'center' },
  { name: 'acoes',  label: '',          field: 'acoes',          align: 'right'  },
]

const roleOptions = [
  { label: 'Todos os perfis', value: '' },
  { label: 'Administrador',   value: 'administrador' },
  { label: 'Editor',          value: 'editor'        },
  { label: 'Consultor',       value: 'consultor'     },
]

const statusOptions = [
  { label: 'Todos',    value: ''         },
  { label: 'Ativo',    value: 'ativo'    },
  { label: 'Expirado', value: 'expirado' },
]

async function load(pagination = {}) {
  const params = {
    page:     pagination.page        ?? 1,
    per_page: pagination.rowsPerPage ?? 15,
    ...filters,
  }
  try {
    await usersStore.fetchUsers(params)
  } catch {
    $q.notify({ type: 'negative', message: 'Erro ao carregar usuários.' })
  }
}

function openCreate() {
  editingUser.value = null
  dialogOpen.value  = true
}

function openEdit(user) {
  editingUser.value = { ...user }
  dialogOpen.value  = true
}

function confirmDelete(user) {
  userToDelete.value = user
  deleteDialog.value = true
}

async function doDelete() {
  try {
    await usersStore.deleteUser(userToDelete.value.id)
    $q.notify({ type: 'positive', message: 'Usuário removido com sucesso.' })
    deleteDialog.value = false
    load()
  } catch {
    $q.notify({ type: 'negative', message: 'Erro ao remover usuário.' })
  }
}

async function handleSaved({ payload, isEdit, id }) {
  try {
    if (isEdit) {
      await usersStore.updateUser(id, payload)
      $q.notify({ type: 'positive', message: 'Usuário atualizado com sucesso.' })
      load()
    } else {
      await usersStore.createUser(payload)
      $q.notify({ type: 'info', icon: 'mail_outline', message: 'Usuário em processamento. Credenciais serão enviadas por e-mail.' })
    }
  } catch (err) {
    const msg = err.response?.data?.message ?? 'Erro ao salvar usuário.'
    $q.notify({ type: 'negative', message: msg })
    throw err
  }
}

let searchTimer = null
function onSearchInput() {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => load(), 400)
}

onMounted(() => {
  if (route.query.search) filters.search = route.query.search
  load()
})
</script>

<template>
  <q-page class="users-page">
    <div class="page-header">
      <div>
        <h1 class="page-header__title">Usuários</h1>
        <p class="page-header__sub">Gerencie os usuários cadastrados na plataforma.</p>
      </div>

      <q-btn
        v-if="canCreate"
        label="NOVO USUÁRIO"
        icon="person_add"
        class="btn-new"
        unelevated
        padding="10px 22px"
        @click="openCreate"
      />
    </div>

    <div class="filters-bar">
      <q-input
        v-model="filters.search"
        outlined dense
        placeholder="Buscar por nome ou e-mail..."
        class="filter-search"
        hide-bottom-space
        @update:model-value="onSearchInput"
      >
        <template #prepend><q-icon name="search" size="16px" /></template>
        <template v-if="filters.search" #append>
          <q-icon name="close" class="cursor-pointer" size="14px" @click="filters.search = ''; load()" />
        </template>
      </q-input>

      <q-select
        v-model="filters.role"
        :options="roleOptions"
        option-value="value" option-label="label"
        emit-value map-options
        outlined dense
        class="filter-select"
        hide-bottom-space
        @update:model-value="load()"
      />

      <q-select
        v-model="filters.status"
        :options="statusOptions"
        option-value="value" option-label="label"
        emit-value map-options
        outlined dense
        class="filter-select"
        hide-bottom-space
        @update:model-value="load()"
      />
    </div>

    <div class="table-card">
      <q-table
        :rows="usersStore.users"
        :columns="columns"
        :loading="usersStore.loading"
        :rows-per-page-options="[10, 15, 25, 50]"
        row-key="id"
        flat
        :class="['users-table', $q.dark.isActive ? 'users-table--dark' : 'users-table--light']"
        v-model:pagination="usersStore.pagination"
        @request="load($event.pagination)"
      >
        <template #body-cell-nome="{ row }">
          <q-td>
            <div class="user-cell">
              <div class="user-cell__avatar">
                {{ row.nome?.split(' ').slice(0,2).map(n => n[0]).join('').toUpperCase() }}
              </div>
              <div>
                <div class="user-cell__name">{{ row.nome }}</div>
                <div class="user-cell__sub">{{ formatDate(row.created_at) }}</div>
              </div>
            </div>
          </q-td>
        </template>

        <template #body-cell-role="{ row }">
          <q-td class="text-center">
            <role-badge :role="row.role" />
          </q-td>
        </template>

        <template #body-cell-status="{ row }">
          <q-td class="text-center">
            <status-badge :status="row.status" />
          </q-td>
        </template>

        <template #body-cell-expira="{ row }">
          <q-td class="text-center">
            <span class="expira-text">{{ formatDate(row.data_expiracao) }}</span>
          </q-td>
        </template>

        <template #body-cell-acoes="{ row }">
          <q-td class="text-right">
            <q-btn flat round dense icon="edit" size="sm" color="primary"
              :disable="!canEdit" @click="openEdit(row)">
              <q-tooltip>Editar</q-tooltip>
            </q-btn>
            <q-btn flat round dense icon="delete_outline" size="sm" color="negative"
              :disable="!canEdit" @click="confirmDelete(row)">
              <q-tooltip>Remover</q-tooltip>
            </q-btn>
          </q-td>
        </template>

        <template #no-data>
          <div class="empty-state">
            <q-icon name="people_outline" size="48px" class="empty-state__icon" />
            <p class="empty-state__text">Nenhum usuário encontrado</p>
          </div>
        </template>
      </q-table>
    </div>

    <user-form-dialog
      v-model="dialogOpen"
      :editing-user="editingUser"
      @saved="handleSaved"
    />

    <q-dialog v-model="deleteDialog">
      <q-card class="delete-dialog">
        <q-card-section class="row items-center gap-md">
          <q-avatar icon="warning" color="negative" text-color="white" />
          <div>
            <div class="delete-dialog__title">Remover usuário?</div>
            <div class="delete-dialog__sub">
              <strong>{{ userToDelete?.nome }}</strong> será removido permanentemente.
            </div>
          </div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn flat label="Cancelar" v-close-popup />
          <q-btn label="REMOVER" color="negative" unelevated @click="doDelete" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<style scoped lang="scss">
.users-page {
  padding: 0 36px 32px;
  min-height: 100vh;
  background: var(--bg-page);

  @media (max-width: 768px) { padding: 0 16px 24px; }
}

.page-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  margin-bottom: 24px;
  gap: 16px;
  flex-wrap: wrap;
}

.page-header__title {
  margin: 0 0 4px;
  font-size: 1.75rem;
  font-weight: 800;
  color: var(--text-primary);
}

.page-header__sub {
  margin: 0;
  font-size: 0.875rem;
  color: var(--text-muted);
}

.btn-new {
  border-radius: 10px;
  font-weight: 700;
  letter-spacing: 0.05em;
  background: linear-gradient(90deg, #11d6ff, #00F5D4) !important;
  color: #04111f !important;
  flex-shrink: 0;
}

.filters-bar {
  display: flex;
  gap: 12px;
  margin-bottom: 20px;
  flex-wrap: wrap;
}

.filter-search {
  flex: 1;
  min-width: 200px;
}

.filter-select {
  min-width: 160px;

  @media (max-width: 600px) { min-width: 130px; }
}

.table-card {
  background: var(--bg-card);
  border: 1px solid var(--border-subtle);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: var(--shadow-card);
}

.users-table {
  &--dark  { background: transparent !important; }
  &--light { background: #fff !important; }

  :deep(.q-table__top),
  :deep(.q-table__bottom) { background: transparent; }

  :deep(thead tr th) {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--text-label);
    border-bottom: 1px solid var(--border-subtle);
  }

  :deep(tbody tr) { background: var(--bg-card); }
  :deep(tbody tr:hover td) { background: var(--bg-table-row); }
}

.user-cell {
  display: flex;
  align-items: center;
  gap: 12px;
}

.user-cell__avatar {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  background: linear-gradient(135deg, #11d6ff, #9D50FF);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.72rem;
  font-weight: 800;
  color: #04111f;
  flex-shrink: 0;
}

.user-cell__name {
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--text-primary);
}

.user-cell__sub {
  font-size: 0.72rem;
  color: var(--text-muted);
}

.expira-text {
  font-size: 0.82rem;
  color: var(--text-muted);
}

.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 48px;
  gap: 12px;
}

.empty-state__icon { opacity: 0.25; }

.empty-state__text {
  font-size: 0.9rem;
  color: var(--text-muted);
}

.delete-dialog {
  border-radius: 14px !important;
  min-width: 340px;
  background: var(--bg-dialog) !important;
  border: 1px solid var(--border-subtle);
}

.delete-dialog__title {
  font-size: 1rem;
  font-weight: 700;
  color: var(--text-primary);
}

.delete-dialog__sub {
  font-size: 0.85rem;
  color: var(--text-muted);
  margin-top: 3px;
}
</style>

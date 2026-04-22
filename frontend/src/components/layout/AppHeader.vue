<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useQuasar } from 'quasar'
import { useAuthStore } from 'stores/auth'
import { useThemeStore } from 'stores/theme'

defineEmits(['toggle-drawer'])

const $q         = useQuasar()
const router     = useRouter()
const authStore  = useAuthStore()
const themeStore = useThemeStore()

const usuario = computed(() => authStore.usuario)
const initials = computed(() => {
  const nome = usuario.value?.nome ?? ''
  return nome.split(' ').slice(0, 2).map(n => n[0]).join('').toUpperCase()
})
const roleLabel = computed(() => {
  const map = { administrador: 'Administrador', editor: 'Editor', consultor: 'Consultor' }
  return map[usuario.value?.role] ?? 'Usuário'
})

const searchQuery = ref('')

function handleSearch() {
  const q = searchQuery.value.trim()
  if (!q) return
  router.push({ name: 'search', query: { q } })
  searchQuery.value = ''
}

async function handleLogout() {
  try {
    await authStore.logout()
    router.push({ name: 'login' })
  } catch {
    $q.notify({ type: 'negative', message: 'Erro ao sair.' })
  }
}
</script>

<template>
  <q-header class="app-header">
    <q-toolbar class="app-header__toolbar">

      <q-btn flat round dense icon="menu" class="app-header__menu-btn lt-md" @click="$emit('toggle-drawer')" />

      <div v-if="usuario?.role !== 'consultor'" class="app-header__search-wrap gt-sm">
        <div class="app-header__search">
          <q-icon name="search" size="16px" class="app-header__search-icon" />
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Buscar usuários, páginas..."
            class="app-header__search-input"
            @keydown.enter="handleSearch"
          />
          <span class="app-header__search-hint">↵ para buscar</span>
        </div>
      </div>

      <div class="app-header__spacer" />

      <div class="app-header__actions">
        <q-btn flat round dense size="sm" class="app-header__action-btn" @click="themeStore.toggle()">
          <q-icon :name="themeStore.isDark ? 'light_mode' : 'dark_mode'" size="19px" />
          <q-tooltip>{{ themeStore.isDark ? 'Tema Claro' : 'Tema Escuro' }}</q-tooltip>
        </q-btn>

        <q-btn flat no-caps class="app-header__user">
          <div class="user-avatar">{{ initials }}</div>
          <div class="user-info gt-xs">
            <span class="user-info__name">{{ usuario?.nome?.split(' ')[0] }}</span>
            <span class="user-info__role">{{ roleLabel }}</span>
          </div>
          <q-icon name="expand_more" size="16px" class="q-ml-xs" />

          <q-menu class="user-menu" anchor="bottom right" self="top right" :offset="[0, 8]">
            <q-list>
              <q-item>
                <q-item-section avatar>
                  <div class="user-avatar user-avatar--sm">{{ initials }}</div>
                </q-item-section>
                <q-item-section>
                  <q-item-label class="user-menu__name">{{ usuario?.nome }}</q-item-label>
                  <q-item-label caption class="user-menu__email">{{ usuario?.email }}</q-item-label>
                </q-item-section>
              </q-item>

              <q-separator class="q-my-xs" />

              <q-item clickable v-ripple :to="{ name: 'profile' }">
                <q-item-section avatar><q-icon name="manage_accounts" size="18px" /></q-item-section>
                <q-item-section>Preferências</q-item-section>
              </q-item>

              <q-item clickable v-ripple :to="{ name: 'settings' }">
                <q-item-section avatar><q-icon name="settings" size="18px" /></q-item-section>
                <q-item-section>Configurações</q-item-section>
              </q-item>

              <q-separator class="q-my-xs" />

              <q-item clickable v-ripple class="text-negative" @click="handleLogout">
                <q-item-section avatar><q-icon name="logout" size="18px" color="negative" /></q-item-section>
                <q-item-section>Sair da conta</q-item-section>
              </q-item>
            </q-list>
          </q-menu>
        </q-btn>
      </div>
    </q-toolbar>
  </q-header>
</template>

<style scoped lang="scss">
.app-header {
  background: var(--bg-header) !important;
  border-bottom: 1px solid var(--border-subtle) !important;
  box-shadow: var(--shadow-card) !important;
}

.app-header__toolbar {
  height: 64px;
  padding: 0 24px;
  gap: 12px;
  position: relative;
}

.app-header__menu-btn { color: var(--text-muted); }

.app-header__search-wrap {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  width: 500px;
}

.app-header__search {
  display: flex;
  align-items: center;
  gap: 8px;
  background: var(--bg-overlay);
  border: 1px solid var(--border-subtle);
  border-radius: 10px;
  padding: 8px 14px;
  transition: border-color 0.2s, background 0.2s;

  &:focus-within {
    border-color: var(--border-focus);
    background: var(--bg-card-inner);
  }
}

.app-header__search-icon { color: var(--text-muted); flex-shrink: 0; }

.app-header__search-input {
  flex: 1;
  background: none;
  border: none;
  outline: none;
  color: var(--text-primary);
  font-size: 0.84rem;
  font-family: inherit;

  &::placeholder { color: var(--text-muted); }
}

.app-header__search-hint {
  font-size: 0.65rem;
  color: var(--text-muted);
  white-space: nowrap;
  opacity: 0.5;
  flex-shrink: 0;
}

.app-header__spacer { flex: 1; }

.app-header__actions {
  display: flex;
  align-items: center;
  gap: 4px;
}

.app-header__action-btn {
  color: var(--text-muted);
  &:hover { color: var(--text-primary); }
}

.app-header__user {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 6px 12px;
  border-radius: 10px;
  color: var(--text-secondary);
  transition: background 0.15s;

  &:hover { background: var(--bg-overlay) !important; }

  :deep(.q-btn__content) { gap: 16px !important; }
}

.user-avatar {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  background: linear-gradient(135deg, #11d6ff, #9D50FF) !important;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.72rem;
  font-weight: 700;
  color: #04111f !important;
  flex-shrink: 0;

  &--sm { width: 26px; height: 26px; font-size: 0.6rem; }
}

.user-info {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  line-height: 1.2;
}

.user-info__name { font-size: 0.82rem; font-weight: 600; color: var(--text-primary); }
.user-info__role { font-size: 0.68rem; color: var(--text-muted); }

.user-menu {
  background: var(--bg-surface) !important;
  border: 1px solid var(--border-subtle) !important;
  border-radius: 12px !important;
  min-width: 220px;
  padding: 6px !important;

  :deep(.q-item) {
    color: var(--text-secondary);
    border-radius: 6px;
    margin: 0;
    padding: 7px 12px;
    font-size: 0.82rem;
    min-height: unset;
    gap: 0;

    &:hover { background: var(--bg-overlay); }
  }

  :deep(.q-item__section--avatar) {
    min-width: unset;
    width: 20px;
    padding-right: 0;
    margin-right: 8px;
  }

  :deep(.q-item__section--main) { flex: 1; }
  :deep(.q-icon) { font-size: 15px !important; }
  :deep(.q-separator) { background: var(--border-subtle); margin: 3px 0; }
}

.user-menu__name  { font-weight: 600; color: var(--text-primary); font-size: 0.875rem; }
.user-menu__email { color: var(--text-muted) !important; font-size: 0.75rem; }
</style>

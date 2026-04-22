<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from 'stores/auth'
import { useUsersStore } from 'stores/users'
import { formatDate, getInitials } from 'utils/format'
import RoleBadge from 'components/common/RoleBadge.vue'

const route      = useRoute()
const router     = useRouter()
const authStore  = useAuthStore()
const usersStore = useUsersStore()

const query        = computed(() => route.query.q ?? '')
const users        = ref([])
const loadingUsers = ref(false)

const pages = [
  { label: 'Dashboard',     desc: 'Visão geral da conta',               icon: 'dashboard',       name: 'home',     keywords: ['dashboard', 'home', 'inicio', 'início'] },
  { label: 'Usuários',      desc: 'Gerencie os usuários da plataforma', icon: 'people_alt',      name: 'users',    keywords: ['usuarios', 'usuários', 'user', 'pessoas'] },
  { label: 'Preferências',  desc: 'Edite seu perfil e dados pessoais',  icon: 'manage_accounts', name: 'profile',  keywords: ['preferencias', 'preferências', 'perfil', 'profile', 'conta'] },
  { label: 'Configurações', desc: 'Tema, notificações e segurança',     icon: 'settings',        name: 'settings', keywords: ['config', 'configurações', 'configuracoes', 'tema', 'notificações'] },
]

const matchedPages = computed(() => {
  const q = query.value.toLowerCase().trim()
  if (!q) return pages
  return pages.filter(p =>
    p.label.toLowerCase().includes(q) ||
    p.desc.toLowerCase().includes(q) ||
    p.keywords.some(k => k.includes(q))
  )
})

const canSeeUsers = computed(() =>
  ['administrador', 'editor'].includes(authStore.usuario?.role)
)

async function fetchUsers() {
  if (!query.value.trim() || !canSeeUsers.value) return
  loadingUsers.value = true
  try {
    users.value = await usersStore.searchUsers(query.value, 10)
  } catch {
    users.value = []
  } finally {
    loadingUsers.value = false
  }
}

function goToUser(user) {
  router.push({ name: 'users', query: { search: user.email } })
}

watch(query, fetchUsers)
onMounted(fetchUsers)
</script>

<template>
  <q-page class="search-page">
    <div class="page-header">
      <div class="page-header__top">
        <q-btn flat round dense icon="arrow_back" class="back-btn" @click="router.back()" />
        <div>
          <h1 class="page-header__title">
            Resultados para <span class="page-header__query">"{{ query }}"</span>
          </h1>
          <p class="page-header__sub">Páginas e usuários encontrados na plataforma.</p>
        </div>
      </div>
    </div>

    <div class="search-grid">
      <section class="result-section">
        <div class="result-section__header">
          <q-icon name="widgets" size="16px" />
          <span>Páginas</span>
          <span class="result-section__count">{{ matchedPages.length }}</span>
        </div>

        <div v-if="matchedPages.length" class="pages-list">
          <router-link
            v-for="page in matchedPages"
            :key="page.name"
            :to="{ name: page.name }"
            class="page-card"
          >
            <div class="page-card__icon">
              <q-icon :name="page.icon" size="20px" />
            </div>
            <div class="page-card__body">
              <span class="page-card__label">{{ page.label }}</span>
              <span class="page-card__desc">{{ page.desc }}</span>
            </div>
            <q-icon name="arrow_forward" size="16px" class="page-card__arrow" />
          </router-link>
        </div>
        <div v-else class="empty-state">
          <q-icon name="search_off" size="32px" class="empty-state__icon" />
          <span>Nenhuma página encontrada</span>
        </div>
      </section>

      <section v-if="canSeeUsers" class="result-section">
        <div class="result-section__header">
          <q-icon name="people_alt" size="16px" />
          <span>Usuários</span>
          <span v-if="!loadingUsers" class="result-section__count">{{ users.length }}</span>
          <q-spinner v-else size="14px" color="primary" />
        </div>

        <div v-if="loadingUsers" class="users-list">
          <div v-for="i in 3" :key="i" class="user-card user-card--skeleton">
            <div class="user-card__avatar skeleton-box" />
            <div class="user-card__body">
              <div class="skeleton-line" style="width: 140px" />
              <div class="skeleton-line" style="width: 200px; height: 10px; margin-top: 6px" />
            </div>
          </div>
        </div>

        <div v-else-if="users.length" class="users-list">
          <div
            v-for="user in users"
            :key="user.id"
            class="user-card"
            @click="goToUser(user)"
          >
            <div class="user-card__avatar">{{ getInitials(user.nome) }}</div>
            <div class="user-card__body">
              <div class="user-card__name">{{ user.nome }}</div>
              <div class="user-card__email">{{ user.email }}</div>
            </div>
            <div class="user-card__meta">
              <role-badge :role="user.role" />
              <span class="user-card__date">{{ formatDate(user.created_at) }}</span>
            </div>
          </div>
        </div>

        <div v-else class="empty-state">
          <q-icon name="person_off" size="32px" class="empty-state__icon" />
          <span>Nenhum usuário encontrado</span>
        </div>
      </section>
    </div>
  </q-page>
</template>

<style scoped lang="scss">
.search-page {
  padding: 0 36px 32px;
  min-height: 100vh;
  background: var(--bg-page);

  @media (max-width: 768px) { padding: 0 20px 24px; }
}

.page-header { margin-bottom: 32px; }

.page-header__top {
  display: flex;
  align-items: flex-start;
  gap: 12px;
}

.back-btn {
  color: var(--text-muted);
  margin-top: 4px;
  flex-shrink: 0;
}

.page-header__title {
  margin: 0 0 6px;
  font-size: 1.6rem;
  font-weight: 800;
  color: var(--text-primary);
}

.page-header__query { color: var(--color-primary); }

.page-header__sub {
  margin: 0;
  font-size: 0.875rem;
  color: var(--text-muted);
}

.search-grid {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.result-section {
  background: var(--bg-card);
  border: 1px solid var(--border-subtle);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: var(--shadow-card);
}

.result-section__header {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 16px 20px;
  border-bottom: 1px solid var(--border-subtle);
  font-size: 0.82rem;
  font-weight: 700;
  color: var(--text-secondary);
  background: var(--bg-card-inner);
}

.result-section__count {
  margin-left: auto;
  font-size: 0.72rem;
  background: var(--bg-overlay);
  border: 1px solid var(--border-subtle);
  border-radius: 999px;
  padding: 1px 8px;
  color: var(--text-muted);
}

.pages-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 1px;
  background: var(--border-subtle);
}

.page-card {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 18px 20px;
  background: var(--bg-card);
  text-decoration: none;
  transition: background 0.15s;

  &:hover {
    background: var(--bg-card-inner);
    .page-card__arrow { opacity: 1; transform: translateX(2px); }
  }
}

.page-card__icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  background: var(--bg-card-inner);
  border: 1px solid var(--border-subtle);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--color-primary);
  flex-shrink: 0;
}

.page-card__body {
  display: flex;
  flex-direction: column;
  gap: 3px;
  flex: 1;
  min-width: 0;
}

.page-card__label {
  font-size: 0.88rem;
  font-weight: 700;
  color: var(--text-primary);
}

.page-card__desc {
  font-size: 0.75rem;
  color: var(--text-muted);
}

.page-card__arrow {
  color: var(--text-muted);
  opacity: 0;
  transition: opacity 0.15s, transform 0.15s;
  flex-shrink: 0;
}

.users-list {
  display: flex;
  flex-direction: column;
}

.user-card {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 14px 20px;
  cursor: pointer;
  border-bottom: 1px solid var(--border-subtle);
  transition: background 0.15s;

  &:last-child { border-bottom: none; }
  &:hover { background: var(--bg-card-inner); }
  &--skeleton { pointer-events: none; }
}

.user-card__avatar {
  width: 38px;
  height: 38px;
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

.user-card__body { flex: 1; min-width: 0; }

.user-card__name {
  font-size: 0.88rem;
  font-weight: 600;
  color: var(--text-primary);
}

.user-card__email {
  font-size: 0.75rem;
  color: var(--text-muted);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.user-card__meta {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 4px;
  flex-shrink: 0;
}

.user-card__date {
  font-size: 0.7rem;
  color: var(--text-muted);
}

.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
  padding: 40px;
  color: var(--text-muted);
  font-size: 0.85rem;
}

.empty-state__icon { opacity: 0.3; }

.skeleton-box {
  background: var(--bg-card-inner) !important;
  animation: pulse 1.4s ease-in-out infinite;
}

.skeleton-line {
  height: 12px;
  border-radius: 6px;
  background: var(--bg-card-inner);
  animation: pulse 1.4s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50%       { opacity: 0.4; }
}
</style>

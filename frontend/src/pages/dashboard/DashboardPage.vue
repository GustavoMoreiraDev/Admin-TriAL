<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from 'stores/auth'
import { formatDate, formatPhone, formatDias } from 'utils/format'
import StatCard from 'components/dashboard/StatCard.vue'
import UserInfoCard from 'components/dashboard/UserInfoCard.vue'

const router    = useRouter()
const authStore = useAuthStore()

const loading = ref(true)
const usuario = computed(() => authStore.usuario)

const statusColor = computed(() => usuario.value?.status === 'ativo' ? 'teal' : 'orange')
const statusLabel = computed(() => usuario.value?.status?.toUpperCase() ?? '-')

const diasParaExpirar = computed(() => {
  if (!usuario.value?.data_expiracao) return null
  const diff = Math.ceil((new Date(usuario.value.data_expiracao) - new Date()) / (1000 * 60 * 60 * 24))
  return diff
})

const statCards = computed(() => [
  {
    label:   'Status da Conta',
    value:   statusLabel.value,
    icon:    'verified_user',
    color:   statusColor.value,
    trend:   usuario.value?.status === 'ativo' ? 'Conta ativa' : 'Conta expirada',
    trendUp: usuario.value?.status === 'ativo',
  },
  {
    label:   'Dias até Expirar',
    value:   formatDias(diasParaExpirar.value),
    icon:    'schedule',
    color:   diasParaExpirar.value > 3 ? 'cyan' : 'orange',
    trend:   `Expira em ${formatDate(usuario.value?.data_expiracao)}`,
    trendUp: diasParaExpirar.value > 3,
  },
  {
    label:   'Membro desde',
    value:   formatDate(usuario.value?.created_at),
    icon:    'person_check',
    color:   'purple',
    trend:   'Cadastro concluído',
    trendUp: true,
  },
])

const infoCards = computed(() => [
  { icon: 'alternate_email', label: 'E-mail',    value: usuario.value?.email                        ?? '-', color: 'blue'   },
  { icon: 'phone',           label: 'Telefone',  value: formatPhone(usuario.value?.telefone)        ?? '-', color: 'teal'   },
  { icon: 'cake',            label: 'Nascimento',value: formatDate(usuario.value?.data_nascimento),         color: 'pink'   },
  { icon: 'schedule',        label: 'Expiração', value: formatDate(usuario.value?.data_expiracao),          color: 'orange' },
])

onMounted(async () => {
  try {
    await authStore.fetchMe()
  } catch {
    router.push({ name: 'login' })
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <q-page class="dashboard-page">
    <div v-if="loading" class="dashboard-page__loading">
      <q-spinner-orbit color="primary" size="48px" />
    </div>

    <template v-else-if="usuario">
      <div class="page-header">
        <div>
          <h1 class="page-header__title">
            Olá, <span class="page-header__name">{{ usuario.nome?.split(' ')[0] }}</span>!
          </h1>
          <p class="page-header__sub">Visão geral da sua conta e atividade recente.</p>
        </div>
        <div class="page-header__meta">
          <span class="page-header__date">{{ new Date().toLocaleDateString('pt-BR', { weekday: 'long', day: 'numeric', month: 'long' }) }}</span>
        </div>
      </div>

      <div class="stat-grid">
        <stat-card v-for="card in statCards" :key="card.label" v-bind="card" />
      </div>

      <div class="lower-grid">
        <section class="section-card">
          <div class="section-card__header">
            <h2 class="section-card__title">Dados da Conta</h2>
            <router-link :to="{ name: 'profile' }" class="section-card__link">
              Editar <q-icon name="arrow_forward" size="14px" />
            </router-link>
          </div>
          <div class="info-grid">
            <user-info-card v-for="card in infoCards" :key="card.label" v-bind="card" />
          </div>
        </section>

        <section class="section-card">
          <div class="section-card__header">
            <h2 class="section-card__title">Segurança</h2>
          </div>
          <div class="security-list">
            <div class="security-item">
              <div class="security-item__icon security-item__icon--ok">
                <q-icon name="lock" size="16px" />
              </div>
              <div class="security-item__body">
                <span class="security-item__label">Senha protegida</span>
                <span class="security-item__desc">Criptografada com bcrypt</span>
              </div>
              <q-icon name="check_circle" size="18px" color="positive" />
            </div>

            <div class="security-item">
              <div class="security-item__icon security-item__icon--ok">
                <q-icon name="verified_user" size="16px" />
              </div>
              <div class="security-item__body">
                <span class="security-item__label">Sessão JWT ativa</span>
                <span class="security-item__desc">Token de acesso seguro</span>
              </div>
              <q-icon name="check_circle" size="18px" color="positive" />
            </div>

            <div class="security-item">
              <div class="security-item__icon security-item__icon--warn">
                <q-icon name="phone_android" size="16px" />
              </div>
              <div class="security-item__body">
                <span class="security-item__label">Autenticação 2FA</span>
                <span class="security-item__desc">Disponível em breve</span>
              </div>
              <q-badge outline color="warning" label="Em breve" style="font-size: 0.65rem;" />
            </div>
          </div>
        </section>
      </div>
    </template>
  </q-page>
</template>

<style scoped lang="scss">
.dashboard-page {
  padding: 32px 36px;
  min-height: 100vh;
  background: var(--bg-page);

  @media (max-width: 768px) { padding: 24px 20px; }
}

.dashboard-page__loading {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 60vh;
}

.page-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  margin-bottom: 32px;
  gap: 16px;
  flex-wrap: wrap;
}

.page-header__title {
  margin: 0 0 6px;
  font-size: 1.75rem;
  font-weight: 800;
  color: var(--text-primary);
  line-height: 1.1;
}

.page-header__name  { color: var(--color-primary); }

.page-header__sub {
  margin: 0;
  font-size: 0.875rem;
  color: var(--text-muted);
}

.page-header__meta { text-align: right; }

.page-header__date {
  font-size: 0.8rem;
  color: var(--text-muted);
  text-transform: capitalize;
}

.stat-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
  margin-bottom: 24px;

  @media (max-width: 900px) { grid-template-columns: 1fr 1fr; }
  @media (max-width: 560px) { grid-template-columns: 1fr; }
}

.lower-grid {
  display: grid;
  grid-template-columns: 1fr 380px;
  gap: 20px;
  align-items: stretch;

  @media (max-width: 1100px) { grid-template-columns: 1fr; }
}

.section-card {
  background: var(--bg-card);
  border: 1px solid var(--border-subtle);
  border-radius: 16px;
  padding: 24px;
  box-shadow: var(--shadow-card);
  display: flex;
  flex-direction: column;
}

.section-card__header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
}

.section-card__title {
  margin: 0;
  font-size: 1rem;
  font-weight: 700;
  color: var(--text-primary);
}

.section-card__link {
  display: flex;
  align-items: center;
  gap: 4px;
  font-size: 0.78rem;
  color: var(--color-primary);
  text-decoration: none;
  font-weight: 600;

  &:hover { opacity: 0.8; }
}

.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;

  @media (max-width: 560px) { grid-template-columns: 1fr; }
}

.security-list {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.security-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 14px;
  background: var(--bg-card-inner);
  border: 1px solid var(--border-subtle);
  border-radius: 10px;
  box-shadow: var(--shadow-card);
}

.security-item__icon {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;

  &--ok   { background: rgba(0, 245, 212, 0.12); color: #00F5D4; }
  &--warn { background: rgba(255, 176, 32, 0.12); color: #FFB020; }
}

.security-item__body {
  display: flex;
  flex-direction: column;
  flex: 1;
  gap: 2px;
}

.security-item__label {
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--text-secondary);
}

.security-item__desc {
  font-size: 0.72rem;
  color: var(--text-muted);
}
</style>

<script setup>
import { reactive, computed, onMounted, ref } from 'vue'
import { useQuasar } from 'quasar'
import { useAuthStore } from 'stores/auth'

const $q = useQuasar()
const authStore = useAuthStore()

const loading = ref(false)
const showPassword = ref(false)
const usuario = computed(() => authStore.usuario)

const form = reactive({
  nome: '',
  telefone: '',
  data_nascimento: '',
})

const displayDate = computed(() => {
  if (!form.data_nascimento) return ''
  const date = new Date(form.data_nascimento)
  if (isNaN(date.getTime())) return ''
  return date.toLocaleDateString('pt-BR', { timeZone: 'UTC' })
})

const maxYearMonth = computed(() => {
  const now = new Date()
  return `${now.getFullYear()}/${String(now.getMonth() + 1).padStart(2, '0')}`
})

function formatPhone(value) {
  if (!value) return ''
  return value.replace(/\D/g, '')
}

async function handleSave() {
  loading.value = true
  try {
    const payload = {
      nome:            form.nome,
      telefone:        form.telefone,
      data_nascimento: form.data_nascimento,
    }
    await authStore.updateProfile(payload)
    $q.notify({ type: 'positive', message: 'Preferências salvas com sucesso!' })
  } catch (err) {
    const msg = err.response?.data?.message ?? 'Erro ao salvar.'
    $q.notify({ type: 'negative', message: msg })
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  if (usuario.value) {
    form.nome = usuario.value.nome ?? ''
    form.telefone = formatPhone(usuario.value.telefone)
    const raw = usuario.value.data_nascimento
    if (raw) {
      form.data_nascimento = raw.includes('T') ? raw.split('T')[0] : raw
    }
  }
})
</script>

<template>
  <q-page class="profile-page">
    
    <div class="page-header">
      <div>
        <h1 class="page-header__title">Preferências</h1>
        <p class="page-header__sub">Gerencie suas informações pessoais e preferências de conta.</p>
      </div>
    </div>

    <div class="profile-grid">
      <section class="section-card profile-avatar-card">
        <div class="avatar-circle">
          {{ usuario?.nome?.split(' ').slice(0, 2).map(n => n[0]).join('').toUpperCase() }}
        </div>
        <div class="avatar-name">{{ usuario?.nome }}</div>
        <div class="avatar-email">{{ usuario?.email }}</div>

        <q-badge
          class="avatar-status"
          :color="usuario?.status === 'ativo' ? 'positive' : 'negative'"
        >
          {{ usuario?.status?.toUpperCase() }}
        </q-badge>

        <q-btn
          outline color="primary" size="sm"
          label="Alterar foto"
          icon="photo_camera"
          class="q-mt-md full-width"
          disable
        >
          <q-tooltip>Disponível em breve</q-tooltip>
        </q-btn>
      </section>

      <section class="section-card profile-form-card">
        <h2 class="section-title">Informações Pessoais</h2>

        <q-form class="profile-form" @submit.prevent="handleSave">
          <div class="form-group">
            <label class="form-label">NOME COMPLETO</label>
            <q-input
              v-model="form.nome"
              outlined dense :dark="$q.dark.isActive" hide-bottom-space
              placeholder="Seu nome completo"
              class="form-input"
            >
              <template #prepend><q-icon name="person_outline" size="18px" /></template>
            </q-input>
          </div>

          <div class="form-group">
            <label class="form-label">TELEFONE</label>
            <q-input
              v-model="form.telefone"
              outlined dense :dark="$q.dark.isActive" hide-bottom-space
              mask="(##) # ####-####"
              placeholder="(11) 9 9999-9999"
              unmasked-value
              class="form-input"
            >
              <template #prepend><q-icon name="phone" size="18px" /></template>
            </q-input>
          </div>

          <div class="form-group">
            <label class="form-label">DATA DE NASCIMENTO</label>
            <q-input
              :model-value="displayDate"
              outlined dense hide-bottom-space readonly
              placeholder="dd/mm/aaaa"
              class="form-input"
            >
              <template #prepend><q-icon name="cake" size="18px" /></template>
              <template #append>
                <q-icon name="event" size="16px" class="cursor-pointer">
                  <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                    <q-date
                      v-model="form.data_nascimento"
                      mask="YYYY-MM-DD"
                      :navigation-max-year-month="maxYearMonth"
                    >
                      <div class="row items-center justify-end">
                        <q-btn v-close-popup label="OK" color="primary" flat />
                      </div>
                    </q-date>
                  </q-popup-proxy>
                </q-icon>
              </template>
            </q-input>
          </div>

          <q-btn
            type="submit"
            label="SALVAR PREFERÊNCIAS"
            icon-right="save"
            class="save-btn full-width"
            :loading="loading"
            unelevated size="md"
            padding="12px 20px"
          />
        </q-form>
      </section>
    </div>
  </q-page>
</template>

<style scoped lang="scss">
.profile-page {
  padding: 0 36px 32px;
  min-height: 100vh;
  background: var(--bg-page);

  @media (max-width: 768px) {
    padding: 0 20px 24px;
  }
}

.page-header {
  margin-bottom: 32px;
}

.page-header__title {
  margin: 0 0 6px;
  font-size: 1.75rem;
  font-weight: 800;
  color: var(--text-primary);
}

.page-header__sub {
  margin: 0;
  font-size: 0.875rem;
  color: var(--text-muted);
}

.profile-grid {
  display: grid;
  grid-template-columns: 260px 1fr;
  gap: 20px;
  align-items: stretch;

  @media (max-width: 900px) {
    grid-template-columns: 1fr;
  }
}

.section-card {
  background: var(--bg-card);
  border: 1px solid var(--border-subtle);
  border-radius: 16px;
  padding: 24px;
  box-shadow: var(--shadow-card);
}

.profile-avatar-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.avatar-circle {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: linear-gradient(135deg, #11d6ff, #9D50FF);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.6rem;
  font-weight: 800;
  color: #04111f;
  margin-bottom: 14px;
}

.avatar-name {
  font-size: 1rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 4px;
}

.avatar-email {
  font-size: 0.78rem;
  color: var(--text-muted);
  margin-bottom: 12px;
  word-break: break-all;
}

.avatar-status {
  font-size: 0.65rem;
  padding: 3px 10px;
}

.section-title {
  margin: 0 0 24px;
  font-size: 1rem;
  font-weight: 700;
  color: var(--text-primary);
}

.profile-form {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.form-label {
  font-size: 0.66rem;
  font-weight: 700;
  letter-spacing: 0.16em;
  color: var(--text-label);
}

.form-input {
  :deep(.q-field__bottom) { display: none !important; }
  :deep(.q-field__control) {
    min-height: 42px;
    background: var(--bg-field) !important;
    border-radius: 12px !important;
  }
  :deep(.q-field__control::before) {
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    border-radius: 12px !important;
    transition: border-color 0.2s ease !important;
  }
  :deep(.q-field__control::after) { display: none !important; }
  :deep(.q-field--focused .q-field__control::before) { border-color: #11d6ff !important; }
  :deep(.q-field__native), :deep(input) { color: var(--text-primary) !important; }
  :deep(.q-field__prepend), :deep(.q-field__append) { color: var(--text-muted); }
}

.date-wrapper {
  position: relative;
  cursor: pointer;
}

.save-btn {
  border-radius: 12px;
  font-weight: 800;
  letter-spacing: 0.06em;
  background: linear-gradient(90deg, #56d7ff, #15e0c5) !important;
  color: #04111f !important;
  box-shadow: 0 0 18px rgba(39, 221, 255, 0.22);
  margin-top: 8px;
}
</style>

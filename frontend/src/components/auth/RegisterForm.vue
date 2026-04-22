<script setup>
import { reactive, ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useQuasar } from 'quasar'
import { useAuthStore } from 'stores/auth'

const emit = defineEmits(['switch-to-login'])

const $q = useQuasar()
const router = useRouter()
const authStore = useAuthStore()

const loading = ref(false)
const showPassword = ref(false)

const form = reactive({
  nome: '',
  email: '',
  senha: '',
  senha_confirmation: '',
  telefone: '',
  data_nascimento: ''
})

const errors = reactive({
  nome: '',
  email: '',
  senha: '',
  telefone: '',
  data_nascimento: ''
})

const displayDate = computed(() => {
  if (!form.data_nascimento) return ''
  const [y, m, d] = form.data_nascimento.split('-')
  return `${d}/${m}/${y}`
})

const maxYearMonth = computed(() => {
  const now = new Date()
  return `${now.getFullYear()}/${String(now.getMonth() + 1).padStart(2, '0')}`
})

async function handleRegister() {
  Object.keys(errors).forEach(k => (errors[k] = ''))
  loading.value = true

  try {
    await authStore.register(form)
    $q.notify({ type: 'positive', message: 'Conta criada com sucesso!' })
    router.push({ name: 'home' })
  } catch (err) {
    const data = err.response?.data

    if (data?.errors) {
      Object.keys(data.errors).forEach(k => {
        if (k in errors) errors[k] = data.errors[k]?.[0] ?? ''
      })
      return
    }

    $q.notify({
      type: 'negative',
      message: data?.message ?? 'Erro ao criar conta.',
      icon: 'warning'
    })
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="register-card">
    <div class="register-card__header">
      <h2 class="register-card__title">Criar Conta</h2>
      <p class="register-card__subtitle">
        Preencha seus dados para começar.
      </p>
    </div>

    <q-form class="register-form" @submit.prevent="handleRegister">

      <div class="form-group">
        <label class="form-label">NOME COMPLETO</label>
        <q-input
          v-model="form.nome"
          outlined dense dark hide-bottom-space
          placeholder="João Silva"
          :error="!!errors.nome"
          class="form-input"
          :class="{ 'form-input--error': !!errors.nome }"
        >
          <template #prepend>
            <q-icon name="person_outline" size="18px" />
          </template>
        </q-input>
        <span class="form-error">{{ errors.nome || '&nbsp;' }}</span>
      </div>

      <div class="form-group">
        <label class="form-label">E-MAIL</label>
        <q-input
          v-model="form.email"
          outlined dense dark hide-bottom-space
          type="email"
          placeholder="exemplo@empresa.com"
          :error="!!errors.email"
          class="form-input"
          :class="{ 'form-input--error': !!errors.email }"
        >
          <template #prepend>
            <q-icon name="alternate_email" size="18px" />
          </template>
        </q-input>
        <span class="form-error">{{ errors.email || '&nbsp;' }}</span>
      </div>

      <div class="form-row-cols">
        <div class="form-group">
          <label class="form-label">SENHA</label>
          <q-input
            v-model="form.senha"
            outlined dense dark hide-bottom-space
            :type="showPassword ? 'text' : 'password'"
            placeholder="••••••••"
            :error="!!errors.senha"
            class="form-input"
            :class="{ 'form-input--error': !!errors.senha }"
          >
            <template #prepend>
              <q-icon name="lock_outline" size="18px" />
            </template>
            <template #append>
              <q-icon
                :name="showPassword ? 'visibility_off' : 'visibility'"
                size="16px" class="cursor-pointer"
                @click="showPassword = !showPassword"
              />
            </template>
          </q-input>
          <span class="form-error">{{ errors.senha || '&nbsp;' }}</span>
        </div>

        <div class="form-group">
          <label class="form-label">CONFIRMAR</label>
          <q-input
            v-model="form.senha_confirmation"
            outlined dense dark hide-bottom-space
            :type="showPassword ? 'text' : 'password'"
            placeholder="••••••••"
            class="form-input"
          >
            <template #prepend>
              <q-icon name="lock_reset" size="18px" />
            </template>
          </q-input>
          <span class="form-error">&nbsp;</span>
        </div>
      </div>

      <div class="form-row-cols">
        <div class="form-group">
          <label class="form-label">TELEFONE</label>
          <q-input
            v-model="form.telefone"
            outlined dense dark hide-bottom-space
            mask="(##) # ####-####"
            placeholder="(11) 9 9999-9999"
            unmasked-value
            :error="!!errors.telefone"
            class="form-input"
            :class="{ 'form-input--error': !!errors.telefone }"
          >
            <template #prepend>
              <q-icon name="phone" size="18px" />
            </template>
          </q-input>
          <span class="form-error">{{ errors.telefone || '&nbsp;' }}</span>
        </div>

        <div class="form-group">
          <label class="form-label">NASCIMENTO</label>
          <div class="date-wrapper">
            <q-input
              :model-value="displayDate"
              outlined dense dark hide-bottom-space readonly
              placeholder="dd/mm/aaaa"
              :error="!!errors.data_nascimento"
              class="form-input"
              :class="{ 'form-input--error': !!errors.data_nascimento }"
            >
              <template #prepend>
                <q-icon name="cake" size="18px" />
              </template>
              <template #append>
                <q-icon name="event" size="16px" />
              </template>
            </q-input>
            <q-popup-proxy cover transition-show="scale" transition-hide="scale">
              <q-date
                v-model="form.data_nascimento"
                mask="YYYY-MM-DD"
                dark
                :navigation-max-year-month="maxYearMonth"
              >
                <div class="row items-center justify-end">
                  <q-btn v-close-popup label="OK" color="primary" flat />
                </div>
              </q-date>
            </q-popup-proxy>
          </div>
          <span class="form-error">{{ errors.data_nascimento || '&nbsp;' }}</span>
        </div>
      </div>

      <q-btn
        type="submit"
        label="CRIAR CONTA"
        icon-right="arrow_forward"
        class="register-button full-width"
        :loading="loading"
        unelevated size="md"
        padding="14px 20px"
      />
    </q-form>

    <p class="register-card__login">
      Já tem conta?
      <button class="switch-link" type="button" @click="emit('switch-to-login')">
        Entrar
      </button>
    </p>
  </div>
</template>

<style scoped lang="scss">
.register-card {
  width: 100%;
  max-width: 550px;
  padding: 32px 28px 24px;
  border-radius: 20px;
  background: linear-gradient(180deg, rgba(23, 31, 49, 0.92), rgba(16, 22, 38, 0.94));
  border: 1px solid rgba(255, 255, 255, 0.06);
  box-shadow:
    0 18px 40px rgba(0, 0, 0, 0.35),
    0 0 40px rgba(17, 214, 255, 0.05);

  &__header { margin-bottom: 20px; }

  &__title {
    margin: 0 0 6px;
    font-size: 1.8rem;
    font-weight: 800;
    color: #fff;
  }

  &__subtitle {
    margin: 0;
    font-size: 0.9rem;
    color: rgba(196, 210, 231, 0.7);
  }

  &__login {
    margin: 16px 0 0;
    text-align: center;
    font-size: 0.88rem;
    color: rgba(196, 210, 231, 0.72);
  }
}

.register-form {
  display: flex;
  flex-direction: column;
  gap: 0;
}

.date-wrapper {
  position: relative;
  cursor: pointer;
}

.form-row-cols {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
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
  color: rgba(196, 210, 231, 0.82);
}

.form-input {
  :deep(.q-field__bottom) { display: none !important; }

  :deep(.q-field__control) {
    display: flex;
    min-height: 42px;
    align-items: center;
    background: rgba(0, 0, 0, 0.28) !important;
    border-radius: 12px !important;
  }

  :deep(.q-field__control::before) {
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    border-radius: 12px !important;
    transition: border-color 0.2s ease !important;
  }

  :deep(.q-field__control::after) { display: none !important; }

  :deep(.q-field--focused .q-field__control::before) {
    border-color: #11d6ff !important;
  }

  &.form-input--error {
    :deep(.q-field__control::before),
    :deep(.q-field--focused .q-field__control::before) {
      border-color: #ff4d6a !important;
    }
  }

  :deep(.q-field__native),
  :deep(input) { color: #fff !important; }

  :deep(.q-field__prepend),
  :deep(.q-field__append) { color: rgba(196, 210, 231, 0.7); }
}

.form-error {
  display: block;
  height: 16px;
  font-size: 0.72rem;
  font-weight: 500;
  color: #ff4d6a;
  padding-left: 4px;
  line-height: 16px;
}

.register-button {
  border-radius: 12px;
  font-weight: 800;
  letter-spacing: 0.06em;
  background: linear-gradient(90deg, #56d7ff, #15e0c5) !important;
  color: #04111f !important;
  box-shadow: 0 0 18px rgba(39, 221, 255, 0.22);
  margin-top: 4px;
}

.switch-link {
  background: none;
  border: none;
  padding: 0;
  margin-left: 4px;
  cursor: pointer;
  color: #11d6ff;
  font-weight: 700;
  font-size: 0.88rem;
  font-family: inherit;

  &:hover { opacity: 0.8; }
}

@media (max-width: 640px) {
  .register-card {
    max-width: 100%;
    padding: 24px 18px 20px;
  }

  .form-row-cols {
    grid-template-columns: 1fr;
  }
}
</style>

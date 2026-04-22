<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useQuasar } from 'quasar'
import { useAuthStore } from 'stores/auth'

const emit = defineEmits(['switch-to-register'])

const $q = useQuasar()
const router = useRouter()
const authStore = useAuthStore()

const loading = ref(false)
const showPassword = ref(false)
const lembrar = ref(false)

const form = reactive({
  email: '',
  senha: ''
})

const errors = reactive({
  email: '',
  senha: ''
})

async function handleLogin() {
  Object.assign(errors, { email: '', senha: '' })
  loading.value = true

  try {
    await authStore.login(form)

    $q.notify({
      type: 'positive',
      message: 'Login realizado com sucesso!'
    })

    router.push({ name: 'home' })
  } catch (err) {
    const data = err.response?.data

    if (data?.errors) {
      errors.email = data.errors.email?.[0] ?? ''
      errors.senha = data.errors.senha?.[0] ?? ''
      return
    }

    $q.notify({
      type: 'negative',
      message: data?.message ?? 'Credenciais inválidas.',
      icon: 'warning'
    })
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="login-card">
    <div class="login-card__header">
      <h2 class="login-card__title">Entrar na Conta</h2>
      <p class="login-card__subtitle">
        Insira suas credenciais para continuar.
      </p>
    </div>

    <q-form class="login-form" @submit.prevent="handleLogin">
      <div class="form-group">
        <label class="form-label">E-MAIL</label>

        <q-input
          v-model="form.email"
          outlined
          dense
          hide-bottom-space
          type="email"
          placeholder="exemplo@empresa.com"
          :error="!!errors.email"
          dark
          class="form-input"
          :class="{ 'form-input--error': !!errors.email }"
        >
          <template #prepend>
            <q-icon name="alternate_email" size="18px" />
          </template>
        </q-input>
        <span class="form-error">{{ errors.email || '&nbsp;' }}</span>
      </div>

      <div class="form-group">
        <div class="form-row">
          <label class="form-label">SENHA</label>
          <a href="#" class="form-link">Esqueci minha senha</a>
        </div>

        <q-input
          v-model="form.senha"
          outlined
          dense
          hide-bottom-space
          :type="showPassword ? 'text' : 'password'"
          placeholder="••••••••"
          :error="!!errors.senha"
          dark
          class="form-input"
          :class="{ 'form-input--error': !!errors.senha }"
        >
          <template #prepend>
            <q-icon name="lock_outline" size="18px" />
          </template>

          <template #append>
            <q-icon
              :name="showPassword ? 'visibility_off' : 'visibility'"
              size="18px"
              class="cursor-pointer"
              @click="showPassword = !showPassword"
            />
          </template>
        </q-input>
        <span class="form-error">{{ errors.senha || '&nbsp;' }}</span>
      </div>

      <q-checkbox
        v-model="lembrar"
        label="Lembrar deste dispositivo"
        dark
        color="primary"
        class="remember-check"
      />

      <q-btn
        type="submit"
        label="ENTRAR"
        icon-right="arrow_forward"
        class="login-button full-width q-mt-md"
        :loading="loading"
        unelevated
        size="md"
        padding="14px 20px"
      />
    </q-form>

    <p class="login-card__register">
      Não tem conta?
      <button class="switch-link" type="button" @click="emit('switch-to-register')">Cadastre-se</button>
    </p>
  </div>
</template>

<style scoped lang="scss">
.login-card {
  width: 100%;
  max-width: 420px;
  padding: 36px 28px 28px;
  border-radius: 20px;
  background: linear-gradient(180deg, rgba(23, 31, 49, 0.92), rgba(16, 22, 38, 0.94));
  border: 1px solid rgba(255, 255, 255, 0.06);
  box-shadow:
    0 18px 40px rgba(0, 0, 0, 0.35),
    0 0 40px rgba(17, 214, 255, 0.05);
}

.login-card__header {
  margin-bottom: 24px;
}

.login-card__title {
  margin: 0 0 8px;
  font-size: 2rem;
  font-weight: 800;
  color: #fff;
}

.login-card__subtitle {
  margin: 0;
  font-size: 0.92rem;
  color: rgba(196, 210, 231, 0.7);
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 0;
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

.form-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.form-link {
  font-size: 0.74rem;
  font-weight: 600;
  color: #a971ff;
  text-decoration: none;
}

.form-input {
  :deep(.q-field__bottom) {
    display: none !important;
  }

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

  :deep(.q-field__control::after) {
    display: none !important;
  }

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
  :deep(input) {
    color: #fff !important;
  }

  :deep(.q-field__prepend),
  :deep(.q-field__append) {
    color: rgba(196, 210, 231, 0.7);
  }
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

.remember-check {
  margin-top: 6px;

  :deep(.q-checkbox__label) {
    font-size: 0.84rem;
    color: rgba(196, 210, 231, 0.76);
  }
}

.login-button {
  border-radius: 12px;
  font-weight: 800;
  letter-spacing: 0.06em;
  background: linear-gradient(90deg, #56d7ff, #15e0c5) !important;
  color: #04111f !important;
  box-shadow: 0 0 18px rgba(39, 221, 255, 0.22);
}

.login-card__register {
  margin: 18px 0 0;
  text-align: center;
  font-size: 0.88rem;
  color: rgba(196, 210, 231, 0.72);
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
  .login-card {
    max-width: 100%;
    padding: 28px 20px 22px;
  }
}
</style>
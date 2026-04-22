<script setup>
import { reactive, ref, watch, computed } from 'vue'
import { useQuasar } from 'quasar'
import RoleBadge from 'components/common/RoleBadge.vue'

const props = defineProps({
  modelValue:  { type: Boolean, required: true },
  editingUser: { type: Object, default: null },
})

const emit = defineEmits(['update:modelValue', 'saved'])

const $q     = useQuasar()
const isEdit = computed(() => !!props.editingUser)

const defaultForm = () => ({
  nome:            '',
  email:           '',
  senha:           '',
  telefone:        '',
  data_nascimento: '',
  role:            'consultor',
  data_expiracao:  '',
  status:          'ativo',
})

const form      = reactive(defaultForm())
const errors    = reactive({})
const loading   = ref(false)
const showSenha = ref(false)

const roleOptions = [
  { label: 'Administrador', value: 'administrador' },
  { label: 'Editor',        value: 'editor'        },
  { label: 'Consultor',     value: 'consultor'     },
]

const statusOptions = [
  { label: 'Ativo',    value: 'ativo'    },
  { label: 'Expirado', value: 'expirado' },
]

watch(() => props.modelValue, (open) => {
  if (!open) return
  Object.assign(form, defaultForm())
  Object.keys(errors).forEach(k => delete errors[k])
  showSenha.value = false

  if (props.editingUser) {
    Object.assign(form, {
      nome:            props.editingUser.nome            ?? '',
      email:           props.editingUser.email           ?? '',
      senha:           '',
      telefone:        props.editingUser.telefone        ?? '',
      data_nascimento: props.editingUser.data_nascimento ?? '',
      role:            props.editingUser.role            ?? 'consultor',
      data_expiracao:  props.editingUser.data_expiracao  ?? '',
      status:          props.editingUser.status          ?? 'ativo',
    })
  }
})

const displayNascimento = computed(() => {
  if (!form.data_nascimento) return ''
  const d = new Date(form.data_nascimento)
  return isNaN(d) ? '' : d.toLocaleDateString('pt-BR', { timeZone: 'UTC' })
})

const displayExpiracao = computed(() => {
  if (!form.data_expiracao) return ''
  const d = new Date(form.data_expiracao)
  return isNaN(d) ? '' : d.toLocaleDateString('pt-BR', { timeZone: 'UTC' })
})

const maxYearMonth = computed(() => {
  const n = new Date()
  return `${n.getFullYear()}/${String(n.getMonth() + 1).padStart(2, '0')}`
})

function close() {
  emit('update:modelValue', false)
}

async function submit() {
  Object.keys(errors).forEach(k => delete errors[k])
  loading.value = true

  const payload = { ...form }
  if (isEdit.value && !payload.senha) delete payload.senha

  try {
    emit('saved', { payload, isEdit: isEdit.value, id: props.editingUser?.id })
    close()
  } catch (err) {
    const data = err.response?.data
    if (data?.errors) {
      Object.assign(errors, data.errors)
    } else {
      $q.notify({ type: 'negative', message: data?.message ?? 'Erro ao salvar usuário.' })
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <q-dialog :model-value="modelValue" persistent @update:model-value="$emit('update:modelValue', $event)">
    <q-card class="user-dialog">
      <div class="dialog-header">
        <div class="dialog-header__info">
          <q-icon :name="isEdit ? 'edit' : 'person_add'" size="22px" color="primary" />
          <span class="dialog-header__title">{{ isEdit ? 'Editar Usuário' : 'Novo Usuário' }}</span>
        </div>
        <q-btn flat round dense icon="close" @click="close" />
      </div>

      <q-separator />

      <q-card-section class="dialog-body">
        <q-form @submit.prevent="submit" class="dialog-form">
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">NOME COMPLETO *</label>
              <q-input
                v-model="form.nome"
                outlined dense
                placeholder="Nome completo"
                :error="!!errors.nome" :error-message="errors.nome?.[0]"
              >
                <template #prepend><q-icon name="person_outline" size="16px" /></template>
              </q-input>
            </div>

            <div class="form-group">
              <label class="form-label">E-MAIL *</label>
              <q-input
                v-model="form.email"
                outlined dense
                type="email" placeholder="email@exemplo.com"
                :error="!!errors.email" :error-message="errors.email?.[0]"
              >
                <template #prepend><q-icon name="alternate_email" size="16px" /></template>
              </q-input>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">{{ isEdit ? 'NOVA SENHA (deixe vazio para manter)' : 'SENHA *' }}</label>
              <q-input
                v-model="form.senha"
                outlined dense
                :type="showSenha ? 'text' : 'password'"
                placeholder="Mínimo 6 caracteres"
                :error="!!errors.senha" :error-message="errors.senha?.[0]"
              >
                <template #prepend><q-icon name="lock_outline" size="16px" /></template>
                <template #append>
                  <q-icon
                    :name="showSenha ? 'visibility_off' : 'visibility'"
                    class="cursor-pointer" size="16px"
                    @click="showSenha = !showSenha"
                  />
                </template>
              </q-input>
            </div>

            <div class="form-group">
              <label class="form-label">TELEFONE *</label>
              <q-input
                v-model="form.telefone"
                outlined dense
                mask="(##) # ####-####" unmasked-value
                placeholder="(11) 9 9999-9999"
                :error="!!errors.telefone" :error-message="errors.telefone?.[0]"
              >
                <template #prepend><q-icon name="phone" size="16px" /></template>
              </q-input>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">DATA DE NASCIMENTO *</label>
              <q-input
                :model-value="displayNascimento"
                outlined dense
                readonly placeholder="dd/mm/aaaa"
                :error="!!errors.data_nascimento" :error-message="errors.data_nascimento?.[0]"
              >
                <template #prepend><q-icon name="cake" size="16px" /></template>
                <template #append>
                  <q-icon name="event" size="14px" class="cursor-pointer">
                    <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                      <q-date v-model="form.data_nascimento" mask="YYYY-MM-DD" :navigation-max-year-month="maxYearMonth">
                        <div class="row items-center justify-end">
                          <q-btn v-close-popup label="OK" color="primary" flat />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </q-icon>
                </template>
              </q-input>
            </div>

            <div class="form-group">
              <label class="form-label">DATA DE EXPIRAÇÃO</label>
              <q-input
                :model-value="displayExpiracao"
                outlined dense
                readonly placeholder="dd/mm/aaaa (padrão: +7 dias)"
                :error="!!errors.data_expiracao" :error-message="errors.data_expiracao?.[0]"
              >
                <template #prepend><q-icon name="schedule" size="16px" /></template>
                <template #append>
                  <q-icon name="event" size="14px" class="cursor-pointer">
                    <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                      <q-date v-model="form.data_expiracao" mask="YYYY-MM-DD">
                        <div class="row items-center justify-end">
                          <q-btn v-close-popup label="OK" color="primary" flat />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </q-icon>
                </template>
              </q-input>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">PERFIL *</label>
              <q-select
                v-model="form.role"
                :options="roleOptions"
                option-value="value" option-label="label"
                emit-value map-options
                outlined dense
                :error="!!errors.role" :error-message="errors.role?.[0]"
              >
                <template #prepend><q-icon name="manage_accounts" size="16px" /></template>
                <template #option="scope">
                  <q-item v-bind="scope.itemProps">
                    <q-item-section>
                      <q-item-label>{{ scope.opt.label }}</q-item-label>
                    </q-item-section>
                    <q-item-section side>
                      <role-badge :role="scope.opt.value" />
                    </q-item-section>
                  </q-item>
                </template>
              </q-select>
            </div>

            <div v-if="isEdit" class="form-group">
              <label class="form-label">STATUS</label>
              <q-select
                v-model="form.status"
                :options="statusOptions"
                option-value="value" option-label="label"
                emit-value map-options
                outlined dense
              >
                <template #prepend><q-icon name="toggle_on" size="16px" /></template>
              </q-select>
            </div>
          </div>

          <div v-if="!isEdit" class="email-notice">
            <q-icon name="mail_outline" size="14px" />
            <span>As credenciais serão enviadas por e-mail após o cadastro ser processado.</span>
          </div>

          <div class="dialog-actions">
            <q-btn flat label="Cancelar" @click="close" />
            <q-btn
              type="submit"
              :label="isEdit ? 'SALVAR ALTERAÇÕES' : 'CRIAR USUÁRIO'"
              :icon-right="isEdit ? 'save' : 'person_add'"
              class="save-btn"
              unelevated :loading="loading"
              padding="10px 24px"
            />
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>

<style scoped lang="scss">
.user-dialog {
  width: 680px;
  max-width: 96vw;
  border-radius: 16px !important;
  overflow: hidden;
  background: var(--bg-dialog) !important;
  border: 1px solid var(--border-subtle);
}

.dialog-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 24px 18px;
}

.dialog-header__info {
  display: flex;
  align-items: center;
  gap: 10px;
}

.dialog-header__title {
  font-size: 1.05rem;
  font-weight: 700;
  color: var(--text-primary);
}

.dialog-body { padding: 20px 24px 8px; }

.dialog-form {
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px;

  @media (max-width: 540px) { grid-template-columns: 1fr; }
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.form-label {
  font-size: 0.62rem;
  font-weight: 700;
  letter-spacing: 0.14em;
  color: var(--text-muted);
}

.email-notice {
  display: flex;
  align-items: center;
  gap: 7px;
  padding: 10px 14px;
  background: rgba(17, 214, 255, 0.06);
  border: 1px solid rgba(17, 214, 255, 0.18);
  border-radius: 8px;
  font-size: 0.78rem;
  color: var(--text-muted);
}

.dialog-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  padding-top: 8px;
  padding-bottom: 8px;
}

.save-btn {
  border-radius: 10px;
  font-weight: 700;
  letter-spacing: 0.05em;
  background: linear-gradient(90deg, #11d6ff, #00F5D4) !important;
  color: #04111f !important;
}
</style>

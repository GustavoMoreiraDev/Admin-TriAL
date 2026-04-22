<script setup>
import { ref, computed } from 'vue'
import { useQuasar } from 'quasar'
import { useThemeStore } from 'stores/theme'

const $q         = useQuasar()
const themeStore = useThemeStore()

const notifications = ref({
  email:        true,
  expiracao:    true,
  atualizacoes: false,
})

const isDark = computed(() => themeStore.isDark)

function selectTheme(dark) {
  themeStore.setDark(dark)
}

function handleSave() {
  $q.notify({ type: 'positive', message: 'Configurações salvas!' })
}
</script>

<template>
  <q-page class="settings-page">
    <div class="page-header">
      <div>
        <h1 class="page-header__title">Configurações</h1>
        <p class="page-header__sub">Controle notificações, aparência e preferências do sistema.</p>
      </div>
      <q-btn
        label="SALVAR CONFIGURAÇÕES"
        icon-right="save"
        class="save-btn"
        unelevated
        padding="10px 24px"
        @click="handleSave"
      />
    </div>

    <div class="settings-grid">
      <section class="section-card">
        <div class="section-header">
          <div class="section-header__icon-wrap">
            <q-icon name="notifications_none" size="18px" />
          </div>
          <h2 class="section-title">Notificações</h2>
        </div>
        <div class="setting-list">
          <div class="setting-item">
            <div class="setting-item__body">
              <span class="setting-item__label">Notificações por e-mail</span>
              <span class="setting-item__desc">Receber alertas e comunicados no e-mail cadastrado</span>
            </div>
            <q-toggle v-model="notifications.email" color="primary" />
          </div>
          <div class="setting-item">
            <div class="setting-item__body">
              <span class="setting-item__label">Aviso de expiração</span>
              <span class="setting-item__desc">Notificar quando sua conta estiver próxima do vencimento</span>
            </div>
            <q-toggle v-model="notifications.expiracao" color="primary" />
          </div>
          <div class="setting-item">
            <div class="setting-item__body">
              <span class="setting-item__label">Atualizações do sistema</span>
              <span class="setting-item__desc">Novidades e melhorias da plataforma</span>
            </div>
            <q-toggle v-model="notifications.atualizacoes" color="primary" />
          </div>
        </div>
      </section>

        <section class="section-card">
          <div class="section-header">
            <div class="section-header__icon-wrap">
              <q-icon name="palette" size="18px" />
            </div>
            <h2 class="section-title">Aparência</h2>
          </div>
          <div class="theme-options">
            <div
              class="theme-option"
              :class="{ 'theme-option--active': isDark }"
              @click="selectTheme(true)"
            >
              <div class="theme-option__preview theme-option__preview--dark">
                <div class="theme-preview-bar" />
                <div class="theme-preview-sidebar" />
                <div class="theme-preview-content">
                  <div class="theme-preview-line" />
                  <div class="theme-preview-line theme-preview-line--short" />
                  <div class="theme-preview-line theme-preview-line--med" />
                </div>
              </div>
              <div class="theme-option__footer">
                <span class="theme-option__label">Escuro</span>
                <q-icon v-if="isDark" name="check_circle" size="14px" color="primary" />
              </div>
            </div>

            <div
              class="theme-option"
              :class="{ 'theme-option--active': !isDark }"
              @click="selectTheme(false)"
            >
              <div class="theme-option__preview theme-option__preview--light">
                <div class="theme-preview-bar theme-preview-bar--light" />
                <div class="theme-preview-sidebar theme-preview-sidebar--light" />
                <div class="theme-preview-content">
                  <div class="theme-preview-line theme-preview-line--dark" />
                  <div class="theme-preview-line theme-preview-line--dark theme-preview-line--short" />
                  <div class="theme-preview-line theme-preview-line--dark theme-preview-line--med" />
                </div>
              </div>
              <div class="theme-option__footer">
                <span class="theme-option__label">Claro</span>
                <q-icon v-if="!isDark" name="check_circle" size="14px" color="primary" />
              </div>
            </div>
          </div>
        </section>

        <section class="section-card section-card--info">
          <div class="section-header">
            <div class="section-header__icon-wrap section-header__icon-wrap--purple">
              <q-icon name="info_outline" size="18px" />
            </div>
            <h2 class="section-title">Sobre a Plataforma</h2>
          </div>
          <div class="info-list">
            <div class="info-row">
              <span class="info-row__label">Versão</span>
              <span class="info-row__value">2.1.0</span>
            </div>
            <div class="info-row">
              <span class="info-row__label">Ambiente</span>
              <span class="info-row__value env-badge">Local</span>
            </div>
            <div class="info-row">
              <span class="info-row__label">Backend</span>
              <span class="info-row__value">Laravel 11 · PHP 8.3</span>
            </div>
            <div class="info-row">
              <span class="info-row__label">Frontend</span>
              <span class="info-row__value">Vue 3 · Quasar 2</span>
            </div>
            <div class="info-row">
              <span class="info-row__label">Autenticação</span>
              <span class="info-row__value">JWT (php-open-source-saver)</span>
            </div>
            <div class="info-row">
              <span class="info-row__label">Banco de dados</span>
              <span class="info-row__value">PostgreSQL 16</span>
            </div>
          </div>
        </section>

      <section class="section-card">
        <div class="section-header">
          <div class="section-header__icon-wrap">
            <q-icon name="security" size="18px" />
          </div>
          <h2 class="section-title">Sessão e Segurança</h2>
        </div>
        <div class="setting-list">
          <div class="setting-item">
            <div class="setting-item__body">
              <span class="setting-item__label">Autenticação em dois fatores (2FA)</span>
              <span class="setting-item__desc">Adicione uma camada extra de segurança ao login</span>
            </div>
            <q-badge outline color="warning" label="Em breve" class="soon-badge" />
          </div>
          <div class="setting-item">
            <div class="setting-item__body">
              <span class="setting-item__label">Expiração automática de sessão</span>
              <span class="setting-item__desc">Encerrar sessão automaticamente após inatividade</span>
            </div>
            <q-badge outline color="warning" label="Em breve" class="soon-badge" />
          </div>
          <div class="setting-item">
            <div class="setting-item__body">
              <span class="setting-item__label">Log de acessos</span>
              <span class="setting-item__desc">Histórico de dispositivos e locais de acesso</span>
            </div>
            <q-badge outline color="warning" label="Em breve" class="soon-badge" />
          </div>
        </div>
      </section>

    </div>
  </q-page>
</template>

<style scoped lang="scss">
.settings-page {
  padding: 0 36px 32px;
  min-height: 100vh;
  background: var(--bg-page);

  @media (max-width: 768px) { padding: 0 16px 24px; }
}

.page-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  margin-bottom: 28px;
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

.save-btn {
  border-radius: 12px;
  font-weight: 800;
  letter-spacing: 0.06em;
  background: linear-gradient(90deg, #56d7ff, #15e0c5) !important;
  color: #04111f !important;
  flex-shrink: 0;
}

.settings-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  grid-auto-rows: 1fr;
  gap: 20px;

  @media (max-width: 900px) { grid-template-columns: 1fr; grid-auto-rows: auto; }
}

.section-card {
  background: var(--bg-card);
  border: 1px solid var(--border-subtle);
  border-radius: 16px;
  padding: 22px 24px;
  box-shadow: var(--shadow-card);
}

.section-header {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
  padding-bottom: 14px;
  border-bottom: 1px solid var(--border-subtle);
}

.section-header__icon-wrap {
  width: 30px;
  height: 30px;
  border-radius: 8px;
  background: rgba(17, 214, 255, 0.1);
  border: 1px solid rgba(17, 214, 255, 0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--color-primary);
  flex-shrink: 0;

  &--purple {
    background: rgba(157, 80, 255, 0.1);
    border-color: rgba(157, 80, 255, 0.2);
    color: #b97aff;
  }
}

.section-title {
  margin: 0;
  font-size: 0.95rem;
  font-weight: 700;
  color: var(--text-primary);
}

.setting-list {
  display: flex;
  flex-direction: column;
}

.setting-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  padding: 13px 0;
  border-bottom: 1px solid var(--border-subtle);

  &:last-child { border-bottom: none; }
}

.setting-item__body {
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.setting-item__label {
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--text-secondary);
}

.setting-item__desc {
  font-size: 0.74rem;
  color: var(--text-muted);
}

.soon-badge { font-size: 0.62rem !important; flex-shrink: 0; }

.theme-options {
  display: flex;
  gap: 14px;
}

.theme-option {
  flex: 1;
  cursor: pointer;
  border-radius: 12px;
  border: 2px solid var(--border-subtle);
  overflow: hidden;
  transition: border-color 0.2s, box-shadow 0.2s;

  &:hover { border-color: var(--border-default); }

  &--active {
    border-color: #11d6ff;
    box-shadow: 0 0 0 2px rgba(17, 214, 255, 0.2);
  }
}

.theme-option__preview {
  width: 100%;
  height: 90px;
  display: flex;
  position: relative;
  overflow: hidden;

  &--dark  { background: #0b101b; }
  &--light { background: #e8edf5; }
}

.theme-preview-bar {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 12px;
  background: #131820;

  &--light { background: #ffffff; border-bottom: 1px solid #dde2ec; }
}

.theme-preview-sidebar {
  position: absolute;
  top: 12px;
  left: 0;
  bottom: 0;
  width: 28px;
  background: #0d1424;

  &--light { background: #f0f4fb; border-right: 1px solid #dde2ec; }
}

.theme-preview-content {
  position: absolute;
  top: 20px;
  left: 36px;
  right: 8px;
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.theme-preview-line {
  height: 5px;
  border-radius: 3px;
  background: rgba(255, 255, 255, 0.1);
  width: 100%;

  &--short { width: 55%; }
  &--med   { width: 75%; }
  &--dark  { background: rgba(0, 0, 0, 0.12); }
}

.theme-option__footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 14px;
  background: var(--bg-card-inner);
}

.theme-option__label {
  font-size: 0.78rem;
  font-weight: 600;
  color: var(--text-secondary);
}

.info-list {
  display: flex;
  flex-direction: column;
}

.info-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  padding: 10px 0;
  border-bottom: 1px solid var(--border-subtle);

  &:last-child { border-bottom: none; }
}

.info-row__label {
  font-size: 0.78rem;
  color: var(--text-muted);
  flex-shrink: 0;
}

.info-row__value {
  font-size: 0.78rem;
  font-weight: 600;
  color: var(--text-secondary);
  text-align: right;
}

.env-badge {
  background: rgba(17, 214, 255, 0.08);
  border: 1px solid rgba(17, 214, 255, 0.2);
  border-radius: 999px;
  padding: 1px 10px;
  color: #11d6ff !important;
  font-size: 0.7rem !important;
}
</style>

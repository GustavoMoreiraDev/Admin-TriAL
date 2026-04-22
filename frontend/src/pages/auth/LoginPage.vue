<script setup>
import { ref } from 'vue'
import LoginForm from 'components/auth/LoginForm.vue'
import RegisterForm from 'components/auth/RegisterForm.vue'
import LoginHero from 'components/auth/LoginHero.vue'

  const activeForm = ref('login')
</script>

<template>
  <q-layout view="lHh Lpr lFf">
    <q-page-container>
      <q-page class="auth-page">

        <login-hero />

        <aside class="auth-right">
          <login-form
            v-if="activeForm === 'login'"
            @switch-to-register="activeForm = 'register'"
          />
          <register-form
            v-else
            @switch-to-login="activeForm = 'login'"
          />
        </aside>

        <footer class="auth-footer">
          <span>© 2026 TriAL Smart Softwares</span>

          <div class="auth-footer__links">
            <router-link :to="{ name: 'termos' }">Termos e Privacidade</router-link>
            <router-link :to="{ name: 'suporte' }">Suporte</router-link>
          </div>
        </footer>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<style lang="scss" scoped>
.auth-page {
  min-height: 100vh;
  display: flex;
  justify-content: space-around;
  background: linear-gradient(180deg, #07111f 0%, #040b17 100%) !important;
  position: relative;
  overflow: hidden;
  padding: 42px 0 96px;
}

.auth-page::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(rgba(17, 214, 255, 0.04) 1px, transparent 1px),
    linear-gradient(90deg, rgba(17, 214, 255, 0.04) 1px, transparent 1px);
  background-size: 44px 44px;
  pointer-events: none;
}

.auth-right,
.auth-footer {
  position: relative;
  z-index: 1;
}

.auth-right {
  width: 100%;
  max-width: 520px;
  padding: 0 36px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.auth-footer {
  position: absolute;
  left: 0;
  bottom: 0;
  width: 100%;
  padding: 20px 56px;
  border-top: 1px solid rgba(255, 255, 255, 0.06);
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.72rem;
  color: rgba(196, 210, 231, 0.5);
}

.auth-footer__links {
  display: flex;
  gap: 22px;
}

.auth-footer__links a {
  color: rgba(196, 210, 231, 0.65);
  text-transform: uppercase;
  letter-spacing: 0.08em;
  text-decoration: none;
}

@media (max-width: 1100px) {
  .auth-page {
    flex-direction: column;
    justify-content: flex-start;
    padding: 36px 0 80px;
  }

  .auth-right {
    max-width: 100%;
    width: 100%;
  }

  .auth-right {
    padding: 16px 24px 40px;
  }

  .auth-footer {
    padding: 20px 24px;
  }
}

@media (max-width: 640px) {

  .auth-footer {
    flex-direction: column;
    gap: 12px;
    text-align: center;
  }
}
</style>
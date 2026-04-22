<template>
  <q-layout view="lHh Lpr lFf">
    <q-page-container>
      <q-page class="auth-page row justify-center items-center">
        <div class="auth-card-wrapper col-xs-12 col-sm-8 col-md-5 col-lg-4">
          <div class="auth-logo text-center q-mb-lg">
            <div class="text-h4 text-weight-bold text-primary">TriAL</div>
            <div class="text-caption text-grey-6">Desenvolvimento</div>
          </div>

          <q-card flat bordered class="auth-card">
            <q-card-section class="q-pa-lg">
              <slot />
            </q-card-section>
          </q-card>
        </div>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script setup>
</script>

<style lang="scss" scoped>
.auth-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
}

.auth-card {
  border-radius: 16px;
  border-color: rgba(255, 255, 255, 0.08);
  background: rgba(255, 255, 255, 0.04);
  backdrop-filter: blur(20px);
}
</style>

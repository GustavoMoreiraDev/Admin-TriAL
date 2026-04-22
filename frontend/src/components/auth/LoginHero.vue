<script setup>
  const features = [
    {
      title: 'Criptografia Total',
      description: 'Dados protegidos por protocolos de ponta.',
      icon: 'verified_user',
      iconClass: 'feature-card__icon--cyan'
    },
    {
      title: 'Alta Performance',
      description: 'Execução instantânea em qualquer mercado.',
      icon: 'bolt',
      iconClass: 'feature-card__icon--purple'
    }
  ]
</script>

<template>
  <section class="auth-left">
    <div class="auth-left__content">
      <div class="brand">
        <span class="brand__title">TriAL</span>
        <span class="brand__subtitle">SMART SOFTWARES</span>
      </div>

      <div class="hero">
        <h1 class="hero__title">
          A evolução da<br>
          <span class="hero__highlight">gestão financeira.</span>
        </h1>

        <p class="hero__description">
          Acesse sua plataforma e acompanhe em tempo real pelo seu computador, tablet ou celular.
        </p>
      </div>

      <div class="feature-list">
        <div
          v-for="feature in features"
          :key="feature.title"
          class="feature-card"
        >
          <q-icon
            :name="feature.icon"
            :class="['feature-card__icon', feature.iconClass]"
          />

          <div>
            <div class="feature-card__title">
              {{ feature.title }}
            </div>

            <div class="feature-card__description">
              {{ feature.description }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped lang="scss">
.auth-left {
  flex: 1;
  max-width: 760px;
  padding: 0 56px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.auth-left__content {
  max-width: 480px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  flex: 1;
}

.brand {
  margin-bottom: 56px;
}

.brand__title {
  display: block;
  font-size: 1.45rem;
  font-weight: 800;
  letter-spacing: 0.08em;
  color: #fff;
  line-height: 1;
}

.brand__subtitle {
  display: block;
  margin-top: 6px;
  font-size: 0.62rem;
  font-weight: 700;
  letter-spacing: 0.28em;
  color: #11d6ff;
}

.hero {
  margin-bottom: 42px;
}

.hero__title {
  margin: 0 0 18px;
  font-size: clamp(2.8rem, 4vw, 4.2rem);
  font-weight: 800;
  line-height: 1.08;
  color: #f7f9fc;
}

.hero__highlight {
  color: #18dfff;
}

.hero__description {
  margin: 0;
  max-width: 430px;
  font-size: 1.05rem;
  line-height: 1.75;
  color: rgba(196, 210, 231, 0.82);
}

.feature-list {
  display: grid;
  grid-template-columns: repeat(2, minmax(220px, 1fr));
  gap: 18px;
  max-width: 520px;
}

.feature-card {
  min-height: 108px;
  display: flex;
  align-items: flex-start;
  gap: 14px;
  padding: 22px 20px;
  border-radius: 16px;
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.06);
  backdrop-filter: blur(8px);
}

.feature-card__icon {
  margin-top: 2px;
  font-size: 1.2rem !important;
}

.feature-card__icon--cyan {
  color: #11d6ff !important;
}

.feature-card__icon--purple {
  color: #8a5cff !important;
}

.feature-card__title {
  margin-bottom: 6px;
  font-size: 0.95rem;
  font-weight: 700;
  color: #fff;
}

.feature-card__description {
  font-size: 0.82rem;
  line-height: 1.5;
  color: rgba(196, 210, 231, 0.72);
}

@media (max-width: 1100px) {
  .auth-left {
    max-width: 100%;
    width: 100%;
    padding: 0 24px 24px;
  }

  .auth-left__content {
    max-width: 100%;
  }

  .feature-list {
    grid-template-columns: 1fr;
    max-width: 100%;
  }
}

@media (max-width: 640px) {
  .hero__title {
    font-size: 2.2rem;
  }
}
</style>
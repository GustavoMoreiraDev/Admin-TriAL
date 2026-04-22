<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from 'stores/auth'

const router    = useRouter()
const authStore = useAuthStore()

function voltar() {
  router.push(authStore.usuario ? { name: 'home' } : { name: 'login' })
}

const voltarLabel = authStore.usuario ? 'Voltar ao painel' : 'Voltar ao login'

const canais = [
  {
    icon: 'phone',
    label: 'Suporte Técnico',
    valor: '(41) 3030-4400',
    detalhe: 'Seg–Sex, 08h às 18h',
    color: 'cyan',
  },
  {
    icon: 'phone_in_talk',
    label: 'Atendimento Comercial',
    valor: '(41) 3030-4401',
    detalhe: 'Seg–Sex, 09h às 17h',
    color: 'purple',
  },
  {
    icon: 'headset_mic',
    label: 'Plantão 24h (Crítico)',
    valor: '0800 720 4400',
    detalhe: 'Apenas incidentes críticos',
    color: 'teal',
  },
  {
    icon: 'mail_outline',
    label: 'E-mail de Suporte',
    valor: 'suporte@trial.com.br',
    detalhe: 'Resposta em até 4h úteis',
    color: 'orange',
  },
]

const faqs = [
  {
    pergunta: 'Esqueci minha senha. Como recupero o acesso?',
    resposta: 'Entre em contato com o administrador da sua conta ou acione o suporte técnico pelo telefone (41) 3030-4400. Um novo acesso temporário será enviado para o e-mail cadastrado.',
  },
  {
    pergunta: 'Minha conta expirou. O que fazer?',
    resposta: 'Contas expiram conforme a data de expiração configurada pelo administrador. Solicite a renovação ao administrador da plataforma ou ao atendimento comercial.',
  },
  {
    pergunta: 'Como altero meus dados pessoais na plataforma?',
    resposta: 'Acesse a área de Preferências no menu lateral após o login. Lá você pode atualizar nome, telefone, data de nascimento e senha.',
  },
  {
    pergunta: 'A plataforma está fora do ar. Onde reporto?',
    resposta: 'Use o plantão 24h (0800 720 4400) para incidentes críticos de indisponibilidade. Para degradação parcial, acione o suporte técnico pelo (41) 3030-4400.',
  },
  {
    pergunta: 'Como adiciono novos usuários ao sistema?',
    resposta: 'Apenas perfis Administrador podem criar usuários. Acesse o menu Usuários > Novo Usuário. As credenciais serão enviadas automaticamente por e-mail ao novo usuário.',
  },
  {
    pergunta: 'Posso usar a plataforma no celular?',
    resposta: 'Sim. A plataforma é responsiva e funciona em navegadores modernos de dispositivos móveis. Não há aplicativo nativo disponível neste momento.',
  },
]

const openFaq = ref(null)

function toggleFaq(i) {
  openFaq.value = openFaq.value === i ? null : i
}

const colorMap = {
  cyan:   '#11d6ff',
  purple: '#b97aff',
  teal:   '#00c4a7',
  orange: '#FFB020',
}
</script>

<template>
  <q-layout view="lHh Lpr lFf">
    <q-page-container>
      <q-page class="suporte-page">

        <div class="suporte-page__bg" />

        <div class="page-wrap">
          <header class="page-header">
            <div class="page-header__brand" @click="voltar()">
              <span class="brand-name">TriAL</span>
              <span class="brand-sub">SMART SOFTWARES</span>
            </div>
            <q-btn flat no-caps class="back-btn" icon="arrow_back" :label="voltarLabel" @click="voltar()" />
          </header>

          <div class="hero">
            <span class="hero__eyebrow">Central de Ajuda</span>
            <h1 class="hero__title">Como podemos <span class="hero__accent">ajudar?</span></h1>
            <p class="hero__sub">Nossa equipe está pronta para atender você nos canais abaixo. Descreva o problema com detalhes para um atendimento mais rápido.</p>
          </div>

          <section class="canais-section">
            <h2 class="section-title">Canais de Atendimento</h2>
            <div class="canais-grid">
              <div v-for="canal in canais" :key="canal.label" class="canal-card">
                <div class="canal-card__icon-wrap" :style="{ color: colorMap[canal.color] }">
                  <q-icon :name="canal.icon" size="24px" />
                </div>
                <div class="canal-card__body">
                  <span class="canal-card__label">{{ canal.label }}</span>
                  <span class="canal-card__valor" :style="{ color: colorMap[canal.color] }">{{ canal.valor }}</span>
                  <span class="canal-card__detalhe">{{ canal.detalhe }}</span>
                </div>
              </div>
            </div>
          </section>

          <section class="horarios-section">
            <div class="horarios-card">
              <div class="horarios-card__icon">
                <q-icon name="schedule" size="22px" />
              </div>
              <div>
                <h3 class="horarios-card__title">Horário de Funcionamento</h3>
                <div class="horarios-grid">
                  <div class="horario-item">
                    <span class="horario-item__dia">Segunda a Sexta</span>
                    <span class="horario-item__hora">08:00 – 18:00</span>
                  </div>
                  <div class="horario-item">
                    <span class="horario-item__dia">Sábado</span>
                    <span class="horario-item__hora">09:00 – 13:00</span>
                  </div>
                  <div class="horario-item">
                    <span class="horario-item__dia">Domingo e Feriados</span>
                    <span class="horario-item__hora">Plantão crítico</span>
                  </div>
                  <div class="horario-item">
                    <span class="horario-item__dia">SLA de resposta (e-mail)</span>
                    <span class="horario-item__hora">Até 4h úteis</span>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <section class="faq-section">
            <h2 class="section-title">Perguntas Frequentes</h2>
            <div class="faq-list">
              <div
                v-for="(faq, i) in faqs"
                :key="i"
                class="faq-item"
                :class="{ 'faq-item--open': openFaq === i }"
                @click="toggleFaq(i)"
              >
                <div class="faq-item__header">
                  <span class="faq-item__pergunta">{{ faq.pergunta }}</span>
                  <q-icon
                    name="expand_more"
                    size="18px"
                    class="faq-item__chevron"
                    :class="{ 'faq-item__chevron--open': openFaq === i }"
                  />
                </div>
                <div v-if="openFaq === i" class="faq-item__resposta">
                  {{ faq.resposta }}
                </div>
              </div>
            </div>
          </section>

          <section class="contato-direto">
            <div class="contato-direto__inner">
              <q-icon name="support_agent" size="32px" class="contato-direto__icon" />
              <div>
                <h3 class="contato-direto__title">Não encontrou o que precisa?</h3>
                <p class="contato-direto__sub">Envie um e-mail detalhado para <strong>suporte@trial.com.br</strong> e nossa equipe retornará em até 4 horas úteis.</p>
              </div>
            </div>
          </section>

          <footer class="page-footer">
            <span>© 2026 TriAL Smart Softwares — Central de Suporte</span>
            <div class="page-footer__links">
              <router-link :to="{ name: 'termos' }">Termos e Privacidade</router-link>
              <router-link :to="{ name: 'login' }">Acessar Plataforma</router-link>
            </div>
          </footer>
        </div>

      </q-page>
    </q-page-container>
  </q-layout>
</template>

<style scoped lang="scss">
.suporte-page {
  min-height: 100vh;
  background: linear-gradient(180deg, #07111f 0%, #040b17 100%);
  position: relative;
  padding-bottom: 64px;
}

.suporte-page__bg {
  position: fixed;
  inset: 0;
  background-image:
    linear-gradient(rgba(17,214,255,0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(17,214,255,0.03) 1px, transparent 1px);
  background-size: 44px 44px;
  pointer-events: none;
  z-index: 0;
}

.page-wrap {
  position: relative;
  z-index: 1;
  max-width: 860px;
  margin: 0 auto;
  padding: 0 24px;
}

.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 32px 0 40px;
  border-bottom: 1px solid rgba(255,255,255,0.06);
  margin-bottom: 56px;
}

.page-header__brand {
  cursor: pointer;
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.brand-name {
  font-size: 1.2rem;
  font-weight: 800;
  letter-spacing: 0.04em;
  color: #e8f0fc;
}

.brand-sub {
  font-size: 0.52rem;
  font-weight: 700;
  letter-spacing: 0.3em;
  color: #11d6ff;
}

.back-btn {
  color: rgba(196,210,231,0.6);
  font-size: 0.8rem;

  &:hover { color: #e8f0fc; }
}

.hero {
  margin-bottom: 56px;
}

.hero__eyebrow {
  display: inline-block;
  font-size: 0.68rem;
  font-weight: 700;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: #11d6ff;
  margin-bottom: 14px;
}

.hero__title {
  margin: 0 0 14px;
  font-size: 2.4rem;
  font-weight: 800;
  color: #e8f0fc;
  line-height: 1.1;
}

.hero__accent { color: #11d6ff; }

.hero__sub {
  font-size: 0.95rem;
  color: rgba(196,210,231,0.6);
  line-height: 1.7;
  max-width: 560px;
}

.section-title {
  font-size: 1.05rem;
  font-weight: 700;
  color: #e8f0fc;
  margin: 0 0 20px;
  padding-bottom: 12px;
  border-bottom: 1px solid rgba(255,255,255,0.06);
}

.canais-section { margin-bottom: 40px; }

.canais-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;

  @media (max-width: 560px) { grid-template-columns: 1fr; }
}

.canal-card {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 20px 22px;
  background: rgba(255,255,255,0.03);
  border: 1px solid rgba(255,255,255,0.08);
  border-radius: 14px;
  transition: border-color 0.2s, background 0.2s;

  &:hover {
    background: rgba(255,255,255,0.05);
    border-color: rgba(255,255,255,0.14);
  }
}

.canal-card__icon-wrap {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background: rgba(255,255,255,0.04);
  border: 1px solid rgba(255,255,255,0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.canal-card__body {
  display: flex;
  flex-direction: column;
  gap: 3px;
  min-width: 0;
}

.canal-card__label {
  font-size: 0.68rem;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: rgba(196,210,231,0.45);
}

.canal-card__valor {
  font-size: 1rem;
  font-weight: 700;
  font-family: monospace;
}

.canal-card__detalhe {
  font-size: 0.72rem;
  color: rgba(196,210,231,0.4);
}

.horarios-section { margin-bottom: 40px; }

.horarios-card {
  display: flex;
  align-items: flex-start;
  gap: 20px;
  padding: 24px 26px;
  background: rgba(17,214,255,0.04);
  border: 1px solid rgba(17,214,255,0.14);
  border-radius: 14px;
}

.horarios-card__icon {
  color: #11d6ff;
  flex-shrink: 0;
  margin-top: 2px;
}

.horarios-card__title {
  font-size: 0.95rem;
  font-weight: 700;
  color: #e8f0fc;
  margin: 0 0 16px;
}

.horarios-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;

  @media (max-width: 480px) { grid-template-columns: 1fr; }
}

.horario-item {
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.horario-item__dia  { font-size: 0.78rem; color: rgba(196,210,231,0.5); }
.horario-item__hora { font-size: 0.88rem; font-weight: 600; color: #e8f0fc; }

.faq-section { margin-bottom: 40px; }

.faq-list {
  display: flex;
  flex-direction: column;
  gap: 1px;
  background: rgba(255,255,255,0.05);
  border-radius: 14px;
  overflow: hidden;
  border: 1px solid rgba(255,255,255,0.07);
}

.faq-item {
  background: rgba(255,255,255,0.02);
  cursor: pointer;
  transition: background 0.15s;

  &:hover { background: rgba(255,255,255,0.04); }
  &--open { background: rgba(17,214,255,0.04); }
}

.faq-item__header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 18px 22px;
  gap: 16px;
}

.faq-item__pergunta {
  font-size: 0.88rem;
  font-weight: 600;
  color: #e8f0fc;
  line-height: 1.4;
}

.faq-item__chevron {
  color: rgba(196,210,231,0.4);
  flex-shrink: 0;
  transition: transform 0.2s;

  &--open { transform: rotate(180deg); color: #11d6ff; }
}

.faq-item__resposta {
  padding: 0 22px 18px;
  font-size: 0.85rem;
  color: rgba(196,210,231,0.65);
  line-height: 1.7;
  border-top: 1px solid rgba(255,255,255,0.05);
  padding-top: 14px;
}

.contato-direto {
  margin-bottom: 48px;
}

.contato-direto__inner {
  display: flex;
  align-items: flex-start;
  gap: 20px;
  padding: 28px;
  background: linear-gradient(135deg, rgba(157,80,255,0.08), rgba(17,214,255,0.06));
  border: 1px solid rgba(157,80,255,0.2);
  border-radius: 16px;
}

.contato-direto__icon { color: #b97aff; flex-shrink: 0; margin-top: 2px; }

.contato-direto__title {
  font-size: 1rem;
  font-weight: 700;
  color: #e8f0fc;
  margin: 0 0 8px;
}

.contato-direto__sub {
  font-size: 0.88rem;
  color: rgba(196,210,231,0.6);
  line-height: 1.65;
  margin: 0;

  strong { color: #e8f0fc; }
}

.page-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 24px 0 0;
  border-top: 1px solid rgba(255,255,255,0.06);
  font-size: 0.72rem;
  color: rgba(196,210,231,0.3);

  @media (max-width: 560px) { flex-direction: column; gap: 14px; text-align: center; }
}

.page-footer__links {
  display: flex;
  gap: 20px;

  a {
    color: rgba(196,210,231,0.55);
    text-decoration: none;
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    transition: color 0.15s;

    &:hover { color: #11d6ff; }
  }
}
</style>

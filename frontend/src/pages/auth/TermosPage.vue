<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from 'stores/auth'

const router    = useRouter()
const authStore = useAuthStore()

function voltar() {
  router.push(authStore.usuario ? { name: 'home' } : { name: 'login' })
}

const voltarLabel = authStore.usuario ? 'Voltar ao painel' : 'Voltar ao login'
</script>

<template>
  <q-layout view="lHh Lpr lFf">
    <q-page-container>
      <q-page class="termos-page">

        <div class="termos-page__bg" />

        <div class="doc-wrap">
          <header class="doc-header">
            <div class="doc-header__brand" @click="voltar()">
              <span class="brand-name">TriAL</span>
              <span class="brand-sub">SMART SOFTWARES</span>
            </div>
            <q-btn flat no-caps class="back-btn" icon="arrow_back" :label="voltarLabel" @click="voltar()" />
          </header>

          <div class="doc-body">
            <div class="doc-title-block">
              <span class="doc-eyebrow">Política Legal</span>
              <h1 class="doc-title">Termos de Uso e Privacidade</h1>
              <p class="doc-meta">Última atualização: 21 de abril de 2026 &nbsp;·&nbsp; Versão 2.1</p>
            </div>

            <div class="doc-alert">
              <q-icon name="verified_user" size="18px" />
              <span>Este documento está em conformidade com a <strong>Lei Geral de Proteção de Dados — LGPD (Lei nº 13.709/2018)</strong> e o Código de Defesa do Consumidor.</span>
            </div>

            <nav class="doc-toc">
              <span class="doc-toc__label">Neste documento</span>
              <div class="doc-toc__links">
                <a href="#coleta">1. Coleta de Dados</a>
                <a href="#uso">2. Uso dos Dados</a>
                <a href="#retencao">3. Retenção</a>
                <a href="#direitos">4. Seus Direitos</a>
                <a href="#seguranca">5. Segurança</a>
                <a href="#cookies">6. Cookies</a>
                <a href="#termos">7. Termos de Uso</a>
                <a href="#contato">8. Contato</a>
              </div>
            </nav>

            <section id="coleta" class="doc-section">
              <h2 class="doc-section__title"><span class="section-num">1</span> Coleta de Dados Pessoais</h2>
              <p>A TriAL Smart Softwares coleta dados pessoais fornecidos diretamente pelo titular no momento do cadastro e durante o uso da plataforma, incluindo:</p>
              <ul>
                <li><strong>Dados de identificação:</strong> nome completo, endereço de e-mail e número de telefone.</li>
                <li><strong>Dados de acesso:</strong> data de nascimento (para verificação de maioridade) e credenciais de autenticação (senha armazenada com hash bcrypt).</li>
                <li><strong>Dados de uso:</strong> registros de acesso (IP, data/hora, ações realizadas) para fins de auditoria e segurança.</li>
              </ul>
              <p>Não coletamos dados sensíveis (art. 11 da LGPD) sem consentimento explícito e específico do titular.</p>
            </section>

            <section id="uso" class="doc-section">
              <h2 class="doc-section__title"><span class="section-num">2</span> Finalidade e Base Legal do Uso</h2>
              <p>Os dados coletados são utilizados exclusivamente para as seguintes finalidades, com as respectivas bases legais (art. 7º e 11 da LGPD):</p>
              <div class="purpose-grid">
                <div class="purpose-card">
                  <q-icon name="login" size="20px" class="purpose-card__icon" />
                  <div>
                    <strong>Autenticação e acesso</strong>
                    <span>Execução de contrato</span>
                  </div>
                </div>
                <div class="purpose-card">
                  <q-icon name="notifications" size="20px" class="purpose-card__icon" />
                  <div>
                    <strong>Comunicações operacionais</strong>
                    <span>Legítimo interesse</span>
                  </div>
                </div>
                <div class="purpose-card">
                  <q-icon name="security" size="20px" class="purpose-card__icon" />
                  <div>
                    <strong>Segurança e prevenção de fraudes</strong>
                    <span>Obrigação legal</span>
                  </div>
                </div>
                <div class="purpose-card">
                  <q-icon name="analytics" size="20px" class="purpose-card__icon" />
                  <div>
                    <strong>Melhoria do produto</strong>
                    <span>Consentimento</span>
                  </div>
                </div>
              </div>
            </section>

            <section id="retencao" class="doc-section">
              <h2 class="doc-section__title"><span class="section-num">3</span> Retenção e Exclusão de Dados</h2>
              <p>Os dados pessoais são mantidos pelo período necessário à execução das finalidades declaradas, observando:</p>
              <ul>
                <li>Dados de conta: enquanto a conta estiver ativa + 5 anos após o encerramento (art. 16, II da LGPD).</li>
                <li>Logs de acesso: 6 meses, conforme Marco Civil da Internet (Lei nº 12.965/2014).</li>
                <li>Dados financeiros relacionados: 5 anos, conforme Código Tributário Nacional.</li>
              </ul>
              <p>Após o prazo aplicável, os dados são anonimizados ou eliminados de forma segura e irreversível.</p>
            </section>

            <section id="direitos" class="doc-section">
              <h2 class="doc-section__title"><span class="section-num">4</span> Seus Direitos como Titular</h2>
              <p>Conforme os arts. 17 a 22 da LGPD, você tem direito a:</p>
              <div class="rights-list">
                <div class="right-item"><q-icon name="check_circle" size="16px" /><span><strong>Confirmação e acesso</strong> — saber quais dados temos sobre você.</span></div>
                <div class="right-item"><q-icon name="check_circle" size="16px" /><span><strong>Correção</strong> — solicitar atualização de dados incompletos ou desatualizados.</span></div>
                <div class="right-item"><q-icon name="check_circle" size="16px" /><span><strong>Anonimização ou eliminação</strong> — para dados desnecessários ou tratados em desconformidade.</span></div>
                <div class="right-item"><q-icon name="check_circle" size="16px" /><span><strong>Portabilidade</strong> — receber seus dados em formato estruturado.</span></div>
                <div class="right-item"><q-icon name="check_circle" size="16px" /><span><strong>Revogação do consentimento</strong> — a qualquer momento, sem custos.</span></div>
                <div class="right-item"><q-icon name="check_circle" size="16px" /><span><strong>Oposição</strong> — contestar tratamento realizado em desacordo com a lei.</span></div>
              </div>
              <p>Para exercer qualquer direito, entre em contato pelo e-mail <strong>privacidade@trial.com.br</strong> ou pelo canal de suporte.</p>
            </section>

            <section id="seguranca" class="doc-section">
              <h2 class="doc-section__title"><span class="section-num">5</span> Segurança da Informação</h2>
              <p>Adotamos medidas técnicas e organizacionais adequadas para proteger seus dados contra acesso não autorizado, perda ou destruição:</p>
              <ul>
                <li>Criptografia de senhas com bcrypt (fator de custo ≥ 12).</li>
                <li>Autenticação via JWT com expiração configurável.</li>
                <li>Comunicação via HTTPS/TLS 1.2+ em todos os ambientes de produção.</li>
                <li>Controle de acesso por perfil (RBAC): administrador, editor e consultor.</li>
                <li>Logs de auditoria com retenção mínima de 6 meses.</li>
              </ul>
            </section>

            <section id="cookies" class="doc-section">
              <h2 class="doc-section__title"><span class="section-num">6</span> Cookies e Armazenamento Local</h2>
              <p>Utilizamos <strong>localStorage</strong> para armazenar o token JWT de sessão no dispositivo do usuário. Não utilizamos cookies de rastreamento ou publicidade. Os únicos dados armazenados localmente são:</p>
              <ul>
                <li>Token de autenticação JWT (removido ao realizar logout).</li>
                <li>Preferência de tema visual (claro/escuro).</li>
              </ul>
            </section>

            <section id="termos" class="doc-section">
              <h2 class="doc-section__title"><span class="section-num">7</span> Termos de Uso da Plataforma</h2>
              <p>Ao utilizar a plataforma TriAL, o usuário concorda com as seguintes condições:</p>
              <ul>
                <li>O acesso é pessoal e intransferível. Compartilhar credenciais constitui violação contratual.</li>
                <li>É vedado o uso da plataforma para fins ilícitos, fraudulentos ou que violem direitos de terceiros.</li>
                <li>A TriAL reserva-se o direito de suspender contas que violem estes termos, sem aviso prévio nos casos de fraude comprovada.</li>
                <li>O serviço é fornecido "no estado em que se encontra". A TriAL não garante disponibilidade ininterrupta, mas compromete-se com SLA mínimo de 99,5% em ambiente de produção.</li>
                <li>Qualquer disputa oriunda deste contrato será resolvida no foro da comarca de Curitiba — PR, com renúncia de qualquer outro.</li>
              </ul>
            </section>

            <section id="contato" class="doc-section">
              <h2 class="doc-section__title"><span class="section-num">8</span> Contato e Encarregado de Dados (DPO)</h2>
              <div class="contact-cards">
                <div class="contact-card">
                  <q-icon name="mail_outline" size="20px" />
                  <div>
                    <strong>Encarregado de Dados (DPO)</strong>
                    <span>privacidade@trial.com.br</span>
                  </div>
                </div>
                <div class="contact-card">
                  <q-icon name="business" size="20px" />
                  <div>
                    <strong>Razão Social</strong>
                    <span>TriAL Smart Softwares Ltda. — CNPJ 00.000.000/0001-00</span>
                  </div>
                </div>
                <div class="contact-card">
                  <q-icon name="location_on" size="20px" />
                  <div>
                    <strong>Endereço</strong>
                    <span>Av. das Américas, 3434 — Curitiba, PR — CEP 80.000-000</span>
                  </div>
                </div>
              </div>
            </section>
          </div>

          <footer class="doc-footer">
            <span>© 2026 TriAL Smart Softwares — Todos os direitos reservados</span>
            <q-btn flat no-caps class="back-btn" icon="arrow_back" :label="voltarLabel" @click="voltar()" />
          </footer>
        </div>

      </q-page>
    </q-page-container>
  </q-layout>
</template>

<style scoped lang="scss">
.termos-page {
  min-height: 100vh;
  background: linear-gradient(180deg, #07111f 0%, #040b17 100%);
  position: relative;
  padding: 0 0 64px;
}

.termos-page__bg {
  position: fixed;
  inset: 0;
  background-image:
    linear-gradient(rgba(17,214,255,0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(17,214,255,0.03) 1px, transparent 1px);
  background-size: 44px 44px;
  pointer-events: none;
  z-index: 0;
}

.doc-wrap {
  position: relative;
  z-index: 1;
  max-width: 860px;
  margin: 0 auto;
  padding: 0 24px;
}

.doc-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 32px 0 40px;
  border-bottom: 1px solid rgba(255,255,255,0.06);
  margin-bottom: 48px;
}

.doc-header__brand {
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

.doc-title-block { margin-bottom: 32px; }

.doc-eyebrow {
  display: inline-block;
  font-size: 0.68rem;
  font-weight: 700;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: #11d6ff;
  margin-bottom: 12px;
}

.doc-title {
  margin: 0 0 10px;
  font-size: 2rem;
  font-weight: 800;
  color: #e8f0fc;
  line-height: 1.15;
}

.doc-meta {
  font-size: 0.78rem;
  color: rgba(196,210,231,0.45);
}

.doc-alert {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  background: rgba(17,214,255,0.06);
  border: 1px solid rgba(17,214,255,0.18);
  border-radius: 10px;
  padding: 14px 18px;
  font-size: 0.84rem;
  color: rgba(196,210,231,0.75);
  margin-bottom: 32px;
  line-height: 1.6;

  .q-icon { color: #11d6ff; flex-shrink: 0; margin-top: 2px; }
  strong  { color: #e8f0fc; }
}

.doc-toc {
  background: rgba(255,255,255,0.03);
  border: 1px solid rgba(255,255,255,0.07);
  border-radius: 12px;
  padding: 18px 22px;
  margin-bottom: 48px;
}

.doc-toc__label {
  display: block;
  font-size: 0.62rem;
  font-weight: 700;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: rgba(196,210,231,0.4);
  margin-bottom: 12px;
}

.doc-toc__links {
  display: flex;
  flex-wrap: wrap;
  gap: 8px 20px;

  a {
    font-size: 0.82rem;
    color: rgba(196,210,231,0.6);
    text-decoration: none;
    transition: color 0.15s;

    &:hover { color: #11d6ff; }
  }
}

.doc-section {
  margin-bottom: 48px;

  p, li { color: rgba(196,210,231,0.72); font-size: 0.9rem; line-height: 1.75; }
  ul    { padding-left: 20px; display: flex; flex-direction: column; gap: 6px; margin: 12px 0; }
  strong { color: #e8f0fc; }
}

.doc-section__title {
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 1.15rem;
  font-weight: 700;
  color: #e8f0fc;
  margin: 0 0 16px;
  padding-bottom: 12px;
  border-bottom: 1px solid rgba(255,255,255,0.06);
}

.section-num {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 28px;
  height: 28px;
  border-radius: 8px;
  background: rgba(17,214,255,0.1);
  border: 1px solid rgba(17,214,255,0.2);
  font-size: 0.75rem;
  font-weight: 800;
  color: #11d6ff;
  flex-shrink: 0;
}

.purpose-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin: 16px 0;

  @media (max-width: 560px) { grid-template-columns: 1fr; }
}

.purpose-card {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 14px 16px;
  background: rgba(255,255,255,0.03);
  border: 1px solid rgba(255,255,255,0.07);
  border-radius: 10px;

  div {
    display: flex;
    flex-direction: column;
    gap: 2px;

    strong { font-size: 0.82rem; color: #e8f0fc; }
    span   { font-size: 0.72rem; color: rgba(196,210,231,0.5); }
  }
}

.purpose-card__icon { color: #11d6ff; flex-shrink: 0; }

.rights-list {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin: 16px 0;
}

.right-item {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  font-size: 0.88rem;
  color: rgba(196,210,231,0.7);
  line-height: 1.6;

  .q-icon { color: #00c4a7; flex-shrink: 0; margin-top: 3px; }
  strong  { color: #e8f0fc; }
}

.contact-cards {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: 16px;
}

.contact-card {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 14px 18px;
  background: rgba(255,255,255,0.03);
  border: 1px solid rgba(255,255,255,0.07);
  border-radius: 10px;

  .q-icon { color: #11d6ff; flex-shrink: 0; }

  div {
    display: flex;
    flex-direction: column;
    gap: 3px;

    strong { font-size: 0.82rem; color: #e8f0fc; }
    span   { font-size: 0.78rem; color: rgba(196,210,231,0.55); }
  }
}

.doc-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 24px 0 0;
  border-top: 1px solid rgba(255,255,255,0.06);
  margin-top: 24px;
  font-size: 0.72rem;
  color: rgba(196,210,231,0.3);

  @media (max-width: 560px) { flex-direction: column; gap: 12px; }
}
</style>

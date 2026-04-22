<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Suas credenciais de acesso</title>
  <style>
    body { font-family: 'Segoe UI', Arial, sans-serif; background: #0B101B; color: #C4D2E7; margin: 0; padding: 0; }
    .container { max-width: 520px; margin: 40px auto; background: #111827; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; overflow: hidden; }
    .header { background: linear-gradient(135deg, #11d6ff22, #9D50FF22); padding: 32px 36px 24px; border-bottom: 1px solid rgba(255,255,255,0.06); }
    .logo { font-size: 1.4rem; font-weight: 800; color: #11d6ff; letter-spacing: -0.02em; }
    .header h1 { margin: 12px 0 0; font-size: 1.1rem; font-weight: 600; color: #e8f0fc; }
    .body { padding: 28px 36px; }
    .greeting { font-size: 0.95rem; color: #C4D2E7; margin-bottom: 20px; }
    .credentials { background: #0B101B; border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 20px 24px; margin: 20px 0; }
    .credential-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
    .credential-row:last-child { border-bottom: none; }
    .credential-label { font-size: 0.72rem; font-weight: 700; letter-spacing: 0.1em; color: rgba(196,210,231,0.5); text-transform: uppercase; }
    .credential-value { font-size: 0.9rem; font-weight: 600; color: #e8f0fc; font-family: monospace; }
    .role-badge { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: 0.7rem; font-weight: 700; }
    .role-administrador { background: rgba(157,80,255,.2); color: #b97aff; }
    .role-editor { background: rgba(17,214,255,.15); color: #11d6ff; }
    .role-consultor { background: rgba(0,245,212,.12); color: #00c4a7; }
    .notice { font-size: 0.8rem; color: rgba(196,210,231,0.5); margin-top: 20px; line-height: 1.6; }
    .footer { padding: 16px 36px; border-top: 1px solid rgba(255,255,255,0.06); font-size: 0.72rem; color: rgba(196,210,231,0.3); text-align: center; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="logo">TriAL</div>
      <h1>Sua conta foi criada com sucesso!</h1>
    </div>
    <div class="body">
      <p class="greeting">Olá, <strong style="color:#e8f0fc">{{ $usuario->nome }}</strong>! Bem-vindo à plataforma TriAL. Suas credenciais de acesso estão abaixo.</p>

      <div class="credentials">
        <div class="credential-row">
          <span class="credential-label">E-mail</span>
          <span class="credential-value">{{ $usuario->email }}</span>
        </div>
        <div class="credential-row">
          <span class="credential-label">Senha</span>
          <span class="credential-value">{{ $senhaPlana }}</span>
        </div>
        <div class="credential-row">
          <span class="credential-label">Perfil</span>
          <span class="role-badge role-{{ $usuario->role }}">{{ ucfirst($usuario->role) }}</span>
        </div>
        <div class="credential-row">
          <span class="credential-label">Acesso válido até</span>
          <span class="credential-value">{{ $usuario->data_expiracao?->format('d/m/Y') ?? 'Indefinido' }}</span>
        </div>
      </div>

      <p class="notice">Por segurança, recomendamos alterar sua senha após o primeiro acesso. Guarde estas informações em local seguro.</p>
    </div>
    <div class="footer">TriAL Platform &mdash; Este é um e-mail automático, não responda.</div>
  </div>
</body>
</html>

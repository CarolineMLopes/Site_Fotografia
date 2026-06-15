# Configurar envio de e-mail (PHPMailer + Gmail SMTP)

Guia passo-a-passo pra fazer o form de contato enviar e-mail de verdade.

---

## 1. Instalar o PHPMailer

### Opção A — Com Composer (recomendado)

Se você ainda **não tem o Composer**, baixe em https://getcomposer.org/Composer-Setup.exe e instale.

Depois, no terminal, dentro da pasta do projeto:

```bash
cd "D:\Documentos\ADS\projetoextracurricular\2parte\HTML.CSS"
composer install
```

Isso cria a pasta `vendor/` com o PHPMailer dentro. Pronto.

### Opção B — Sem Composer (manual)

1. Baixe o PHPMailer ZIP: https://github.com/PHPMailer/PHPMailer/archive/refs/heads/master.zip
2. Extraia o zip
3. Crie a pasta `vendor/phpmailer/phpmailer/` no projeto
4. Copie a pasta `src/` do zip pra dentro de `vendor/phpmailer/phpmailer/`
5. Crie o arquivo `vendor/autoload.php` com o conteúdo abaixo:

```php
<?php
require_once __DIR__ . '/phpmailer/phpmailer/src/Exception.php';
require_once __DIR__ . '/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/phpmailer/phpmailer/src/SMTP.php';
```

Pronto, agora funciona igual ao Composer.

---

## 2. Gerar a "Senha de App" do Gmail

O Gmail não deixa mais usar a senha normal pra enviar e-mail por aplicativo. Você precisa criar uma "Senha de app" específica:

1. Acesse https://myaccount.google.com/security
2. **Ative a "Verificação em duas etapas"** (obrigatório, senão a opção de senha de app não aparece).
3. Volte em **Segurança** → procure "**Senhas de app**" (pode estar dentro de "Como você faz login no Google").
4. Em **Selecionar app** → escolha "**E-mail**".
5. Em **Selecionar dispositivo** → "**Outro (nome personalizado)**" → digite "NK Site".
6. Clique em **Gerar**.
7. O Google mostra 16 caracteres tipo `abcd efgh ijkl mnop`. **Copia tudo, ignorando os espaços.**

---

## 3. Configurar a senha no projeto

Abra `html/includes/mail-config.php`:

```php
'smtp_password' => 'COLE_AQUI_A_SENHA_DE_APP_DE_16_CHARS',
```

Substitua pelo que o Google te deu, sem espaços. Exemplo:

```php
'smtp_password' => 'abcdefghijklmnop',
```

> **Importante:** esse arquivo está no `.gitignore`. **Nunca** suba ele pro GitHub público — é sua chave de acesso.

---

## 4. Testar

```bash
php -S localhost:8000
```

Abra http://localhost:8000 → role até "Contato" → preencha o form → envia.

Se chegou no Gmail: ✅ tá funcionando.
Se aparecer erro: confere se ativou 2FA, se copiou a senha sem espaços, e se o `smtp_user` é exatamente o mesmo Gmail.

---

## 5. Como funciona (resumo técnico)

```
[ form HTML ]
    │ POST
    ▼
[ contato.php ]                ← valida + sanitiza
    │
    ├─ honeypot? sai silencioso
    │
    ├─ carrega mail-config.php
    │
    ▼
[ PHPMailer ]                  ← se conecta no smtp.gmail.com:587 (TLS)
    │ login com app password
    │
    ▼
[ Gmail SMTP ]                 ← envia o e-mail
    │
    ▼
[ Inbox do destino ]           ← carolinemlopes98@gmail.com
```

---

## 6. Quando hospedar online

Se você for hospedar em InfinityFree, Hostinger, etc., **NÃO suba o arquivo `mail-config.php` com a senha real pelo Git**. Configure assim:

1. Suba o código sem o `mail-config.php` (o `.gitignore` já cuida disso).
2. Pelo painel do servidor (cPanel/FTP), crie o `mail-config.php` direto lá, com a senha de app.
3. Pronto: o servidor tem a senha, o GitHub não.

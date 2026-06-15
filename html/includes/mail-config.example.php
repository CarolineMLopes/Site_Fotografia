<?php
/**
 * mail-config.example.php — Modelo de configuração SMTP.
 *
 * Copie este arquivo para mail-config.php e preencha os valores.
 * O mail-config.php NÃO é commitado no Git (veja .gitignore).
 */
return [
    'smtp_host'      => 'smtp.gmail.com',
    'smtp_port'      => 587,
    'smtp_secure'    => 'tls',
    'smtp_user'      => 'SEU_EMAIL@gmail.com',
    'smtp_password'  => 'SUA_SENHA_DE_APP_AQUI',
    'destino'        => 'SEU_EMAIL@gmail.com',
    'destino_nome'   => 'Nome do site',
    'remetente_nome' => 'Site',
];

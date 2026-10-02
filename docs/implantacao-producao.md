# Implantação em produção

Este guia cobre a publicação do sistema de suporte e inventário Laravel 12 em um servidor Linux com PHP 8.2+, MySQL/MariaDB, Node.js e um servidor web como Nginx ou Apache.

## 1. Requisitos

- PHP 8.2 ou superior.
- Extensões PHP: `ctype`, `curl`, `dom`, `fileinfo`, `gd`, `mbstring`, `openssl`, `pdo`, `pdo_mysql`, `tokenizer`, `xml` e `zip`.
- Composer 2.
- Node.js 20+ e npm.
- MySQL/MariaDB.
- Nginx ou Apache apontando o document root para `public/`.
- Supervisor ou systemd para manter o worker da fila ativo.
- Cron para executar o scheduler do Laravel.

## 2. Publicação do código

```bash
git clone https://github.com/duu-beat/TI.git /var/www/ti
cd /var/www/ti
git checkout main
composer install --no-dev --prefer-dist --optimize-autoloader
npm ci
npm run build
```

Nunca copie o `.env` do ambiente local para o servidor. Crie o arquivo de produção diretamente no servidor e mantenha-o fora do Git.

## 3. Variáveis obrigatórias do `.env`

Use valores reais e fortes no servidor:

```dotenv
APP_NAME="TI - Suporte e Inventário"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://suporte.exemplo.com
APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR
APP_FAKER_LOCALE=pt_BR
APP_TIMEZONE=America/Sao_Paulo

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=informatico
DB_USERNAME=ti_app
DB_PASSWORD=senha-forte

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local

MAIL_MAILER=smtp
MAIL_HOST=smtp.exemplo.com
MAIL_PORT=587
MAIL_USERNAME=usuario-smtp
MAIL_PASSWORD=senha-smtp
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=suporte@exemplo.com
MAIL_FROM_NAME="${APP_NAME}"
```

Gere a chave apenas se `APP_KEY` estiver vazio:

```bash
php artisan key:generate --force
```

Não altere `APP_KEY` depois que o sistema estiver em uso sem planejar a rotação das chaves. A troca invalida dados criptografados e sessões existentes.

## 4. Banco e arquivos

```bash
php artisan migrate --force
php artisan storage:link
php artisan optimize:clear
php artisan optimize
```

O sistema usa o disco `local` para anexos de chamados, assinaturas e PDFs de termos. Esses arquivos ficam fora da pasta pública e só devem ser entregues pelos controllers autorizados. Não troque `FILESYSTEM_DISK` para `public` sem revisar essa regra.

Permissões recomendadas:

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo find storage bootstrap/cache -type d -exec chmod 775 {} \;
sudo find storage bootstrap/cache -type f -exec chmod 664 {} \;
```

## 5. Fila de jobs

A aplicação envia notificações e outros trabalhos para a fila. Mantenha um worker permanente:

```bash
php artisan queue:work database --sleep=3 --tries=3 --timeout=120 --max-time=3600
```

Após cada publicação de código:

```bash
php artisan queue:restart
```

Monitore falhas regularmente:

```bash
php artisan queue:failed
php artisan queue:prune-failed --hours=168
```

Exemplo de unidade systemd (`/etc/systemd/system/ti-queue.service`):

```ini
[Unit]
Description=Fila Laravel do sistema TI
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
RestartSec=5
WorkingDirectory=/var/www/ti
ExecStart=/usr/bin/php artisan queue:work database --sleep=3 --tries=3 --timeout=120 --max-time=3600

[Install]
WantedBy=multi-user.target
```

Ative com:

```bash
sudo systemctl daemon-reload
sudo systemctl enable --now ti-queue
sudo systemctl status ti-queue
```

## 6. Scheduler e escalonamento de SLA

O projeto agenda `tickets:escalate-overdue` a cada 15 minutos. Configure uma entrada de cron para chamar o scheduler:

```cron
* * * * * cd /var/www/ti && php artisan schedule:run >> /dev/null 2>&1
```

Valide o agendamento:

```bash
php artisan schedule:list
php artisan schedule:test
```

## 7. Cache e deploy

Depois de cada release:

```bash
php artisan down --render="errors::503" --retry=60
php artisan migrate --force
php artisan optimize
php artisan queue:restart
php artisan up
```

Se o deploy for feito sem janela de manutenção, execute pelo menos `php artisan optimize` e `php artisan queue:restart` após atualizar os arquivos.

## 8. Backup mínimo

Faça backup diário do banco e do diretório privado de arquivos. Mantenha várias retenções e teste a restauração mensalmente. O backup deve incluir:

- Banco MySQL/MariaDB.
- `storage/app/private`.
- `.env` armazenado em cofre seguro, nunca no repositório.
- Chaves ou credenciais externas necessárias para e-mail e integrações.

## 9. Checklist pós-publicação

- [ ] `APP_DEBUG=false`.
- [ ] `APP_URL` usa HTTPS.
- [ ] Certificado TLS válido.
- [ ] `APP_KEY` preenchida.
- [ ] Banco de produção configurado e migrations executadas.
- [ ] `storage:link` executado apenas para arquivos realmente públicos.
- [ ] Worker `ti-queue` ativo.
- [ ] Cron do scheduler configurado.
- [ ] E-mail SMTP testado.
- [ ] Download de anexo privado testado com usuário autorizado e não autorizado.
- [ ] Login Admin e Master testados com 2FA.
- [ ] Backup executado e restauração validada.
- [ ] Logs e espaço em disco monitorados.

## 10. Verificação rápida

```bash
php artisan about
php artisan migrate:status
php artisan route:list --except-vendor
php artisan queue:failed
php artisan schedule:list
```

A rota `/seguranca/saude` também disponibiliza a verificação operacional para usuários Master, respeitando a autenticação e o 2FA configurados.

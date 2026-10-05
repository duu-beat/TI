# Estado atual do projeto

**Atualizado em:** 5 de outubro de 2026
**Branch:** `main`  
**Último avanço validado:** unificação dos FormRequests de chamados

## Resumo

O sistema está em fase de **estabilização técnica**, com os principais fluxos funcionais implementados. O locale atual não foi alterado nesta etapa para preservar o ambiente de desenvolvimento existente.

## Validações do último avanço

- 89 testes aprovados.
- 340 asserções executadas.
- 7 testes ignorados por funcionalidades opcionais já conhecidas.
- Views Blade compiladas com sucesso.
- Rotas compiladas com sucesso.
- Build Vite concluído.
- PHP lint executado nos arquivos alterados.
- Repositório sincronizado com `origin/main`.

O build exibiu apenas um aviso não bloqueante sobre a versão desatualizada do `caniuse-lite`.

## Segurança estabilizada

- Providers de login social limitados a `google` e `github`.
- Providers não autorizados retornam `404`.
- Teste de regressão para impedir chaves `messages.public.*` e `messages.seo.*` na Home.
- Anexos novos usam armazenamento privado (`local`) e download autorizado pelo chamado.
- Removido o trait legado que gravava anexos no disco público.
- Removido o fallback JavaScript para `/storage/` nas telas de chamados.
- Nome enviado no header de download é sanitizado contra quebra de cabeçalho.
- Teste de isolamento entre proprietários e teste de sanitização de nome de anexo.
- O fluxo de criação de chamados utiliza apenas `app/Http/Requests/StoreTicketRequest.php`.
- Removido o FormRequest duplicado e não utilizado em `app/Http/Requests/Client/StoreTicketRequest.php`.
- Policies e middlewares existentes continuam preservados.
- O locale não foi modificado nesta etapa.

## Módulos disponíveis

- Portal público institucional.
- Autenticação de clientes, Admin e Master.
- 2FA para perfis privilegiados.
- Chamados, mensagens, anexos e NPS.
- SLA e escalonamento automático.
- Tags e respostas prontas.
- Checklists de atendimento.
- Visitas técnicas.
- Inventário de ativos.
- QR Code de ativos.
- Termos digitais com assinatura.
- Base de Conhecimento/Wiki.
- Relatórios em PDF e Excel/CSV.
- Notificações.
- Auditoria e health check Master.

## Pontos ainda pendentes

### Prioridade alta

- Validar migrations, índices e constraints em MySQL/MariaDB real.
- Configurar e testar filas, scheduler, e-mail e backup no ambiente de implantação.

A duplicidade dos FormRequests de criação de chamados foi resolvida. O fluxo utiliza apenas `app/Http/Requests/StoreTicketRequest.php`; o arquivo específico não utilizado em `app/Http/Requests/Client/StoreTicketRequest.php` foi removido para evitar regras conflitantes.

### Prioridade média

- Criar Policies específicas para módulos que hoje dependem apenas de middleware administrativo.
- Ampliar testes de autorização para todos os recursos.
- Melhorar busca textual para bases maiores.
- Preparar exportações grandes para processamento em fila.
- Atualizar documentação de deploy conforme o ambiente definitivo.

### Próximas funcionalidades

- Setores, filiais e localizações.
- Histórico completo de movimentação de ativos.
- Importação de inventário por CSV/Excel.
- Gestão de fornecedores, garantias e contratos.
- Dashboard analítico por setor, categoria e ativo.
- Escalonamento configurável por prioridade e categoria.
- Integração oficial com WhatsApp ou Telegram, após definição do provedor e requisitos de LGPD.
- Recursos opcionais de IA, controlados por configuração e limite de custo.

## Fluxo obrigatório para cada avanço

1. Alterar uma área isolada.
2. Atualizar esta documentação e os documentos específicos afetados.
3. Executar lint e testes direcionados.
4. Executar a suíte completa.
5. Executar `npm run build` quando houver impacto no frontend.
6. Revisar `git diff --check`.
7. Criar commit em português.
8. Publicar somente após revisão explícita.

## Comandos de validação

```bash
php artisan optimize:clear
php artisan test
php artisan view:cache
php artisan route:cache
npm run build
git diff --check
```

## Observação sobre locale

O locale não deve ser alterado automaticamente. Qualquer mudança em `APP_LOCALE`, `APP_FALLBACK_LOCALE`, `APP_FAKER_LOCALE` ou `config/app.php` deve ser tratada como uma alteração separada, com validação no ambiente local do usuário antes de publicação.

# Estado atual do projeto

**Atualizado em:** 16 de outubro de 2026
**Branch:** `main`  
**Último avanço validado:** transferência transacional de ativos

## Resumo

O sistema está em fase de **estabilização técnica**, com os principais fluxos funcionais implementados. O locale atual não foi alterado nesta etapa para preservar o ambiente de desenvolvimento existente.

## Validações do último avanço

- 106 testes aprovados.
- 426 asserções executadas.
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
- Migration aplicada para alterar o padrão histórico de `ticket_attachments.disk` para `local`.
- Valores nulos de `disk` são normalizados para `local`; arquivos antigos não são movidos automaticamente.
- Policies explícitas adicionadas para Tags, respostas prontas e modelos de checklist.
- Clientes recebem `403` nesses módulos e Admin mantém o acesso operacional.
- Policy explícita adicionada para visitas técnicas, incluindo autorização do chamado no agendamento e da visita na atualização de status.
- Policy nomeada `viewReports` adicionada para proteger a tela e as exportações PDF/CSV dos relatórios.
- Histórico de ativos permanece acessível somente dentro da ficha autorizada pela `AssetPolicy`; não há endpoint independente exposto.
- Matriz de regressão confirma que Cliente e Master não acessam módulos operacionais em `/admin`; o Admin mantém o acesso operacional.
- Endpoints de alteração de status, resposta, escalação, atribuição, fusão e checklist passaram a exigir autorização explícita do chamado.
- Fusão entre chamados de clientes diferentes foi bloqueada para impedir exposição de mensagens e anexos entre contas.
- O fluxo de criação de chamados utiliza apenas `app/Http/Requests/StoreTicketRequest.php`.
- Removido o FormRequest duplicado e não utilizado em `app/Http/Requests/Client/StoreTicketRequest.php`.
- Policies e middlewares existentes continuam preservados.
- O locale não foi modificado nesta etapa.
- O Kanban carrega somente a mensagem mais recente e seus anexos por chamado, evitando carregar todo o histórico em memória.
- Relatórios administrativos não carregam mais a relação de tags, que não é utilizada nas telas nem nas exportações.
- Exportação CSV utiliza `lazy(500)` e grava os registros em fluxo, reduzindo o uso de memória em bases maiores.
- Busca global normaliza espaços, limita a entrada a 80 caracteres e seleciona somente as colunas necessárias.
- Busca global mantém limites de 5 chamados e 3 usuários, com ordenação determinística e teste de autorização para o endpoint.
- Menu público desktop e mobile reorganizado para: Serviços, FAQ, Portfólio, Sobre e Contato.
- Ativos agora podem ser vinculados opcionalmente a uma filial e a um setor.
- Setores só podem ser vinculados à filial correspondente; filiais e setores excluídos logicamente não são aceitos nos formulários.
- Ativos existentes permanecem compatíveis sem localização definida.
- CRUD administrativo de filiais e setores protegido por policy e middleware de administrador.
- Exclusão de filiais e setores utiliza SoftDeletes, preservando vínculos e histórico dos ativos.
- Nomes de setores são únicos dentro de cada filial, com regressão automatizada.
- Transferências de ativos atualizam responsável, filial e setor dentro de transação com `lockForUpdate()`.
- Cada transferência registra motivo, executor e valores antigos/novos no histórico do ativo.
- Setor de outra filial é rejeitado antes da alteração, evitando estado inconsistente.

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
- Filiais e setores para organização do inventário.
- Transferência rastreável de ativos entre responsáveis, filiais e setores.
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

A análise estática de MySQL/MariaDB foi concluída e está registrada em [analise-mysql-mariadb.md](analise-mysql-mariadb.md). A validação real permanece pendente porque o sandbox atual não possui servidor MySQL/MariaDB disponível. O padrão dos novos anexos já foi corrigido para `local`; a migração física de arquivos históricos, caso necessária, depende da inspeção do ambiente.

A duplicidade dos FormRequests de criação de chamados foi resolvida. O fluxo utiliza apenas `app/Http/Requests/StoreTicketRequest.php`; o arquivo específico não utilizado em `app/Http/Requests/Client/StoreTicketRequest.php` foi removido para evitar regras conflitantes.

### Prioridade média

- Ampliar testes de autorização para todos os recursos.
- Avaliar busca full-text para bases muito maiores, caso o volume real justifique.
- Atualizar documentação de deploy conforme o ambiente definitivo.

### Auditoria de performance realizada

- A listagem administrativa utiliza paginação de 15 registros e relacionamentos principais eager-loaded.
- O Kanban permanece limitado aos 100 chamados mais recentes em aberto, agora com relacionamento `latestMessage` e anexos pré-carregados.
- O relatório administrativo permanece paginado em 50 registros.
- A exportação CSV é processada em blocos de 500 registros durante o streaming da resposta; a exportação PDF continua limitada pela capacidade de renderização do formato.
- A busca global evita payloads desnecessários e entradas excessivamente longas; buscas com menos de dois caracteres retornam vazio.

### Próximas funcionalidades

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

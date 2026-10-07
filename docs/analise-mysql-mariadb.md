# Análise de compatibilidade com MySQL/MariaDB

**Data:** 7 de outubro de 2026  
**Status:** análise estática concluída; validação real pendente

## Resultado executivo

O ambiente atual do sandbox está configurado para SQLite e não possui um servidor MySQL/MariaDB escutando em `3306`. Portanto, a suíte automatizada confirma a estabilidade do projeto em SQLite, mas ainda não comprova a compatibilidade completa com o banco utilizado no ambiente local do usuário.

A análise estática não encontrou uso de SQL bruto claramente incompatível. As migrations utilizam principalmente recursos suportados pelo Laravel nos três bancos. Mesmo assim, há pontos que precisam de validação real antes de produção.

## Validações realizadas

- `DB_CONNECTION` atual: `sqlite`.
- `phpunit.xml` usa SQLite para os testes.
- Não foi encontrado servidor local escutando em `3306` ou `3307`.
- Suíte completa: **89 testes aprovados, 340 asserções**.
- Migrations, views, rotas e build frontend já foram validados no fluxo atual.
- Não foram expostas credenciais de banco durante a análise.

## Achado importante: disco dos anexos

A migration `2026_02_08_082631_upgrade_admin_features.php` cria a coluna `ticket_attachments.disk` com padrão `public`:

```php
$table->string('disk')->default('public')->after('size');
```

O fluxo ativo de upload, entretanto, grava novos arquivos explicitamente no disco privado `local`:

```php
$disk = 'local';
```

Isso não quebra os uploads novos, pois o valor é persistido como `local`. Porém, registros antigos ou registros criados por scripts/importações que dependam do valor padrão podem apontar para `public`. A correção deve ser feita com uma migration própria e um procedimento de migração física dos arquivos, nunca alterando uma migration já executada.

## Pontos específicos para MySQL/MariaDB

### 1. Foreign keys e ordem das migrations

As tabelas usam `foreignId()->constrained()` e ações de cascata/restrição. É necessário executar `migrate:fresh` em MySQL/MariaDB para confirmar a ordem, os nomes das tabelas e o comportamento de `restrictOnDelete` nos termos de ativos.

### 2. Alterações com `change()`

As migrations que alteram colunas existentes com `change()` precisam ser executadas no banco real. Devem ser conferidos especialmente:

- `ticket_messages.time_spent` como inteiro, não nulo e com padrão zero.
- `ticket_messages.user_id` nullable.
- Compatibilidade da versão do MySQL/MariaDB com os tipos gerados pelo Laravel.

### 3. ENUMs

Assets e termos de responsabilidade usam `enum`. O MySQL/MariaDB impõe os valores no banco, enquanto SQLite é mais permissivo. É necessário testar inserções e atualizações inválidas para confirmar que a aplicação sempre usa os valores definidos pelos Enums PHP.

### 4. Índices compostos

A migration de índices adiciona índices para notificações, anexos, visitas, Wiki e respostas prontas. Eles são compatíveis com MySQL/MariaDB, mas devem ser verificados com `SHOW INDEX` e `EXPLAIN` no banco real. Também é importante confirmar que migrations não sejam executadas duas vezes em uma instalação parcialmente atualizada.

### 5. Busca textual

As buscas usam `LIKE`. O resultado pode variar conforme collation e configuração de acentuação/case sensitivity. A collation do banco de produção deve ser definida conscientemente, preferencialmente uma variante `utf8mb4` adequada ao português brasileiro.

### 6. Tamanho de índices e texto

Os campos `text` e `longText` são adequados para mensagens e documentos, mas não devem entrar em índices. Os índices polimórficos e únicos das tags precisam ser confirmados com `SHOW CREATE TABLE`, principalmente em instalações antigas com charset diferente de `utf8mb4`.

## Procedimento recomendado no ambiente MySQL/MariaDB

Com um banco de teste descartável configurado, executar:

```bash
cp .env.example .env.mysql-test
# configurar DB_CONNECTION=mysql e as credenciais do banco de teste
php artisan config:clear
php artisan migrate:fresh --seed
php artisan test
php artisan migrate:status
```

Depois, verificar no MySQL/MariaDB:

```sql
SHOW TABLES;
SHOW CREATE TABLE tickets;
SHOW CREATE TABLE ticket_messages;
SHOW CREATE TABLE ticket_attachments;
SHOW INDEX FROM tickets;
SHOW INDEX FROM ticket_attachments;
```

Também devem ser exercidos manualmente os fluxos de criação de chamado, resposta, anexos, SLA, QR Code, termos digitais e relatórios.

## Conclusão

O projeto está estável na linha de base SQLite, mas a validação MySQL/MariaDB permanece pendente por falta de um servidor disponível neste ambiente. O maior ponto técnico identificado é a divergência histórica entre o padrão `public` da coluna `disk` e o fluxo atual privado `local`. Não foi feita alteração automática desse comportamento para evitar quebrar arquivos existentes ou o ambiente do usuário.

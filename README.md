# Suporte TI

Sistema web de suporte técnico, gestão de chamados e inventário de ativos desenvolvido em Laravel 12. A aplicação atende clientes, administradores e usuários Master, com foco em segurança, rastreabilidade, SLA, inventário e relatórios gerenciais.

> **Status atual:** em estabilização técnica, com os principais módulos funcionais implementados.
>
> **Última validação:** 129 testes aprovados e 514 asserções.

## Sumário

- [Visão geral](#visão-geral)
- [Módulos disponíveis](#módulos-disponíveis)
- [Perfis e permissões](#perfis-e-permissões)
- [Segurança](#segurança)
- [Inventário](#inventário)
- [Relatórios](#relatórios)
- [Tecnologias](#tecnologias)
- [Requisitos](#requisitos)
- [Instalação](#instalação-local)
- [Comandos úteis](#comandos-úteis)
- [Testes e validações](#testes-e-validações)
- [Documentação](#documentação)
- [Pendências conhecidas](#pendências-conhecidas)

## Visão geral

O Suporte TI centraliza o atendimento técnico e o controle patrimonial da empresa. O projeto possui áreas separadas para clientes, administradores e Master, com autorização por policies, middleware e validações nos controllers e FormRequests.

O locale de desenvolvimento é preservado conforme a configuração atual do ambiente. As strings da interface estão centralizadas nos catálogos de tradução, principalmente em `lang/pt_BR`.

## Módulos disponíveis

### Portal do cliente

- Dashboard com resumo dos chamados.
- Abertura, acompanhamento e histórico de chamados.
- Mensagens e anexos privados.
- Avaliação de atendimento e NPS.
- Base de Conhecimento/Wiki com artigos publicados.
- Sugestões de artigos durante a abertura de chamados.
- Perfil, senha, sessões e autenticação em dois fatores quando aplicável.

### Atendimento administrativo

- Lista de chamados e quadro Kanban.
- Filtros por status, prioridade, categoria e responsável.
- Atribuição, escalação e alteração de status.
- Mensagens públicas e notas internas.
- Checklists de atendimento.
- Tags e respostas prontas.
- Visitas técnicas.
- SLA, indicadores operacionais e escalonamento.
- Busca global de chamados e usuários.
- Relatórios de chamados em PDF e CSV.

### Inventário de ativos

- Cadastro, edição, consulta e exclusão lógica de ativos.
- QR Code individual para cada ativo.
- Termos digitais de responsabilidade com assinatura e geração de PDF.
- Histórico de movimentações e transferências.
- Associação a responsável, filial, setor e fornecedor.
- Importação de ativos por CSV com validação de todas as linhas e operação atômica.
- Dashboard analítico por status, tipo, localização, categoria e chamados abertos.
- Cadastro de filiais e setores com validação de compatibilidade.
- Gestão de fornecedores com status, dados comerciais e exclusão lógica.
- Gestão de garantias com vigência, fornecedor, contrato e nota fiscal.
- Gestão de contratos com valor, renovação automática, fornecedor e nota fiscal.
- Relatório financeiro consolidado por fornecedor.

### Área Master

- Dashboard de segurança.
- Gestão e supervisão de usuários privilegiados.
- Health check do sistema.
- Auditoria e resolução de incidentes escalados.
- Proteção adicional por autenticação em dois fatores.

## Perfis e permissões

| Recurso | Cliente | Admin | Master |
| --- | :---: | :---: | :---: |
| Criar e acompanhar os próprios chamados | Sim | Sim | Não operacional |
| Responder chamados próprios | Sim | Sim | Não operacional |
| Avaliar atendimento/NPS | Sim | Sim | Não operacional |
| Base de Conhecimento pública ao cliente | Sim | Sim | Não operacional |
| Gerenciar chamados administrativos | Não | Sim | Não operacional |
| Tags, respostas prontas e checklists | Não | Sim | Não operacional |
| Visitas técnicas e relatórios | Não | Sim | Não operacional |
| Inventário, ativos e transferências | Não | Sim | Não operacional |
| Fornecedores, garantias e contratos | Não | Sim | Não operacional |
| Dashboard financeiro do inventário | Não | Sim | Não operacional |
| Gestão de usuários privilegiados | Não | Não | Sim |
| Auditoria e health check | Não | Não | Sim |

As permissões são reforçadas por policies e autorização explícita. O fato de um usuário conhecer um identificador ou URL não concede acesso ao recurso.

## Segurança

- Proteção contra IDOR em chamados, ativos, histórico, anexos, notificações, Wiki, garantias e contratos.
- Bloqueio de fusão de chamados pertencentes a clientes diferentes.
- Autorização explícita em endpoints críticos de chamados.
- Anexos novos armazenados em disco privado (`local`).
- Download de anexos autorizado pelo chamado e pelo usuário.
- Nome de arquivo sanitizado nos headers de download.
- Providers de login social limitados a Google e GitHub.
- Clientes e usuários sem permissão recebem `403` nos módulos administrativos protegidos.
- Validação de fornecedor, filial e setor ativos nos formulários de ativos.
- Operações de garantia e contrato confirmam o ativo pai antes de editar ou excluir.
- Importação CSV validada antes da gravação; qualquer erro cancela o lote inteiro.
- Dados antigos continuam compatíveis sem localização ou fornecedor definidos.

## Inventário

A ficha administrativa do ativo concentra os recursos relacionados ao equipamento:

```text
/admin/assets
/admin/assets/{asset}
/admin/assets/{asset}/garantias/novo
/admin/assets/{asset}/contratos/novo
/admin/assets/{asset}/transferencia
/admin/assets/dashboard
/admin/assets/importar
```

O relatório financeiro pode ser acessado em:

```text
/admin/relatorios/inventario-financeiro
```

Ele apresenta ativos, garantias, contratos, valor contratado e situação dos contratos por fornecedor, com filtro individual.

## Relatórios

- Relatório operacional de chamados.
- Exportação PDF de chamados.
- Exportação CSV em fluxo, usando leitura lazy em blocos.
- Dashboard analítico de inventário.
- Relatório consolidado de custos, garantias e contratos por fornecedor.

## Tecnologias

| Camada | Tecnologia |
| --- | --- |
| Backend | PHP 8.2+ e Laravel 12 |
| Autenticação | Laravel Jetstream/Fortify, Sanctum e Socialite |
| Frontend | Blade, Livewire, Tailwind CSS e Alpine.js |
| Build | Vite e Node.js |
| Banco de dados | MySQL/MariaDB em produção; SQLite nos testes |
| PDF | DomPDF |
| Tempo real | Laravel Reverb |
| Testes | PHPUnit/Laravel Test Runner |
| Traduções | Catálogos em `lang/pt_BR` |

## Requisitos

- PHP 8.2 ou superior.
- Composer.
- Node.js e npm.
- MySQL/MariaDB ou SQLite.
- Extensões PHP utilizadas pelo Laravel e pelo projeto, incluindo GD quando o fluxo de imagens/QR Code exigir.

## Instalação local

```bash
# 1. Clonar o projeto
git clone https://github.com/duu-beat/TI.git
cd TI

# 2. Instalar dependências
composer install
npm install

# 3. Criar e configurar o ambiente
cp .env.example .env
php artisan key:generate
```

Configure no `.env` a conexão de banco, e-mail, filas e demais serviços necessários. Para um ambiente local simples, SQLite pode ser utilizado nos testes e no desenvolvimento.

```bash
# 4. Criar estrutura do banco
php artisan migrate

# 5. Compilar os assets para produção
npm run build

# 6. Iniciar o servidor local
php artisan serve
```

Para desenvolvimento com Vite:

```bash
npm run dev
```

O script completo de desenvolvimento do Composer também inicia servidor, fila, logs e Vite em conjunto:

```bash
composer run dev
```

## Comandos úteis

```bash
# Limpar caches
php artisan optimize:clear

# Compilar views e rotas
php artisan view:cache
php artisan route:cache

# Listar rotas
php artisan route:list

# Executar todos os testes
php artisan test

# Executar um teste específico
php artisan test tests/Feature/AssetContractTest.php

# Validar o build frontend
npm run build

# Verificar problemas básicos no diff
git diff --check
```

## Testes e validações

A última suíte validada apresentou:

- **129 testes aprovados**.
- **514 asserções**.
- 7 testes ignorados por funcionalidades opcionais já conhecidas.
- Views Blade compiladas.
- Rotas compiladas.
- Build Vite concluído.
- PHP lint executado nos arquivos alterados.
- `git diff --check` aprovado.

O build registra apenas um aviso não bloqueante sobre a versão do `caniuse-lite`.

Os testes cobrem autenticação, autorização, IDOR, chamados, anexos, inventário, filiais, setores, transferências, QR Code, termos digitais, fornecedores, garantias, contratos e relatório financeiro.

## Documentação

- [Estado atual](docs/estado-atual.md) — estado real, validações, segurança, pendências e roadmap.
- [Análise MySQL/MariaDB](docs/analise-mysql-mariadb.md) — auditoria estática de compatibilidade.
- [Acessibilidade implementada](ACESSIBILIDADE_IMPLEMENTADA.md).
- [Melhorias implementadas](MELHORIAS_IMPLEMENTADAS.md).
- [Planejamento de funcionalidades](PLANEJAMENTO_FUNCIONALIDADES.md).
- [Testes de funcionalidades](TESTES_FUNCIONALIDADES.md).
- [Testes de validação](TESTES_VALIDACAO.md).

## Pendências conhecidas

### Prioridade alta

- Validar migrations, índices e constraints em um servidor MySQL/MariaDB real.
- Configurar e testar filas, scheduler, e-mail e backup em ambiente de staging/deploy.

### Prioridade média

- Ampliar a cobertura de autorização de todos os recursos.
- Avaliar busca full-text se o volume real de dados justificar.
- Atualizar a documentação de deploy conforme o ambiente definitivo.

### Próximas funcionalidades

- Escalonamento configurável por prioridade e categoria.
- Integração oficial com WhatsApp ou Telegram, após definição do provedor e requisitos de LGPD.
- Recursos opcionais de IA controlados por configuração e limite de custo.

## Locale

O locale não deve ser alterado automaticamente. Mudanças em `APP_LOCALE`, `APP_FALLBACK_LOCALE`, `APP_FAKER_LOCALE` ou `config/app.php` devem ser tratadas separadamente e validadas no ambiente local antes da publicação.

## Licença

Este projeto é proprietário. Consulte os termos definidos pelo responsável pelo repositório.

# Planejamento de Novas Funcionalidades e Acessibilidade

## Data
08/02/2026

---

## 1. Novas Funcionalidades por Hierarquia

### 🟢 CLIENTE

#### A. Dashboard Melhorado
- **Cards de Estatísticas Pessoais**
  - Total de tickets abertos
  - Tickets resolvidos
  - Tempo médio de resposta
  - Última avaliação dada

- **Atalhos Rápidos**
  - Botão "Novo Chamado" destacado
  - Acesso rápido a tickets em andamento
  - Link para FAQ

- **Timeline de Tickets**
  - Últimos 5 tickets com status visual
  - Indicador de tempo desde última atualização

#### B. FAQ Interativo
- Seção de perguntas frequentes
- Busca por palavra-chave
- Categorias (Hardware, Software, Rede, etc.)
- Botão "Não resolveu? Abrir chamado"

#### C. Notificações
- Badge de notificações não lidas
- Lista de atualizações em tickets
- Notificação quando admin responde

---

### 🔵 ADMIN

#### A. Dashboard Avançado
- **Métricas de Performance**
  - Tickets resolvidos hoje/semana/mês
  - Taxa de resolução no primeiro contato
  - Tempo médio de resolução por categoria
  - Gráfico de tickets por status

- **Alertas e Prioridades**
  - Tickets com SLA vencido (destaque vermelho)
  - Tickets não atribuídos
  - Tickets aguardando resposta há mais de 24h

- **Meus Tickets**
  - Lista de tickets atribuídos a mim
  - Filtro rápido por status

#### B. Templates de Resposta Rápida
- Criar/editar templates personalizados
- Variáveis dinâmicas (nome do cliente, ID do ticket, etc.)
- Categorização de templates
- Uso com atalho de teclado

#### C. Exportação em Lote
- Exportar múltiplos tickets selecionados
- Formato PDF ou Excel
- Incluir anexos opcionalmente

#### D. Estatísticas por Agente
- Ranking de performance
- Tickets resolvidos por agente
- Avaliação média recebida
- Tempo médio de resposta

---

### 🔴 MASTER

#### A. Dashboard de Controle Total
- **Saúde do Sistema**
  - Status do servidor
  - Uso de memória/CPU
  - Erros recentes
  - Uptime

- **Auditoria Completa**
  - Log de todas as ações administrativas
  - Quem alterou o quê e quando
  - Filtros por usuário, data, ação

- **Visão Geral Executiva**
  - Total de usuários (clientes, admins)
  - Total de tickets (histórico completo)
  - Crescimento mensal
  - Gráficos de tendência

#### B. Configurações Avançadas
- Configurar tempos de SLA por prioridade
- Ativar/desativar funcionalidades
- Personalizar e-mails automáticos
- Gerenciar categorias e tags

#### C. Backup e Manutenção
- Botão de backup manual
- Histórico de backups
- Limpeza de logs antigos
- Otimização de banco de dados

#### D. Gerenciamento de Permissões
- Definir o que cada nível pode fazer
- Criar roles personalizados (futuro)
- Log de acessos por usuário

---

## 2. Personalização de Home/Dashboard

### Cliente (`/client/dashboard`)
**Layout:**
```
┌─────────────────────────────────────┐
│  Bem-vindo, [Nome]!                 │
│  [Botão: Novo Chamado]              │
├─────────────────────────────────────┤
│  📊 Minhas Estatísticas             │
│  ┌──────┐ ┌──────┐ ┌──────┐        │
│  │Abertos│ │Resolv│ │Tempo │        │
│  │   3   │ │  12  │ │ 4h   │        │
│  └──────┘ └──────┘ └──────┘        │
├─────────────────────────────────────┤
│  🎫 Meus Tickets Recentes           │
│  • #123 - Problema de rede [Novo]  │
│  • #122 - Impressora [Resolvido]   │
│  • #121 - E-mail [Em Andamento]    │
├─────────────────────────────────────┤
│  ❓ Precisa de Ajuda?               │
│  [Ver FAQ] [Contato Rápido]        │
└─────────────────────────────────────┘
```

### Admin (`/admin/dashboard`)
**Layout:**
```
┌─────────────────────────────────────┐
│  Dashboard Administrativo           │
├─────────────────────────────────────┤
│  ⚠️ Alertas                         │
│  • 5 tickets com SLA vencido        │
│  • 3 tickets não atribuídos         │
├─────────────────────────────────────┤
│  📊 Métricas Hoje                   │
│  ┌──────┐ ┌──────┐ ┌──────┐        │
│  │Resolv│ │Média │ │Satisf│        │
│  │  15  │ │ 2.5h │ │ 4.5★ │        │
│  └──────┘ └──────┘ └──────┘        │
├─────────────────────────────────────┤
│  📈 Gráfico: Tickets por Status     │
│  [Gráfico de barras/pizza]          │
├─────────────────────────────────────┤
│  🎫 Meus Tickets Atribuídos (8)     │
│  [Lista com filtros rápidos]        │
└─────────────────────────────────────┘
```

### Master (`/master/dashboard`)
**Layout:**
```
┌─────────────────────────────────────┐
│  Controle Central - Master          │
├─────────────────────────────────────┤
│  🟢 Sistema Operacional             │
│  Uptime: 99.8% | CPU: 45% | RAM: 2GB│
├─────────────────────────────────────┤
│  📊 Visão Geral                     │
│  ┌──────┐ ┌──────┐ ┌──────┐        │
│  │Usuár.│ │Ticket│ │Admins│        │
│  │  150 │ │ 1.2k │ │   5  │        │
│  └──────┘ └──────┘ └──────┘        │
├─────────────────────────────────────┤
│  📈 Crescimento Mensal              │
│  [Gráfico de linha]                 │
├─────────────────────────────────────┤
│  🔴 Erros Recentes (3)              │
│  • [Erro 1] - há 2h                 │
│  • [Erro 2] - há 5h                 │
├─────────────────────────────────────┤
│  👁️ Auditoria Recente               │
│  • Admin João alterou ticket #123   │
│  • Admin Maria criou tag "Urgente"  │
└─────────────────────────────────────┘
```

---

## 3. Recursos de Acessibilidade (WCAG 2.1 Nível AA)

### A. Navegação por Teclado
- **Tab Navigation**
  - Todos os elementos interativos acessíveis via Tab
  - Ordem lógica de foco
  - Indicador visual de foco (outline)

- **Atalhos de Teclado**
  - `Alt + N`: Novo chamado
  - `Alt + H`: Ir para home
  - `Alt + M`: Abrir menu
  - `Esc`: Fechar modais
  - `/`: Focar no campo de busca

- **Skip Links**
  - "Pular para conteúdo principal"
  - "Pular para menu"
  - Visível ao focar (Tab)

### B. Leitores de Tela (ARIA)
- **Labels Descritivos**
  - `aria-label` em ícones
  - `aria-labelledby` em seções
  - `aria-describedby` em campos de formulário

- **Roles Semânticos**
  - `role="navigation"` no menu
  - `role="main"` no conteúdo principal
  - `role="alert"` em notificações
  - `role="dialog"` em modais

- **Live Regions**
  - `aria-live="polite"` para notificações
  - `aria-live="assertive"` para alertas críticos

- **Estados**
  - `aria-expanded` em dropdowns
  - `aria-selected` em tabs
  - `aria-disabled` em botões desabilitados

### C. Contraste e Cores
- **Verificar Contraste**
  - Texto: mínimo 4.5:1
  - Texto grande: mínimo 3:1
  - Elementos interativos: mínimo 3:1

- **Não Depender Apenas de Cor**
  - Ícones + texto
  - Padrões + cores
  - Sublinhado em links

- **Modo de Alto Contraste**
  - Opção de ativar modo alto contraste
  - Salvar preferência no localStorage

### D. Textos e Conteúdo
- **Textos Alternativos**
  - `alt` em todas as imagens
  - Descrição significativa (não "imagem1.png")

- **Linguagem Clara**
  - Evitar jargões
  - Instruções simples
  - Mensagens de erro descritivas

- **Tamanho de Fonte**
  - Permitir zoom até 200% sem quebrar layout
  - Usar unidades relativas (rem, em)

### E. Formulários Acessíveis
- **Labels Associados**
  - `<label for="campo">` em todos os inputs
  - Placeholder não substitui label

- **Mensagens de Erro**
  - Associadas ao campo (`aria-describedby`)
  - Visíveis e claras
  - Cor + ícone

- **Validação**
  - Feedback imediato
  - Não apenas visual

### F. Modais e Popups
- **Foco Gerenciado**
  - Foco vai para modal ao abrir
  - Foco retorna ao elemento que abriu ao fechar
  - Esc fecha modal

- **Overlay Acessível**
  - `aria-modal="true"`
  - Bloqueia interação com conteúdo de fundo

---

## 4. Implementação Incremental

### Fase 1: Cliente (Prioridade Alta)
1. Dashboard melhorado
2. FAQ básico
3. Acessibilidade no menu e formulários

### Fase 2: Admin (Prioridade Alta)
1. Dashboard com métricas
2. Templates de resposta
3. Acessibilidade em tabelas e filtros

### Fase 3: Master (Prioridade Média)
1. Dashboard executivo
2. Auditoria
3. Configurações avançadas

### Fase 4: Acessibilidade Global (Prioridade Alta)
1. Skip links em todas as páginas
2. ARIA labels completos
3. Navegação por teclado
4. Testes com leitor de tela

---

## 5. Testes Planejados

### Testes Funcionais
- [ ] Cliente consegue ver dashboard personalizado
- [ ] Admin vê métricas corretas
- [ ] Master acessa auditoria
- [ ] FAQ funciona corretamente
- [ ] Notificações aparecem

### Testes de Acessibilidade
- [ ] Navegação completa por teclado
- [ ] Leitor de tela (NVDA/JAWS) funciona
- [ ] Contraste de cores adequado (WCAG AA)
- [ ] Zoom até 200% sem quebrar
- [ ] Skip links funcionam

### Testes de Regressão
- [ ] Funcionalidades antigas ainda funcionam
- [ ] Rotas não quebradas
- [ ] Permissões respeitadas
- [ ] Performance não degradada

---

## 6. Arquivos a Criar/Modificar

### Criar:
- `app/Http/Controllers/Client/FaqController.php`
- `app/Http/Controllers/Client/NotificationController.php`
- `resources/views/client/faq.blade.php`
- `resources/views/client/notifications.blade.php`
- `resources/views/components/skip-link.blade.php`
- `resources/views/components/accessible-modal.blade.php`
- `app/View/Components/SkipLink.php`

### Modificar:
- `resources/views/client/dashboard.blade.php`
- `resources/views/admin/dashboard.blade.php`
- `resources/views/master/dashboard.blade.php`
- `resources/views/layouts/app.blade.php` (adicionar skip links)
- `app/Http/Controllers/Client/DashboardController.php`
- `app/Http/Controllers/Admin/DashboardController.php` (criar se não existir)
- `app/Http/Controllers/Master/DashboardController.php`
- `routes/web.php` (novas rotas)

---

## 7. Priorização

### 🔴 Crítico (Fazer Primeiro)
1. Dashboard do Cliente melhorado
2. Skip links e navegação por teclado
3. ARIA labels básicos

### 🟡 Importante (Fazer em Seguida)
1. Dashboard do Admin com métricas
2. FAQ para cliente
3. Contraste de cores e alto contraste

### 🟢 Desejável (Fazer Depois)
1. Dashboard do Master completo
2. Templates de resposta
3. Auditoria avançada

---

## Próximo Passo

Começar pela **Fase 1: Cliente** com foco em:
1. Dashboard melhorado
2. FAQ básico
3. Acessibilidade fundamental (skip links, ARIA, teclado)

Testar cada implementação antes de avançar.

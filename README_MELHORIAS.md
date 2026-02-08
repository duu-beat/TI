# 🚀 Guia de Uso das Novas Funcionalidades

## 📦 Instalação e Configuração

### 1. Executar Migrations

As migrations adicionam novas tabelas e campos ao banco de dados:

```bash
php artisan migrate
```

**O que será criado:**
- Tabela `tags` para gerenciar etiquetas
- Tabela `taggables` para relacionamento polimórfico
- Campos de SLA na tabela `tickets` (sla_due_at, first_response_at, etc.)
- Campos de metadados na tabela `ticket_attachments` (mime_type, size, disk)

### 2. Popular Tags Iniciais

Execute o seeder para criar 10 tags pré-configuradas:

```bash
php artisan db:seed --class=TagSeeder
```

**Tags criadas:**
- 🔴 Urgente
- 🔵 Hardware
- 🟣 Software
- 🟢 Rede
- 🟠 E-mail
- 🟣 Impressora
- 🔴 Acesso
- 🟢 Treinamento
- 🔴 Bug
- 🟢 Melhoria

### 3. Limpar Cache

Após as mudanças, limpe todos os caches:

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### 4. Recompilar Assets (se necessário)

Se você modificou arquivos CSS/JS:

```bash
npm run build
# ou para desenvolvimento
npm run dev
```

---

## 🏷️ Sistema de Tags

### Como Usar

#### **Acessar Gerenciamento de Tags**
```
URL: /admin/tags
```

#### **Criar Nova Tag**
1. Clique no botão "Nova Tag"
2. Preencha:
   - **Nome**: Nome da tag (ex: "Urgente")
   - **Cor**: Escolha uma cor em hexadecimal (ex: #EF4444)
   - **Descrição**: Opcional, explica o uso da tag
3. Clique em "Criar Tag"

#### **Editar Tag**
1. Na lista de tags, clique em "Editar"
2. Modifique os campos desejados
3. Clique em "Salvar Alterações"

#### **Excluir Tag**
1. Clique em "Excluir"
2. Confirme a ação
3. A tag será removida de todos os tickets

#### **Atribuir Tags a Tickets**
No painel de visualização de um ticket (`/admin/chamados/{id}`), você poderá:
- Ver tags atuais
- Adicionar novas tags
- Remover tags existentes

---

## 🔍 Busca Avançada

### Como Usar

#### **Acessar Lista de Chamados**
```
URL: /admin/chamados
```

#### **Filtros Disponíveis**

**1. Busca Textual**
- Digite no campo de busca
- Busca em: ID, assunto, descrição e mensagens
- Exemplo: "impressora não funciona"

**2. Filtro por Status**
- Novo
- Em Andamento
- Aguardando Cliente
- Resolvido
- Fechado

**3. Filtro por Prioridade**
- Alta
- Média
- Baixa

**4. Filtro por Categoria**
- Selecione a categoria desejada

**5. Filtro por Responsável**
- Selecione o agente atribuído

**6. Filtro por Tags**
- Selecione uma ou mais tags

**7. Filtro por Data**
- **Data Inicial**: Tickets criados a partir desta data
- **Data Final**: Tickets criados até esta data

**8. Filtro por SLA Vencido**
- Marque para ver apenas tickets com prazo expirado

#### **Combinar Filtros**
Você pode usar múltiplos filtros simultaneamente para busca precisa.

**Exemplo:**
- Status: "Em Andamento"
- Prioridade: "Alta"
- Tag: "Urgente"
- Data: Últimos 7 dias

---

## 📊 Relatórios

### Como Usar

#### **Acessar Relatórios**
```
URL: /admin/relatorios
```

#### **Gerar Relatório**

**1. Aplicar Filtros**
- Data inicial e final
- Status
- Prioridade
- Categoria
- Responsável

**2. Visualizar Estatísticas**
O sistema mostra automaticamente:
- Total de chamados no período
- Tempo médio de resposta
- Tempo médio de resolução
- Avaliação média dos clientes

**3. Exportar**

**Exportar PDF:**
- Clique em "Exportar PDF"
- Arquivo será baixado automaticamente
- Contém: estatísticas + lista de tickets formatada

**Exportar Excel/CSV:**
- Clique em "Exportar Excel"
- Arquivo CSV será baixado
- Compatível com Excel, Google Sheets, LibreOffice
- Encoding UTF-8 com BOM

#### **Dados Exportados**
- ID do chamado
- Nome do cliente
- Assunto
- Categoria
- Status
- Prioridade
- Responsável
- Data de criação
- Tempo de resposta (minutos)
- Tempo de resolução (minutos)
- Avaliação

---

## ⏱️ Sistema de SLA

### Como Funciona

#### **Cálculo Automático**
Quando um ticket é criado, o sistema automaticamente:
1. Define o prazo de SLA baseado na prioridade:
   - **Alta**: 4 horas
   - **Média**: 24 horas
   - **Baixa**: 72 horas

2. Registra timestamps importantes:
   - **Criação**: Quando o ticket foi aberto
   - **Primeira Resposta**: Quando admin respondeu pela primeira vez
   - **Resolução**: Quando foi marcado como resolvido

3. Calcula tempos:
   - **Tempo de Resposta**: Criação → Primeira resposta
   - **Tempo de Resolução**: Criação → Resolução

#### **Visualizar Métricas de SLA**

**No Dashboard (`/admin/dashboard`):**
- Chamados com SLA vencido
- Chamados com vencimento hoje
- Tempo médio de resposta (todos os tickets)
- Tempo médio de resolução (todos os tickets)

**Na Lista de Tickets:**
- Indicador visual de SLA vencido (vermelho)
- Tempo restante até vencimento

**No Ticket Individual:**
- Prazo de SLA
- Status do SLA (dentro do prazo / vencido)
- Tempo de primeira resposta
- Tempo de resolução (se resolvido)

#### **Alertas de SLA**
O sistema destaca visualmente:
- 🔴 **Vencido**: SLA já passou do prazo
- 🟡 **Próximo do vencimento**: Menos de 25% do tempo restante
- 🟢 **Dentro do prazo**: Tempo suficiente

---

## 📎 Sistema de Anexos Melhorado

### Como Funciona

#### **Upload de Arquivos**
Ao criar ou responder um ticket:
1. Clique em "Anexar Arquivos"
2. Selecione um ou mais arquivos
3. O sistema automaticamente:
   - Valida o tipo de arquivo
   - Verifica o tamanho (máx 10MB)
   - Salva metadados (mime type, tamanho)
   - Gera nome único

#### **Tipos de Arquivo Suportados**
- 🖼️ **Imagens**: JPG, PNG, GIF, WebP
- 📄 **Documentos**: PDF, Word (.doc, .docx), Excel (.xls, .xlsx)
- 📦 **Compactados**: ZIP, RAR
- 📝 **Texto**: TXT

#### **Visualização de Anexos**

**Preview de Imagens:**
- Imagens são exibidas inline
- Clique para ampliar

**Outros Arquivos:**
- Ícone automático baseado no tipo
- Nome do arquivo
- Tamanho formatado (KB/MB)
- Botão de download

#### **Gerenciamento**
- Ver todos os anexos de um ticket
- Download individual
- Informações detalhadas (tipo, tamanho, data)

---

## 🎯 Casos de Uso Práticos

### **Cenário 1: Organizar Tickets por Tipo**
1. Crie tags: "Hardware", "Software", "Rede"
2. Ao receber um ticket, atribua a tag apropriada
3. Use o filtro de tags para ver apenas tickets de um tipo

### **Cenário 2: Identificar Gargalos**
1. Acesse Relatórios
2. Filtre por "Última semana"
3. Veja o tempo médio de resolução
4. Exporte para Excel e analise

### **Cenário 3: Monitorar SLA**
1. No dashboard, veja "SLA Vencidos"
2. Clique para ver a lista
3. Priorize esses tickets
4. Acompanhe a redução do número

### **Cenário 4: Relatório Mensal para Gestão**
1. Acesse Relatórios
2. Filtre: Último mês
3. Veja estatísticas gerais
4. Exporte PDF
5. Apresente para gestores

### **Cenário 5: Buscar Ticket Específico**
1. Acesse lista de tickets
2. Use busca textual: "impressora HP"
3. Combine com filtros: Status "Resolvido"
4. Encontre rapidamente o histórico

---

## 🔧 Troubleshooting

### **Erro ao executar migrations**
```bash
# Verificar status das migrations
php artisan migrate:status

# Executar migrations pendentes
php artisan migrate

# Se necessário, fazer rollback da última batch
php artisan migrate:rollback
```

### **Tags não aparecem**
```bash
# Verificar se o seeder foi executado
php artisan db:seed --class=TagSeeder

# Limpar cache
php artisan cache:clear
```

### **Relatórios não exportam**
```bash
# Verificar se o DomPDF está instalado
composer show barryvdh/laravel-dompdf

# Se não estiver, instalar
composer require barryvdh/laravel-dompdf
```

### **SLA não calcula**
```bash
# Verificar se as migrations foram executadas
php artisan migrate:status

# Limpar cache de configuração
php artisan config:clear
php artisan cache:clear
```

### **Anexos não fazem upload**
```bash
# Verificar permissões da pasta storage
chmod -R 775 storage
chown -R www-data:www-data storage

# Criar link simbólico se não existir
php artisan storage:link
```

---

## 📚 Referência de Rotas

### **Tags**
- `GET /admin/tags` - Listar tags
- `POST /admin/tags` - Criar tag
- `PUT /admin/tags/{id}` - Editar tag
- `DELETE /admin/tags/{id}` - Excluir tag
- `POST /admin/chamados/{id}/tags` - Atribuir tags a ticket

### **Relatórios**
- `GET /admin/relatorios` - Visualizar relatórios
- `GET /admin/relatorios/exportar-pdf` - Exportar PDF
- `GET /admin/relatorios/exportar-excel` - Exportar Excel

### **Tickets (Atualizadas)**
- `GET /admin/chamados` - Lista com filtros avançados
- `GET /admin/chamados/{id}` - Ver ticket (com SLA e tags)
- `GET /admin/dashboard` - Dashboard (com métricas SLA)

---

## 💡 Dicas e Boas Práticas

### **Tags**
- Use cores consistentes (ex: vermelho para urgente)
- Crie tags específicas mas não excessivas (10-20 é ideal)
- Revise periodicamente tags não utilizadas

### **Relatórios**
- Exporte semanalmente para acompanhamento
- Compare períodos para identificar tendências
- Use Excel para análises mais profundas

### **SLA**
- Ajuste os tempos no código se necessário (`SlaService.php`)
- Monitore diariamente os vencimentos
- Use como métrica de performance da equipe

### **Busca**
- Combine múltiplos filtros para precisão
- Salve filtros comuns como favoritos (futura feature)
- Use busca textual para encontrar por palavras-chave

---

## 🎓 Próximos Passos

Após dominar essas funcionalidades, considere:

1. **Criar dashboards personalizados** com as métricas mais relevantes
2. **Configurar alertas automáticos** para SLA próximo do vencimento
3. **Integrar com outras ferramentas** via API (futura implementação)
4. **Treinar a equipe** no uso das novas funcionalidades
5. **Coletar feedback** e sugerir melhorias

---

**Desenvolvido com ❤️ para otimizar seu suporte técnico**

Para dúvidas ou sugestões, consulte a documentação completa em `MELHORIAS_IMPLEMENTADAS.md`

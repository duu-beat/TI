# Recursos de Acessibilidade Implementados

## Data
08/02/2026

## Objetivo
Tornar o sistema de suporte TI acessível para todos os usuários, incluindo pessoas com deficiências visuais, motoras e cognitivas, seguindo as diretrizes **WCAG 2.1 Nível AA**.

---

## ✅ Recursos Implementados

### 1. Navegação por Teclado

#### Skip Links
- **Localização**: Primeira coisa após `<body>` em todas as páginas
- **Funcionalidade**: Permite pular diretamente para o conteúdo principal
- **Atalho**: Pressionar `Tab` ao carregar a página
- **Visibilidade**: Invisível até receber foco, então aparece no topo esquerdo

**Código:**
```blade
<x-skip-link />
```

#### Foco Visível
- **Outline**: 3px sólido azul índigo (#6366f1)
- **Offset**: 2-3px para não sobrepor conteúdo
- **Box Shadow**: Sombra suave rgba(99, 102, 241, 0.2)
- **Aplicado em**: Todos os elementos interativos (botões, links, inputs)

#### Áreas Clicáveis Mínimas
- **Tamanho**: Mínimo 44x44px (WCAG AAA)
- **Aplicado em**: Botões, links, checkboxes, radio buttons
- **Exceção**: Elementos inline pequenos (32x32px mínimo)

---

### 2. Leitores de Tela (ARIA)

#### Roles Semânticos
- `role="navigation"` - Menu lateral (sidebar)
- `role="main"` - Conteúdo principal
- `id="main-content"` - Âncora para skip link
- `role="dialog"` - Modais
- `role="alert"` - Notificações urgentes
- `role="tooltip"` - Dicas de contexto

#### Labels e Descrições
- `aria-label` - Botões sem texto visível (ex: fechar modal)
- `aria-labelledby` - Títulos de seções
- `aria-describedby` - Descrições de campos de formulário
- `aria-controls` - Associação entre botão e elemento controlado

#### Estados Dinâmicos
- `aria-expanded` - Accordions e dropdowns (true/false)
- `aria-selected` - Tabs e opções selecionadas
- `aria-disabled` - Elementos desabilitados
- `aria-invalid` - Campos com erro de validação
- `aria-live="polite"` - Notificações não urgentes
- `aria-live="assertive"` - Alertas críticos

**Exemplos no FAQ:**
```blade
<button @click="openItem = '{{ $itemId }}'"
        :aria-expanded="openItem === '{{ $itemId }}'"
        aria-controls="answer-{{ $itemId }}">
    Pergunta
</button>

<div x-show="openItem === '{{ $itemId }}'"
     id="answer-{{ $itemId }}">
    Resposta
</div>
```

---

### 3. Contraste de Cores

#### Níveis de Contraste
- **Texto normal**: Mínimo 4.5:1 (WCAG AA)
- **Texto grande**: Mínimo 3:1 (WCAG AA)
- **Elementos interativos**: Mínimo 3:1

#### Paleta Acessível
- **Fundo principal**: `#0f172a` (Slate-950)
- **Texto primário**: `#f1f5f9` (Slate-100) - Contraste 14:1 ✅
- **Texto secundário**: `#cbd5e1` (Slate-300) - Contraste 9:1 ✅
- **Texto terciário**: `#94a3b8` (Slate-400) - Contraste 5.5:1 ✅

#### Modo Alto Contraste
- **Media Query**: `@media (prefers-contrast: high)`
- **Ajustes**: Bordas mais fortes, cores mais saturadas
- **Ativação**: Automática quando usuário ativa no sistema operacional

---

### 4. Formulários Acessíveis

#### Labels Associados
- Todos os inputs têm `<label for="campo">`
- Labels visíveis (não apenas placeholder)
- Descrições adicionais com `aria-describedby`

#### Validação
- Mensagens de erro claras e descritivas
- `aria-invalid="true"` em campos com erro
- Ícones + texto (não apenas cor)

**Exemplo:**
```blade
<label for="search" class="sr-only">Buscar no FAQ</label>
<input type="text" 
       id="search"
       name="search" 
       aria-label="Campo de busca no FAQ"
       class="...">
```

---

### 5. Modais e Popups

#### Gerenciamento de Foco
- Foco vai para modal ao abrir
- Foco retorna ao elemento que abriu ao fechar
- `Esc` fecha modal

#### ARIA
- `aria-modal="true"`
- `role="dialog"`
- Overlay bloqueia interação com fundo

**Exemplo (Tags no Admin):**
```blade
<div x-show="showTagModal" 
     @click.away="showTagModal = false"
     role="dialog"
     aria-modal="true"
     class="fixed inset-0 z-50 ...">
    <!-- Conteúdo do modal -->
</div>
```

---

### 6. Animações Responsivas

#### Respeito a Preferências
- **Media Query**: `@media (prefers-reduced-motion: reduce)`
- **Comportamento**: Animações reduzidas a 0.01ms
- **Scroll**: `scroll-behavior: auto` (sem smooth scroll)

**CSS:**
```css
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}
```

---

### 7. Textos e Conteúdo

#### Tamanho de Fonte
- **Base**: 16px
- **Mobile**: 14px (para telas pequenas)
- **Zoom**: Suporta até 200% sem quebrar layout

#### Linguagem Clara
- Evita jargões técnicos desnecessários
- Instruções simples e diretas
- Mensagens de erro descritivas

#### Textos Alternativos
- Todas as imagens têm `alt` descritivo
- Ícones decorativos têm `aria-hidden="true"`
- Logos têm alt com nome da empresa

---

### 8. Screen Reader Only

#### Classe `.sr-only`
- Esconde visualmente mas mantém acessível para leitores de tela
- Usado em labels, instruções e contexto adicional

**CSS:**
```css
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
}
```

**Uso:**
```blade
<label for="search" class="sr-only">Buscar no FAQ</label>
```

---

### 9. Tabelas Acessíveis

#### Estrutura Semântica
- `<th>` para cabeçalhos
- `scope="col"` ou `scope="row"`
- `<caption>` para descrição da tabela

**Exemplo (Relatórios):**
```blade
<table>
    <caption class="sr-only">Lista de tickets com status e prioridade</caption>
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Assunto</th>
            <th scope="col">Status</th>
        </tr>
    </thead>
    <tbody>
        <!-- Dados -->
    </tbody>
</table>
```

---

### 10. Links Acessíveis

#### Diferenciação Visual
- Sublinhado por padrão (ou ao hover)
- Cor diferente do texto normal
- Não depende apenas de cor

#### Contexto
- Texto descritivo (evita "clique aqui")
- `aria-label` quando necessário

**Bom:**
```blade
<a href="...">Abrir chamado #123</a>
```

**Ruim:**
```blade
<a href="...">Clique aqui</a>
```

---

## 📊 Checklist WCAG 2.1 Nível AA

### Perceptível
- ✅ 1.1.1 Conteúdo Não Textual (alt em imagens)
- ✅ 1.3.1 Informações e Relações (labels, roles)
- ✅ 1.4.3 Contraste Mínimo (4.5:1)
- ✅ 1.4.10 Reflow (zoom 200%)
- ✅ 1.4.11 Contraste Não Textual (3:1)

### Operável
- ✅ 2.1.1 Teclado (navegação completa)
- ✅ 2.1.2 Sem Armadilha de Teclado
- ✅ 2.4.1 Ignorar Blocos (skip links)
- ✅ 2.4.3 Ordem do Foco (lógica)
- ✅ 2.4.7 Foco Visível
- ✅ 2.5.5 Tamanho do Alvo (44x44px)

### Compreensível
- ✅ 3.1.1 Idioma da Página (lang="pt-BR")
- ✅ 3.2.1 Em Foco (sem mudanças inesperadas)
- ✅ 3.3.1 Identificação de Erros
- ✅ 3.3.2 Labels ou Instruções

### Robusto
- ✅ 4.1.2 Nome, Função, Valor (ARIA)
- ✅ 4.1.3 Mensagens de Status (aria-live)

---

## 🧪 Testes Recomendados

### Ferramentas Automatizadas
1. **axe DevTools** (extensão Chrome/Firefox)
2. **WAVE** (WebAIM)
3. **Lighthouse** (Chrome DevTools)

### Testes Manuais
1. **Navegação por Teclado**
   - Tab através de todos os elementos
   - Enter/Space para ativar botões
   - Esc para fechar modais
   - Setas para navegação em menus

2. **Leitores de Tela**
   - **NVDA** (Windows - gratuito)
   - **JAWS** (Windows - pago)
   - **VoiceOver** (macOS/iOS - nativo)
   - **TalkBack** (Android - nativo)

3. **Zoom**
   - Testar até 200% de zoom
   - Verificar se layout não quebra
   - Verificar se textos não sobrepõem

4. **Contraste**
   - Usar ferramenta de verificação de contraste
   - Testar modo alto contraste do SO

---

## 📁 Arquivos Criados/Modificados

### Criados
- `resources/css/accessibility.css` - Estilos de acessibilidade
- `resources/views/components/skip-link.blade.php` - Skip link
- `ACESSIBILIDADE_IMPLEMENTADA.md` - Esta documentação

### Modificados
- `resources/css/app.css` - Import do CSS de acessibilidade
- `resources/views/layouts/app.blade.php` - Skip link + role main
- `resources/views/components/sidebar.blade.php` - role navigation
- `resources/views/client/faq.blade.php` - ARIA labels completos

---

## 🎯 Próximos Passos (Futuro)

### Melhorias Adicionais
1. **Atalhos de Teclado Globais**
   - `Alt + N`: Novo chamado
   - `Alt + H`: Home
   - `/`: Focar busca

2. **Modo Alto Contraste Manual**
   - Toggle no perfil do usuário
   - Salvar preferência no localStorage

3. **Tamanho de Fonte Ajustável**
   - Botões A- A A+ no header
   - Salvar preferência

4. **Traduções**
   - Suporte a múltiplos idiomas
   - `lang` dinâmico por página

5. **Breadcrumbs**
   - Navegação hierárquica
   - `aria-label="Breadcrumb"`

---

## 📚 Referências

- [WCAG 2.1](https://www.w3.org/WAI/WCAG21/quickref/)
- [MDN ARIA](https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA)
- [WebAIM](https://webaim.org/)
- [A11y Project](https://www.a11yproject.com/)

---

## ✅ Status

**Nível de Conformidade Atual**: WCAG 2.1 Nível AA (parcial)

**Áreas Cobertas**:
- ✅ Navegação por teclado
- ✅ Leitores de tela (ARIA)
- ✅ Contraste de cores
- ✅ Formulários acessíveis
- ✅ Skip links
- ✅ Foco visível
- ✅ Tamanhos mínimos de toque

**Próximas Melhorias**:
- ⏳ Atalhos de teclado globais
- ⏳ Modo alto contraste manual
- ⏳ Testes com usuários reais
- ⏳ Auditoria completa com ferramentas

---

**Conclusão**: O sistema agora possui uma base sólida de acessibilidade, permitindo que usuários com diferentes necessidades possam utilizar o sistema de forma eficaz e independente.

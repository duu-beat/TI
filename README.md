# 🛠️ Suporte TI : Portal de Atendimento e Gestão de Chamados

![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-%2300f.svg?style=for-the-badge&logo=mysql&logoColor=white)
![Vite](https://img.shields.io/badge/vite-%23646CFF.svg?style=for-the-badge&logo=vite&logoColor=white)
![WCAG 2.1 AA](https://img.shields.io/badge/Accessibility-WCAG%202.1%20AA-green.svg?style=for-the-badge)

> **Status do Projeto** : 🚀 **Concluído / Produção**

Sistema web completo de suporte técnico focado em organização, comunicação clara e uma interface moderna. Desenvolvido para resolver problemas reais de gestão interna e atendimento ao cliente, agora com recursos avançados de SLA, acessibilidade e métricas de performance.

---

## 📌 Sumário
* [Visão Geral](#-visão-geral)
* [Funcionalidades](#-funcionalidades)
* [Hierarquia de Acesso](#-hierarquia-de-acesso)
* [Acessibilidade (WCAG 2.1 AA)](#-acessibilidade-wcag-21-aa)
* [Tecnologias](#-tecnologias)
* [Instalação](#-instalação-local)
* [Documentação Técnica](#-documentação-técnica)

---

## 🚀 Visão Geral

O **Suporte TI** é uma plataforma centralizada onde a eficiência encontra a simplicidade. O fluxo foi desenhado para separar camadas de acesso, garantindo segurança e produtividade:

* **👤 Clientes** : Autonomia para criar, gerenciar e acompanhar chamados com suporte a autoatendimento via FAQ.
* **🛡️ Administradores** : Controle total da fila de atendimento, métricas de SLA e ranking de performance.
* **🔐 Segurança (Master)** : Gestão de usuários, logs de auditoria e configurações globais do sistema.

---

## ✨ Funcionalidades

### 👤 Portal do Cliente
* **Dashboard** : Resumo visual de chamados abertos, em andamento e resolvidos.
* **FAQ Interativo** : Base de conhecimento com 15+ perguntas frequentes para reduzir tickets simples.
* **Gestão de Chamados** : Abertura rápida com anexos e histórico completo de interação.
* **Feedback Visual** : Skeleton loaders para uma experiência de carregamento fluida.

### 🛡️ Painel Administrativo
* **Gestão de Fila** : Atendimento organizado por prioridade, categoria e tags.
* **Métricas de SLA** : Controle automático de prazos (4h/24h/72h) com alertas de vencimento.
* **Ranking de Agentes** : Gamificação com top 5 agentes baseado em taxa de resolução e avaliações.
* **Relatórios Avançados** : Exportação de dados em PDF e Excel com filtros personalizados.
* **Sistema de Tags** : Organização flexível de chamados por etiquetas coloridas.
* **Respostas Prontas** : Agilidade no atendimento com templates pré-configurados.

---

## 👥 Hierarquia de Acesso

O sistema possui uma separação rígida de permissões e interfaces personalizadas:

| Funcionalidade | Cliente | Admin | Master |
| :--- | :---: | :---: | :---: |
| Abrir/Ver Chamados | ✅ | ✅ | ✅ |
| FAQ e Autoatendimento | ✅ | ✅ | ✅ |
| Alterar Status/Responsável | ❌ | ✅ | ✅ |
| Gerenciar Tags e Relatórios | ❌ | ✅ | ✅ |
| Notas Internas | ❌ | ✅ | ✅ |
| Ranking de Agentes | ❌ | ✅ | ✅ |
| Gestão de Usuários | ❌ | ❌ | ✅ |
| Logs de Auditoria | ❌ | ❌ | ✅ |

---

## ♿ Acessibilidade (WCAG 2.1 AA)

O sistema foi reconstruído para ser inclusivo, alcançando o nível **AA** das diretrizes WCAG:
* **Navegação por Teclado** : 100% operável via teclado com foco visível aprimorado.
* **Skip Links** : Atalhos para pular navegação e ir direto ao conteúdo principal.
* **Suporte a Leitores de Tela** : Implementação completa de ARIA Labels e Roles semânticos.
* **Contraste Validado** : Paleta de cores com contraste mínimo de 4.5:1.
* **Redução de Movimento** : Respeito à preferência `prefers-reduced-motion` do usuário.

---

## 🧱 Tecnologias

| Camada | Tecnologia |
| :--- | :--- |
| **Backend** | Laravel 11/12 |
| **Frontend** | Blade Components + Tailwind CSS + Alpine.js |
| **Autenticação** | Laravel Jetstream (Fortify) |
| **Database** | MySQL / SQLite |
| **Build Tool** | Vite |
| **Acessibilidade** | WCAG 2.1 AA Standards |

---

## 📦 Instalação Local

```bash
# 1. Clone o repositório
git clone https://github.com/duu-beat/TI.git

# 2. Instale as dependências
composer install
npm install

# 3. Configure o ambiente
cp .env.example .env
php artisan key:generate

# 4. Prepare o banco de dados e sementes
php artisan migrate
php artisan db:seed --class=TagSeeder

# 5. Inicie os motores
php artisan serve
npm run dev
```

---

## 📚 Documentação Técnica

Para detalhes específicos sobre as implementações, consulte os documentos na raiz do projeto:
* [Relatório de Documentação](RELATORIO_DOCUMENTACAO.md) - Detalhes dos comentários e DocBlocks.
* [Acessibilidade Implementada](ACESSIBILIDADE_IMPLEMENTADA.md) - Guia completo de recursos WCAG.
* [Resumo de Melhorias](MELHORIAS_IMPLEMENTADAS.md) - Detalhes técnicos das 5 grandes funcionalidades.
* [Skeleton Loaders](SKELETONS_IMPLEMENTADOS.md) - Guia dos componentes de carregamento.

---

## 📄 Licença

Este projeto está sob a licença do proprietário. Consulte o arquivo LICENSE para mais detalhes.

---
**Desenvolvido com ❤️ para a gestão eficiente de TI.**

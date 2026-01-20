# 🛠️ Suporte TI : Portal de Atendimento e Gestão de Chamados

![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-%2300f.svg?style=for-the-badge&logo=mysql&logoColor=white)
![Vite](https://img.shields.io/badge/vite-%23646CFF.svg?style=for-the-badge&logo=vite&logoColor=white)

> **Status do Projeto** : 🚀 Em desenvolvimento / Funcional

Sistema web completo de suporte técnico focado em organização, comunicação clara e uma interface moderna. Desenvolvido para resolver problemas reais de gestão interna e atendimento ao cliente.

---

## 📌 Sumário
* [Visão Geral](#-visão-geral)
* [Funcionalidades](#-funcionalidades)
* [Tecnologias](#-tecnologias)
* [Layout & UX](#-layout--ux)
* [Instalação](#-instalação-local)
* [Roadmap](#-próximos-passos-roadmap)

---

## 🚀 Visão Geral

O **Suporte TI** é uma plataforma centralizada onde a eficiência encontra a simplicidade. O fluxo foi desenhado para separar camadas de acesso, garantindo segurança e produtividade:

* **👤 Clientes** : Autonomia para criar, gerenciar e acompanhar chamados.
* **🛡️ Administradores** : Controle total da fila de atendimento e métricas em tempo real.
* **🔐 Segurança** : Separação rígida de permissões via Middleware.

---

## ✨ Funcionalidades

### 🔓 Área Pública
* Landing Page institucional com foco em conversão.
* Exibição de serviços e portfólio.
* Fluxo de cadastro de novos clientes intuitivo.

### 👤 Portal do Cliente
* **Dashboard** : Resumo de atividades recentes.
* **Chamados** : Abertura rápida e histórico completo de interação.
* **Status** : Acompanhamento visual (Novo, Em andamento, Resolvido).

### 🛡️ Painel Administrativo
* **Gestão de Fila** : Atendimento organizado por prioridade e cliente.
* **Métricas** : Visualização rápida de desempenho da equipe.
* **Controle de Status** : Atualização em tempo real do progresso do suporte.

---

## 🧱 Estrutura do Projeto

O projeto utiliza o que há de mais moderno no ecossistema PHP e Laravel:

| Camada | Tecnologia |
| :--- | :--- |
| **Backend** | Laravel 10/11 |
| **Frontend** | Blade Components + Tailwind CSS |
| **Autenticação** | Laravel Jetstream (Fortify) |
| **Database** | MySQL / SQLite (Dev) |
| **Build Tool** | Vite |

---

## 🖥️ Layout & UX

O sistema foi concebido com uma estética **High-Tech Dark Mode**, priorizando:
* **Sidebar Dinâmica** : Navegação fluida entre módulos.
* **Feedback Visual** : Modais customizados para ações críticas.
* **Responsividade** : Interface adaptável para qualquer dispositivo.

---

## 📦 Instalação Local

```bash
# 1. Clone o repositório
git clone [https://github.com/seu-usuario/suporte-ti.git](https://github.com/seu-usuario/suporte-ti.git)

# 2. Acesse a pasta
cd suporte-ti

# 3. Instale as dependências
composer install
npm install

# 4. Configure o ambiente
cp .env.example .env
php artisan key:generate

# 5. Prepare o banco de dados
php artisan migrate

# 6. Inicie os motores
php artisan serve
npm run dev

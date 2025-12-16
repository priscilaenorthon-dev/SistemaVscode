# O Que Este Sistema Pode Fazer?

## Visão Geral
Este é um **Sistema de Gestão de Ferramentaria** desenvolvido em PHP + MySQL que oferece controle completo sobre estoque de ferramentas, empréstimos, devoluções e relatórios gerenciais.

---

## 🎯 Funcionalidades Principais

### 1. **Dashboard Interativo e Personalizado**

#### Para Administradores e Operadores:
- **KPIs Clicáveis em Tempo Real:**
  - Total de ferramentas cadastradas
  - Ferramentas disponíveis para empréstimo
  - Ferramentas atualmente emprestadas
  - Ferramentas em manutenção/calibração
  - Usuários ativos no sistema

- **Navegação Intuitiva:**
  - Clique nos cards de KPIs para navegar diretamente para a seção relacionada
  - Estatísticas atualizadas em tempo real

#### Para Usuários Comuns:
- **Dashboard Personalizado** com visão das próprias atividades:
  - Total de empréstimos realizados
  - Empréstimos ativos (em aberto)
  - Quantidade de ferramentas diferentes já utilizadas
  - Total de itens retirados
  - Itens pendentes de devolução
  - Histórico dos últimos 10 empréstimos

- **Tema Claro/Escuro:**
  - Toggle no navbar para alternar entre temas
  - Preferência salva no navegador

---

### 2. **Gestão Completa de Ferramentas**

#### Cadastro e Controle:
- **Informações Detalhadas:**
  - Código único da ferramenta
  - Descrição completa
  - Categoria (ex: Ferramentas Manuais, Elétricas, Medição)
  - Modelo/Fabricante
  - Local de armazenamento
  - Quantidade total no estoque
  - Quantidade disponível (atualizada automaticamente)
  - Status: Disponível, Emprestada, Em Manutenção

#### Busca e Filtros Avançados:
- **Busca por múltiplos critérios:**
  - Código da ferramenta
  - Descrição
  - Fabricante
  - Local de armazenamento
  
- **Filtros:**
  - Status (disponível, emprestada, manutenção)
  - Categoria
  - Fabricante
  - Local

- **Ordenação:**
  - Por código
  - Por descrição
  - Por categoria
  - Por data de cadastro (mais recentes primeiro)

#### Recursos Adicionais:
- **Visualização Detalhada:**
  - Histórico completo de empréstimos da ferramenta
  - Quem pegou, quando e por quanto tempo
  
- **Exportação de Dados:**
  - Exportar lista de ferramentas para Excel/CSV
  
- **Soft Delete:**
  - Ferramentas podem ser excluídas logicamente (não são perdidas)
  - Administradores podem visualizar itens excluídos
  - Possibilidade de restaurar ferramentas excluídas
  
- **Edição com Validações:**
  - Sistema valida quantidade mínima durante edição
  - Impede alterações que causem inconsistências no estoque

---

### 3. **Sistema de Empréstimos Inteligente**

#### Registro de Empréstimos:
- **Processo Simplificado:**
  - Seleção do colaborador que vai retirar
  - Seleção de múltiplas ferramentas simultaneamente
  - Definição de quantidade para cada ferramenta
  - Formato flexível: `CODIGO:QTD CODIGO:QTD` (ex: `FER001:5 FER002:3`)

- **Validações Automáticas:**
  - Verifica disponibilidade em estoque antes de confirmar
  - Impede empréstimos de quantidades maiores que o disponível
  - Valida se ferramentas existem e estão ativas
  - Controle automático de estoque (diminui quantidade disponível)

#### Controle de Devoluções:
- **Devolução Simplificada:**
  - Lista de empréstimos em aberto
  - Devolução completa do empréstimo
  - Registro de condição das ferramentas devolvidas
  - Restauração automática do estoque

#### Impressão:
- **Termo de Responsabilidade:**
  - Geração automática de documento para impressão
  - Inclui todos os itens emprestados
  - Data e responsáveis
  - Pronto para assinatura do colaborador

#### Histórico e Rastreabilidade:
- **Registro Completo:**
  - Data e hora do empréstimo
  - Operador que registrou
  - Colaborador que retirou
  - Itens e quantidades
  - Status (aberto/fechado)
  - Data de devolução

---

### 4. **Relatórios e Estatísticas Avançadas**

#### Filtros por Período:
- Selecione data inicial e final
- Relatórios personalizados por intervalo de tempo
- Período padrão: último mês

#### Estatísticas Disponíveis:
- **Totais:**
  - Total de empréstimos no período
  - Empréstimos abertos (pendentes)
  - Empréstimos fechados (concluídos)
  - Ferramentas diferentes emprestadas
  - Empréstimos antigos (mais de 7 dias em aberto)

- **Rankings:**
  - **Top 10 Ferramentas Mais Emprestadas**
    - Código e descrição
    - Quantidade de vezes emprestada
    
  - **Top 10 Usuários Mais Ativos**
    - Nome, matrícula e setor
    - Quantidade de empréstimos realizados

- **Gráficos Visuais:**
  - Gráfico de distribuição de empréstimos (Chart.js)
  - Visualização clara de padrões de uso

#### Impressão de Relatórios:
- Relatórios podem ser impressos
- Formatação adequada para documentação

---

### 5. **Gestão de Usuários (Apenas Administradores)**

#### Cadastro e Edição:
- **Informações Completas:**
  - Nome completo
  - Email (usado para login)
  - Senha (criptografada com bcrypt)
  - Matrícula
  - Setor/Departamento
  - Nível de acesso
  - Status (ativo/inativo)

#### Níveis de Acesso:
- **Administrador:**
  - Acesso total ao sistema
  - Gestão de usuários
  - Gestão de ferramentas
  - Registro de empréstimos e devoluções
  - Relatórios completos
  - Auditoria
  - Manutenção

- **Operador:**
  - Gestão de ferramentas
  - Registro de empréstimos e devoluções
  - Relatórios
  - Manutenção

- **Usuário Comum:**
  - Dashboard personalizado
  - Visualização do próprio histórico
  - Estatísticas pessoais

#### Controle de Status:
- Ativar/desativar usuários
- Usuários inativos não podem fazer login
- Soft delete: usuários excluídos podem ser restaurados

---

### 6. **Auditoria Completa (Administradores)**

#### Registro de Ações:
O sistema mantém um log detalhado de todas as ações importantes:
- Criação de usuários
- Criação de ferramentas
- Edição de ferramentas
- Exclusão/restauração de itens
- Registro de empréstimos
- Devoluções

#### Consulta de Logs:
- **Busca por:**
  - Tipo de ação
  - Entidade afetada (user, tool, loan)
  - Detalhes da operação
  
- **Filtros:**
  - Por tipo de entidade
  - Por ação específica
  
- **Informações Registradas:**
  - Data e hora da ação
  - Usuário que executou
  - Tipo de ação
  - Detalhes em JSON
  - Entidade afetada

---

### 7. **Controle de Manutenção e Calibração**

#### Gestão de Manutenções:
- **Agendamento:**
  - Registro de ferramentas em manutenção
  - Ferramentas em calibração
  - Data agendada
  - Status (pendente/concluído)

- **Listagem:**
  - Visualização de ferramentas em manutenção
  - Ordenadas por data de agendamento
  - Código e descrição da ferramenta
  - Status da manutenção

#### Integração com Estoque:
- Ferramentas em manutenção são marcadas no sistema
- Status refletido no dashboard e relatórios
- Controle de disponibilidade atualizado

---

### 8. **Segurança e Proteção**

#### Autenticação:
- **Login Seguro:**
  - Email e senha
  - Senhas criptografadas com bcrypt
  - Validação de credenciais
  
- **Sessões Protegidas:**
  - Controle de sessão PHP
  - Redirecionamento automático para login se não autenticado
  - Logout seguro

#### Proteção contra Ataques:
- **SQL Injection:**
  - Uso de PDO com prepared statements
  - Todos os queries parametrizados
  
- **CSRF (Cross-Site Request Forgery):**
  - Tokens CSRF em todos os formulários
  - Validação de tokens em ações críticas
  
- **Validação de Dados:**
  - Sanitização de inputs
  - Validação de emails
  - Filtros de segurança

#### Controle de Acesso:
- Verificação de nível de usuário em cada rota
- Usuários só acessam recursos permitidos ao seu nível
- Proteção em nível de controller

---

### 9. **Interface e Experiência do Usuário**

#### Design Moderno:
- **Bootstrap 5.3:**
  - Interface responsiva
  - Funciona em desktop, tablet e mobile
  - Cards modernos e intuitivos
  
- **Ícones Bootstrap:**
  - Iconografia clara e consistente
  - Facilita identificação de ações
  
- **Fonte Inter (Google Fonts):**
  - Tipografia moderna e legível
  - Experiência visual profissional

#### Recursos Visuais:
- **Feedback Visual:**
  - Mensagens de sucesso/erro
  - Confirmações de ações
  - Loading indicators
  
- **Validações em Tempo Real:**
  - Campos obrigatórios marcados
  - Validação de formatos
  - Mensagens de erro claras

- **Tema Claro/Escuro:**
  - Toggle no navbar
  - Preferência salva localmente
  - Transição suave entre temas

---

### 10. **Importação e Exportação de Dados**

#### Exportação:
- **Excel/CSV:**
  - Lista de ferramentas
  - Relatórios
  - Dados formatados para análise externa

#### Backups do Banco de Dados:
Dois arquivos SQL disponíveis na pasta `/bd`:

- **banco_zerado.sql:**
  - Estrutura completa do banco
  - Apenas usuário admin padrão
  - Ideal para começar do zero
  - Recomendado para produção

- **banco_com_dados.sql:**
  - Estrutura + dados de exemplo
  - Ferramentas de teste
  - Usuários de teste
  - Empréstimos de exemplo
  - Ideal para testes e demonstrações

---

## 🔧 Requisitos Técnicos

### Servidor:
- PHP 7.4 ou superior
- Extensão PDO habilitada
- MySQL ou MariaDB

### Cliente:
- Navegador moderno (Chrome, Firefox, Edge, Safari)
- JavaScript habilitado
- Conexão à internet (para fontes e CDNs)

### Recomendações:
- XAMPP (facilita instalação do Apache + MySQL + PHP)
- phpMyAdmin (para gestão do banco)

---

## 📦 Como Instalar

### Passo 1: Copiar Arquivos
```bash
# Copie o projeto para htdocs do XAMPP
C:\xampp\htdocs\SistemaVscode
```

### Passo 2: Iniciar Serviços
- Abra o Painel de Controle do XAMPP
- Inicie Apache
- Inicie MySQL

### Passo 3: Criar Banco de Dados
1. Acesse http://localhost/phpmyadmin
2. Crie um banco chamado `sistemavscode`
3. Importe um dos arquivos:
   - `bd/banco_zerado.sql` (começar do zero)
   - `bd/banco_com_dados.sql` (com dados de teste)

### Passo 4: Configurar Conexão
Verifique `config/database.php`:
```php
$dbHost = 'localhost';
$dbName = 'sistemavscode';
$dbUser = 'root';
$dbPass = ''; // Sem senha por padrão no XAMPP
```

### Passo 5: Acessar
- URL: http://localhost/SistemaVscode/public
- Ou: http://localhost/SistemaVscode (redireciona automaticamente)

---

## 🔑 Credenciais de Teste

### Administrador (Acesso Total):
- **Email:** admin@empresa.com
- **Senha:** password

### Operador:
- **Email:** operador@empresa.com
- **Senha:** password

### Usuário Comum:
- **Email:** usuario@usuario.com.br
- **Senha:** password

---

## 🎯 Casos de Uso Práticos

### Caso 1: Registrar Empréstimo de Múltiplas Ferramentas
1. Acesse "Empréstimos" > "Novo Empréstimo"
2. Selecione o colaborador
3. Digite os códigos: `FER001:2 FER005:1 FER010:3`
4. Clique em "Registrar"
5. Sistema valida estoque e registra
6. Imprima o termo de responsabilidade
7. Solicite assinatura do colaborador

### Caso 2: Devolver Ferramentas
1. Acesse "Empréstimos" > Aba "Em Aberto"
2. Encontre o empréstimo
3. Clique em "Devolver"
4. Confirme a devolução
5. Sistema restaura estoque automaticamente

### Caso 3: Gerar Relatório Mensal
1. Acesse "Relatórios"
2. Selecione período (ex: 01/12/2025 a 31/12/2025)
3. Visualize estatísticas
4. Veja rankings de ferramentas e usuários
5. Imprima ou exporte para análise

### Caso 4: Adicionar Nova Ferramenta
1. Acesse "Ferramentas" > "Nova Ferramenta"
2. Preencha dados (código, descrição, categoria, etc.)
3. Defina quantidade total
4. Salve
5. Ferramenta fica disponível para empréstimo

### Caso 5: Criar Novo Usuário
1. Admin acessa "Usuários" > "Novo Usuário"
2. Preenche nome, email, matrícula, setor
3. Define senha temporária
4. Seleciona nível de acesso
5. Salva
6. Usuário recebe credenciais e pode acessar

---

## 📊 Tecnologias Utilizadas

### Backend:
- **PHP 7.4+** (Arquitetura MVC)
- **MySQL/MariaDB** (Banco de dados)
- **PDO** (Conexão segura com DB)
- **bcrypt** (Criptografia de senhas)

### Frontend:
- **HTML5 + CSS3**
- **Bootstrap 5.3** (Framework CSS)
- **Bootstrap Icons** (Iconografia)
- **Chart.js** (Gráficos interativos)
- **JavaScript Vanilla** (Funcionalidades dinâmicas)
- **Google Fonts - Inter** (Tipografia)

### Segurança:
- **Prepared Statements** (Anti SQL Injection)
- **CSRF Tokens** (Anti CSRF)
- **Password Hashing** (bcrypt)
- **Validação de Sessão**
- **Controle de Acesso por Nível**

---

## 🚀 Diferenciais do Sistema

1. **Controle de Quantidade:** Não apenas rastreia ferramentas, mas também quantidades específicas de cada uma
2. **Multi-nível de Acesso:** Dashboards e permissões diferentes para cada tipo de usuário
3. **Auditoria Completa:** Rastreabilidade total de todas as ações
4. **Interface Moderna:** Design atual e responsivo
5. **Soft Delete:** Nada é perdido permanentemente
6. **Tema Claro/Escuro:** Conforto visual para o usuário
7. **Relatórios Visuais:** Gráficos e estatísticas fáceis de entender
8. **Termo de Responsabilidade:** Documentação formal dos empréstimos
9. **Validações Inteligentes:** Sistema previne erros e inconsistências
10. **Manutenção Integrada:** Controle de calibração e manutenção

---

## 📝 Estrutura de Pastas

```
/SistemaVscode
├── /bd                     # Backups do banco de dados
│   ├── banco_zerado.sql    # DB limpo
│   ├── banco_com_dados.sql # DB com dados de teste
│   └── README.txt          # Instruções dos backups
├── /config                 # Configurações do sistema
│   ├── config.php          # Configurações gerais
│   └── database.php        # Conexão PDO
├── /controllers            # Lógica de negócio (MVC)
│   ├── AuthController.php  # Autenticação
│   ├── DashboardController.php
│   ├── ToolController.php  # Ferramentas
│   ├── LoanController.php  # Empréstimos
│   ├── UserController.php  # Usuários
│   ├── ReportController.php # Relatórios
│   ├── AuditController.php # Auditoria
│   └── MaintenanceController.php # Manutenção
├── /views                  # Interface do usuário
│   ├── /auth               # Telas de login
│   ├── /dashboard          # Dashboard
│   ├── /tools              # Ferramentas
│   ├── /loans              # Empréstimos
│   ├── /users              # Usuários
│   ├── /reports            # Relatórios
│   ├── /audit              # Auditoria
│   ├── /maintenance        # Manutenção
│   └── /layouts            # Header e Footer
├── /public                 # Ponto de entrada
│   ├── index.php           # Front controller (roteamento)
│   └── /assets             # CSS, JS, imagens
├── /logs                   # Logs do sistema
├── /tests                  # Testes (se houver)
├── index.php               # Redirecionador para /public
├── README.md               # Documentação principal
├── README.txt              # Documentação detalhada
└── CAPACIDADES.md          # Este arquivo
```

---

## 🛠️ Solução de Problemas Comuns

### Erro: "Connection failed"
**Solução:**
- Verifique se o MySQL está rodando no XAMPP
- Confirme que o banco `sistemavscode` existe
- Verifique credenciais em `config/database.php`

### Erro 404 ou página em branco
**Solução:**
- Certifique-se de acessar `/public` na URL
- Ou use o redirecionador: http://localhost/SistemaVscode
- Ative `display_errors` no php.ini para diagnóstico

### Login não funciona
**Solução:**
- Confirme que importou o banco corretamente
- Use as credenciais de teste fornecidas
- Limpe cache e cookies do navegador
- Verifique se as senhas foram importadas corretamente

### Ferramentas não aparecem
**Solução:**
- Verifique se importou banco com dados
- Ou cadastre novas ferramentas manualmente
- Confirme que não está filtrando apenas itens excluídos

---

## 📞 Suporte

Para dúvidas:
- Consulte este arquivo (CAPACIDADES.md)
- Leia README.md e README.txt
- Revise comentários no código-fonte
- Consulte a documentação na pasta `/bd`

---

## 📅 Informações da Versão

- **Versão:** 1.0
- **Data de Criação:** Novembro 2025
- **Última Atualização:** Dezembro 2025
- **Status:** Produção

---

## 🎓 Resumo Executivo

**Este sistema pode:**
- ✅ Gerenciar estoque de ferramentas com controle de quantidade
- ✅ Registrar empréstimos de múltiplos itens simultaneamente
- ✅ Controlar devoluções e restaurar estoque automaticamente
- ✅ Gerar relatórios e estatísticas detalhadas
- ✅ Gerenciar usuários com diferentes níveis de acesso
- ✅ Manter auditoria completa de todas as ações
- ✅ Controlar manutenção e calibração de ferramentas
- ✅ Imprimir termos de responsabilidade
- ✅ Exportar dados para Excel
- ✅ Proteger dados com segurança robusta
- ✅ Oferecer interface moderna e responsiva
- ✅ Funcionar em tema claro ou escuro

**Ideal para:**
- Empresas que precisam controlar ferramentas e equipamentos
- Almoxarifados e ferramentarias
- Departamentos de manutenção
- Oficinas e fábricas
- Qualquer organização que empresta itens para colaboradores

---

**Desenvolvido com ❤️ para facilitar o controle de ferramentaria**

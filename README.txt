==============================================
SISTEMA DE FERRAMENTARIA - PHP + MySQL
Sistema de Gestão de Ferramentas com Controle de Estoque
Versão 1.0 - Novembro 2025
==============================================

==============================================
INSTALAÇÃO
==============================================

1. REQUISITOS
   - XAMPP (Apache + MySQL + PHP 7.4+)
   - Navegador moderno (Chrome, Firefox, Edge)

2. CONFIGURAÇÃO INICIAL

   a) Copie a pasta do projeto para dentro do diretório 'htdocs' do XAMPP
      Exemplo: C:\xampp\htdocs\Sistema

   b) Inicie o Apache e o MySQL no Painel de Controle do XAMPP

   c) Importe o banco de dados:
      - Acesse http://localhost/phpmyadmin
      - Crie um banco chamado 'ferramentaria'
      - Importe um dos arquivos da pasta /bd:
        * banco_zerado.sql (para começar do zero)
        * banco_com_dados.sql (com dados de teste)

   d) Verifique as configurações em 'config/database.php'
      Padrão: usuário 'root', sem senha

3. ACESSO AO SISTEMA
   
   URL: http://localhost/Sistema/public
   
   Ou simplesmente: http://localhost/Sistema
   (O index.php na raiz redireciona automaticamente)

==============================================
CREDENCIAIS DE ACESSO
==============================================

ADMINISTRADOR (acesso total):
- Email: admin@empresa.com
- Senha: admin123

OPERADOR (se usar banco_com_dados.sql):
- Email: operador1@empresa.com
- Senha: admin123

USUÁRIO COMUM (se usar banco_com_dados.sql):
- Email: joao@empresa.com
- Senha: admin123

==============================================
FUNCIONALIDADES PRINCIPAIS
==============================================

1. DASHBOARD INTERATIVO
   - KPIs clicáveis (Total, Disponíveis, Emprestadas, Manutenção)
   - Dashboard personalizado por perfil de usuário
   - Estatísticas em tempo real
   - Tema escuro/claro (toggle no navbar)

2. GESTÃO DE FERRAMENTAS
   - Cadastro com controle de quantidade (estoque)
   - Busca avançada (código, descrição, categoria, fabricante, local)
   - Filtros múltiplos
   - Histórico completo de empréstimos por ferramenta
   - Exportação para Excel
   - Edição com validação de estoque

3. SISTEMA DE EMPRÉSTIMOS
   - Registro de empréstimo com seleção de quantidade
   - Modal para escolher quantidade a emprestar
   - Controle automático de estoque disponível
   - Validação de quantidade disponível
   - Devolução com registro de condição
   - Restauração automática do estoque

4. RELATÓRIOS E ESTATÍSTICAS
   - Filtro por período
   - Total de empréstimos
   - Empréstimos em aberto
   - Empréstimos antigos (+7 dias)
   - Top 10 ferramentas mais emprestadas
   - Top 10 usuários mais ativos
   - Gráfico de distribuição
   - Impressão de relatórios

5. GESTÃO DE USUÁRIOS (Admin)
   - Cadastro de usuários
   - Níveis de acesso (Admin, Operador, Usuário)
   - Controle de status (Ativo/Inativo)
   - Edição de perfis

6. RECURSOS ADICIONAIS
   - Tema escuro/claro
   - Design responsivo
   - Interface moderna (Bootstrap 5 + Inter font)
   - Validações em tempo real
   - Feedback visual

==============================================
NÍVEIS DE ACESSO
==============================================

ADMINISTRADOR:
- Acesso total ao sistema
- Gestão de usuários
- Gestão de ferramentas
- Registro de empréstimos
- Relatórios completos

OPERADOR:
- Gestão de ferramentas
- Registro de empréstimos
- Devoluções
- Relatórios

USUÁRIO COMUM:
- Visualização do próprio histórico
- Dashboard personalizado
- Estatísticas pessoais

==============================================
ESTRUTURA DO PROJETO
==============================================

/Sistema
├── /bd                    # Backups do banco de dados
│   ├── banco_zerado.sql   # Banco limpo (apenas admin)
│   ├── banco_com_dados.sql # Backup completo com dados
│   └── README.txt         # Instruções dos backups
├── /config                # Configurações
│   ├── config.php         # Configurações gerais
│   └── database.php       # Conexão com banco
├── /controllers           # Lógica de negócio
│   ├── AuthController.php
│   ├── DashboardController.php
│   ├── LoanController.php
│   ├── ReportController.php
│   ├── ToolController.php
│   └── UserController.php
├── /views                 # Interface do usuário
│   ├── /auth              # Login
│   ├── /dashboard         # Dashboard
│   ├── /layouts           # Header e Footer
│   ├── /loans             # Empréstimos
│   ├── /reports           # Relatórios
│   ├── /tools             # Ferramentas
│   └── /users             # Usuários
├── /public                # Ponto de entrada
│   ├── index.php          # Front controller
│   └── /assets            # CSS, JS, imagens
├── index.php              # Redirecionador
└── README.txt             # Este arquivo

==============================================
TECNOLOGIAS UTILIZADAS
==============================================

BACKEND:
- PHP 7.4+ (MVC Pattern)
- MySQL/MariaDB
- PDO (conexão segura)
- Password Hash (bcrypt)

FRONTEND:
- HTML5 + CSS3
- Bootstrap 5.3
- Bootstrap Icons
- Google Fonts (Inter)
- Chart.js (gráficos)
- JavaScript Vanilla

SEGURANÇA:
- Senhas criptografadas (password_hash)
- Prepared Statements (PDO)
- Validação de sessão
- Controle de acesso por nível
- Proteção contra SQL Injection

==============================================
SISTEMA DE QUANTIDADE/ESTOQUE
==============================================

O sistema implementa controle completo de estoque:

- Cada ferramenta tem QUANTIDADE TOTAL e DISPONÍVEL
- Ao emprestar: quantidade disponível diminui
- Ao devolver: quantidade disponível aumenta
- Validações impedem empréstimos sem estoque
- Histórico registra quantidade emprestada
- Edição valida quantidade mínima

EXEMPLO:
- Cadastro: Parafusos = 100 unidades
- Empréstimo: João pega 25 unidades
- Disponível: 75 unidades
- Devolução: João devolve 25 unidades
- Disponível: 100 unidades novamente

==============================================
BACKUPS E RESTAURAÇÃO
==============================================

A pasta /bd contém:

1. banco_zerado.sql
   - Use para começar do zero
   - Apenas estrutura + admin
   - Ideal para produção

2. banco_com_dados.sql
   - Backup completo atual
   - Todas as ferramentas e dados
   - Ideal para testes

Para importar:
mysql -u root ferramentaria < bd/banco_zerado.sql

Ou via phpMyAdmin (Importar > Escolher arquivo)

==============================================
SOLUÇÃO DE PROBLEMAS
==============================================

1. ERRO DE CONEXÃO COM BANCO:
   - Verifique se MySQL está rodando
   - Confira config/database.php
   - Verifique se o banco 'ferramentaria' existe

2. PÁGINA EM BRANCO:
   - Ative display_errors no php.ini
   - Verifique logs do Apache
   - Verifique permissões de pasta

3. LOGIN NÃO FUNCIONA:
   - Verifique se importou o banco corretamente
   - Use: admin@empresa.com / admin123
   - Limpe cookies e cache do navegador

4. ERRO 404:
   - Verifique se está acessando /public
   - Ou use http://localhost/Sistema (redireciona automaticamente)

==============================================
SUPORTE E CONTATO
==============================================

Para dúvidas ou suporte:
- Consulte a documentação em /bd/README.txt
- Verifique os comentários no código
- Revise este arquivo

Data de criação: 19/11/2025
Última atualização: 19/11/2025
Versão: 1.0

==============================================
FIM DO README
==============================================

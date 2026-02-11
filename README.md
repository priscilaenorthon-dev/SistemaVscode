# Sistema de Ferramentaria

Sistema web em PHP + MySQL para gestao completa de ferramentaria: controle de estoque, emprestimos, devolucoes, manutencao, auditoria e relatorios, com dashboard interativo e perfis de acesso (admin, operador e usuario).

## Principais recursos

- **Dashboard** com KPIs clicaveis, acoes rapidas e tema claro/escuro.
- **Ferramentas**: cadastro com controle de quantidade total/disponivel, filtros por status/categoria/fabricante/localizacao, busca por codigo, soft delete com restauracao.
- **Emprestimos**: selecao visual de itens com quantidade, validacao de estoque em tempo real, termo de responsabilidade para impressao, devolucao parcial/total com deteccao de danos.
- **Manutencao**: registro automatico ao devolver item danificado, filtro por status (pendente, em andamento, concluida), acoes de iniciar e concluir com liberacao automatica da ferramenta.
- **Relatorios**: estatisticas por periodo, ranking de ferramentas e usuarios mais ativos, emprestimos em aberto/atrasados.
- **Usuarios**: gestao completa com niveis de acesso (admin, operator, user), soft delete, ativacao/inativacao.
- **Auditoria**: log de todas as acoes do sistema com busca e filtros.
- **Seguranca**: senhas com bcrypt, protecao CSRF, prepared statements (PDO), controle de acesso por nivel.

## Requisitos

- PHP 8.0+ com extensao PDO habilitada
- MySQL 5.7+ ou MariaDB 10.3+
- Navegador moderno (Chrome, Firefox, Edge, Safari)

## Instalacao

### Com XAMPP (Windows)
1. Clone ou copie o projeto para `C:\xampp\htdocs\SistemaVscode`
2. Inicie Apache e MySQL no painel do XAMPP
3. Importe o banco de dados (veja abaixo)
4. Acesse `http://localhost/SistemaVscode/public`

### Com PHP embutido (Linux/Mac)
```bash
git clone https://github.com/priscilaenorthon-dev/SistemaVscode.git
cd SistemaVscode
mysql -u root < bd/banco_com_dados.sql
php -S localhost:8080 -t public
```
Acesse `http://localhost:8080`

### Banco de dados
Importe um dos arquivos em `bd/`:
- `banco_zerado.sql` — estrutura limpa + conta admin
- `banco_com_dados.sql` — estrutura + 25 usuarios, 120 ferramentas, 60 emprestimos e 15 manutencoes

Ajuste credenciais em `config/database.php` se necessario (padrao: root sem senha).

## Credenciais de teste

| Perfil    | Email                    | Senha      |
|-----------|--------------------------|------------|
| Admin     | `admin@empresa.com`      | `password` |
| Operador  | `operador@empresa.com`   | `password` |
| Usuario   | `usuario3@empresa.com`   | `password` |

## Estrutura do projeto

```
SistemaVscode/
├── bd/                  # Dumps do banco (zerado e com dados)
├── config/
│   ├── config.php       # Configuracoes gerais, sessao, CSRF, helpers
│   └── database.php     # Conexao PDO com MySQL
├── controllers/
│   ├── AuthController.php
│   ├── DashboardController.php
│   ├── ToolController.php
│   ├── LoanController.php
│   ├── UserController.php
│   ├── ReportController.php
│   ├── AuditController.php
│   └── MaintenanceController.php
├── views/
│   ├── auth/            # Tela de login
│   ├── dashboard/       # Dashboard com KPIs
│   ├── tools/           # CRUD de ferramentas
│   ├── loans/           # Emprestimos, devolucao e impressao
│   ├── users/           # Gestao de usuarios
│   ├── reports/         # Relatorios e estatisticas
│   ├── audit/           # Log de auditoria
│   ├── maintenance/     # Manutencao de ferramentas
│   └── layouts/         # Header (navbar) e footer
├── public/
│   ├── index.php        # Roteador principal
│   └── assets/css/      # Estilos globais
├── tests/
│   ├── integration_smoke.php  # Teste rapido de pre-requisitos
│   └── test_system.php        # Suite completa (129 testes)
├── logs/                # Logs da aplicacao
└── index.php            # Redirecionamento para /public
```

## Testes automatizados

O sistema inclui uma suite de 129 testes cobrindo:
- Conexao e estrutura do banco de dados
- Seguranca (CSRF, hashing de senhas)
- Integridade de dados e referencias
- Logica de negocio (dashboard, filtros, relatorios)
- Operacoes CRUD transacionais (ferramentas, emprestimos, usuarios, manutencao)
- Funcoes auxiliares e permissoes
- Existencia de arquivos e rotas

```bash
# Teste rapido (smoke test)
php tests/integration_smoke.php

# Suite completa
php tests/test_system.php
```

## Fluxos principais

### Ferramentas
Cadastrar → Editar → Filtrar/Buscar → Visualizar historico → Soft delete → Restaurar

### Emprestimos
Selecionar colaborador → Escolher ferramentas e quantidades → Confirmar → Imprimir termo → Devolver (parcial ou total)

### Manutencao
Devolucao com dano detectado → Registro automatico de manutencao → Iniciar → Concluir → Ferramenta liberada

### Relatorios
Filtrar por periodo → Visualizar estatisticas → Top ferramentas → Top usuarios → Imprimir

## Problemas comuns

| Problema | Solucao |
|----------|---------|
| Conexao DB falhou | Verifique se MySQL esta ativo e se `config/database.php` aponta para `sistemavscode` |
| 404 ou pagina em branco | Acesse via `/public`; habilite `display_errors` no php.ini |
| Login nao funciona | Confirme importacao do dump e use as credenciais acima; limpe cookies |
| Manutencao com erro | Rode `php tests/test_system.php` para diagnosticar |

## Backups

Leia `bd/README.txt` para detalhes dos dumps.

```bash
# Restaurar banco zerado
mysql -u root < bd/banco_zerado.sql

# Restaurar banco com dados de exemplo
mysql -u root < bd/banco_com_dados.sql
```

---

Atualizado em: 11/02/2026

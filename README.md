# SistemaVscode

Sistema PHP + MySQL para gestao de ferramentaria: controla estoque, emprestimos e devolucoes, com dashboard, relatorios e perfis de acesso (admin, operador e usuario).

## 📖 Documentação Completa
**Para uma lista detalhada de TODAS as funcionalidades e capacidades do sistema, consulte [CAPACIDADES.md](CAPACIDADES.md)**

## Principais recursos
- Dashboard com KPIs clicaveis, graficos (Chart.js) e tema claro/escuro.
- Cadastro de ferramentas com controle de quantidade total e disponivel, filtros e exportacao.
- Emprestimos com selecao de itens e quantidades, termo de impressao e controle de devolucao.
- Relatorios com filtros por periodo, ranking de itens/usuarios e impressao.
- Gestao de usuarios e niveis de acesso; senhas com bcrypt; sessoes protegidas.
- **Auditoria completa** de todas as acoes do sistema (admin).
- **Controle de manutencao** e calibracao de ferramentas.
- Soft delete com possibilidade de restauracao.

## Requisitos
- PHP 7.4+ (XAMPP recomendado) com ext PDO habilitado.
- MySQL/MariaDB.
- Navegador moderno.

## Instalar e rodar localmente
1) Copie/clonar o projeto para `C:\xampp\htdocs\SistemaVscode` (ou outro nome).  
2) Inicie Apache e MySQL no painel do XAMPP.  
3) Crie o banco `sistemavscode` e importe um dos arquivos em `bd/`:
   - `banco_zerado.sql` (estrutura + admin)
   - `banco_com_dados.sql` (estrutura + dados de exemplo)
4) Ajuste credenciais em `config/database.php` se necessario (padrao: root sem senha).
5) Acesse `http://localhost/SistemaVscode/public` (ou apenas `http://localhost/SistemaVscode`, o `index.php` redireciona).

## Credenciais de teste
- Admin: `admin@empresa.com` / `password`
- Operador: `operador@empresa.com` / `password`
- Usuario: `usuario@usuario.com.br` / `password`

## Estrutura resumida
- `bd/` dumps do banco (`banco_zerado.sql`, `banco_com_dados.sql`).
- `config/` configuracoes gerais e conexao PDO.
- `controllers/` regras de negocio (Auth, Dashboard, Loan, Report, Tool, User, Audit, Maintenance).
- `views/` telas (auth, dashboard, loans, reports, tools, users, audit, maintenance, layouts).
- `public/` ponto de entrada (`index.php`) e assets.
- `index.php` na raiz apenas redireciona para `/public`.
- `CAPACIDADES.md` documentacao completa de todas as funcionalidades.

## Fluxos principais
- Ferramentas: cadastra, edita, filtra, exporta e mantem historico por item.
- Emprestimos: escolhe quantidade por ferramenta, valida estoque, imprime termo de responsabilidade e registra devolucao.
- Relatorios: estatisticas por periodo, itens mais emprestados, usuarios mais ativos, emprestimos em aberto/antigos.
- Usuarios: admin cria/edita, ativa/inativa e define niveis (admin, operator, user).
- Auditoria: admin visualiza logs de todas as acoes do sistema.
- Manutencao: controle de ferramentas em manutencao/calibracao.

## Problemas comuns
- Conexao DB falhou: verifique se MySQL esta ativo e se `config/database.php` aponta para `sistemavscode`.
- 404/pagina em branco: acesse via `/public`; habilite `display_errors` no php.ini para diagnosticar.
- Login nao funciona: confirme importacao do dump e use as credenciais de teste acima; limpe cache/cookies.

## Backups
Leia `bd/README.txt` para detalhes dos dumps. Para restaurar via CLI: `mysql -u root sistemavscode < bd/banco_zerado.sql`.

## Saiba mais
Para informacoes detalhadas sobre todas as funcionalidades, casos de uso e capacidades do sistema, consulte [CAPACIDADES.md](CAPACIDADES.md).

---

Atualizado em: 16/12/2025

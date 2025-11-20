==============================================
BACKUPS DO BANCO DE DADOS - Sistema Ferramentaria
==============================================

Esta pasta contém 2 arquivos SQL para diferentes finalidades:

==============================================
1. banco_zerado.sql
==============================================
DESCRIÇÃO:
- Banco de dados completamente LIMPO
- Apenas estrutura das tabelas
- Conta de administrador padrão
- Categorias e modelos básicos

QUANDO USAR:
- Para começar do zero
- Para ambiente de produção limpo
- Para resetar o sistema

CREDENCIAIS DE ACESSO:
- Email: admin@empresa.com
- Senha: admin123

COMO IMPORTAR:
1. Via phpMyAdmin:
   - Acesse http://localhost/phpmyadmin
   - Selecione o banco 'ferramentaria'
   - Clique em 'Importar'
   - Escolha o arquivo 'banco_zerado.sql'
   - Clique em 'Executar'

2. Via linha de comando:
   mysql -u root ferramentaria < banco_zerado.sql

==============================================
2. banco_com_dados.sql
==============================================
DESCRIÇÃO:
- Backup COMPLETO do banco atual
- Todas as ferramentas cadastradas
- Todos os empréstimos
- Todos os usuários
- Todos os dados de teste

QUANDO USAR:
- Para restaurar dados após testes
- Para backup de segurança
- Para migrar para outro servidor
- Para ambiente de desenvolvimento

COMO IMPORTAR:
1. Via phpMyAdmin:
   - Acesse http://localhost/phpmyadmin
   - Selecione o banco 'ferramentaria'
   - Clique em 'Importar'
   - Escolha o arquivo 'banco_com_dados.sql'
   - Clique em 'Executar'

2. Via linha de comando:
   mysql -u root ferramentaria < banco_com_dados.sql

==============================================
IMPORTANTE - LEIA ANTES DE IMPORTAR
==============================================

⚠️ ATENÇÃO: Importar qualquer um desses arquivos irá:
   - APAGAR todos os dados atuais do banco
   - SUBSTITUIR por completo o banco de dados
   - NÃO HÁ COMO DESFAZER esta ação

✓ RECOMENDAÇÃO:
   - Sempre faça um backup antes de importar
   - Use 'banco_zerado.sql' apenas se quiser começar do zero
   - Use 'banco_com_dados.sql' para restaurar dados de teste

==============================================
COMO CRIAR NOVOS BACKUPS
==============================================

Para criar um novo backup do banco atual:

1. Via linha de comando (Windows):
   C:\xampp\mysql\bin\mysqldump.exe -u root ferramentaria > bd\meu_backup.sql

2. Via phpMyAdmin:
   - Selecione o banco 'ferramentaria'
   - Clique em 'Exportar'
   - Escolha 'Método rápido'
   - Clique em 'Executar'

==============================================
ESTRUTURA DO BANCO DE DADOS
==============================================

Tabelas principais:
- users (usuários do sistema)
- tool_categories (categorias de ferramentas)
- tool_models (modelos de ferramentas)
- tools (ferramentas do inventário)
- loans (empréstimos)
- loan_items (itens de cada empréstimo)
- maintenance (manutenções)

==============================================
SUPORTE
==============================================

Em caso de dúvidas ou problemas:
1. Verifique se o MySQL está rodando
2. Verifique as credenciais de acesso
3. Consulte a documentação do sistema

Data de criação: 19/11/2025
Versão do sistema: 1.0

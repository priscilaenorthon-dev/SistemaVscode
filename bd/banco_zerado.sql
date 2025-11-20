-- =============================================
-- BANCO DE DADOS ZERADO - Sistema Ferramentaria
-- Apenas estrutura + conta administrador
-- =============================================

-- Criar banco de dados
DROP DATABASE IF EXISTS ferramentaria;
CREATE DATABASE ferramentaria CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ferramentaria;

-- =============================================
-- TABELAS
-- =============================================

-- Tabela de usuários
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    registration VARCHAR(50),
    sector VARCHAR(100),
    level ENUM('admin', 'operator', 'user') DEFAULT 'user',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de categorias de ferramentas
CREATE TABLE tool_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

-- Tabela de modelos de ferramentas
CREATE TABLE tool_models (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

-- Tabela de ferramentas
CREATE TABLE tools (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    description VARCHAR(255) NOT NULL,
    category_id INT,
    model_id INT,
    manufacturer VARCHAR(100),
    serial_number VARCHAR(100),
    location VARCHAR(100),
    acquisition_date DATE,
    status ENUM('available', 'borrowed', 'maintenance', 'inactive') DEFAULT 'available',
    quantity INT DEFAULT 1,
    available_quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES tool_categories(id),
    FOREIGN KEY (model_id) REFERENCES tool_models(id)
);

-- Tabela de empréstimos
CREATE TABLE loans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    operator_id INT NOT NULL,
    loan_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('open', 'closed') DEFAULT 'open',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (operator_id) REFERENCES users(id)
);

-- Tabela de itens de empréstimo
CREATE TABLE loan_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    loan_id INT NOT NULL,
    tool_id INT NOT NULL,
    quantity INT DEFAULT 1,
    return_date TIMESTAMP NULL,
    return_condition TEXT,
    status ENUM('borrowed', 'returned') DEFAULT 'borrowed',
    FOREIGN KEY (loan_id) REFERENCES loans(id),
    FOREIGN KEY (tool_id) REFERENCES tools(id)
);

-- Tabela de manutenções
CREATE TABLE maintenance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tool_id INT NOT NULL,
    description TEXT,
    start_date DATE,
    end_date DATE,
    cost DECIMAL(10,2),
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    FOREIGN KEY (tool_id) REFERENCES tools(id)
);

-- =============================================
-- DADOS INICIAIS
-- =============================================

-- Inserir usuário administrador
-- Email: admin@empresa.com
-- Senha: admin123
INSERT INTO users (name, email, password, registration, sector, level, status) VALUES
('Administrador', 'admin@empresa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADM-001', 'TI', 'admin', 'active');

-- Inserir algumas categorias básicas
INSERT INTO tool_categories (name, description) VALUES
('Ferramentas Manuais', 'Ferramentas de uso manual'),
('Ferramentas Elétricas', 'Ferramentas que utilizam energia elétrica'),
('Equipamentos de Medição', 'Instrumentos de medição e precisão'),
('Equipamentos de Segurança', 'EPIs e equipamentos de proteção');

-- Inserir alguns modelos básicos
INSERT INTO tool_models (name, description) VALUES
('Padrão', 'Modelo padrão'),
('Profissional', 'Modelo profissional'),
('Industrial', 'Modelo industrial');

-- =============================================
-- ÍNDICES PARA PERFORMANCE
-- =============================================

CREATE INDEX idx_tools_code ON tools(code);
CREATE INDEX idx_tools_status ON tools(status);
CREATE INDEX idx_tools_available ON tools(available_quantity);
CREATE INDEX idx_loans_status ON loans(status);
CREATE INDEX idx_loans_date ON loans(loan_date);
CREATE INDEX idx_loan_items_status ON loan_items(status);

-- =============================================
-- FIM DO SCRIPT
-- =============================================

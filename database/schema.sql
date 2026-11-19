CREATE DATABASE IF NOT EXISTS gestao_logistica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestao_logistica;
CREATE TABLE IF NOT EXISTS produtos (
 id INT AUTO_INCREMENT PRIMARY KEY, codigo VARCHAR(50) NOT NULL UNIQUE,
 nome VARCHAR(150) NOT NULL, peso_unitario DECIMAL(10,3) NOT NULL,
 quantidade INT NOT NULL DEFAULT 0, criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS enderecos (
 id INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(150) NOT NULL,
 cidade VARCHAR(120) NOT NULL, uf CHAR(2) NOT NULL,
 logradouro VARCHAR(255) NOT NULL DEFAULT '', cep VARCHAR(12) NOT NULL DEFAULT ''
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS transportadores (
 id INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(150) NOT NULL,
 documento VARCHAR(25) NOT NULL DEFAULT '', telefone VARCHAR(25) NOT NULL DEFAULT '',
 veiculo VARCHAR(120) NOT NULL DEFAULT ''
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS fretes (
 id INT AUTO_INCREMENT PRIMARY KEY, data_frete DATE NOT NULL,
 origem_id INT NOT NULL, destino_id INT NOT NULL, transportador_id INT NOT NULL,
 distancia_km DECIMAL(10,2) NOT NULL, peso_total DECIMAL(12,3) NOT NULL,
 valor_frete DECIMAL(12,2) NOT NULL, status VARCHAR(30) NOT NULL DEFAULT 'Pendente',
 criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (origem_id) REFERENCES enderecos(id),
 FOREIGN KEY (destino_id) REFERENCES enderecos(id),
 FOREIGN KEY (transportador_id) REFERENCES transportadores(id)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS frete_itens (
 id INT AUTO_INCREMENT PRIMARY KEY, frete_id INT NOT NULL, produto_id INT NOT NULL,
 quantidade INT NOT NULL, peso_unitario DECIMAL(10,3) NOT NULL,
 peso_total DECIMAL(12,3) NOT NULL,
 FOREIGN KEY (frete_id) REFERENCES fretes(id) ON DELETE CASCADE,
 FOREIGN KEY (produto_id) REFERENCES produtos(id)
) ENGINE=InnoDB;
INSERT IGNORE INTO produtos (codigo,nome,peso_unitario,quantidade) VALUES ('CJ001','Calça jeans',0.600,150),('BJ002','Bermuda jeans',0.400,80),('JQ003','Jaqueta jeans',0.850,40);
INSERT INTO enderecos (nome,cidade,uf,logradouro,cep)
 SELECT 'Fábrica Toledo','Toledo','PR','', '' WHERE NOT EXISTS (SELECT 1 FROM enderecos WHERE nome='Fábrica Toledo');
INSERT INTO enderecos (nome,cidade,uf,logradouro,cep)
 SELECT 'Centro de distribuição São Paulo','São Paulo','SP','','' WHERE NOT EXISTS (SELECT 1 FROM enderecos WHERE nome='Centro de distribuição São Paulo');
INSERT INTO transportadores (nome,documento,telefone,veiculo)
 SELECT 'Transportadora ABC','','','Caminhão baú' WHERE NOT EXISTS (SELECT 1 FROM transportadores WHERE nome='Transportadora ABC');

INSERT INTO pais SET pais = 'Brasil';

INSERT INTO estado (idpais, estado) VALUES (1,'Rio Grande do Sul'), (1,'Santa Catarina'), (1,'Paraná'), (1,'São Paulo'), (1,'Rio de Janeiro'), (1,'Belo Horizonte'), (1,'Espírito Santo');

INSERT INTO cidade (idestado, cidade) VALUES (1, 'Porto Alegre'), (1, 'São Leopoldo'), (1,'Novo Hamburgo'), (1,'Canoas'), (1,'Sapiranga'), (1,'Gravataí'), (1,'Sapucaia'), (1,'Esteio');

INSERT INTO modulo (nome, ativo) VALUES ('Geral', 'A'), ('Doctor Service', 'A'), ('Operadora', 'A'), ('Médicos', 'A');

INSERT INTO tipo_contato (ativo, tipo) VALUES ('A', 'EMAIL'), ('A', 'TELEFONE');

INSERT INTO tipo_endereco (ativo, tipo) VALUES ('A', 'OPERADORA');

INSERT INTO medico SET crm = '1234567890', crm_uf = 'RS', ativo = 'A', idpessoa = 1;

insert into operadora_grupo (nome, idoperadora, ativo) values ('Grupo Doctor Clin', 2, 'A');

INSERT INTO operadora_grupo_medico SET data_inclusao = null, data_aprovacao = null, ativo = 'A', idoperadora_grupo_medico = 1, idmedico = 1;

INSERT INTO operadora_unidade (nome, idoperadora, ativo) VALUES ('Unidade Novo Hamburgo', 1, 'A'), ('Unidade São Leopoldo', 1, 'A'), ('Unidade Esteio', 1, 'A'), ('Unidade Sapucaia', 1, 'A');

INSERT INTO status_prospect (nome, ativo) VALUES ('Lead', 'A'), ('Prospect', 'A'), ('Implantação', 'A'), ('Convertido', 'A');

INSERT INTO comunicacao_tipo (nome, ativo) VALUES ('Presencial', 'A'), ('Telefone', 'A'), ('E-mail', 'A'), ('Outros', 'A');
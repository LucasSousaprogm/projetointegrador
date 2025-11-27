create database sge;
use sge;
create table funcionario(
cpf int primary key,
senha int not null,
nome varchar(100) not null
);

CREATE TABLE sala (
    nome VARCHAR(100) PRIMARY KEY,
    razão VARCHAR(150) NOT NULL
);

CREATE TABLE patrimonio (
    codigo INT PRIMARY KEY,
    tipo VARCHAR(100) NOT NULL,
    sala_de_origem VARCHAR(100) NOT NULL,
    quantidade INT NOT NULL,
    ano INT NOT NULL,
    marca VARCHAR(100) NOT NULL,
    nome_sala VARCHAR(100) NOT NULL,
    FOREIGN KEY (nome_sala) REFERENCES sala(nome)
);

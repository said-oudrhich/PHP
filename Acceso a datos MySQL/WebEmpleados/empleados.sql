CREATE DATABASE empleados;
USE empleados;

CREATE TABLE departamento
(cod_dpto      VARCHAR(4),
 nombre_dpto   VARCHAR(40),
 CONSTRAINT pk_departamento 
 PRIMARY KEY (cod_dpto)) ENGINE=InnoDB;
 
CREATE TABLE empleado 
(dni VARCHAR(9), 
 nombre VARCHAR(40),
 apellidos VARCHAR(40), 
 fecha_nac DATE, 
 salario DOUBLE, 
CONSTRAINT pk_empleado PRIMARY KEY (dni)) ENGINE=InnoDB;

CREATE TABLE emple_depart 
(dni VARCHAR(9), 
 cod_dpto VARCHAR(4),
 fecha_ini DATE,
 fecha_fin DATE,
 CONSTRAINT pk_emple_depart PRIMARY KEY (dni,cod_dpto,fecha_ini),
 CONSTRAINT fk_emple FOREIGN KEY (dni) REFERENCES empleado(dni),
 CONSTRAINT fk_depart FOREIGN KEY (cod_dpto) REFERENCES departamento(cod_dpto)
  ) ENGINE=InnoDB;
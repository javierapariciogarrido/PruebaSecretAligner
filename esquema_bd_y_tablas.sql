# Creo base de datos 
CREATE DATABASE IF NOT EXISTS secret_aligner;

# Usamos la base de datos 
use secret_aligner;

# Creamos tabla TODO
CREATE TABLE IF NOT EXISTS todo(
    id                  int(255)        auto_increment    not null,
    nombre              varchar(255)    not null,
    fecha_creacion      datetime        not null,
    fecha_tope          datetime        not null,       
    estado              varchar(255)    not null,    
CONSTRAINT      pk_todo                 PRIMARY KEY(id)

)ENGINE=InnoDb;

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


# MODIFICACIÓN DE LA TABLA TODO PARA AÑADIRLE UN CAMPO user_id COMO CLAVE AJENA
ALTER TABLE todo ADD user_id   int(255)  NOT NULL;
ALTER TABLE todo ADD CONSTRAINT fk_todo_user  FOREIGN KEY(user_id) REFERENCES users(id);





# TABLA USUARIO

CREATE TABLE IF NOT EXISTS users(
    id          int(255)    auto_increment      not null,
    role        varchar(50),
    nombre      varchar(100),
    apellidos   varchar(200),
    email       varchar(255),
    password    varchar(255),
    created_at  datetime,
CONSTRAINT      pk_users        PRIMARY KEY(id)    
)ENGINE=InnoDb;




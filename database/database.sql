-- Tabla: estudiante
CREATE TABLE estudiante (
    estudiante_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    apellidos VARCHAR(255) NOT NULL,
    ci VARCHAR(20) UNIQUE NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    sexo ENUM('M', 'F') NOT NULL
);

-- Tabla: nivel
CREATE TABLE nivel (
    nivel_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

-- Tabla: carrera
CREATE TABLE carrera (
    carrera_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    nivel_id INT NOT NULL,
    duracion_meses INT NOT NULL,
    FOREIGN KEY (nivel_id) REFERENCES nivel(nivel_id)
);

-- Tabla: estudiante_carrera
CREATE TABLE estudiante_carrera (
    estudiante_carrera_id INT AUTO_INCREMENT PRIMARY KEY,
    estudiante_id INT NOT NULL,
    carrera_id INT NOT NULL,
    fecha_inscripcion DATE NOT NULL,
    FOREIGN KEY (estudiante_id) REFERENCES estudiante(estudiante_id),
    FOREIGN KEY (carrera_id) REFERENCES carrera(carrera_id)
);

-- Tabla: gestion
CREATE TABLE gestion (
    gestion_id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(50) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL
);

-- Tabla: matricula
CREATE TABLE matricula (
    matricula_id INT AUTO_INCREMENT PRIMARY KEY,
    estudiante_carrera_id INT NOT NULL,
    gestion_id INT NOT NULL,
    fecha_matricula DATE NOT NULL,
    estado ENUM('Activa', 'Finalizada') NOT NULL,
    FOREIGN KEY (estudiante_carrera_id) REFERENCES estudiante_carrera(estudiante_carrera_id),
    FOREIGN KEY (gestion_id) REFERENCES gestion(gestion_id)
);

-- Tabla: pago
CREATE TABLE pago (
    pago_id INT AUTO_INCREMENT PRIMARY KEY,
    matricula_id INT NOT NULL,
    concepto ENUM('Modular', 'Mensual') NOT NULL,
    fecha DATE NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    mes_pago VARCHAR(50),
    FOREIGN KEY (matricula_id) REFERENCES matricula(matricula_id)
);

-- Tabla: egreso
CREATE TABLE egreso (
    egreso_id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion TEXT NOT NULL,
    fecha DATE NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    responsable VARCHAR(255) NOT NULL,
    gestion_id INT NOT NULL,
    FOREIGN KEY (gestion_id) REFERENCES gestion(gestion_id)
);

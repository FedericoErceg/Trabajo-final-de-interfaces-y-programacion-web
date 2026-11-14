# Sistema de Reservas de Hotel

Trabajo Final para la materia **Interfaces y Programación Web** - Universidad Atlántida Argentina.

**Alumno:** Federico Erceg

## Tecnologías utilizadas

- **Frontend:** Vue.js 3 (Options API), Vue Router, Bootstrap 5, Bootstrap Icons
- **Backend:** PHP 8 con mysqli (prepared statements)
- **Base de datos:** MySQL / MariaDB
- **Autenticación:** Firebase Authentication + Google reCAPTCHA v2
- **Servidor local:** XAMPP (Apache + MySQL)
- **Bundler:** Vite

## Instalación y ejecución

### 1. Base de datos
En la carpeta `public/doc/Script SQL/` se encuentra el archivo `hotel_reservas.sql`. Importarlo desde phpMyAdmin o ejecutarlo directamente en MySQL. El script crea la base de datos y las tablas con datos de prueba.

### 2. Backend (PHP)
Iniciar Apache y MySQL desde XAMPP. Los archivos PHP del backend están en la carpeta `backend/`.

### 3. Frontend (Vue)
```bash
cd ProyectoFinal-FedericoErceg
npm install
npm run dev
```
La aplicación corre en `http://localhost:5173`

### Credenciales de prueba
- **Email:** prueba@gmail.com
- **Contraseña:** 123456

## Documentación

En la carpeta `public/doc/` se encuentra:
- **Diagrama relacional** de la base de datos
- **Script SQL** (`hotel_reservas.sql`) para crear la base de datos de forma local

## Estructura del proyecto
```

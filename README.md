# Gestor de Punto de Ventas e Inventario

Sistema de gestión de ventas, inventario y caja hecho con **Laravel** (backend/API) y **Vue.js** (frontend SPA) utilizando **Laravel Breeze**.

## Instalación y ejecución

### 1. Clonar el repositorio
```bash
git clone https://github.com/Amonwill/Tienda.git
cd Tienda
```

### 2. Instalar dependencias backend (Laravel)
Asegúrate de tener `composer` instalado.
```bash
composer install
```

### 3. Instalar dependencias frontend
Asegúrate de tener `node` y `npm` instalados.
```bash
npm install
```

### 4. Crear y configurar el archivo de entorno
Copia el archivo `.env.example` como `.env` y ajusta los datos de conexión a la base de datos:
```bash
cp .env.example .env
```
Edita `.env` con tus credenciales de base de datos.

### 5. Generar clave de aplicación
```bash
php artisan key:generate
```

### 6. Migrar la base de datos
```bash
php artisan migrate
```

### 7. (Opcional) Instalar Breeze + Vue (si no está instalado)
```bash
composer require laravel/breeze --dev
php artisan breeze:install vue
```

### 8. Levantar el servidor
En una terminal (backend):
```bash
php artisan serve
```
En otra terminal (frontend):
```bash
npm run dev
```

---

## Comandos útiles para desarrollo

- Crear modelos y sus migraciones:
  ```bash
  php artisan make:model NombreModelo -m
  ```
- Crear controladores:
  ```bash
  php artisan make:controller NombreController
  ```

## Frameworks y dependencias usadas

- **Laravel**: Backend/API, migraciones, autenticación, ORM Eloquent.
- **Laravel Breeze**: Starter kit para autenticación y scaffolding.
- **Vue.js**: Frontend SPA integrada.
- **Node.js/NPM**: Gestión de assets y dependencias JS.
- **Composer**: Gestión de dependencias PHP.

## Capturas de pantalla

---

## Notas
- Recuerda iniciar los servicios de base de datos (MySQL/MariaDB).
- Revisa las migraciones y controladores para adaptar o ampliar funcionalidades.

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Instalacion 

### Paso 1 : Crear proyecto en Laravel
Instalacion de composer:

```bash
    composer create-project laravel/laravel gestor-tienda
```
Despues ingresa a la carpeta creada:

```bash
    cd gestor-tienda
```

### Paso 2 : Iniciar proyecto con Breeze + Vue
Descarga de las herramientas necesarias con el siguiente comando en consola:

```bash
    composer require laravel/breeze --dev
```

Usa Vue como frontend:

```bash
    php artisan breeze:install vue
```

### Paso 3 : Node 
Instala Node y dependencias:

```bash
    npm install
    php artisan migrate
```

### Paso 4 : Levantar proyecto para pruebas
puedes hacer uso de dos consolas donde usaremos los siguientes comandos:

consola 1 (Backend):
```bash 
    php artisan serve
```

consola 2 (Frontend):
```bash 
    npm run dev
```

## Creacion de base de datos desde VS Code

### Creacion de tablas desde la consola de vs Code

Ejecuta linea por linea para crear migraciones de cada una de las tablas, estas se crearan de manera automatica:

```bash
    php artisan make:model Clientes_Cat -m
    php artisan make:model Proveedores_Cat -m
    php artisan make:model Lotes_Cat -m
    php artisan make:model Productos_Cat -m
    php artisan make:model Cajas_Cat -m
    php artisan make:model Caja_General -m
    php artisan make:model Ventas -m
    php artisan make:model Detalle_Ventas -m
    php artisan make:model Bandeja_Alertas -m
```
Despues de generar las migraciones, genera los controlladores a traves de la consola:

```bash
    php artisan make:controller ProductoController
    php artisan make:controller VentaController
    php artisan make:controller CajaController
    php artisan make:controller ProveedorController 
```


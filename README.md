# Gestor de punto de ventas e inventario 
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


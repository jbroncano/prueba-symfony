# Prueba Tecnica

Aplicación web desarrollada con **Symfony 7** para gestionar tareas como parte de una prueba técnica.

## Características

- **CRUD Completo**: Crear, Leer, Actualizar y Eliminar tareas
- **Sistema de Autenticación**: Login y Registro de usuarios
- **Seguridad**: Solo el creador puede ver/editar/eliminar sus propias tareas
- **Interfaz Moderna**: Diseño responsivo con Bootstrap 5
- **Dashboard**: Estadísticas de tareas completadas y pendientes
- **Marcar como Completada**: Cambiar estado de tareas con un click
- **UI/UX Profesional**: Diseño intuitivo con iconos Bootstrap Icons

## Tecnologías Utilizadas

- **Backend**: PHP 8.2+ con Symfony 7.0
- **ORM**: Doctrine ORM
- **Base de Datos**: PostgreSQL 17 (configurable para MySQL/SQLite)
- **Frontend**: Bootstrap 5.3 + Bootstrap Icons
- **Seguridad**: Symfony Security Bundle
- **Validaciones**: Symfony Validator

## Requisitos

- PHP 8.2 o superior
- Composer 2.x
- PostgreSQL 17 (o MySQL 8.0+ / SQLite)
- Extensiones PHP: pdo, pdo_pgsql (o pdo_mysql), intl, json, mbstring

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/jbroncano/prueba-symfony.git
cd prueba-symfony
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar la Base de Datos

Edita el archivo `.env` y configura la conexión a la base de datos:

**PostgreSQL:**
```env
DATABASE_URL="postgresql://postgres:password@localhost:5433/pruebaSymfony?serverVersion=17&charset=utf8"
```

### 4. Crear la Base de Datos y Ejecutar Migraciones

```bash
# Crear la base de datos
php bin/console doctrine:database:create

# Ejecutar las migraciones para crear las tablas
php bin/console doctrine:migrations:migrate
```

Cuando pregunte si deseamos ejecutar la migración, escribir **yes**.

### 5. Crear un Usuario de Prueba

```bash
php bin/console app:create-user test@test.com 123456
```

### 6. Iniciar el Servidor

**Opción 1 - Con Symfony CLI (recomendado):**
```bash
symfony server:start
```

**Opción 2 - Con PHP:**
```bash
php -S localhost:8000 -t public
```

### 7. Acceder a la Aplicación

Abre tu navegador en: **http://localhost:8000**

**Credenciales de prueba:**
- Email: `test@test.com`
- Password: `123456`

## Estructura del Proyecto

```
prueba-symfony/
├── config/              # Configuraciones de Symfony
├── migrations/          # Migraciones de base de datos
├── public/              # Punto de entrada web
├── src/
│   ├── Command/         # Comandos de consola
│   ├── Controller/      # Controladores
│   │   ├── SecurityController.php
│   │   └── TaskController.php
│   ├── Entity/          # Entidades Doctrine
│   │   ├── Task.php
│   │   └── User.php
│   ├── Form/            # Formularios
│   │   └── TaskType.php
│   ├── Repository/      # Repositorios
│   └── Security/        # Autenticación
│       └── AppAuthenticator.php
├── templates/           # Plantillas Twig
│   ├── base.html.twig
│   ├── security/
│   │   ├── login.html.twig
│   │   └── register.html.twig
│   └── task/
│       ├── index.html.twig
│       ├── new.html.twig
│       ├── edit.html.twig
│       └── show.html.twig
├── var/                 # Cache y logs
├── .env                 # Variables de entorno
└── composer.json        # Dependencias PHP
```


## Comandos

```bash
# Crear un nuevo usuario
php bin/console app:create-user email@example.com password123

# Limpiar caché
php bin/console cache:clear

# Ver todas las rutas
php bin/console debug:router

# Verificar esquema de base de datos
php bin/console doctrine:schema:validate

# Ver logs en tiempo real
tail -f var/log/dev.log
```

## Troubleshooting

### Error: "Access Denied"
- Asegúrate de estar autenticado
- Verifica que la tarea te pertenece

### Error: Base de datos no existe
```bash
php bin/console doctrine:database:create
```

### Error: Tablas no existen
```bash
php bin/console doctrine:migrations:migrate
```

### Olvidé mi contraseña
Crea un nuevo usuario con el comando:
```bash
php bin/console app:create-user nuevo@email.com nuevapassword
```

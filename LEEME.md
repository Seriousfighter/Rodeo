# 🐄 Rodeo

Aplicación fullstack para la gestión de rodeos ganaderos, diseñada para veterinarios y profesionales del agro que trabajan con múltiples establecimientos y clientes.

## 📋 Descripción

**Rodeo** permite registrar y administrar diferentes grupos de animales (rodeos) de diversos clientes. Cada grupo puede someterse a protocolos como inseminaciones, vacunaciones, chequeos de peso, ecografías, entre otros.

La plataforma está pensada para uso profesional, con funcionalidades de exportación de planillas en PDF o Excel, y una arquitectura moderna basada en Laravel + Vue con una API de registros en Node.js y MongoDB.

Este proyecto se encuentra en desarrollo activo y sirve como ejemplo práctico de mi forma de trabajo, estructura de código y proceso de versionado.

## 🚀 Tecnologías utilizadas

### Backend Principal
- ⚙️ Laravel 10 (Backend, API, lógica de negocio)
- 🐬 MySQL (Base de datos principal)

### RecordingAPI (Microservicio)
- 🟢 Node.js + Express.js (API de registros veterinarios)
- 🍃 MongoDB (Base de datos para registros)

### Frontend
- 🖼️ Vue 3 + Inertia.js (Frontend SSR)
- 🎨 TailwindCSS (Estilos)

### DevOps
- 🐳 Docker / Docker Compose (Entorno de desarrollo)
- 🧪 PHPUnit (Tests - planeado)

## 🏗️ Arquitectura del Proyecto

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │   Laravel API   │    │  RecordingAPI   │
│   Vue 3 +       │◄───┤   (Animals,     │◄───┤   Express.js    │
│   Inertia.js    │    │   Rodeos,       │    │   + MongoDB     │
│   TailwindCSS   │    │   Clients)      │    │   (Recordings)  │
└─────────────────┘    └─────────────────┘    └─────────────────┘
                                │                        │
                                ▼                        ▼
                       ┌─────────────────┐    ┌─────────────────┐
                       │     MySQL       │    │    MongoDB      │
                       │   (Main DB)     │    │  (Recordings)   │
                       └─────────────────┘    └─────────────────┘
```

## 🧰 Instalación y ejecución local

### Requisitos
- Docker y Docker Compose instalados
- Git

### Rama de desarrollo
⚠️ **Importante**: Use la rama `development` para el desarrollo activo:

```bash
git clone https://github.com/Seriousfighter/Rodeo.git
cd Rodeo
git checkout development
```

### Configuración inicial

1. **Copiar archivo de entorno:**
```bash
cp .env.example .env
```

2. **Levantar contenedores:**
```bash
docker-compose up -d --build
```

3. **Configurar Laravel:**
```bash
# Entrar al contenedor de Laravel
docker exec -it rodeo-app /bin/sh

# Generar clave de aplicación
php artisan key:generate

# Ejecutar migraciones
php artisan migrate
```

### 🐧 Configuración específica para Linux

En sistemas Linux, es posible que necesites crear manualmente la base de datos MySQL:

```bash
# Conectar al contenedor de MySQL
docker exec -it rodeo-mysql mysql -u root -p

# Crear la base de datos (usar password del .env)
CREATE DATABASE rodeo_db;
exit
```

Luego ejecutar las migraciones:
```bash
docker exec -it rodeo-app php artisan migrate
```

### 🔧 Servicios disponibles

Una vez levantado el entorno, tendrás acceso a:

| Servicio | URL | Descripción |
|----------|-----|-------------|
| **Frontend** | `http://localhost:8000` | Aplicación principal Laravel + Vue |
| **RecordingAPI** | `http://localhost:3000` | API de registros en Express.js |
| **MySQL** | `localhost:3306` | Base de datos principal |
| **MongoDB** | `localhost:27017` | Base de datos de registros |

### 🧪 Verificar instalación

```bash
# Verificar que todos los contenedores estén corriendo
docker-compose ps

# Verificar logs de la aplicación
docker-compose logs rodeo-app

# Verificar logs del RecordingAPI
docker-compose logs recording-api
```

## 📊 RecordingAPI

El microservicio RecordingAPI maneja todos los registros veterinarios:

### Endpoints principales
- `GET /api/recordings` - Listar registros
- `POST /api/recordings` - Crear registro
- `GET /api/recordings/:id` - Obtener registro específico
- `PUT /api/recordings/:id` - Actualizar registro
- `DELETE /api/recordings/:id` - Eliminar registro

### Tipos de registros soportados
- Inseminaciones
- Vacunaciones
- Controles de peso
- Ecografías/Ultrasonidos
- Montas naturales
- Chequeos de salud
- Otros procedimientos

## 🛠️ Comandos útiles de desarrollo

```bash
# Reconstruir contenedores
docker-compose up -d --build

# Ver logs en tiempo real
docker-compose logs -f

# Ejecutar comandos en Laravel
docker exec -it rodeo-app php artisan [comando]

# Ejecutar comandos en RecordingAPI
docker exec -it recording-api npm run [comando]

# Limpiar volúmenes (cuidado: borra datos)
docker-compose down -v
```

## 📝 Estado del proyecto

- ✅ Gestión de animales, rodeos y clientes
- ✅ RecordingAPI con MongoDB
- ✅ Integración Docker completa
- ✅ Frontend responsivo con Vue 3
- 🔄 Sistema de registros veterinarios (en desarrollo)
- 📋 Exportación PDF/Excel (planeado)
- 🧪 Tests automatizados (planeado)


## 📄 Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

## Contacto
contacto@ss-schneiderservices.com
---

**Nota**: Este proyecto utiliza Git Flow. La rama `development` contiene las últimas características en desarrollo.
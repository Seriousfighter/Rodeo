# 🐄 Rodeo

Full-stack application for cattle rodeo management, designed for veterinarians and agricultural professionals working with multiple establishments and clients.

## 📋 Description

**Rodeo** allows you to register and manage different groups of animals (rodeos) from various clients. Each group can undergo protocols such as inseminations, vaccinations, weight checks, ultrasounds, among others.

The platform is designed for professional use, with PDF or Excel export functionalities, and a modern architecture based on Laravel + Vue with a Node.js and MongoDB recording API.

This project is under active development and serves as a practical example of my work approach, code structure, and versioning process.

## 🚀 Technologies Used

### Main Backend
- ⚙️ Laravel 10 (Backend, API, business logic)
- 🐬 MySQL (Main database)

### RecordingAPI (Microservice)
- 🟢 Node.js + Express.js (Veterinary records API)
- 🍃 MongoDB (Records database)

### Frontend
- 🖼️ Vue 3 + Inertia.js (SSR Frontend)
- 🎨 TailwindCSS (Styling)

### DevOps
- 🐳 Docker / Docker Compose (Development environment)
- 🧪 PHPUnit (Tests - planned)

## 🏗️ Project Architecture

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

## 🧰 Local Installation and Execution

### Requirements
- Docker and Docker Compose installed
- Git

### Development Branch
⚠️ **Important**: Use the `development` branch for active development:

```bash
git clone https://github.com/Seriousfighter/Rodeo.git
cd Rodeo
git checkout development
```

### Initial Configuration

1. **Copy environment file:**
```bash
cp .env.example .env
```

2. **Start containers:**
```bash
docker-compose up -d --build
```

3. **Configure Laravel:**
```bash
# Enter Laravel container
docker exec -it rodeo-app /bin/sh

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate
```

### 🐧 Linux-specific Configuration

On Linux systems, you may need to manually create the MySQL database:

```bash
# Connect to MySQL container
docker exec -it rodeo-mysql mysql -u root -p

# Create database (use password from .env)
CREATE DATABASE rodeo_db;
exit
```

Then run migrations:
```bash
docker exec -it rodeo-app php artisan migrate
```

### 🔧 Available Services

Once the environment is up, you'll have access to:

| Service | URL | Description |
|---------|-----|-------------|
| **Frontend** | `http://localhost:8000` | Main Laravel + Vue application |
| **RecordingAPI** | `http://localhost:3000` | Express.js records API |
| **MySQL** | `localhost:3306` | Main database |
| **MongoDB** | `localhost:27017` | Records database |

### 🧪 Verify Installation

```bash
# Check all containers are running
docker-compose ps

# Check application logs
docker-compose logs rodeo-app

# Check RecordingAPI logs
docker-compose logs recording-api
```

## 📊 RecordingAPI

The RecordingAPI microservice handles all veterinary records:

### Main Endpoints
- `GET /api/recordings` - List records
- `POST /api/recordings` - Create record
- `GET /api/recordings/:id` - Get specific record
- `PUT /api/recordings/:id` - Update record
- `DELETE /api/recordings/:id` - Delete record

### Supported Record Types
- Inseminations
- Vaccinations
- Weight checks
- Ultrasounds/Ecographies
- Natural breeding
- Health checks
- Other procedures

## 🛠️ Useful Development Commands

```bash
# Rebuild containers
docker-compose up -d --build

# View logs in real time
docker-compose logs -f

# Run Laravel commands
docker exec -it rodeo-app php artisan [command]

# Run RecordingAPI commands
docker exec -it recording-api npm run [command]

# Clean volumes (warning: deletes data)
docker-compose down -v
```

## 📝 Project Status

- ✅ Animal, rodeo, and client management
- ✅ RecordingAPI with MongoDB
- ✅ Complete Docker integration
- ✅ Responsive frontend with Vue 3
- 🔄 Veterinary recording system (in development)
- 📋 PDF/Excel export (planned)
- 🧪 Automated tests (planned)


## 📄 License

This project is open source and available under the MIT License.

## Contact
contacto@ss-schneiderservices.com
---


**Note**: This project uses Git Flow. The `development` contains the latest features in development.



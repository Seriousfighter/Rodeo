# 🐄 Rodeo

ALL IA, ECXEPT LINE 3 OF THE README.md

Full-stack application for cattle rodeo management, designed for veterinarians and agricultural professionals working with multiple establishments and clients.

## 📋 Description

**Rodeo** allows you to register and manage different groups of animals (rodeos) from various clients. Each group can undergo protocols such as inseminations, vaccinations, weight checks, ultrasounds, among others.

**NEW**: The platform now includes comprehensive **Diet Management** capabilities, allowing professionals to create, customize, and assign nutritional diets to different animal groups, with detailed tracking and recording features.

The platform is designed for professional use, with PDF or Excel export functionalities, and a modern microservices architecture based on Laravel + Vue with specialized APIs for records and diet management.

This project is under active development and serves as a practical example of my work approach, code structure, and versioning process.

## 🚀 Technologies Used

### Main Backend
- ⚙️ Laravel 10 (Backend, API, business logic)
- 🐬 MySQL (Main database)

### RecordingAPI (Microservice)
- 🟢 Node.js + Express.js (Veterinary records API)
- 🍃 MongoDB (Records database)

### DietAPI (Microservice) ✨ NEW
- 🟢 Node.js + Express.js (Diet management API)
- 🍃 MongoDB (Diet database)
- 🥗 Complete CRUD for diets and ingredients
- 📊 Group diet assignments and customizations

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
                                │                        │
                       ┌─────────────────┐               │
                       │    DietAPI      │               │
                       │   Express.js    │◄──────────────┤
                       │   + MongoDB     │               │
                       │   (Diets &      │               │
                       │   Ingredients)  │               │
                       └─────────────────┘               │
                                │                        │
                                ▼                        ▼
                       ┌─────────────────┐    ┌─────────────────┐
                       │     MySQL       │    │    MongoDB      │
                       │   (Main DB)     │    │ (Records & Diets)│
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
| **DietAPI** | `http://localhost:3001` | Express.js diet management API |
| **MySQL** | `localhost:3306` | Main database |
| **MongoDB** | `localhost:27017` | Records and diets database |

### 🧪 Verify Installation

```bash
# Check all containers are running
docker-compose ps

# Check application logs
docker-compose logs rodeo-app

# Check RecordingAPI logs
docker-compose logs recording-api

# Check DietAPI logs
docker-compose logs diet-api
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

## 🥗 DietAPI - Diet Management System ✨ NEW

The DietAPI microservice provides comprehensive diet management capabilities:

### 🔧 Core Features
- **Complete Diet CRUD**: Create, read, update, and delete nutritional diets
- **Ingredient Management**: Manage ingredient database with nutritional information
- **Group Diet Assignment**: Assign specific diets to animal groups
- **Diet Customization**: Modify assigned diets without affecting original templates
- **Multi-condition Support**: Different diets for various weather/seasonal conditions

### 🌐 Main Endpoints

#### Diet Management
- `GET /diets` - List all diets with filters
- `POST /diets` - Create new diet
- `GET /diets/:id` - Get specific diet details
- `PUT /diets/:id` - Update diet
- `DELETE /diets/:id` - Delete diet
- `GET /diets/type/:type` - Get diets by type (maintenance, growth, reproduction, etc.)

#### Ingredient Management
- `GET /ingredients` - List all ingredients
- `POST /ingredients` - Create new ingredient
- `GET /ingredients/:id` - Get ingredient details
- `PUT /ingredients/:id` - Update ingredient
- `DELETE /ingredients/:id` - Delete ingredient

#### Group Diet Management
- `GET /group-diets` - List all group diet assignments
- `POST /group-diets` - Assign diet to group
- `GET /group-diets/group/:groupId` - Get diets assigned to specific group
- `PUT /group-diets/:groupId/diets/:dietId` - Update group-specific diet
- `POST /group-diets/:groupId/activate/:dietId` - Set active diet for group
- `DELETE /group-diets/:groupId/diets/:dietId` - Remove diet from group

### 📋 Diet Types Supported
- **Maintenance**: Basic nutritional requirements
- **Growth**: Enhanced nutrition for growing animals
- **Reproduction**: Specialized nutrition for breeding
- **Lactation**: High-energy diets for lactating animals
- **Custom**: User-defined specialized diets

### 🎯 Current Capabilities
- ✅ Create and manage master diet templates
- ✅ Assign diets to specific animal groups
- ✅ Customize assigned diets without affecting originals
- ✅ Multiple diet conditions (normal, rain, drought, seasonal)
- ✅ Priority-based diet selection
- ✅ Active diet management per group

### 🚀 Future Enhancements (Planned)
- 📅 **Daily Diet Recording**: Track daily food consumption for each animal
- 📊 **Lifetime Nutrition History**: Complete nutritional record throughout animal's life
- 🔄 **Automatic Diet Transitions**: Scheduled diet changes based on animal lifecycle
- 📈 **Nutritional Analytics**: Performance correlation with diet effectiveness
- 🍽️ **Portion Control**: Precise feeding amount calculations
- 📱 **Mobile Recording**: Field-friendly diet recording interface

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

# Run DietAPI commands
docker exec -it diet-api npm run [command]

# Clean volumes (warning: deletes data)
docker-compose down -v
```

## 📝 Project Status

- ✅ Animal, rodeo, and client management
- ✅ RecordingAPI with MongoDB
- ✅ **Complete Diet Management System** ✨
- ✅ **Group Diet Assignment & Customization** ✨
- ✅ Complete Docker integration
- ✅ Responsive frontend with Vue 3
- 🔄 Veterinary recording system (in development)
- 🔄 **Daily diet recording per animal** (in development)
- 📋 **Lifetime nutrition tracking** (planned)
- 📋 PDF/Excel export (planned)
- 🧪 Automated tests (planned)

## 📄 License

This project is open source and available under the MIT License.

## Contact
contacto@ss-schneiderservices.com
---

**Note**: This project uses Git Flow. The `development` branch contains the latest features in development.
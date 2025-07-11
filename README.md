# 🐄 Rodeo

Aplicación fullstack para la gestión de rodeos ganaderos, diseñada para veterinarios y profesionales del agro que trabajan con múltiples establecimientos y clientes.

## 📋 Descripción

**Rodeo** permite registrar y administrar diferentes grupos de animales (rodeos) de diversos clientes. Cada grupo puede someterse a protocolos como inseminaciones, vacunaciones, chequeos de peso, ecografías, entre otros.

La plataforma está pensada para uso profesional, con funcionalidades de exportación de planillas en PDF o Excel, y una arquitectura moderna basada en Laravel + Vue.

Este proyecto se encuentra en desarrollo activo y sirve como ejemplo práctico de mi forma de trabajo, estructura de código y proceso de versionado.

## 🚀 Tecnologías utilizadas

- ⚙️ Laravel 10 (Backend, API, lógica de negocio)
- 🖼️ Vue 3 + Inertia.js (Frontend SSR)
- 🎨 TailwindCSS (Estilos)
- 🐬 MySQL (Base de datos)
- 🐳 Docker / Docker Compose (Entorno de desarrollo)
- 🧪 PHPUnit (Tests - planeado)

## 🧰 Instalación y ejecución local

Requisitos: Docker y Docker Compose instalados.

```bash
git clone https://github.com/Seriousfighter/Rodeo.git
cd Rodeo
cp .env.example .env
docker-compose up -d --build

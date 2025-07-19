<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
  <div class="min-h-screen bg-white">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-100">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center space-x-2">
            <span class="text-2xl">🐄</span>
            <span class="text-xl font-bold text-gray-900">Rodeo</span>
          </div>
          <a
            href="/login"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200"
          >
            Ingresar
          </a>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-green-50 to-blue-50 py-20 lg:py-32">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <div class="mb-8">
            <span class="text-6xl sm:text-8xl">🐄</span>
          </div>
          <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
            Gestión Ganadera
            <span class="text-green-600">Inteligente</span>
          </h1>
          <p class="text-xl sm:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto">
            Administra tus rodeos, registros veterinarios y datos ganaderos desde una plataforma moderna y completa
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a
              href="/login"
              class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-colors duration-200 shadow-lg hover:shadow-xl"
            >
              Comenzar Ahora
            </a>
            <button class="border-2 border-green-600 text-green-600 hover:bg-green-600 hover:text-white px-8 py-4 rounded-lg text-lg font-semibold transition-colors duration-200">
              Ver Demo
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
            Todo lo que necesitas para tu ganadería
          </h2>
          <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Una solución completa para la gestión moderna de establecimientos ganaderos
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <!-- Feature 1: Manage Rodeos -->
          <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
            <div class="text-4xl mb-4">🐮</div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Gestión de Rodeos</h3>
            <p class="text-gray-600">
              Organiza y administra grupos de animales de manera eficiente con seguimiento detallado de cada rodeo.
            </p>
          </div>

          <!-- Feature 2: Veterinary Records -->
          <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
            <div class="text-4xl mb-4">💉</div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Registros Veterinarios</h3>
            <p class="text-gray-600">
              Lleva control completo de inseminaciones, vacunaciones, peso y todos los datos sanitarios.
            </p>
          </div>

          <!-- Feature 3: Data Export -->
          <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
            <div class="text-4xl mb-4">📊</div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Exportación de Datos</h3>
            <p class="text-gray-600">
              Genera reportes profesionales en PDF y Excel para análisis y presentaciones.
            </p>
          </div>

          <!-- Feature 4: Multi-client Support -->
          <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
            <div class="text-4xl mb-4">🏢</div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Multi-establecimiento</h3>
            <p class="text-gray-600">
              Soporte para múltiples clientes y establecimientos desde una sola plataforma.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Tech Stack Section -->
    <section class="py-20 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
            Tecnología de Vanguardia
          </h2>
          <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Construido con las mejores herramientas para garantizar rendimiento, escalabilidad y confiabilidad
          </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
          <!-- Laravel -->
          <div class="bg-white p-8 rounded-xl shadow-md text-center hover:shadow-lg transition-shadow duration-300">
            <div class="text-5xl mb-4 text-red-500">⚡</div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Laravel</h3>
            <p class="text-gray-600 text-sm">Framework PHP robusto y elegante</p>
          </div>

          <!-- Vue.js -->
          <div class="bg-white p-8 rounded-xl shadow-md text-center hover:shadow-lg transition-shadow duration-300">
            <div class="text-5xl mb-4 text-green-500">🖖</div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Vue.js</h3>
            <p class="text-gray-600 text-sm">Framework JavaScript progresivo</p>
          </div>

          <!-- MongoDB -->
          <div class="bg-white p-8 rounded-xl shadow-md text-center hover:shadow-lg transition-shadow duration-300">
            <div class="text-5xl mb-4 text-green-600">🍃</div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">MongoDB</h3>
            <p class="text-gray-600 text-sm">Base de datos NoSQL flexible</p>
          </div>

          <!-- Docker -->
          <div class="bg-white p-8 rounded-xl shadow-md text-center hover:shadow-lg transition-shadow duration-300">
            <div class="text-5xl mb-4 text-blue-500">🐳</div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Docker</h3>
            <p class="text-gray-600 text-sm">Contenedores para desarrollo ágil</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-green-600">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
          ¿Listo para modernizar tu ganadería?
        </h2>
        <p class="text-xl text-green-100 mb-8 max-w-2xl mx-auto">
          Únete a los ganaderos que ya están optimizando sus operaciones con Rodeo
        </p>
        <a
          href="/login"
          class="bg-white text-green-600 hover:bg-gray-100 px-8 py-4 rounded-lg text-lg font-semibold transition-colors duration-200 shadow-lg hover:shadow-xl inline-block"
        >
          Comenzar Gratis
        </a>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Brand -->
          <div>
            <div class="flex items-center space-x-2 mb-4">
              <span class="text-2xl">🐄</span>
              <span class="text-xl font-bold">Rodeo</span>
            </div>
            <p class="text-gray-400">
              Plataforma moderna para la gestión integral de establecimientos ganaderos.
            </p>
          </div>

          <!-- Contact Info -->
          <div>
            <h3 class="text-lg font-semibold mb-4">Contacto</h3>
            <div class="space-y-2 text-gray-400">
              <p>📧 Contacto@ss-schneiderservices.com</p>
              <p>📱 +54 3435303842</p>
              <p>📍 Entre Rios, Argentina</p>
            </div>
          </div>

          <!-- Links -->
          <div>
            <h3 class="text-lg font-semibold mb-4">Enlaces</h3>
            <div class="space-y-2">
              <a
                href="https://github.com/your-username/rodeo"
                target="_blank"
                rel="noopener noreferrer"
                class="text-gray-400 hover:text-white transition-colors duration-200 flex items-center space-x-2"
              >
                <span>🐙</span>
                <span>GitHub Repository</span>
              </a>
              <a
                href="/login"
                class="text-gray-400 hover:text-white transition-colors duration-200 block"
              >
                Iniciar Sesión
              </a>
              <a
                href="/docs"
                class="text-gray-400 hover:text-white transition-colors duration-200 block"
              >
                Documentación
              </a>
            </div>
          </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
          <p>&copy; {{ new Date().getFullYear() }} Rodeo. Todos los derechos reservados.</p>
        </div>
      </div>
    </footer>
  </div>
</template>



<style scoped>
/* Additional custom styles if needed */
.gradient-text {
  background: linear-gradient(135deg, #10b981, #3b82f6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
</style>
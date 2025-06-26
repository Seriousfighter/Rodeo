<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            <i class="fas fa-user-circle text-green-600 mr-3"></i>
                            Detalles del Ganadero
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Información completa del cliente y sus rodeos registrados
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <Link 
                            :href="route('clients.edit', client.id)"
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                        >
                            <i class="fas fa-edit mr-2"></i>
                            Editar
                        </Link>
                        <Link 
                            :href="route('clients.index')"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                        >
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Client Information Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <!-- Card Header -->
                        <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-green-600 text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-900">
                                        {{ client.name }} {{ client.last_name }}
                                    </h2>
                                    <p class="text-sm text-gray-600">Ganadero</p>
                                </div>
                            </div>
                        </div>

                        <!-- Client Details -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Personal Information -->
                                <div class="space-y-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                        Información Personal
                                    </h3>
                                    
                                    <div class="space-y-3">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-user text-gray-400 w-5"></i>
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">Nombre Completo</p>
                                                <p class="text-sm text-gray-600">{{ client.name }} {{ client.last_name }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-envelope text-gray-400 w-5"></i>
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">Correo Electrónico</p>
                                                <p class="text-sm text-gray-600">{{ client.email }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-phone text-gray-400 w-5"></i>
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">Teléfono</p>
                                                <p class="text-sm text-gray-600">{{ client.phone }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Registration Information -->
                                <div class="space-y-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                                        <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                                        Información de Registro
                                    </h3>
                                    
                                    <div class="space-y-3">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-calendar-plus text-gray-400 w-5"></i>
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">Fecha de Registro</p>
                                                <p class="text-sm text-gray-600">{{ formatDate(client.created_at) }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-calendar-check text-gray-400 w-5"></i>
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">Última Actualización</p>
                                                <p class="text-sm text-gray-600">{{ formatDate(client.updated_at) }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-clipboard-list text-gray-400 w-5"></i>
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">Total de Rodeos</p>
                                                <p class="text-sm text-gray-600">{{ client.rodeos ? client.rodeos.length : 0 }} registrados</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Stats -->
                <div class="space-y-6">
                    <!-- Quick Actions Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">
                                <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                                Acciones Rápidas
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <Link 
                                :href="route('clients.edit', client.id)"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors duration-200"
                            >
                                <i class="fas fa-edit mr-2"></i>
                                Editar Cliente
                            </Link>
                            
                            <Link 
                                :href="route('rodeos.create', { client_id: client.id })"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200"
                            >
                                <i class="fas fa-plus mr-2"></i>
                                Nuevo Rodeo
                            </Link>

                            <button
                                @click="confirmDelete"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200"
                            >
                                <i class="fas fa-trash mr-2"></i>
                                Eliminar Cliente
                            </button>
                        </div>
                    </div>

                    <!-- Stats Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">
                                <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                                Estadísticas
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 gap-4">
                                <div class="bg-green-50 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-clipboard-list text-green-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-green-900">Rodeos Activos</p>
                                            <p class="text-2xl font-bold text-green-600">{{ client.rodeos ? client.rodeos.length : 0 }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-blue-50 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-cow text-blue-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-blue-900">Total Animales</p>
                                            <p class="text-2xl font-bold text-blue-600">{{ getTotalAnimals() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rodeos Section -->
            <div class="mt-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clipboard-list text-green-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900">Rodeos Registrados</h3>
                                    <p class="text-sm text-gray-600">Lista de todos los rodeos asociados a este cliente</p>
                                </div>
                            </div>
                            <Link 
                                :href="route('rodeos.create', { client_id: client.id })"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200"
                            >
                                <i class="fas fa-plus mr-2"></i>
                                Nuevo Rodeo
                            </Link>
                        </div>
                    </div>

                    <div class="p-6">
                        <div v-if="!client.rodeos || client.rodeos.length === 0" class="text-center py-8">
                            <i class="fas fa-clipboard text-gray-400 text-4xl mb-4"></i>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">No hay rodeos registrados</h4>
                            <p class="text-gray-600 mb-4">Este cliente aún no tiene rodeos asociados</p>
                            <Link 
                                :href="route('rodeos.create', { client_id: client.id })"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200"
                            >
                                <i class="fas fa-plus mr-2"></i>
                                Crear Primer Rodeo
                            </Link>
                        </div>

                        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div 
                                v-for="rodeo in client.rodeos" 
                                :key="rodeo.id"
                                class="bg-gray-50 rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow duration-200"
                            >
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900">{{ rodeo.name }}</h4>
                                        <p class="text-sm text-gray-600">{{ rodeo.location }}</p>
                                    </div>
                                    <Link 
                                        :href="route('rodeos.show', rodeo.id)"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                        title="Ver rodeo"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </Link>
                                </div>
                                
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-map-marker-alt text-gray-400 text-sm"></i>
                                        <span class="text-sm text-gray-600">{{ rodeo.location || 'Sin ubicación' }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-calendar text-gray-400 text-sm"></i>
                                        <span class="text-sm text-gray-600">{{ formatDate(rodeo.created_at) }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-cow text-gray-400 text-sm"></i>
                                        <span class="text-sm text-gray-600">{{ rodeo.animals ? rodeo.animals.length : 0 }} animales</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <div class="flex items-center space-x-3 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Confirmar Eliminación</h3>
                </div>
                <p class="text-gray-600 mb-6">
                    ¿Estás seguro de que deseas eliminar a <strong>{{ client.name }} {{ client.last_name }}</strong>? 
                    Esta acción eliminará también todos sus rodeos y no se puede deshacer.
                </p>
                <div class="flex space-x-3">
                    <button
                        @click="cancelDelete"
                        class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition-colors duration-200"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="deleteClient"
                        class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200"
                    >
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'

// Props
const props = defineProps({
    client: {
        type: Object,
        required: true
    }
})

// Reactive data
const showDeleteModal = ref(false)

// Methods
const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const getTotalAnimals = () => {
    if (!props.client.rodeos) return 0
    return props.client.rodeos.reduce((total, rodeo) => {
        return total + (rodeo.animals ? rodeo.animals.length : 0)
    }, 0)
}

const confirmDelete = () => {
    showDeleteModal.value = true
}

const cancelDelete = () => {
    showDeleteModal.value = false
}

const deleteClient = () => {
    router.delete(route('clients.destroy', props.client.id), {
        onSuccess: () => {
            router.visit(route('clients.index'))
        }
    })
}
</script>

<style scoped>
.transition-shadow {
    transition: box-shadow 0.2s ease-in-out;
}
</style>
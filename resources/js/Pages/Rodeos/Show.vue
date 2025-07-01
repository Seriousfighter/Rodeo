<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            <i class="fas fa-clipboard-list text-green-600 mr-3"></i>
                            Detalles del Rodeo
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Información completa del rodeo y sus animales registrados
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <Link 
                            :href="route('rodeos.edit', rodeo.id)"
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                        >
                            <i class="fas fa-edit mr-2"></i>
                            Editar
                        </Link>
                        <Link 
                            :href="route('rodeos.index')"
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
                <!-- Rodeo Information Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <!-- Card Header -->
                        <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clipboard-list text-green-600 text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-900">
                                        {{ rodeo.name }}
                                    </h2>
                                    <p class="text-sm text-gray-600">Rodeo de {{ rodeo.client?.name }} {{ rodeo.client?.last_name }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Rodeo Details -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Basic Information -->
                                <div class="space-y-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                        Información Básica
                                    </h3>
                                    
                                    <div class="space-y-3">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-tag text-gray-400 w-5"></i>
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">Nombre del Rodeo</p>
                                                <p class="text-sm text-gray-600">{{ rodeo.name }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-user text-gray-400 w-5"></i>
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">Cliente</p>
                                                <p class="text-sm text-gray-600">{{ rodeo.client?.name }} {{ rodeo.client?.last_name }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-map-marker-alt text-gray-400 w-5"></i>
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">Ubicación</p>
                                                <p class="text-sm text-gray-600">{{ rodeo.location || 'No especificada' }}</p>
                                            </div>
                                        </div>


                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-file-alt text-gray-400 w-5"></i>
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">RENSPA</p>
                                                <p class="text-sm text-gray-600">{{ rodeo.renspa || 'No especificado' }}</p>
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

                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-cow text-gray-400 w-5"></i>
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">Total de Animales</p>
                                                <p class="text-sm text-gray-600">{{ rodeo.animals ? rodeo.animals.length : 0 }} registrados</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description Section -->
                            <div v-if="rodeo.description" class="mt-6 pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-3">
                                    <i class="fas fa-align-left text-blue-600 mr-2"></i>
                                    Descripción
                                </h3>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ rodeo.description }}</p>
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
                                :href="route('rodeos.edit', rodeo.id)"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors duration-200"
                            >
                                <i class="fas fa-edit mr-2"></i>
                                Editar Rodeo
                            </Link>
                            
                            <Link 
                                :href="route('animals.create', { rodeo_id: rodeo.id })"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200"
                            >
                                <i class="fas fa-plus mr-2"></i>
                                Nuevo Animal
                            </Link>

                            <Link 
                                :href="route('clients.show', rodeo.client.id)"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200"
                            >
                                <i class="fas fa-user mr-2"></i>
                                Ver Cliente
                            </Link>

                            <button
                                @click="confirmDelete"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200"
                            >
                                <i class="fas fa-trash mr-2"></i>
                                Eliminar Rodeo
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
                                            <i class="fas fa-cow text-green-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-green-900">Animales</p>
                                            <p class="text-2xl font-bold text-green-600">{{ rodeo.animals ? rodeo.animals.length : 0 }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Animals Section -->
            <div class="mt-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-cow text-green-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900">Animales Registrados</h3>
                                    <p class="text-sm text-gray-600">Lista de todos los animales en este rodeo</p>
                                </div>
                            </div>
                            <Link 
                                :href="route('animals.create', { rodeo_id: rodeo.id })"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200"
                            >
                                <i class="fas fa-plus mr-2"></i>
                                Nuevo Animal
                            </Link>
                        </div>
                    </div>

                    <div class="p-6">
                        <div v-if="!rodeo.animals || rodeo.animals.length === 0" class="text-center py-8">
                            <i class="fas fa-cow text-gray-400 text-4xl mb-4"></i>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">No hay animales registrados</h4>
                            <p class="text-gray-600 mb-4">Este rodeo aún no tiene animales asociados</p>
                            <Link 
                                :href="route('animals.create', { rodeo_id: rodeo.id })"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200"
                            >
                                <i class="fas fa-plus mr-2"></i>
                                Agregar Primer Animal
                            </Link>
                        </div>

                        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div 
                                v-for="animal in rodeo.animals" 
                                :key="animal.id"
                                class="bg-gray-50 rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow duration-200"
                            >
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900">{{ animal.caravana }}</h4>
                                        <p class="text-sm text-gray-600">{{ animal.caravana_oficial || 'Sin caravana oficial' }}</p>
                                    </div>
                                    <Link 
                                        :href="route('animals.show', animal.id)"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                        title="Ver animal"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </Link>
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
                    ¿Estás seguro de que deseas eliminar el rodeo <strong>{{ rodeo.name }}</strong>? 
                    Esta acción eliminará también todos los animales asociados y no se puede deshacer.
                </p>
                <div class="flex space-x-3">
                    <button
                        @click="cancelDelete"
                        class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition-colors duration-200"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="deleteRodeo"
                        class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200"
                    >
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
  
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'

// Props
const props = defineProps({
    rodeo: {
        type: Object,
        required: true
    }
})

// Reactive data
const showDeleteModal = ref(false)



const confirmDelete = () => {
    showDeleteModal.value = true
}

const cancelDelete = () => {
    showDeleteModal.value = false
}

const deleteRodeo = () => {
    router.delete(route('rodeos.destroy', props.rodeo.id), {
        onSuccess: () => {
            router.visit(route('rodeos.index'))
        }
    })
}
</script>

<style scoped>
.transition-shadow {
    transition: box-shadow 0.2s ease-in-out;
}
</style>
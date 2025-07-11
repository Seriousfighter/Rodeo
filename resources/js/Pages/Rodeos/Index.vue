<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            <i class="fas fa-clipboard-list text-green-600 mr-3"></i>
                            Rodeos Registrados
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Gestión de rodeos para control reproductivo bovino
                        </p>
                    </div>
                    <Link 
                        :href="route('rodeos.create')"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                    >
                        <i class="fas fa-plus mr-2"></i>
                        Nuevo Rodeo
                    </Link>
                </div>
            </div>
        </div>

        <!-- Search and Filter Bar -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <input
                                v-model="searchTerm"
                                type="text"
                                placeholder="Buscar por nombre, ubicación o cliente..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            />
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">
                            Total: <span class="font-semibold text-green-600">{{ filteredRodeos.length }}</span> rodeos
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rodeos Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <div v-if="filteredRodeos.length === 0" class="text-center py-12">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <i class="fas fa-clipboard-list text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay rodeos registrados</h3>
                    <p class="text-gray-600 mb-6">Comienza agregando tu primer rodeo al sistema</p>
                    <Link 
                        :href="route('rodeos.create')"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200"
                    >
                        <i class="fas fa-plus mr-2"></i>
                        Agregar Rodeo
                    </Link>
                </div>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div 
                    v-for="rodeo in filteredRodeos" 
                    :key="rodeo.id"
                    class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200"
                >
                    <!-- Rodeo Card Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clipboard-list text-green-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ rodeo.name }}
                                    </h3>
                                    <p class="text-sm text-gray-600">{{ rodeo.client?.name }} {{ rodeo.client?.last_name }}</p>
                                </div>
                            </div>
                            <div class="flex space-x-1">
                                <Link 
                                    :href="route('rodeos.show', rodeo.id)"
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                    title="Ver detalles"
                                >
                                    <i class="fas fa-eye"></i>
                                </Link>
                                <Link 
                                    :href="route('rodeos.edit', rodeo.id)"
                                    class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors duration-200"
                                    title="Editar"
                                >
                                    <i class="fas fa-edit"></i>
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Rodeo Details -->
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-map-marker-alt text-gray-400 w-4"></i>
                                <span class="text-sm text-gray-600">{{ rodeo.location || 'Sin ubicación' }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-calendar text-gray-400 w-4"></i>
                                <span class="text-sm text-gray-600">
                                    {{ rodeo.date ? formatDate(rodeo.date) : 'Fecha no especificada' }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-file-alt text-gray-400 w-4"></i>
                                <span class="text-sm text-gray-600">
                                    RENSPA: {{ rodeo.renspa || 'No especificado' }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-clock text-gray-400 w-4"></i>
                                <span class="text-sm text-gray-600">
                                    Creado: {{ formatDate(rodeo.created_at) }}
                                </span>
                            </div>
                        </div>

                        <!-- Animals Summary -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-cow text-green-600"></i>
                                    <span class="text-sm font-medium text-gray-700">Animales</span>
                                </div>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                    {{ rodeo.animals ? rodeo.animals.length : 0 }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-lg">
                        <div class="flex space-x-2">
                            <Link 
                                :href="route('rodeos.show', rodeo.id)"
                                class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg text-center transition-colors duration-200"
                            >
                                Ver Detalles
                            </Link>
                            <button
                                @click="confirmDelete(rodeo)"
                                class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                                title="Eliminar rodeo"
                            >
                                <i class="fas fa-trash"></i>
                            </button>
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
                    ¿Estás seguro de que deseas eliminar el rodeo <strong>{{ rodeoToDelete?.name }}</strong>? 
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
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'

// Props
const props = defineProps({
    rodeos: {
        type: Array,
        default: () => []
    }
})

// Reactive data
const searchTerm = ref('')
const showDeleteModal = ref(false)
const rodeoToDelete = ref(null)

// Computed properties
const filteredRodeos = computed(() => {
    if (!searchTerm.value) return props.rodeos
    
    return props.rodeos.filter(rodeo => 
        rodeo.name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
        (rodeo.location && rodeo.location.toLowerCase().includes(searchTerm.value.toLowerCase())) ||
        (rodeo.client?.name && rodeo.client.name.toLowerCase().includes(searchTerm.value.toLowerCase())) ||
        (rodeo.client?.last_name && rodeo.client.last_name.toLowerCase().includes(searchTerm.value.toLowerCase()))
    )
})

// Methods
const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

const confirmDelete = (rodeo) => {
    rodeoToDelete.value = rodeo
    showDeleteModal.value = true
}

const cancelDelete = () => {
    rodeoToDelete.value = null
    showDeleteModal.value = false
}

const deleteRodeo = () => {
    if (rodeoToDelete.value) {
        router.delete(route('rodeos.destroy', rodeoToDelete.value.id), {
            onSuccess: () => {
                cancelDelete()
            }
        })
    }
}
</script>

<style scoped>
.transition-shadow {
    transition: box-shadow 0.2s ease-in-out;
}
</style>
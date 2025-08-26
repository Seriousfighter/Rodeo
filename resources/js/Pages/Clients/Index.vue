<template>
    <AppLayout title="Clientes">
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            <i class="fas fa-users text-green-600 mr-3"></i>
                            Ganaderos Registrados
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Gestión de clientes para control reproductivo bovino
                        </p>
                    </div>
                    <Link 
                        :href="route('clients.create')"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                    >
                        <i class="fas fa-plus mr-2"></i>
                        Nuevo Cliente
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
                                placeholder="Buscar por nombre, email o teléfono..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            />
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">
                            Total: <span class="font-semibold text-green-600">{{ filteredClients.length }}</span> clientes
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clients Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <div v-if="filteredClients.length === 0" class="text-center py-12">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <i class="fas fa-cow text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay clientes registrados</h3>
                    <p class="text-gray-600 mb-6">Comienza agregando tu primer ganadero al sistema</p>
                    <Link 
                        :href="route('clients.create')"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200"
                    >
                        <i class="fas fa-plus mr-2"></i>
                        Agregar Cliente
                    </Link>
                </div>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div 
                    v-for="cliente in filteredClients" 
                    :key="cliente.id"
                    class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200"
                >
                    <!-- Client Card Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-green-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ cliente.name }} {{ cliente.last_name }}
                                    </h3>
                                    <p class="text-sm text-gray-600">Ganadero</p>
                                </div>
                            </div>
                            <div class="flex space-x-1">
                                <Link 
                                    :href="route('clients.show', cliente.id)"
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                    title="Ver detalles"
                                >
                                    <i class="fas fa-eye"></i>
                                </Link>
                                <Link 
                                    :href="route('clients.edit', cliente.id)"
                                    class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors duration-200"
                                    title="Editar"
                                >
                                    <i class="fas fa-edit"></i>
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Client Details -->
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-envelope text-gray-400 w-4"></i>
                                <span class="text-sm text-gray-600">{{ cliente.email }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-phone text-gray-400 w-4"></i>
                                <span class="text-sm text-gray-600">{{ cliente.phone }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-calendar text-gray-400 w-4"></i>
                                <span class="text-sm text-gray-600">
                                    Registrado: {{ formatDate(cliente.created_at) }}
                                </span>
                            </div>
                        </div>

                        <!-- Rodeos Summary -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-clipboard-list text-green-600"></i>
                                    <span class="text-sm font-medium text-gray-700">Rodeos</span>
                                </div>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                    {{ cliente.rodeos ? cliente.rodeos.length : 0 }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-lg">
                        <div class="flex space-x-2">
                            <Link 
                                :href="route('clients.show', cliente.id)"
                                class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg text-center transition-colors duration-200"
                            >
                                Ver Detalles
                            </Link>
                            <button
                                @click="confirmDelete(cliente)"
                                class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                                title="Eliminar cliente"
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
                    ¿Estás seguro de que deseas eliminar a <strong>{{ clientToDelete?.name }} {{ clientToDelete?.last_name }}</strong>? 
                    Esta acción no se puede deshacer.
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
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
// Props
const props = defineProps({
    clientes: {
        type: Array,
        default: () => []
    }
})

// Reactive data
const searchTerm = ref('')
const showDeleteModal = ref(false)
const clientToDelete = ref(null)

// Computed properties
const filteredClients = computed(() => {
    if (!searchTerm.value) return props.clientes
    
    return props.clientes.filter(cliente => 
        cliente.name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
        cliente.last_name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
        cliente.email.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
        cliente.phone.includes(searchTerm.value)
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

const confirmDelete = (cliente) => {
    clientToDelete.value = cliente
    showDeleteModal.value = true
}

const cancelDelete = () => {
    clientToDelete.value = null
    showDeleteModal.value = false
}

const deleteClient = () => {
    if (clientToDelete.value) {
        router.delete(route('clients.destroy', clientToDelete.value.id), {
            onSuccess: () => {
                cancelDelete()
            }
        })
    }
}
</script>

<style scoped>
/* Additional custom styles if needed */
.transition-shadow {
    transition: box-shadow 0.2s ease-in-out;
}
</style>
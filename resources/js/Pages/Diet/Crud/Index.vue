<template>
    <AppLayout title="Dietas">  
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            <i class="fas fa-utensils text-green-600 mr-3"></i>
                            Dietas Registradas
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Gestión de dietas para alimentación bovina
                        </p>
                    </div>
                    <Link 
                        :href="route('diets.create')"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                    >
                        <i class="fas fa-plus mr-2"></i>
                        Nueva Dieta
                    </Link>
                </div>
            </div>
        </div>

        <!-- Search and Filter Bar -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <input
                                v-model="searchTerm"
                                type="text"
                                placeholder="Buscar por nombre de dieta..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            />
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <select
                            v-model="selectedType"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        >
                            <option value="">Todos los tipos</option>
                            <option value="maintenance">Mantenimiento</option>
                            <option value="growth">Crecimiento</option>
                            <option value="reproduction">Reproducción</option>
                            <option value="lactation">Lactancia</option>
                            <option value="custom">Personalizada</option>
                        </select>
                        <select
                            v-model="selectedStatus"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        >
                            <option value="active">Activas</option>
                            <option value="inactive">Inactivas</option>
                            <option value="">Todas</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-4">
                    <span class="text-sm text-gray-600">
                        Total: <span class="font-semibold text-green-600">{{ totalDiets }}</span> dietas
                    </span>
                </div>
            </div>
        </div>

        <!-- Diets Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <div v-if="loading" class="text-center py-12">
                <i class="fas fa-spinner fa-spin text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-600">Cargando dietas...</p>
            </div>

            <div v-else-if="filteredDiets.length === 0" class="text-center py-12">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <i class="fas fa-utensils text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay dietas registradas</h3>
                    <p class="text-gray-600 mb-6">Comienza agregando tu primera dieta al sistema</p>
                    <Link 
                        :href="route('diets.create')"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200"
                    >
                        <i class="fas fa-plus mr-2"></i>
                        Crear Primera Dieta
                    </Link>
                </div>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div 
                    v-for="diet in paginatedDiets" 
                    :key="diet._id"
                    class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200"
                >
                    <!-- Diet Card Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <i :class="getDietTypeIcon(diet.diet_type)" class="text-green-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ diet.name }}
                                    </h3>
                                    <p class="text-sm text-gray-600">{{ getDietTypeName(diet.diet_type) }}</p>
                                </div>
                            </div>
                            <div class="flex space-x-1">
                                <Link 
                                    :href="route('diets.show', diet._id)"
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                    title="Ver detalles"
                                >
                                    <i class="fas fa-eye"></i>
                                </Link>
                                <Link 
                                    :href="route('diets.edit', diet._id)"
                                    class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors duration-200"
                                    title="Editar"
                                >
                                    <i class="fas fa-edit"></i>
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Diet Details -->
                    <div class="p-6">
                        <div class="space-y-3">
                            <div v-if="diet.description" class="text-sm text-gray-600">
                                {{ diet.description.substring(0, 120) }}{{ diet.description.length > 120 ? '...' : '' }}
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-weight-hanging text-gray-400 w-4"></i>
                                <span class="text-sm text-gray-600">
                                    <strong>Peso Total:</strong> {{ diet.total_weight || 'No especificado' }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-list text-gray-400 w-4"></i>
                                <span class="text-sm text-gray-600">
                                    <strong>Componentes:</strong> {{ diet.components?.length || 0 }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-calendar text-gray-400 w-4"></i>
                                <span class="text-sm text-gray-600">
                                    Creada: {{ formatDate(diet.createdAt) }}
                                </span>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <span 
                                    :class="getStatusBadgeClass(diet.status)"
                                    class="px-2 py-1 text-xs font-semibold rounded-full"
                                >
                                    {{ diet.status === 'active' ? 'Activa' : 'Inactiva' }}
                                </span>
                                <div class="flex items-center space-x-2 text-xs text-gray-500">
                                    <i class="fas fa-flask"></i>
                                    <span>{{ getDietTypeName(diet.diet_type) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-lg">
                        <div class="flex space-x-2">
                            <Link 
                                :href="route('diets.show', diet._id)"
                                class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg text-center transition-colors duration-200"
                            >
                                Ver Detalles
                            </Link>
                            <button
                                @click="confirmDelete(diet)"
                                class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                                title="Eliminar dieta"
                            >
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="totalPages > 1" class="mt-8 flex justify-center">
                <nav class="flex items-center space-x-2">
                    <button
                        @click="currentPage = Math.max(1, currentPage - 1)"
                        :disabled="currentPage === 1"
                        class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Anterior
                    </button>
                    
                    <span class="px-4 py-2 text-sm font-medium text-gray-700">
                        Página {{ currentPage }} de {{ totalPages }}
                    </span>
                    
                    <button
                        @click="currentPage = Math.min(totalPages, currentPage + 1)"
                        :disabled="currentPage === totalPages"
                        class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Siguiente
                    </button>
                </nav>
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
                    ¿Estás seguro de que deseas eliminar la dieta <strong>{{ dietToDelete?.name }}</strong>? 
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
                        @click="deleteDiet"
                        :disabled="deleting"
                        class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-medium rounded-lg transition-colors duration-200"
                    >
                        <i v-if="deleting" class="fas fa-spinner fa-spin mr-2"></i>
                        {{ deleting ? 'Eliminando...' : 'Eliminar' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

// Props
const props = defineProps({
    diets: {
        type: Object,
        default: () => ({ success: false, data: [], pagination: {} })
    },
    filters: {
        type: Object,
        default: () => ({})
    }
})

// Reactive data
const loading = ref(false)
const searchTerm = ref(props.filters.search || '')
const selectedType = ref(props.filters.diet_type || '')
const selectedStatus = ref(props.filters.status || 'active')
const currentPage = ref(1)
const itemsPerPage = ref(9)
const showDeleteModal = ref(false)
const dietToDelete = ref(null)
const deleting = ref(false)

// Computed properties
const allDiets = computed(() => {
    return props.diets.success ? props.diets.data : []
})

const filteredDiets = computed(() => {
    let filtered = allDiets.value

    if (searchTerm.value) {
        filtered = filtered.filter(diet => 
            diet.name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
            (diet.description && diet.description.toLowerCase().includes(searchTerm.value.toLowerCase()))
        )
    }

    if (selectedType.value) {
        filtered = filtered.filter(diet => diet.diet_type === selectedType.value)
    }

    if (selectedStatus.value) {
        filtered = filtered.filter(diet => diet.status === selectedStatus.value)
    }

    return filtered
})

const paginatedDiets = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value
    const end = start + itemsPerPage.value
    return filteredDiets.value.slice(start, end)
})

const totalDiets = computed(() => filteredDiets.value.length)
const totalPages = computed(() => Math.ceil(totalDiets.value / itemsPerPage.value))

// Methods
const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

const getDietTypeIcon = (type) => {
    const icons = {
        maintenance: 'fas fa-home',
        growth: 'fas fa-chart-line',
        reproduction: 'fas fa-heart',
        lactation: 'fas fa-baby',
        custom: 'fas fa-cog'
    }
    return icons[type] || 'fas fa-utensils'
}

const getDietTypeName = (type) => {
    const names = {
        maintenance: 'Mantenimiento',
        growth: 'Crecimiento',
        reproduction: 'Reproducción',
        lactation: 'Lactancia',
        custom: 'Personalizada'
    }
    return names[type] || 'Desconocido'
}

const getStatusBadgeClass = (status) => {
    return status === 'active' 
        ? 'bg-green-100 text-green-800' 
        : 'bg-red-100 text-red-800'
}

const confirmDelete = (diet) => {
    dietToDelete.value = diet
    showDeleteModal.value = true
}

const cancelDelete = () => {
    dietToDelete.value = null
    showDeleteModal.value = false
}

const deleteDiet = async () => {
    if (!dietToDelete.value) return
    
    deleting.value = true
    
    try {
        await router.delete(route('diets.destroy', dietToDelete.value._id), {
            onSuccess: () => {
                showDeleteModal.value = false
                dietToDelete.value = null
            },
            onError: (errors) => {
                console.error('Error deleting diet:', errors)
                alert('Error al eliminar la dieta')
            }
        })
    } catch (error) {
        console.error('Error deleting diet:', error)
        alert('Error al eliminar la dieta')
    } finally {
        deleting.value = false
    }
}

// Watch for filter changes
watch([searchTerm, selectedType, selectedStatus], () => {
    currentPage.value = 1
})
</script>

<style scoped>
.transition-shadow {
    transition: box-shadow 0.2s ease-in-out;
}
</style>
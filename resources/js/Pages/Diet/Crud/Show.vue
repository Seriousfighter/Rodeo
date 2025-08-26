<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            <i :class="getDietTypeIcon(diet.diet_type)" class="text-green-600 mr-3"></i>
                            {{ diet.name }}
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ diet.description || 'Sin descripción' }}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <Link 
                            :href="route('diets.index')"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                        >
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver a Dietas
                        </Link>
                        
                        <Link 
                            :href="route('diets.edit', diet._id)"
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                        >
                            <i class="fas fa-edit mr-2"></i>
                            Editar
                        </Link>
                        
                        <button
                            @click="confirmDelete"
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                        >
                            <i class="fas fa-trash mr-2"></i>
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Information -->
                <div class="lg:col-span-2">
                    <!-- Diet Overview -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
                        <div class="p-6 border-b border-gray-100">
                            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                Información General
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                                    <p class="text-gray-900 font-semibold">{{ diet.name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                                    <div class="flex items-center">
                                        <i :class="getDietTypeIcon(diet.diet_type)" class="text-green-600 mr-2"></i>
                                        <span class="text-gray-900">{{ getDietTypeName(diet.diet_type) }}</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                                    <span 
                                        :class="getStatusBadgeClass(diet.status)"
                                        class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full"
                                    >
                                        <i :class="diet.status === 'active' ? 'fas fa-check-circle' : 'fas fa-times-circle'" class="mr-1"></i>
                                        {{ diet.status === 'active' ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Peso Total</label>
                                    <p class="text-gray-900 font-semibold">
                                        <i class="fas fa-weight-hanging text-gray-400 mr-2"></i>
                                        {{ diet.total_weight || 'No especificado' }}
                                    </p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                                    <p class="text-gray-900">
                                        {{ diet.description || 'Sin descripción disponible' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Components -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-100">
                            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-list-ul text-green-600 mr-2"></i>
                                Componentes de la Dieta
                                <span class="ml-2 bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">
                                    {{ diet.components?.length || 0 }} componentes
                                </span>
                            </h2>
                        </div>
                        <div class="p-6">
                            <div v-if="!diet.components || diet.components.length === 0" class="text-center py-8">
                                <i class="fas fa-box-open text-gray-400 text-4xl mb-3"></i>
                                <p class="text-gray-600">No hay componentes registrados</p>
                            </div>
                            <div v-else class="space-y-4">
                                <div
                                    v-for="(component, index) in diet.components"
                                    :key="component._id || index"
                                    class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-sm transition-shadow duration-200"
                                >
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                    <span class="text-green-600 font-semibold text-sm">{{ index + 1 }}</span>
                                                </div>
                                                <h3 class="text-lg font-semibold text-gray-900">{{ component.name }}</h3>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 ml-11">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Cantidad</label>
                                                    <p class="text-sm font-semibold text-gray-900">
                                                        {{ component.amount }} {{ getUnitLabel(component.unit) }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Unidad</label>
                                                    <p class="text-sm text-gray-900">{{ getUnitLabel(component.unit) }}</p>
                                                </div>
                                                <div v-if="component.notes" class="sm:col-span-1">
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Notas</label>
                                                    <p class="text-sm text-gray-600 italic">{{ component.notes }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <i class="fas fa-seedling text-green-500 text-xl"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Diet Stats -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                                Estadísticas
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Componentes</span>
                                    <span class="text-lg font-semibold text-gray-900">
                                        {{ diet.components?.length || 0 }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Peso Total</span>
                                    <span class="text-lg font-semibold text-gray-900">
                                        {{ diet.total_weight || 'N/A' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Estado</span>
                                    <span 
                                        :class="getStatusBadgeClass(diet.status)"
                                        class="px-2 py-1 text-xs font-semibold rounded-full"
                                    >
                                        {{ diet.status === 'active' ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Creation Info -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-info text-blue-600 mr-2"></i>
                                Información de Creación
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Fecha de Creación</label>
                                    <p class="text-sm text-gray-900">
                                        <i class="fas fa-calendar text-gray-400 mr-2"></i>
                                        {{ formatDate(diet.createdAt) }}
                                    </p>
                                </div>
                                <div v-if="diet.updatedAt && diet.updatedAt !== diet.createdAt">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Última Actualización</label>
                                    <p class="text-sm text-gray-900">
                                        <i class="fas fa-clock text-gray-400 mr-2"></i>
                                        {{ formatDate(diet.updatedAt) }}
                                    </p>
                                </div>
                                <div v-if="diet.created_by">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Creado por</label>
                                    <p class="text-sm text-gray-900">
                                        <i class="fas fa-user text-gray-400 mr-2"></i>
                                        Usuario ID: {{ diet.created_by }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div v-if="diet.notes" class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-sticky-note text-yellow-600 mr-2"></i>
                                Notas Adicionales
                            </h3>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ diet.notes }}</p>
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
                    ¿Estás seguro de que deseas eliminar la dieta <strong>{{ diet.name }}</strong>? 
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
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'

// Props
const props = defineProps({
    diet: {
        type: Object,
        required: true
    }
})

// Reactive data
const showDeleteModal = ref(false)
const deleting = ref(false)

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

const getUnitLabel = (unit) => {
    const labels = {
        kg: 'kg',
        gr: 'gr',
        L: 'L',
        ml: 'ml',
        units: 'unidades',
        other: 'otro'
    }
    return labels[unit] || unit
}

const confirmDelete = () => {
    showDeleteModal.value = true
}

const cancelDelete = () => {
    showDeleteModal.value = false
}

const deleteDiet = async () => {
    deleting.value = true
    
    try {
        await router.delete(route('diets.destroy', props.diet._id), {
            onSuccess: () => {
                // Redirect handled by backend
            },
            onError: (errors) => {
                console.error('Error deleting diet:', errors)
                alert('Error al eliminar la dieta')
                showDeleteModal.value = false
            }
        })
    } catch (error) {
        console.error('Error deleting diet:', error)
        alert('Error al eliminar la dieta')
        showDeleteModal.value = false
    } finally {
        deleting.value = false
    }
}
</script>

<style scoped>
.transition-colors {
    transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
}

.transition-shadow {
    transition: box-shadow 0.2s ease-in-out;
}
</style>
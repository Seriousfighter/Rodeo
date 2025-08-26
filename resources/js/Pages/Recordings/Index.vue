<template>
    <AppLayout title="Recordings">
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            <i class="fas fa-file-medical text-green-600 mr-3"></i>
                            Registros de Animales
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Gestión de registros veterinarios y seguimiento de animales
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <button
                            @click="refreshRecordings"
                            :disabled="loading"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                        >
                            <i :class="loading ? 'fas fa-spinner fa-spin' : 'fas fa-sync-alt'" class="mr-2"></i>
                            {{ loading ? 'Cargando...' : 'Actualizar' }}
                        </button>
                        <Link 
                            :href="route('recordings.create')"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            Nuevo Registro
                        </Link>
                    </div>
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
                                placeholder="Buscar por animal, rodeo o cliente..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            />
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <select
                            v-model="filters.recording_type"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        >
                            <option value="">Todos los tipos</option>
                            <option value="insemination">Inseminación</option>
                            <option value="vaccination">Vacunación</option>
                            <option value="weight_check">Control de Peso</option>
                            <option value="ultrasound">Ultrasonido</option>
                            <option value="natural_breeding">Monta Natural</option>
                            <option value="health_check">Control de Salud</option>
                            <option value="other">Otro</option>
                        </select>
                        <select
                            v-model="filters.status"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        >
                            <option value="">Todos los estados</option>
                            <option value="pending">Pendiente</option>
                            <option value="completed">Completado</option>
                            <option value="cancelled">Cancelado</option>
                        </select>
                        <span class="text-sm text-gray-600 whitespace-nowrap">
                            Total: <span class="font-semibold text-green-600">{{ filteredRecordings.length }}</span> registros
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recordings List -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <div v-if="loading && initialRecordings.length === 0" class="text-center py-12">
                <i class="fas fa-spinner fa-spin text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600">Cargando registros...</p>
            </div>

            <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-3xl mb-4"></i>
                <p class="text-red-700 font-medium">Error al cargar los registros</p>
                <p class="text-red-600 text-sm mt-2">{{ error }}</p>
                <button
                    @click="refreshRecordings"
                    class="mt-4 inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200"
                >
                    <i class="fas fa-sync-alt mr-2"></i>
                    Reintentar
                </button>
            </div>

            <div v-else-if="filteredRecordings.length === 0" class="text-center py-12">
                <i class="fas fa-file-medical text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay registros</h3>
                <p class="text-gray-600 mb-6">
                    {{ searchTerm || filters.recording_type || filters.status ? 'No se encontraron registros que coincidan con los filtros' : 'Aún no hay registros creados' }}
                </p>
                <Link 
                    :href="route('recordings.create')"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200"
                >
                    <i class="fas fa-plus mr-2"></i>
                    Crear Primer Registro
                </Link>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div 
                    v-for="recording in paginatedRecordings" 
                    :key="recording._id"
                    class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200"
                >
                    <!-- Recording Card Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <i :class="getRecordingIcon(recording.recording_type)" class="text-green-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ getRecordingTypeLabel(recording.recording_type) }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        Animal ID: {{ recording.animal_id }}
                                    </p>
                                </div>
                            </div>
                            <span :class="getStatusClass(recording.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                {{ getStatusLabel(recording.status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Recording Card Body -->
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-clipboard-list text-gray-400 w-4"></i>
                                <span class="text-sm text-gray-600">
                                    <strong>Rodeo:</strong> {{ recording.rodeo_id }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-user text-gray-400 w-4"></i>
                                <span class="text-sm text-gray-600">
                                    <strong>Cliente:</strong> {{ recording.client_id }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-user-md text-gray-400 w-4"></i>
                                <span class="text-sm text-gray-600">
                                    <strong>Veterinario:</strong> {{ recording.veterinarian_id }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-calendar text-gray-400 w-4"></i>
                                <span class="text-sm text-gray-600">
                                    <strong>Fecha:</strong> {{ formatDate(recording.recording_date) }}
                                </span>
                            </div>
                            <div v-if="recording.notes" class="flex items-start space-x-3">
                                <i class="fas fa-sticky-note text-gray-400 w-4 mt-1"></i>
                                <span class="text-sm text-gray-600">
                                    <strong>Notas:</strong> {{ recording.notes.length > 50 ? recording.notes.substring(0, 50) + '...' : recording.notes }}
                                </span>
                            </div>
                        </div>

                        <!-- Recording Data Preview -->
                        <div v-if="recording.recording_data" class="mt-4 pt-4 border-t border-gray-100">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Datos del Registro</h4>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div v-for="(value, key) in getDisplayData(recording.recording_data)" :key="key">
                                        <span class="text-gray-500">{{ formatFieldName(key) }}:</span>
                                        <span class="font-medium">{{ value }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recording Card Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">
                                Creado: {{ formatDate(recording.createdAt) }}
                            </span>
                            <div class="flex space-x-2">
                                <button
                                    @click="viewRecording(recording)"
                                    class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                    title="Ver detalles"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button
                                    @click="editRecording(recording)"
                                    class="text-green-600 hover:text-green-900 transition-colors duration-200"
                                    title="Editar"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button
                                    @click="confirmDelete(recording)"
                                    class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                    title="Eliminar"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="filteredRecordings.length > itemsPerPage" class="mt-8 flex justify-center">
                <nav class="flex items-center space-x-2">
                    <button
                        @click="currentPage = Math.max(1, currentPage - 1)"
                        :disabled="currentPage === 1"
                        class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:bg-gray-100 disabled:text-gray-400"
                    >
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span class="px-4 py-2 text-sm text-gray-700">
                        Página {{ currentPage }} de {{ totalPages }}
                    </span>
                    <button
                        @click="currentPage = Math.min(totalPages, currentPage + 1)"
                        :disabled="currentPage === totalPages"
                        class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:bg-gray-100 disabled:text-gray-400"
                    >
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        <h3 class="text-lg font-medium text-gray-900">Confirmar Eliminación</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-6">
                        ¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.
                    </p>
                    <div class="flex justify-end space-x-3">
                        <button
                            @click="cancelDelete"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition-colors duration-200"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="deleteRecording"
                            :disabled="deleting"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-medium rounded-lg transition-colors duration-200"
                        >
                            <i v-if="deleting" class="fas fa-spinner fa-spin mr-2"></i>
                            {{ deleting ? 'Eliminando...' : 'Eliminar' }}
                        </button>
                    </div>
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
// Props from Laravel backend
const props = defineProps({
    recordings: {
        type: Object,
        default: () => ({ success: false, data: [] })
    }
})

// Reactive data
const initialRecordings = ref([])
const loading = ref(false)
const error = ref(null)
const searchTerm = ref('')
const currentPage = ref(1)
const itemsPerPage = ref(12)
const showDeleteModal = ref(false)
const recordingToDelete = ref(null)
const deleting = ref(false)

// Filters
const filters = ref({
    recording_type: '',
    status: '',
    animal_id: '',
    rodeo_id: '',
    client_id: ''
})

// Initialize recordings from props
onMounted(() => {
    if (props.recordings.success && props.recordings.data) {
        initialRecordings.value = props.recordings.data
    } else if (props.recordings.error) {
        error.value = props.recordings.error
    }
})

// Computed properties
const filteredRecordings = computed(() => {
    let filtered = initialRecordings.value

    // Apply search filter
    if (searchTerm.value) {
        const term = searchTerm.value.toLowerCase()
        filtered = filtered.filter(recording => 
            recording.animal_id.toString().includes(term) ||
            recording.rodeo_id.toString().includes(term) ||
            recording.client_id.toString().includes(term) ||
            recording.recording_type.toLowerCase().includes(term) ||
            recording.status.toLowerCase().includes(term) ||
            (recording.notes && recording.notes.toLowerCase().includes(term))
        )
    }

    // Apply type filter
    if (filters.value.recording_type) {
        filtered = filtered.filter(recording => recording.recording_type === filters.value.recording_type)
    }

    // Apply status filter
    if (filters.value.status) {
        filtered = filtered.filter(recording => recording.status === filters.value.status)
    }

    return filtered
})

const totalPages = computed(() => {
    return Math.ceil(filteredRecordings.value.length / itemsPerPage.value)
})

const paginatedRecordings = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value
    const end = start + itemsPerPage.value
    return filteredRecordings.value.slice(start, end)
})

// Methods
const refreshRecordings = () => {
    const currentFilters = { ...filters.value }
    // Remove empty filters
    Object.keys(currentFilters).forEach(key => {
        if (!currentFilters[key]) {
            delete currentFilters[key]
        }
    })
    
    router.reload({
        data: currentFilters,
        preserveScroll: true
    })
}

const getRecordingTypeLabel = (type) => {
    const labels = {
        'insemination': 'Inseminación',
        'vaccination': 'Vacunación',
        'weight_check': 'Control de Peso',
        'ultrasound': 'Ultrasonido',
        'natural_breeding': 'Monta Natural',
        'health_check': 'Control de Salud',
        'other': 'Otro'
    }
    return labels[type] || type
}

const getRecordingIcon = (type) => {
    const icons = {
        'insemination': 'fas fa-syringe',
        'vaccination': 'fas fa-shield-alt',
        'weight_check': 'fas fa-weight',
        'ultrasound': 'fas fa-heartbeat',
        'natural_breeding': 'fas fa-heart',
        'health_check': 'fas fa-stethoscope',
        'other': 'fas fa-file-medical'
    }
    return icons[type] || 'fas fa-file-medical'
}

const getStatusLabel = (status) => {
    const labels = {
        'pending': 'Pendiente',
        'completed': 'Completado',
        'cancelled': 'Cancelado'
    }
    return labels[status] || status
}

const getStatusClass = (status) => {
    const classes = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'completed': 'bg-green-100 text-green-800',
        'cancelled': 'bg-red-100 text-red-800'
    }
    return classes[status] || 'bg-gray-100 text-gray-800'
}

const formatDate = (dateString) => {
    if (!dateString) return '-'
    const date = new Date(dateString)
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const getDisplayData = (recordingData) => {
    if (!recordingData || typeof recordingData !== 'object') return {}
    
    // Return first 3 items for display
    const entries = Object.entries(recordingData).slice(0, 3)
    return Object.fromEntries(entries)
}

const formatFieldName = (fieldName) => {
    const fieldNames = {
        'temperature': 'Temperatura',
        'weight': 'Peso',
        'condition': 'Condición',
        'vaccine_type': 'Tipo de Vacuna',
        'dose': 'Dosis',
        'result': 'Resultado',
        'observations': 'Observaciones'
    }
    return fieldNames[fieldName] || fieldName.replace('_', ' ').toUpperCase()
}

const viewRecording = (recording) => {
    router.visit(route('recordings.show', recording._id))
}

const editRecording = (recording) => {
    router.visit(route('recordings.edit', recording._id))
}

const confirmDelete = (recording) => {
    recordingToDelete.value = recording
    showDeleteModal.value = true
}

const cancelDelete = () => {
    recordingToDelete.value = null
    showDeleteModal.value = false
}

const deleteRecording = async () => {
    if (!recordingToDelete.value) return
    
    deleting.value = true
    
    try {
        await router.delete(route('recordings.destroy', recordingToDelete.value._id), {
            onSuccess: () => {
                showDeleteModal.value = false
                recordingToDelete.value = null
            },
            onError: (errors) => {
                error.value = errors.message || 'Error al eliminar el registro'
            }
        })
    } catch (err) {
        error.value = err.message || 'Error al eliminar el registro'
    } finally {
        deleting.value = false
    }
}

// Watch for filter changes
watch(filters, () => {
    currentPage.value = 1
}, { deep: true })

// Watch for search term changes
watch(searchTerm, () => {
    currentPage.value = 1
})
</script>

<style scoped>
.transition-colors {
    transition: color 0.2s ease-in-out, background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
}

.transition-shadow {
    transition: box-shadow 0.2s ease-in-out;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.fa-spin {
    animation: spin 1s linear infinite;
}
</style>
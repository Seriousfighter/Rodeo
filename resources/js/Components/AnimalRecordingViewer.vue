<template>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-medical text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Registros del Animal</h3>
                        <p class="text-sm text-gray-600">Historial de registros veterinarios</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button
                        @click="refreshPage"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                    >
                        <i class="fas fa-sync-alt mr-2"></i>
                        Actualizar
                    </button>
                    <Link 
                         :href="route('recordings.create', { animalId: props.animalId })"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                    >
                        <i class="fas fa-plus mr-2"></i>
                        Nuevo Registro
                    </Link>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input
                            v-model="searchTerm"
                            type="text"
                            placeholder="Buscar por tipo, veterinario o notas..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <select
                        v-model="filters.recording_type"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">Todos los estados</option>
                        <option value="pending">Pendiente</option>
                        <option value="completed">Completado</option>
                        <option value="cancelled">Cancelado</option>
                    </select>
                    <input
                        v-model="filters.date_from"
                        type="date"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Desde"
                    />
                    <input
                        v-model="filters.date_to"
                        type="date"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Hasta"
                    />
                </div>
            </div>
            <div class="flex flex-col sm:flex-row justify-between items-center mt-4">
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">
                        Total: <span class="font-semibold text-blue-600">{{ filteredRecordings.length }}</span> registros
                    </span>
                    <span class="text-sm text-gray-600">
                        Mostrando: <span class="font-semibold text-blue-600">{{ paginatedRecordings.length }}</span> de {{ filteredRecordings.length }}
                    </span>
                </div>
                <div class="flex items-center space-x-2">
                    <label class="text-sm text-gray-600">Ver:</label>
                    <select
                        v-model="itemsPerPage"
                        class="px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-sm text-gray-600">por página</span>
                </div>
            </div>
        </div>

        <!-- Column Visibility Controls -->
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-700">Mostrar columnas:</span>
                <div class="flex flex-wrap gap-2">
                    <label v-for="column in availableColumns" :key="column.key" class="flex items-center space-x-1">
                        <input
                            type="checkbox"
                            v-model="visibleColumns"
                            :value="column.key"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <span class="text-sm text-gray-600">{{ column.label }}</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredRecordings.length === 0" class="px-6 py-12 text-center">
            <i class="fas fa-file-medical text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay registros</h3>
            <p class="text-gray-600 mb-6">
                {{ searchTerm || hasActiveFilters ? 'No se encontraron registros que coincidan con los filtros' : 'Este animal aún no tiene registros veterinarios' }}
            </p>
            <Link 
                :href="route('recordings.create', { animalId: props.animalId })"
                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200"
            >
                <i class="fas fa-plus mr-2"></i>
                Crear Primer Registro
            </Link>
        </div>

        <!-- Table -->
        <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            v-for="column in displayedColumns"
                            :key="column.key"
                            @click="sortBy(column.key)"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200"
                            :class="{ 'bg-blue-100': sortKey === column.key }"
                        >
                            <div class="flex items-center space-x-1">
                                <i :class="column.icon" class="mr-1"></i>
                                <span>{{ column.label }}</span>
                                <i
                                    v-if="sortKey === column.key"
                                    :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"
                                    class="text-blue-600"
                                ></i>
                                <i v-else class="fas fa-sort text-gray-400"></i>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="recording in paginatedRecordings"
                        :key="recording._id"
                        class="hover:bg-gray-50 transition-colors duration-200"
                    >
                        <td
                            v-for="column in displayedColumns"
                            :key="column.key"
                            class="px-6 py-4 whitespace-nowrap"
                        >
                            <div v-if="column.key === 'recording_type'" class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i :class="getRecordingIcon(recording.recording_type)" class="text-blue-600"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ getRecordingTypeLabel(recording.recording_type) }}
                                </span>
                            </div>
                            <div v-else-if="column.key === 'status'" class="flex items-center">
                                <span :class="getStatusClass(recording.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ getStatusLabel(recording.status) }}
                                </span>
                            </div>
                            <div v-else-if="column.key === 'recording_date'" class="text-sm text-gray-900">
                                {{ formatDate(recording.recording_date) }}
                            </div>
                            <div v-else-if="column.key === 'recording_data'" class="text-sm text-gray-900">
                                <div class="max-w-xs">
                                    <div class="grid grid-cols-1 gap-1">
                                        <div v-for="(value, key) in getDisplayData(recording.recording_data)" :key="key" class="text-xs">
                                            <span class="text-gray-500">{{ formatFieldName(key) }}:</span>
                                            <span class="font-medium">{{ value }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="column.key === 'notes'" class="text-sm text-gray-900">
                                <div class="max-w-xs">
                                    {{ recording.notes ? (recording.notes.length > 50 ? recording.notes.substring(0, 50) + '...' : recording.notes) : '-' }}
                                </div>
                            </div>
                            <div v-else class="text-sm text-gray-900">
                                {{ recording[column.key] || '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button
                                    @click="editRecording(recording)"
                                    class="text-green-600 hover:text-green-900 transition-colors duration-200"
                                    title="Editar"
                                >Edit
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button
                                    @click="confirmDelete(recording)"
                                    class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                    title="Eliminar"
                                >Delete
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div v-if="totalPages > 1" class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-700">
                        Página {{ currentPage }} de {{ totalPages }}
                    </span>
                </div>
                <div class="flex items-center space-x-2">
                    <button
                        @click="currentPage = Math.max(1, currentPage - 1)"
                        :disabled="currentPage === 1"
                        class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:bg-gray-100 disabled:text-gray-400 transition-colors duration-200"
                    >
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    
                    <div class="flex items-center space-x-1">
                        <button
                            v-for="page in visiblePages"
                            :key="page"
                            @click="currentPage = page"
                            :class="page === currentPage ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium transition-colors duration-200"
                        >
                            {{ page }}
                        </button>
                    </div>
                    
                    <button
                        @click="currentPage = Math.min(totalPages, currentPage + 1)"
                        :disabled="currentPage === totalPages"
                        class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:bg-gray-100 disabled:text-gray-400 transition-colors duration-200"
                    >
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
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
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'

// Props
const props = defineProps({
    recordings: {
        type: Array,
        default: () => []
    },
    animalId: {
        type: Number,
        required: true
    }
})

console.log(props.animalId);

// Reactive data
const searchTerm = ref('')
const currentPage = ref(1)
const itemsPerPage = ref(25)
const sortKey = ref('recording_date')
const sortDirection = ref('desc')
const showDeleteModal = ref(false)
const recordingToDelete = ref(null)
const deleting = ref(false)

// Filters
const filters = ref({
    recording_type: '',
    status: '',
    date_from: '',
    date_to: ''
})

// Column configuration
const availableColumns = [
    { key: 'recording_type', label: 'Tipo', icon: 'fas fa-stethoscope' },
    { key: 'recording_date', label: 'Fecha', icon: 'fas fa-calendar' },
    //{ key: 'veterinarian_id', label: 'Veterinario', icon: 'fas fa-user-md' },
    { key: 'status', label: 'Estado', icon: 'fas fa-flag' },
    { key: 'recording_data', label: 'Datos', icon: 'fas fa-database' },
    { key: 'notes', label: 'Notas', icon: 'fas fa-sticky-note' }
]

const visibleColumns = ref(['recording_type', 'recording_date', 'veterinarian_id', 'status', 'recording_data'])

// Computed properties
const displayedColumns = computed(() => {
    return availableColumns.filter(column => visibleColumns.value.includes(column.key))
})

const hasActiveFilters = computed(() => {
    return filters.value.recording_type || filters.value.status || filters.value.date_from || filters.value.date_to
})

const filteredRecordings = computed(() => {
    let filtered = props.recordings

    // Apply search filter
    if (searchTerm.value) {
        const term = searchTerm.value.toLowerCase()
        filtered = filtered.filter(recording => 
            recording.recording_type.toLowerCase().includes(term) ||
            recording.status.toLowerCase().includes(term) ||
            recording.veterinarian_id.toString().includes(term) ||
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

    // Apply date filters
    if (filters.value.date_from) {
        filtered = filtered.filter(recording => 
            new Date(recording.recording_date) >= new Date(filters.value.date_from)
        )
    }

    if (filters.value.date_to) {
        filtered = filtered.filter(recording => 
            new Date(recording.recording_date) <= new Date(filters.value.date_to)
        )
    }

    return filtered
})

const sortedRecordings = computed(() => {
    return [...filteredRecordings.value].sort((a, b) => {
        let aValue = a[sortKey.value]
        let bValue = b[sortKey.value]

        // Handle date sorting
        if (sortKey.value === 'recording_date') {
            aValue = new Date(aValue)
            bValue = new Date(bValue)
        }

        if (sortDirection.value === 'asc') {
            return aValue > bValue ? 1 : -1
        } else {
            return aValue < bValue ? 1 : -1
        }
    })
})

const totalPages = computed(() => {
    return Math.ceil(filteredRecordings.value.length / itemsPerPage.value)
})

const paginatedRecordings = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value
    const end = start + itemsPerPage.value
    return sortedRecordings.value.slice(start, end)
})

const visiblePages = computed(() => {
    const pages = []
    const maxVisible = 5
    let start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2))
    let end = Math.min(totalPages.value, start + maxVisible - 1)
    
    if (end - start + 1 < maxVisible) {
        start = Math.max(1, end - maxVisible + 1)
    }
    
    for (let i = start; i <= end; i++) {
        pages.push(i)
    }
    
    return pages
})

// Methods
const refreshPage = () => {
    router.reload({
        preserveScroll: true
    })
}

const sortBy = (key) => {
    if (sortKey.value === key) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
    } else {
        sortKey.value = key
        sortDirection.value = 'asc'
    }
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
                // Refresh the page to get updated data
                router.reload({
                    preserveScroll: true
                })
            },
            onError: (errors) => {
                console.error('Error deleting recording:', errors)
            }
        })
    } catch (err) {
        console.error('Error deleting recording:', err)
    } finally {
        deleting.value = false
    }
}

// Watch for filter changes
watch([filters, searchTerm], () => {
    currentPage.value = 1
}, { deep: true })

watch(itemsPerPage, () => {
    currentPage.value = 1
})
</script>

<style scoped>
.transition-colors {
    transition: color 0.2s ease-in-out, background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
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
<template>
    <AppLayout title="Registro de Animales">
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            <i class="fas fa-cow text-green-600 mr-3"></i>
                            Registro de Animales
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Gestión de animales estilo planilla - Edite directamente en la tabla
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <button
                            @click="addNewRow"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            Agregar Animal
                        </button>
                        <button
                            @click="saveAll"
                            :disabled="!hasChanges"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                        >
                            <i class="fas fa-save mr-2"></i>
                            Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Bar -->
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <input
                                v-model="searchTerm"
                                type="text"
                                placeholder="Buscar por caravana, rodeo o cliente..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            />
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">
                            Total: <span class="font-semibold text-green-600">{{ filteredAnimals.length }}</span> animales
                        </span>
                        <span v-if="hasChanges" class="text-sm text-orange-600 font-medium">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            Cambios sin guardar
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Animals Table -->
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <!-- Table Header -->
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                    #
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-tag mr-1"></i>
                                    Caravana *
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-certificate mr-1"></i>
                                    Caravana Oficial
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-clipboard-list mr-1"></i>
                                    Rodeo *
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-user mr-1"></i>
                                    Cliente
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Fecha Registro
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <!-- Table Body -->
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr 
                                v-for="(animal, index) in filteredAnimals" 
                                :key="animal.id || `new-${index}`"
                                :class="{ 
                                    'bg-green-50': animal.isNew,
                                    'bg-yellow-50': animal.isModified && !animal.isNew,
                                    'bg-red-50': animal.hasErrors
                                }"
                                class="hover:bg-gray-50 transition-colors duration-200"
                            >
                                <!-- Row Number -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ index + 1 }}
                                    <span v-if="animal.isNew" class="ml-1 text-green-600 font-medium">NEW</span>
                                    <span v-else-if="animal.isModified" class="ml-1 text-orange-600 font-medium">MOD</span>
                                </td>

                                <!-- Caravana -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input
                                        v-model="animal.caravana"
                                        @input="markAsModified(animal)"
                                        type="text"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm"
                                        :class="{ 'border-red-500': animal.errors?.caravana }"
                                        placeholder="Ingrese caravana"
                                        required
                                    />
                                    <p v-if="animal.errors?.caravana" class="mt-1 text-xs text-red-600">
                                        {{ animal.errors.caravana }}
                                    </p>
                                </td>

                                <!-- Caravana Oficial -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input
                                        v-model="animal.caravana_oficial"
                                        @input="markAsModified(animal)"
                                        type="text"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm"
                                        :class="{ 'border-red-500': animal.errors?.caravana_oficial }"
                                        placeholder="Caravana oficial"
                                    />
                                    <p v-if="animal.errors?.caravana_oficial" class="mt-1 text-xs text-red-600">
                                        {{ animal.errors.caravana_oficial }}
                                    </p>
                                </td>

                                <!-- Rodeo -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <select
                                        v-model="animal.rodeo_id"
                                        @change="markAsModified(animal)"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm"
                                        :class="{ 'border-red-500': animal.errors?.rodeo_id }"
                                        required
                                    >
                                        <option value="">Seleccionar rodeo</option>
                                        <option 
                                            v-for="rodeo in rodeos" 
                                            :key="rodeo.id" 
                                            :value="rodeo.id"
                                        >
                                            {{ rodeo.name }}
                                        </option>
                                    </select>
                                    <p v-if="animal.errors?.rodeo_id" class="mt-1 text-xs text-red-600">
                                        {{ animal.errors.rodeo_id }}
                                    </p>
                                </td>

                                <!-- Cliente -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ getClientName(animal.rodeo_id) }}
                                </td>

                                <!-- Fecha Registro -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ animal.created_at ? formatDate(animal.created_at) : 'Nuevo' }}
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <!-- Save Individual -->
                                        <button
                                            v-if="animal.isNew || animal.isModified"
                                            @click="saveIndividual(animal, index)"
                                            class="text-green-600 hover:text-green-900 transition-colors duration-200"
                                            title="Guardar"
                                        >
                                            <i class="fas fa-check"></i>
                                        </button>

                                        <!-- View -->
                                        <Link 
                                            v-if="!animal.isNew"
                                            :href="route('animals.show', animal.id)"
                                            class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                            title="Ver detalles"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </Link>

                                        <!-- Cancel Changes -->
                                        <button
                                            v-if="animal.isModified && !animal.isNew"
                                            @click="cancelChanges(animal, index)"
                                            class="text-gray-600 hover:text-gray-900 transition-colors duration-200"
                                            title="Cancelar cambios"
                                        >
                                            <i class="fas fa-undo"></i>
                                        </button>

                                        <!-- Delete -->
                                        <button
                                            @click="confirmDelete(animal, index)"
                                            class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                            title="Eliminar"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Empty State -->
                            <tr v-if="filteredAnimals.length === 0">
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-cow text-4xl mb-4"></i>
                                        <h3 class="text-lg font-medium mb-2">No hay animales registrados</h3>
                                        <p class="text-sm mb-4">Comience agregando su primer animal</p>
                                        <button
                                            @click="addNewRow"
                                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200"
                                        >
                                            <i class="fas fa-plus mr-2"></i>
                                            Agregar Primer Animal
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                    ¿Estás seguro de que deseas eliminar el animal con caravana <strong>{{ animalToDelete?.caravana }}</strong>? 
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
                        @click="deleteAnimal"
                        class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200"
                    >
                        Eliminar
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div v-if="loading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-spinner fa-spin text-green-600 text-2xl"></i>
                    <span class="text-lg font-medium text-gray-900">Procesando...</span>
                </div>
            </div>
        </div>
    </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'
// Props
const props = defineProps({
    animals: {
        type: Array,
        default: () => []
    },
    rodeos: {
        type: Array,
        default: () => []
    }
})

// Reactive data
const searchTerm = ref('')
const showDeleteModal = ref(false)
const animalToDelete = ref(null)
const deleteIndex = ref(null)
const loading = ref(false)
const animalsData = ref([])
const originalData = ref([])

// Computed properties
const filteredAnimals = computed(() => {
    if (!searchTerm.value) return animalsData.value
    
    return animalsData.value.filter(animal => 
        animal.caravana?.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
        (animal.caravana_oficial && animal.caravana_oficial.toLowerCase().includes(searchTerm.value.toLowerCase())) ||
        (animal.rodeo?.name && animal.rodeo.name.toLowerCase().includes(searchTerm.value.toLowerCase())) ||
        (animal.rodeo?.client?.name && animal.rodeo.client.name.toLowerCase().includes(searchTerm.value.toLowerCase()))
    )
})

const hasChanges = computed(() => {
    return animalsData.value.some(animal => animal.isNew || animal.isModified)
})

// Methods
const initializeData = () => {
    animalsData.value = props.animals.map(animal => ({
        ...animal,
        isNew: false,
        isModified: false,
        hasErrors: false,
        errors: {},
        originalData: { ...animal }
    }))
    originalData.value = JSON.parse(JSON.stringify(props.animals))
}

const addNewRow = () => {
    const newAnimal = {
        id: null,
        caravana: '',
        caravana_oficial: '',
        rodeo_id: '',
        rodeo: null,
        created_at: null,
        updated_at: null,
        isNew: true,
        isModified: false,
        hasErrors: false,
        errors: {},
        originalData: null
    }
    animalsData.value.unshift(newAnimal)
}

const markAsModified = (animal) => {
    if (!animal.isNew) {
        animal.isModified = true
    }
    animal.hasErrors = false
    animal.errors = {}
}

const getClientName = (rodeoId) => {
    if (!rodeoId) return '-'
    const rodeo = props.rodeos.find(r => r.id == rodeoId)
    return rodeo?.client ? `${rodeo.client.name} ${rodeo.client.last_name}` : '-'
}

const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

const saveIndividual = async (animal, index) => {
    loading.value = true
    
    try {
        const data = {
            caravana: animal.caravana,
            caravana_oficial: animal.caravana_oficial,
            rodeo_id: animal.rodeo_id
        }

        let response
        if (animal.isNew) {
            response = await axios.post(route('animals.store'), data)
        } else {
            response = await axios.put(route('animals.update', animal.id), data)
        }

        if (response.data.success) {
            // Update the animal with the response data
            Object.assign(animal, response.data.animal)
            animal.isNew = false
            animal.isModified = false
            animal.hasErrors = false
            animal.errors = {}
            animal.originalData = { ...response.data.animal }
            
            // Show success message
            showNotification(response.data.message, 'success')
        }
    } catch (error) {
        animal.hasErrors = true
        if (error.response && error.response.data.errors) {
            animal.errors = error.response.data.errors
        } else {
            showNotification(error.response?.data?.message || 'Error al guardar el animal', 'error')
        }
    } finally {
        loading.value = false
    }
}

const saveAll = async () => {
    const animalsToSave = animalsData.value.filter(animal => animal.isNew || animal.isModified)
    
    if (animalsToSave.length === 0) return

    loading.value = true
    
    for (const animal of animalsToSave) {
        const index = animalsData.value.indexOf(animal)
        await saveIndividual(animal, index)
    }
    
    loading.value = false
}

const cancelChanges = (animal, index) => {
    if (animal.isNew) {
        animalsData.value.splice(index, 1)
    } else {
        // Restore original data
        Object.assign(animal, animal.originalData)
        animal.isModified = false
        animal.hasErrors = false
        animal.errors = {}
    }
}

const confirmDelete = (animal, index) => {
    animalToDelete.value = animal
    deleteIndex.value = index
    showDeleteModal.value = true
}

const cancelDelete = () => {
    animalToDelete.value = null
    deleteIndex.value = null
    showDeleteModal.value = false
}

const deleteAnimal = async () => {
    if (!animalToDelete.value) return

    loading.value = true

    try {
        if (animalToDelete.value.isNew) {
            // Just remove from array if it's a new row
            animalsData.value.splice(deleteIndex.value, 1)
        } else {
            // Delete from server
            const response = await axios.delete(route('animals.destroy', animalToDelete.value.id))
            
            if (response.data.success) {
                animalsData.value.splice(deleteIndex.value, 1)
                showNotification(response.data.message, 'success')
            }
        }
    } catch (error) {
        showNotification(error.response?.data?.message || 'Error al eliminar el animal', 'error')
    } finally {
        loading.value = false
        cancelDelete()
    }
}

const showNotification = (message, type = 'info') => {
    // Simple notification - you can replace with a proper toast library
    alert(message)
}

// Lifecycle
onMounted(() => {
    initializeData()
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

/* Custom scrollbar for table */
.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
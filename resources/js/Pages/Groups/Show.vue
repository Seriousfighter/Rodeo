<!-- must show group info: the data will be provider from inertia render,based on the group prop
it will use the animalTableEditor.vue component to show the animals in the group
also it must have a toolbar with a button to add or remove a  animal to the group, actions to take,
like:  choose the tratment to do   <option value="">Seleccione un tipo</option>
                                <option value="insemination">Inseminación</option>
                                <option value="vaccination">Vacunación</option>
                                <option value="weight_check">Control de Peso</option>
                                <option value="ultrasound">Ultrasonido</option>
                                <option value="natural_breeding">Monta Natural</option>
                                <option value="health_check">Control de Salud</option>
                                <option value="other">Otro</option>
                                and that apllies the action to the selected animals
it must have a button to add a new animal to the group, and a button to remove
also if i want to edit some animal it must allowme to do it. the save button must be now consolog like: group_id and the array of animals and his data
it must have a button to save the changes made to the group, like adding or removing animals
-->

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            <i class="fas fa-layer-group text-blue-600 mr-3"></i>
                            {{ group.name }}
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Gestión de grupo con {{ group.animals?.length || 0 }} animales
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <Link 
                            :href="route('rodeo.groups', group.rodeo_id)"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200"
                        >
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver a Grupos
                        </Link>
                        
                        <!-- Export CSV Button -->
                        <button
                            @click="exportToCSV"
                            :disabled="!group.animals?.length || exportingCSV"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors duration-200"
                            title="Exportar animales a CSV"
                        >
                            <i :class="exportingCSV ? 'fas fa-spinner fa-spin' : 'fas fa-download'" class="mr-2"></i>
                            {{ exportingCSV ? 'Exportando...' : 'Exportar CSV' }}
                        </button>
                        <Link 
                            :href="route('groups.diets.index', props.group.id)"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                        >
                            <i class="fas fa-utensils mr-2"></i>
                            Gestionar Dietas
                        </Link>
                        
                        <button
                            @click="showAddAnimalModal = true"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            Agregar Animal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Group Information Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Información del Grupo
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-cow text-blue-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-blue-900">Total Animales</p>
                                    <p class="text-2xl font-bold text-blue-600">{{ group.animals?.length || 0 }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check-square text-green-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-900">Con Tratamiento</p>
                                    <p class="text-2xl font-bold text-green-600">{{ animalsWithTreatment.length }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clipboard-list text-yellow-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-yellow-900">Rodeo</p>
                                    <p class="text-lg font-bold text-yellow-600">{{ group.rodeo?.name || 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="group.description" class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-700">{{ group.description }}</p>
                    </div>
                </div>
            </div>

            <!-- Master Controls -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-sliders-h text-blue-600 mr-2"></i>
                        Controles Generales
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                        <!-- Master Treatment Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Aplicar Tratamiento a Todos
                            </label>
                            <select
                                v-model="masterTreatment"
                                @change="applyMasterTreatment"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="">Seleccione un tipo</option>
                                <option value="insemination">Inseminación</option>
                                <option value="vaccination">Vacunación</option>
                                <option value="weight_check">Control de Peso</option>
                                <option value="ultrasound">Ultrasonido</option>
                                <option value="natural_breeding">Monta Natural</option>
                                <option value="health_check">Control de Salud</option>
                                <option value="other">Otro</option>
                            </select>
                        </div>

                        <!-- Master Veterinarian Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Veterinario para Todos
                            </label>
                            <select
                                v-model="masterVeterinarian"
                                @change="applyMasterVeterinarian"
                                disabled
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed"
                            >
                                <option value="1">Dr. García</option>
                                <option value="2">Dr. Martínez</option>
                                <option value="3">Dr. López</option>
                            </select>
                        </div>

                        <!-- Master Recording Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha para Todos
                            </label>
                            <input
                                v-model="masterRecordingDate"
                                @change="applyMasterDate"
                                type="date"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-end space-x-2">
                            <button
                                @click="clearAllTreatments"
                                class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                            >
                                <i class="fas fa-eraser mr-2"></i>
                                Limpiar
                            </button>
                            <button
                                @click="createAllRecordings"
                                :disabled="!canCreateRecordings"
                                class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                            >
                                <i class="fas fa-save mr-2"></i>
                                Crear Registros
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Animals Table with Individual Controls -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-cow text-green-600 mr-2"></i>
                            Animales y Tratamientos
                        </h2>
                        
                    </div>
                </div>

                <!-- Animals Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-tag mr-1"></i>
                                    Animal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-stethoscope mr-1"></i>
                                    Tratamiento
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-user-md mr-1"></i>
                                    Veterinario
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Fecha
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-cog mr-1"></i>
                                    Detalles
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-sticky-note mr-1"></i>
                                    Notas
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="animal in group.animals"
                                :key="animal.id"
                                class="hover:bg-gray-50 transition-colors duration-200"
                            >
                                <!-- Animal Info -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="fas fa-cow text-green-600 mr-2"></i>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ animal.caravana }}</div>
                                            <div class="text-sm text-gray-500">{{ animal.caravana_oficial || 'Sin caravana oficial' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Treatment Selection -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <select
                                        v-model="getAnimalTreatment(animal.id).recording_type"
                                        @change="updateAnimalTreatment(animal.id, 'recording_type', $event.target.value)"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm"
                                    >
                                        <option value="">Seleccione un tipo</option>
                                        <option value="insemination">Inseminación</option>
                                        <option value="vaccination">Vacunación</option>
                                        <option value="weight_check">Control de Peso</option>
                                        <option value="ultrasound">Ultrasonido</option>
                                        <option value="natural_breeding">Monta Natural</option>
                                        <option value="health_check">Control de Salud</option>
                                        <option value="other">Otro</option>
                                    </select>
                                </td>

                                <!-- Veterinarian Selection -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <select
                                        v-model="getAnimalTreatment(animal.id).veterinarian_id"
                                        @change="updateAnimalTreatment(animal.id, 'veterinarian_id', $event.target.value)"
                                        :disabled="!getAnimalTreatment(animal.id).recording_type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed"
                                    >
                                        <option value="1">Dr. García</option>
                                        <option value="2">Dr. Martínez</option>
                                        <option value="3">Dr. López</option>
                                    </select>
                                </td>

                                <!-- Recording Date -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input
                                        v-model="getAnimalTreatment(animal.id).recording_date"
                                        @change="updateAnimalTreatment(animal.id, 'recording_date', $event.target.value)"
                                        type="date"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm"
                                    />
                                </td>

                                <!-- Details Button -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button
                                        @click="showTreatmentDetails(animal.id)"
                                        :disabled="!getAnimalTreatment(animal.id).recording_type"
                                        class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                                    >
                                        <i class="fas fa-cog mr-1"></i>
                                        Detalles
                                    </button>
                                </td>

                                <!-- Notes -->
                                <td class="px-6 py-4">
                                    <textarea
                                        v-model="getAnimalTreatment(animal.id).notes"
                                        @input="updateAnimalTreatment(animal.id, 'notes', $event.target.value)"
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm"
                                        :placeholder="`Notas para ${animal.caravana}`"
                                    ></textarea>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="relative inline-block text-left">
                                        <button
                                            @click="toggleActionsMenu(animal.id)"
                                            class="inline-flex items-center p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition-colors duration-200"
                                            :id="`menu-button-${animal.id}`"
                                            aria-expanded="false"
                                            aria-haspopup="true"
                                        >
                                            
                                            <i class="fas fa-bars"></i>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div
                                            v-show="openMenuId === animal.id"
                                            class="absolute right-0 z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                            role="menu"
                                            :aria-labelledby="`menu-button-${animal.id}`"
                                            style="z-index: 1000;"
                                        > 
                                            <div class="py-1" role="none">
                                                <Link
                                                    :href="route('animals.show', animal.id)"
                                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200"
                                                    role="menuitem"
                                                    @click="closeActionsMenu"
                                                >
                                                    <i class="fas fa-eye mr-3 text-blue-600"></i>
                                                    Ver Animal
                                                </Link>
                                                <Link
                                                    :href="route('animals.edit', animal.id)"
                                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200"
                                                    role="menuitem"
                                                    @click="closeActionsMenu"
                                                >
                                                    <i class="fas fa-edit mr-3 text-green-600"></i>
                                                    Editar Animal
                                                </Link>
                                                <button
                                                    @click="confirmRemoveAnimal(animal); closeActionsMenu()"
                                                    class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200"
                                                    role="menuitem"
                                                >
                                                    <i class="fas fa-times mr-3 text-red-600"></i>
                                                    Remover del Grupo
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Treatment Details Modal -->
        <div v-if="showDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-cog text-blue-600 mr-2"></i>
                        Detalles del Tratamiento - {{ getCurrentAnimal()?.caravana }}
                    </h3>
                    <button
                        @click="closeDetailsModal"
                        class="text-gray-400 hover:text-gray-600"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="mb-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-700">
                            <i class="fas fa-info-circle mr-2"></i>
                            Configurando tratamiento de tipo: 
                            <strong>{{ getTreatmentLabel(getAnimalTreatment(currentAnimalId)?.recording_type) }}</strong>
                        </p>
                    </div>
                </div>

                <!-- Treatment-specific form -->
                <div v-if="currentAnimalId" class="space-y-4">
                    <TreatmentDetailsForm
                        :animal-id="currentAnimalId"
                        :treatment-type="getAnimalTreatment(currentAnimalId)?.recording_type"
                        :recording-data="getAnimalTreatment(currentAnimalId)?.recording_data || {}"
                        @update="updateAnimalRecordingData"
                    />
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button
                        @click="closeDetailsModal"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition-colors duration-200"
                    >
                        Cerrar
                    </button>
                    <button
                        @click="saveAnimalDetails"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200"
                    >
                        <i class="fas fa-save mr-2"></i>
                        Guardar Detalles
                    </button>
                </div>
            </div>
        </div>

        <!-- Add the Confirmation Modal at the bottom of the template -->
        <ConfirmationModal
            :show="showRemoveAnimalModal"
            title="Remover Animal del Grupo"
            :message="`¿Está seguro de que desea remover a <strong>${animalToRemove?.caravana}</strong> del grupo?`"
            confirm-text="Remover"
            cancel-text="Cancelar"
            type="warning"
            @confirm="removeAnimalFromGroup"
            @cancel="cancelRemoveAnimal"
        />
    </div>
</template>

<script setup>
import { ref, computed, onMounted, reactive, onUnmounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import TreatmentDetailsForm from '@/Components/TreatmentDetailsForm.vue'
import ConfirmationModal from '@/Components/ConfirmationModal.vue'

// Props
const props = defineProps({
    group: {
        type: Object,
        required: true
    }
})

// Reactive data
const masterTreatment = ref('')
const masterVeterinarian = ref('1')
const masterRecordingDate = ref(new Date().toISOString().split('T')[0])
const showDetailsModal = ref(false)
const currentAnimalId = ref(null)
const processing = ref(false)
const hasChanges = ref(false)
const showAddAnimalModal = ref(false)
const openMenuId = ref(null)
const showRemoveAnimalModal = ref(false)
const animalToRemove = ref(null)

// Add export state
const exportingCSV = ref(false)

// Animal treatments - each animal has its own treatment configuration
const animalTreatments = reactive({})

// Initialize animal treatments
const initializeAnimalTreatments = () => {
    if (props.group?.animals) {
        props.group.animals.forEach(animal => {
            animalTreatments[animal.id] = {
                animal_id: animal.id,
                recording_type: '',
                veterinarian_id: '1',
                recording_date: new Date().toISOString().split('T')[0],
                notes: '',
                recording_data: {}
            }
        })
    }
}

// Helper function to safely get animal treatment
const getAnimalTreatment = (animalId) => {
    if (!animalTreatments[animalId]) {
        animalTreatments[animalId] = {
            animal_id: animalId,
            recording_type: '',
            veterinarian_id: '1',
            recording_date: new Date().toISOString().split('T')[0],
            notes: '',
            recording_data: {}
        }
    }
    return animalTreatments[animalId]
}

// Computed properties
const animalsWithTreatment = computed(() => {
    return Object.values(animalTreatments).filter(treatment => treatment.recording_type)
})

const canCreateRecordings = computed(() => {
    return animalsWithTreatment.value.length > 0
})

// Methods
const getTreatmentLabel = (type) => {
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

const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

const applyMasterTreatment = () => {
    if (masterTreatment.value) {
        Object.keys(animalTreatments).forEach(animalId => {
            animalTreatments[animalId].recording_type = masterTreatment.value
        })
        hasChanges.value = true
    }
}

const applyMasterVeterinarian = () => {
    Object.keys(animalTreatments).forEach(animalId => {
        animalTreatments[animalId].veterinarian_id = masterVeterinarian.value
    })
    hasChanges.value = true
}

const applyMasterDate = () => {
    Object.keys(animalTreatments).forEach(animalId => {
        animalTreatments[animalId].recording_date = masterRecordingDate.value
    })
    hasChanges.value = true
}

const clearAllTreatments = () => {
    Object.keys(animalTreatments).forEach(animalId => {
        animalTreatments[animalId].recording_type = ''
        animalTreatments[animalId].recording_data = {}
        animalTreatments[animalId].notes = ''
    })
    masterTreatment.value = ''
    hasChanges.value = true
}

const updateAnimalTreatment = (animalId, field, value) => {
    const treatment = getAnimalTreatment(animalId)
    treatment[field] = value
    hasChanges.value = true
}

const updateAnimalRecordingData = (animalId, data) => {
    const treatment = getAnimalTreatment(animalId)
    treatment.recording_data = { ...data }
    hasChanges.value = true
}

const showTreatmentDetails = (animalId) => {
    currentAnimalId.value = animalId
    showDetailsModal.value = true
}

const closeDetailsModal = () => {
    showDetailsModal.value = false
    currentAnimalId.value = null
}

const saveAnimalDetails = () => {
    // Details are automatically saved via reactive updates
    showDetailsModal.value = false
    currentAnimalId.value = null
}

const getCurrentAnimal = () => {
    if (!currentAnimalId.value) return null
    return props.group.animals.find(animal => animal.id === currentAnimalId.value)
}

const createAllRecordings = async () => {
    processing.value = true
    
    try {
        const recordings = animalsWithTreatment.value.map(treatment => {
            const animal = props.group.animals.find(a => a.id === treatment.animal_id)
            return {
                animal_id: treatment.animal_id,
                rodeo_id: props.group.rodeo_id,
                client_id: props.group.rodeo.client_id,
                recording_type: treatment.recording_type,
                recording_data: treatment.recording_data,
                veterinarian_id: parseInt(treatment.veterinarian_id),
                notes: treatment.notes,
                status: 'completed',
                recording_date: treatment.recording_date
            }
        })

        console.log('Creating recordings for group:', {
            group_id: props.group.id,
            animals: recordings
        })

        // Make the API call to create recordings using the correct pattern
        router.post(route('recordings.bulk.store'), {
            recordings: recordings,
            group_id: props.group.id
        }, {
            onStart: () => {
                processing.value = true
            },
            onSuccess: (page) => {
                alert(`Se crearon ${recordings.length} registros exitosamente`)
                clearAllTreatments()
                hasChanges.value = false
            },
            onError: (errors) => {
                console.error('Error creating recordings:', errors)
                const errorMessage = errors.message || 'Error al crear los registros'
                alert(errorMessage)
            },
            onFinish: () => {
                processing.value = false
            }
        })
        
    } catch (error) {
        console.error('Error creating recordings:', error)
        alert('Error al crear los registros')
        processing.value = false
    }
}

const saveGroupChanges = () => {
    console.log('Saving group changes:', {
        group_id: props.group.id,
        animals: props.group.animals.map(animal => ({
            id: animal.id,
            caravana: animal.caravana,
            caravana_oficial: animal.caravana_oficial,
            treatment: animalTreatments[animal.id]
        }))
    })
    
    hasChanges.value = false
    alert('Cambios guardados exitosamente')
}

// Update the confirmRemoveAnimal method
const confirmRemoveAnimal = (animal) => {
    animalToRemove.value = animal
    showRemoveAnimalModal.value = true
}

// Update the removeAnimalFromGroup method
const removeAnimalFromGroup = async () => {
    if (!animalToRemove.value) return
    
    try {
        const animal = animalToRemove.value
        
        // Make API call to remove animal from group
        router.delete(route('groups.removeAnimals', props.group.id), {
            data: {
                animal_ids: [animal.id]  // Send as array to match backend validation
            },
            onSuccess: (page) => {
                // Remove from group animals array
                const groupIndex = props.group.animals.findIndex(groupAnimal => groupAnimal.id === animal.id)
                if (groupIndex > -1) {
                    props.group.animals.splice(groupIndex, 1)
                }
                
                // Remove from animal treatments
                delete animalTreatments[animal.id]
                
                hasChanges.value = true
                
                // Show success message from backend
                if (page.props.flash?.success) {
                    alert(page.props.flash.message || 'Animal removido del grupo exitosamente')
                }
                
                // Close modal and reset
                showRemoveAnimalModal.value = false
                animalToRemove.value = null
            },
            onError: (errors) => {
                console.error('Error removing animal from group:', errors)
                const errorMessage = errors.error || 'Error al remover el animal del grupo'
                alert(errorMessage)
                showRemoveAnimalModal.value = false
                animalToRemove.value = null
            }
        })
        
    } catch (error) {
        console.error('Error removing animal from group:', error)
        alert('Error al remover el animal del grupo')
        showRemoveAnimalModal.value = false
        animalToRemove.value = null
    }
}

// Add cancel method
const cancelRemoveAnimal = () => {
    showRemoveAnimalModal.value = false
    animalToRemove.value = null
}

const toggleActionsMenu = (animalId) => {
    openMenuId.value = openMenuId.value === animalId ? null : animalId
}

const closeActionsMenu = () => {
    openMenuId.value = null
}

// Close menu when clicking outside
const handleClickOutside = (event) => {
    if (!event.target.closest('.relative.inline-block.text-left')) {
        closeActionsMenu()
    }
}

// Initialize component
onMounted(() => {
    initializeAnimalTreatments()
    document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
})

// Add export method
const exportToCSV = async () => {
   
    exportingCSV.value = true
    
    try {
        // Create a temporary link to trigger download
        const link = document.createElement('a')
        link.href = route('groups.exportCSV', props.group.id)
        link.style.display = 'none'
        document.body.appendChild(link)
        link.click()
        document.body.removeChild(link)
        
    } catch (error) {
        console.error('Error exporting CSV:', error)
        alert('Error al exportar el archivo CSV')
    } finally {
        exportingCSV.value = false
    }
}
</script>

<style scoped>
.transition-colors {
    transition: color 0.2s ease-in-out, background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
}
</style>
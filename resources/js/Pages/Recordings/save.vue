<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            <i class="fas fa-file-medical text-green-600 mr-3"></i>
                            {{ isEditing ? 'Editar Registro' : 'Nuevo Registro' }}
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ isEditing ? 'Actualiza la información del registro veterinario' : 'Crea un nuevo registro veterinario para el animal' }}
                        </p>
                    </div>
                    <Link 
                        :href="route('recordings.index')"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                    >
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </Link>
                </div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <!-- Form Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-file-medical text-green-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Información del Registro</h2>
                            <p class="text-sm text-gray-600">Complete todos los campos requeridos</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitForm" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                       

                        <!-- Recording Date Field -->
                        <div>
                            <label for="recording_date" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-gray-400 mr-1"></i>
                                Fecha del Registro *
                            </label>
                            <DatePicker
                                v-model="form.recording_date"
                                input-id="recording_date"
                                placeholder="Seleccione la fecha del registro"
                                :has-error="!!errors.recording_date"
                                :error-message="errors.recording_date"
                                date-format="DD-MM-YYYY"
                            
                                @change="onDateChange"
                            />
                        </div>

                        <!-- Recording Type Field -->
                        <div>
                            <label for="recording_type" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-stethoscope text-gray-400 mr-1"></i>
                                Tipo de Registro *
                            </label>
                            <select
                                id="recording_type"
                                v-model="form.recording_type"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': errors.recording_type }"
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
                            <p v-if="errors.recording_type" class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ errors.recording_type }}
                            </p>
                        </div>

                        <!-- Veterinarian ID Field 
                        <div>
                            <label for="veterinarian_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user-md text-gray-400 mr-1"></i>
                                Veterinario ID *
                            </label>
                            <input
                                disabled
                                id="veterinarian_id"
                                v-model="form.veterinarian_id"
                                type="number"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': errors.veterinarian_id }"
                                placeholder="Ingrese el ID del veterinario"
                            />
                            <p v-if="errors.veterinarian_id" class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ errors.veterinarian_id }}
                            </p>
                        </div> 
                        -->

                        <!-- Status Field -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-flag text-gray-400 mr-1"></i>
                                Estado
                            </label>
                            <select
                                id="status"
                                v-model="form.status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': errors.status }"
                            >
                                <option value="pending">Pendiente</option>
                                <option value="completed">Completado</option>
                                <option value="cancelled">Cancelado</option>
                            </select>
                            <p v-if="errors.status" class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ errors.status }}
                            </p>
                        </div>

                        <!-- Recording Time Field 
                        <div>
                            <label for="recording_time" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-clock text-gray-400 mr-1"></i>
                                Hora del Registro
                            </label>
                            <input
                                id="recording_time"
                                v-model="form.recording_time"
                                type="time"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': errors.recording_time }"
                            />
                            <p v-if="errors.recording_time" class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ errors.recording_time }}
                            </p>
                        </div>-->
                    </div> 

                    <!-- Recording Data Section -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-database text-green-600 mr-2"></i>
                            Datos del Registro
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Temperature -->
                            <div>
                                <label for="temperature" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-thermometer-half text-gray-400 mr-1"></i>
                                    Temperatura
                                </label>
                                <input
                                    id="temperature"
                                    v-model="form.recording_data.temperature"
                                    type="text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                    placeholder="38.5°C"
                                />
                            </div>

                            <!-- Weight -->
                            <div>
                                <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-weight text-gray-400 mr-1"></i>
                                    Peso
                                </label>
                                <input
                                    id="weight"
                                    v-model="form.recording_data.weight"
                                    type="text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                    placeholder="450kg"
                                />
                            </div>

                            <!-- Condition -->
                            <div>
                                <label for="condition" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-heartbeat text-gray-400 mr-1"></i>
                                    Condición
                                </label>
                                <input
                                    id="condition"
                                    v-model="form.recording_data.condition"
                                    type="text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                    placeholder="Buena"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Notes Field -->
                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-sticky-note text-gray-400 mr-1"></i>
                            Notas
                        </label>
                        <textarea
                            id="notes"
                            v-model="form.notes"
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                            :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': errors.notes }"
                            placeholder="Ingrese notas adicionales sobre el registro..."
                        ></textarea>
                        <p v-if="errors.notes" class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ errors.notes }}
                        </p>
                    </div>

                    <!-- Additional Info Section -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                                <div>
                                    <h3 class="text-sm font-medium text-blue-900">Información Importante</h3>
                                    <p class="text-sm text-blue-700 mt-1">
                                        Este registro será guardado en la base de datos MongoDB y estará disponible para consultas futuras. 
                                        Asegúrese de que toda la información sea correcta antes de guardar.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 mt-8 pt-6 border-t border-gray-200">
                        <Link 
                            :href="route('animals.show', props.recording.animal_id)"
                            class="inline-flex justify-center items-center px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition-colors duration-200"
                        >sds
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </Link>
                        
                        <button
                            type="submit"
                            :disabled="processing"
                            class="inline-flex justify-center items-center px-6 py-3 bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                        >
                            <i v-if="processing" class="fas fa-spinner fa-spin mr-2"></i>
                            <i v-else :class="isEditing ? 'fas fa-save' : 'fas fa-plus'" class="mr-2"></i>
                            {{ processing ? 'Procesando...' : (isEditing ? 'Actualizar Registro' : 'Crear Registro') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Recording Preview Card (Only for editing) -->
            <div v-if="isEditing && recording" class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-eye text-blue-600 mr-2"></i>
                    Vista Previa del Registro
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-cow text-gray-400"></i>
                        <span class="text-sm text-gray-600">
                            <strong>Animal ID:</strong> {{ form.animal_id }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-clipboard-list text-gray-400"></i>
                        <span class="text-sm text-gray-600">
                            <strong>Rodeo ID:</strong> {{ form.rodeo_id }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-user text-gray-400"></i>
                        <span class="text-sm text-gray-600">
                            <strong>Cliente ID:</strong> {{ form.client_id }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-calendar-alt text-gray-400"></i>
                        <span class="text-sm text-gray-600">
                            <strong>Fecha:</strong> {{ form.recording_date }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-stethoscope text-gray-400"></i>
                        <span class="text-sm text-gray-600">
                            <strong>Tipo:</strong> {{ getRecordingTypeLabel(form.recording_type) }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-flag text-gray-400"></i>
                        <span class="text-sm text-gray-600">
                            <strong>Estado:</strong> {{ getStatusLabel(form.status) }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-user-md text-gray-400"></i>
                        <span class="text-sm text-gray-600">
                            <strong>Veterinario ID:</strong> {{ form.veterinarian_id }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import DatePicker from '@/Components/DatePicker.vue'

// Props
const props = defineProps({
    recording: {
        type: Object,
        default: null
    },
    errors: {
        type: Object,
        default: () => ({})
    },

})

console.log(props.recording.animal_id);

// Reactive data
const processing = ref(false)

// Computed properties
const isEditing = computed(() => {
    if (!props.recording) return false
    
    const fieldsToCheck = [
        props.recording.animal_id,
        props.recording.client_id,
        props.recording.rodeo_id,
        props.recording.recording_date,
        props.recording.recording_type,
        props.recording.veterinarian_id,
        props.recording.status,
        props.recording.recording_time,
        props.recording.notes
    ]
    
    const nonNullFields = fieldsToCheck.filter(field => field !== null && field !== undefined && field !== '')
    
    return nonNullFields.length > 3
})

// Form data using Inertia's useForm
const form = useForm({
    animal_id: props.recording?.animal_id ?? '',
    rodeo_id: props.recording?.rodeo_id ?? '',
    client_id: props.recording?.client_id ?? '',
    recording_date: props.recording?.recording_date ?? '',
    recording_time: props.recording?.recording_time ?? '',
    recording_type: props.recording?.recording_type ?? '',
    veterinarian_id: props.recording?.veterinarian_id ?? 1,
    status: props.recording?.status ?? 'pending',
    recording_data: {
        temperature: props.recording?.recording_data?.temperature ?? '',
        weight: props.recording?.recording_data?.weight ?? '',
        condition: props.recording?.recording_data?.condition ?? ''
    },
    notes: props.recording?.notes ?? ''
})

// Methods
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

const getStatusLabel = (status) => {
    const labels = {
        'pending': 'Pendiente',
        'completed': 'Completado',
        'cancelled': 'Cancelado'
    }
    return labels[status] || status
}

const onDateChange = (date) => {
    console.log('Selected date:', date)
}

const submitForm = () => {
    processing.value = true
    
    if (isEditing.value) {
        // Update existing recording
        form.put(route('recordings.update', props.recording._id), {
            onFinish: () => {
                processing.value = false
            }
        })
    } else {
        // Create new recording
        form.post(route('recordings.store'), {
            onFinish: () => {
                processing.value = false
            }
        })
    }
}

// Lifecycle
onMounted(() => {
    // Focus on first input when component mounts
    document.getElementById('recording_type')?.focus()
})
</script>

<style scoped>
.transition-colors {
    transition: color 0.2s ease-in-out, background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
}

/* Loading spinner animation */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.fa-spin {
    animation: spin 1s linear infinite;
}
</style>
<template>
    <div class="space-y-4">
        <!-- Common fields for all treatments -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Temperatura (°C)
                </label>
                <input
                    :value="recordingData.temperature"
                    @input="updateData('temperature', $event.target.value)"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="38.5"
                />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Peso (kg)
                </label>
                <input
                    :value="recordingData.weight"
                    @input="updateData('weight', $event.target.value)"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="500"
                />
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Condición
            </label>
            <select
                :value="recordingData.condition"
                @change="updateData('condition', $event.target.value)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
            >
                <option value="">Seleccionar condición</option>
                <option value="excellent">Excelente</option>
                <option value="good">Buena</option>
                <option value="fair">Regular</option>
                <option value="poor">Pobre</option>
            </select>
        </div>

        <!-- Treatment-specific fields -->
        <div v-if="treatmentType === 'insemination'" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Semen
                    </label>
                    <select
                        :value="recordingData.semen_type"
                        @change="updateData('semen_type', $event.target.value)"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    >
                        <option value="">Seleccionar tipo</option>
                        <option value="artificial">Artificial</option>
                        <option value="natural">Natural</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        ID del Toro
                    </label>
                    <input
                        :value="recordingData.bull_id"
                        @input="updateData('bull_id', $event.target.value)"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="227"
                    />
                </div>
            </div>
        </div>

        <div v-else-if="treatmentType === 'vaccination'" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Vacuna
                    </label>
                    <input
                        :value="recordingData.vaccine_type"
                        @input="updateData('vaccine_type', $event.target.value)"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Clostridium"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Dosis
                    </label>
                    <input
                        :value="recordingData.dosage"
                        @input="updateData('dosage', $event.target.value)"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="2ml"
                    />
                </div>
            </div>
        </div>

        <div v-else-if="treatmentType === 'ultrasound'" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Estado de Preñez
                    </label>
                    <select
                        :value="recordingData.pregnancy_status"
                        @change="updateData('pregnancy_status', $event.target.value)"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    >
                        <option value="">Seleccionar estado</option>
                        <option value="positive">Positivo</option>
                        <option value="negative">Negativo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Días de Gestación
                    </label>
                    <input
                        :value="recordingData.gestation_days"
                        @input="updateData('gestation_days', $event.target.value)"
                        type="number"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="30"
                    />
                </div>
            </div>
        </div>

        <div v-else-if="treatmentType === 'natural_breeding'" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        ID del Toro
                    </label>
                    <input
                        :value="recordingData.bull_id"
                        @input="updateData('bull_id', $event.target.value)"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="319"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Duración del Apareamiento
                    </label>
                    <select
                        :value="recordingData.mating_duration"
                        @change="updateData('mating_duration', $event.target.value)"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    >
                        <option value="">Seleccionar duración</option>
                        <option value="short">Corta</option>
                        <option value="normal">Normal</option>
                        <option value="extended">Extendida</option>
                    </select>
                </div>
            </div>
        </div>

        <div v-else-if="treatmentType === 'weight_check'" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Puntuación Corporal
                </label>
                <input
                    :value="recordingData.body_score"
                    @input="updateData('body_score', $event.target.value)"
                    type="number"
                    step="0.1"
                    min="1"
                    max="5"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="3.5"
                />
            </div>
        </div>

        <div v-else-if="treatmentType === 'health_check'" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Observaciones de Salud
                </label>
                <textarea
                    :value="recordingData.health_observations"
                    @input="updateData('health_observations', $event.target.value)"
                    rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="Observaciones del control de salud"
                ></textarea>
            </div>
        </div>

        <div v-else-if="treatmentType === 'other'" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Procedimiento
                </label>
                <input
                    :value="recordingData.procedure"
                    @input="updateData('procedure', $event.target.value)"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="Descripción del procedimiento"
                />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Resultados
                </label>
                <textarea
                    :value="recordingData.results"
                    @input="updateData('results', $event.target.value)"
                    rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="Resultados del procedimiento"
                ></textarea>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
    animalId: {
        type: Number,
        required: true
    },
    treatmentType: {
        type: String,
        required: true
    },
    recordingData: {
        type: Object,
        default: () => ({})
    }
})

const emit = defineEmits(['update'])

const updateData = (field, value) => {
    const newData = { ...props.recordingData, [field]: value }
    emit('update', props.animalId, newData)
}
</script>
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
                            {{ isEditing ? 'Editar Dieta' : 'Nueva Dieta' }}
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ isEditing ? 'Actualiza la información de la dieta' : 'Registra una nueva dieta para alimentación bovina' }}
                        </p>
                    </div>
                    <Link 
                        :href="route('diets.index')"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                    >
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver a Dietas
                    </Link>
                </div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <form @submit.prevent="submitForm" class="p-6">
                    <!-- Basic Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Información General
                        </h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Diet Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre de la Dieta *
                                </label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    placeholder="Ej: Dieta Engorde Intensivo"
                                />
                                <div v-if="errors.name" class="mt-1 text-sm text-red-600">
                                    {{ errors.name }}
                                </div>
                            </div>

                            <!-- Diet Type -->
                            <div>
                                <label for="diet_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo de Dieta *
                                </label>
                                <select
                                    id="diet_type"
                                    v-model="form.diet_type"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                >
                                    <option value="">Selecciona un tipo</option>
                                    <option value="maintenance">Mantenimiento</option>
                                    <option value="growth">Crecimiento</option>
                                    <option value="reproduction">Reproducción</option>
                                    <option value="lactation">Lactancia</option>
                                    <option value="custom">Personalizada</option>
                                </select>
                                <div v-if="errors.diet_type" class="mt-1 text-sm text-red-600">
                                    {{ errors.diet_type }}
                                </div>
                            </div>

                            <!-- Total Weight - Make it readonly and show calculated value -->
                            <div>
                                <label for="total_weight" class="block text-sm font-medium text-gray-700 mb-2">
                                    Peso Total (Calculado Automáticamente)
                                </label>
                                <div class="relative">
                                    <input
                                        id="total_weight"
                                        :value="calculatedTotalWeight"
                                        type="text"
                                        readonly
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                        placeholder="Se calcula automáticamente"
                                    />
                                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                        <i class="fas fa-calculator text-gray-400"></i>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Se calcula sumando todos los componentes (kg y gr convertidos a kg)
                                </p>
                                <div v-if="errors.total_weight" class="mt-1 text-sm text-red-600">
                                    {{ errors.total_weight }}
                                </div>
                            </div>

                            <!-- Status (only show when editing) -->
                            <div v-if="isEditing">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                    Estado
                                </label>
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                >
                                    <option value="active">Activa</option>
                                    <option value="inactive">Inactiva</option>
                                </select>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Descripción
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="Describe el propósito y características de la dieta..."
                            ></textarea>
                            <div v-if="errors.description" class="mt-1 text-sm text-red-600">
                                {{ errors.description }}
                            </div>
                        </div>
                    </div>

                    <!-- Components Section -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-list-ul text-green-600 mr-2"></i>
                                Componentes de la Dieta
                            </h3>
                            <button
                                type="button"
                                @click="addComponent"
                                class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                            >
                                <i class="fas fa-plus mr-2"></i>
                                Agregar Componente
                            </button>
                        </div>

                        <div v-if="form.components.length === 0" class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                            <i class="fas fa-plus-circle text-gray-400 text-4xl mb-3"></i>
                            <p class="text-gray-600 mb-4">No hay componentes agregados</p>
                            <button
                                type="button"
                                @click="addComponent"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200"
                            >
                                <i class="fas fa-plus mr-2"></i>
                                Agregar Primer Componente
                            </button>
                        </div>

                        <div v-else class="space-y-4">
                            <div
                                v-for="(component, index) in form.components"
                                :key="index"
                                class="bg-gray-50 rounded-lg p-4 border border-gray-200"
                            >
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-900">
                                        Componente {{ index + 1 }}
                                    </h4>
                                    <button
                                        type="button"
                                        @click="removeComponent(index)"
                                        class="p-1 text-red-600 hover:bg-red-50 rounded transition-colors duration-200"
                                        title="Eliminar componente"
                                    >
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <!-- Component Name -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">
                                            Nombre *
                                        </label>
                                        <input
                                            v-model="component.name"
                                            type="text"
                                            required
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                            placeholder="Ej: Silo maíz"
                                        />
                                        <div v-if="errors[`components.${index}.name`]" class="mt-1 text-xs text-red-600">
                                            {{ errors[`components.${index}.name`] }}
                                        </div>
                                    </div>

                                    <!-- Amount -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">
                                            Cantidad *
                                        </label>
                                        <input
                                            v-model="component.amount"
                                            type="text"
                                            required
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                            placeholder="Ej: 24"
                                        />
                                        <div v-if="errors[`components.${index}.amount`]" class="mt-1 text-xs text-red-600">
                                            {{ errors[`components.${index}.amount`] }}
                                        </div>
                                    </div>

                                    <!-- Unit -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">
                                            Unidad *
                                        </label>
                                        <select
                                            v-model="component.unit"
                                            required
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        >
                                            <option value="kg">Kilogramos</option>
                                            <option value="gr">Gramos</option>
                                            <option value="L">Litros</option>
                                            <option value="ml">Mililitros</option>
                                            <option value="units">Unidades</option>
                                            <option value="other">Otro</option>
                                        </select>
                                        <div v-if="errors[`components.${index}.unit`]" class="mt-1 text-xs text-red-600">
                                            {{ errors[`components.${index}.unit`] }}
                                        </div>
                                    </div>

                                    <!-- Notes -->
                                    <div class="md:col-span-2 lg:col-span-1">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">
                                            Notas
                                        </label>
                                        <input
                                            v-model="component.notes"
                                            type="text"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                            placeholder="Notas opcionales"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="mb-8">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notas Adicionales
                        </label>
                        <textarea
                            id="notes"
                            v-model="form.notes"
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Instrucciones especiales, recomendaciones de uso, etc..."
                        ></textarea>
                        <div v-if="errors.notes" class="mt-1 text-sm text-red-600">
                            {{ errors.notes }}
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200">
                        <Link 
                            :href="route('diets.index')"
                            class="inline-flex justify-center items-center px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition-colors duration-200"
                        >
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </Link>
                        
                        <button
                            type="submit"
                            :disabled="processing || form.components.length === 0"
                            class="inline-flex justify-center items-center px-6 py-3 bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                        >
                            <i v-if="processing" class="fas fa-spinner fa-spin mr-2"></i>
                            <i v-else :class="isEditing ? 'fas fa-save' : 'fas fa-plus'" class="mr-2"></i>
                            {{ processing ? 'Guardando...' : (isEditing ? 'Actualizar Dieta' : 'Crear Dieta') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </AppLayout>

</template>

<script setup>
import { ref, computed, reactive, watch } from 'vue'
import { Link, useForm, router } from '@inertiajs/vue3'

import AppLayout from '@/Layouts/AppLayout.vue'


// Props
const props = defineProps({
    diet: {
        type: Object,
        default: null
    },
    errors: {
        type: Object,
        default: () => ({})
    }
})

// Computed properties
const isEditing = computed(() => !!props.diet)

// Form data
const form = useForm({
    name: props.diet?.name || '',
    description: props.diet?.description || '',
    components: props.diet?.components ? [...props.diet.components] : [],
    total_weight: props.diet?.total_weight || '',
    diet_type: props.diet?.diet_type || '',
    status: props.diet?.status || 'active',
    notes: props.diet?.notes || ''
})

// Reactive state
const processing = ref(false)

// Computed property for automatic weight calculation
const calculatedTotalWeight = computed(() => {
    let totalKg = 0
    
    form.components.forEach(component => {
        const amount = parseFloat(component.amount) || 0
        
        if (amount > 0) {
            switch (component.unit) {
                case 'kg':
                    totalKg += amount
                    break
                case 'gr':
                    totalKg += amount / 1000 // Convert grams to kg
                    break
                case 'L':
                    totalKg += amount // Assume 1L = 1kg for liquids
                    break
                case 'ml':
                    totalKg += amount / 1000 // Convert ml to L, then assume 1L = 1kg
                    break
                case 'units':
                    // For units, we could assume an average weight per unit
                    // For now, we'll skip units in the calculation
                    break
                case 'other':
                    // Skip 'other' units as we don't know the conversion
                    break
            }
        }
    })
    
    return totalKg > 0 ? `${totalKg.toFixed(2)} kg` : ''
})

// Watch for changes in components and auto-update total weight
watch(
    () => form.components.map(c => ({ amount: c.amount, unit: c.unit })),
    () => {
        form.total_weight = calculatedTotalWeight.value
    },
    { deep: true }
)

// Methods
const addComponent = () => {
    form.components.push({
        name: '',
        amount: '',
        unit: 'kg',
        notes: ''
    })
}

const removeComponent = (index) => {
    if (form.components.length > 1) {
        form.components.splice(index, 1)
    } else if (confirm('¿Estás seguro de eliminar el único componente? Una dieta debe tener al menos un componente.')) {
        form.components.splice(index, 1)
    }
    // After removing, recalculate total weight
    form.total_weight = calculatedTotalWeight.value
}

const submitForm = () => {
    if (form.components.length === 0) {
        alert('Debe agregar al menos un componente a la dieta')
        return
    }

    // Validate components
    const hasInvalidComponents = form.components.some(component => 
        !component.name.trim() || !component.amount.trim()
    )

    if (hasInvalidComponents) {
        alert('Todos los componentes deben tener nombre y cantidad')
        return
    }

    processing.value = true

    // Ensure total weight is calculated before submitting
    form.total_weight = calculatedTotalWeight.value

    if (isEditing.value) {
        // Update existing diet
        form.put(route('diets.update', props.diet._id), {
            onSuccess: () => {
                processing.value = false
            },
            onError: () => {
                processing.value = false
            }
        })
    } else {
        // Create new diet
        form.post(route('diets.store'), {
            onSuccess: () => {
                processing.value = false
            },
            onError: () => {
                processing.value = false
            }
        })
    }
}

// Initialize with at least one component for new diets
if (!isEditing.value && form.components.length === 0) {
    addComponent()
}
</script>
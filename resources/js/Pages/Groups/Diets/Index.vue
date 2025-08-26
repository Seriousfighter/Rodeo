<template>

    <AppLayout title="Gestión de Dietas del Grupo">

    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            <i class="fas fa-utensils text-green-600 mr-3"></i>
                            Dietas del Grupo: {{ group.name }}
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Gestión de dietas asignadas al grupo
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <Link 
                            :href="route('groups.show', group.id)"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                        >
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver al Grupo
                        </Link>
                        <button
                            @click="showAssignModal = true"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            Asignar Dieta
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Group Diets -->
            <div v-if="loading" class="text-center py-12">
                <i class="fas fa-spinner fa-spin text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-600">Cargando dietas...</p>
            </div>

            <div v-else-if="!groupDiets?.success || !groupDiets?.data?.diets?.length" class="text-center py-12">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <i class="fas fa-utensils text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay dietas asignadas</h3>
                    <p class="text-gray-600 mb-6">Asigna la primera dieta a este grupo</p>
                    <button
                        @click="showAssignModal = true"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200"
                    >
                        <i class="fas fa-plus mr-2"></i>
                        Asignar Primera Dieta
                    </button>
                </div>
            </div>

            <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div 
                    v-for="diet in groupDiets.data.diets" 
                    :key="diet._id"
                    class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200"
                    :class="{ 'ring-2 ring-green-500': isActiveDiet(diet._id) }"
                >
                    <!-- Diet Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="flex items-center space-x-2 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ diet.name }}</h3>
                                    <span v-if="isActiveDiet(diet._id)" class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                        ACTIVA
                                    </span>
                                    <span v-if="diet.is_customized" class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                        PERSONALIZADA
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600">{{ getDietTypeName(diet.diet_type) }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Condición: {{ getConditionName(diet.usage_condition) }}
                                </p>
                            </div>
                            <div class="flex space-x-1">
                                <button
                                    @click="editDiet(diet)"
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                    title="Editar dieta"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button
                                    v-if="!isActiveDiet(diet._id)"
                                    @click="setActiveDiet(diet._id)"
                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors duration-200"
                                    title="Establecer como activa"
                                >
                                    <i class="fas fa-check-circle"></i>
                                </button>
                                <button
                                    @click="confirmRemoveDiet(diet)"
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                    title="Remover dieta"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Diet Details -->
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Componentes:</h4>
                                <div class="space-y-2">
                                    <div 
                                        v-for="component in diet.components" 
                                        :key="component._id"
                                        class="flex justify-between items-center text-sm"
                                    >
                                        <span class="text-gray-700">{{ component.name }}</span>
                                        <span class="text-gray-600">{{ component.amount }} {{ getUnitLabel(component.unit) }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div v-if="diet.total_weight" class="flex justify-between text-sm">
                                <span class="font-medium text-gray-900">Peso Total:</span>
                                <span class="text-gray-600">{{ diet.total_weight }}</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="font-medium text-gray-900">Prioridad:</span>
                                <span class="text-gray-600">#{{ diet.priority }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assign Diet Modal -->
        <div v-if="showAssignModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Asignar Dieta al Grupo</h3>
                        <button @click="cancelAssign" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form @submit.prevent="assignDiet" class="space-y-4">
                        <!-- Select Diet -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Seleccionar Dieta
                            </label>
                            <select 
                                v-model="assignForm.diet_id" 
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                            >
                                <option value="">Seleccione una dieta...</option>
                                <option 
                                    v-for="diet in availableDiets?.data || []" 
                                    :key="diet._id" 
                                    :value="diet._id"
                                >
                                    {{ diet.name }} ({{ getDietTypeName(diet.diet_type) }})
                                </option>
                            </select>
                        </div>

                        <!-- Usage Condition -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Condición de Uso
                            </label>
                            <select 
                                v-model="assignForm.usage_condition"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                            >
                                <option value="normal">Normal</option>
                                <option value="rain">Lluvia</option>
                                <option value="hot_weather">Clima Caluroso</option>
                                <option value="cold_weather">Clima Frío</option>
                                <option value="drought">Sequía</option>
                                <option value="winter">Invierno</option>
                                <option value="summer">Verano</option>
                                <option value="emergency">Emergencia</option>
                                <option value="custom">Personalizada</option>
                            </select>
                        </div>

                        <!-- Priority -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Prioridad
                            </label>
                            <input 
                                v-model.number="assignForm.priority"
                                type="number"
                                min="1"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                            >
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Notas
                            </label>
                            <textarea 
                                v-model="assignForm.notes"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Notas adicionales..."
                            ></textarea>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <button
                                type="button"
                                @click="cancelAssign"
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors duration-200"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="assigning"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 disabled:opacity-50"
                            >
                                <i v-if="assigning" class="fas fa-spinner fa-spin mr-2"></i>
                                {{ assigning ? 'Asignando...' : 'Asignar Dieta' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Confirmar Eliminación</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            ¿Estás seguro de que deseas remover la dieta "{{ dietToDelete?.name }}" del grupo?
                            Esta acción no se puede deshacer.
                        </p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button
                            @click="removeDiet"
                            :disabled="deleting"
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 mr-3 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 disabled:opacity-50"
                        >
                            <i v-if="deleting" class="fas fa-spinner fa-spin"></i>
                            <span v-else>Eliminar</span>
                        </button>
                        <button
                            @click="cancelRemove"
                            class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-24 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300"
                        >
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </AppLayout>

</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

import AppLayout from '@/Layouts/AppLayout.vue'


const props = defineProps({
    group: Object,
    groupDiets: Object,
    availableDiets: Object
})

// State
const loading = ref(false)
const showAssignModal = ref(false)
const assigning = ref(false)
const showDeleteModal = ref(false)
const deleting = ref(false)
const dietToDelete = ref(null)

const assignForm = ref({
    diet_id: '',
    usage_condition: 'normal',
    priority: 1,
    notes: ''
})

// Computed
const isActiveDiet = (dietId) => {
    return props.groupDiets?.data?.active_diet_id === dietId
}

// Methods
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

const getConditionName = (condition) => {
    const names = {
        normal: 'Normal',
        rain: 'Lluvia',
        hot_weather: 'Clima Caluroso',
        cold_weather: 'Clima Frío',
        drought: 'Sequía',
        winter: 'Invierno',
        summer: 'Verano',
        emergency: 'Emergencia',
        custom: 'Personalizada'
    }
    return names[condition] || 'Normal'
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

const assignDiet = () => {
    assigning.value = true
    
    router.post(route('groups.diets.assign', props.group.id), assignForm.value, {
        onSuccess: () => {
            showAssignModal.value = false
            assignForm.value = {
                diet_id: '',
                usage_condition: 'normal',
                priority: 1,
                notes: ''
            }
        },
        onError: (errors) => {
            console.error('Error assigning diet:', errors)
            alert('Error al asignar la dieta')
        },
        onFinish: () => {
            assigning.value = false
        }
    })
}

const cancelAssign = () => {
    showAssignModal.value = false
    assignForm.value = {
        diet_id: '',
        usage_condition: 'normal',
        priority: 1,
        notes: ''
    }
}

const setActiveDiet = (dietId) => {
    router.post(route('groups.diets.activate', [props.group.id, dietId]), {}, {
        onError: (errors) => {
            console.error('Error setting active diet:', errors)
            alert('Error al establecer la dieta activa')
        }
    })
}

const confirmRemoveDiet = (diet) => {
    dietToDelete.value = diet
    showDeleteModal.value = true
}

const removeDiet = () => {
    if (!dietToDelete.value) return
    
    deleting.value = true
    
    router.delete(route('groups.diets.remove', [props.group.id, dietToDelete.value._id]), {
        onSuccess: () => {
            showDeleteModal.value = false
            dietToDelete.value = null
        },
        onError: (errors) => {
            console.error('Error removing diet:', errors)
            alert('Error al remover la dieta')
        },
        onFinish: () => {
            deleting.value = false
        }
    })
}

const cancelRemove = () => {
    showDeleteModal.value = false
    dietToDelete.value = null
}

const editDiet = (diet) => {
    // TODO: Implementar modal de edición de dieta
    console.log('Edit diet:', diet)
    alert('Funcionalidad de edición próximamente')
}
</script>

<style scoped>
.transition-shadow {
    transition: box-shadow 0.2s ease-in-out;
}
</style>
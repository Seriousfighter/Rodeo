<template>
    <AppLayout title="Grupos del Rodeo">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Grupos del Rodeo
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8">
                        <!-- Header Actions -->
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">
                                    Grupos del Rodeo
                                </h3>
                                <p class="text-sm text-gray-600">
                                    Gestiona los grupos de animales para este rodeo
                                </p>
                            </div>
                            <div class="flex space-x-3">
                                <Link 
                                    :href="route('rodeos.show', rodeoId)"
                                    class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                                >
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Volver al Rodeo
                                </Link>
                                <button
                                    @click="showCreateModal = true"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                                >
                                    <i class="fas fa-plus mr-2"></i>
                                    Crear Grupo
                                </button>
                            </div>
                        </div>

                        <!-- Groups Grid -->
                        <div v-if="groups && groups.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div 
                                v-for="group in groups" 
                                :key="group.id"
                                class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200"
                            >
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-semibold text-gray-900 mb-2">
                                                {{ group.name }}
                                            </h4>
                                            <p v-if="group.description" class="text-sm text-gray-600 mb-3">
                                                {{ group.description }}
                                            </p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button
                                                @click="editGroup(group)"
                                                class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                                title="Editar grupo"
                                            >Editar
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button
                                                @click="confirmDelete(group)"
                                                class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                                title="Eliminar grupo"
                                            >
                                                <i class="fas fa-trash mr-1"></i>
                                                Eliminar
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Animals Info -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class="fas fa-cow mr-2"></i>
                                            <span>{{ group.animals?.length || 0 }} animales</span>
                                        </div>
                                        <button
                                            @click="viewGroupDetails(group)"
                                            class="text-sm text-blue-600 hover:text-blue-800 font-medium"
                                        >
                                            Ver detalles
                                        </button>
                                    </div>

                                    <!-- Animals Preview -->
                                    <div v-if="group.animals && group.animals.length > 0" class="mt-4 pt-4 border-t border-gray-100">
                                        <p class="text-xs text-gray-500 mb-2">Animales en el grupo:</p>
                                        <div class="flex flex-wrap gap-1">
                                            <span 
                                                v-for="animal in group.animals.slice(0, 5)" 
                                                :key="animal.id"
                                                class="inline-flex items-center px-2 py-1 bg-gray-100 text-xs text-gray-700 rounded"
                                            >
                                                {{ animal.caravana }}
                                            </span>
                                            <span 
                                                v-if="group.animals.length > 5"
                                                class="inline-flex items-center px-2 py-1 bg-gray-200 text-xs text-gray-600 rounded"
                                            >
                                                +{{ group.animals.length - 5 }} más
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-else class="text-center py-12">
                            <i class="fas fa-layer-group text-gray-400 text-6xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                No hay grupos creados
                            </h3>
                            <p class="text-gray-600 mb-6">
                                Comienza creando tu primer grupo de animales para este rodeo.
                            </p>
                            <button
                                @click="showCreateModal = true"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                            >
                                <i class="fas fa-plus mr-2"></i>
                                Crear Primer Grupo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Group Modal -->
        <Modal :show="showCreateModal" @close="closeCreateModal">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    {{ editingGroup ? 'Editar Grupo' : 'Crear Nuevo Grupo' }}
                </h3>
                
                <form @submit.prevent="saveGroup">
                    <div class="mb-4">
                        <label for="groupName" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre del Grupo
                        </label>
                        <input
                            id="groupName"
                            v-model="form.name"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Ingrese el nombre del grupo"
                            required
                        />
                    </div>

                    <div class="mb-6">
                        <label for="groupDescription" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción (opcional)
                        </label>
                        <textarea
                            id="groupDescription"
                            v-model="form.description"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Descripción del grupo"
                        ></textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            @click="closeCreateModal"
                            class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors duration-200"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="loading"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200 disabled:opacity-50"
                        >
                            <span v-if="loading" class="inline-flex items-center">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Guardando...
                            </span>
                            <span v-else>
                                {{ editingGroup ? 'Actualizar' : 'Crear' }} Grupo
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Confirmation Modal -->
        <Modal :show="showDeleteModal" @close="closeDeleteModal">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3"></i>
                    <h3 class="text-lg font-medium text-gray-900">
                        Confirmar Eliminación
                    </h3>
                </div>
                
                <p class="text-gray-600 mb-6">
                    ¿Estás seguro de que quieres eliminar el grupo "{{ groupToDelete?.name }}"? 
                    Esta acción no se puede deshacer.
                </p>

                <div class="flex justify-end space-x-3">
                    <button
                        @click="closeDeleteModal"
                        class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors duration-200"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="deleteGroup"
                        :disabled="loading"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200 disabled:opacity-50"
                    >
                        <span v-if="loading" class="inline-flex items-center">
                            <i class="fas fa-spinner fa-spin mr-2"></i>
                            Eliminando...
                        </span>
                        <span v-else>
                            Eliminar Grupo
                        </span>
                    </button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Modal from '@/Components/Modal.vue'

// Props
const props = defineProps({
    groups: {
        type: Array,
        default: () => []
    },
    rodeoId: {
        type: Number,
        required: true
    }
})

// State
const showCreateModal = ref(false)
const showDeleteModal = ref(false)
const loading = ref(false)
const editingGroup = ref(null)
const groupToDelete = ref(null)

const form = reactive({
    name: '',
    description: ''
})

// Methods
const closeCreateModal = () => {
    showCreateModal.value = false
    editingGroup.value = null
    form.name = ''
    form.description = ''
}

const closeDeleteModal = () => {
    showDeleteModal.value = false
    groupToDelete.value = null
}

const editGroup = (group) => {
    editingGroup.value = group
    form.name = group.name
    form.description = group.description || ''
    showCreateModal.value = true
}

const confirmDelete = (group) => {
    groupToDelete.value = group
    showDeleteModal.value = true
}

const saveGroup = async () => {
    loading.value = true
    
    try {
        if (editingGroup.value) {
            // Update existing group
            await axios.put(route('groups.update', editingGroup.value.id), {
                name: form.name,
                description: form.description
            })
        } else {
            // Create new group
            await axios.post(route('groups.store'), {
                name: form.name,
                description: form.description,
                rodeo_id: props.rodeoId
            })
        }
        
        closeCreateModal()
        
        // Refresh the page to show updated data
        router.visit(route('rodeo.groups', props.rodeoId))
        
    } catch (error) {
        console.error('Error saving group:', error)
    } finally {
        loading.value = false
    }
}

const deleteGroup = async () => {
    if (!groupToDelete.value) return
    
    loading.value = true
    
    try {
        await axios.delete(route('groups.destroy', groupToDelete.value.id))
        
        closeDeleteModal()
        
        // Refresh the page to show updated data
        router.visit(route('rodeo.groups', props.rodeoId))
        
    } catch (error) {
        console.error('Error deleting group:', error)
    } finally {
        loading.value = false
    }
}

const viewGroupDetails = (group) => {
    // Navigate to group details page
    router.visit(route('groups.show', group.id))
}
</script>
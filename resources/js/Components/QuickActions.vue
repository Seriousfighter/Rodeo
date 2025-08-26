<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const isOpen = ref(false);

const actions = [
    {
        name: 'Nuevo Rodeo',
        route: 'rodeos.create',
        icon: 'fas fa-horse',
        color: 'bg-green-600 hover:bg-green-700'
    },
    {
        name: 'Nuevo Cliente',
        route: 'clients.create',
        icon: 'fas fa-user-plus',
        color: 'bg-blue-600 hover:bg-blue-700'
    },
    {
        name: 'Nueva Dieta',
        route: 'diets.create',
        icon: 'fas fa-leaf',
        color: 'bg-emerald-600 hover:bg-emerald-700'
    },
    {
        name: 'Nuevo Registro',
        route: 'recordings.create',
        icon: 'fas fa-clipboard-list',
        color: 'bg-purple-600 hover:bg-purple-700'
    }
];

const toggleActions = () => {
    isOpen.value = !isOpen.value;
};

const closeActions = () => {
    isOpen.value = false;
};
</script>

<template>
    <!-- Overlay -->
    <div 
        v-if="isOpen" 
        class="fixed inset-0 bg-black bg-opacity-25 z-40"
        @click="closeActions"
    ></div>

    <!-- Quick Actions -->
    <div class="fixed bottom-6 right-6 z-50">
        <!-- Action Items -->
        <transition-group
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0 scale-95 translate-y-2"
            enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100 scale-100 translate-y-0"
            leave-to-class="opacity-0 scale-95 translate-y-2"
        >
            <div 
                v-for="(action, index) in actions" 
                v-show="isOpen"
                :key="action.route"
                class="mb-4"
                :style="{ transitionDelay: `${index * 50}ms` }"
            >
                <Link 
                    :href="route(action.route)"
                    :class="[
                        'flex items-center px-4 py-3 rounded-lg shadow-lg text-white text-sm font-medium transition-all duration-200 hover:shadow-xl transform hover:scale-105',
                        action.color
                    ]"
                    @click="closeActions"
                >
                    <i :class="action.icon" class="mr-3"></i>
                    {{ action.name }}
                </Link>
            </div>
        </transition-group>

        <!-- Main Button -->
        <button
            @click="toggleActions"
            :class="[
                'flex items-center justify-center w-14 h-14 rounded-full shadow-lg text-white font-bold transition-all duration-300 hover:shadow-xl transform',
                isOpen 
                    ? 'bg-red-600 hover:bg-red-700 rotate-45' 
                    : 'bg-green-600 hover:bg-green-700 hover:scale-110'
            ]"
            title="Acciones rápidas"
        >
            <i class="fas fa-plus text-xl"></i>
        </button>
    </div>
</template>
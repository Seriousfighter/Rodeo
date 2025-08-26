// filepath: /home/alan/Propios/RODEO/RodeoV2/resources/js/Components/Breadcrumb.vue
<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    items: {
        type: Array,
        required: true,
        // Expected format: [{ name: 'Dashboard', route: 'dashboard' }, { name: 'Rodeos', route: 'rodeos.index' }, { name: 'Show' }]
    }
});
</script>

<template>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
            <li>
                <div>
                    <Link :href="route('dashboard')" class="text-gray-400 hover:text-gray-500 transition-colors">
                        <i class="fas fa-home"></i>
                        <span class="sr-only">Dashboard</span>
                    </Link>
                </div>
            </li>
            <li v-for="(item, index) in items" :key="index">
                <div class="flex items-center">
                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                    </svg>
                    <Link 
                        v-if="item.route && index < items.length - 1" 
                        :href="route(item.route)"
                        class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors"
                    >
                        {{ item.name }}
                    </Link>
                    <span 
                        v-else 
                        class="ml-4 text-sm font-medium text-gray-900" 
                        :aria-current="index === items.length - 1 ? 'page' : undefined"
                    >
                        {{ item.name }}
                    </span>
                </div>
            </li>
        </ol>
    </nav>
</template>
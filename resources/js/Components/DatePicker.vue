<template>
    <div class="relative">
        <!-- Date Input Field -->
        <div class="relative">
            <input
                :id="inputId"
                v-model="displayValue"
                type="text"
                :placeholder="placeholder"
                :disabled="disabled"
                readonly
                @click="toggleCalendar"
                class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 cursor-pointer bg-white"
                :class="[
                    inputClass,
                    { 'border-red-500 focus:ring-red-500 focus:border-red-500': hasError },
                    { 'bg-gray-50 cursor-not-allowed': disabled }
                ]"
            />
            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <button
                    type="button"
                    @click="toggleCalendar"
                    :disabled="disabled"
                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200 disabled:cursor-not-allowed"
                >
                    <i class="fas fa-calendar-alt"></i>
                </button>
            </div>
        </div>

        <!-- Error Message -->
        <p v-if="hasError && errorMessage" class="mt-2 text-sm text-red-600">
            <i class="fas fa-exclamation-circle mr-1"></i>
            {{ errorMessage }}
        </p>

        <!-- Calendar Dropdown -->
        <div
            v-if="showCalendar"
            class="absolute z-50 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg"
            :class="dropdownClass"
        >
            <!-- Calendar Header -->
            <div class="flex items-center justify-between p-3 bg-green-50 border-b border-gray-200">
                <button
                    type="button"
                    @click="previousMonth"
                    class="p-1 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded transition-colors duration-200"
                >
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <div class="flex items-center space-x-2">
                    <select
                        v-model="currentMonth"
                        @change="updateCalendar"
                        class="text-sm font-medium text-gray-700 border-0 bg-transparent focus:outline-none"
                    >
                        <option v-for="(month, index) in months" :key="index" :value="index">
                            {{ month }}
                        </option>
                    </select>
                    <select
                        v-model="currentYear"
                        @change="updateCalendar"
                        class="text-sm font-medium text-gray-700 border-0 bg-transparent focus:outline-none"
                    >
                        <option v-for="year in availableYears" :key="year" :value="year">
                            {{ year }}
                        </option>
                    </select>
                </div>
                
                <button
                    type="button"
                    @click="nextMonth"
                    class="p-1 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded transition-colors duration-200"
                >
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Calendar Grid -->
            <div class="p-3">
                <!-- Day Headers -->
                <div class="grid grid-cols-7 gap-1 mb-2">
                    <div
                        v-for="day in dayHeaders"
                        :key="day"
                        class="text-center text-xs font-medium text-gray-500 py-2"
                    >
                        {{ day }}
                    </div>
                </div>

                <!-- Calendar Days -->
                <div class="grid grid-cols-7 gap-1">
                    <button
                        v-for="day in calendarDays"
                        :key="day.key"
                        type="button"
                        @click="selectDate(day)"
                        :disabled="day.disabled"
                        class="w-8 h-8 text-sm rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500"
                        :class="getDayClass(day)"
                    >
                        {{ day.day }}
                    </button>
                </div>
            </div>

            <!-- Calendar Footer -->
            <div class="flex items-center justify-between p-3 bg-gray-50 border-t border-gray-200">
                <button
                    type="button"
                    @click="selectToday"
                    class="text-sm text-green-600 hover:text-green-800 font-medium transition-colors duration-200"
                >
                    <i class="fas fa-calendar-day mr-1"></i>
                    Hoy
                </button>
                
                <div class="flex space-x-2">
                    <button
                        type="button"
                        @click="clearDate"
                        class="px-3 py-1 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded hover:bg-gray-100 transition-colors duration-200"
                    >
                        Limpiar
                    </button>
                    <button
                        type="button"
                        @click="closeCalendar"
                        class="px-3 py-1 text-sm text-white bg-green-600 hover:bg-green-700 rounded transition-colors duration-200"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>

        <!-- Overlay to close calendar when clicking outside -->
        <div
            v-if="showCalendar"
            class="fixed inset-0 z-40"
            @click="closeCalendar"
        ></div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'

// Props
const props = defineProps({
    modelValue: {
        type: [String, Date],
        default: null
    },
    placeholder: {
        type: String,
        default: 'Seleccione una fecha'
    },
    disabled: {
        type: Boolean,
        default: false
    },
    inputId: {
        type: String,
        default: 'datepicker'
    },
    inputClass: {
        type: String,
        default: ''
    },
    dropdownClass: {
        type: String,
        default: 'w-80'
    },
    errorMessage: {
        type: String,
        default: ''
    },
    hasError: {
        type: Boolean,
        default: false
    },
    dateFormat: {
        type: String,
        default: 'DD/MM/YYYY'
    },
    minDate: {
        type: [String, Date],
        default: null
    },
    maxDate: {
        type: [String, Date],
        default: null
    },
    defaultToToday: {
        type: Boolean,
        default: true
    }
})

// Emits
const emit = defineEmits(['update:modelValue', 'change'])

// Reactive data
const showCalendar = ref(false)
const currentMonth = ref(new Date().getMonth())
const currentYear = ref(new Date().getFullYear())
const selectedDate = ref(null)

// Constants
const months = [
    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
]

const dayHeaders = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb']

// Computed properties
const displayValue = computed(() => {
    if (!selectedDate.value) return ''
    return formatDate(selectedDate.value)
})

const availableYears = computed(() => {
    const currentYear = new Date().getFullYear()
    const years = []
    for (let year = currentYear - 10; year <= currentYear + 10; year++) {
        years.push(year)
    }
    return years
})

const calendarDays = computed(() => {
    const firstDay = new Date(currentYear.value, currentMonth.value, 1)
    const lastDay = new Date(currentYear.value, currentMonth.value + 1, 0)
    const firstDayOfWeek = firstDay.getDay()
    const daysInMonth = lastDay.getDate()
    
    const days = []
    
    // Previous month days
    const prevMonth = new Date(currentYear.value, currentMonth.value - 1, 0)
    const prevMonthDays = prevMonth.getDate()
    
    for (let i = firstDayOfWeek - 1; i >= 0; i--) {
        days.push({
            day: prevMonthDays - i,
            date: new Date(currentYear.value, currentMonth.value - 1, prevMonthDays - i),
            isCurrentMonth: false,
            isToday: false,
            isSelected: false,
            disabled: true,
            key: `prev-${prevMonthDays - i}`
        })
    }
    
    // Current month days
    const today = new Date()
    today.setHours(0, 0, 0, 0)
    
    for (let day = 1; day <= daysInMonth; day++) {
        const date = new Date(currentYear.value, currentMonth.value, day)
        const isToday = date.getTime() === today.getTime()
        const isSelected = selectedDate.value && date.getTime() === selectedDate.value.getTime()
        const isDisabled = isDateDisabled(date)
        
        days.push({
            day,
            date,
            isCurrentMonth: true,
            isToday,
            isSelected,
            disabled: isDisabled,
            key: `current-${day}`
        })
    }
    
    // Next month days to fill the grid
    const remainingDays = 42 - days.length // 6 weeks * 7 days
    for (let day = 1; day <= remainingDays; day++) {
        days.push({
            day,
            date: new Date(currentYear.value, currentMonth.value + 1, day),
            isCurrentMonth: false,
            isToday: false,
            isSelected: false,
            disabled: true,
            key: `next-${day}`
        })
    }
    
    return days
})

// Methods
const formatDate = (date) => {
    if (!date) return ''
    
    const day = date.getDate().toString().padStart(2, '0')
    const month = (date.getMonth() + 1).toString().padStart(2, '0')
    const year = date.getFullYear()
    
    switch (props.dateFormat) {
        case 'DD/MM/YYYY':
            return `${day}/${month}/${year}`
        case 'MM/DD/YYYY':
            return `${month}/${day}/${year}`
        case 'YYYY-MM-DD':
            return `${year}-${month}-${day}`
        default:
            return `${day}/${month}/${year}`
    }
}

const parseDate = (dateString) => {
    if (!dateString) return null
    
    if (dateString instanceof Date) {
        return dateString
    }
    
    // Try to parse different formats
    const patterns = [
        /^(\d{1,2})\/(\d{1,2})\/(\d{4})$/,  // DD/MM/YYYY
        /^(\d{1,2})-(\d{1,2})-(\d{4})$/,   // DD-MM-YYYY
        /^(\d{4})-(\d{1,2})-(\d{1,2})$/    // YYYY-MM-DD
    ]
    
    for (const pattern of patterns) {
        const match = dateString.match(pattern)
        if (match) {
            let day, month, year
            if (pattern.source.startsWith('^\\(\\d{4}')) {
                // YYYY-MM-DD format
                year = parseInt(match[1])
                month = parseInt(match[2]) - 1
                day = parseInt(match[3])
            } else {
                // DD/MM/YYYY or DD-MM-YYYY format
                day = parseInt(match[1])
                month = parseInt(match[2]) - 1
                year = parseInt(match[3])
            }
            
            return new Date(year, month, day)
        }
    }
    
    // Fallback to Date constructor
    return new Date(dateString)
}

const isDateDisabled = (date) => {
    if (props.minDate) {
        const minDate = parseDate(props.minDate)
        if (minDate && date < minDate) return true
    }
    
    if (props.maxDate) {
        const maxDate = parseDate(props.maxDate)
        if (maxDate && date > maxDate) return true
    }
    
    return false
}

const getDayClass = (day) => {
    const classes = []
    
    if (day.disabled) {
        classes.push('text-gray-300 cursor-not-allowed')
    } else if (day.isSelected) {
        classes.push('bg-green-600 text-white')
    } else if (day.isToday) {
        classes.push('bg-green-100 text-green-800 font-semibold')
    } else if (day.isCurrentMonth) {
        classes.push('text-gray-900 hover:bg-gray-100')
    } else {
        classes.push('text-gray-400')
    }
    
    return classes.join(' ')
}

const toggleCalendar = () => {
    if (props.disabled) return
    showCalendar.value = !showCalendar.value
}

const closeCalendar = () => {
    showCalendar.value = false
}

const selectDate = (day) => {
    if (day.disabled) return
    
    selectedDate.value = day.date
    emit('update:modelValue', formatDate(day.date))
    emit('change', day.date)
    closeCalendar()
}

const selectToday = () => {
    const today = new Date()
    today.setHours(0, 0, 0, 0) // Reset time to avoid timezone issues
    
    if (isDateDisabled(today)) {
        return // Don't select today if it's disabled
    }
    
    selectedDate.value = today
    emit('update:modelValue', formatDate(today))
    emit('change', today)
    closeCalendar()
}

const clearDate = () => {
    selectedDate.value = null
    emit('update:modelValue', '')
    emit('change', null)
    closeCalendar()
}

const previousMonth = () => {
    if (currentMonth.value === 0) {
        currentMonth.value = 11
        currentYear.value--
    } else {
        currentMonth.value--
    }
}

const nextMonth = () => {
    if (currentMonth.value === 11) {
        currentMonth.value = 0
        currentYear.value++
    } else {
        currentMonth.value++
    }
}

const updateCalendar = () => {
    // Calendar will reactively update based on currentMonth and currentYear
}

const handleKeydown = (e) => {
    if (e.key === 'Escape' && showCalendar.value) {
        closeCalendar()
    }
}

const initializeDatePicker = () => {
    // If there's a modelValue, parse it
    if (props.modelValue) {
        selectedDate.value = parseDate(props.modelValue)
        if (selectedDate.value) {
            currentMonth.value = selectedDate.value.getMonth()
            currentYear.value = selectedDate.value.getFullYear()
        }
    } else if (props.defaultToToday) {
        // Only set default to today if no modelValue is provided
        const today = new Date()
        today.setHours(0, 0, 0, 0)
        
        if (!isDateDisabled(today)) {
            selectedDate.value = today
            currentMonth.value = today.getMonth()
            currentYear.value = today.getFullYear()
            emit('update:modelValue', formatDate(today))
            emit('change', today)
        }
    }
}

// Watchers
watch(() => props.modelValue, (newValue) => {
    if (newValue) {
        const parsedDate = parseDate(newValue)
        if (parsedDate && parsedDate.getFullYear() > 1900) { // Validate year
            selectedDate.value = parsedDate
            currentMonth.value = parsedDate.getMonth()
            currentYear.value = parsedDate.getFullYear()
        }
    } else {
        selectedDate.value = null
    }
}, { immediate: true })

// Lifecycle
onMounted(() => {
    document.addEventListener('keydown', handleKeydown)
    
    // Initialize the date picker only if no modelValue is provided
    if (!props.modelValue && props.defaultToToday) {
        initializeDatePicker()
    }
})

onUnmounted(() => {
    document.removeEventListener('keydown', handleKeydown)
})
</script>

<style scoped>
/* Calendar transitions */
.calendar-enter-active, .calendar-leave-active {
    transition: all 0.2s ease;
}

.calendar-enter-from, .calendar-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

/* Custom scrollbar for year/month selects */
select::-webkit-scrollbar {
    width: 4px;
}

select::-webkit-scrollbar-track {
    background: #f1f1f1;
}

select::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 2px;
}

select::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
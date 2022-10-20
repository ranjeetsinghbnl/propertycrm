<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    options: Array,
    checked: String
})
const emit = defineEmits(['update:checked'])

const proxyChecked = computed({
    get() {
        return props.checked
    },

    set(val) {
        emit('update:checked', val)
    },
})

const input = ref(null)

</script>

<template>
    <div class="radio-button-group pt-2">
        <label :for="option" v-for="option in options" :key="options.key">
            <input v-model="proxyChecked" type="radio" name="radio-input" :value="option.key" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
            :id="option"
            />
            {{ option.label }}
        </label>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface SelectOption {
    value: string | number;
    label: string;
    disabled?: boolean;
}

interface Props {
    modelValue?: string | number;
    options: SelectOption[];
    placeholder?: string;
    disabled?: boolean;
    class?: string;
}

const props = withDefaults(defineProps<Props>(), {
    placeholder: 'Select an option...',
    disabled: false,
    class: '',
});

const emit = defineEmits<{
    'update:modelValue': [value: string | number];
}>();

const selectClasses = computed(() => {
    const baseClasses = [
        'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm',
        'ring-offset-background placeholder:text-muted-foreground',
        'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2',
        'disabled:cursor-not-allowed disabled:opacity-50'
    ];

    return [...baseClasses, props.class].join(' ');
});

function handleChange(event: Event) {
    const target = event.target as HTMLSelectElement;
    const value = target.value;

    // Convert to number if the original value was a number
    const numericValue = Number(value);
    const finalValue = !isNaN(numericValue) && value !== '' ? numericValue : value;

    emit('update:modelValue', finalValue);
}
</script>

<template>
    <select
        :value="modelValue"
        :disabled="disabled"
        :class="selectClasses"
        @change="handleChange"
    >
        <option v-if="placeholder" value="" disabled>
            {{ placeholder }}
        </option>
        <option
            v-for="option in options"
            :key="option.value"
            :value="option.value"
            :disabled="option.disabled"
        >
            {{ option.label }}
        </option>
    </select>
</template>

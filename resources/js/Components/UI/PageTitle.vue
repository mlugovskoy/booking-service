<script setup>
defineProps({
    as: {
        type: String,
        default: 'h1',
        validator: (val) => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'].includes(val)
    },
    text: {
        type: String,
        required: true
    }
});

const titlesClass = {
    h1: 'text-3xl font-bold',
    h2: 'text-2xl font-semibold',
    h3: 'text-xl font-medium',
    h4: 'text-lg font-medium',
    h5: 'text-base font-medium',
    h6: 'text-sm font-medium'
};
</script>

<template>
    <component :is="as" :class="[titlesClass[as], 'mb-4']">{{ text }}</component>
</template>

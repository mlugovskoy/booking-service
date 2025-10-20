<script setup>
defineProps({
    customClass: {
        type: String,
        default: ''
    },
    withBg: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <button v-if="withBg"
            :class="[customClass, 'p-2 cursor-pointer bg-red-400 text-white rounded-md transition-colors hover:bg-red-500 disabled:bg-gray-300 disabled:cursor-default']">
        <slot/>
    </button>
    <button v-else
            :class="[customClass, 'cursor-pointer hover:underline disabled:text-gray-200 disabled:hover:no-underline disabled:cursor-default']">
        <slot/>
    </button>
</template>

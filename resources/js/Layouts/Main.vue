<script setup>

</script>

<template>
    <div class="min-h-screen h-full flex flex-col items-center justify-items-center bg-gray-50 mx-auto">
        <div class="max-w-2xl w-full py-20">
            <div class="border-red-200 border rounded-md p-4">
                <slot />
            </div>
        </div>
    </div>
</template>

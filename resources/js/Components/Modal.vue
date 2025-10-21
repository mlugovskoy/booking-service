<script setup>
import {computed, onMounted, onUnmounted, watch} from 'vue';
import Button from "./UI/Button.vue";
import PageTitle from "./UI/PageTitle.vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    service: {
        type: String,
        default: ''
    },
    date: {
        type: String,
        default: ''
    }
})

const emit = defineEmits(['close']);

watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = null;
        }
    },
);

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};
</script>

<template>
    <div v-show="show" class="absolute top-0 right-0 w-full h-full bg-black opacity-50">
    </div>
    <div v-show="show"
         class="p-8 w-sm absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 transform overflow-hidden rounded-md bg-white shadow-md transition-all">
        <PageTitle as="h3" text="Успешно!"/>
        <p class="mb-4">Услуга: <b class="font-medium">{{ service }}</b>, <br> Время: <b class="font-medium">{{ date }}</b></p>
        <Button @click="close" :with-bg="true">Закрыть</Button>
    </div>
</template>

<script setup>
import {ref, reactive, onMounted, watch} from 'vue';
import Button from './UI/Button.vue';
import PageTitle from './UI/PageTitle.vue';
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    items: {
        type: Array,
        default: []
    },
    showModal: {
        type: Function
    }
});

const emit = defineEmits(['booking'])

let slots = ref([]);
const currentWeek = ref([]);
const selectedService = ref(null);
const selectedCurrentDate = ref(null);
const selectedSlot = ref(null);
const selectedArray = reactive({
    'default': true,
    'service': false,
    'weekday': false,
    'slot': false
});
const form = useForm({
    client_name: '',
    client_phone: '',
    service_id: '',
    booking_start_time: ''
})

const prevStep = () => {
    if (selectedArray.slot) {
        selectedArray.service = false;
        selectedArray.weekday = true;
        selectedArray.slot = false;
        selectedCurrentDate.value = null;
    } else if (selectedArray.weekday) {
        selectedArray.service = true;
        selectedArray.weekday = false;
        slots.value = [];
    } else if (selectedArray.service) {
        selectedArray.default = true;
        selectedArray.service = false;
        selectedService.value = null;
    }
};

const selectService = (service) => {
    selectedService.value = service;
    selectedArray.default = false;
    selectedArray.service = true;
};

const selectWeekDay = (date) => {
    selectedCurrentDate.value = date;
    selectedArray.default = false;
    selectedArray.service = false;
    selectedArray.weekday = true;
    fetchSlots()
}

const initCurrentWeek = () => {
    const todayStart = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
    const monday = new Date();
    monday.setDate(todayStart.getDate() - todayStart.getDay() + 1)

    for (let i = 0; i < 7; i++) {
        const date = new Date(monday)
        date.setDate(monday.getDate() + i);

        const dateStart = new Date(date.getFullYear(), date.getMonth(), date.getDate());

        currentWeek.value.push({
            date: date.toISOString().split('T')[0],
            label: date.toLocaleDateString('ru-RU', {weekday: 'short', day: 'numeric'}),
            isSunday: date.getDay() === 0,
            active: dateStart >= todayStart
        })
    }
}

const fetchSlots = () => {
    axios.post('/booking/slots', {
        service_id: selectedService.value.id,
        booking_start_time: selectedCurrentDate.value.date
    }).then(response => {
        slots.value = response.data.slots || [];
    }).catch(error => {
        console.error('Ошибка загрузки слотов:', error);
        slots.value = [];
    });
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('ru-RU', {
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric'
    })
}

const selectSlot = (slot) => {
    selectedSlot.value = slot;
    selectedArray.default = false;
    selectedArray.service = false;
    selectedArray.weekday = false;
    selectedArray.slot = true;
}

const formPost = () => {
    form.post('/booking', {
        preserveScroll: true,
        onSuccess: () => {
            props.showModal({
                service: selectedService.value.name,
                date: form.booking_start_time
            });

            form.reset();
            selectedArray.default = true;
            selectedArray.service = false;
            selectedArray.weekday = false;
            selectedArray.slot = false;
        }
    })
}

watch([selectedService, selectedSlot], () => {
    if (selectedService.value) {
        form.service_id = selectedService.value.id;
    }
    if (selectedSlot.value) {
        form.booking_start_time = selectedSlot.value;
    }
})

onMounted(() => {
    initCurrentWeek()
})
</script>

<template>
    <Button v-if="!selectedArray.default" @click="prevStep()" custom-class="text-sm text-red-400 my-2">Назад</Button>

    <div v-if="selectedArray.default" class="flex flex-col gap-2">
        <div v-for="item in items" :key="item.id" class="flex items-center justify-between">
            <p>{{ item.name }} ({{ item.duration }} минут)</p>
            <Button @click="selectService(item)" :with-bg="true">Бронировать</Button>
        </div>
    </div>

    <div v-if="selectedArray.service">
        <PageTitle as="h4" text="Выберите день недели"/>
        <div class="grid grid-cols-2 gap-3">
            <Button v-for="day in currentWeek"
                    @click="selectWeekDay(day)"
                    :key="day.date"
                    :with-bg="true"
                    :disabled="day.isSunday || !day.active">
                {{ day.label }}
            </Button>
        </div>
    </div>

    <div v-if="selectedArray.weekday">
        <PageTitle as="h4" text="Выберите слот"/>
        <div v-if="slots.length === 0">Нет доступных слотов</div>
        <div v-else class="grid grid-cols-4 gap-2">
            <Button v-for="slot in slots"
                    @click="selectSlot(slot)"
                    :key="slot.id"
                    :with-bg="true">
                {{ formatDate(slot) }}
            </Button>
        </div>
    </div>

    <div v-if="selectedArray.slot">
        <PageTitle as="h4" text="Заполните форму"/>
        <form @submit.prevent="formPost" class="flex flex-col gap-4">
            <input type="text"
                   v-model="form.client_name"
                   placeholder="Имя"
                   class="border border-red-200 rounded-md p-2">
            <input type="text"
                   v-model="form.client_phone"
                   placeholder="Телефон"
                   class="border border-red-200 rounded-md p-2">
            <input type="hidden" v-model="form.service_id">
            <input type="hidden" v-model="form.booking_start_time">
            <Button type="submit"
                    :with-bg="true"
                    :disabled="form.processing">
                Бронировать
            </Button>
        </form>
    </div>
</template>

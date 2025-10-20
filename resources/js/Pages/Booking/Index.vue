<script setup>
import {ref} from "vue";
import {usePage} from '@inertiajs/vue3';
import Main from '@/Layouts/Main.vue';
import Modal from '../../Components/Modal.vue';
import PageTitle from '../../Components/UI/PageTitle.vue';
import ServiceList from '../../Components/ServiceList.vue';

const page = usePage();
const createdModal = ref(false);
const modalData = ref(null);

const closeModal = () => {
    createdModal.value = false;
};
const showModal = (data) => {
    modalData.value = data;
    createdModal.value = true;
};

const booking = (data) => {
    axios.get('/booking', {
        service_id: selectedService.value.id,
        booking_start_time: '123',
        client_name: selectedService.value.id,
        client_phone: selectedCurrentDate.value.date
    }).then(() => {
        showModal(data)
    }).catch(error => {
        console.error('Ошибка бронирования: ', error);
    });
}

</script>

<template>
    <Main>
        <PageTitle as="h1" text="Услуги"/>

        <ServiceList @booking="booking"
                     :items="page.props.services"/>

        <Modal v-if="modalData"
               @close="closeModal"
               :show="createdModal"
               :service="modalData.service"
               :date="modalData.date"/>
    </Main>
</template>

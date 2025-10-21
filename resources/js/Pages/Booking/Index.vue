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

</script>

<template>
    <Main>
        <PageTitle as="h1" text="Услуги"/>

        <ServiceList :showModal="showModal"
                     :items="page.props.services"/>

        <Modal v-if="modalData"
               @close="closeModal"
               :show="createdModal"
               :service="modalData.service"
               :date="modalData.date"/>
    </Main>
</template>

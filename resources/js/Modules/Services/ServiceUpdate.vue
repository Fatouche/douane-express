<template>
    <div class="container">
        <div class="row">
            <div class="col-12 mt-5"></div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title" v-text="title"></h5>
                    <form @submit.prevent="updateService">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" v-model="service.name" class="form-control" id="name" >
                        </div>
                        <div class="mb-3">
                            <label for="reference" class="form-label">Référence</label>
                            <input type="text" v-model="service.reference" class="form-control" id="reference">
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Prix</label>
                            <input type="number" v-model="service.price" class="form-control" id="price">
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" @change="updateServiceImage">
                        </div>
                        <div class="mb-3" v-if="service.image">
                            <img style="width: 100%; height: 200px; object-fit: cover;" :src="`${service.image}`">
                        </div>
                        <div class="mb-3 col-2">
                            <label for="associate" class="form-label">Associer à un autre service</label>
                            <select class="form-select form-control" aria-label="Default select example" v-model="associate" id="associate" @change="associateChanged">
                                <option value="false">Non</option>
                                <option value="true">Oui</option>
                            </select>
                        </div>
                        <div class="row" v-if="associate">
                            <div class="mb-3 col-2">
                                <label for="minQuantity" class="form-label">Quantité minimun</label>
                                <input type="number" v-model="service.minQuantity" class="form-control" id="minQuantity">
                            </div>
                            <div class="mb-3 col-2">
                                <label for="maxQuantity" class="form-label">Quantité maximum</label>
                                <input type="number" v-model="service.maxQuantity" class="form-control" id="maxQuantity">
                            </div>
                            <div class="mb-3 col-8">
                                <label for="services" class="form-label">Services</label>
                                <select class="form-select form-control" id="services" v-model="service.serviceId">
                                    <option selected>Selectionné un service</option>
                                    <option v-for="service in services" :key="service.id" :value="service.id" :text="service.name"></option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Modifier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { onMounted, ref, inject } from 'vue';
    import { useRoute } from 'vue-router';
    import useService from '../../composables/services';
    const toast = inject('toast');

    const route = useRoute();
    const { getOne, update, uploadImage } = useService();

    const services = ref([]);
    const service = ref({
        name: '',
        reference: '',
        price: 0,
        minQuantity: 1,
        maxQuantity: null,
        serviceId: null,
    });

    const associate = ref(false);

    const refresh = async () => {
        await getOne(route.params.id).then((response) => {
            service.value = response.service;
            services.value = response.services;
            associate.value = response.service.serviceId !== null;
        });
    };

    const updateServiceImage = async (event) => {
        const file = event.target.files[0];
        const formData = new FormData();
        formData.append('image', file);
        await uploadImage(route.params.id, formData).then(async () => {
            toast.success(`Service mis à jour`);
            await refresh();
        }).catch(() => toast.error(`Erreur lors de la mise à jour du service`));
    }

    onMounted(async () => {
        await refresh();
    });

    const updateService = async () => {
        await update(route.params.id, service.value).then(() => toast.success(`Service mis à jour`)).catch(() => toast.error(`Erreur lors de la mise à jour du service`));
    };

    const associateChanged = () => {
        if (associate.value === 'true') {
            return associate.value = true;
        }
        associate.value = false;
        service.value.minQuantity = 1;
        service.value.maxQuantity = null;
        service.value.serviceId = null;
    };
</script>

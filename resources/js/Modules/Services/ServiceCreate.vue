<template>
    <div class="container">
        <div class="row">
            <div class="col-12 mt-5"></div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Créer un nouveau service</h5>
                        <form @submit.prevent="create">
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
                            <div class="mb-3 col-2">
                                <label for="associate" class="form-label">Associer à un autre service</label>
                                <select class="form-select form-control" aria-label="Default select example" v-model="associate" id="associate">
                                    <option value="false">Non</option>
                                    <option value="true">Oui</option>
                                </select>
                            </div>
                            <div class="row" v-if="associate === 'true'">
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
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </form>
                    </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import useService from '../../composables/services';
    import { useRouter } from 'vue-router';
    import { inject, ref, onMounted } from 'vue';

    const toast = inject('toast');

    const router = useRouter();
 
    const { add, getAll } = useService();

    const services = ref([]);
    const service = {
        name: '',
        reference: '',
        price: 0,
        minQuantity: 1,
        maxQuantity: null,
        serviceId: null,
    }

    const associate = ref(false);

    const create = async () => {
        await add(service).then(() => {router.push('/services'); toast.success(`Service créé !`);});
    };

    onMounted(async () => {
        services.value = await getAll();
    });

</script>

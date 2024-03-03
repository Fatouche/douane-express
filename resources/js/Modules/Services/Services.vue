<template>
    <div class="d-flex flex-column p-3">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Liste des services</h1>
        <router-link class="btn btn-primary" to="/services/new">Ajouter un service</router-link>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Reference</th>
                <th scope="col">Nom</th>
                <th scope="col">Prix</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="service in services" :key="service.id">
                <td>{{ service.reference }}</td>
                <td>{{ service.name }}</td>
                <td>{{ service.price }} €</td>
                <td>
                    <div class="d-flex gap-2 justify-content-end">
                        <router-link class="btn btn-primary" :to="`/services/${service.id}`"><i class="fa fa-pencil" style="font-size:24px"></i></router-link>
                        <button class="btn btn-danger" @click="deleteService(service.id)"><i class="fa fa-trash-o" style="font-size:24px"></i></button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</template>


<script setup>
    import { onMounted, ref, inject } from 'vue';
    import useService from '../../composables/services';
    const toast = inject('toast');

    const { getAll, deleteOne } = useService();
    const services = ref([]);

    const refresh = async () => {
        services.value = await getAll();
    };

    onMounted(async () => {
        await refresh();
    });

    const deleteService = async (serviceId) => {
        try {
            await deleteOne(serviceId);
            await refresh();
            toast.show(`Service supprimé !`);
        } catch (error) {
            console.error('Une erreur s\'est produite lors de la suppression du service:', error);
        }
    };

</script>

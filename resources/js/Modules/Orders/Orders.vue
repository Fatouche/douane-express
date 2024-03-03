<template>
    <div class="d-flex flex-column p-3">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Liste des bons de commande</h1>
        <router-link class="btn btn-primary" to="/orders/new">Ajouter un bon de commande</router-link>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Reference</th>
                <th scope="col">Date</th>
                <th scope="col">status</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="order in orders" :key="order.id">
                <td>{{ order.order_number }}</td>
                <td>{{ order.date ? new Date(order.date).toLocaleDateString("fr-FR") : "Aucune date renseigné"}}</td>
                <td>{{ order.status === 'open' ? 'Ouvert' : "Fermé" }}</td>
                <td>
                    <div class="d-flex gap-2 justify-content-end">
                        <router-link class="btn btn-primary" :to="`/orders/${order.id}`"><i class="fa fa-pencil" style="font-size:24px"></i></router-link>	
                        <button class="btn btn-danger" @click="deleteService(order.id)"><i class="fa fa-trash-o" style="font-size:24px"></i></button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</template>


<script setup>
    import { onMounted, ref, inject } from 'vue';
    import useOrder from '../../composables/orders';
    const toast = inject('toast');

    const { getAll, deleteOne } = useOrder();
    const orders = ref([]);

    const refresh = async () => {
        orders.value = await getAll();
    };

    onMounted(async () => {
        await refresh();
    });

    const deleteService = async (orderId) => {
        try {
            await deleteOne(orderId);
            await refresh();
            toast.success(`Bon de commande supprimé !`);
        } catch (error) {
            console.error('Une erreur s\'est produite lors de la suppression du bon de commande:', error);
        }
    };

</script>

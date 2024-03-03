<template>
    <div class="container">
        <div class="row">
            <div class="col-12 mt-5"></div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Créer un nouveau bon de commande</h5>
                        <form @submit.prevent="create">
                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <VueDatePicker id="date" v-model="order.date" :enable-time-picker="false" locale="fr" :format="formatDate"></VueDatePicker>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Statut</label>
                                <select class="form-select form-control" aria-label="Default select example" v-model="order.status" id="status">
                                    <option value="open" :selected="order.status === open">Ouvert</option>
                                    <option value="close" :selected="order.status === close">fermé</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" :disabled="order.date === null">Ajouter</button>
                        </form>
                    </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import useOrder from '../../composables/orders';
    import { useRouter } from 'vue-router';
    import { inject, ref } from 'vue';
    import { formatDate } from '../../Utils/date'

    const toast = inject('toast');
    const router = useRouter();
 
    const { add } = useOrder();

    const order = ref({
        order_number: '',
        date: null,
        status: 'open',
        services: [],
    });

    const create = async () => {
        await add(order.value).then((res) =>{
            toast.success(`Bon de commande créé !`);
            router.push(`/orders/${res.data.order.id}`)
        });
    };
</script>
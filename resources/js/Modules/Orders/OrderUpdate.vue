<template>
  <div class="container">
    <div class="row">
      <div class="col-12 mt-5"></div>
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <h5 class="card-title" v-text="title"></h5>
            <div  class="d-flex gap-2 align-items-center">prix total:<h5 v-text="`${order.total_price} €`"></h5></div>
          </div>
          <form>
            <div class="mb-3">
              <label for="date" class="form-label">Date</label>
              <VueDatePicker id="date" v-model="order.date" :enable-time-picker="false" locale="fr" :format="formatDate" :disabled="order.status === 'close'"></VueDatePicker>
            </div>
            <div class="mb-3">
              <label for="status" class="form-label">Statut</label>
              <select class="form-select form-control" :disabled="order.status === 'close'" v-model="order.status" id="status" @change="saveOrder">
                <option value="open">Ouvert</option>
                <option value="close">Fermé</option>
              </select>
            </div>
            <div class="mb-3" :hidden="order.status === 'close'">
              <label for="services"  class="form-label">Services</label>
              <div class="dropdown">
                <input class="form-control dropdown-toggle" type="text" data-bs-toggle="dropdown" id="services" v-model="searchValue" placeholder="Ajouter un service"/>
                <ul class="dropdown-menu form-control">
                  <li v-for="service in filteredServices" :key="service.id" class="dropdown-item" @click="addService(service.id)" v-text="service.name"></li>
                </ul>
              </div>
            </div> 
              <div class="border-top my-3" :hidden="order.status === 'open'"></div>
              <h6>Liste des produits</h6>
              <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Nom</th>
                        <th scope="col">Quantité</th>
                        <th scope="col">Prix unitaire</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="linkedService in order.services" :key="linkedService.id">
                        <td>{{ linkedService.name }}</td>
                        <td>
                          <input :disabled="order.status === 'close'" class="border border-0 text-center" type="number" v-model="linkedService.pivot.total_quantity" @blur="updateService(linkedService.id, linkedService.pivot.total_quantity)">
                        </td>
                        <td>{{ linkedService.price }}</td>
                        <td v-if="order.status === 'open'">
                            <div class="d-flex gap-2 justify-content-end">
                                <button class="btn btn-danger" @click="deleteServiceToOrder(order.id, linkedService.id)"><i class="fa fa-trash-o" style="font-size:24px"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, watch, inject } from 'vue';
import { useRoute } from 'vue-router';
import useOrder from '../../composables/orders';
import useService from '../../composables/services';

const toast = inject('toast');
const route = useRoute();
const orderId = route.params.id;
const { getOne, update, addServiceToOrder, updateServiceToOrder, deleteServiceToOrder } = useOrder();
const serviceAPI = useService();

const order = ref({
  order_number: '',
  date: '',
  status: 'open',
  total_price: 0
});

const availableServices = ref([]);
const filteredServices = ref([]);
const searchValue = ref(null);
const title = ref('');

const refresh = async () => {
  const fetchedOrder = await getOne(orderId);
  const fetchedService = await serviceAPI.getAll();
  order.value = {...fetchedOrder.order, total_price: fetchedOrder.total_price};
  availableServices.value = fetchedService;
  filteredServices.value = fetchedService;
  title.value = `Bon de commande: ${fetchedOrder.order.order_number}`;
};

onMounted(async () => {
  await refresh();
});

watch(searchValue, (newValue) => {
  filteredServices.value = availableServices.value.filter(service =>
    service.name.toLowerCase().includes(newValue.toLowerCase())
  );
});

const saveOrder = async () => {
  try {
    await update(orderId, {
      date: order.value.date,
      status: order.value.status
    });
  } catch (error) {
    toast.error(`Une erreur est survenue !`)
    console.error('Failed to update order:', error);
  }
};

const addService = async (serviceId) => {
  try {
    await addServiceToOrder(orderId, {serviceId});
    await refresh();
  } catch (error) {
    toast.error(`Une erreur est survenue !`)
    console.error('Failed to add service to order:', error);
  }
}

const updateService = async (serviceId, quantity) => {
  try {
    if (quantity !== 0) {
      await updateServiceToOrder(orderId, serviceId, {quantity}).catch(() => toast.error(`Une erreur est survenue !`));
    } else {
      await deleteServiceToOrder(orderId, serviceId).catch(() => toast.error(`Une erreur est survenue !`))
    }
    await refresh();
  } catch (error) {
    console.error('Failed to add service to order:', error);
  }
}
</script>

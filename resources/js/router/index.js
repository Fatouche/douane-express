import { createRouter, createWebHistory } from "vue-router";
import Services from "../Modules/Services/Services.vue";
import ServiceCreate from "../Modules/Services/ServiceCreate.vue";
import ServiceUpdate from "../Modules/Services/ServiceUpdate.vue";
import Orders from "../Modules/Orders/Orders.vue";
import OrderCreate from "../Modules/Orders/OrderCreate.vue";
import OrderUpdate from "../Modules/Orders/OrderUpdate.vue";

const routes = [
    {
        path: "/",
        name: "home",
        redirect: "/services"
    },
    {
        path: "/services",
        name: "services",
        component: Services
    },
    {
        path: "/services/new",
        name: "services.new",
        component: ServiceCreate
    },
    {
        path: "/services/:id",
        name: "services.update",
        component: ServiceUpdate
    },
    {
        path: "/orders",
        name: "orders",
        component: Orders
    },
    {
        path: "/orders/new",
        name: "orders.new",
        component: OrderCreate
    },
    {
        path: "/orders/:id",
        name: "orders.update",
        component: OrderUpdate
    },
];


const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;

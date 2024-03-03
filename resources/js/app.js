import './bootstrap';
import { createApp } from "vue";
import App from './App.vue';
import router from './router';
import Toaster from '@meforma/vue-toaster';
import '@vuepic/vue-datepicker/dist/main.css'; 
import VueDatePicker from '@vuepic/vue-datepicker'; 

const app = createApp(App);

app.use(router);
app.use(Toaster, {position: 'top-right'}).provide('toast', app.config.globalProperties.$toast);
app.component('VueDatePicker', VueDatePicker) 
app.mount('#app');

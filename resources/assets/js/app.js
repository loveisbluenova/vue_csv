import Vue from 'vue'
import App from './App.vue'

const app = new Vue({
    el: '#root',
    template: `<app url="/payments" :map-fields="['invoice_id','landlord_id','tenant_id','amount', 'status', 'payment_method', 'payment_status', 'merchant_reference','psp_reference', 'payment_auth_result']"></app>`,
    components: { App },
})

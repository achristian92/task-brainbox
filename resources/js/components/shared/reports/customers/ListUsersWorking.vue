<template>
    <div class="modal fade" id="ListUserWorkingModal" tabindex="-1" role="dialog" aria-labelledby="edit-event-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <loading :active.sync="isLoading" :is-full-page="false"></loading>
                <div class="modal-body">
                    <div class="row">
                        <div class="col text-center">
                            <h4 class="">Lista usuarios que trabajaron para el cliente</h4>
                        </div>
                    </div>
                    <form class="form" @submit.prevent="handleExport">
                        <div class="form-group">
                            <label for="customer_day" class="form-control-label">Cliente</label>
                            <select class="form-control form-control-sm"
                                    id="customer_day"
                                    v-model="customer_id"
                                    required>
                                <option value="" :disable="true">Seleccionar...</option>
                                <option v-for="customer in customers" :value="customer.id">{{customer.name}}</option>
                            </select>
                            <div v-if="errors && errors.customer_id" class="h6 text-danger">{{ errors.customer_id[0] }}</div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="month" class="form-control-label">Mes</label>
                                    <input type="month"
                                           class="form-control form-control-sm"
                                           id="month"
                                           v-model="yearAndMonth">
                                    <div v-if="errors && errors.yearAndMonth" class="h6 text-danger">{{ errors.yearAndMonth[0] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-sm">Exportar</button>
                            </div>
                            <div class="col text-right">
                                <button class="btn btn-sm btn-outline-default ml-auto" data-dismiss="modal">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import axios from 'axios'
import moment from 'moment'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    components: {
        Loading
    },
    data() {
        return {
            isLoading    : false,
            customer_id  : '',
            yearAndMonth : moment().startOf('month').format('YYYY-MM'),
            customers    : [],
            errors       : []
        }
    },
    props: ['c_customers'],
    created() {
        if (this.c_customers) this.customers = this.c_customers;
    },
    methods: {
        handleExport: function () {
            this.isLoading = true

            axios.get(`${this.appUrl}api/reports/customers/list-users-working`,{
                params: {
                    'customer_id' : this.customer_id,
                    'yearAndMonth' : this.yearAndMonth,
                }
            }).then(res => {
                this.isLoading = false
                if (res.status !== 201) {
                    Vue.$toast.warning("Inténtelo de nuevo");
                    return;
                }

                this.reset()
                Vue.$toast.success(res.data.msg);
                setTimeout(function(){window.open(res.data.url)},2000);
                setTimeout(function(){$('#ListUserWorkingModal').modal('hide');},4000);
            }).catch(error => {
                this.isLoading = false
                if (error.response.status === 422){
                    Vue.$toast.error("Información inválida");
                    this.errors = error.response.data.errors;
                }
                if (error.response.status === 401) {
                    Vue.$toast.error(error.response.data.msg);
                }
            });
        },
        reset: function ()
        {
            this.customer_id  = ''
            this.yearAndMonth  = moment().startOf('month').format('YYYY-MM')
            this.errors      = []
        }
    }
}
</script>

<style scoped>

</style>

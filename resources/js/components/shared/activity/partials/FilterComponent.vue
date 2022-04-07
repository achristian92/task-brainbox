<template>
    <form @submit.prevent="formFilter">
        <loading :active.sync="isLoading" :is-full-page="false"></loading>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label class="form-control-label text-muted"
                           for="filterCustomers">
                        Clientes
                    </label>
                    <Select2 v-model="customerSelected"
                             :options="customers"
                             placeholder="Todos..."/>
<!--                    <select class="form-control form-control-sm"-->
<!--                            id="filterCustomers"-->
<!--                            v-model="customerSelected">-->
<!--                        <option value="">Todos...</option>-->
<!--                        <option v-for="customer in customers" :value="customer.id">{{customer.name}}</option>-->
<!--                    </select>-->
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-control-label text-muted"
                           for="filterActivityStatus">
                        Estados
                    </label>
                    <select class="form-control form-control-sm"
                            id="filterActivityStatus"
                            v-model="statusSelected">
                        <option value="">Todos...</option>
                        <option v-for="(status,key) in statuses" :value="key">{{status}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2 text-right">
                <button type="submit"
                        class="btn btn-sm btn-outline-default btn-block"
                        style="margin-top: 30px">
                    Filtrar
                </button>
            </div>
        </div>
    </form>
</template>

<script>
import EventBus from "../../../../event-bus";
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import moment from 'moment'
import Select2 from 'v-select2-component';

export default {
    components: {
        Loading,
        Select2
    },
    data() {
        return {
            isLoading        : false,
            customerSelected : '',
            statusSelected   : '',
            currentDate      : moment().format('YYYY-MM-DD'),
        }
    },
    watch: {
        customerSelected: function () {
            this.statusSelected = ''
            EventBus.$emit('selectedAnotherFilter', {});
        }
    },
    props: {
        customers : '',
        statuses  : '',
    },
    methods: {
        formFilter() {
            const filter = {
                'customer_id'  : this.customerSelected,
                'status_id'    : this.statusSelected,
                'current_date' : this.currentDate
            }
            EventBus.$emit('sendFilterOwn', filter);
        },
    }
}
</script>

<style scoped>

</style>

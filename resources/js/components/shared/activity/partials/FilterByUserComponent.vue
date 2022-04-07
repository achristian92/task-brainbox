<template>
    <form @submit.prevent="formFilter">
        <loading :active.sync="isLoading" :is-full-page="false"></loading>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="selectCounter"
                           class="form-control-label text-muted">
                            Usuario
                    </label>
                    <Select2 v-model="userSelected"
                             :options="users"
                             @change="getListCustomers"
                             placeholder="Seleccionar" required />
<!--                    <select class="form-control form-control-sm"-->
<!--                            id="selectCounter"-->
<!--                            v-model="userSelected"-->
<!--                            @change="getListCustomers"-->
<!--                            required>-->
<!--                        <option disabled value="">Seleccione ...</option>-->
<!--                        <option v-for="user in users" :value="user.id">{{user.full_name}}</option>-->
<!--                    </select>-->
                </div>
            </div>
            <div class="col-md-4">
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
import axios from "axios";
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import moment from 'moment'
import EventBus from '../../../../event-bus'
import Select2 from 'v-select2-component';

export default {
    components: {
        Loading,
        Select2
    },
    data() {
        return {
            isLoading         : false,
            userSelected      : '',
            customerSelected  : '',
            statusSelected    : '',
            customers         : [],
            currentDate       : moment().format('YYYY-MM-DD'),
        }
    },
    created() {
        EventBus.$on('resetFilter', data => {
            this.customerSelected  = ''
            this.statusSelected = ''
        });
    },
    props: {
        users    : '',
        statuses : '',
        view     : ''
    },
    watch: {
        userSelected: function () {
            this.customerSelected  = ''
            this.statusSelected = ''
            EventBus.$emit('selectedAnotherUser', {});
        }
    },
    methods: {
        formFilter() {
            const filter = {
                'user_id'      : this.userSelected,
                'customer_id'  : this.customerSelected,
                'status_id'    : this.statusSelected,
                'current_date' : this.currentDate
            }
            EventBus.$emit('sendFilter', filter);
        },
        getListCustomers() {
            this.isLoading = true
            let url = `${this.appUrl}api/counter/${this.userSelected}/list-customers`;
            axios.get(url).then(res =>{
                this.isLoading = false
                this.customers = res.data.customers.map(function (i) {
                    return {
                        'id' : i.id,
                        'text': i.name
                    }
                })
            });
        },
    }
}
</script>

<style scoped>

</style>

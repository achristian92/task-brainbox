<template>
    <div class="card">
        <loading :active.sync="isLoading" :is-full-page="true"></loading>
        <div class="card-body">
            <br>
            <div class="row">
                <div class="col-md-10">
                    <multiselect v-model="customersSelected"
                                 :options="customers"
                                 :multiple="true"
                                 :close-on-select="false"
                                 :clear-on-select="false"
                                 :preserve-search="true"
                                 placeholder="Seleccionar..."
                                 label="name"
                                 :max="5"
                                 track-by="name">
                        <template slot="selection" slot-scope="{ values, search, isOpen }">
                                    <span class="multiselect__single"
                                          v-if="values.length &amp;&amp; !isOpen">
                                        {{ values.length }} seleccionado
                                    </span>
                        </template>
                    </multiselect>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-secondary mt-1" @click="handleClickFilterCustomers">Filtrar</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <bar-activities-fusionchart :activities="barActivities"></bar-activities-fusionchart>
                </div>
                <div class="col-md-6">
                    <bar-hours-fusionchart :hours="barHours"></bar-hours-fusionchart>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <history-fusionchart :line="line"></history-fusionchart>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios'
import Loading from 'vue-loading-overlay'
import Multiselect from 'vue-multiselect'
import moment from 'moment'
moment.locale('es')

export default {
    components: {
        Loading,
        Multiselect,
    },
    data() {
        return {
            isLoading     : false,
            progress      : null,
            resume        : null,
            barActivities : null,
            barHours      : null,
            line          : null,
            customerMoreHours : null,
            customerLessHours : null,
            userMoreHours : null,
            userLessHours : null,
            tagPercentage : null,
            lineTags      : null,
            date : {
                from  : null,
                to    : null,
                month : null,
                year  : null,
            },
            currentMonth  : parseInt(moment().format("MM")),
            yearAndMonth  : moment().startOf('month').format('YYYY-MM'),
            qtyShow       : 5,
            firstLoadPage : true,
            customers     : [],
            customersSelected: [],
        }
    },
    props: ['c_customers','p_yearandmonth'],
    created() {
        if (this.c_customers) this.customers = this.c_customers;
        if (this.p_yearandmonth) this.yearAndMonth = this.p_yearandmonth
        this.getInfo()

        console.log(this.p_yearandmonth)
    },
    watch: {
        qtyShow() {
            this.getInfo()
        }
    },
    methods: {
        getInfo: function ()
        {
            let customer_ids = this.customersSelected.map(function(a) {return a.id;});
            this.isLoading = true
            axios.get(`${this.appUrl}api/admin/dashboard`,{
                params: {
                    customer_ids : customer_ids,
                    yearAndMonth : this.yearAndMonth,
                    qtyShow      : this.qtyShow
                }
            })
            .then(res => {
                this.isLoading         = false
                this.userMoreHours     = res.data.userMoreHours
                this.userLessHours     = res.data.userLessHours
                this.progress          = res.data.progress
                this.resume            = res.data.resume
                this.barActivities     = res.data.activities
                this.barHours          = res.data.hours
                this.line              = res.data.period
                this.customerMoreHours = res.data.customerMoreHours
                this.customerLessHours = res.data.customerLessHours,
                this.tagPercentage     = res.data.tagPercentage
                this.lineTags          = res.data.lineTags
            })
            .catch(error => {
                this.isLoading = false
                if (error.response.status === 401) {
                    Vue.$toast.error(error.response.data.msg);
                }
            });
        },
        handleClickFilterCustomers ()
        {
            this.getInfo();
        },
        handleMonthChange (date) {
            this.date = date
            this.yearAndMonth =  moment(date.from).format('YYYY-MM')
            if (this.firstLoadPage) {
                this.firstLoadPage = false;
                return;
            }
            this.getInfo()
        },
    }
}
</script>

<style scoped>

</style>

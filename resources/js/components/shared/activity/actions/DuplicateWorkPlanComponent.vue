<template>
    <div class="modal fade" id="DuplicateWorkPlanModal" tabindex="-1" role="dialog" aria-labelledby="edit-event-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <loading :active.sync="isLoading" :is-full-page="false"></loading>
                <form class="form" @submit.prevent="duplicate">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <h2>Duplicar plan de trabajo</h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <validation-errors :errors="validationErrors" v-if="validationErrors"></validation-errors>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <h5>De:</h5>
                                <div class="btn-group dropright">
                                    <button type="button"
                                            class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                            data-toggle="dropdown"
                                            aria-haspopup="true"
                                            aria-expanded="false">
                                        <i class="fas fa-calendar-alt mr-1 ml-1"></i>
                                        {{ dateFrom.month }}
                                    </button>
                                    <div class="dropdown-menu">
                                        <month-picker
                                            :lang=selectedLang
                                            :default-month=currentMonth
                                            @change="handleFromMonthChange"
                                        ></month-picker>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 align-items-center">
                                <h5> - </h5>
                                =>
                            </div>
                            <div class="col-md-5">
                                <h5>Hacia:</h5>
                                <div class="btn-group dropleft">
                                    <button type="button"
                                            class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                            data-toggle="dropdown"
                                            aria-haspopup="true"
                                            aria-expanded="false">
                                        <i class="fas fa-calendar-alt mr-1 ml-1"></i>
                                        {{ dateTo.month }}
                                    </button>
                                    <div class="dropdown-menu">
                                        <month-picker
                                            :lang=selectedLang
                                            :default-month=nextMonth
                                            :default-year=nextYear
                                            @change="handleToMonthChange"
                                        ></month-picker>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <button type="submit"
                                        class="btn btn-primary btn-sm">
                                    Duplicar
                                </button>
                            </div>
                            <div class="col text-right">
                                <button class="btn btn-sm btn-outline-default ml-auto" data-dismiss="modal">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

</template>

<script>
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import { MonthPicker } from 'vue-month-picker'
import moment from 'moment'
import axios from "axios";

export default {
    components: {
        Loading,
        MonthPicker
    },
    data() {
        return {
            isLoading    : false,
            selectedLang : 'es',
            currentMonth : parseInt(moment().format("MM")),
            nextMonth    : parseInt(moment().add(1,'months').format("MM")),
            nextYear     : parseInt(moment().add(1,'months').format("Y")),
            dateFrom : {
                from : null,
                to   : null,
                month: null,
                year : null,
            },
            dateTo: {
                from : null,
                to   : null,
                month: null,
                year : null,
            },
            validationErrors: ''
        }
    },

    methods: {
        duplicate: function () {
            let dateFrom = moment(this.dateFrom.from).format('YYYY-MM-DD')
            let dateTo = moment(this.dateTo.from).format('YYYY-MM-DD')

            if (dateFrom > dateTo) {
                Vue.$toast.warning("Mes destino debe ser mayor")
                return;
            }

            this.isLoading = true
            let url = `${this.appUrl}api/users/${this.currentUser.id}/duplicate-work-plan`
            axios.post(url, {
                from_month: dateFrom,
                to_month  : dateTo
            })
                .then(res => {

                    this.isLoading = false
                    $('#DuplicateWorkPlanModal').modal('hide');
                    setTimeout(() => Vue.$toast.success(res.data.msg), 500);

                }).catch(error => {
                this.isLoading = false
                if (error.response.status === 422){
                    Vue.$toast.warning("Información incorrecta")
                    this.validationErrors = error.response.data.errors;
                }
                if (error.response.status === 401) {
                    Vue.$toast.error(error.response.data.msg);
                }
            });

        },
        handleFromMonthChange: function (date) {
            this.dateFrom = date
        },
        handleToMonthChange: function (date) {
            this.dateTo = date
        }
    }
}
</script>

<style scoped>

</style>

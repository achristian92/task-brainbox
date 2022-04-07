<template>
    <div class="div">
        <loading :active.sync="isLoading" :is-full-page="true"></loading>
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col">
                        <h3 class="text-primary">{{ customer.name }}</h3>
                    </div>
                    <div class="col text-right">
                        <div class="btn-group dropleft float-right">
                            <button type="button"
                                    class="btn btn-white btn-sm btn-xs float-right dropdown-toggle"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false">
                                <i class="fas fa-calendar-alt mr-1 ml-1"></i>
                                {{ this.date.month }}
                            </button>
                            <div class="dropdown-menu">
                                <month-picker
                                    ref="monthpick"
                                    lang="es"
                                    :default-month=currentMonth
                                    @change="handleMonthChange" ></month-picker>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-muted">Estado</label><br>
                            <span v-if="customer.status" class="badge badge-success">Activo</span>
                            <span v-else class="badge badge-danger">Inactivo</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-muted">Documento</label>
                            <h5>RUC: {{ customer.ruc }}</h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-muted">Dirección</label>
                            <h5>{{ customer.address }}</h5>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-muted">Contacto</label>
                            <h5>{{ customer.contact_name }}</h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-muted">Teléfonos</label>
                            <h5>{{ customer.contact_telephone }}</h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-muted">Email</label>
                            <h5>{{ customer.contact_email }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Act. completadas</h5>
                                <span class="h2 font-weight-bold mb-0">
                                    {{ cardStats.acts.completed }}
                                    <span class="text-success">/</span>
                                    {{ cardStats.acts.total }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                    <i class="fas fa-list"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0"># Contadores</h5>
                                <span class="h2 font-weight-bold mb-0">{{ cardStats.counters.quantity }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Estimado / Real</h5>
                                <span class="h2 font-weight-bold mb-0">
                                    {{ cardStats.time.estimated }}
                                    <span class="text-info">/</span>
                                    {{ cardStats.time.real }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                    <i class="fas fa-stopwatch"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Progreso</h5>
                                <span class="h2 font-weight-bold mb-0">{{ cardStats.progress.percentage }}%</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-light text-white rounded-circle shadow">
                                    <i class="ni ni-chart-bar-32"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row justify-content-center">
                            <div class="col-md-9">
                                <progress-fusionchart :progress="charts.progress"></progress-fusionchart>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <resume-fusionchart :resume="charts.activities"></resume-fusionchart>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <tags-trend :line="charts.lineTags"></tags-trend>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <table-imbox :p_activities="activities" v-show="activities.length > 0"></table-imbox>
                    </div>
                </div>
                <activity-show-component></activity-show-component>
            </div>
        </div>
    </div>

</template>

<script>
import EventBus from "../../../event-bus";
import axios from 'axios'
import Loading from 'vue-loading-overlay'
import { MonthPicker } from 'vue-month-picker'
import moment from 'moment'
import Multiselect from "vue-multiselect";

export default {
    components: {
        Loading, MonthPicker
    },
    data() {
        return {
            isLoading     : false,
            customer_id   : '',
            cardStats     : {
                acts: {
                    completed: null,
                    total: null
                },
                counters: {
                    quantity: null
                },
                time: {
                    estimated: null,
                    real: null,
                },
                progress : {
                    percentage : null
                }
            },
            charts: {
                progress: 0,
                activities: {},
                lineTags : null
            },
            activities    : [],
            customer      : '',
            filters : {
                users : null,
                tags: null,
                status: null,
            },
            filtersSelected : {
                user_id: '',
                tag_id: '',
                status: '',
            },
            currentMonth  : parseInt(moment().format("MM")),
            yearAndMonth  : moment().startOf('month').format('YYYY-MM'),
            firstLoadPage : true,
            date : {
                from  : null,
                to    : null,
                month : null,
                year  : null,
            },
        }
    },
    props:['c_customer_id'],
    created() {
        if (this.c_customer_id) this.customer_id = this.c_customer_id
        this.getData()
    },
    methods: {
        back() {
            window.location.href = `${this.appUrl}admin/customers`;
        },
        getData() {
            this.isLoading = true
            console.log("entro x1")
            axios.get(`${this.appUrl}api/customers/${this.customer_id}/show`,{
                params:{
                    yearAndMonth : this.yearAndMonth,
                    user_id : this.filtersSelected.user_id,
                    tag_id : this.filtersSelected.tag_id,
                    status : this.filtersSelected.status,
                }
            })
            .then(res => {
                console.log("entro x2")
                this.isLoading  = false
                this.cardStats = {
                    acts: {
                        completed: res.data.charts.activities.qtyCompleted,
                        total: res.data.charts.activities.total
                    },
                    counters: {
                        quantity: res.data.filters.users.length
                    },
                    time: {
                        estimated: res.data.cardStats.timeEstimated,
                        real: res.data.cardStats.timeReal,
                    },
                    progress : {
                        percentage : res.data.charts.progress
                    }
                }

                this.charts = res.data.charts
                this.filters = res.data.filters
                this.activities = res.data.activities
                this.customer = res.data.customer
            })
            .catch (error => {
                this.isLoading = false
                if (error.response.status === 401) {
                    Vue.$toast.error(error.response.data.msg);
                }
            })
        },
        handleMonthChange (date) {
            this.filtersSelected = {
                user_id   : '',
                tag_id    : '',
                status : '',
            }
            this.date = date
            this.yearAndMonth =  moment(date.from).format('YYYY-MM')
            if (this.firstLoadPage) {
                this.firstLoadPage = false;
                return;
            }
            this.getData()
        },
        filter() {
            this.getData()
        }
    }
}
</script>

<style scoped>

</style>

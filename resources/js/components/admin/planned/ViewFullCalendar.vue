<template>
    <div class="row">
        <loading :active.sync="isLoading" :is-full-page="false"></loading>
        <div class="col-md-12">
            <div class="card card-calendar">
                <div class="card-header">
                    <filter-by-user-component
                        :view     = this.view
                        :users    = this.users
                        :statuses = this.status>
                    </filter-by-user-component>

                    <counters-component v-show="showCounters"
                        :total         = counters.total
                        :planned       = counters.planned
                        :approved      = counters.approved
                        :partial       = counters.partial
                        :completed     = counters.completed
                        :timeEstimated = counters.hoursEst>
                    </counters-component>

                    <div class="row mt-1" v-show="showRowActions">
                        <div class="col text-right d-inline">
                            <a href=""
                               class="btn btn-sm btn-outline-secondary btn-icon"
                               @click="handleMassApprove">
                                <span class="btn-inner--icon"><i class="ni ni-like-2"></i></span>
                                Aprobar plan
                            </a>
                            <a href=""
                               class="btn btn-sm btn-outline-secondary btn-icon"
                               @click="handleExport">
                                <span class="btn-inner--icon"><i class="fas fa-download"></i></span>
                                Exportar
                            </a>
                            <a href=""
                               class="btn btn-sm btn-outline-secondary btn-icon"
                               @click="handleExportDays">
                                <span class="btn-inner--icon"><i class="fas fa-download"></i></span>
                                Exportar x Día
                            </a>
                            <div class="btn-group dropleft">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-calendar-alt mr-1 ml-1"></i>
                                    {{ this.date.month }}
                                </button>
                                <div class="dropdown-menu">
                                        <month-picker
                                            ref="monthpick"
                                            :lang=selectedLang
                                            :default-month=currentMonth
                                            @change="handleMonthChange" ></month-picker>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#calendar" role="tab"
                               aria-controls="home" aria-selected="true">Calendario</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="order-tab" data-toggle="tab" href="#list" role="tab"
                               aria-controls="order" aria-selected="false">Lista</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="calendar" role="tabpanel" aria-labelledby="home-tab">
                            <full-calendar
                                ref="calendar"
                                :events="events"
                                @event-created="eventCreate"
                                @event-selected="eventClick"
                                :config="config">
                            </full-calendar>
                        </div>
                        <div class="tab-pane fade" id="list" role="tabpanel" aria-labelledby="order-tab">
                            <div class="table-responsive" v-show="this.activities.length > 0">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">Cliente</th>
                                        <th class="text-right" scope="col">Total Horas</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card mb-0" v-for="(customer,index) in activities">
                                <div class="card-header"
                                     data-toggle="collapse"
                                     aria-expanded="false"
                                     :id="'headingOne'+index"
                                     :data-target="'#collapseOne'+index"
                                     :aria-controls="'#collapseOne'+index">
                                    <div class="row">
                                        <div class="col align-self-center">
                                            <h5 class="mb-0">{{customer.name}}</h5>
                                        </div>
                                        <div class="col text-center">
                                            <div class="progress-wrapper pt-0">
                                                <div class="progress-info">
                                                    <div class="progress-label">
                                                        <span class="text-muted">{{customer.qtyActivities}} actividades</span>
                                                    </div>
                                                    <div class="progress-percentage">
                                                        <span>{{customer.progress}}%</span>
                                                    </div>
                                                </div>
                                                <div class="progress">
                                                    <div :class="'progress-bar '+customer.bgProgress"
                                                         role="progressbar"
                                                         aria-valuemin="0"
                                                         :aria-valuenow="customer.progress"
                                                         aria-valuemax="100"
                                                         :style="'width:'+ customer.progress+'%;'">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col align-self-center text-right">
                                            <h5 class="text-primary mr-4">{{customer.sumHoursEstCustomer}} Horas</h5>
                                        </div>
                                    </div>
                                </div>

                                <div :id="'collapseOne'+index" :aria-labelledby="'headingOne'+index" class="collapse" >
                                    <div class="table-responsive">
                                        <table class="table table-sm align-items-center">
                                            <thead>
                                            <tr>
                                                <th style="width: 20px">Fecha</th>
                                                <th>Actividad</th>
                                                <th>Estado</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="activity in customer.activities">
                                                <td>
                                                    {{activity.startDate}}
                                                </td>
                                                <td>
                                                    <h4><span class="font-weight-bold">{{activity.nameActivity}}</span></h4>
                                                    <div class="h6 d-inline-block">
                                                <span>
                                                    <i :class="'far fa-flag mr-1 '+activity.colorPriority"></i>
                                                    <span v-if="activity.is_priority">Alta</span>
                                                    <span v-else>Normal</span>
                                                </span>
                                                        <span v-if="activity.tagId">
                                                    <i class="ni ni-tag ml-2 mr-1" :style="'color:'+activity.tagColor"></i>
                                                    {{ activity.tagName }}
                                                </span>
                                                        <span>
                                                    <i class="ni ni-watch-time ml-2 mr-1"></i>
                                                    {{activity.estimatedTime}} hrs.
                                                </span>
                                                    </div>
                                                </td>
                                                <td>
                                        <span class="badge badge-dot mr-4">
                                            <i :class="activity.colorState"></i>
                                            <span class="status">{{activity.statusName}}</span>
                                        </span>
                                                    <div class="h6">
                                                        <i class="ni ni-single-02 mr-1"></i>
                                                        {{ activity.nameUserStateActivity }}
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</template>
<script>

import EventBus from '../../../event-bus'
import axios from 'axios'
import { MonthPicker } from 'vue-month-picker'
import moment from 'moment'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'

export default{
    components: {
        MonthPicker,
        Loading,
    },
    data() {
        return {
            isLoading      : false,
            userIDFilter   : '',
            customerFilter : '',
            statusFilter   : '',

            activities     : [],

            users          : [],
            status         : [],

            showCounters   : false,
            firstLoadPage  : true,

            month          : '' ,
            year           : '' ,

            view           : 'calendar',
            events         : [],
            showRowActions : false,
            selectedLang   : 'es',
            currentMonth   : parseInt(moment().format("MM")),
            counters : {
                total      : 0,
                planned    : 0,
                approved   : 0,
                partial    : 0,
                completed  : 0,
                hoursEst   : 0,
            },
            date : {
                from       : null,
                to         : null,
                month      : null,
                year       : null,
            },
            config : {
                locale     : 'es',
                defaultView: 'month',
                eventLimit : 3,
            },
        }
    },

    props: ['c_users','c_type_status'],
    created() {
        if (this.c_users) {
            this.users = this.c_users.map(function (i) {
                return {
                    'id' : i.id,
                    'text': i.full_name
                }
            })
        }

        if (this.c_type_status) this.status = this.c_type_status;


        EventBus.$on('sendFilter', data => {
            this.userIDFilter   = data.user_id
            this.customerFilter = data.customer_id
            this.statusFilter   = data.status_id
            this.firstLoadPage  = true
            this.$refs.monthpick.selectMonth(this.currentMonth-1)// january begin with 0
            this.$refs.calendar.fireMethod('gotoDate', data.current_date)
            this.refreshEvents()
        });

        EventBus.$on('selectedAnotherUser', data => {
            this.showRowActions = false
            this.showCounters   = false
            this.events         = []
        });

        EventBus.$on('refreshEvents',event => this.refreshEvents())

    },
    methods: {
        refreshEvents() {
            this.isLoading = true
            axios.get(`${this.appUrl}api/users/${this.userIDFilter}/activities-planned` , {
                params: {
                    customer_id : this.customerFilter,
                    status_id   : this.statusFilter,
                    month       : this.month,
                    year        : this.year,
                    view        : this.view,
                }
            })
            .then(res => {
                this.isLoading      = false
                this.showCounters   = true
                this.showRowActions = true
                this.events         = res.data.calendar
                this.activities     = res.data.list
                this.setCounters(res.data.counters)
            })
            .catch (error => {
                this.isLoading = false
                if (error.response.status === 401) {
                    Vue.$toast.error(error.response.data.msg);
                }
            })
        },

        eventCreate(event) {
            if (this.userIDFilter === '')  return;

            let date = moment(event.start).format()
            EventBus.$emit('newActivity', {date: date, view: this.view, userIDFilter: this.userIDFilter,activities: this.events});
            $('#ActivityModal').modal('show');
        },

        eventClick(event) {
            this.isLoading = true
            axios.get(`${this.appUrl}api/user/activities/${event.id}/edit`)
            .then(res => {
                this.isLoading = false
                EventBus.$emit('editActivity',  {activity:res.data.activity, view: this.view,activities: this.events});
                $('#ActivityModal').modal('show');
            })
            .catch(error => {
                this.isLoading = false
                if (error.response.status === 401) {
                    Vue.$toast.error(error.response.data.msg);
                }
            });
        },

        handleMonthChange (date) {
            this.date = date
            let dateFormat =  moment(date.from).format('YYYY-MM-DD')
            if (this.userIDFilter === '')  return;

            if (this.firstLoadPage) {
                this.firstLoadPage = false;
                return;
            }

            this.month = this.date.monthIndex
            this.year  = this.date.year

            this.$refs.calendar.fireMethod('gotoDate', dateFormat)
            this.refreshEvents()
        },

        handleMassApprove (e) {
            e.preventDefault();
            this.isLoading = true
            let url = `${this.appUrl}api/users/${this.userIDFilter}/activities/mass-approve`

            axios.get(url, {
                params: {
                    month : this.date.monthIndex,
                    year  : this.date.year
                }
            })
            .then(res => {
                this.isLoading = false
                Vue.$toast.success(res.data.msg)
                EventBus.$emit('resetFilter', {})
                this.refreshEvents()
            })
            .catch(error => {
                this.isLoading = false
                if (error.response.status === 401) {
                    Vue.$toast.error(error.response.data.msg);
                }
            });
        },
        handleExport (e) {
            e.preventDefault()
            Vue.$toast.success("Descargando...");
            let yearAndMonth =  moment(this.date.from).format('YYYY-MM-DD')
            window.location =  `${this.appUrl}api/users/${this.userIDFilter}/planned/export-list?yearAndMonth=${yearAndMonth}`
        },
        handleExportDays (e) {
            e.preventDefault()
            Vue.$toast.success("Descargando...");
            let yearAndMonth =  moment(this.date.from).format('YYYY-MM-DD')
            window.location =  `${this.appUrl}api/users/${this.userIDFilter}/planned/export-day?yearAndMonth=${yearAndMonth}`
        },

        setCounters(counter) {
            this.counters.total     = counter.total
            this.counters.planned   = counter.qtyPlanned
            this.counters.approved  = counter.qtyApproved
            this.counters.partial   = counter.qtyPartial
            this.counters.completed = counter.qtyCompleted
            this.counters.hoursEst  = counter.timeEstimate
        },
    }

}
</script>

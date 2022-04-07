<template>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header border-0">
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
                </div>
                <div class="card-body border-0">
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
</template>

<script>
import EventBus from "../../../event-bus";
import axios from "axios";
import moment from 'moment'

export default {
    data() {
        return {
            userIDFilter   : '',
            customerFilter : '',
            statusFilter   : '',

            users          : '',
            status         : [],

            activities     : [],
            view           : 'list',
            showCounters   : false,
            currentMonth   : parseInt(moment().format("MM")),
            currentYear    : parseInt(moment().format("Y")),
            counters : {
                total      : 0,
                planned    : 0,
                approved   : 0,
                partial    : 0,
                completed  : 0,
                hoursEst   : 0,
            },
        }
    },
    props: ['c_users','c_type_status'],
    created() {
        if (this.c_users) this.users = this.c_users;
        if (this.c_type_status) this.status = this.c_type_status;

        EventBus.$on('sendFilter', data => {
            this.userIDFilter   = data.user_id
            this.customerFilter = data.customer_id
            this.statusFilter   = data.status_id
            this.refreshList()
        });

        EventBus.$on('selectedAnotherUser', data => {
            this.showCounters = false
            this.activities = []
        });

        EventBus.$on('refreshList',event => this.refreshList())

    },
    methods: {
        refreshList() {
            Vue.$toast.success("Actualizando información...");
            this.isLoading = true
            let url = `${this.appUrl}api/users/${this.userIDFilter}/activities-planned`;
            axios.get(url , {
                params: {
                    customer_id : this.customerFilter,
                    status_id   : this.statusFilter,
                    month       : this.currentMonth,
                    year        : this.currentYear,
                    view        : this.view,
                }
            })
            .then(res => {
                this.isLoading      = false
                this.showCounters   = true
                this.showRowActions = true
                this.activities     = res.data.activities
                this.setCounters(res.data.counters)
            })
            .catch (error => {
                this.isLoading = false
                if (error.response.status === 401) {
                    Vue.$toast.error(error.response.data.msg);
                }
            })
        },

        setCounters(counter) {
            this.counters.total     = counter.total
            this.counters.planned   = counter.qtyPlanned
            this.counters.approved  = counter.qtyApproved
            this.counters.partial   = counter.qtyPartial
            this.counters.completed = counter.qtyCompleted
            this.counters.hoursEst  = counter.timeEstimate
        }
    }
}
</script>

<style scoped>

</style>

<script>

  import Loading from 'vue-loading-overlay';
  import 'vue-loading-overlay/dist/vue-loading.css';
  import moment from 'moment';
  import axios from 'axios';
  import EventBus from '../../../event-bus';

  export default {
    components: {
      Loading
    },
    data() {
      return {
        isLoading: false,
        customerFilter: '',
        statusFilter: '',

        statuses: [],
        customers: [],
        user_id: '',

        activities     : [],

        events: [],
        month: '',
        year: '',

        showCounters: true,
        showRowActions: true,
        view: 'calendar',

        counters: {
          total: 0,
          planned: 0,
          approved: 0,
          partial: 0,
          completed: 0,
          hoursEst: 0
        },
        config: {
          locale: 'es',
          defaultView: 'month',
          eventLimit: 3
        }
      };
    },
    props: ['c_customers', 'c_type_status'],
    created() {
      if (this.c_customers) {
          this.customers = this.c_customers.map(function (i) {
              return {
                  'id' : i.id,
                  'text': i.name
              }
          })
      }
      if (this.c_type_status) this.statuses = this.c_type_status;
      this.user_id = this.currentUser.id;

      this.refreshEventsOwn(); //LOAD INITIAL OF ACTIVITIES

      EventBus.$on('sendFilterOwn', data => {
        // IF USER USED TO FILTER
        this.customerFilter = data.customer_id;
        this.statusFilter = data.status_id;
        EventBus.$emit('resetMonthPicker', {});
        this.$refs.calendar.fireMethod('gotoDate', data.current_date);
      });

      EventBus.$on('selectedAnotherFilter', data => {
        // IF USER CHANGE OPTION FILTER
        this.events = [];
        this.showCounters = false;
        this.showRowActions = false;
      });

      EventBus.$on('refreshEvents', event => this.refreshEventsOwn()); //REFRESH ACTION FROM ACTIVITY COMPONENT

      EventBus.$on('changeMonth', data => this.handleChangeMonth(data)); // LISTER USER CHANGE MONTH
    },
    methods: {
      refreshEventsOwn() {
        //Vue.$toast.success("Actualizando información...");
        this.isLoading = true;
        axios
          .get(`${this.appUrl}api/users/${this.user_id}/activities-planned`, {
            params: {
              customer_id: this.customerFilter,
              status_id: this.statusFilter,
              month: this.month,
              year: this.year,
              view: this.view
            }
          })
          .then(res => {
            this.isLoading = false;
            this.showCounters = true;
            this.showRowActions = true;
            this.events         = res.data.calendar
            this.activities     = res.data.list
            this.setCounters(res.data.counters);
          })
          .catch(error => {
            this.isLoading = false;
            if (error.response.status === 401) {
              Vue.$toast.error(error.response.data.msg);
            }
          });
      },

      handleChangeMonth(data) {
        this.month = parseInt(moment(data.dateFormat).format('MM'));
        this.year = parseInt(moment(data.dateFormat).format('Y'));

        this.$refs.calendar.fireMethod('gotoDate', data.dateFormat);

        this.refreshEventsOwn();
      },
      eventCreate(event) {
        let date = moment(event.start).format();
        EventBus.$emit('newActivity', {
          date: date,
          view: this.view,
          userIDFilter: this.user_id,
          activities: this.events
        });
        $('#ActivityModal').modal('show');
      },
      eventClick(event) {
        this.isLoading = true;
        axios
          .get(`${this.appUrl}api/user/activities/${event.id}/edit`)
          .then(res => {
            this.isLoading = false;
            EventBus.$emit('editActivity', { activity: res.data.activity, view: this.view, activities: this.events });
            $('#ActivityModal').modal('show');
          })
          .catch(error => {
            this.isLoading = false;
            if (error.response.status === 401) {
              Vue.$toast.error(error.response.data.msg);
            }
          });
      },

      setCounters(counter) {
        this.counters.total = counter.total;
        this.counters.planned = counter.qtyPlanned;
        this.counters.approved = counter.qtyApproved;
        this.counters.partial = counter.qtyPartial;
        this.counters.completed = counter.qtyCompleted;
        this.counters.hoursEst = counter.timeEstimate;
      }
    }
  };

</script>

<template>

  <div class="row">
    <loading :active.sync="isLoading" :is-full-page="false"></loading>
    <div class="col">
      <div class="card card-calendar">
        <div class="card-header">
          <filter-component :view=view :customers=customers :statuses=statuses>
          </filter-component>

          <counters-component v-show="showCounters" :total=counters.total :planned=counters.planned :approved=counters.approved
                              :partial=counters.partial
                              :completed=counters.completed
                              :timeEstimated=counters.hoursEst>
          </counters-component>

          <actions-component v-show="showRowActions"
                             :view="view"
                             :user_id="this.user_id">
          </actions-component>
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
                    <full-calendar ref="calendar"
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

<style scoped>

</style>

<template>

  <div class="row">
      <div class="col-md-12 mt-3 mb-2">
          <div class="m-b-md">
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
                          :lang=selectedLang
                          :default-month=currentMonth
                          @change="handleMonthChange" ></month-picker>
                  </div>
              </div>
          </div>
      </div>
    <div class="col-lg-12">
      <div class="card">
        <div class="tabs-container">
          <tab-imbox :myImboxId="myImboxID"></tab-imbox>
          <div class="tab-content">
            <div role="tabpanel" id="tab-1" class="tab-pane active show">
              <div class="row mt-2 mb-2 mr-1">
                <div class="col text-right">
                  <button @click="activityNew" type="button" class="btn btn-sm btn-primary btn-icon">
                      <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>
                      <span class="btn-inner--text">Nueva actividad</span>
                  </button>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <TableImbox :p_activities="activities" :p_tab="currentTab"></TableImbox>
                </div>
              </div>
              <activity-new-component :p_customers="customers" :p_tags="tags"></activity-new-component>
            </div>
            <div role="tabpanel" id="tab-2" class="tab-pane">
              <div class="row">
                <div class="col-md-12">
                  <TableImbox :p_activities="activities" :p_tab="currentTab"></TableImbox>
                </div>
              </div>
            </div>
            <div role="tabpanel" id="tab-3" class="tab-pane">
              <div class="row">
                <div class="col-md-12">
                  <TableImbox :p_activities="activities" :p_tab="currentTab"></TableImbox>
                </div>
              </div>
            </div>
          <div role="tabpanel" id="tab-4" class="tab-pane">
              <div class="row">
                  <div class="col-md-12">
                      <TableImboxApproved :p_activities="activities"></TableImboxApproved>
                  </div>
              </div>
          </div>
          </div>
        </div>
      </div>
    </div>
    <activity-partial-component></activity-partial-component>
    <activity-show-component></activity-show-component>
    <sub-activity-component></sub-activity-component>
  </div>

</template>

<script>

  import EventBus from '../../../event-bus';
  import moment from "moment";
  import Loading from "vue-loading-overlay";
  import {MonthPicker} from "vue-month-picker";

  export default {
      components: {
          Loading, MonthPicker
      },
    data() {
      return {
        activities: [],
        customers: [],
        tags: [],
        currentTab: 'today',
        myImboxID: null,
          currentMonth  : parseInt(moment().format("MM")),
          yearAndMonth  : '',
          firstLoadPage : true,
          selectedLang  : 'es',
          date : {
              from  : null,
              to    : null,
              month : null,
              year  : null,
          },

      };
    },
    props: ['c_customers', 'c_tags', 'c_my_imbox'],
    created() {
      if (this.c_customers) this.customers = this.c_customers;
      if (this.c_tags) this.tags = this.c_tags;
      if (this.c_my_imbox) this.myImboxID = this.c_my_imbox;

      EventBus.$on('update-activity-event', data => {
        let indexInActivitiesData = this.activities.findIndex(i => i.id === data.activity.id);
        if (indexInActivitiesData !== undefined) this.activities.splice(indexInActivitiesData, 1, data.activity);
        Vue.$toast.success(data.msg);
      });

      EventBus.$on('activity-added', data => {
        this.activities.push(data.activity);
        Vue.$toast.success(data.msg);
      });

      EventBus.$on('refreshImbox', data => {
        this.activities = data.activities;
        this.currentTab = data.typeTab;
      });
    },
    methods: {
        activityNew() {
            EventBus.$emit('activityNew', {});
        },
        handleMonthChange (date) {
            this.date = date
            if (this.firstLoadPage) {
                this.firstLoadPage = false;
                return;
            }
            this.yearAndMonth =  moment(date.from).format('YYYY-MM-DD')
            EventBus.$emit('refreshImboxByMonth', {'yearAndMonth':this.yearAndMonth});
        },
    }
  };

</script>

<style scoped>

</style>

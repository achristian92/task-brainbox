
// require('./bootstrap');


window.Vue = require('vue');

import 'fullcalendar/dist/fullcalendar.css';
import FullCalendar from 'vue-full-calendar';
Vue.use(FullCalendar);

import * as VueFusionCharts from 'vue-fusioncharts';
import * as FusionCharts from 'fusioncharts';
import Column2D from 'fusioncharts/fusioncharts.charts';
import * as FusionTheme from 'fusioncharts/themes/fusioncharts.theme.fusion';
import * as Widgets from "fusioncharts/fusioncharts.widgets.js";
import TreeMap from 'fusioncharts/fusioncharts.treemap';
import * as Gantt from "fusioncharts/fusioncharts.gantt.js";

import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
Vue.use(VueToast)


Vue.use(VueFusionCharts, FusionCharts, Column2D, FusionTheme,Widgets,TreeMap,Gantt);


Vue.component('admin-imbox-basic', require('./components/admin/imbox/BasicTable.vue').default);
Vue.component('admin-imbox-evaluate', require('./components/admin/imbox/EvaluationTable.vue').default);


Vue.component('counter-imbox-action', require('./components/counter/imbox/Imboxv2.vue').default);



/** Admin */
Vue.component('Statistics', require('./components/admin/dashboard/Statistics.vue').default);
Vue.component('list-tags', require('./components/admin/tags/listTags.vue').default);
Vue.component('add-tag-btn', require('./components/admin/tags/addTag.vue').default);
Vue.component('modal-tag', require('./components/admin/tags/modalTag.vue').default);

Vue.component('view-fullcalendar', require('./components/admin/planned/ViewFullCalendar.vue').default);
Vue.component('view-list', require('./components/admin/planned/ViewLis.vue').default);
Vue.component('imbox-admin', require('./components/admin/imbox/Imbox.vue').default);

Vue.component('customers-show', require('./components/admin/customers/Show').default);


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Vue.component('company-more-hours', require('./components/fusioncharts/dashboard/CompanyMoreHours').default);
Vue.component('company-less-hours', require('./components/fusioncharts/dashboard/CompanyLessHours').default);
Vue.component('user-more-hours', require('./components/fusioncharts/dashboard/UserMoreHours').default);
Vue.component('user-less-hours', require('./components/fusioncharts/dashboard/UserLessHours').default);
Vue.component('tags-percentage', require('./components/fusioncharts/TagsPercentage').default);
//Company
Vue.component('tags-trend', require('./components/fusioncharts/company/TagTrend').default);

//FusionChart
Vue.component('progress-fusionchart', require('./components/fusioncharts/Progress').default);
Vue.component('resume-fusionchart', require('./components/fusioncharts/Resume').default);
Vue.component('bar-activities-fusionchart', require('./components/fusioncharts/BarActivities').default);
Vue.component('bar-hours-fusionchart', require('./components/fusioncharts/BarHours').default);
Vue.component('history-fusionchart', require('./components/fusioncharts/History').default);

/*
|--------------------------------------------------------------------------
| Tracks
|--------------------------------------------------------------------------
*/
Vue.component('tracks', require('./components/admin/tracks/List').default);



/** Own */
Vue.component('view-fullcalendar-counter-planned', require('./components/counter/planned/ViewFullCalendar').default);
Vue.component('view-list-counter-planned', require('./components/counter/planned/ViewList').default);
Vue.component('imbox-counter', require('./components/counter/imbox/Imbox').default);
Vue.component('add-new-activity', require('./components/counter/imbox/AddNewActivity').default);

/** Shared */
Vue.component('TableImbox', require('./components/shared/imbox/TableImbox.vue').default);
Vue.component('TableImboxApproved', require('./components/shared/imbox/TableImboxApproved.vue').default);
Vue.component('tab-imbox', require('./components/shared/imbox/TabImbox').default);
Vue.component('validation-errors', require('./components/shared/ValidationErrors').default);

Vue.component('show-user-track', require('./components/shared/tracks/ShowUserTrack').default);

//REPORTS
Vue.component('report-users', require('./components/shared/reports/users/ReportComponent').default);
Vue.component('report-users-planned-vs-real-component', require('./components/shared/reports/users/Planned-vs-Real').default);
Vue.component('report-users-time-worked-by-customer-component', require('./components/shared/reports/users/TimeWorkedByCustomer').default);
Vue.component('report-users-time-worked-by-day-component', require('./components/shared/reports/users/TimeWorkedByDay').default);

Vue.component('report-customers', require('./components/shared/reports/customers/ReportComponent').default);
Vue.component('report-customers-time-worked-by-month-component', require('./components/shared/reports/customers/TimeWorkedByMonth').default);
Vue.component('report-customers-list-users-working-component', require('./components/shared/reports/customers/ListUsersWorking').default);
Vue.component('report-customers-history-last-six-months-component', require('./components/shared/reports/customers/HistoryLastSixMonth').default);

Vue.component('report-activities', require('./components/shared/reports/activities/ReportComponent').default);
Vue.component('report-activities-list-activities-component', require('./components/shared/reports/activities/ListActivities').default);

Vue.component('report-tags', require('./components/shared/reports/customers/Tags').default);


//ACTIVITIES
Vue.component('activity-component', require('./components/shared/activity/ActivityComponent').default);
Vue.component('activity-new-component', require('./components/shared/activity/ActivityNewComponent').default);
Vue.component('activity-partial-component', require('./components/shared/activity/PartialComponent').default);
Vue.component('activity-show-component', require('./components/shared/activity/ActivityShowComponent').default);
Vue.component('sub-activity-component', require('./components/shared/activity/SubActivityComponent').default);

Vue.component('filter-by-user-component', require('./components/shared/activity/partials/FilterByUserComponent').default);
Vue.component('counters-component', require('./components/shared/activity/partials/CountersComponent.vue').default);
Vue.component('filter-component', require('./components/shared/activity/partials/FilterComponent.vue').default);
Vue.component('actions-component', require('./components/shared/activity/partials/ActionsComponent').default);

Vue.component('duplicate-work-plan-component', require('./components/shared/activity/actions/DuplicateWorkPlanComponent').default);
Vue.component('mass-destroy-work-plan-component', require('./components/shared/activity/actions/MassDestroyWorkPlanComponent').default);
Vue.component('import-work-plan-component', require('./components/shared/activity/actions/ImportWorkPlanComponent').default);



import auth from './mixins/auth'
Vue.mixin(auth);


const app = new Vue({
    el: '#app',
});

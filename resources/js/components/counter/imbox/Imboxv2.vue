<template>
    <div class="table-responsive">
        <loading :active.sync="isLoading" :is-full-page="false"></loading>
        <table class="table align-items-center table-flush border-bottom-0" id="dtMyImboxAction">
            <thead class="thead-light">
            <tr>
                <th v-if="canTakeAcions()"></th>
                <th style="width: 10px;">Fecha</th>
                <th>Actividad | Cliente</th>
                <th>Estado</th>
                <th>Planeado</th>
                <th>Duración</th>
                <th class="text-right">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(activity,index) in activities">
                <td  v-if="canTakeAcions()">
                    <input type="radio"
                           :value="activity.id"
                           v-model="checkedRadio"
                           v-on:click="showRowImputDuration(index)"
                           :disabled="!activity.canCompleted"/>
                </td>
                <td style="width: 10px;">
                    {{ activity.startDateShort }}
                </td>
                <td>
                    <a class="font-weight-bold">{{ activity.activityNameShort }}</a>
                    <span v-if="!activity.isPlanned" class="text-success ml-2">(Nuevo)</span>
                    <br>
                    <span class="h6 text-muted">
                        <i class="far fa-building mr-1"></i> {{activity.customerNameShort}} |
                        <i class="far fa-user mr-1 ml-1"></i> {{activity.userNameShort}} |
                        <i class="far fa-flag mr-1 ml-1"></i>
                        <span v-if="activity.isPriority">Alta</span>
                        <span v-else>Normal</span>
                        <span v-if="activity.qtySubActivities > 0">
                          |  <i class="fas fa-cogs mr-1 ml-1" ></i> {{activity.qtySubActivities}}
                        </span>
                        <span v-if="activity.qtyDependencies > 0">
                          |  <i class="fas fa-code-branch mr-1 ml-1" ></i> {{activity.qtyDependencies}}
                        </span>
                        <span v-if="activity.tagId">
                            <i class="ni ni-tag ml-2 mr-1" :style="'color:'+activity.tagColor"></i>
                            {{ activity.tagName }}
                        </span>
                    </span>
                </td>
                <td>
                    <button type="button" :class="'btn btn-sm '+activity.statusColorBtnOutline">
                        {{ activity.statusName }}
                        <span v-show="activity.status === 'partial'"><i class="fas fa-bell ml-1 text-yellow"></i></span>
                    </button>
                </td>
                <td>
                    {{ activity.estimatedTime }} h
                </td>
                <td>
                    <div v-if="showInputTimeReal !== index">{{ activity.duration }} h</div>
                    <div v-else>
                        <input type="text"
                               maxlength="5"
                               placeholder="00:00"
                               class="d-inline-block form-control form-control-sm"
                               style="width: 100px"
                               v-model="activity.estimatedTime" />
                        <button v-on:click="activityFinished(activity.id,activity.estimatedTime)" type="button" class="ml-2 d-inline-block btn btn-outline-success btn-sm">
                            <span><i class="fas fa-check"></i></span>
                        </button>
                        <button v-on:click="cancelShowRowImputDuration" type="button" class="ml-1 d-inline-block btn btn-outline-danger btn-sm">x</button>
                    </div>
                </td>

                <td class="text-right">
                    <div class="dropdown">
                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a v-show="showToComplete(activity)"
                               class="dropdown-item"
                               href=""
                               @click.prevent="partial(activity.id)">
                                <span>Completado | Parcial</span>
                            </a>
                            <a v-show="showToComplete(activity)"
                               class="dropdown-item"
                               href=""
                               @click.prevent="addSubTasks(activity.id)">
                                <span>Agregar Sub Actividades</span>
                            </a>
                            <a @click.prevent="show(activity.id)"
                               class="dropdown-item"
                               href="">
                                <span>Detalle</span>
                            </a>
                            <a v-show="activity.canDestroy"
                               class="dropdown-item"
                               href=""
                               @click.prevent="destroy(activity.id)">
                                <span>Eliminar</span>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <br><br><br>

    </div>
</template>
<script>
import axios from 'axios'
import EventBus from "../../../event-bus";
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import moment from 'moment'

export default {
    components: {
        Loading
    },
    data() {
        return {
            activities        : [],
            tab               : '',
            checkedRadio      : false,
            showInputTimeReal : -1,
            isLoading         : false,
        }
    },
    props: ['p_activities','p_tab'],
    created() {
        if (this.p_activities)
            this.activities = this.p_activities

        if (this.p_tab)
            this.tab = this.p_tab

        EventBus.$on('update-activity-event', data => {
            let indexInActivitiesData = this.activities.findIndex(i => i.id === data.activity.id);
            if (indexInActivitiesData !== undefined) this.activities.splice(indexInActivitiesData, 1, data.activity);
            Vue.$toast.success(data.msg);
        });

        EventBus.$on('activity-added', data => {
            this.activities.push(data.activity);
            Vue.$toast.success(data.msg);
        });
    },
    methods: {
        show: function (activity_id) {
            this.isLoading = true
            axios.get(`${this.appUrl}api/user/activities/${activity_id}`)
                .then(res => {
                    this.isLoading = false
                    EventBus.$emit('activity-show', res.data);
                })
                .catch(error => {
                    this.isLoading = false
                    if (error.response.status === 401) {
                        Vue.$toast.error(error.response.data.msg);
                    }
                });
        },
        showToComplete: function (activity) {
            return activity.status !== 'planned' &&
                activity.isPlanned &&
                activity.isDependenciesCompleted &&
                this.canTakeAcions()

        },
        canTakeAcions: function() {
            return this.tab === 'today' || this.tab ==='overdue'
        },
        partial: function (activity_id) {
            axios.get(`${this.appUrl}api/user/activities/${activity_id}`)
                .then(res => {
                    EventBus.$emit('partial-event', res.data);
                    $('#PartialActivityModal').modal('show');
                })
                .catch(error => {
                    this.isLoading = false
                    if (error.response.status === 401) {
                        Vue.$toast.error(error.response.data.msg);
                    }
                });
        },
        addSubTasks: function (activity_id) {
            EventBus.$emit('subactivity-event', activity_id);
        },
        showRowImputDuration:function (index) {
            this.showInputTimeReal = index
        },
        activityFinished: function (activityID,duration) {

            if (!this.isValidTimeEstimated(duration) || duration === '00:00') {
                Vue.$toast.warning('Tiempo incorrecto');
                return;
            }

            axios.put(`${this.appUrl}api/users/activities/${activityID}/finished`,{
                duration: duration,
            })
                .then(res => {
                    this.isLoading = false
                    $('#ModalSubActivity').modal('hide');
                    this.showInputTimeReal = -1
                    this.checkedRadio = false

                    let indexInActivitiesData = this.activities.findIndex(i => i.id === res.data.activity.id);
                    if (indexInActivitiesData !== undefined) this.activities.splice(indexInActivitiesData, 1, res.data.activity);
                    Vue.$toast.success(res.data.msg);
                })
                .catch(error => {
                    this.isLoading = false
                    if (error.response.status === 401) {
                        Vue.$toast.error(error.response.data.msg);
                    }
                });
        },
        destroy: function (activity_id) {
            let isConfirmed = confirm("Estas seguro de eliminar esta actividad?")
            if(!isConfirmed) {return false;}

            axios.delete(`${this.appUrl}api/user/activities/${activity_id}/destroy`)
                .then(res => {
                    let indexInActivitiesData = this.activities.findIndex(i => i.id === activity_id);
                    if (indexInActivitiesData !== undefined) this.activities.splice(indexInActivitiesData, 1);
                    Vue.$toast.success(res.data.msg);
                })
                .catch(error => {
                    this.isLoading = false
                    if (error.response.status === 401) {
                        Vue.$toast.error(error.response.data.msg);
                    }
                });
        },
        cancelShowRowImputDuration: function () {
            this.showInputTimeReal = -1;
            this.checkedRadio = false;
        },
        isValidTimeEstimated(inputStr) {
            if (inputStr.substr(2,1) === ':') {
                if (!inputStr.match(/^\d\d:\d\d/)) { return false;}
                else if (parseInt(inputStr.substr(0,2)) >= 24 || parseInt(inputStr.substr(3,2)) >= 60)  {
                    return false;
                }
                else { return true;}
            }
            else { return false; }
        }
    }
}

</script>

<style scoped>

</style>

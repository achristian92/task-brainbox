<template>
    <div class="table-responsive">
        <loading :active.sync="isLoading" :is-full-page="false"></loading>
        <table class="table align-items-center table-flush">
            <thead class="thead-light">
            <tr>
                <th>Actividad | Cliente</th>
                <th>Estado</th>
                <th>Completado</th>
                <th>Registrado</th>
                <th class="text-right">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(activity,index) in p_activities">
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
                <td class="text-primary">
                    {{ activity.recordDateShort }}
                </td>
                <td class="text-warning">
                    {{ activity.completedDateShort }}
                </td>

                <td class="text-right">
                    <button type="button" @click.prevent="approvereject(activity.id,true)" class="ml-2 d-inline-block btn btn-outline-success btn-sm">
                        <span><i class="fas fa-check"></i></span>
                    </button>
                    <button type="button" @click.prevent="approvereject(activity.id,false)" class="ml-2 d-inline-block btn btn-outline-danger btn-sm">
                        <span><i class="fas fa-times"></i></span>
                    </button>
                    <div class="dropdown">
                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
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
    props: {
        p_activities: '',
        p_tab: ''
    },
    data() {
        return {
            activities        : this.p_activities,
            isLoading         : false,
        }
    },
    methods: {
        approvereject: function (id,approved = false)  {
            axios.post(`${this.appUrl}api/imbox/approve-reject`,{
                'activity_id' : id,
                'approved' : approved
            })
            .then(res => {
                this.isLoading = false
                let indexInActivitiesData = this.p_activities.findIndex(i => i.id === id);
                if (indexInActivitiesData !== undefined) this.p_activities.splice(indexInActivitiesData, 1);
                Vue.$toast.success(res.data.msg);
            })
            .catch(error => {
                this.isLoading = false
                if (error.response.status === 422){
                    this.errors = error.response.data.errors;
                    Vue.$toast.error("Información inválida");
                }

                if (error.response.status === 401) {
                    Vue.$toast.error(error.response.data.msg);
                }
            });
            console.log("approve:"+id)
            console.log("approve:"+approved)
        },
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

        destroy: function (activity_id) {
            let isConfirmed = confirm("Estas seguro de eliminar esta actividad?")
            if(!isConfirmed) {return false;}

            axios.delete(`${this.appUrl}api/user/activities/${activity_id}/destroy`)
                .then(res => {
                    let indexInActivitiesData = this.p_activities.findIndex(i => i.id === activity_id);
                    if (indexInActivitiesData !== undefined) this.p_activities.splice(indexInActivitiesData, 1);
                    Vue.$toast.success(res.data.msg);
                })
                .catch(error => {
                    this.isLoading = false
                    if (error.response.status === 401) {
                        Vue.$toast.error(error.response.data.msg);
                    }
                });
        }
    }
}

</script>

<style scoped>

</style>

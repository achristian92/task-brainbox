<template>
    <div class="modal fade" id="ActivityShowModal" tabindex="-1" role="dialog" aria-labelledby="edit-event-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="nav-wrapper">
                        <ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="tabs-text" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-text-1-tab" data-toggle="tab" href="#tabs-text-1" role="tab" aria-controls="tabs-text-1" aria-selected="true">Actividad</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-text-2-tab" data-toggle="tab" href="#tabs-text-2" role="tab" aria-controls="tabs-text-2" aria-selected="false">SubActividades</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-text-3-tab" data-toggle="tab" href="#tabs-text-3" role="tab" aria-controls="tabs-text-3" aria-selected="false">Historial</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="tabs-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                                    <dl class="row">
                                        <dt class="col-sm-3 h5 text-muted">Cliente:</dt>
                                        <dd class="col-sm-9 h5">{{customer}}</dd>
                                        <dt class="col-sm-3 h5 text-muted">Actividad:</dt>
                                        <dd class="col-sm-9 h5">{{activity}}</dd>
                                        <dt class="col-sm-3 h5 text-muted">Etiqueta:</dt>
                                        <dd class="col-sm-9 h5">{{tag}}</dd>
                                        <dt class="col-sm-3 h5 text-muted">Inicio:</dt>
                                        <dd class="col-sm-3 h5">{{startDate}}</dd>
                                        <dt class="col-sm-3 h5 text-muted">Fin:</dt>
                                        <dd class="col-sm-3 h5">{{dueDate}}</dd>
                                        <dt class="col-sm-3 h5 text-muted" v-show="completedOutDate"></dt>
                                        <dd class="col-sm-9 h5 text-danger" v-show="completedOutDate">Completado el {{dateCompleted}} fuera de fecha </dd>
                                        <dt class="col-sm-3 h5 text-muted">Previsto:</dt>
                                        <dd class="col-sm-3 h5">{{estimatedHours}} h</dd>
                                        <dt class="col-sm-3 h5 text-muted">Real:</dt>
                                        <dd class="col-sm-3 h5">{{realHours}} h</dd>
                                        <dt class="col-sm-3 h5 text-muted">Estado:</dt>
                                        <dd class="col-sm-9 h5">{{status}} por {{userStatus}}</dd>
                                        <dt class="col-sm-3 h5 text-muted">Descripción:</dt>
                                        <dd class="col-sm-9 h5">{{description}}</dd>
                                    </dl>
                                    <div class="row" v-show="partials.length > 0">
                                        <div class="col-md-12">
                                            <span class="h5">Avances</span>
                                            <ul class="list-group">
                                                <li class="list-group-item" v-for="partial in partials">
                                                    <span class="h5">{{partial.name}}</span> <br>
                                                    <span class="h6 text-muted">
                                                    <i class="far fa-clock mr-1"></i>{{partial.duration}} |
                                                    <i class="far fa-calendar-check ml-1 mr-1"></i>{{ partial.completed_at}}
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row mt-2" v-show="dependencies.length > 0">
                                        <div class="col-md-12">
                                            <span class="h5">Dependencias</span>
                                            <ul class="list-group">
                                                <li class="list-group-item" v-for="dependence in dependencies">
                                                    <span class="h5">{{dependence.name}}</span> <br>
                                                    <span class="h6 text-muted">
                                                    <i class="far fa-clock mr-1"></i>{{dependence.duration}} |
                                                    <i class="far fa-calendar-check ml-1 mr-1"></i>{{ dependence.completed_at}} |
                                                    <i class="far fa-circle-o ml-1 mr-1"></i>{{ dependence.status}}
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tabs-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                    <div class="row" v-if="subActivities.length > 0">
                                        <div class="col-md-12">
                                            <ul class="list-group">
                                                <li class="list-group-item" v-for="subact in subActivities">
                                                    <span class="h5">{{subact.name}}</span> <br>
                                                    <span class="h6 text-muted">
                                                    <i class="far fa-clock mr-1"></i>{{subact.duration}} |
                                                    <i class="far fa-calendar-check ml-1 mr-1"></i>{{ subact.completed_at}}
                                                    <a href="" @click.prevent="SubActivityDestroy(subact.id)" class="text-muted"><i class="far fa-trash-alt ml-2 mr-1"></i> eliminar </a>
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row" v-else>
                                        <div class="h4">No tienes subactividades</div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tabs-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
                                    <div class="list-group">
                                        <a v-for="history in histories"  class="list-group-item list-group-item-action flex-column align-items-start">
                                            <p class="mb-0 h5"> {{history.description}} </p>
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1 text-muted">por {{history.user}}</h6>
                                                <small class="h6 text-muted">{{history.dateShort}}</small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col text-right">
                            <button class="btn btn-sm btn-outline-default ml-auto" data-dismiss="modal">
                                Cerrar
                            </button>
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

export default {
    data() {
        return {
            customer         : null,
            tag              : null,
            activity         : null,
            startDate        : null,
            dueDate          : null,
            dateCompleted    : null,
            completedOutDate : false,
            estimatedHours   : null,
            realHours        : null,
            status           : null,
            userStatus       : null,
            description      : null,
            histories        : [],
            subActivities    : [],
            partials         : [],
            dependencies     : []
        }
    },
    created() {
        EventBus.$on('activity-show', data => this.show(data));
    },
    methods: {
        SubActivityDestroy: function (subactivity_id) {
            let isConfirmed = confirm("Estas seguro de eliminar esta sub-actividad?")
            if(!isConfirmed) {return false;}
            axios.delete( `${this.appUrl}api/users/activities/sub-activity/${subactivity_id}/destroy`)
                .then(res => {
                    let indexInActivitiesData = this.subActivities.findIndex(i => i.id === subactivity_id);
                    if (indexInActivitiesData !== undefined) this.subActivities.splice(indexInActivitiesData, 1);
                })
                .catch(error => {
                    this.isLoading = false
                    if (error.response.status === 401) {
                        Vue.$toast.error(error.response.data.msg);
                    }
                });

        },
        show: function (data) {
            this.clear()
            this.customer         = data.customer
            this.tag              = data.tag
            this.activity         = data.activity
            this.startDate        = data.startDateShort
            this.dueDate          = data.dueDateShort
            this.dateCompleted    = data.dateCompleted
            this.completedOutDate = data.isCompletedOutDate
            this.estimatedHours   = data.estimatedTime
            this.realHours        = data.realTime
            this.status           = data.status
            this.userStatus       = data.userStatusAct
            this.description      = data.description
            this.histories        = data.histories
            this.subActivities    = data.subActivities
            this.partials         = data.partials
            this.dependencies     = data.dependencies
            $('#ActivityShowModal').modal('show');
        },
        clear: function () {
            this.customer         = null
            this.tag              = null
            this.activity         = null
            this.startDate        = null
            this.dueDate          = null
            this.dateCompleted    = null
            this.completedOutDate = false
            this.estimatedHours   = null
            this.realHours        = null
            this.status           = null
            this.userStatus       = null
            this.description      = null
            this.histories        = []
            this.subActivities    = []
            this.partials         = []
            this.dependencies     = []
        }
    }
}
</script>

<style scoped>

</style>

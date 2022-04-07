<template>
    <div class="modal fade" id="PartialActivityModal" tabindex="-1" role="dialog" aria-labelledby="edit-event-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <loading :active.sync="isLoading" :is-full-page="false"></loading>
                <form class="form" @submit.prevent="save">
                    <div class="modal-body">
                        <div class="form-group row mb-0">
                            <label class="col-sm-3 col-form-label">Actividad:</label>
                            <div class="col-sm-9">
                                <label class="col col-form-label text-muted">{{name}}</label>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <label class="col-sm-3 col-form-label">Estimado:</label>
                            <div class="col-sm-3">
                                <label class="col col-form-label text-muted">{{estimated}} </label>
                            </div>
                            <label class="col-sm-3 col-form-label">Actual:</label>
                            <div class="col">
                                <label class="col col-form-label text-muted">{{duration}} </label>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <label class="col-sm-3 col-form-label">Tiempo:</label>
                            <div class="col-sm-3">
                                <input type="text"
                                       maxlength="5"
                                       placeholder="00:00"
                                       class="form-control form-control-sm"
                                       v-model="remaining">
                            </div>
                            <div class="col-sm-6">
                                <div class="custom-control custom-checkbox mb-3 mt-1">
                                    <input class="custom-control-input" id="customCheck2" type="checkbox" v-model="isPartial">
                                    <label class="custom-control-label" for="customCheck2">Parcial</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Fecha:</label>
                            <div class="col-sm-9">
                                <input type="date"
                                       class="form-control form-control-sm"
                                       v-model="date">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <button v-show="!this.isCompleted"
                                        type="submit"
                                        class="btn btn-primary btn-sm">
                                    Guardar
                                </button>
                                <button v-show="this.isAdvanced"
                                        @click="reset"
                                        type="button"
                                        class="btn btn-outline-danger btn-sm">
                                    Reiniciar
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
import EventBus from "../../../event-bus";
import axios from "axios";
import moment from "moment";

export default {
    components: { Loading },
    data() {
        return {
            isLoading   : false,
            name        : null,
            estimated   : null,
            duration    : null,
            remaining   : null,
            date        : moment().format("YYYY-MM-DD"),
            activityID  : null,
            isPartial   : false,
            isCompleted : false,
            isAdvanced  : false,
            today       : moment().format("YYYY-MM-DD"),
            errors      : [],
        }
    },
    created() {
        EventBus.$on('partial-event', data => {
            this.activityID  = data.id
            this.name        = data.activity
            this.estimated   = data.estimatedTime
            this.duration    = data.realTime
            this.remaining   = '00:00'
            this.date        = moment().format("YYYY-MM-DD")
            this.isPartial   = false
            this.isCompleted = data.currentStatus === 'completed'
            this.isAdvanced  = data.realTime !== "00:00"
        });
    },
    methods: {
        save: function () {
            if (!this.isValidTimeEstimated(this.remaining) || this.remaining === '00:00') {
                Vue.$toast.warning('¿Tiempo incorrecto');
                return;
            }

            if (! this.isPartial) {
                let isConfirmed = confirm("¿Estás seguro que terminaste la actividad?")
                if(!isConfirmed) {return false;}
            }

            if (this.today !== this.date) {
                let isConfirmed = confirm("La fecha seleccionada es diferente a la actual, se enviará a evaluación, ¿Quieres continuar?")
                if(!isConfirmed) {return false;}
            }

            axios.put(`${this.appUrl}api/users/activities/${this.activityID}/finished`,{
                duration   : this.remaining,
                is_partial : this.isPartial,
                date       : this.date,
            })
            .then(res => {
                $('#PartialActivityModal').modal('hide');
                EventBus.$emit('update-activity-event', {activity : res.data.activity, msg: res.data.msg});
            })
            .catch(error => {
                this.isLoading = false
                if (error.response.status === 401) {
                    Vue.$toast.error(error.response.data.msg);
                }
            });
        },
        reset: function () {
            let isConfirmed = confirm("Estas seguro de reiniciar esta actividad?")
            if(!isConfirmed) {return false;}
            this.isLoading = true;
            axios.put(`${this.appUrl}api/users/activities/${this.activityID}/reset-partial`)
                .then(res => {
                    this.isLoading = false
                    $('#PartialActivityModal').modal('hide');
                    EventBus.$emit('update-activity-event', {activity : res.data.activity, msg: res.data.msg});
                })
                .catch(error => {
                    this.isLoading = false
                    if (error.response.status === 401) {
                        Vue.$toast.error(error.response.data.msg);
                    }
                });
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

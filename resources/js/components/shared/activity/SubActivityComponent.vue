<template>
    <div class="modal fade" id="ModalSubActivity" tabindex="-1" role="dialog" aria-labelledby="edit-event-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <loading :active.sync="isLoading" :is-full-page="false"></loading>
                <div class="modal-body">
                    <div class="row">
                        <div class="col text-center">
                            <h4 class="">AGREGAR SUBACTIVIDADES</h4>
                        </div>
                    </div>
                    <form class="form" @submit.prevent="saveSubTask">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="activity" class="form-control-label ">Actividad</label>
                                    <input v-model="name"
                                           type="text"
                                           class="form-control form-control-sm"
                                           id="activity"
                                           required>
                                    <div v-if="errors && errors.name" class="h6 text-danger">{{ errors.name[0] }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="estHours" class="form-control-label">Horas</label>
                                    <input v-model="duration"
                                           type="text"
                                           maxlength="5"
                                           class="form-control form-control-sm"
                                           id="estHours"
                                           placeholder="00:00"
                                           required>
                                    <div v-if="errors && errors.estimated_hours" class="h6 text-danger">{{ errors.duration[0] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    Guardar
                                </button>
                            </div>
                            <div class="col text-right">
                                <button class="btn btn-sm btn-outline-default ml-auto" data-dismiss="modal">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import axios from 'axios'
import EventBus from "../../../event-bus";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    components: {
        Loading
    },
    data() {
        return {
            isLoading   : false,
            errors      : [],
            name        : null,
            duration    : null,
            activity_id : ''
        }
    },
    created() {
        EventBus.$on('subactivity-event', activity_id => {
            this.name        = ''
            this.duration    = ''
            this.activity_id = activity_id
            $('#ModalSubActivity').modal('show');
        });
    },
    methods: {
        saveSubTask: function () {
            if (!this.isValidTimeEstimated(this.duration) || this.duration === '00:00') {
                Vue.$toast.warning('Tiempo incorrecto');
                return;
            }
            this.isLoading = true

            axios.post(`${this.appUrl}api/users/activities/${this.activity_id}/sub-activity`,{
                name     : this.name,
                duration : this.duration
            }).then(res => {
                this.isLoading = false
                $('#ModalSubActivity').modal('hide');
                EventBus.$emit('update-activity-event', {activity : res.data.activity, msg: res.data.msg});
            }).catch(error => {
                this.isLoading = false
                if (error.response.status === 422){
                    this.errors = error.response.data.errors;
                }
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

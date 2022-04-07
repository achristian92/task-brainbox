<template>
    <div class="modal fade" id="ListActivitiesModal" tabindex="-1" role="dialog" aria-labelledby="edit-event-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <loading :active.sync="isLoading" :is-full-page="false"></loading>
                <div class="modal-body">
                    <div class="row">
                        <div class="col text-center">
                            <h4>Exportar actividades</h4>
                        </div>
                    </div>
                    <form class="form" @submit.prevent="handleExport">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="startActivities" class="form-control-label">Desde</label>
                                    <input type="date"
                                           class="form-control form-control-sm"
                                           id="startActivities"
                                           v-model="start_date">
                                    <div v-if="errors && errors.start_date" class="h6 text-danger">{{ errors.start_date[0] }}</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="dueActivities" class="form-control-label">Hasta</label>
                                    <input type="date"
                                           class="form-control form-control-sm"
                                           id="dueActivities"
                                           v-model="due_date">
                                    <div v-if="errors && errors.due_date" class="h6 text-danger">{{ errors.due_date[0] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-sm">Exportar</button>
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
import moment from 'moment'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    components: {
        Loading
    },
    data() {
        return {
            isLoading   : false,
            start_date  : moment().startOf('month').format('YYYY-MM-DD'),
            due_date    : moment().endOf('month').format('YYYY-MM-DD'),
            errors      : []
        }
    },
    methods: {
        handleExport: function () {
            this.isLoading = true
            axios.get(`${this.appUrl}api/reports/activities/list-activities`,{
                params: {
                    'start_date' : this.start_date,
                    'due_date'   : this.due_date,
                }
            }).then(res => {
                this.isLoading = false
                if (res.status !== 201) {
                    Vue.$toast.warning("Inténtelo de nuevo")
                    return;
                }

                this.reset()
                Vue.$toast.success(res.data.msg);
                setTimeout(function(){window.open(res.data.url)},2000);
                setTimeout(function(){$('#ListActivitiesModal').modal('hide');},4000);
            }).catch(error => {
                this.isLoading = false
                if (error.response.status === 422){
                    Vue.$toast.error("Información inválida");
                    this.errors = error.response.data.errors;
                }
                if (error.response.status === 401) {
                    Vue.$toast.error(error.response.data.msg);
                }
            });
        },
        reset: function ()
        {
            this.start_date  = moment().startOf('month').format('YYYY-MM-DD')
            this.due_date    = moment().endOf('month').format('YYYY-MM-DD')
            this.errors      = []
        }
    }
}
</script>

<style scoped>

</style>

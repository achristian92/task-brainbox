<template>
    <div class="modal fade" id="MassDestroyWorkPlanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <loading :active.sync="isLoading" :is-full-page="false"></loading>
                <div class="modal-header">
                    <h5 class="modal-title text-danger text-center" id="exampleModalLabel">ELIMINAR ACTIVIDADES PLANEADAS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mt-0">
                    <table class="table table-sm">
                        <thead>
                        <tr>
                            <th scope="col">
                                <div class="form-check">
                                    <input type="checkbox"
                                           v-model="allSelected" @click="selectAll"                                           class="form-check-input"
                                           id="exampleCheck1">
                                    <label class="form-check-label mt-1" for="exampleCheck1">Todos</label>
                                </div>
                            </th>
                            <th scope="col">Actividad</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(activity,index) in activities">
                            <td>
                                <div class="form-check">
                                    <input type="checkbox"
                                           v-model="activity.checked"
                                           @change="select_guest($event)"
                                           class="form-check-input"
                                    >
                                </div>
                            </td>
                            <td>
                                {{ activity.name }} <br>
                                <small>{{ activity.startDate }} | {{ activity.customer }} </small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit"
                            :disabled='isDisabled'
                            class="btn btn-sm btn-primary"
                            @click="destroyPlanned">Eliminar
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import axios from 'axios'
import EventBus from '../../../../event-bus';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    components: {
        Loading
    },
    data() {
        return {
            isLoading      : false,
            activities     : [],
            allSelected    : false,
            showButtonSave : false,
        }
    },
    computed: {
        isDisabled: function(){
            return !this.showButtonSave;
        }
    },
    created() {
        EventBus.$on('dataMassDestroyWorkPlan',data => {
            this.activities     = data
            this.allSelected    = false
            this.showButtonSave = false
            $('#MassDestroyWorkPlanModal').modal('show');
        })
    },

    methods: {
        selectAll : function () {
            if (this.activities.length !== 0) {
                if (!this.allSelected) {
                    this.activities.forEach(function(item) {
                        item.checked = true;
                    });
                    this.showButtonSave = true
                }else{
                    this.activities.forEach(function(item){
                        item.checked = false;
                    });
                    this.showButtonSave = false
                }
            }
        },
        select_guest : function (e) {
            this.showButtonSave = false;
            this.activities.forEach(item => {
                if(item.checked){
                    this.showButtonSave = true
                }
            });
            this.allSelected = false;
        },
        destroyPlanned: function ()
        {
            this.isLoading = true
            const destroyIDS = [];
            const cachedGuest = Object.assign({}, this.activities);
            for (let i in cachedGuest) {
                if (cachedGuest[i].checked){
                    destroyIDS.push(cachedGuest[i].id)
                }
            }

            let url = `${this.appUrl}api/activities/planned/mass-planned-status`

            axios.post(url, {
                'destroyIDS': destroyIDS
            })
                .then(res => {
                    this.isLoading = false
                    Vue.$toast.success(res.data.msg);
                    $('#MassDestroyWorkPlanModal').modal('hide');
                    EventBus.$emit('refreshEvents', {});
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

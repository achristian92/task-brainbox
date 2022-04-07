<template>
    <div class="modal fade" id="ActivityNewModal" role="dialog" aria-labelledby="edit-event-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <loading :active.sync="isLoading" :is-full-page="false"></loading>
                <form class="form" @submit.prevent="formSave">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="customer" class="form-control-label">Cliente</label>
                            <Select2 v-model="activity.customer_id"
                                     :options="customers"
                                     placeholder="Seleccionar..." required/>
<!--                            <select class="form-control form-control-sm"-->
<!--                                    id="customer"-->
<!--                                    v-model="activity.customer_id"-->
<!--                                    required>-->
<!--                                <option value="" :disable="true">Seleccionar...</option>-->
<!--                                <option v-for="customer in p_customers" :value="customer.id">{{customer.name}}</option>-->
<!--                            </select>-->
                            <div v-if="errors && errors.customer_id" class="h6 text-danger">{{ errors.customer_id[0] }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="activityplanned" class="form-control-label ">Actividad</label>
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           id="activityplanned"
                                           v-model="activity.name"
                                           required>
                                    <div v-if="errors && errors.name" class="h6 text-danger">{{ errors.name[0] }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="estHoursplanned" class="form-control-label">Tiempo</label>
                                    <input type="text"
                                           maxlength="5"
                                           class="form-control form-control-sm"
                                           id="estHoursplanned"
                                           placeholder="00:00"
                                           v-model="activity.time_real"
                                           required>
                                    <div v-if="errors && errors.time_real" class="h6 text-danger">{{ errors.time_real[0] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Etiquetas</label>
                            <Select2 v-model="activity.tag_id"
                                     :options="tags"
                                     placeholder="Seleccionar..." required/>
<!--                            <select class="form-control form-control-sm"-->
<!--                                    id="tag"-->
<!--                                    v-model="activity.tag_id">-->
<!--                                <option value="">Seleccionar...</option>-->
<!--                                <option v-for="tag in p_tags" :value="tag.id">{{tag.name}}</option>-->
<!--                            </select>-->
                            <div v-if="errors && errors.tag_id" class="h6 text-danger">{{ errors.tag_id[0] }}</div>
                        </div>
                        <div  v-show="showMore">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="start" class="form-control-label">Inicio</label>
                                        <input type="date"
                                               class="form-control form-control-sm"
                                               id="start"
                                               v-model="activity.start_date">
                                        <div v-if="errors && errors.start_date" class="h6 text-danger">{{ errors.start_date[0] }}</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="due" class="form-control-label">Fin</label>
                                        <input type="date"
                                               class="form-control form-control-sm"
                                               id="due"
                                               v-model="activity.due_date">
                                        <div v-if="errors && errors.due_date" class="h6 text-danger">{{ errors.due_date[0] }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox mb-3">
                                    <input class="custom-control-input"
                                           id="customCheck1"
                                           type="checkbox"
                                           v-model="activity.is_priority">
                                    <label class="custom-control-label" for="customCheck1">Es prioridad alta</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-control-label">Descripción</label>
                                <textarea class="form-control"
                                          id="description"
                                          rows="2"
                                          placeholder="Descripción..."
                                          v-model="activity.description2">
                                </textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-right">
                                <button type="button" class="btn btn-sm btn-link" @click="showMoreClick">{{textShowMore}}</button>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <button type="submit"
                                        class="btn btn-primary btn-sm">
                                    Guardar
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
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import Multiselect from 'vue-multiselect'
import axios from "axios";
import EventBus from "../../../event-bus";
import moment from 'moment'
import Select2 from 'v-select2-component';

export default {
    components: {
        Loading, Multiselect, Select2
    },

    data() {
        return {
            isLoading    : false,
            currentDate  : moment().format('YYYY-MM-DD'),
            showMore     : false,
            textShowMore : 'ver más',
            errors       : [],
            customers       : [],
            tags       : [],
            activity : {
                customer_id  : '',
                tag_id       : '',
                name         : '',
                time_real    : '',
                start_date   : '',
                due_date     : '',
                is_priority  : false,
                description2 : ''
            },
        }
    },
    props: ['p_customers','p_tags'],
    created() {
        if (this.p_customers) {
            this.customers = this.p_customers.map(function (i) {
                return {
                    'id' : i.id,
                    'text': i.name
                }
            })
        }

        if (this.p_tags)  {
            this.tags = this.p_tags.map(function (i) {
                return {
                    'id' : i.id,
                    'text': i.name
                }
            })
        }
        EventBus.$on('activityNew',data => this.open())
    },
    methods: {
        open() {
            this.errors = []
            this.resetActivity()
            this.showMore = false
            this.activity.start_date = this.currentDate
            this.activity.due_date   = this.currentDate
            $('#ActivityNewModal').modal('show');
        },

        formSave() {
            if (!this.isValidTimeEstimated(this.activity.time_real) || this.activity.time_real === '00:00') {
                Vue.$toast.warning('Tiempo incorrecto');
                return;
            }

            if (this.activity.tag_id === null || this.activity.tag_id === '') {
                Vue.$toast.error("La etiqueta es obligatorio");
                return false;
            }

            this.isLoading = true
            axios.post(`${this.appUrl}api/user/new-activities`,this.sendParams()).
            then(res => {
                this.isLoading = false
                if (res.status === 201) {
                    $('#ActivityNewModal').modal('hide');
                    EventBus.$emit('activity-added', {activity: res.data.activity, msg: res.data.msg})
                    this.resetActivity()
                }
            }).
            catch(error => {
                this.isLoading = false
                if (error.response.status === 422){
                    this.errors = error.response.data.errors;
                    Vue.$toast.error("Información inválida");
                }
                if (error.response.status === 401) {
                    Vue.$toast.error(error.response.data.msg);
                }
            });
        },


        sendParams() {

            return {
                customer_id  : this.activity.customer_id,
                tag_id       : this.activity.tag_id,
                name         : this.activity.name,
                time_real    : this.activity.time_real,
                start_date   : this.activity.start_date,
                due_date     : this.activity.due_date,
                is_priority  : this.activity.is_priority,
                description2 : this.activity.description2,
            }

        },

        showMoreClick() {
            if ( !this.showMore) {
                this.showMore = true
                this.textShowMore = 'ver menos'
            } else {
                this.showMore = false
                this.textShowMore = 'ver más'
            }
        },
        resetActivity() {
            this.activity = {
                customer_id   : '',
                tag_id        : '',
                name          : '',
                time_real     : '',
                start_date    : '',
                due_date      : '',
                is_priority   : false,
                description2  : ''
            }
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

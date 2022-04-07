<template>
    <div class="modal fade" id="ActivityModal"  role="dialog" aria-labelledby="edit-event-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <loading :active.sync="isLoading" :is-full-page="false"></loading>
                <form class="form" @submit.prevent="submit">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="customer" class="form-control-label">Cliente</label>
                            <Select2 v-model="activity.customer_id"
                                     :options="customers"
                                     placeholder="Seleccionar..."/>
<!--                            <select class="form-control form-control-sm"-->
<!--                                    id="customer"-->
<!--                                    v-model="activity.customer_id"-->
<!--                                    required>-->
<!--                                <option value="" :disable="true">Seleccionar...</option>-->
<!--                                <option v-for="customer in customers" :value="customer.id">{{customer.name}}</option>-->
<!--                            </select>-->
                            <div v-if="errors && errors.customer_id" class="h6 text-danger">{{ errors.customer_id[0] }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="activity" class="form-control-label ">Actividad</label>
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           id="activity"
                                           v-model="activity.name"
                                           required>
                                    <div v-if="errors && errors.name" class="h6 text-danger">{{ errors.name[0] }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="estHours" class="form-control-label">Tiempo</label>
                                    <input type="text"
                                           maxlength="5"
                                           class="form-control form-control-sm"
                                           id="estHours"
                                           placeholder="00:00"
                                           v-model="activity.time_estimate"
                                           required>
                                    <div v-if="errors && errors.time_estimate" class="h6 text-danger">{{ errors.time_estimate[0] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tag" class="form-control-label">Etiqueta</label>
                            <Select2 v-model="activity.tag_id"
                                     :options="tags"
                                     placeholder="Seleccionar..."/>
<!--                            <select class="form-control form-control-sm"-->
<!--                                    id="tag"-->
<!--                                    v-model="activity.tag_id">-->
<!--                                <option value="">Seleccionar...</option>-->
<!--                                <option v-for="tag in tags" :value="tag.id">{{tag.name}}</option>-->
<!--                            </select>-->
                            <div v-if="errors && errors.tag_id" class="h6 text-danger">{{ errors.tag_id[0] }}</div>
                        </div>
                        <div class="form-group" v-if="users.length > 0">
                            <label for="userModel" class="form-control-label">Usuario</label>
                            <Select2 v-model="activity.user_id"
                                     :options="users"
                                     placeholder="Seleccionar..."/>
<!--                            <select class="form-control form-control-sm"-->
<!--                                    id="userModel"-->
<!--                                    v-model="activity.user_id"-->
<!--                                    required>-->
<!--                                <option v-for="user in users" :value="user.id">{{user.fullName}}</option>-->
<!--                            </select>-->
                            <div v-if="errors && errors.user_id" class="h6 text-danger">{{ errors.user_id[0] }}</div>
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
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox mb-3 mt-4">
                                            <input class="custom-control-input"
                                                   id="customCheck1"
                                                   type="checkbox"
                                                   v-model="activity.is_priority">
                                            <label class="custom-control-label" for="customCheck1">Es prioridad alta</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="due" class="form-control-label">Fecha límite</label>
                                        <input type="date"
                                               class="form-control form-control-sm"
                                               id="deadline"
                                               v-model="activity.deadline">
                                        <div v-if="errors && errors.deadline" class="h6 text-danger">{{ errors.deadline[0] }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Dependencias</label>
                                <multiselect v-model="actSelected"
                                             :options="activities"
                                             :multiple="true"
                                             :close-on-select="false"
                                             :clear-on-select="false"
                                             :preserve-search="true"
                                             placeholder="Seleccionar..."
                                             label="title"
                                             track-by="title">
                                    <template slot="selection"
                                              slot-scope="{ values, search, isOpen }">
                                        <span class="multiselect__single"
                                              v-if="values.length &amp;&amp; !isOpen">
                                            {{ values.length }} seleccionado
                                        </span></template>
                                </multiselect>
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-control-label">Descripción</label>
                                <textarea class="form-control"
                                          id="description"
                                          rows="2"
                                          placeholder="Descripción..."
                                          v-model="activity.description">
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
                                <button v-show="(accion === 'save' || activity.canUpdate) || isSuperAdmin"
                                        type="submit" class="btn btn-primary btn-sm">
                                    {{ textAccion }}
                                </button>
                                <button v-show="activity.canApprove"
                                        @click="approve"
                                        type="button"
                                        class="btn btn-outline-warning btn-sm btn-icon">
                                    <span class="btn-inner--icon"><i class="ni ni-like-2"></i></span>
                                    Aprobar
                                </button>
                                <button v-show="activity.canReverse || (isSuperAdmin  && activity.canReverse && accion === 'edit')"
                                        @click="reserve"
                                        type="button"
                                        class="btn btn-outline-warning btn-sm btn-icon">
                                    <span class="btn-inner--icon"><i class="ni ni-bold-left"></i></span>
                                    Revertir
                                </button>
                            </div>
                            <div class="col text-right">
                                <button v-show="activity.canDestroy && accion === 'edit'"
                                        @click.prevent="destroy(activity.id)"
                                        class="btn btn-sm btn-outline-danger">
                                    Eliminar
                                </button>
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
import axios from 'axios'
import EventBus from '../../../event-bus';
import Multiselect from 'vue-multiselect'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import Select2 from 'v-select2-component';

export default {
    components: {
        Multiselect,
        Loading,
        Select2
    },
    data() {
        return {
            isLoading     : false,
            view          : 'calendar',
            accion        : 'save',
            textAccion    : 'Guardar',
            customers     : [],
            tags          : [],
            users         : [],
            actSelected   : [],
            activities    : [],
            showMore      : false,
            textShowMore  : 'ver más',
            errors        : [],
            activity : {
                customer_id   : '',
                tag_id        : '',
                name          : '',
                time_estimate : '',
                start_date    : '',
                due_date      : '',
                deadline      : '',
                is_priority   : false,
                description   : ''
            }
        }
    },
    props: ['c_customers','c_tags','c_users'],
    created() {
        if (this.c_customers) {
            this.customers = this.c_customers.map(function (i) {
                return {
                    'id' : i.id,
                    'text': i.name
                }
            })
        }

        if (this.c_tags)  {
            this.tags = this.c_tags.map(function (i) {
                return {
                    'id' : i.id,
                    'text': i.name
                }
            })
        }
        if (this.c_users) {
            this.users = this.c_users.map(function (i) {
                return {
                    'id' : i.id,
                    'text': i.fullName
                }
            })
        }

        EventBus.$on('newActivity',data => this.createActivity(data))
        EventBus.$on('editActivity',data => this.editActivity(data))
    },
    methods: {
        submit() {
            if (this.accion === 'save') {
                this.save()
            } else {
                this.update()
            }
        },
        save() {
            if (!this.isValidTimeEstimated(this.activity.time_estimate)  || this.activity.time_estimate === '00:00') {
                Vue.$toast.warning('Tiempo incorrecto');
                return;
            }

            if (this.activity.tag_id === null || this.activity.tag_id === '') {
                Vue.$toast.error("La etiqueta es obligatorio");
                return false;
            }

            this.isLoading = true
            axios.post(`${this.appUrl}api/user/activities`,this.sendParams()).
            then(res => {
                this.isLoading = false
                Vue.$toast.success(res.data.msg)
                this.refreshActivities(res.data.view)
                $('#ActivityModal').modal('hide');
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
        update() {
            if (!this.isValidTimeEstimated(this.activity.time_estimate) || this.activity.time_estimate === '00:00') {
                Vue.$toast.warning('Tiempo incorrecto');
                return;
            }
            this.isLoading = true
            axios.put(`${this.appUrl}api/user/activities/${this.activity.id}`,this.sendParams()).
            then(res => {
                this.isLoading = false
                Vue.$toast.success(res.data.msg)
                this.refreshActivities(res.data.view)
                $('#ActivityModal').modal('hide');
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
        approve() {
            this.isLoading = true
            axios.put(`${this.appUrl}api/user/activities/${this.activity.id}/approve`).
            then(res => {
                this.isLoading = false
                Vue.$toast.success(res.data.msg);
                this.refreshActivities(res.data.view)
                $('#ActivityModal').modal('hide');
            })
                .catch(error => {
                    this.isLoading = false
                    if (error.response.status === 401) {
                        Vue.$toast.error(error.response.data.msg);
                    }
                });
        },
        reserve() {
            this.isLoading = true
            axios.put(`${this.appUrl}api/user/activities/${this.activity.id}/reserve`)
                .then(res => {
                    this.isLoading = false
                    Vue.$toast.success(res.data.msg);
                    this.refreshActivities(res.data.view)
                    $('#ActivityModal').modal('hide');
                })
                .catch(error => {
                    this.isLoading = false
                    if (error.response.status === 401) {
                        Vue.$toast.error(error.response.data.msg);
                    }
                });
        },
        destroy(activity_id) {
            this.isLoading = true
            axios.delete(`${this.appUrl}api/user/activities/${activity_id}/destroy`)
                .then(res => {
                    this.isLoading = false
                    Vue.$toast.success(res.data.msg);
                    this.refreshActivities(res.data.view)
                    $('#ActivityModal').modal('hide');
                })
                .catch(error => {
                    this.isLoading = false
                    if (error.response.status === 401) {
                        Vue.$toast.error(error.response.data.msg);
                    }
                });
        },

        createActivity(data) {
            this.errors              = []
            this.activities          = data.activities
            this.view                = data.view
            this.accion              = 'save'
            this.textAccion          = 'Guardar'
            this.resetActivity()
            this.showMore            = false
            this.activity.start_date = data.date
            this.activity.due_date   = data.date
            this.activity.user_id    = data.userIDFilter
        },
        editActivity(data) {
            console.log(data.activity)
            this.errors       = []
            this.activities   = data.activities.filter(function(x) { return x.id !== data.activity.id; })
            this.view         = data.view
            this.accion       = 'edit'
            this.textAccion   = 'Actualizar'
            const self        = this;
            this.activity     = data.activity

            self.actSelected  = []

            if (this.activity.previous_ids.length > 0 ) {
                this.activity.previous_ids.forEach(function (id) {
                    self.actSelected.push(self.activities.filter(x => x.id === id)[0]);
                })
            }
        },
        sendParams() {
            let act_ids = this.actSelected.map(function(a) {return a.id;});

            return {
                customer_id   : this.activity.customer_id,
                user_id       : this.activity.user_id,
                name          : this.activity.name,
                tag_id        : this.activity.tag_id,
                time_estimate : this.activity.time_estimate,
                start_date    : this.activity.start_date,
                due_date      : this.activity.due_date,
                deadline      : this.activity.deadline,
                description   : this.activity.description,
                is_priority   : this.activity.is_priority,
                previous      : act_ids,
                view          : this.view
            }

        },
        refreshActivities(view) {
            if (view === 'calendar') {
                EventBus.$emit('refreshEvents', {});
            } else {
                EventBus.$emit('refreshList', {});
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
            this.actSelected  = []
            this.activity = {
                customer_id   : '',
                tag_id        : '',
                name          : '',
                time_estimate : '',
                start_date    : '',
                due_date      : '',
                deadline      : '',
                is_priority   : false,
                description   : ''
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

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>


<style>
.multiselect__tag {
    background-color: #bfbfbf;
}
.multiselect__option--highlight {
    background: #bfbfbf;
}

.multiselect__content {
    background: #f5f5f5;
}
</style>

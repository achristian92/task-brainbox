<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6"><h3 class="mb-0">Seguimiento de Usuarios [{{ users.length }}]</h3></div>
                        <div class="col-md-6 text-right" >
                            <div class="btn-group dropleft">
                                <button type="button"
                                        class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false">
                                    <i class="fas fa-calendar-alt mr-1 ml-1"></i>
                                    {{ this.date.month }}
                                </button>
                                <div class="dropdown-menu">
                                    <month-picker
                                        ref="monthpick"
                                        lang="es"
                                        :default-month=currentMonth
                                        @change="handleMonthChange">
                                    </month-picker>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive table-size-12">
                        <table class="table align-items-center table-flush border-bottom-0" id="dtTracks">
                            <thead class="thead-light">
                            <tr>
                                <th>
                                    Usuario
                                    <a href="#" @click.prevent="sortBy('name')"><i class="fas fa-sort"></i></a>
                                </th>
                                <th class="text-center">
                                    Vencidos
                                    <a href="#" @click.prevent="sortBy('qtyOverdue')"><i class="fas fa-sort"></i></a>
                                </th>
                                <th class="text-center">Completados</th>
                                <th class="text-center">Estimado/Real</th>
                                <th class="text-center">
                                    Progreso
                                    <a href="#" @click.prevent="sortBy('progress')"><i class="fas fa-sort"></i></a>
                                </th>
                                <th class="text-center">
                                    Desempeño
                                    <a href="#" @click.prevent="sortBy('performanceRaw')"><i class="fas fa-sort"></i></a>
                                </th>
                                <th class="text-right">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="user in sortedUsers">
                                <td><h5 class="mb-0"><a href="#" @click="show(user.id)">{{ user.name }}</a></h5></td>
                                <td class="text-right text-danger">
                                    <strong v-if="user.qtyOverdue > 0">{{ user.qtyOverdue }}</strong>
                                </td>
                                <td class="text-right">
                                      <span class="text-success">
                                          <i class="ni ni-check-bold mr-1"></i>
                                          <strong>{{ user.qtyCompleted }}</strong>
                                      </span> /
                                    <span class="text-primary"><strong>{{ user.total }}</strong></span>
                                </td>
                                <td class="text-right">
                                    <i class="far fa-clock mr-1"></i>
                                    <strong>{{ user.estimatedTime }}</strong> / <strong>{{ user.realTime }}</strong>
                                </td>
                                <td class="text-right">
                                    <div class="d-flex align-items-center">
                                        <span class="completion mr-2">{{ user.progress }}%</span>
                                        <div>
                                            <div class="progress">
                                                <div class="progress-bar" :class="user.bgProgress" role="progressbar" aria-valuemin="0" aria-valuemax="100" :style="{width: user.progress+'%'}"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <i v-if="user.hoursWorked > hoursMonth" class="fas fa-arrow-up text-primary mr-1"></i>
                                    <i v-if="user.hoursWorked === hoursMonth" class="fas fa-check text-success mr-1"></i>
                                    <i  v-if="user.hoursWorked === 0" class="fas fa-exclamation-triangle text-warning mr-1"></i>
                                    <strong>{{ user.performance }}%</strong>
                                </td>
                                <td class="text-right">
                                    <a href="#" @click="show(user.id)">
                                        <span class="text-primary"><i class="far fa-eye mr-2"></i></span>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import Loading from "vue-loading-overlay";
import Multiselect from "vue-multiselect";
import {MonthPicker} from "vue-month-picker";
import moment from "moment";
import axios from "axios";

export default {
    components: {
        Loading,
        Multiselect,
        MonthPicker
    },
    data() {
        return {
            isLoading     : false,
            sort: {
                key: '',
                isAsc: false
            },
            users: [],
            hoursMonth: '',
            date : {
                from  : null,
                to    : null,
                month : null,
                year  : null,
            },
            currentMonth  : parseInt(moment().format("MM")),
            yearAndMonth  : moment().startOf('month').format('YYYY-MM'),
            firstLoadPage : true,
        }
    },
    created() {
        this.getInfo()
    },
    computed: {
        sortedUsers () {
            const list = this.users.slice();
            if (!!this.sort.key) {
                list.sort((a, b) => {
                    a = a[this.sort.key]
                    b = b[this.sort.key]

                    return (a === b ? 0 : a > b ? 1 : -1) * (this.sort.isAsc ? 1 : -1)
                });
            }

            return list;
        }
    },
    methods: {
        sortedClass (key) {
            return this.sort.key === key ? `sorted ${this.sort.isAsc ? 'asc' : 'desc' }` : '';
        },
        sortBy (key) {
            console.log("entro"+key)
            this.sort.isAsc = this.sort.key === key ? !this.sort.isAsc : false;
            this.sort.key = key;
        },
        show: function (id) {
            window.location.href = `${this.appUrl}admin/tracks/${id}`;
        },
        getInfo: function () {
            this.isLoading = true
            axios.get(`${this.appUrl}api/tracks`,{
                params: {
                    yearAndMonth : this.yearAndMonth,
                }
            })
                .then(res => {
                    this.isLoading = false
                    this.users = res.data.users
                    this.hoursMonth = res.data.hoursMonth
                    console.log(res.data.users)
                })
                .catch(error => {
                    this.isLoading = false
                    if (error.response.status === 401) {
                        Vue.$toast.error(error.response.data.msg);
                    }
                });
        },
        handleMonthChange (date) {
            this.date = date
            this.yearAndMonth =  moment(date.from).format('YYYY-MM')
            if (this.firstLoadPage) {
                this.firstLoadPage = false;
                return;
            }
            this.getInfo()
        },
    }

}
</script>

<style scoped>

</style>

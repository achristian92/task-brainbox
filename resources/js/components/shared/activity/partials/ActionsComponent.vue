<template>
    <div class="row mt-1">
        <loading :active.sync="isLoading" :is-full-page="false"></loading>
        <div class="col text-right d-inline">
            <a href=""
               @click.prevent="importWorkPlan"
               class="btn btn-sm btn-outline-secondary btn-icon">
                <span class="btn-inner--icon">
                    <i class="fas fa-upload"></i>
                </span>
                Importar
            </a>
            <a href=""
               @click.prevent="massDelete"
               class="btn btn-sm btn-outline-secondary btn-icon">
                <span class="btn-inner--icon">
                    <i class="fas fa-trash"></i>
                </span>
                Eliminar
            </a>
            <a href=""
               @click.prevent="handleExport"
               class="btn btn-sm btn-outline-secondary btn-icon" >
                <span class="btn-inner--icon">
                    <i class="fas fa-download"></i>
                </span>
                Exportar
            </a>
            <a href=""
               @click.prevent="handleExportDays"
               class="btn btn-sm btn-outline-secondary btn-icon" >
                <span class="btn-inner--icon">
                    <i class="fas fa-download"></i>
                </span>
                Exportar - dias
            </a>
            <a href=""
               @click.prevent="handleDuplicate"
               class="btn btn-sm btn-outline-secondary btn-icon" >
                <span class="btn-inner--icon">
                    <i class="fas fa-copy"></i>
                </span>
                Duplicar
            </a>
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
                        :lang=selectedLang
                        :default-month=currentMonth
                        @change="handleMonthChange" ></month-picker>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import { MonthPicker } from 'vue-month-picker'
import moment from 'moment'
import axios from "axios";
import EventBus from "../../../../event-bus";

export default {
    components: {
        Loading,
        MonthPicker
    },
    data() {
        return {
            isLoading     : false,
            monthSelected : '',
            firstLoadPage : true,
            selectedLang  : 'es',
            currentMonth  : parseInt(moment().format("MM")),
            date : {
                from  : null,
                to    : null,
                month : null,
                year  : null,
            },
        }
    },
    props: {
        user_id : '',
        view    : '',
    },
    created() {
        EventBus.$on('resetMonthPicker',data => {
            this.$refs.monthpick.selectMonth(this.currentMonth-1)// january begin with 0
        })
    },
    methods: {
        importWorkPlan() {
            EventBus.$emit('importFrom', {view:this.view});
            $('#ImportWorkPlanModal').modal('show');
        },
        massDelete() {
            this.isLoading = true
            let url = `${this.appUrl}api/users/${this.user_id}/planned/planned-status`
            axios.get(url , {
                params: {
                    filter_month: this.date.monthIndex,
                    filter_year: this.date.year,
                }
            })
                .then(res => {
                    this.isLoading = false
                    EventBus.$emit('dataMassDestroyWorkPlan', res.data.activities);
                })
                .catch (error => {
                    this.isLoading = false
                    if (error.response.status === 401) {
                        Vue.$toast.error(error.response.data.msg);
                    }
                })
        },
        handleExport(e) {
            e.preventDefault()
            Vue.$toast.success("Descargando...");
            let yearAndMonth =  moment(this.date.from).format('YYYY-MM-DD')
            window.location =  `${this.appUrl}api/users/${this.user_id}/planned/export-list?yearAndMonth=${yearAndMonth}`
        },
        handleExportDays (e) {
            e.preventDefault()
            Vue.$toast.success("Descargando...");
            let yearAndMonth =  moment(this.date.from).format('YYYY-MM-DD')
            window.location =  `${this.appUrl}api/users/${this.user_id}/planned/export-day?yearAndMonth=${yearAndMonth}`
        },
        handleDuplicate() {
            $('#DuplicateWorkPlanModal').modal('show');
        },
        handleMonthChange (date) {
            this.date = date
            let dateFormat =  moment(date.from).format('YYYY-MM-DD')
            if (this.firstLoadPage) {
                this.firstLoadPage = false;
                return;
            }
            this.monthSelected =  this.date.monthIndex
            EventBus.$emit('changeMonth', {dateFormat:dateFormat});
        }

    }
}
</script>

<style scoped>

</style>

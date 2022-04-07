<template>
    <div class="card">
        <loading :active.sync="isLoading" :is-full-page="false"></loading>
        <div class="col-md-12 mt-3">
            <div class="m-b-md">
                <div class="btn-group dropleft float-right">
                    <button type="button"
                            class="btn btn-white btn-sm btn-xs float-right dropdown-toggle"
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
                <h3 class="ml-3">
                    {{ this.userName }}
                </h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <dl class="row mb-0">
                        <div class="col-sm-4 text-sm-right">
                            <dt>Estimado:</dt>
                        </div>
                        <div class="col-sm-8 text-sm-left">
                            <dd class="mb-1"> {{ timeWorked }} </dd>
                        </div>
                    </dl>
                </div>
                <div class="col-lg-6" id="cluster_info">
                    <dl class="row mb-0">
                        <div class="col-sm-4 text-sm-right">
                            <dt>Real:</dt>
                        </div>
                        <div class="col-sm-8 text-sm-left">
                            <dd class="mb-1"> {{ timeReal }} </dd>
                        </div>
                    </dl>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="row justify-content-center">
                        <div class="col-md-9">
                            <progress-fusionchart :progress="progress"></progress-fusionchart>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <resume-fusionchart :resume="resume"></resume-fusionchart>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <table-imbox :p_activities="activities" v-show="activities.length > 0"></table-imbox>
                </div>
            </div>
        </div>
        <activity-show-component></activity-show-component>
    </div>
</template>
<script>

import axios from 'axios'
import Loading from 'vue-loading-overlay'
import { MonthPicker } from 'vue-month-picker'
import moment from 'moment'


export default {
    components: {
        Loading, MonthPicker
    },
    data() {
        return {
            isLoading     : false,
            user_id       : null,
            userName      : null,
            timeWorked    : null,
            timeReal      : null,
            progress      : 0,
            resume        : {},
            activities    : [],
            currentMonth  : parseInt(moment().format("MM")),
            yearAndMonth  : '',
            firstLoadPage : true,
            selectedLang  : 'es',
            date : {
                from  : null,
                to    : null,
                month : null,
                year  : null,
            },

        }
    },
    props: ['c_user_id'],
    created() {
        if (this.c_user_id) this.user_id = this.c_user_id;
        this.getTrackUserShow();
    },
    methods: {
        getTrackUserShow() {
            this.isLoading = true
            axios.get(`${this.appUrl}api/track/users/${this.user_id}`,{
                params:{
                    yearAndMonth : this.yearAndMonth,
                }
            })
                .then(res => {
                    this.isLoading  = false
                    this.userName   = res.data.user
                    this.timeWorked = res.data.timeWorked
                    this.timeReal   = res.data.timeReal
                    this.activities = res.data.activities
                    this.progress   = res.data.progress
                    this.resume     = res.data.resume
                }).catch (error => {
                this.isLoading = false
                if (error.response.status === 401) {
                    Vue.$toast.error(error.response.data.msg);
                }
            })
        },
        handleMonthChange (date) {
            this.date = date
            if (this.firstLoadPage) {
                this.firstLoadPage = false;
                return;
            }
            this.yearAndMonth =  moment(date.from).format('YYYY-MM-DD')
            this.getTrackUserShow()
        },

    }
}

</script>

<style scoped>

</style>

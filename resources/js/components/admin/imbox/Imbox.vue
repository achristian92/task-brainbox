<template>
    <div class="row">
        <div class="col-md-12 mt-3 mb-2">
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
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="tabs-container">
                    <tab-imbox></tab-imbox>
                    <div class="tab-content">
                        <div role="tabpanel" id="tab-1" class="tab-pane active show">
                            <div class="row">
                                <div class="col-md-12">
                                    <TableImbox :p_activities="activities"></TableImbox>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" id="tab-2" class="tab-pane">
                            <div class="row">
                                <div class="col-md-12">
                                    <TableImbox :p_activities="activities"></TableImbox>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" id="tab-3" class="tab-pane">
                            <div class="row">
                                <div class="col-md-12">
                                    <TableImbox :p_activities="activities"></TableImbox>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" id="tab-4" class="tab-pane">
                            <div class="row">
                                <div class="col-md-12">
                                    <TableImboxApproved :p_activities="activities"></TableImboxApproved>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <activity-show-component></activity-show-component>
    </div>
</template>

<script>
import EventBus from "../../../event-bus";
import Loading from "vue-loading-overlay";
import {MonthPicker} from "vue-month-picker";
import moment from "moment";

export default {
    components: {
        Loading, MonthPicker
    },
    data() {
        return {
            activities: [],
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
    created() {
        EventBus.$on('refreshImbox', data => {
            this.activities = data.activities
        });
    },
    methods: {
        handleMonthChange (date) {
            this.date = date
            if (this.firstLoadPage) {
                this.firstLoadPage = false;
                return;
            }
            this.yearAndMonth =  moment(date.from).format('YYYY-MM-DD')
            console.log(this.yearAndMonth)
            EventBus.$emit('refreshImboxByMonth', {'yearAndMonth':this.yearAndMonth});
        },
    }

}
</script>

<style scoped>

</style>

<template>
    <ul class="nav nav-tabs" role="tablist">
        <loading :active.sync="isLoading" :is-full-page="false"></loading>
        <li>
            <a  @click="clickTabToday"
                class="nav-link active show"
                data-toggle="tab"
                href="#tab-1">
                <span class="h4"><i class="fas fa-calendar-day mr-1"></i> Hoy</span>
            </a>
        </li>
        <li>
            <a  @click="clickTabProximate"
                class="nav-link"
                data-toggle="tab"
                href="#tab-2">
                <span class="h4"><i class="far fa-arrow-alt-circle-right mr-1"></i> Próximas</span>
            </a>
        </li>
        <li>
            <a  @click="clickTabOverdue"
                class="nav-link"
                data-toggle="tab"
                href="#tab-3">
                <span class="h4 text-danger"><i class="fas fa-exclamation-triangle mr-1"></i> Vencidas</span>
            </a>
        </li>
        <li v-if="this.currentUser.is_admin">
            <a  @click="clickTabEvaluation"
                class="nav-link"
                data-toggle="tab"
                href="#tab-4">
                <span class="h4 text-primary"><i class="far fa-thumbs-up mr-1"></i> Aprobar/Rechazar</span>
            </a>
        </li>
    </ul>
</template>

<script>
import axios from "axios";
import moment from 'moment'
import EventBus from "../../../event-bus";
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'

export default {
    components: {
        Loading
    },
    data() {
        return {
            isLoading     : false,
            currentMonth  : parseInt(moment().format("MM")),
            selectedMonth : parseInt(moment().format("MM")),
            yearAndMonth  : '',
            msg           : null,
            typeTab       : null,
        }
    },
    props: [
        'myImboxId'
    ],
    created() {
        this.clickTabToday()

        EventBus.$on('refreshImboxByMonth', data => {
            this.yearAndMonth = data.yearAndMonth
            this.getImbox()
        });
        console.log(this.myImboxId)
        console.log(this.currentUser)
    },
    methods: {
        getImbox() {
            this.isLoading = true
            axios.get(`${this.appUrl}api/counter/imbox`, {
                params: this.sendParams()
            })
            .then(res => {
            this.isLoading = false
            EventBus.$emit('refreshImbox', {activities: res.data.activities, typeTab: this.typeTab});
            Vue.$toast.success(this.msg);
            })
            .catch(error => {
                this.isLoading = false
                if (error.response.status === 401) {
                    Vue.$toast.error(error.response.data.msg);
                }
            });
        },
        clickTabToday() {
            this.msg           = 'Actividades de hoy'
            this.typeTab       = 'today'
            //this.selectedMonth = this.currentMonth
            this.getImbox()
        },
        clickTabProximate() {
            this.msg           = 'Actividades próximas'
            this.typeTab       = "proximate"
            //this.selectedMonth = this.currentMonth
            this.getImbox()
        },
        clickTabOverdue() {
            this.msg           = 'Actividades vencidas'
            this.typeTab       = "overdue"
            //this.selectedMonth = this.currentMonth
            this.getImbox()
        },
        clickTabEvaluation() {
            this.msg           = 'Actividades por aprobar cambio de fecha'
            this.typeTab       = "evaluation"
            //this.selectedMonth = this.currentMonth
            this.getImbox()
        },
        sendParams() {
            return {
                'myImboxId' : this.myImboxId,
                   'typeTab': this.typeTab,
              'yearAndMonth': this.yearAndMonth
            }
        }
    }
}
</script>

<style scoped>

</style>

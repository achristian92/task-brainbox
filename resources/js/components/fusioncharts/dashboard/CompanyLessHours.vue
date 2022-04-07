<template>
    <div class="div">
        <fusioncharts
            :type="type"
            :width="width"
            :height="height"
            :dataFormat="dataFormat"
            :dataSource="dataSource"
        ></fusioncharts>
    </div>
</template>

<script>
import moment from "moment";
moment.locale('es')

export default {
    data() {
        return {
            type       : "column2d",
            width      : "100%",
            height     : "400",
            dataFormat : "json",
            dataSource : {},
        }
    },
    props: ['totalHours','p_total_hours'],
    created() {
        if(this.p_total_hours) {
            this.dataSource = {
                chart: {
                    caption: "Empresas con menos horas trabajadas",
                    subcaption: '(Mensual)',
                    yAxisName: "Total Horas",
                    numberSuffix: "H",
                    theme: "gammel"
                },
                data: this.p_total_hours
            }
        }
    },
    watch: {
        totalHours: function (totalHours) {
            this.dataSource = {
                chart: {
                    caption: "Empresas con menos horas trabajadas",
                    subcaption: '(Mensual)',
                    yAxisName: "Total Horas",
                    numberSuffix: "H",
                    theme: "gammel"
                },
                data: totalHours
            }
        },
    },
}
</script>

<style scoped>

</style>

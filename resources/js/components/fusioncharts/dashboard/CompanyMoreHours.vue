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
export default {
    data() {
        return {
            type: "column2d",
            width: "100%",
            height: "400",
            dataFormat: "json",
            dataSource: {},
        }
    },
    props: ['totalHours','p_total_hours'],
    created() {
        if(this.p_total_hours) {
            this.dataSource = {
                chart: {
                    caption: "Empresas con más horas trabajadas",
                    subcaption: "(Mensual)",
                    yAxisName: "Total Horas",
                    numberSuffix: "H",
                    theme: "gammel"
                },
                data: this.p_total_hours
            }
        }
    },
    watch: {
        totalHours: function (data) {
            this.dataSource = {
                chart: {
                    caption: "Empresas con más horas trabajadas",
                    subcaption: "(Mensual)",
                    yAxisName: "Total Horas",
                    numberSuffix: "H",
                    theme: "gammel"
                },
                data: data
            }
        },
    },
}
</script>

<style scoped>

</style>

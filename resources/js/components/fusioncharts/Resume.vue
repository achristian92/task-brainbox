<template>
    <div class="div">
        <fusioncharts
            :type="type"
            :width="width"
            :dataFormat="dataFormat"
            :dataSource="dataSource"
        ></fusioncharts>
    </div>
</template>

<script>

export default {
    data() {
        return {
            type: "doughnut2d",
            width: "100%",
            dataFormat: "json",
            dataSource: {
                chart: {
                    caption: "RESUMEN DE ACTIVIDADES",
                    defaultcenterlabel: "0",
                    showpercentvalues: "0",
                    theme: "fusion"
                },
                data: []
            },

        }
    },
    props: ['resume','p_resume'],
    created() {
       if(this.p_resume) {
           this.dataSource.data = [
               {
                   "label":"Pendientes",
                   "value": this.p_resume['qtyPending']
               },
               {
                   "label":"Completado",
                   "value": this.p_resume['qtyCompleted']
               },
               {
                   "label":"Vencido",
                   "value": this.p_resume['qtyOverdue']
               }
           ]
           this.dataSource.chart.defaultcenterlabel = this.p_resume['total'].toString()
       }

    },
    watch: {
        resume: function (data) {
            this.dataSource.data = [
                {
                    "label":"Pendientes",
                    "value": data['qtyPending']
                },
                {
                    "label":"Completado",
                    "value": data['qtyCompleted']
                },
                {
                    "label":"Vencido",
                    "value": data['qtyOverdue']
                }
            ]
            this.dataSource.chart.defaultcenterlabel = data['total'].toString()
        },
    },
}
</script>

<style scoped>

</style>

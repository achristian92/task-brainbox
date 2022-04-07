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
    props: ['activities'],
    data() {
        return {
            type       : "msbar3d",
            width      : "100%",
            height     : "400",
            dataFormat : "json",
            dataSource : {},
        }
    },
    watch: {
        activities: function (data) {
            this.dataSource = {
                chart: {
                    caption: "# ACTIVIDADES DE LOS CLIENTES",
                    subcaption: "Total vs Completado",
                    plottooltext: "$label tiene <b>$dataValue</b> actividades",
                    theme: "fusion"
                },
                categories: [
                    {
                        "category": data.categories
                    }
                ],
                dataset: data.dataset
            }
        }
    }
}
</script>

<style scoped>

</style>

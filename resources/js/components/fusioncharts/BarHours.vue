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
    props: ['hours'],
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
        hours: function (data) {
            this.dataSource = {
                chart: {
                    caption: "# HORAS DE LOS CLIENTES",
                    subcaption: "Estimado vs Real",
                    plottooltext: "$label tiene <b>$dataValue</b> horas",
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

<template>
    <div class="div">
        <fusioncharts
            :type="typePeriod"
            :width="width"
            :dataFormat="dataFormat"
            :dataSource="dataSource"
        ></fusioncharts>
    </div>
</template>

<script>
export default {
    props: ['line'],
    data() {
        return {
            typePeriod : "msline",
            width      : "100%",
            dataFormat : "json",
            dataSource : {},
        }
    },
    watch: {
        line: function (line) {
            this.dataSource = {
                chart: {
                    caption: "HISTORIAL HORAS MENSUAL DE LOS CLIENTES",
                    drawcrossline: "1",
                    theme: "fusion"
                },
                categories: [
                    {
                        "category": line.categories
                    }
                ],
                dataset: line.dataset
            }
        },
    }
}
</script>

<style scoped>

</style>

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

    data() {
        return {
            typePeriod : "msline",
            width      : "100%",
            dataFormat : "json",
            dataSource : {},
        }
    },
    props: ['line','p_line'],
    created() {
        if (this.p_line)
            this.dataSource = {
                chart: {
                    caption: "Tendencia por etiquetas",
                    subcaption: "(mensual)",
                    yAxisName: "Total Horas",
                    drawcrossline: "1",
                    theme: "fusion"
                },
                categories: [
                    {
                        "category": this.p_line.categories
                    }
                ],
                dataset: this.p_line.dataset
            }
    },
    watch: {
        line: function (line) {
            this.dataSource = {
                chart: {
                    caption: "Tendencia por etiquetas",
                    subcaption: "(mensual)",
                    yAxisName: "Total Horas",
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

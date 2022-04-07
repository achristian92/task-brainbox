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
            type       : "pie3d",
            width      : "100%",
            height     : "400",
            dataFormat : "json",
            dataSource : {},
        }
    },
    props: ['tagPercentage','p_tag_percentage'],
    created() {
        if (this.p_tag_percentage) {
            this.dataSource = {
                chart: {
                    caption: "División porcentual por etiquetas",
                    subcaption: "(Total Horas)",
                    showvalues: "1",
                    showpercentintooltip: "0",
                    enablemultislicing: "1",
                    theme: "fusion"
                },
                data: this.p_tag_percentage
            }
        }

    },
    watch: {
        tagPercentage: function (data) {
            this.dataSource = {
                chart: {
                    caption: "División porcentual por etiquetas",
                    subcaption: "(Total Horas)",
                    showvalues: "1",
                    showpercentintooltip: "0",
                    enablemultislicing: "1",
                    theme: "fusion"
                },
                data: data
            }
        },
    },
}
</script>

<style scoped>

</style>

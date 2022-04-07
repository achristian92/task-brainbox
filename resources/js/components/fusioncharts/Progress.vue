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
            type: "angulargauge",
            width: "100%",
            dataFormat: "json",
            dataSource:
                {
                    chart: {
                        caption: "PROGRESO",
                        lowerlimit: "0",
                        upperlimit: "100",
                        showvalue: "1",
                        numbersuffix: "%",
                        theme: "fusion",
                        showtooltip: "0"
                    },
                    colorrange: {
                        color: [
                            {
                                minvalue: "0",
                                maxvalue: "50",
                                code: "#F2726F"
                            },
                            {
                                minvalue: "50",
                                maxvalue: "75",
                                code: "#FFC533"
                            },
                            {
                                minvalue: "75",
                                maxvalue: "100",
                                code: "#62B58F"
                            }
                        ]
                    },
                    dials: {
                        dial: [
                            {
                                value: '0'
                            }
                        ]
                    }
                },

        }
    },
    props: ['progress','p_progress'],
    created() {
        if (this.p_progress)
            this.dataSource.dials.dial[0].value = this.p_progress
    },
    watch: {
        progress: function (progress) {
            this.dataSource.dials.dial[0].value = progress
        },
    }
}
</script>

<style scoped>

</style>

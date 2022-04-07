<template>
    <button @click="activityNew" type="button" class="btn btn-sm btn-primary btn-icon">
        <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>
        <span class="btn-inner--text">Nueva actividad</span>
    </button>
</template>

<script>
import EventBus from "../../../event-bus";

export default {
    name: "AddNewActivity",
    methods: {
        activityNew() {
            EventBus.$emit('activityNew', {});
        },
    }
}
</script>

<style scoped>

</style>

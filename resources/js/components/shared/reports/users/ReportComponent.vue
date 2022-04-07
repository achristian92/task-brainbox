<template>
    <div class="card">
        <div class="card-body mb-0">
            <h4 class="card-title mb-1 text-primary"><i class="fas fa-user-shield mr-2"></i> Usuario</h4>
            <hr class="mt-0 mb-2">
            <ul class="list-group list-group-flush mt-0">
                <li class="list-group-item">
                    <a href="#" data-toggle="modal" data-target="#PlannedvsRealModal">
                        <span class="text-muted h5">
                            <i class="fas fa-chevron-right mr-2"></i>
                            Planificado vs Real
                        </span>
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="#" data-toggle="modal" data-target="#TimeWorkedByCustomerModal">
                        <span class="text-muted h5">
                            <i class="fas fa-chevron-right mr-2"></i>
                            Tiempo trabajado - cliente
                        </span>
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="#"  data-toggle="modal" data-target="#TimeWorkedByDayModal">
                        <span class="text-muted h5">
                            <i class="fas fa-chevron-right mr-2"></i>
                            Tiempo trabajado - día
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    name: "ReportCounter"
}
</script>

<style scoped>

</style>

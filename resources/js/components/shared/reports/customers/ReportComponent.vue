<template>
    <div class="card">
        <div class="card-body mb-0">
            <h4 class="card-title mb-1 text-primary"><i class="fas fa-building mr-2"></i> Clientes</h4>
            <hr class="mt-0 mb-2">
            <ul class="list-group list-group-flush mt-0">
                <li class="list-group-item">
                    <a href="#" data-toggle="modal" data-target="#TimeWorkedByMonthModal">
                        <span class="text-muted h5">
                            <i class="fas fa-chevron-right mr-2"></i>
                            Tiempo trabajo - mes
                        </span>
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="#" data-toggle="modal" data-target="#ListUserWorkingModal">
                        <span class="text-muted h5">
                            <i class="fas fa-chevron-right mr-2"></i>
                            Lista usuarios - cliente
                        </span>
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="#" data-toggle="modal" data-target="#HistoryLastSixMonthModal">
                        <span class="text-muted h5">
                            <i class="fas fa-chevron-right mr-2"></i>
                            Historial de horas mensuales
                        </span>
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="#" data-toggle="modal" data-target="#TagsModal">
                        <span class="text-muted h5">
                            <i class="fas fa-chevron-right mr-2"></i>
                            Actividades por etiquetas <span class="text-success">(Nuevo)</span>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>

export default {

}
</script>

<style scoped>

</style>

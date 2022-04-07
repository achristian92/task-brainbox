<template>
    <div class="row">
        <div class="col">
            <div class="card-profile-stats d-flex justify-content-center pb-0 pt-0">
                <div>
                    <span class="heading">{{total}}</span>
                    <span class="description text-primary">
                        <i class="ni ni-world-2"></i>
                        Total
                    </span>
                </div>
                <div>
                    <span class="heading">{{planned}}</span>
                    <span class="description text-plomo">
                        <i class="ni ni-air-baloon mr-1"></i>
                        Planeados
                    </span>
                </div>
                <div>
                    <span class="heading">{{approved}}</span>
                    <span class="description text-warning">
                        <i class="ni ni-like-2 mr-1"></i>
                        Aprobados
                    </span>
                </div>
                <div>
                    <span class="heading">{{ partial }}</span>
                    <span class="description text-yellow">
                        <i class="ni ni-bell-55 mr-1"></i>
                        Parcial
                    </span>
                </div>
                <div>
                    <span class="heading">{{completed}}</span>
                    <span class="description text-success">
                        <i class="ni ni-check-bold mr-1"></i>
                        Completados
                    </span>
                </div>
                <div>
                    <span class="heading">{{timeEstimated}}</span>
                    <span class="description text-default">
                        <i class="ni ni-watch-time mr-1"></i>
                        Horas est.
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        total        : 0,
        planned      : 0,
        approved     : 0,
        partial      : 0,
        completed    : 0,
        timeEstimated: 0
    },
}
</script>

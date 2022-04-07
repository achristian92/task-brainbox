<template>
    <div class="table-responsive">
        <loading :active.sync="isLoading" :is-full-page="false"></loading>
        <table class="table align-items-center table-flush table-hover border-bottom-0" id="dtTags">
                <thead class="thead-light">
                <tr>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="list">
                <tr v-for="tag in tags">
                    <td>
                        <span class="name mb-0 text-sm">
                            <i class="ni ni-tag mr-2" :style="'color:'+ tag.color"></i>
                            {{tag.name}}
                        </span>
                    </td>
                    <td>
                        <span v-if="tag.status"  class='badge badge-success'>Activo</span>
                        <span v-else class='badge badge-danger'>Inactivo</span>
                    </td>
                    <td class="text-right">
                        <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item" href="" @click.prevent="edit(tag.id)">
                                    <i class="ni ni-ruler-pencil text-primary"></i>
                                    <span>Editar</span>
                                </a>
                                <a class="dropdown-item" href="" @click.prevent="destroy(tag.id)">
                                    <i class="fas fa-trash-alt text-danger"></i>
                                    <span>Eliminar</span>
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>

                </tbody>
            </table>
        <br>
        </div>
</template>

<script>
import axios from 'axios'
import EventBus from "../../../event-bus";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    name: "listTags",
    components: { Loading },
    data() {
        return {
            isLoading: false,
            tags: [],
        }
    },
    props: ['p_tags'],
    created() {
        if(this.p_tags)
            this.tags = this.p_tags
        EventBus.$on('newTag', data => this.addTag(data));
        EventBus.$on('updatedTag', data => this.updatedTag(data));
    },
    methods: {

        addTag(data) {
            this.tags.push(data.tag)
            Vue.$toast.success(data.msg);
        },
        updatedTag(data) {
            this.tags = this.tags.map(tag => tag.id === data.tag.id ? { ...tag, ...data.tag } : tag );
            Vue.$toast.success(data.msg);
        },
        edit(id) {
            this.isLoading = true
            axios.get(`${this.appUrl}api/admin/tags/${id}/edit`)
            .then(res => {
                this.isLoading = false
                EventBus.$emit('loadTag', {tag:res.data.tag});
                $('#ModalTag').modal('show');
            })
            .catch(error => {
                this.isLoading = false
                if (error.response.status === 401) {
                    Vue.$toast.error(error.response.data.msg);
                }
            });
        },
        destroy(id) {
            this.isLoading = true
            axios.delete(`${this.appUrl}api/admin/tags/${id}`)
            .then(res => {
                this.isLoading = false
                Vue.$toast.success(res.data.msg);
                this.getTags();
            })
            .catch(error => {
                this.isLoading = false
                if (error.response.status === 401) {
w                }
            });
        },
        getTags() {
            axios.get(`${this.appUrl}api/admin/tags`)
                .then(res => this.tags = res.data.tags)
                .catch(error => {
                    this.isLoading = false
                    if (error.response.status === 401) {
                        Vue.$toast.error(error.response.data.msg);
                    }
                });
        },
    }
}
</script>

<style scoped>

</style>

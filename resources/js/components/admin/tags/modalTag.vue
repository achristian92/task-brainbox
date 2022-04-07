<template>
    <div class="modal fade" id="ModalTag" tabindex="-1" role="dialog" aria-labelledby="edit-event-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Anadir etiqueta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form" @submit.prevent="methodTag">
                    <loading :active.sync="isLoading" :is-full-page="false"></loading>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="form-control-label" for="nametagInput">Nombre de la etiqueta</label>
                                    <input type="text" class="form-control" id="nametagInput" v-model="name" required>
                                    <div v-if="errors && errors.name" class="h6 text-danger">{{ errors.name[0] }}</div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form__field">
                                        <div class="form__input">
                                            <v-swatches v-model="color" popover-x="left" style="margin-top: 32px;"></v-swatches>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ txtAction }}</button>
                        <button type="button" class="btn btn-outline-default  ml-auto" data-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import EventBus from '../../../event-bus';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import VSwatches from 'vue-swatches'
import 'vue-swatches/dist/vue-swatches.css'


export default {
    components: { VSwatches,Loading },
    data() {
        return {
            isLoading: false,
            id: '',
            name: '',
            color: '#dde5e8',
            method: null,
            txtAction: null,
            errors: []
        }
    },
    created() {
        EventBus.$on('openTag', data => this.loadTagToCreate());
        EventBus.$on('loadTag', data => this.loadTagToEdit(data));
    },
    methods: {
        methodTag() {
            (this.method === 'save') ? this.saveTag() : this.updateTag()
        },
        saveTag() {
            this.isLoading = true
            axios.post(`${this.appUrl}api/admin/tags`,this.sendParams())
            .then(res => {
                this.isLoading = false
                if (res.status === 201) {
                    Vue.$toast.success(res.data.msg)
                    setTimeout(() => {
                        window.location.href = res.data.route;
                    }, 1000)
                }
            }).catch(error => {
                this.isLoading = false
                if (error.response.status === 422){
                    this.errors = error.response.data.errors;
                }
                if (error.response.status === 401) {
                    this.notification(error.response.data.msg,'error')
                }
            });
        },
        updateTag() {
            this.isLoading = true
            axios.put(`${this.appUrl}api/admin/tags/${this.id}`, this.sendParams()).then(res => {
                this.isLoading = false
                if (res.status === 200) {
                    EventBus.$emit('updatedTag', {tag:res.data.tag, msg:res.data.msg});
                    $('#ModalTag').modal('hide');
                    this.clear()
                }
            }).catch(error => {
                this.isLoading = false
                if (error.response.status === 422){
                    this.errors = error.response.data.errors;
                }
                if (error.response.status === 401) {
                    Vue.$toast.error(error.response.data.msg);
                }
            });
        },
        loadTagToCreate() {
            this.method = 'save'
            this.txtAction = 'Guardar'
            this.clear()
        },
        loadTagToEdit(data) {
          this.method = 'update'
          this.txtAction = 'Actualizar'
          this.id = data.tag.id
          this.name = data.tag.name
          this.color = data.tag.color
        },
        sendParams() {
            return {
                name: this.name,
                color: this.color
            }
        },
        clear() {
            this.name = ''
            this.color = '#dde5e8'
            this.errors = []
        },
    }
}
</script>

<style scoped>

</style>

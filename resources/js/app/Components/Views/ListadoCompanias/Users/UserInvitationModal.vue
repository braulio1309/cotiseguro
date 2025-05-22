<template>
    <modal :modal-id="userAndRoles.users.userModalId" :title="'Companias'" :preloader="preloader" @submit="submit"
        @close-modal="closeModal">
        <template slot="body">
            <app-overlay-loader v-if="preloader" />
            <form class="mb-0" :class="{ 'loading-opacity': preloader }" ref="form"
                :data-url='`/edit/porcinos/${inputs.id}`'>
                <div class="form-group row align-items-center">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nombre: {{ inputs.nombre }}</label>
                                    </div>
                                    <div class="form-group">
                                        <label>Raza: {{ inputs.raza }}</label>
                                    </div>
                                    <div class="form-group">
                                        <label>N° Arete: {{ inputs.n_arete }}</label>
                                    </div>
                                    <div class="form-group">
                                        <label>Fecha de Nacimiento: {{ inputs.f_nacimiento }}</label>
                                    </div>
                                    <div class="form-group">
                                        <label>Peso al Nacer (Kg): {{ inputs.peso_al_nacido }}</label>
                                    </div>
                                    <div class="form-group">
                                        <label>Sexo: {{ inputs.sexo }}</label>
                                    </div>
                                    <div class="form-group">
                                        <label>Fecha de Ingreso: {{ inputs.f_ingreso }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Etapa de Ingreso: {{ inputs.etapa_de_ingreso }}</label>
                                    </div>
                                    <div class="form-group">
                                        <label>Aptitud: {{ inputs.aptitud }}</label>
                                    </div>
                                    <div class="form-group">
                                        <label>ID Madre: {{ inputs.id_madre }}</label>
                                    </div>
                                    <div class="form-group">
                                        <label>ID Padre: {{ inputs.id_padre }}</label>
                                    </div>
                                    <div class="form-group">
                                        <label>Procede: {{ inputs.procede }}</label>
                                    </div>
                                    <div class="form-group">
                                        <label>Ubicación: {{ inputs.ubicacion }}</label>
                                    </div>
                                    <div class="form-group">
                                        <label>Lote: {{ inputs.lote }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Observaciones: {{ inputs.observaciones }}</label>
                            </div>
                        </div>

                    </div>

                </div>
            </form>
        </template>
    </modal>
</template>

<script>
import { FormMixin } from '../../../../../core/mixins/form/FormMixin.js';
import { ModalMixin } from "../../../../Mixins/ModalMixin";

import { UserAndRoleMixin } from '../Mixins/UserAndRoleMixin';

export default {
    name: "UserModal",
    mixins: [FormMixin, ModalMixin, UserAndRoleMixin],
    data() {
        return {
            preloader: false,
            inputs: {},
            modalTitle: this.$t('edit_user'),
        }
    },
    created() {
        this.inputs = this.userAndRoles.rowData;
    },
    methods: {
        submit() {
            this.save(this.inputs);
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.reLoadTable();
        },
    },
}
</script>
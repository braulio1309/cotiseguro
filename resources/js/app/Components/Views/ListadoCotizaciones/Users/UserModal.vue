<template>
  <modal :modal-id="userAndRoles.users.userModalId" :title="'Depositar'" :preloader="preloader" @submit="submit"
    @close-modal="closeModal">
    <template slot="body">
      <app-overlay-loader v-if="preloader" />
      <form class="mb-0" :class="{ 'loading-opacity': preloader }" ref="form" :data-url='`/edit/porcinos/${inputs.id}`'>
        <div class="form-group row align-items-center">
          <div class="card-body">
            <div class="form-group">
              <label for="nombre">Nombre:</label>
              <input type="text" id="nombre" class="form-control" v-model="inputs.nombre" />
            </div>
            <div class="form-group">
              <label for="raza">Raza:</label>
              <input type="text" id="raza" class="form-control" v-model="inputs.raza" />
            </div>
            <div class="form-group">
              <label for="n_arete">N° Arete:</label>a
              <input type="text" id="n_arete" class="form-control" v-model="inputs.n_arete" />
            </div>
            <div class="form-group">
              <label for="f_nacimiento">Fecha de Nacimiento:</label>
              <input type="date" id="f_nacimiento" class="form-control" v-model="inputs.f_nacimiento" />
            </div>
            <div class="form-group">
              <label for="peso_al_nacido">Peso al Nacer en Kg:</label>
              <input type="number" id="peso_al_nacido" class="form-control" v-model="inputs.peso_al_nacido" />
            </div>
            <div class="form-group">
              <label for="sexo">Sexo:</label>
              <select id="sexo" class="form-control" v-model="inputs.sexo">
                <option value="Macho">Macho</option>
                <option value="Hembra">Hembra</option>
              </select>
            </div>
            <div class="form-group">
              <label for="f_ingreso">Fecha de Ingreso:</label>
              <input type="date" id="f_ingreso" class="form-control" v-model="inputs.f_ingreso" />
            </div>
            <div class="form-group">
              <label for="etapa_de_ingreso">Etapa de Ingreso:</label>
              <input type="text" id="etapa_de_ingreso" class="form-control" v-model="inputs.etapa_de_ingreso" />
            </div>
            <div class="form-group">
              <label for="aptitud">Aptitud:</label>
              <input type="text" id="aptitud" class="form-control" v-model="inputs.aptitud" />
            </div>
            <div class="form-group">
              <label for="id_madre">ID Madre:</label>
              <input type="text" id="id_madre" class="form-control" v-model="inputs.id_madre" />
            </div>
            <div class="form-group">
              <label for="id_padre">ID Padre:</label>
              <input type="text" id="id_padre" class="form-control" v-model="inputs.id_padre" />
            </div>
            <div class="form-group">
              <label for="procede">Procede:</label>
              <input type="text" id="procede" class="form-control" v-model="inputs.procede" />
            </div>
            <div class="form-group">
              <label for="ubicacion">Ubicación:</label>
              <input type="text" id="ubicacion" class="form-control" v-model="inputs.ubicacion" />
            </div>
            <div class="form-group">
              <label for="lote">Lote:</label>
              <input type="text" id="lote" class="form-control" v-model="inputs.lote" />
            </div>
            <div class="form-group">
              <label for="observaciones">Observaciones:</label>
              <textarea id="observaciones" class="form-control" v-model="inputs.observaciones"></textarea>
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
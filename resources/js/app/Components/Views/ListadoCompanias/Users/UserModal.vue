<template>
  <modal :modal-id="userAndRoles.users.userModalId" :title="'Editar Compañía'" :preloader="preloader" @submit="submit"
    @close-modal="closeModal">
    <template slot="body">
      <app-overlay-loader v-if="preloader" />
      <form class="mb-0" :class="{ 'loading-opacity': preloader }" ref="form" :data-url="`/companias/${inputs.id}`">
        <div class="card-body">
          <!-- Nombre -->
          <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" class="form-control" v-model="inputs.nombre" />
          </div>

          <!-- Cuotas -->
          <div class="form-group">
            <label>Cuotas:</label>
            <div v-for="(cuota, index) in inputs.cuotas" :key="index" class="d-flex mb-2 align-items-center">
              <input type="number" class="form-control mr-2" v-model="cuota.valor" placeholder="Valor" />
              <input type="number" class="form-control mr-2" v-model="cuota.recargo" placeholder="Recargo" />
              <button type="button" class="btn btn-danger btn-sm" @click="removeCuota(index)">Eliminar</button>
            </div>
            <button type="button" class="btn btn-secondary btn-sm mt-2" @click="addCuota">+ Agregar cuota</button>
          </div>

          <!-- Coberturas -->
          <div class="form-group">
            <label>Coberturas:</label>
            <div v-for="(cobertura, index) in inputs.coberturas" :key="index" class="d-flex mb-2 align-items-center">
              <input type="text" class="form-control mr-2" v-model="cobertura.tipo" placeholder="Tipo" />
              <input type="number" class="form-control mr-2" v-model="cobertura.monto" placeholder="Monto" />
              <button type="button" class="btn btn-danger btn-sm" @click="removeCobertura(index)">Eliminar</button>
            </div>
            <button type="button" class="btn btn-secondary btn-sm mt-2" @click="addCobertura">+ Agregar
              cobertura</button>
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
    addCuota() {
      this.inputs.cuotas.push({ valor: '', recargo: '' });
    },
    removeCuota(index) {
      this.inputs.cuotas.splice(index, 1);
    },
    addCobertura() {
      this.inputs.coberturas.push({ tipo: '', monto: '' });
    },
    removeCobertura(index) {
      this.inputs.coberturas.splice(index, 1);
    },
    onLogoChange(e) {
      const file = e.target.files[0];
      this.inputs.logo = file;

      if (file) {
        this.inputs.logo_url = URL.createObjectURL(file);
      }
    },
  },
}
</script>
<template>
  <div class="container mt-5">
    <div class="card shadow p-4">
      <h4 class="text-center mb-4">Cotizador De Salud</h4>

      <div class="row mb-3">
        <div class="col-md-6">
          <label>Nombre de Agente</label>
          <input type="text" class="form-control" v-model="form.agente" placeholder="Miguel Granado" />
        </div>
        <div class="col-md-6">
          <label>Nombre de Titular</label>
          <input type="text" class="form-control" v-model="form.titular" />
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label><i class="bi bi-envelope"></i> Email</label>
          <input type="email" class="form-control" v-model="form.email" />
        </div>
        <div class="col-md-6">
          <label><i class="bi bi-phone"></i> Teléfono</label>
          <input type="text" class="form-control" v-model="form.telefono" />
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label><i class="bi bi-person-plus"></i> Edades</label>
          <input type="text" class="form-control" v-model="form.edades" placeholder="Ej: 32, 28, 5" />
        </div>
        <div class="col-md-3">
          <label>Compañías</label>
          <app-input class="col-sm-8" type="multi-select" :list="list" :isAnimatedDropdown="true"
            v-model="form.companias" />
        </div>
        <div class="col-md-3">
          <label>Sumas Aseguradas</label>
          <app-input class="col-sm-8" type="multi-select" :list="list2" :isAnimatedDropdown="true"
            v-model="form.sumas" />
        </div>
      </div>

      <div class="form-group">
        <label>Tipos de cotización:</label>
        <div class="row">
          <div class="col-md-2 form-check form-switch">
            <input class="form-check-input" type="checkbox" id="vida" v-model="form.vida">
            <label class="form-check-label" for="vida">Vida</label>
          </div>
          <div class="col-md-2 form-check form-switch">
            <input class="form-check-input" type="checkbox" id="funerarios" v-model="form.funerarios">
            <label class="form-check-label" for="funerarios">Funerarios</label>
          </div>
          <div class="col-md-2 form-check form-switch">
            <input class="form-check-input" type="checkbox" id="maternidad" v-model="form.maternidad">
            <label class="form-check-label" for="maternidad">Maternidad</label>
          </div>
          <div class="col-md-2 form-check form-switch">
            <input class="form-check-input" type="checkbox" id="salud" v-model="form.salud">
            <label class="form-check-label" for="salud">Salud</label>
          </div>
          <div class="col-md-2 form-check form-switch">
            <input class="form-check-input" type="checkbox" id="viajes" v-model="form.viajes">
            <label class="form-check-label" for="viajes">Viajes</label>
          </div>
        </div>
      </div>


      <!-- Submit -->
      <div class="text-end">
        <button class="btn btn-primary" @click="crearCotizacion">CREAR COTIZACIÓN</button>
      </div>
    </div>
  </div>

</template>

<script>
import { axiosPost, axiosGet } from "../../../Helpers/AxiosHelper";
import { FormMixin } from '../../../../core/mixins/form/FormMixin';

export default {
  mixins: [FormMixin],
  data() {
    return {
      step: 1,
      ubicaciones: [],
      hembras: [],
      machos: [],
      list: [],
      list2:[
        {
        id: 100000,
        value: '100000'
      },
      {
        id: 200000,
        value: '200000'
      },
      {
        id: 300000,
        value: '300000'
      },
      {
        id: 5000,
        value: '5000'
      },
      ],
      form: {
        
      },
      multiSelect: [],

    };
  },
  async mounted() {
    await this.getPorcinos();
  },
  methods: {
    async getPorcinos() {
      try {
        const [porcinosResponse] = await Promise.all([
          axios.get('/get/companias'),
        ]);

        this.list = porcinosResponse.data;

      } catch (error) {
        console.error("Error al obtener los datos:", error);
      }
    },
    calculateConversion() {
      const conversionRate = 20.58; // Tasa de conversión de ejemplo (1 USD = 20 MXN)
      if (this.form.amount) {
        return this.form.amount * conversionRate;
      } else {
        return 0;
      }
    },
    crearCotizacion() {
      const url = "cotizaciones/create";
      const data = {
        data: this.form,
      };

      axiosPost(url, data)
        .then((response) => {
          this.$toastr.s('Creado exitosamente');
          console.log("Registro exitoso", response.data);
        })
        .catch((error) => {
          console.error("Error en el depósito", error);
        });
    },
    SiguienteStep() {
      if (this.step < 3) {
        this.step++;
      }
    },
    prevStep() {
      if (this.step > 1) {
        this.step--;
      }
    },
    openDepositModal() {
      this.isModalVisible = true;
    },
    closeDepositModal() {
      this.isModalVisible = false;
    },
    reloadPage() {
      window.location.reload();
    },
    copyToClipboard(value) {
      const textarea = document.createElement("textarea");
      textarea.value = value;
      document.body.appendChild(textarea);
      textarea.select();
      document.execCommand("copy"); // Método compatible con más navegadores
      document.body.removeChild(textarea);
      alert("Copiado al portapapeles");
    },
    goToDepositView() {
      const depositData = {
        method: this.form.method,
        amount: this.form.amount,
        clabe: this.clabe,
        wallet: this.wallet
      };

      // Redirigir a la vista en Laravel con los datos
      window.open(`deposit/confirmation?method=${this.form.method}&amount=${this.form.amount}`, '_blank');


    },
    closeDepositModal() {
      this.isModalVisible = false;
    }
  },
};
</script>

<style>
.card {
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.modal-content {
  background-color: #f8f9fa;
}

.modal-dialog.modal-lg {
  max-width: 90%;
  /* Hace que el modal sea más grande */
}

.modal-header {
  background-color: #28a745;
  color: white;
}

.modal-footer {
  text-align: center;
}

.alert-success {
  text-align: center;
}

.deposit-confirmation {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background: rgba(0, 0, 0, 0.7);
}


.btn {
  margin-top: 10px;
}
</style>

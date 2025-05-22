<template>
  <div class="container mt-5">
    <div class="card">
      <div class="card-header text-center">
        <h4>Registro de Compañías de Seguros</h4>
      </div>
      <div class="card-body">
        <!-- Step Indicator -->
        <ul class="nav nav-pills nav-justified mb-4">
          <li class="nav-item">
            <button class="nav-link" :class="{ active: step === 1, disabled: step !== 1 }">
              Paso 1: Información General
            </button>
          </li>
          <li class="nav-item">
            <button class="nav-link" :class="{ active: step === 2, disabled: step !== 2 }">
              Paso 2: Cuotas y Recargos
            </button>
          </li>
          <li class="nav-item">
            <button class="nav-link" :class="{ active: step === 3, disabled: step !== 3 }">
              Paso 3: Coberturas
            </button>
          </li>
        </ul>

        <!-- Step 1: Información General -->
        <div v-if="step === 1">
          <div class="form-group">
            <label for="nombre">Nombre de la Compañía:</label>
            <input type="text" id="nombre" class="form-control" v-model="form.nombre" />
          </div>
          <div class="form-group">
            <label for="logo">Logo (URL o archivo):</label>
            <input type="text" id="logo" class="form-control" v-model="form.logo" />
            <!-- o usa <input type="file" /> si vas a subir -->
          </div>
          <button class="btn btn-primary" @click="SiguienteStep">Siguiente</button>
        </div>

        <!-- Step 2: Cuotas y Recargos -->
        <div v-if="step === 2">
          <div class="form-group" v-for="(cuota, index) in form.cuotas" :key="index">
            <label>Cuota {{ index + 1 }}:</label>
            <input type="text" class="form-control mb-2" v-model="cuota.valor" placeholder="Mensual, semestral, trimestral..." />
            <input type="number" class="form-control" v-model="cuota.recargo" placeholder="Recargo (opcional)" />
          </div>
          <button class="btn btn-sm btn-outline-secondary mt-2" @click="agregarCuota">Agregar Cuota</button>
          <br />
          <button class="btn btn-secondary mr-2 mt-3" @click="prevStep">Atrás</button>
          <button class="btn btn-primary mt-3" @click="SiguienteStep">Siguiente</button>
        </div>

        <!-- Step 3: Coberturas -->
        <div v-if="step === 3">
          <div v-for="(cobertura, index) in form.coberturas" :key="index" class="form-group">
            <label>Cobertura {{ index + 1 }}</label>
            <select class="form-control mb-2" v-model="cobertura.tipo">
              <option value="Viaje">Viaje</option>
              <option value="Funerarios">Funerarios</option>
              <option value="AMD">AMD</option>
              <option value="Maternidad">Maternidad</option>
              <option value="Vida">Vida</option>
              <option value="Edades">Edades</option>
              <!-- puedes incluir más si lo deseas -->
            </select>
            <input type="number" class="form-control" v-model="cobertura.monto" placeholder="Monto de la cobertura" />
            <label>Prima </label>

            <input type="number" class="form-control" v-model="cobertura.prima" placeholder="Monto de la prima" />
          </div>
          <button class="btn btn-sm btn-outline-secondary mt-2" @click="agregarCobertura">Agregar Cobertura</button>
          <br />
          <button class="btn btn-secondary mr-2 mt-3" @click="prevStep">Atrás</button>
          <button class="btn btn-success mt-3" @click="submitForm">Registrar Compañía</button>
        </div>
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
      form: {
        nombre: '',
        logo: '',
        cuotas: [{ valor: '', recargo: '' }],
        coberturas: [{ tipo: 'Viaje', monto: '' }]
      }
    };
  },
  async mounted() {
    await this.getPorcinos();
  },
  methods: {

    agregarCuota() {
      this.form.cuotas.push({ valor: '', recargo: '' });
    },
    agregarCobertura() {
      this.form.coberturas.push({ tipo: 'Viaje', monto: '' });
    },
    async getPorcinos() {
      try {
        const [porcinosResponse, parametrosResponse] = await Promise.all([
          axios.get('/get/porcinos'),
          axios.get('/get/parametros')
        ]);

        this.hembras = porcinosResponse.data.filter(porcino => porcino.sexo === 'Hembra');
        this.machos = porcinosResponse.data.filter(porcino => porcino.sexo === 'Macho');

        console.log("Lista de porcinos:", this.list);

        this.ubicaciones = parametrosResponse.data.filter(porcino => porcino.tipo === 'Ubicaciones');
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
    submitForm() {
      const url = "companias/create";
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

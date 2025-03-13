<template>
  <div class="container mt-5">
    <h1 class="text-center mb-4">Dashboard</h1>
    <p class="text-center mb-5">Bem-vindo ao sistema de gerenciamento de cargos e pessoas!</p>
    
    <!-- Loading Indicator -->
    <div v-if="loading" class="text-center text-muted">Carregando...</div>

    <!-- Data Summary -->
    <div v-else class="row">
      <div class="col-md-6 mb-3">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">Total de Cargos</h5>
            <p class="card-text display-4">{{ totalCargos }}</p>
          </div>
        </div>
      </div>

      <div class="col-md-6 mb-3">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">Total de Pessoas</h5>
            <p class="card-text display-4">{{ totalPessoas }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Seção de gerenciamento de vínculos -->
    <div class="vinculos-section mt-4 p-4 border rounded shadow-sm">
      <h2 class="text-center mb-4">Gerenciamento de Vínculos</h2>

    
      <div class="form-group mb-4">
        <label for="selectPessoa">Selecione uma Pessoa:</label>
        <select id="selectPessoa" v-model="selectedPessoa" @change="carregarHistorico" class="form-select">
          <option value="">Selecione uma pessoa</option>
          <option v-for="pessoa in pessoaStore.pessoas" :key="pessoa.id" :value="pessoa.id">
            {{ pessoa.nome }}
          </option>
        </select>
      </div>


      <div class="d-flex justify-content-center mb-4">
        <button @click="abrirModalNovoVinculo" :disabled="!selectedPessoa" class="btn btn-primary">
          Novo Vínculo
        </button>
      </div>

      <!-- Histórico de cargos -->
      <div v-if="cargoPessoaStore.loading" class="text-center">Carregando histórico...</div>
      <div v-else-if="cargoPessoaStore.error" class="alert alert-danger">
        {{ cargoPessoaStore.error }}
      </div>
      <table v-else class="table table-striped">
        <thead class="table-dark">
          <tr>
            <th>Cargo</th>
            <th>Data Início</th>
            <th>Data Fim</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="vinculo in cargoPessoaStore.historico" :key="vinculo.id">
            <td>{{ nomeCargo(vinculo.cargo_id) }}</td>
            <td>{{ formatarData(vinculo.data_inicio) }}</td>
            <td>{{ formatarData(vinculo.data_fim) }}</td>
            <td>
              <button @click="abrirModalEdicao(vinculo)" class="btn btn-warning btn-sm">Editar</button>
              <button @click="excluirVinculo(vinculo.id)" class="btn btn-danger btn-sm ms-2">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal para novo/editar vínculo -->
    <div v-if="showModal" class="modal fade show" tabindex="-1" style="display: block;" aria-labelledby="modalLabel" aria-hidden="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">{{ modalTitle }}</h5>
            <button type="button" class="btn-close" @click="fecharModal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="salvarVinculo">
              <div class="mb-3">
                <label for="cargoSelect" class="form-label">Cargo:</label>
                <select id="cargoSelect" v-model="vinculoEditavel.cargo_id" required class="form-select">
                  <option value="">Selecione um cargo</option>
                  <option v-for="cargo in cargoStore.cargos" :key="cargo.id" :value="cargo.id">
                    {{ cargo.nome }}
                  </option>
                </select>
              </div>

              <div class="mb-3">
                <label for="dataInicio" class="form-label">Data Início:</label>
                <input type="date" id="dataInicio" v-model="vinculoEditavel.data_inicio" required class="form-control" />
              </div>

              <div class="mb-3">
                <label for="dataFim" class="form-label">Data Fim:</label>
                <input type="date" id="dataFim" v-model="vinculoEditavel.data_fim" class="form-control" />
                <small class="form-text text-muted">Deixe em branco para cargo atual</small>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="fecharModal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useCargoStore } from '@/stores/cargoStore';
import { usePessoaStore } from '@/stores/pessoaStore';
import { useCargoPessoaStore } from '@/stores/cargoPessoaStore';
import { onMounted, ref, computed } from 'vue';

// Stores
const cargoStore = useCargoStore();
const pessoaStore = usePessoaStore();
const cargoPessoaStore = useCargoPessoaStore();

// Estados
const totalCargos = ref(0);
const totalPessoas = ref(0);
const loading = ref(true);
const selectedPessoa = ref(null);
const showModal = ref(false);
const isEditMode = ref(false);
const vinculoEditavel = ref({
  id: null,
  cargo_id: null,
  data_inicio: '',
  data_fim: null,
});


const modalTitle = computed(() => {
  return isEditMode.value ? 'Editar Vínculo' : 'Novo Vínculo';
});

// Métodos
const abrirModalNovoVinculo = () => {
  isEditMode.value = false;
  vinculoEditavel.value = {
    id: null,
    cargo_id: null,
    data_inicio: formatarDataParaInput(new Date()), // Data atual no formato YYYY-MM-DD
    data_fim: null,
  };
  showModal.value = true;
};

const abrirModalEdicao = (vinculo) => {
  isEditMode.value = true;
  vinculoEditavel.value = {
    ...vinculo,
    data_inicio: formatarDataParaInput(vinculo.data_inicio), 
    data_fim: vinculo.data_fim ? formatarDataParaInput(vinculo.data_fim) : null,
  };
  showModal.value = true;
};

const fecharModal = () => {
  showModal.value = false;
};

const salvarVinculo = async () => {
  try {
  
    const dadosParaEnviar = {
      ...vinculoEditavel.value,
      data_inicio: formatarDataParaAPI(vinculoEditavel.value.data_inicio),
      data_fim: vinculoEditavel.value.data_fim ? formatarDataParaAPI(vinculoEditavel.value.data_fim) : null,
      pessoa_id: selectedPessoa.value, 
    };

    if (isEditMode.value) {
      await cargoPessoaStore.atualizarVinculo(dadosParaEnviar.id, dadosParaEnviar);
    } else {
      // Verifica se já existe um cargo ativo
      const vinculoAtivo = cargoPessoaStore.historico.find((v) => !v.data_fim);

      if (vinculoAtivo) {
       
        await cargoPessoaStore.atualizarVinculo(vinculoAtivo.id, {
          ...vinculoAtivo,
          data_fim: formatarDataParaAPI(new Date()),
        });
      }

      // Cria o novo vínculo
      await cargoPessoaStore.criarVinculo(dadosParaEnviar);
    }

    await carregarHistorico();
    fecharModal();
  } catch (error) {
    console.error('Erro ao salvar vínculo:', error);
  }
};

const excluirVinculo = async (id) => {
  if (confirm('Tem certeza que deseja excluir este vínculo?')) {
    try {
      await cargoPessoaStore.excluirVinculo(id);
      await carregarHistorico(); 
    } catch (error) {
      console.error('Erro ao excluir vínculo:', error);
      alert('Erro ao excluir vínculo. Tente novamente.');
    }
  }
};

const carregarHistorico = async () => {
  if (selectedPessoa.value) {
    await cargoPessoaStore.buscarHistorico(selectedPessoa.value);
  }
};

const nomeCargo = (cargoId) => {
  const cargo = cargoStore.cargos.find((c) => c.id === cargoId);
  return cargo ? cargo.nome : 'Cargo não encontrado';
};

const formatarData = (data) => {
  if (!data) return '-';
  const dateObj = new Date(data);
  return dateObj.toLocaleDateString('pt-BR', { timeZone: 'UTC' });
};

const formatarDataParaInput = (data) => {

  if (!data) return '';
  const dateObj = new Date(data);
  return dateObj.toISOString().split('T')[0];
};

const formatarDataParaAPI = (data) => {

  if (!data) return null;
  const dateObj = new Date(data);
  return dateObj.toISOString().split('T')[0];
};

// Lifecycle
onMounted(async () => {
  await cargoStore.fetchCargos();
  await pessoaStore.fetchPessoas();
  totalCargos.value = cargoStore.cargos.length;
  totalPessoas.value = pessoaStore.pessoas.length;
  loading.value = false;
});
</script>


<style scoped>
.vinculos-section {
  background-color: #f8f9fa;
}

.modal-content {
  border-radius: 10px;
}

.modal-header {
  background-color: #007bff;
  color: white;
}

.error {
  color: red;
}

.btn-primary {
  background-color: #007bff;
  color: white;
}

.btn-primary:disabled {
  background-color: #cccccc;
}

.table {
  margin-top: 2rem;
  border-radius: 5px;
}

.table-striped tbody tr:nth-of-type(odd) {
  background-color: #f2f2f2;
}

.card {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style>
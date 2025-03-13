<template>
  <div>
    <h1>Dashboard</h1>
    <p>Bem-vindo ao sistema de gerenciamento de cargos e pessoas!</p>
    <div v-if="loading">Carregando...</div>
    <div v-else>
      <p>Total de Cargos: {{ totalCargos }}</p>
      <p>Total de Pessoas: {{ totalPessoas }}</p>
    </div>

    <!-- Seção de gerenciamento de vínculos -->
    <div class="vinculos-section">
      <h2>Gerenciamento de Vínculos</h2>

      <!-- Selecione uma pessoa -->
      <div class="form-group">
        <label>Selecione uma Pessoa:</label>
        <select v-model="selectedPessoa" @change="carregarHistorico">
          <option value="">Selecione uma pessoa</option>
          <option v-for="pessoa in pessoaStore.pessoas" :key="pessoa.id" :value="pessoa.id">
            {{ pessoa.nome }}
          </option>
        </select>
      </div>

      <!-- Botão para novo vínculo -->
      <button @click="abrirModalNovoVinculo" :disabled="!selectedPessoa" class="btn-primary">
        Novo Vínculo
      </button>

      <!-- Histórico de cargos -->
      <div v-if="cargoPessoaStore.loading">Carregando histórico...</div>
      <div v-else-if="cargoPessoaStore.error" class="error">
        {{ cargoPessoaStore.error }}
      </div>
      <table v-else class="historico-table">
        <thead>
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
              <button @click="abrirModalEdicao(vinculo)">Editar</button>
              <button @click="excluirVinculo(vinculo.id)">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal para novo/editar vínculo -->
    <div v-if="showModal" class="modal">
      <div class="modal-content">
        <h3>{{ modalTitle }}</h3>

        <form @submit.prevent="salvarVinculo">
          <div class="form-group">
            <label>Cargo:</label>
            <select v-model="vinculoEditavel.cargo_id" required>
              <option value="">Selecione um cargo</option>
              <option v-for="cargo in cargoStore.cargos" :key="cargo.id" :value="cargo.id">
                {{ cargo.nome }}
              </option>
            </select>
          </div>

          <div class="form-group">
            <label>Data Início:</label>
            <input type="date" v-model="vinculoEditavel.data_inicio" required />
          </div>

          <div class="form-group">
            <label>Data Fim:</label>
            <input type="date" v-model="vinculoEditavel.data_fim" />
            <small>(Deixe em branco para cargo atual)</small>
          </div>

          <div class="modal-actions">
            <button type="button" @click="fecharModal">Cancelar</button>
            <button type="submit">Salvar</button>
          </div>
        </form>
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

// Computed
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
    data_inicio: formatarDataParaInput(vinculo.data_inicio), // Garante o formato YYYY-MM-DD
    data_fim: vinculo.data_fim ? formatarDataParaInput(vinculo.data_fim) : null,
  };
  showModal.value = true;
};

const fecharModal = () => {
  showModal.value = false;
};

const salvarVinculo = async () => {
  try {
    // Garante que a data de início esteja no formato correto
    const dadosParaEnviar = {
      ...vinculoEditavel.value,
      data_inicio: formatarDataParaAPI(vinculoEditavel.value.data_inicio),
      data_fim: vinculoEditavel.value.data_fim ? formatarDataParaAPI(vinculoEditavel.value.data_fim) : null,
      pessoa_id: selectedPessoa.value, // Inclui o pessoa_id no JSON
    };

    if (isEditMode.value) {
      await cargoPessoaStore.atualizarVinculo(dadosParaEnviar.id, dadosParaEnviar);
    } else {
      // Verifica se já existe um cargo ativo
      const vinculoAtivo = cargoPessoaStore.historico.find((v) => !v.data_fim);

      if (vinculoAtivo) {
        // Encerra o vínculo anterior
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
      await carregarHistorico(); // Recarrega o histórico após a exclusão
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
  return dateObj.toLocaleDateString('pt-BR', { timeZone: 'UTC' }); // Usa UTC para evitar problemas de fuso horário
};

const formatarDataParaInput = (data) => {
  // Converte para o formato YYYY-MM-DD (usado no input type="date")
  if (!data) return '';
  const dateObj = new Date(data);
  return dateObj.toISOString().split('T')[0];
};

const formatarDataParaAPI = (data) => {
  // Converte para o formato YYYY-MM-DD (usado na API)
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
  margin-top: 2rem;
  padding: 1rem;
  border: 1px solid #ddd;
  border-radius: 8px;
}

.form-group {
  margin: 1rem 0;
}

.historico-table {
  width: 100%;
  margin-top: 1rem;
  border-collapse: collapse;
}

.historico-table th,
.historico-table td {
  padding: 0.5rem;
  border: 1px solid #ddd;
  text-align: left;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  min-width: 400px;
}

.modal-actions {
  margin-top: 1rem;
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
}

.error {
  color: red;
  margin-top: 1rem;
}

.btn-primary {
  background-color: #007bff;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-primary:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}
</style>
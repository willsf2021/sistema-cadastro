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
      <div v-else>
        <table class="table table-striped">
          <thead class="table-dark">
            <tr>
              <th>Cargo</th>
              <th>Data Início</th>
              <th>Data Fim</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="vinculo in historicoPaginado" :key="vinculo.id">
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

        <!-- Paginação -->
        <nav v-if="totalPaginas > 1" aria-label="Paginação">
          <ul class="pagination justify-content-center">
            <li class="page-item" :class="{ disabled: paginaAtual === 1 }">
              <a class="page-link" href="#" @click.prevent="paginaAnterior">Anterior</a>
            </li>
            <li class="page-item" v-for="pagina in totalPaginas" :key="pagina"
              :class="{ active: pagina === paginaAtual }">
              <a class="page-link" href="#" @click.prevent="irParaPagina(pagina)">{{ pagina }}</a>
            </li>
            <li class="page-item" :class="{ disabled: paginaAtual === totalPaginas }">
              <a class="page-link" href="#" @click.prevent="proximaPagina">Próxima</a>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <!-- Modal para novo/editar vínculo -->
    <div class="modal fade" id="modalVinculo" tabindex="-1" aria-labelledby="modalVinculoLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalVinculoLabel">{{ modalTitle }}</h5>
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
                <input type="date" id="dataInicio" v-model="vinculoEditavel.data_inicio" required
                  class="form-control" />
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

    <!-- Backdrop -->
    <div v-if="showModal" class="modal-backdrop fade show"></div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { Modal } from 'bootstrap'
import { useCargoStore } from '@/stores/cargoStore'
import { usePessoaStore } from '@/stores/pessoaStore'
import { useCargoPessoaStore } from '@/stores/cargoPessoaStore'
import { useDates } from '@/composables/useDates'
import { usePagination } from '@/composables/usePagination'
import { useValidations } from '@/composables/useValidations'

const cargoStore = useCargoStore()
const pessoaStore = usePessoaStore()
const cargoPessoaStore = useCargoPessoaStore()

// Datas
const { formatarData, formatarDataParaInput, formatarDataParaAPI } = useDates()

// Paginação
const itensPorPagina = ref(10)
const {
  paginaAtual,
  totalPaginas,
  itemsPaginados: historicoPaginado,
  irParaPagina,
  paginaAnterior,
  proximaPagina
} = usePagination(
  computed(() => cargoPessoaStore.historico),
  itensPorPagina
)

// Validações
const { validarDatas, validarSobreposicao, validarVinculoAtivo } = useValidations(computed(() => cargoPessoaStore.historico))


const totalCargos = ref(0)
const totalPessoas = ref(0)
const loading = ref(true)
const selectedPessoa = ref(null)
const showModal = ref(false)
const isEditMode = ref(false)
const vinculoEditavel = ref(createEmptyVinculo())
let modalInstance = null

// Computed
const modalTitle = computed(() => isEditMode.value ? 'Editar Vínculo' : 'Novo Vínculo')

// Métodos
function createEmptyVinculo() {
  return {
    id: null,
    cargo_id: null,
    data_inicio: formatarDataParaInput(new Date()),
    data_fim: null,
  }
}

const abrirModalNovoVinculo = () => {
  isEditMode.value = false
  vinculoEditavel.value = createEmptyVinculo()
  showModal.value = true
  modalInstance.show()
}

const abrirModalEdicao = (vinculo) => {
  isEditMode.value = true
  vinculoEditavel.value = {
    ...vinculo,
    data_inicio: formatarDataParaInput(vinculo.data_inicio),
    data_fim: vinculo.data_fim ? formatarDataParaInput(vinculo.data_fim) : null,
  }
  showModal.value = true
  modalInstance.show()
}

const fecharModal = () => {
  showModal.value = false
  modalInstance.hide()
}

const salvarVinculo = async () => {
  try {
    const dadosParaEnviar = {
      ...vinculoEditavel.value,
      data_inicio: formatarDataParaAPI(vinculoEditavel.value.data_inicio),
      data_fim: vinculoEditavel.value.data_fim ? formatarDataParaAPI(vinculoEditavel.value.data_fim) : null,
      pessoa_id: selectedPessoa.value,
    }

    // Validações
    const erroDatas = validarDatas(dadosParaEnviar.data_inicio, dadosParaEnviar.data_fim)
    if (erroDatas) {
      alert(`Erro: ${erroDatas}`)
      return
    }

    if (!isEditMode.value) {
      const erroVinculoAtivo = validarVinculoAtivo(
        computed(() => cargoPessoaStore.historico),
        dadosParaEnviar.data_fim
      )
      if (erroVinculoAtivo) {
        alert(`Erro: ${erroVinculoAtivo}`)
        return
      }
    }

    if (validarSobreposicao(dadosParaEnviar, isEditMode.value)) {
      alert('Erro: Há sobreposição de datas com outro vínculo existente.')
      return
    }

    if (isEditMode.value) {
      await cargoPessoaStore.atualizarVinculo(dadosParaEnviar.id, dadosParaEnviar)
    } else {
      await cargoPessoaStore.criarVinculo(dadosParaEnviar)
    }

    await carregarHistorico()
    fecharModal()
    alert(`Vínculo ${isEditMode.value ? 'atualizado' : 'criado'} com sucesso!`)
  } catch (error) {
    console.error('Erro ao salvar vínculo:', error)
    alert('Erro ao salvar vínculo. Verifique os dados e tente novamente.')
  }
}

const excluirVinculo = async (id) => {
  if (confirm('Tem certeza que deseja excluir este vínculo?')) {
    try {
      await cargoPessoaStore.excluirVinculo(id)
      await carregarHistorico()
      alert('Vínculo excluído com sucesso!')
    } catch (error) {
      console.error('Erro ao excluir vínculo:', error)
      alert('Erro ao excluir vínculo. Tente novamente.')
    }
  }
}

const carregarHistorico = async () => {
  if (selectedPessoa.value) {
    try {
      await cargoPessoaStore.buscarHistorico(selectedPessoa.value)
      cargoPessoaStore.historico.sort((a, b) => 
        new Date(b.data_inicio) - new Date(a.data_inicio) 
      )
      paginaAtual.value = 1
    } catch (error) {
      console.error('Erro ao carregar histórico:', error)
      alert('Erro ao carregar histórico. Tente novamente.')
    }
  }
}

const nomeCargo = (cargoId) => {
  return cargoStore.cargos.find(c => c.id === cargoId)?.nome || 'Cargo não encontrado'
}

// Lifecycle
onMounted(async () => {
  try {
    await Promise.all([
      cargoStore.fetchCargos(),
      pessoaStore.fetchPessoas()
    ])
    totalCargos.value = cargoStore.cargos.length
    totalPessoas.value = pessoaStore.pessoas.length


    modalInstance = new Modal(document.getElementById('modalVinculo'), {
      backdrop: true,
      keyboard: true
    })

    const modalElement = document.getElementById('modalVinculo')
    modalElement.addEventListener('hidden.bs.modal', () => {
      showModal.value = false
    })
    modalElement.addEventListener('shown.bs.modal', () => {
      showModal.value = true
    })
  } catch (error) {
    console.error('Erro ao carregar dados:', error)
    alert('Erro ao carregar dados. Tente novamente.')
  } finally {
    loading.value = false
  }
})

onUnmounted(() => {
  if (modalInstance) {
    modalInstance.dispose()
  }
  cargoPessoaStore.historico = []
  selectedPessoa.value = null
})
</script>

<style scoped>
.vinculos-section {
  background-color: #f8f9fa;
}

.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1040;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
  border-radius: 10px;
  z-index: 1050;
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
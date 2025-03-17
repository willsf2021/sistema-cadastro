<template>
  <div class="container-cargos-general container-fluid">
    <h1 class="text-center mb-4">Cargos</h1>

    <div class="text-center mt-4">
      <button class="btn btn-primary" @click="abrirModalGerenciamento">
        Gerenciar Cargos
      </button>
    </div>

    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <div v-if="loading" class="text-center">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Carregando...</span>
      </div>
    </div>

    <!-- Lista de Cargos -->
    <ul v-if="!loading && cargos.length" class="list-group mb-5 container-cargos">
      <li v-for="cargo in cargosPaginados" :key="cargo.id"
        class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3">
        <div class="cargo-info w-100 w-md-auto">
          <div>
            <strong>{{ cargo.nome }}</strong>
          </div>
          <div class="cargo-actions mt-3 mt-md-0">
            <i class="bi bi-gear-fill editar-icon" @click="editarCargo(cargo)"
              :style="{ backgroundColor: '#FF0000' }"></i>
          </div>
        </div>
      </li>
    </ul>

    <div v-if="!loading && !cargos.length" class="alert alert-warning">
      Nenhum cargo encontrado.
    </div>


    <!-- Paginação -->
    <div class="footer-fixo">
      <nav v-if="cargos.length > itensPorPagina" class="d-flex justify-content-center mt-4">
        <ul class="pagination">
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

    <!-- Modal de Gerenciamento -->
    <div class="modal fade" id="modalGerenciamento" tabindex="-1" aria-labelledby="modalGerenciamentoLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalGerenciamentoLabel">Gerenciamento de Cargos</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="salvarCargo" class="mb-4">
              <div class="mb-3">
                <input v-model="formCargo.nome" class="form-control" placeholder="Nome do cargo" required />
              </div>
              <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-success me-md-2">
                  {{ editando ? 'Atualizar' : 'Criar' }}
                </button>
                <button v-if="editando" @click="cancelarEdicao" class="btn btn-secondary">Cancelar</button>
              </div>
            </form>

            <ul class="list-group">
              <li v-for="cargo in cargos" :key="cargo.id"
                class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <strong>{{ cargo.nome }}</strong>
                </div>
                <button class="btn btn-sm btn-danger" @click="abrirModalExclusao(cargo)">Excluir</button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de Exclusão -->
    <div class="modal fade" id="modalExclusao" tabindex="-1" aria-labelledby="modalExclusaoLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalExclusaoLabel">Confirmar Exclusão</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p v-if="cargoVinculado">Este cargo está vinculado a uma ou mais pessoas e não pode ser excluído.</p>
            <p v-else>Deseja realmente excluir o cargo "{{ cargoSelecionado?.nome }}"?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button v-if="!cargoVinculado" type="button" class="btn btn-danger"
              @click="confirmarExclusao">Excluir</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useCargoStore } from '@/stores/cargoStore';
import { onMounted, ref, computed } from 'vue';
import { Modal } from 'bootstrap';
import { usePagination } from '@/composables/usePagination';
import { useValidations } from '@/composables/useValidations'

const cargoStore = useCargoStore();

const cargos = ref([]);
const loading = ref(false);
const error = ref(null);
const formCargo = ref({ nome: '' });
const editando = ref(false);
const cargoSelecionado = ref(null);
const cargoVinculado = ref(false);

// Paginação com composable
const itensPorPagina = ref(4);
const {
  paginaAtual,
  totalPaginas,
  itemsPaginados: cargosPaginados,
  irParaPagina,
  paginaAnterior,
  proximaPagina
} = usePagination(cargos, itensPorPagina);

// Modal instances
let modalGerenciamento = null;
let modalExclusao = null;

onMounted(async () => {
  loading.value = true;
  error.value = null;
  try {
    await cargoStore.fetchCargos();
    cargos.value = cargoStore.cargos;
  } catch (err) {
    error.value = cargoStore.error;
  } finally {
    loading.value = false;
  }

  modalGerenciamento = new Modal(document.getElementById('modalGerenciamento'));
  modalExclusao = new Modal(document.getElementById('modalExclusao'));
});

const abrirModalGerenciamento = () => {
  modalGerenciamento.show();
};

const abrirModalExclusao = async (cargo) => {
  cargoSelecionado.value = cargo;
  cargoVinculado.value = await cargoStore.verificarVinculos(cargo.id);
  modalExclusao.show();
};

const confirmarExclusao = async () => {
  if (cargoSelecionado.value) {
    await cargoStore.deleteCargo(cargoSelecionado.value.id);
    await cargoStore.fetchCargos();
    cargos.value = cargoStore.cargos;
    modalExclusao.hide();
  }
};

const salvarCargo = async () => {
  const { validarNomeCargo } = useValidations()

  const validarFormularioCargo = (cargo) => {
    const erroNome = validarNomeCargo(cargo.nome)
    if (erroNome) return erroNome
    return null
  }

  try {
    const erro = validarFormularioCargo(formCargo.value)
    if (erro) {
      alert(erro)
      return
    }
    if (editando.value) {
      await cargoStore.updateCargo(formCargo.value.id, { nome: formCargo.value.nome });
    } else {
      await cargoStore.addCargo({ nome: formCargo.value.nome });
    }

    formCargo.value = { nome: '' };
    editando.value = false;
    await cargoStore.fetchCargos();
    cargos.value = cargoStore.cargos;
  } catch (err) {
    console.error('Erro ao salvar cargo:', err);
    error.value = 'Erro ao salvar cargo. Tente novamente.';
  }
};

const editarCargo = (cargo) => {
  formCargo.value = { ...cargo };
  editando.value = true;
  abrirModalGerenciamento();
};

const cancelarEdicao = () => {
  formCargo.value = { nome: '' };
  editando.value = false;
};
</script>

<style>
.container-cargos-general {
  padding: 0px;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.container-cargos {
  flex: 1;
  display: flex;
  align-items: center;
}

.cargo-info {
  background-color: white;
  padding: 16px;
  border-radius: 8px;
  width: 100%;
  max-width: 600px;
  min-width: 600px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.cargo-actions {
  display: flex;
  align-items: center;
}

.cargo-actions i {
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2px;
  height: 40px;
  width: 40px;
  border-radius: 4px;
  cursor: pointer;
}

.editar-icon {
  font-size: 24px;
  cursor: pointer;
  margin-left: 10px;
}

.editar-icon:hover {
  transform: scale(1.03);
  transition: ease-in-out 200ms all;
}

.footer-fixo {
  position: sticky;
  bottom: 0;
  padding: 16px;
}

/* Responsividade */
@media (max-width: 768px) {
  .cargo-info {
    flex-direction: column;
    align-items: flex-start;
    min-width: 300px;
  }

  .cargo-actions {
    margin-top: 10px;
  }

  .editar-icon {
    margin-left: 0;
    margin-right: 10px;
  }
}
</style>
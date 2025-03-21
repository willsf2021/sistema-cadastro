<template>
  <div class="container-pessoas-general container-fluid">
    <h1 class="text-center mb-4">Pessoas</h1>

    <div class="text-center mt-4">
      <button class="btn btn-primary" @click="abrirModalGerenciamento">
        Gerenciar Pessoas
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

    <!-- Lista de Pessoas -->
    <ul v-if="!loading && pessoas.length" class="list-group mb-5 container-pessoas">
      <li v-for="pessoa in pessoasPaginadas" :key="pessoa.id"
        class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3">
        <div class="pessoa-info w-100 w-md-auto">
          <div>
            <strong>{{ pessoa.nome }}</strong>
            <div class="text-muted"><strong>Email: </strong>{{ pessoa.email }}</div>
            <div class="text-muted"><strong>Último cargo: </strong>{{ obterUltimoCargo(pessoa) }}</div>
          </div>
          <div class="pessoa-actions mt-3 mt-md-0">
            <i class="bi bi-eye detalhes-icon" @click="abrirModalDetalhes(pessoa)"
              :style="{ backgroundColor: '#3F861E' }"></i>
            <i class="bi bi-gear-fill editar-icon" @click="editarPessoa(pessoa)"
              :style="{ backgroundColor: '#FF0000' }"></i>
          </div>
        </div>
      </li>
    </ul>

    <div v-if="!loading && !pessoas.length" class="alert alert-warning">
      Nenhuma pessoa encontrada.
    </div>


    <!-- Paginação -->
    <div class="footer-fixo">
      <nav v-if="pessoas.length > itensPorPagina" class="d-flex justify-content-center mt-4">
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
            <h5 class="modal-title" id="modalGerenciamentoLabel">Gerenciamento de Pessoas</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="salvarPessoa" class="mb-4">
              <div class="mb-3">
                <input v-model="formPessoa.nome" class="form-control" placeholder="Nome da pessoa" required />
              </div>
              <div class="mb-3">
                <input v-model="formPessoa.email" type="email" class="form-control" placeholder="E-mail da pessoa"
                  required />
              </div>
              <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-success me-md-2">
                  {{ editando ? 'Atualizar' : 'Criar' }}
                </button>
                <button v-if="editando" @click="cancelarEdicao" class="btn btn-secondary">Cancelar</button>
              </div>
            </form>

            <ul class="list-group">
              <li v-for="pessoa in pessoas" :key="pessoa.id"
                class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <strong>{{ pessoa.nome }}</strong>
                  <div class="text-muted">{{ pessoa.email }}</div>
                </div>
                <button class="btn btn-sm btn-danger" @click="abrirModalExclusao(pessoa)">Excluir</button>
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
            <p v-if="!pessoaSelecionada?.cargos?.length">Deseja realmente excluir a pessoa "{{ pessoaSelecionada?.nome
            }}"?</p>
            <div v-else>
              <p>Esta pessoa possui vínculo com o(s) cargo(s):</p>
              <ul>
                <li v-for="cargo in pessoaSelecionada.cargos" :key="cargo.id">{{ cargo.nome }}</li>
              </ul>
              <p>Deseja realmente excluir?</p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-danger" @click="confirmarExclusao">Excluir</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de Detalhes da Pessoa -->
    <div class="modal fade" id="modalDetalhes" tabindex="-1" aria-labelledby="modalDetalhesLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalDetalhesLabel">Detalhes da Pessoa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="d-flex flex-column align-items-center">

              <img :src="pessoaSelecionada?.foto || 'https://placehold.co/150x150/add8e6/000000'" alt="Foto de Perfil"
                class="rounded-circle mb-3" style="width: 150px; height: 150px;" />


              <h4>{{ pessoaSelecionada?.nome }}</h4>
              <p class="text-muted">{{ pessoaSelecionada?.email }}</p>


              <div class="mt-3">
                <strong>Último cargo: </strong>
                <span>{{ obterUltimoCargo(pessoaSelecionada) }}</span>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { usePessoaStore } from '@/stores/pessoaStore';
import { onMounted, ref, computed, onUnmounted } from 'vue';
import { Modal } from 'bootstrap';
import { usePagination } from '@/composables/usePagination';
import { useValidations } from '@/composables/useValidations'

const pessoaStore = usePessoaStore();

const pessoas = ref([]);
const loading = ref(false);
const error = ref(null);
const formPessoa = ref({ nome: '', email: '' });
const editando = ref(false);
const pessoaSelecionada = ref(null);

// Paginação
const itensPorPagina = ref(4);
const {
  paginaAtual,
  totalPaginas,
  itemsPaginados: pessoasPaginadas,
  irParaPagina,
  paginaAnterior,
  proximaPagina
} = usePagination(pessoas, itensPorPagina);


let modalGerenciamento = null;
let modalExclusao = null;
let modalDetalhes = null;

const obterUltimoCargo = (pessoa) => {
  if (!pessoa?.cargos || !Array.isArray(pessoa.cargos) || pessoa.cargos.length === 0) {
    return "Nenhum cargo vinculado";
  }

  const cargosAtivos = pessoa.cargos.filter((cargo) => cargo?.pivot?.data_fim === null);
  if (cargosAtivos.length > 0) {
    return cargosAtivos[0]?.nome || "Cargo sem nome";
  }

  const cargosOrdenados = pessoa.cargos
    .filter((cargo) => cargo?.pivot?.data_inicio)
    .sort((a, b) => new Date(b.pivot.data_inicio) - new Date(a.pivot.data_inicio));

  return cargosOrdenados[0]?.nome || "Cargo sem nome";
};

const abrirModalDetalhes = (pessoa) => {
  pessoaSelecionada.value = pessoa;
  modalDetalhes.show();
};

const abrirModalGerenciamento = () => {
  modalGerenciamento.show();
};

const abrirModalExclusao = (pessoa) => {
  pessoaSelecionada.value = pessoa;
  modalExclusao.show();
};

const confirmarExclusao = async () => {
  if (pessoaSelecionada.value) {
    if (pessoaSelecionada.value.cargos?.length > 0) {
      const confirmacao = confirm(
        `Esta pessoa possui vínculo com o(s) cargo(s): ${pessoaSelecionada.value.cargos.map((cargo) => cargo.nome).join(', ')}. Deseja realmente excluir?`
      );
      if (!confirmacao) return;
    }

    await pessoaStore.deletePessoa(pessoaSelecionada.value.id);
    await pessoaStore.fetchPessoas();
    pessoas.value = pessoaStore.pessoas;
    modalExclusao.hide();
  }
};


const { validarNomePessoa, validarEmail } = useValidations()


const validarFormularioPessoa = (pessoa) => {
  const erroNome = validarNomePessoa(pessoa.nome)
  const erroEmail = validarEmail(pessoa.email)

  if (erroNome) return erroNome
  if (erroEmail) return erroEmail
  return null
}


const salvarPessoa = async () => {
  const erro = validarFormularioPessoa(formPessoa.value)
  if (erro) {
    alert(erro)
    return
  }
  try {
    if (editando.value) {
      await pessoaStore.updatePessoa(formPessoa.value.id, formPessoa.value);
    } else {
      await pessoaStore.addPessoa(formPessoa.value);
    }

    formPessoa.value = { nome: '', email: '' };
    editando.value = false;
    await pessoaStore.fetchPessoas();
    pessoas.value = pessoaStore.pessoas;
  } catch (err) {
    console.error('Erro ao salvar pessoa:', err);
    error.value = 'Erro ao salvar pessoa. Tente novamente.';
  }
};

const editarPessoa = (pessoa) => {
  formPessoa.value = { ...pessoa };
  editando.value = true;
  abrirModalGerenciamento();
};

const cancelarEdicao = () => {
  formPessoa.value = { nome: '', email: '' };
  editando.value = false;
};

onMounted(async () => {
  loading.value = true;
  try {
    await pessoaStore.fetchPessoas();
    pessoas.value = pessoaStore.pessoas;
  } catch (err) {
    error.value = pessoaStore.error || 'Erro ao carregar pessoas';
  } finally {
    loading.value = false;
  }

  modalGerenciamento = new Modal(document.getElementById('modalGerenciamento'));
  modalExclusao = new Modal(document.getElementById('modalExclusao'));
  modalDetalhes = new Modal(document.getElementById('modalDetalhes'));
});

onUnmounted(() => {
  pessoas.value = [];
});
</script>

<style>
.container-pessoas {
  display: flex;
  align-items: center;
}

.container-pessoas-general {
  padding: 0px;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.container-pessoas {
  flex: 1;
}

.pessoa-info {
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

.pessoa-actions {
  display: flex;
  align-items: center;
}

.pessoa-actions i {
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

.detalhes-icon,
.editar-icon {
  font-size: 24px;
  cursor: pointer;
  margin-left: 10px;
}

.editar-icon:hover {
  transform: scale(1.03);
  transition: ease-in-out 200ms all;
}

.detalhes-icon:hover {
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
  .pessoa-info {
    flex-direction: column;
    align-items: flex-start;
    min-width: 300px;
  }

  .pessoa-actions {
    margin-top: 10px;
  }

  .detalhes-icon,
  .editar-icon {
    margin-left: 0;
    margin-right: 10px;
  }
}
</style>

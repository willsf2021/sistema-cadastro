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
      <li v-for="pessoa in pessoasPaginadas" :key="pessoa.id" class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3">
        <div class="pessoa-info w-100 w-md-auto">
          <div>
            <strong>{{ pessoa.nome }}</strong>
            <div class="text-muted"><strong>Email: </strong>{{ pessoa.email }}</div>
            <div class="text-muted"><strong>Cargo: </strong>{{ "" }}</div>
          </div>
          <div class="pessoa-actions mt-3 mt-md-0">
            <i class="bi bi-eye detalhes-icon" :style="{ backgroundColor: '#3F861E' }"></i>
            <i class="bi bi-gear-fill editar-icon" @click="editarPessoa(pessoa)" :style="{ backgroundColor: '#FF0000' }"></i>
          </div>
        </div>
      </li>
    </ul>

    <div v-if="!loading && !pessoas.length" class="alert alert-warning">
      Nenhuma pessoa encontrada.
    </div>

    <!-- Paginação e Botão Gerenciar Pessoas -->
    <div class="footer-fixo">
      <!-- Paginação -->
      <nav v-if="pessoas.length > itensPorPagina" class="d-flex justify-content-center mt-4">
        <ul class="pagination">
          <li class="page-item" :class="{ disabled: paginaAtual === 1 }">
            <a class="page-link" href="#" @click.prevent="paginaAnterior">Anterior</a>
          </li>
          <li class="page-item" v-for="pagina in totalPaginas" :key="pagina" :class="{ active: pagina === paginaAtual }">
            <a class="page-link" href="#" @click.prevent="irParaPagina(pagina)">{{ pagina }}</a>
          </li>
          <li class="page-item" :class="{ disabled: paginaAtual === totalPaginas }">
            <a class="page-link" href="#" @click.prevent="proximaPagina">Próxima</a>
          </li>
        </ul>
      </nav>

    </div>

    <!-- Modal de Gerenciamento -->
    <div class="modal fade" id="modalGerenciamento" tabindex="-1" aria-labelledby="modalGerenciamentoLabel" aria-hidden="true">
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
                <input v-model="formPessoa.email" type="email" class="form-control" placeholder="E-mail da pessoa" required />
              </div>
              <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-success me-md-2">
                  {{ editando ? 'Atualizar' : 'Criar' }}
                </button>
                <button v-if="editando" @click="cancelarEdicao" class="btn btn-secondary">Cancelar</button>
              </div>
            </form>

            <ul class="list-group">
              <li v-for="pessoa in pessoas" :key="pessoa.id" class="list-group-item d-flex justify-content-between align-items-center">
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
            <p>Deseja realmente excluir a pessoa "{{ pessoaSelecionada?.nome }}"?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-danger" @click="confirmarExclusao">Excluir</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { usePessoaStore } from '@/stores/pessoaStore';
import { onMounted, ref, computed } from 'vue';
import { Modal } from 'bootstrap';

const pessoaStore = usePessoaStore();

const pessoas = ref([]);
const loading = ref(false);
const error = ref(null);
const formPessoa = ref({ nome: '', email: '' });
const editando = ref(false);

const pessoaSelecionada = ref(null);
let modalGerenciamento = null;
let modalExclusao = null;

// Paginação
const paginaAtual = ref(1);
const itensPorPagina = ref(4); // Ajuste conforme necessário

const pessoasPaginadas = computed(() => {
  const inicio = (paginaAtual.value - 1) * itensPorPagina.value;
  const fim = inicio + itensPorPagina.value;
  return pessoas.value.slice(inicio, fim);
});

const totalPaginas = computed(() => Math.ceil(pessoas.value.length / itensPorPagina.value));

const paginaAnterior = () => {
  if (paginaAtual.value > 1) paginaAtual.value--;
};

const proximaPagina = () => {
  if (paginaAtual.value < totalPaginas.value) paginaAtual.value++;
};

const irParaPagina = (pagina) => {
  paginaAtual.value = pagina;
};

onMounted(async () => {
  loading.value = true;
  error.value = null;
  try {
    await pessoaStore.fetchPessoas();
    pessoas.value = pessoaStore.pessoas;
  } catch (err) {
    error.value = pessoaStore.error;
  } finally {
    loading.value = false;
  }

  modalGerenciamento = new Modal(document.getElementById('modalGerenciamento'));
  modalExclusao = new Modal(document.getElementById('modalExclusao'));
});

const abrirModalGerenciamento = () => {
  modalGerenciamento.show();
};

const abrirModalExclusao = (pessoa) => {
  pessoaSelecionada.value = pessoa;
  modalExclusao.show();
};

const confirmarExclusao = async () => {
  if (pessoaSelecionada.value) {
    await pessoaStore.deletePessoa(pessoaSelecionada.value.id);
    await pessoaStore.fetchPessoas();
    pessoas.value = pessoaStore.pessoas;
    modalExclusao.hide();
  }
};

const salvarPessoa = async () => {
  if (editando.value) {
    await pessoaStore.updatePessoa(formPessoa.value.id, { nome: formPessoa.value.nome, email: formPessoa.value.email });
  } else {
    await pessoaStore.addPessoa({ nome: formPessoa.value.nome, email: formPessoa.value.email });
  }
  formPessoa.value = { nome: '', email: '' };
  editando.value = false;
  await pessoaStore.fetchPessoas();
  pessoas.value = pessoaStore.pessoas;
};

const editarPessoa = (pessoa) => {
  formPessoa.value = { id: pessoa.id, nome: pessoa.nome, email: pessoa.email };
  editando.value = true;
  abrirModalGerenciamento();
};

const cancelarEdicao = () => {
  formPessoa.value = { nome: '', email: '' };
  editando.value = false;
};
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
  flex: 1; /* Faz a lista ocupar o espaço restante */
}

.pessoa-info {
  background-color: white;
  padding: 16px;
  border-radius: 8px;
  width: 100%;
  max-width: 600px;
  min-width: 600px; /* Ajustado para telas menores */
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
    min-width:300px ;
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
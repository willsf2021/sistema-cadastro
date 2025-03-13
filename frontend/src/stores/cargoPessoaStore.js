import { defineStore } from 'pinia';
import cargoPessoaService from '@/services/cargoPessoaService';

export const useCargoPessoaStore = defineStore('cargoPessoa', {
  state: () => ({
    historico: [],
    loading: false,
    error: null,
  }),
  actions: {
    async criarVinculo(vinculo) {
      this.loading = true;
      this.error = null;
      try {
        const response = await cargoPessoaService.createCargoPessoa(vinculo);
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Erro ao criar vínculo';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async atualizarVinculo(id, dadosAtualizados) {
      this.loading = true;
      this.error = null;
      try {
        const response = await cargoPessoaService.updateCargoPessoa(id, dadosAtualizados);
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Erro ao atualizar vínculo';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async excluirVinculo(id) {
      this.loading = true;
      this.error = null;
      try {
        await cargoPessoaService.deleteCargoPessoa(id);
      } catch (error) {
        this.error = error.response?.data?.message || 'Erro ao excluir vínculo';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async buscarHistorico(pessoaId) {
      this.loading = true;
      this.error = null;
      try {
        const response = await cargoPessoaService.getHistoricoDeCargos(pessoaId);
        this.historico = response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Erro ao buscar histórico';
        throw error;
      } finally {
        this.loading = false;
      }
    },
  },
});

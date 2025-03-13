import { defineStore } from 'pinia';
import pessoaService from '@/services/pessoaService';

export const usePessoaStore = defineStore('pessoa', {
  state: () => ({
    pessoas: [],
    loading: false,
    error: null,
  }),
  actions: {

    async fetchPessoas() {
      this.loading = true;
      this.error = null;
      try {
        const response = await pessoaService.getPessoas();
        this.pessoas = response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Erro ao carregar pessoas';
        throw error;
      } finally {
        this.loading = false;
      }
    },


    async addPessoa(pessoa) {
      this.loading = true;
      this.error = null;
      try {
        const response = await pessoaService.createPessoa(pessoa);
        this.pessoas.push(response.data);
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Erro ao criar pessoa';
        throw error;
      } finally {
        this.loading = false;
      }
    },


    async updatePessoa(id, pessoa) {
      this.loading = true;
      this.error = null;
      try {
        await pessoaService.updatePessoa(id, pessoa);
        await this.fetchPessoas(); 
      } catch (error) {
        this.error = error.response?.data?.message || 'Erro ao atualizar pessoa';
        throw error;
      } finally {
        this.loading = false;
      }
    },


    async deletePessoa(id) {
      this.loading = true;
      this.error = null;
      try {
        await pessoaService.deletePessoa(id);
        await this.fetchPessoas();
      } catch (error) {
        this.error = error.response?.data?.message || 'Erro ao excluir pessoa';
        throw error;
      } finally {
        this.loading = false;
      }
    },
  },
});
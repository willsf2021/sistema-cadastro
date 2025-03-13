import { defineStore } from 'pinia';
import axios from 'axios';

const API_URL = 'http://localhost:8000/api';

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
        const response = await axios.post(`${API_URL}/cargo-pessoa`, vinculo);
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
        const response = await axios.put(`${API_URL}/cargo-pessoa/${id}`, dadosAtualizados);
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
        await axios.delete(`${API_URL}/cargo-pessoa/${id}`);
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
        const response = await axios.get(`${API_URL}/historico-cargo/${pessoaId}`);
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
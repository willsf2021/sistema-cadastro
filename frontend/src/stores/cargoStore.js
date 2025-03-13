import { defineStore } from 'pinia';
import cargoService from '@/services/cargoService';

export const useCargoStore = defineStore('cargo', {
  state: () => ({
    cargos: [], 
    loading: false, 
    error: null,
  }),
  actions: {

    async fetchCargos() {
      this.loading = true;
      this.error = null;
      try {
        const response = await cargoService.getCargos();
        this.cargos = response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Erro ao carregar cargos';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async verificarVinculos(id) {
      try {
        const response = await cargoService.verificarVinculos(id);
        return response.data.vinculado; 
      } catch (error) {
        this.error = error.response?.data?.message || 'Erro ao verificar vínculos';
        throw error;
      }
    },

    async addCargo(cargo) {
      this.loading = true;
      this.error = null;
      try {
        const response = await cargoService.createCargo(cargo);
        this.cargos.push(response.data);
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Erro ao criar cargo';
        throw error;
      } finally {
        this.loading = false;
      }
    },

   
    async updateCargo(id, cargo) {
      this.loading = true;
      this.error = null;
      try {
        await cargoService.updateCargo(id, cargo);
        await this.fetchCargos();
      } catch (error) {
        this.error = error.response?.data?.message || 'Erro ao atualizar cargo';
        throw error;
      } finally {
        this.loading = false;
      }
    },


    async deleteCargo(id) {
      this.loading = true;
      this.error = null;
      try {
        await cargoService.deleteCargo(id);
        await this.fetchCargos(); 
      } catch (error) {
        this.error = error.response?.data?.message || 'Erro ao excluir cargo';
        throw error;
      } finally {
        this.loading = false;
      }
    },
  },
});
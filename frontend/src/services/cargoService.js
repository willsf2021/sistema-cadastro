import axios from 'axios'

const API_URL = "http://localhost:8000/api"


export default {
    getCargos() {
        return axios.get(`${API_URL}/cargos`);
    },
    createCargo(cargo) {
        return axios.post(`${API_URL}/cargos`, cargo);
    },
    updateCargo(id, cargo) {
        return axios.put(`${API_URL}/cargos/${id}`, cargo);
    },
    deleteCargo(id) {
        return axios.delete(`${API_URL}/cargos/${id}`);
    },
    verificarVinculos(id) {
        return axios.get(`${API_URL}/cargos/${id}/vinculos`);
      },
}
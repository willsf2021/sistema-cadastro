import axios from "axios";

const API_URL = "http://localhost:8000/api";

export default {

    getHistoricoDeCargos(pessoa_id) {
        return axios.get(`${API_URL}/historico-cargo/${pessoa_id}`)
    },

    createCargoPessoa(data) {
        return axios.post(`${API_URL}/cargo-pessoa`, data);
    },

    updateCargoPessoa(id, data) {
        return axios.put(`${API_URL}/cargo-pessoa/${id}`, data);
    },

    
    deleteCargoPessoa(id) {
        return axios.delete(`${API_URL}/cargo-pessoa/${id}`);
    },

}
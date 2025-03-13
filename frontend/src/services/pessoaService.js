import axios from 'axios'

const API_URL = "http://localhost:8000/api"


export default {
    getPessoas() {
        return axios.get(`${API_URL}/pessoas`);
    },
    createPessoa(pessoa) {
        return axios.post(`${API_URL}/pessoas`, pessoa);
    },
    updatePessoa(id, pessoa) {
        return axios.put(`${API_URL}/pessoas/${id}`, pessoa);
    },
    deletePessoa(id) {
        return axios.delete(`${API_URL}/pessoas/${id}`);
    }
}

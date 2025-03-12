import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/views/Login.vue'
import Dashboard from '@/views/Dashboard.vue'
import Pessoas from '@/views/Pessoas.vue'
import Cargos from '@/views/Cargos.vue'
import Layout from "../components/Layout.vue"

const routes = [

  {
    path: '/',
    component: Login,
  },

  {

    path: '/app',
    component: Layout,
    children: [
      { path: 'dashboard', component: Dashboard },
      { path: 'pessoas', component: Pessoas },
      { path: 'cargos', component: Cargos }
    ]
  },

]

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router

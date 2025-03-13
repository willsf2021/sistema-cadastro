<template>
    <div>
      <h1>Dashboard</h1>
      <p>Bem-vindo ao sistema de gerenciamento de cargos e pessoas!</p>
      <div v-if="loading">Carregando...</div>
      <div v-else>
        <p>Total de Cargos: {{ totalCargos }}</p>
        <p>Total de Pessoas: {{ totalPessoas }}</p>
      </div>
    </div>
  </template>
  
  <script setup>
  import { useCargoStore } from '@/stores/cargoStore';
  import { usePessoaStore } from '@/stores/pessoaStore';
  import { onMounted, ref } from 'vue';
  
  const cargoStore = useCargoStore();
  const pessoaStore = usePessoaStore();
  
  const totalCargos = ref(0);
  const totalPessoas = ref(0);
  const loading = ref(true);
  
  onMounted(async () => {
    await cargoStore.fetchCargos();
    await pessoaStore.fetchPessoas();
    totalCargos.value = cargoStore.cargos.length;
    totalPessoas.value = pessoaStore.pessoas.length;
    loading.value = false;
  });
  </script>
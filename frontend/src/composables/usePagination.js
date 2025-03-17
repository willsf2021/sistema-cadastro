import { ref } from 'vue'
import { computed } from 'vue'

export const usePagination = (items, itemsPerPage) => {
  const paginaAtual = ref(1)

  const totalPaginas = computed(() => {
    return Math.ceil(items.value.length / itemsPerPage.value)
  })

  const itemsPaginados = computed(() => {
    const inicio = (paginaAtual.value - 1) * itemsPerPage.value
    const fim = inicio + itemsPerPage.value
    return items.value.slice(inicio, fim)
  })

  const irParaPagina = (pagina) => {
    paginaAtual.value = pagina
  }

  const paginaAnterior = () => {
    if (paginaAtual.value > 1) paginaAtual.value--
  }

  const proximaPagina = () => {
    if (paginaAtual.value < totalPaginas.value) paginaAtual.value++
  }

  return {
    paginaAtual,
    totalPaginas,
    itemsPaginados,
    irParaPagina,
    paginaAnterior,
    proximaPagina
  }
}
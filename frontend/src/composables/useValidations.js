// composables/useValidations.js
export const useValidations = (historico) => {

  const validarNome = (valor, campo = 'Nome', min = 3, max = 50) => {
    if (!valor) return `${campo} é obrigatório`
    if (valor.length < min) return `${campo} deve ter pelo menos ${min} caracteres`
    if (valor.length > max) return `${campo} deve ter no máximo ${max} caracteres`
    return null
  }

  const validarEmail = (email) => {
    if (!email) return 'E-mail é obrigatório'
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return 'E-mail inválido'
    return null
  }


  const validarNomeCargo = (nome) => {
    return validarNome(nome, 'Cargo', 3, 50)
  }


  const validarNomePessoa = (nome) => {
    return validarNome(nome, 'Nome da pessoa', 3, 100)
  }

  const validarDatas = (inicio, fim) => {
    const dataAtual = new Date()
    const inicioDate = new Date(inicio)
    const fimDate = fim ? new Date(fim) : null

    if (inicioDate > dataAtual) return 'A data de início não pode ser no futuro.'
    if (fimDate && fimDate > dataAtual) return 'A data de fim não pode ser no futuro.'
    if (fimDate && fimDate < inicioDate) return 'A data de fim não pode ser anterior à data de início.'
    if (fimDate && fimDate.getTime() === inicioDate.getTime()) return 'As datas não podem ser iguais.'
    return null
  }

  const validarSobreposicao = (novoVinculo, isEditMode) => {
    return historico.value.some(vinculo => {
      if (isEditMode && vinculo.id === novoVinculo.id) return false

      const inicioExistente = new Date(vinculo.data_inicio)
      const fimExistente = vinculo.data_fim ? new Date(vinculo.data_fim) : null
      const inicioNovo = new Date(novoVinculo.data_inicio)
      const fimNovo = novoVinculo.data_fim ? new Date(novoVinculo.data_fim) : null

      return (
        (fimNovo === null || inicioExistente <= fimNovo) &&
        (fimExistente === null || inicioNovo <= fimExistente)
      )
    })
  }

  const validarVinculoAtivo = (historico, novoFim) => {
    const vinculoAtivo = historico.value.find(v => !v.data_fim)
    if (!vinculoAtivo) return null

    const inicioAtivo = new Date(vinculoAtivo.data_inicio)
    const fimNovo = novoFim ? new Date(novoFim) : null

    if (!fimNovo || fimNovo >= inicioAtivo) {
      return 'Encerre o vínculo atual antes de criar um novo.'
    }
    return null
  }

  return {
    validarNome,
    validarEmail,
    validarNomeCargo,
    validarNomePessoa,
    validarDatas,
    validarSobreposicao,
    validarVinculoAtivo
  }
}
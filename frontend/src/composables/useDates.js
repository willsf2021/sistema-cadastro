export const useDates = () => {
  const formatarData = (data) => {
    if (!data) return '-'
    const dateObj = new Date(data)
    return dateObj.toLocaleDateString('pt-BR', { timeZone: 'UTC' })
  }

  const formatarDataParaInput = (data) => {
    if (!data) return ''
    const dateObj = new Date(data)
    return dateObj.toISOString().split('T')[0]
  }

  const formatarDataParaAPI = (data) => {
    if (!data) return null
    const dateObj = new Date(data)
    return dateObj.toISOString().split('T')[0]
  }

  return {
    formatarData,
    formatarDataParaInput,
    formatarDataParaAPI
  }
}
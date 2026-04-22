export function formatDate(value) {
  if (!value) return '-'
  const d = new Date(value)
  return isNaN(d.getTime()) ? '-' : d.toLocaleDateString('pt-BR', { timeZone: 'UTC' })
}

export function formatPhone(value) {
  if (!value) return '-'
  const digits = value.replace(/\D/g, '')
  if (digits.length === 11) return `(${digits.slice(0, 2)}) ${digits[2]} ${digits.slice(3, 7)}-${digits.slice(7)}`
  if (digits.length === 10) return `(${digits.slice(0, 2)}) ${digits.slice(2, 6)}-${digits.slice(6)}`
  return value
}

export function getInitials(nome) {
  return (nome ?? '').split(' ').slice(0, 2).map(n => n[0]).join('').toUpperCase()
}

export function formatDias(dias) {
  if (dias === null || dias === undefined) return '-'
  if (dias < 0)     return 'Expirado'
  if (dias === 0)   return 'Hoje'
  if (dias === 1)   return '1 dia'
  if (dias < 30)    return `${dias} dias`
  if (dias < 365) {
    const meses = Math.floor(dias / 30)
    return meses === 1 ? '1 mês' : `${meses} meses`
  }
  const anos = Math.floor(dias / 365)
  if (anos >= 5)    return 'Vitalício'
  return anos === 1 ? '1 ano' : `${anos} anos`
}

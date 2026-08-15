export const PHONE_DISPLAY = '+254 20 2470436'
export const PHONE_TEL = '+254202470436'
export const WHATSAPP_NUMBER = '254202470436'

export function whatsappLink(message = "Hi Poseidon Vet, I'd like some help.") {
  return `https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(message)}`
}

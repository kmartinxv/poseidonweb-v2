import { ref } from 'vue'
import { portalApi } from '../lib/portalApi.js'

/** Shared "fetch a list on demand" shape used by every portal resource below. */
function listResource(path, unwrap = (res) => res.data) {
  const items   = ref([])
  const loading = ref(true)
  const error   = ref(null)

  async function load() {
    loading.value = true
    error.value = null
    try {
      items.value = unwrap(await portalApi.get(path))
    } catch (e) {
      error.value = e.message
    } finally {
      loading.value = false
    }
  }

  load()
  return { items, loading, error, reload: load }
}

export function usePortalPets()            { return listResource('/portal/pets') }
export function usePortalAppointments()    { return listResource('/portal/appointments') }
export function usePortalMedicalRecords()  { return listResource('/portal/medical-records') }
export function usePortalPrescriptions()   { return listResource('/portal/prescriptions') }
export function usePortalInvoices()        { return listResource('/portal/invoices') }
export function usePortalNotifications()   { return listResource('/portal/notifications', (res) => res.notifications) }

export async function fetchPortalInvoice(id) {
  const { data } = await portalApi.get(`/portal/invoices/${id}`)
  return data
}

<template>
  <div class="appts-page">
    <div class="container" style="padding-top:136px;padding-bottom:80px">
      <div class="appts-layout">

        <!-- Left: Booking form -->
        <div class="booking-form">
          <span class="eyebrow">Online Booking</span>
          <h1>Book Your Visit<br><span class="teal wavy-line">In Minutes</span></h1>
          <p class="text-light" style="margin:12px 0 32px">Simple. Fast. Convenient. Schedule your appointment in just a few easy steps.</p>

          <!-- Step indicator -->
          <div class="steps-indicator">
            <div
              v-for="(s, i) in steps"
              :key="i"
              :class="['step-item', { done: currentStep > i, active: currentStep === i }]"
            >
              <div class="step-dot">
                <svg v-if="currentStep > i" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                <span v-else>{{ i + 1 }}</span>
              </div>
              <span class="step-label">{{ s }}</span>
              <div v-if="i < steps.length - 1" class="step-connector"></div>
            </div>
          </div>

          <!-- Step 0: Select Service -->
          <div v-if="currentStep === 0" class="step-panel">
            <h3>1. Select Service</h3>
            <div class="form-group">
              <label class="form-label">Service Type</label>
              <select v-model="form.service" class="form-control">
                <option value="">Choose a service...</option>
                <option v-for="s in serviceOptions" :key="s" :value="s">{{ s }}</option>
              </select>
            </div>
            <div class="form-group" style="margin-top:16px">
              <label class="form-label">Branch / Location</label>
              <select v-model="form.branch" class="form-control">
                <option value="Langata (Main)">Langata (Main)</option>
                <option value="Westlands">Westlands</option>
                <option value="Karen">Karen</option>
                <option value="Mombasa Road">Mombasa Road</option>
              </select>
            </div>
          </div>

          <!-- Step 1: Choose Vet -->
          <div v-if="currentStep === 1" class="step-panel">
            <h3>2. Choose Your Vet</h3>
            <div class="vet-options">
              <div
                v-for="vet in vetOptions"
                :key="vet.id"
                :class="['vet-card card', { selected: form.vetId === vet.id }]"
                @click="form.vetId = vet.id"
              >
                <img :src="vet.image" :alt="vet.name" />
                <div class="vet-info">
                  <strong>{{ vet.name }}</strong>
                  <span>{{ vet.role }}</span>
                  <div class="vet-rating">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="var(--teal)"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <span>4.9 · 200+ reviews</span>
                  </div>
                </div>
                <div v-if="form.vetId === vet.id" class="vet-selected-check">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
              </div>
            </div>
          </div>

          <!-- Step 2: Date & Time -->
          <div v-if="currentStep === 2" class="step-panel">
            <h3>3. Select Date &amp; Time</h3>
            <div class="date-time-row">
              <div class="form-group">
                <label class="form-label">Appointment Date</label>
                <input type="date" v-model="form.date" class="form-control" :min="minDate" />
              </div>
              <div class="form-group">
                <label class="form-label">Preferred Time</label>
                <select v-model="form.time" class="form-control">
                  <option value="">Select time...</option>
                  <option v-for="t in timeSlots" :key="t" :value="t">{{ t }}</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Step 3: Your Info -->
          <div v-if="currentStep === 3" class="step-panel">
            <h3>4. Your Information</h3>
            <div class="form-2col">
              <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" v-model="form.name" class="form-control" placeholder="Jane Mwangi" />
              </div>
              <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="tel" v-model="form.phone" class="form-control" placeholder="+254 700 000 000" />
              </div>
              <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" v-model="form.email" class="form-control" placeholder="jane@example.com" />
              </div>
              <div class="form-group">
                <label class="form-label">Pet Name &amp; Species</label>
                <input type="text" v-model="form.pet" class="form-control" placeholder="Simba — German Shepherd" />
              </div>
              <div class="form-group" style="grid-column:1/-1">
                <label class="form-label">Reason for Visit (Optional)</label>
                <textarea v-model="form.notes" class="form-control" placeholder="Describe symptoms or what you'd like us to address..."></textarea>
              </div>
            </div>
            <div class="info-secure">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              Your information is secure and encrypted.
            </div>
          </div>

          <!-- Step 4: Confirm -->
          <div v-if="currentStep === 4" class="step-panel">
            <div v-if="!submitted" class="confirm-panel">
              <div class="confirm-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              </div>
              <h3>Confirm Your Appointment</h3>
              <p>Please review the details in the summary panel, then confirm.</p>
            </div>
            <div v-else class="success-panel">
              <div class="success-icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              </div>
              <h3>Booking Confirmed!</h3>
              <p>We've received your request and will send a confirmation to <strong>{{ form.email }}</strong>. See you soon!</p>
              <RouterLink to="/" class="btn btn-primary" style="margin-top:16px">Back to Home</RouterLink>
            </div>
          </div>

          <!-- Navigation -->
          <div v-if="!submitted" class="step-nav">
            <button v-if="currentStep > 0" class="btn btn-secondary" @click="currentStep--">← Back</button>
            <div style="flex:1"></div>
            <button
              v-if="currentStep < 4"
              class="btn btn-primary"
              :disabled="!canProceed"
              @click="currentStep++"
            >
              Next Step →
            </button>
            <button
              v-if="currentStep === 4 && !submitted"
              class="btn btn-primary"
              @click="submitted = true"
            >
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              Confirm Appointment
            </button>
          </div>
        </div>

        <!-- Right: Summary sidebar -->
        <div class="appt-summary card">
          <h3>Appointment Summary</h3>
          <div class="summary-items">
            <div class="si" v-for="s in summaryItems" :key="s.label">
              <div class="si-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2"><path :d="s.icon"/></svg>
              </div>
              <div>
                <small>{{ s.label }}</small>
                <strong>{{ s.value || '—' }}</strong>
              </div>
            </div>
          </div>

          <div class="new-patient-badge" v-if="!form.name">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <div>
              <strong>New Patient Visit</strong>
              <small>We look forward to welcoming you!</small>
            </div>
          </div>

          <button
            class="btn btn-primary btn-lg"
            style="width:100%;justify-content:center;margin-top:20px"
            :disabled="currentStep < 4"
            @click="currentStep === 4 && !submitted ? (submitted = true) : null"
          >
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Confirm Appointment
          </button>

          <p class="cancel-note">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Free cancellation up to 24 hours before your appointment.
          </p>
        </div>

      </div>

      <!-- Bottom strip -->
      <div class="appt-trust-strip">
        <div class="at-item" v-for="t in trustItems" :key="t.label">
          <div class="icon-circle" style="width:48px;height:48px">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path :d="t.icon"/></svg>
          </div>
          <div>
            <strong>{{ t.label }}</strong>
            <span>{{ t.sub }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed }   from 'vue'
import { team }            from '../data/team.js'
import { useScrollReveal } from '../composables/useScrollReveal.js'
useScrollReveal()

const steps = ['Service', 'Doctor', 'Date & Time', 'Your Info', 'Confirm']
const currentStep = ref(0)
const submitted   = ref(false)

const form = ref({
  service: '',
  branch:  'Langata (Main)',
  vetId:   null,
  date:    '',
  time:    '',
  name:    '',
  phone:   '',
  email:   '',
  pet:     '',
  notes:   '',
})

const minDate = new Date(Date.now() + 86400000).toISOString().split('T')[0]

const serviceOptions = [
  'General Consultation', 'Wellness Check-up', 'Vaccination', 'Dental Check', 'Surgery Consultation',
  'Lab / Diagnostics', 'Emergency', 'Grooming', 'Boarding Enquiry', 'Nutrition Counselling',
]
const vetOptions = team.filter(m => m.role.includes('Vet') || m.role.includes('Chief')).slice(0, 4)
const timeSlots = [
  '9:00 AM', '9:30 AM', '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM',
  '12:00 PM', '12:30 PM', '2:00 PM', '2:30 PM', '3:00 PM', '3:30 PM', '4:00 PM',
]

const canProceed = computed(() => {
  if (currentStep.value === 0) return form.value.service && form.value.branch
  if (currentStep.value === 1) return form.value.vetId !== null
  if (currentStep.value === 2) return form.value.date && form.value.time
  if (currentStep.value === 3) return form.value.name && form.value.phone && form.value.email
  return true
})

const selectedVet = computed(() => vetOptions.find(v => v.id === form.value.vetId))
const summaryItems = computed(() => [
  { label: 'Service',  value: form.value.service || 'Not selected',       icon: 'M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z' },
  { label: 'Doctor',   value: selectedVet.value?.name || 'Not selected',   icon: 'M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2' },
  { label: 'Date',     value: form.value.date || 'Not selected',           icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z' },
  { label: 'Time',     value: form.value.time || 'Not selected',           icon: 'M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10zm0-14v4l3 3' },
  { label: 'Location', value: form.value.branch,                           icon: 'M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z' },
])

const trustItems = [
  { label: 'Insurance Accepted', sub: 'Most major providers', icon: 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z' },
  { label: 'Flexible Scheduling', sub: 'Mon–Sat 9am–5pm',    icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z' },
  { label: 'Urgent Care Available', sub: 'Same-day for emergencies', icon: 'M22 12h-6l-2 3H10l-2-3H2' },
]
</script>

<style scoped>
.appts-layout {
  display: grid;
  grid-template-columns: 1fr 360px;
  gap: 40px;
  align-items: start;
}
.booking-form h1 { margin: 8px 0; }

/* Steps indicator */
.steps-indicator {
  display: flex;
  align-items: center;
  margin-bottom: 36px;
  overflow-x: auto;
  padding-bottom: 4px;
}
.step-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  position: relative;
  flex: 1;
  min-width: 80px;
}
.step-dot {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: var(--gray-200);
  border: 2.5px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.82rem;
  font-weight: 700;
  color: var(--gray-600);
  transition: all var(--transition);
  z-index: 1;
}
.step-item.active .step-dot {
  background: var(--teal);
  border-color: var(--teal);
  color: white;
  box-shadow: 0 0 0 4px var(--teal-light);
}
.step-item.done .step-dot {
  background: var(--teal);
  border-color: var(--teal);
}
.step-label { font-size: 0.72rem; font-weight: 600; color: var(--text-light); white-space: nowrap; }
.step-item.active .step-label { color: var(--teal); }
.step-connector {
  position: absolute;
  top: 18px;
  left: calc(50% + 18px);
  right: calc(-50% + 18px);
  height: 2px;
  background: var(--gray-200);
  z-index: 0;
}
.step-item.done .step-connector { background: var(--teal); }

/* Step panels */
.step-panel {
  background: var(--gray-50);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 28px;
  margin-bottom: 24px;
}
.step-panel h3 { margin-bottom: 20px; font-size: 1.05rem; }
.date-time-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.form-2col     { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.info-secure   { display: flex; align-items: center; gap: 6px; font-size: 0.78rem; color: var(--text-light); margin-top: 16px; }

/* Vet picker */
.vet-options { display: flex; flex-direction: column; gap: 12px; }
.vet-card {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 14px 16px;
  cursor: pointer;
  border: 2px solid var(--border);
  position: relative;
  transition: border-color var(--transition);
}
.vet-card:hover   { border-color: var(--teal-light); }
.vet-card.selected { border-color: var(--teal); background: var(--teal-xlight); }
.vet-card img { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; }
.vet-info strong  { display: block; font-size: 0.9rem; color: var(--navy); }
.vet-info span    { font-size: 0.78rem; color: var(--text-light); }
.vet-rating { display: flex; align-items: center; gap: 4px; margin-top: 4px; font-size: 0.72rem; color: var(--text-light); }
.vet-selected-check {
  position: absolute;
  right: 14px;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: var(--teal);
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Confirm / success */
.confirm-panel, .success-panel {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 12px;
  padding: 16px 0;
}
.confirm-icon {
  width: 72px;
  height: 72px;
  border-radius: 50%;
  background: var(--teal-light);
  display: flex;
  align-items: center;
  justify-content: center;
}
.success-icon {
  width: 72px;
  height: 72px;
  border-radius: 50%;
  background: var(--teal);
  display: flex;
  align-items: center;
  justify-content: center;
}
.success-panel p { color: var(--text-light); }

/* Step nav */
.step-nav { display: flex; align-items: center; gap: 12px; }

/* Summary sidebar */
.appt-summary { padding: 28px; position: sticky; top: 96px; }
.appt-summary h3 { margin-bottom: 20px; }
.summary-items { display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px; }
.si { display: flex; align-items: flex-start; gap: 12px; }
.si-icon {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: var(--teal-light);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.si small  { display: block; font-size: 0.7rem; color: var(--text-light); margin-bottom: 2px; }
.si strong { display: block; font-size: 0.88rem; color: var(--navy); font-weight: 600; }
.new-patient-badge {
  display: flex;
  align-items: center;
  gap: 10px;
  background: var(--teal-xlight);
  border: 1px solid var(--teal-light);
  border-radius: var(--radius);
  padding: 12px 14px;
}
.new-patient-badge strong { display: block; font-size: 0.85rem; color: var(--teal); }
.new-patient-badge small  { font-size: 0.75rem; color: var(--text-light); }
.cancel-note {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 0.75rem;
  color: var(--text-light);
  margin-top: 12px;
  text-align: center;
  justify-content: center;
}

/* Trust strip */
.appt-trust-strip {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
  margin-top: 56px;
  padding: 32px;
  background: var(--gray-50);
  border-radius: var(--radius-lg);
  border: 1px solid var(--border);
}
.at-item { display: flex; align-items: center; gap: 14px; }
.at-item strong { display: block; font-size: 0.88rem; color: var(--navy); }
.at-item span   { font-size: 0.78rem; color: var(--text-light); }

@media (max-width: 1024px) {
  .appts-layout { grid-template-columns: 1fr; }
  .appt-summary { position: static; }
  .appt-trust-strip { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
  .date-time-row { grid-template-columns: 1fr; }
  .form-2col     { grid-template-columns: 1fr; }
}
</style>

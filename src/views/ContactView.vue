<template>
  <div class="contact-page">

    <section class="page-hero">
      <div class="container">
        <span class="eyebrow">Get In Touch</span>
        <h1>Contact Poseidon Vet</h1>
        <p>We're here to help. Reach us by phone, email, or just walk in during clinic hours.</p>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="contact-layout">

          <!-- Contact form -->
          <div class="contact-form-wrap">
            <h2 style="margin-bottom:8px">Send Us a Message</h2>
            <p class="text-light" style="margin-bottom:28px">We typically respond within a few hours on business days.</p>

            <form v-if="!sent" @submit.prevent="sent = true" class="contact-form">
              <div class="form-2col">
                <div class="form-group">
                  <label class="form-label">Your Name</label>
                  <input class="form-control" v-model="f.name" placeholder="Jane Mwangi" required />
                </div>
                <div class="form-group">
                  <label class="form-label">Phone Number</label>
                  <input class="form-control" type="tel" v-model="f.phone" placeholder="+254 700 000 000" />
                </div>
                <div class="form-group" style="grid-column:1/-1">
                  <label class="form-label">Email Address</label>
                  <input class="form-control" type="email" v-model="f.email" placeholder="jane@example.com" required />
                </div>
                <div class="form-group" style="grid-column:1/-1">
                  <label class="form-label">Subject</label>
                  <select class="form-control" v-model="f.subject">
                    <option value="">Select a topic...</option>
                    <option>General Enquiry</option>
                    <option>Book Appointment</option>
                    <option>Shop / Product Question</option>
                    <option>Boarding Enquiry</option>
                    <option>Emergency</option>
                    <option>Feedback</option>
                  </select>
                </div>
                <div class="form-group" style="grid-column:1/-1">
                  <label class="form-label">Message</label>
                  <textarea class="form-control" v-model="f.message" rows="5" placeholder="Tell us how we can help..." required></textarea>
                </div>
              </div>
              <button type="submit" class="btn btn-primary btn-lg">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                Send Message
              </button>
            </form>

            <div v-else class="form-success">
              <div class="success-icon">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              </div>
              <h3>Message Sent!</h3>
              <p>Thank you, {{ f.name }}! We'll get back to you at {{ f.email }} within a few hours.</p>
              <button class="btn btn-secondary" @click="sent = false; f = {...defaultForm}" style="margin-top:16px">Send Another</button>
            </div>
          </div>

          <!-- Contact info -->
          <div class="contact-info">
            <div class="contact-card card" v-for="c in contactCards" :key="c.title">
              <div class="icon-circle">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path :d="c.icon"/></svg>
              </div>
              <h3>{{ c.title }}</h3>
              <div class="contact-lines">
                <p v-for="l in c.lines" :key="l" v-html="l"></p>
              </div>
            </div>

            <!-- Map placeholder -->
            <div class="map-card card">
              <h3 style="padding:20px 20px 14px">Find Us</h3>
              <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.819!2d36.8184!3d-1.3028!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMcKwMTgnMTAuMSJTIDM2wrA0OScwNi4yIkU!5e0!3m2!1sen!2ske!4v1000000000000!5m2!1sen!2ske"
                width="100%"
                height="220"
                style="border:0;display:block"
                allowfullscreen
                loading="lazy"
                title="Poseidon Vet Location"
              ></iframe>
              <div style="padding:16px 20px">
                <p style="font-size:0.82rem;color:var(--text-light)">Mai Mahiu Rd, opposite Magharibi Place, Nairobi</p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

  </div>
</template>

<script setup>
import { ref, reactive }   from 'vue'
import { useScrollReveal } from '../composables/useScrollReveal.js'
import { PHONE_DISPLAY, PHONE_TEL, whatsappLink } from '../data/contact.js'
useScrollReveal()

const defaultForm = { name: '', phone: '', email: '', subject: '', message: '' }
const f    = ref({ ...defaultForm })
const sent = ref(false)

const contactCards = [
  {
    title: 'Call Us',
    icon:  'M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.62 3.38 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.77a16 16 0 0 0 6.28 6.28l1.06-1.06a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.03z',
    lines: [`<a href="tel:${PHONE_TEL}" style="color:var(--teal);font-weight:600">${PHONE_DISPLAY}</a>`, 'Available Mon–Sat 9am–5pm, Sun 10am–4pm'],
  },
  {
    title: 'Chat on WhatsApp',
    icon:  'M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z',
    lines: [`<a href="${whatsappLink()}" target="_blank" rel="noopener" style="color:var(--teal);font-weight:600">Message us directly</a>`, 'Usually replies within minutes during clinic hours.'],
  },
  {
    title: 'Email Us',
    icon:  'M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z',
    lines: ['<a href="mailto:info@poseidonvet.com" style="color:var(--teal);font-weight:600">info@poseidonvet.com</a>', 'We respond within a few hours on business days.'],
  },
  {
    title: 'Visit Us',
    icon:  'M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z',
    lines: ['Mai Mahiu Rd, opposite Magharibi Place', 'Nairobi, Kenya'],
  },
  {
    title: 'Clinic Hours',
    icon:  'M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10zm0-14v4l3 3',
    lines: ['Mon–Sat: 9:00 AM – 5:00 PM', 'Sunday: 10:00 AM – 4:00 PM'],
  },
]
</script>

<style scoped>
.contact-page .page-hero { padding: 136px 0 72px; }
.contact-layout {
  display: grid;
  grid-template-columns: 1fr 380px;
  gap: 48px;
  align-items: start;
}
.form-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-success {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 12px;
  padding: 48px 0;
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
.form-success h3 { color: var(--navy); }
.form-success p  { color: var(--text-light); max-width: 360px; }

.contact-info { display: flex; flex-direction: column; gap: 16px; }
.contact-card { padding: 22px; display: flex; flex-direction: column; gap: 10px; }
.contact-card h3 { font-size: 1rem; }
.contact-lines p { font-size: 0.85rem; color: var(--text-light); line-height: 1.5; }
.map-card { overflow: hidden; padding: 0; }

@media (max-width: 1024px) { .contact-layout { grid-template-columns: 1fr; } }
@media (max-width: 600px)  { .contact-page .page-hero { padding-top: 100px; } .form-2col { grid-template-columns: 1fr; } }
</style>

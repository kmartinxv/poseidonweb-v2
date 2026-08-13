<template>
  <div class="about-page">

    <!-- Hero -->
    <section class="about-hero">
      <div class="container">
        <div class="about-hero-layout">
          <div class="about-hero-text">
            <span class="eyebrow">Get To Know Us</span>
            <h1>Care With<br><span class="teal wavy-line">Compassion</span></h1>
            <p>At Poseidon Veterinary Clinic, we believe a healthy pet means a happy family. Since 2010, we've been providing expert, compassionate care in a welcoming environment that puts both animals and owners at ease.</p>
            <p style="margin-top:12px">Our mission is simple — to treat every animal like our own, and every client like family.</p>
            <RouterLink to="/appointments" class="btn btn-primary" style="margin-top:28px">
              Book a Visit
            </RouterLink>
          </div>
          <div class="about-hero-img">
            <img
              src="https://images.unsplash.com/photo-1666214280557-f1b5022eb634?auto=format&fit=crop&w=800&q=80"
              alt="Poseidon Vet Team"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- 3 Pillars -->
    <section class="section pillars-section">
      <div class="container">
        <div class="grid-3">
          <div class="pillar-card card" v-for="p in pillars" :key="p.title">
            <div class="icon-circle">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path :d="p.icon"/>
              </svg>
            </div>
            <h3>{{ p.title }}</h3>
            <p>{{ p.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Journey / Timeline -->
    <section class="section timeline-section">
      <div class="container">
        <div class="section-header">
          <span class="eyebrow">Our Journey</span>
          <h2>Growing Together</h2>
          <div class="divider-teal"></div>
        </div>
        <div class="timeline">
          <div class="timeline-item" v-for="(t, i) in timeline" :key="i">
            <div class="timeline-dot">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
                <path :d="t.icon"/>
              </svg>
            </div>
            <div class="timeline-year">{{ t.year }}</div>
            <div class="timeline-title">{{ t.title }}</div>
            <div class="timeline-desc">{{ t.desc }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonial spotlight -->
    <section class="section testi-spotlight">
      <div class="container">
        <div class="testi-layout">
          <div class="testi-quote-block">
            <div class="quote-mark">"</div>
            <blockquote>The team at Poseidon Vet is amazing. They make every visit comfortable and stress-free. My dog actually gets excited to come in!</blockquote>
            <div class="testi-author">
              <img src="https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?auto=format&fit=crop&w=80&h=80&q=80" alt="Client" />
              <div>
                <strong>Aisha M.</strong>
                <small>Langata, Nairobi</small>
                <div class="stars">
                  <svg v-for="n in 5" :key="n" width="14" height="14" viewBox="0 0 24 24" fill="var(--teal)"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
              </div>
            </div>
          </div>
          <div class="testi-right">
            <img
              src="https://images.unsplash.com/photo-1450778869180-41d0601e046e?auto=format&fit=crop&w=600&q=80"
              alt="Happy pet"
              class="testi-img"
            />
            <div class="trust-card card">
              <div class="icon-circle" style="width:48px;height:48px">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
              </div>
              <div>
                <strong>Trusted by Our Community</strong>
                <p>Thank you to the 1,000+ pet owners who trust us with their animals' health.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Full Team -->
    <section class="section team-full-section">
      <div class="container">
        <div class="section-header">
          <span class="eyebrow">Our People</span>
          <h2>The Poseidon Team</h2>
          <div class="divider-teal"></div>
          <p>Every member of our team brings expertise, passion, and a genuine love for animals.</p>
        </div>
        <div class="team-full-grid">
          <div class="team-card card" v-for="m in team" :key="m.id">
            <div class="team-img-wrap">
              <img :src="m.image" :alt="m.name" />
            </div>
            <div class="team-info">
              <h3>{{ m.name }}</h3>
              <span class="team-role">{{ m.role }}</span>
              <p class="team-bio">{{ m.bio }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>
</template>

<script setup>
import { team }              from '../data/team.js'
import { useScrollReveal }   from '../composables/useScrollReveal.js'
useScrollReveal()

const pillars = [
  {
    title: 'Expert Team',
    desc: 'Our vets and specialists bring skill, compassion, and dedication to every animal in their care.',
    icon: 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75',
  },
  {
    title: 'Advanced Technology',
    desc: 'We invest in the latest veterinary equipment for precise, comfortable, and effective treatment.',
    icon: 'M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18',
  },
  {
    title: 'Pet-First Care',
    desc: 'Your pet\'s comfort, safety, and wellbeing are our top priorities at every step of their journey.',
    icon: 'M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z',
  },
]

const timeline = [
  {
    year: '2010',
    title: 'Founded',
    desc: 'Dr. Chesang opened Poseidon Vet with a vision to bring world-class care to Nairobi\'s pet community.',
    icon: 'M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z',
  },
  {
    year: '2014',
    title: 'Expanded Team',
    desc: 'Grew to 5 vets and opened our surgical suite and in-house laboratory.',
    icon: 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2',
  },
  {
    year: '2019',
    title: 'New Diagnostics Wing',
    desc: 'Introduced digital radiography, ultrasound, and advanced blood analysis systems.',
    icon: 'M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18',
  },
  {
    year: '2025',
    title: 'Growing Forward',
    desc: 'Continuing to grow and serve more pets and families across Nairobi and beyond.',
    icon: 'M18 20V10M12 20V4M6 20v-6',
  },
]
</script>

<style scoped>
.about-hero {
  padding: 136px 0 80px;
  background: var(--bg);
}
.about-hero-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 72px;
  align-items: center;
}
.about-hero-text h1 { margin: 8px 0 20px; }
.about-hero-text p  { color: var(--text-light); font-size: 0.95rem; max-width: 440px; }
.about-hero-img {
  border-radius: var(--radius-xl);
  overflow: hidden;
  height: 460px;
  box-shadow: var(--shadow-lg);
}
.about-hero-img img { width: 100%; height: 100%; object-fit: cover; }

.pillars-section { background: white; }
.pillar-card { padding: 32px 24px; display: flex; flex-direction: column; gap: 14px; }
.pillar-card h3 { font-size: 1.1rem; }
.pillar-card p  { font-size: 0.88rem; color: var(--text-light); }

.timeline-section { background: var(--gray-50); }
.timeline {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 0;
  position: relative;
  margin-top: 48px;
}
.timeline::before {
  content: '';
  position: absolute;
  top: 22px;
  left: calc(12.5% + 22px);
  right: calc(12.5% + 22px);
  height: 2px;
  background: var(--teal-light);
}
.timeline-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  padding: 0 16px;
  position: relative;
}
.timeline-dot {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: var(--teal);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  z-index: 1;
  box-shadow: 0 0 0 6px var(--teal-light);
  margin-bottom: 16px;
}
.timeline-year  { font-size: 1.1rem; font-weight: 800; color: var(--teal); margin-bottom: 6px; }
.timeline-title { font-size: 0.9rem; font-weight: 700; color: var(--navy); margin-bottom: 8px; }
.timeline-desc  { font-size: 0.8rem; color: var(--text-light); line-height: 1.5; }

.testi-spotlight { background: white; }
.testi-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 72px;
  align-items: center;
}
.quote-mark {
  font-size: 5rem;
  line-height: 1;
  color: var(--teal);
  font-family: Georgia, serif;
  margin-bottom: -16px;
}
blockquote { font-size: 1.1rem; line-height: 1.7; color: var(--text); font-style: italic; margin-bottom: 28px; }
.testi-author { display: flex; align-items: center; gap: 14px; }
.testi-author img { width: 52px; height: 52px; border-radius: 50%; object-fit: cover; }
.testi-author strong { display: block; font-size: 0.92rem; color: var(--navy); }
.testi-author small  { font-size: 0.78rem; color: var(--text-light); }
.stars { display: flex; gap: 2px; margin-top: 4px; }
.testi-right { display: flex; flex-direction: column; gap: 20px; }
.testi-img {
  border-radius: var(--radius-lg);
  height: 280px;
  width: 100%;
  object-fit: cover;
  box-shadow: var(--shadow);
}
.trust-card { padding: 20px; display: flex; align-items: flex-start; gap: 14px; }
.trust-card strong { display: block; font-size: 0.9rem; color: var(--navy); margin-bottom: 4px; }
.trust-card p { font-size: 0.82rem; color: var(--text-light); }

.team-full-section { background: var(--gray-50); }
.team-full-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24px;
}
.team-card { overflow: hidden; }
.team-img-wrap { height: 220px; overflow: hidden; }
.team-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 400ms var(--ease-out); }
.team-card:hover .team-img-wrap img { transform: scale(1.05); }
.team-info  { padding: 18px; }
.team-info h3 { font-size: 0.95rem; margin-bottom: 4px; }
.team-role  { font-size: 0.75rem; color: var(--teal); font-weight: 600; display: block; margin-bottom: 8px; }
.team-bio   { font-size: 0.8rem; color: var(--text-light); line-height: 1.5; }

@media (max-width: 1024px) {
  .about-hero-layout { grid-template-columns: 1fr; }
  .about-hero-img { height: 340px; }
  .testi-layout { grid-template-columns: 1fr; }
  .timeline { grid-template-columns: repeat(2, 1fr); }
  .timeline::before { display: none; }
  .team-full-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
  .about-hero { padding-top: 100px; }
  .team-full-grid { grid-template-columns: 1fr 1fr; }
  .timeline { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 480px) {
  .team-full-grid { grid-template-columns: 1fr; }
  .timeline { grid-template-columns: 1fr; }
}
</style>

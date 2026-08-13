<template>
  <div class="home">

    <!-- ── Hero Slider ────────────────────────────────────────── -->
    <HeroSlider />

    <!-- ── Stats strip ──────────────────────────────────────────── -->
    <section class="stats-strip">
      <div class="container">
        <div class="stats-grid">
          <div
            class="stat-item"
            v-for="(s, i) in stats"
            :key="s.label"
            data-reveal="scale"
            :data-delay="i * 100"
          >
            <div class="icon-circle">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path :d="s.icon"/>
              </svg>
            </div>
            <div>
              <div class="stat-num" :ref="el => statEls[i] = el">{{ s.num }}</div>
              <div class="stat-label">{{ s.label }}</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ── Services ─────────────────────────────────────────────── -->
    <section class="section services-section">
      <div class="container">
        <div class="services-layout">

          <!-- Left panel -->
          <div class="services-left" data-reveal="left">
            <span class="eyebrow">Our Services</span>
            <h2>Care Tailored For<br><span class="teal wavy-line">Every Animal</span></h2>
            <p>From routine wellness checks to complex surgeries, our experienced team provides full-spectrum care.</p>
            <div class="services-actions">
              <RouterLink to="/appointments" class="btn btn-primary">Book Appointment</RouterLink>
              <RouterLink to="/services" class="btn btn-secondary">
                View All
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
              </RouterLink>
            </div>
            <div class="why-us">
              <p class="why-title">Why Pet Owners Trust Us</p>
              <ul class="check-list">
                <li>Experienced &amp; Caring Veterinary Team</li>
                <li>Advanced In-House Diagnostics</li>
                <li>Personalised Care For Every Pet</li>
                <li>Transparent Pricing, No Hidden Fees</li>
              </ul>
            </div>
          </div>

          <!-- Right grid -->
          <div class="services-grid">
            <div
              class="service-card card"
              v-for="(s, i) in displayedServices"
              :key="s.id"
              data-reveal
              :data-delay="i * 80"
            >
              <div class="icon-circle">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path :d="serviceIconPaths[s.icon]"/>
                </svg>
              </div>
              <h3>{{ s.title }}</h3>
              <p>{{ s.desc }}</p>
              <RouterLink to="/services" class="learn-more">
                Learn More
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
              </RouterLink>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- ── Pet gallery strip ──────────────────────────────────── -->
    <section class="pets-gallery" aria-hidden="true">
      <div class="pets-marquee">
        <div class="pets-track">
          <img v-for="(img, i) in [...petGallery, ...petGallery]" :key="i" :src="img.src" :alt="img.alt" class="pet-thumb" />
        </div>
      </div>
    </section>

    <!-- ── About strip ──────────────────────────────────────────── -->
    <section class="section about-strip">
      <div class="container">
        <div class="about-layout">
          <div class="about-image" data-reveal="left">
            <img
              src="https://images.unsplash.com/photo-1770836037793-95bdbf190f71?auto=format&fit=crop&w=800&q=80"
              alt="Poseidon Vet clinic interior"
            />
            <!-- overlay badge -->
            <div class="about-img-badge">
              <span class="badge-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              </span>
              <div>
                <strong>Accredited Clinic</strong>
                <small>Kenya Vet Board Certified</small>
              </div>
            </div>
            <!-- floating paw decoration -->
            <div class="about-paw-deco float-anim-slow">
              <svg width="64" height="64" viewBox="0 0 40 40" fill="none" opacity="0.18">
                <ellipse cx="14" cy="13" rx="3" ry="4" fill="white"/>
                <ellipse cx="26" cy="13" rx="3" ry="4" fill="white"/>
                <ellipse cx="9"  cy="19" rx="2.5" ry="3.5" fill="white"/>
                <ellipse cx="31" cy="19" rx="2.5" ry="3.5" fill="white"/>
                <path d="M20 18c-5 0-8 3-8 6s2 5 8 5 8-2 8-5-3-6-8-6z" fill="white"/>
              </svg>
            </div>
          </div>

          <div class="about-text" data-reveal="right">
            <span class="eyebrow">Welcome to Poseidon Vet</span>
            <h2>Compassionate Care.<br>Healthy Pets.</h2>
            <div class="divider-teal" style="margin: 16px 0 20px;"></div>
            <p>Founded by Dr. Chesang, Poseidon Veterinary Clinic has been Nairobi's home for trusted pet healthcare since 2010. We combine clinical excellence with genuine warmth.</p>
            <p style="margin-top:12px; color:var(--text-light);">Our mission is simple: world-class veterinary care where both pets and owners feel safe, heard, and valued.</p>
            <div class="about-stats">
              <div class="about-stat" v-for="(s, i) in aboutStats" :key="s.label" :data-delay="i * 80">
                <strong>{{ s.num }}</strong>
                <span>{{ s.label }}</span>
              </div>
            </div>
            <RouterLink to="/about" class="btn btn-primary" style="margin-top:28px">
              Learn Our Story
            </RouterLink>
          </div>
        </div>
      </div>
    </section>

    <!-- ── Team preview ─────────────────────────────────────────── -->
    <section class="section team-section">
      <div class="container">
        <div class="section-header" data-reveal>
          <span class="eyebrow">Our Team</span>
          <h2>Meet Your Pet's Care Team</h2>
          <div class="divider-teal"></div>
          <p>A multidisciplinary team united by a genuine love for animals.</p>
        </div>
        <div class="grid-4">
          <div
            class="team-card card"
            v-for="(m, i) in teamPreview"
            :key="m.id"
            data-reveal
            :data-delay="i * 100"
          >
            <div class="team-img-wrap">
              <img :src="m.image" :alt="m.name" loading="lazy" />
              <div class="team-overlay">
                <span class="team-role-overlay">{{ m.role }}</span>
              </div>
            </div>
            <div class="team-info">
              <h3>{{ m.name }}</h3>
              <span class="team-role">{{ m.role }}</span>
            </div>
          </div>
        </div>
        <div style="text-align:center; margin-top:40px" data-reveal data-delay="200">
          <RouterLink to="/about" class="btn btn-secondary">Meet The Full Team</RouterLink>
        </div>
      </div>
    </section>

    <!-- ── Testimonials ─────────────────────────────────────────── -->
    <section class="section testimonials-section">
      <div class="container">
        <div class="section-header" data-reveal>
          <span class="eyebrow">Client Reviews</span>
          <h2>What Pet Owners Say</h2>
          <div class="divider-teal"></div>
        </div>
        <div class="testimonials-grid">
          <div
            class="testimonial-card card"
            v-for="(t, i) in testimonials"
            :key="t.id"
            data-reveal
            :data-delay="i * 120"
          >
            <div class="stars">
              <svg v-for="n in 5" :key="n" width="16" height="16" viewBox="0 0 24 24" fill="var(--teal)">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
              </svg>
            </div>
            <blockquote>"{{ t.quote }}"</blockquote>
            <div class="reviewer">
              <img :src="t.avatar" :alt="t.author" />
              <div>
                <strong>{{ t.author }}</strong>
                <small>{{ t.location }}</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ── Shop CTA ──────────────────────────────────────────────── -->
    <section class="section shop-cta-section" data-reveal>
      <div class="container">
        <div class="shop-cta-card">
          <div class="shop-cta-text">
            <span class="eyebrow" style="color:rgba(255,255,255,0.7)">Poseidon Pet Shop</span>
            <h2 style="color:white">Quality Products,<br>Vet-Approved</h2>
            <p>Shop prescription foods, supplements, medications, and accessories — hand-picked by our vets.</p>
            <div class="shop-cta-actions">
              <RouterLink to="/shop" class="btn" style="background:white;color:var(--teal);font-weight:700">Shop Now</RouterLink>
              <RouterLink to="/shop" class="btn" style="background:rgba(255,255,255,0.12);color:white;border:1.5px solid rgba(255,255,255,0.25)">View All Products</RouterLink>
            </div>
          </div>
          <div class="shop-pet-images">
            <img
              v-for="img in shopPetImgs"
              :key="img.src"
              :src="img.src"
              :alt="img.alt"
              class="shop-pet-img"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- ── CTA Banner ────────────────────────────────────────────── -->
    <section class="cta-banner" data-reveal data-delay="100">
      <div class="container">
        <div class="cta-inner">
          <div class="cta-icon">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2">
              <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
          </div>
          <div>
            <p class="cta-label">Your pet's health is our priority.</p>
            <p class="cta-sub">Join 1,000+ Nairobi families who trust Poseidon Vet.</p>
          </div>
          <div class="cta-avatars">
            <img v-for="(av, i) in avatars" :key="i" :src="av" :alt="`Client ${i+1}`" class="cta-avatar" />
            <span class="cta-avatar cta-avatar-count">+1K</span>
          </div>
          <RouterLink to="/appointments" class="btn btn-primary btn-lg" style="white-space:nowrap;margin-left:auto;flex-shrink:0">
            Book Your Visit
          </RouterLink>
        </div>
      </div>
    </section>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import HeroSlider                   from '../components/HeroSlider.vue'
import { services, serviceIconPaths } from '../data/services.js'
import { team, testimonials }         from '../data/team.js'
import { useScrollReveal }            from '../composables/useScrollReveal.js'

useScrollReveal()

const displayedServices = computed(() => services.slice(0, 6))
const teamPreview       = computed(() => team.slice(0, 4))

const stats = [
  { num: '15+',    label: 'Years of Experience',  icon: 'M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10zm0-14v4l3 3' },
  { num: '4,200+', label: 'Happy Patients',        icon: 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2' },
  { num: '98%',    label: 'Satisfaction Rate',     icon: 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z' },
  { num: '10+',    label: 'Specialist Vets',       icon: 'M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z' },
]

const aboutStats = [
  { num: '15+', label: 'Years of Excellence' },
  { num: '10+', label: 'Specialist Vets' },
  { num: '98%', label: 'Satisfaction Rate' },
  { num: '4K+', label: 'Pets Treated' },
]

const petGallery = [
  { src: 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?auto=format&fit=crop&w=260&h=180&q=75', alt: 'Golden retriever' },
  { src: 'https://images.unsplash.com/photo-1615789591457-74a63395c990?auto=format&fit=crop&w=260&h=180&q=75', alt: 'Cat' },
  { src: 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=260&h=180&q=75', alt: 'Puppy' },
  { src: 'https://images.unsplash.com/photo-1597595735637-05a49627ee29?auto=format&fit=crop&w=260&h=180&q=75', alt: 'Dogs' },
  { src: 'https://images.unsplash.com/photo-1583337130417-3346a1be7dee?auto=format&fit=crop&w=260&h=180&q=75', alt: 'Dog with vet' },
  { src: 'https://images.unsplash.com/photo-1552728089-57bdde30beb3?auto=format&fit=crop&w=260&h=180&q=75', alt: 'Parrot' },
  { src: 'https://images.unsplash.com/photo-1450778869180-41d0601e046e?auto=format&fit=crop&w=260&h=180&q=75', alt: 'Happy pets' },
  { src: 'https://images.unsplash.com/photo-1601758003122-53c40e686a19?auto=format&fit=crop&w=260&h=180&q=75', alt: 'Dog at clinic' },
]

const shopPetImgs = [
  { src: 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=200&h=200&q=80', alt: 'Puppy' },
  { src: 'https://images.unsplash.com/photo-1615789591457-74a63395c990?auto=format&fit=crop&w=200&h=200&q=80', alt: 'Cat' },
  { src: 'https://images.unsplash.com/photo-1552728089-57bdde30beb3?auto=format&fit=crop&w=200&h=200&q=80', alt: 'Parrot' },
]

const avatars = [
  'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?auto=format&fit=crop&w=80&h=80&q=80',
  'https://images.unsplash.com/photo-1594824476967-48c8b964273f?auto=format&fit=crop&w=80&h=80&q=80',
  'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&w=80&h=80&q=80',
]

// stat element refs for counter animation
const statEls = ref([])

onMounted(() => {
  // Animate stat counters when visible
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return
      const el  = entry.target
      const raw = el.textContent
      const num = parseInt(raw.replace(/[^0-9]/g, ''), 10)
      if (!num) return
      const suffix = raw.replace(/[0-9,]/g, '')
      let start = 0
      const step = num / 60
      const tick = setInterval(() => {
        start = Math.min(start + step, num)
        el.textContent = Math.floor(start).toLocaleString() + suffix
        if (start >= num) clearInterval(tick)
      }, 24)
      observer.unobserve(el)
    })
  }, { threshold: 0.5 })

  statEls.value.forEach(el => el && observer.observe(el))
})
</script>

<style scoped>
/* ── Stats strip ── */
.stats-strip {
  background: white;
  border-bottom: 1px solid var(--border);
  padding: 28px 0;
  box-shadow: var(--shadow-sm);
}
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
.stat-item  { display: flex; align-items: center; gap: 14px; padding: 10px 0; }
.stat-num   { font-size: 1.5rem; font-weight: 800; color: var(--teal); line-height: 1; font-variant-numeric: tabular-nums; }
.stat-label { font-size: 0.75rem; color: var(--text-light); margin-top: 3px; }

/* ── Services ── */
.services-section { background: var(--gray-50); }
.services-layout  { display: grid; grid-template-columns: 340px 1fr; gap: 64px; align-items: start; }
.services-left h2 { margin: 8px 0 16px; }
.services-left p  { color: var(--text-light); margin-bottom: 28px; }
.services-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 36px; }
.why-title        { font-weight: 700; font-size: 0.9rem; color: var(--navy); margin-bottom: 12px; }
.services-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
.service-card  { padding: 24px 20px; display: flex; flex-direction: column; gap: 12px; }
.service-card h3  { font-size: 1rem; margin: 0; }
.service-card p   { font-size: 0.84rem; color: var(--text-light); flex: 1; }
.learn-more {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: 0.82rem; font-weight: 600; color: var(--teal); margin-top: 4px;
  transition: gap var(--transition);
}
.learn-more:hover { gap: 10px; }

/* ── Pet marquee gallery ── */
.pets-gallery { overflow: hidden; padding: 0; background: var(--gray-50); border-bottom: 1px solid var(--border); }
.pets-marquee { position: relative; width: 100%; }
.pets-track {
  display: flex;
  gap: 10px;
  padding: 12px 0;
  animation: marquee 35s linear infinite;
  width: max-content;
}
.pets-track:hover { animation-play-state: paused; }
@keyframes marquee {
  0%   { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}
.pet-thumb {
  width: 220px;
  height: 140px;
  object-fit: cover;
  border-radius: var(--radius);
  flex-shrink: 0;
  transition: transform 300ms var(--ease-out), box-shadow 300ms var(--ease-out);
}
.pet-thumb:hover { transform: scale(1.04); box-shadow: var(--shadow-md); }

/* ── About ── */
.about-strip  { background: white; }
.about-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 72px; align-items: center; }
.about-image  {
  position: relative;
  border-radius: var(--radius-xl);
  overflow: hidden;
  height: 500px;
  box-shadow: var(--shadow-lg);
}
.about-image img { width: 100%; height: 100%; object-fit: cover; }
.about-img-badge {
  position: absolute;
  bottom: 20px;
  right: 20px;
  background: white;
  border-radius: var(--radius);
  padding: 12px 16px;
  display: flex;
  align-items: center;
  gap: 10px;
  box-shadow: var(--shadow-md);
}
.badge-icon   { width: 40px; height: 40px; border-radius: 50%; background: var(--teal); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.about-img-badge strong { display: block; font-size: 0.85rem; color: var(--navy); }
.about-img-badge small  { font-size: 0.72rem; color: var(--text-light); }
.about-paw-deco { position: absolute; top: 20px; left: 20px; pointer-events: none; }
.about-text h2 { margin: 8px 0; }
.about-text p  { color: var(--text-light); font-size: 0.95rem; }
.about-stats {
  display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;
  margin-top: 32px; padding: 24px;
  background: var(--teal-xlight); border-radius: var(--radius); border: 1px solid var(--teal-light);
}
.about-stat { text-align: center; }
.about-stat strong { display: block; font-size: 1.4rem; font-weight: 800; color: var(--teal); }
.about-stat span   { font-size: 0.72rem; color: var(--text-light); }

/* ── Team ── */
.team-section   { background: var(--gray-50); }
.team-card      { overflow: hidden; }
.team-img-wrap  { height: 260px; overflow: hidden; position: relative; }
.team-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 450ms var(--ease-out); }
.team-card:hover .team-img-wrap img { transform: scale(1.07); }
.team-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(13,43,75,0.7) 0%, transparent 50%);
  opacity: 0;
  display: flex;
  align-items: flex-end;
  padding: 16px;
  transition: opacity 350ms var(--ease-out);
}
.team-card:hover .team-overlay { opacity: 1; }
.team-role-overlay { font-size: 0.78rem; color: white; font-weight: 600; }
.team-info  { padding: 16px; }
.team-info h3 { font-size: 0.95rem; margin-bottom: 4px; }
.team-role    { font-size: 0.75rem; color: var(--teal); font-weight: 600; }

/* ── Testimonials ── */
.testimonials-section { background: var(--teal-xlight); }
.testimonials-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
}
.testimonial-card { padding: 28px 24px; display: flex; flex-direction: column; gap: 16px; }
.stars            { display: flex; gap: 2px; }
blockquote        { font-size: 0.92rem; color: var(--text-light); line-height: 1.7; flex: 1; font-style: italic; }
.reviewer         { display: flex; align-items: center; gap: 12px; }
.reviewer img     { width: 42px; height: 42px; border-radius: 50%; object-fit: cover; }
.reviewer strong  { display: block; font-size: 0.88rem; color: var(--navy); }
.reviewer small   { font-size: 0.75rem; color: var(--text-light); }

/* ── Shop CTA ── */
.shop-cta-section { background: white; }
.shop-cta-card {
  background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 55%, var(--teal-dark) 100%);
  border-radius: var(--radius-xl);
  padding: 56px 64px;
  display: flex;
  align-items: center;
  gap: 48px;
  flex-wrap: wrap;
  overflow: hidden;
  position: relative;
}
.shop-cta-card::before {
  content: '';
  position: absolute;
  top: -60px;
  right: 280px;
  width: 300px;
  height: 300px;
  border-radius: 50%;
  border: 40px solid rgba(255,255,255,0.04);
  pointer-events: none;
}
.shop-cta-text h2  { color: white; margin: 8px 0 14px; }
.shop-cta-text p   { color: rgba(255,255,255,0.7); max-width: 380px; margin-bottom: 24px; }
.shop-cta-actions  { display: flex; gap: 12px; flex-wrap: wrap; }
.shop-pet-images   { display: flex; gap: -12px; margin-left: auto; position: relative; }
.shop-pet-img {
  width: 110px;
  height: 110px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid rgba(255,255,255,0.2);
  margin-left: -16px;
  transition: transform 300ms var(--ease-out), z-index 0ms;
}
.shop-pet-img:first-child { margin-left: 0; }
.shop-pet-img:hover { transform: scale(1.15) translateY(-6px); z-index: 2; }

/* ── CTA Banner ── */
.cta-banner { background: var(--gray-50); padding: 40px 0; border-top: 1px solid var(--border); }
.cta-inner  { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
.cta-icon   { width: 56px; height: 56px; border-radius: 50%; background: var(--teal-light); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.cta-label  { font-weight: 700; color: var(--navy); }
.cta-sub    { font-size: 0.85rem; color: var(--text-light); margin-top: 2px; }
.cta-avatars{ display: flex; }
.cta-avatar {
  width: 38px; height: 38px; border-radius: 50%;
  border: 2.5px solid white; object-fit: cover;
  margin-left: -10px; transition: transform var(--transition);
}
.cta-avatar:first-child { margin-left: 0; }
.cta-avatar:hover { transform: translateY(-4px) scale(1.1); z-index: 1; }
.cta-avatar-count {
  background: var(--teal); color: white;
  font-size: 0.68rem; font-weight: 700;
  display: flex; align-items: center; justify-content: center;
}

/* ── Responsive ── */
@media (max-width: 1024px) {
  .services-layout   { grid-template-columns: 1fr; }
  .services-grid     { grid-template-columns: repeat(3, 1fr); }
  .about-layout      { grid-template-columns: 1fr; }
  .about-image       { height: 360px; }
  .stats-grid        { grid-template-columns: repeat(2, 1fr); }
  .testimonials-grid { grid-template-columns: 1fr 1fr; }
  .shop-cta-card     { padding: 40px 32px; }
  .shop-pet-images   { display: none; }
}
@media (max-width: 768px) {
  .services-grid     { grid-template-columns: 1fr 1fr; }
  .testimonials-grid { grid-template-columns: 1fr; }
  .about-stats       { grid-template-columns: repeat(2, 1fr); }
  .grid-4            { grid-template-columns: repeat(2, 1fr) !important; }
}
@media (max-width: 480px) {
  .services-grid { grid-template-columns: 1fr; }
  .grid-4        { grid-template-columns: 1fr !important; }
  .cta-avatars   { display: none; }
}
</style>

<template>
  <section class="hero-slider" @mouseenter="pauseAuto" @mouseleave="resumeAuto">

    <!-- ── Slides (full-bleed) ─────────────────────────── -->
    <div class="slides-track">
      <div
        v-for="(slide, i) in slides"
        :key="i"
        :class="['slide', { active: current === i, leaving: leaving === i }]"
        :aria-hidden="current !== i"
      >
        <img
          :src="slide.image"
          :alt="slide.alt"
          class="slide-bg"
          loading="lazy"
        />
        <!-- gradient: heavy navy left → transparent right -->
        <div class="slide-gradient"/>
      </div>
    </div>

    <!-- ── Content ─────────────────────────────────────── -->
    <div class="hero-content container">
      <div class="hero-left">

        <Transition name="slide-text" mode="out-in">
          <div class="slide-copy" :key="current">
            <span class="hero-eyebrow">{{ slides[current].eyebrow }}</span>
            <h1 class="hero-title" v-html="slides[current].title"></h1>
            <p class="hero-sub">{{ slides[current].sub }}</p>
          </div>
        </Transition>

        <div class="hero-actions">
          <RouterLink to="/appointments" class="btn btn-primary btn-lg hero-btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="4" width="18" height="18" rx="2"/>
              <line x1="16" y1="2" x2="16" y2="6"/>
              <line x1="8" y1="2" x2="8" y2="6"/>
              <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            Book Appointment
          </RouterLink>
          <RouterLink to="/services" class="btn hero-btn-ghost">
            Explore Services
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="5" y1="12" x2="19" y2="12"/>
              <polyline points="12 5 19 12 12 19"/>
            </svg>
          </RouterLink>
        </div>

        <div class="hero-trust">
          <div class="trust-pill" v-for="t in trust" :key="t">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <path :d="t.icon"/>
            </svg>
            {{ t.label }}
          </div>
        </div>

      </div>

      <!-- Floating stat card -->
      <div class="stat-float">
        <div class="stat-float-inner">
          <span class="sf-num">4,200<sup>+</sup></span>
          <span class="sf-label">Pets Served</span>
        </div>
        <div class="sf-ring">
          <svg viewBox="0 0 56 56" class="sf-ring-svg">
            <circle cx="28" cy="28" r="24" fill="none" stroke="var(--teal)" stroke-width="3" stroke-dasharray="150.8" stroke-dashoffset="30" stroke-linecap="round"/>
          </svg>
        </div>
      </div>
    </div>

    <!-- ── Progress indicators ───────────────────────────── -->
    <div class="hero-controls">
      <div class="slide-nav">
        <button
          v-for="(s, i) in slides"
          :key="i"
          :class="['slide-dot-wrap', { active: current === i }]"
          @click="goTo(i)"
          :aria-label="`Slide ${i + 1}: ${s.eyebrow}`"
        >
          <span class="slide-dot-bg"/>
          <span v-if="current === i" class="slide-dot-progress" :style="{ animationDuration: `${duration}ms` }"/>
        </button>
      </div>

      <div class="slide-counter">
        <span class="sc-current">{{ String(current + 1).padStart(2, '0') }}</span>
        <span class="sc-sep">/</span>
        <span class="sc-total">{{ String(slides.length).padStart(2, '0') }}</span>
      </div>

      <div class="slide-arrows">
        <button class="arrow-btn" @click="prev" aria-label="Previous">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <button class="arrow-btn" @click="next" aria-label="Next">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>
    </div>

    <!-- Scroll cue -->
    <div class="scroll-cue">
      <span>Scroll</span>
      <div class="scroll-line"/>
    </div>

  </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const duration = 6000

const slides = [
  {
    eyebrow: 'Nairobi\'s Premier Veterinary Clinic',
    title:   'Exceptional Care<br>For Every<br><span class="accent">Beloved Pet</span>',
    sub:     'Compassionate vets, advanced diagnostics, and a warm welcoming environment — all under one roof.',
    image:   'https://images.unsplash.com/photo-1601758003122-53c40e686a19?auto=format&fit=crop&w=1600&q=80',
    alt:     'Dog being cared for at vet clinic',
  },
  {
    eyebrow: 'Expert Feline & Small Animal Care',
    title:   'Every Cat Deserves<br><span class="accent">Expert Attention</span>',
    sub:     'From routine wellness visits to complex surgery, our feline specialists have you covered.',
    image:   'https://images.unsplash.com/photo-1615789591457-74a63395c990?auto=format&fit=crop&w=1600&q=80',
    alt:     'Happy cat at veterinary clinic',
  },
  {
    eyebrow: 'Advanced In-House Diagnostics',
    title:   'Fast Answers.<br><span class="accent">Better Outcomes.</span>',
    sub:     'In-house blood panels, imaging, and pathology — same-day results for your peace of mind.',
    image:   'https://images.unsplash.com/photo-1583337130417-3346a1be7dee?auto=format&fit=crop&w=1600&q=80',
    alt:     'Veterinarian with happy dog',
  },
  {
    eyebrow: 'Safe, Comfortable Boarding',
    title:   'A Home Away<br>From Home For<br><span class="accent">Your Pet</span>',
    sub:     'Our kennel team provides enriching, monitored care so you travel with total peace of mind.',
    image:   'https://images.unsplash.com/photo-1597595735637-05a49627ee29?auto=format&fit=crop&w=1600&q=80',
    alt:     'Happy dogs at boarding facility',
  },
  {
    eyebrow: 'Trusted Since 2010',
    title:   'Where Pets Come<br>First, Always &<br><span class="accent">Forever.</span>',
    sub:     'Over 4,200 animals cared for. Join thousands of Nairobi families who trust Poseidon Vet.',
    image:   'https://images.unsplash.com/photo-1450778869180-41d0601e046e?auto=format&fit=crop&w=1600&q=80',
    alt:     'Happy pets with their owners',
  },
]

const trust = [
  { label: '4,200+ Pets Served',       icon: 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z' },
  { label: '5-Star Rated',              icon: 'M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z' },
  { label: 'Mon–Sat · 9am–5pm',        icon: 'M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10zm0-14v4l3 3' },
]

const current = ref(0)
const leaving = ref(-1)
let timer     = null
let paused    = false

function goTo(index) {
  if (index === current.value) return
  leaving.value = current.value
  current.value = index
  setTimeout(() => (leaving.value = -1), 1200)
  resetAuto()
}
function next() { goTo((current.value + 1) % slides.length) }
function prev() { goTo((current.value - 1 + slides.length) % slides.length) }

function startAuto() {
  timer = setInterval(() => { if (!paused) next() }, duration)
}
function resetAuto() { clearInterval(timer); startAuto() }
function pauseAuto()  { paused = true }
function resumeAuto() { paused = false }

onMounted(startAuto)
onUnmounted(() => clearInterval(timer))
</script>

<style scoped>
/* ── Container ── */
.hero-slider {
  position: relative;
  width: 100%;
  height: 100svh;
  min-height: 640px;
  max-height: 900px;
  overflow: hidden;
  background: var(--navy);
}

/* ── Slides ── */
.slides-track { position: absolute; inset: 0; }

.slide {
  position: absolute;
  inset: 0;
  opacity: 0;
  transition: opacity 1.4s ease-in-out;
  will-change: opacity;
}
.slide.active  { opacity: 1; z-index: 2; }
.slide.leaving { opacity: 0; z-index: 1; }

.slide-bg {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center 30%;
  transform-origin: center center;
  transition: filter 1.4s ease-in-out;
}
.slide.leaving .slide-bg { filter: blur(6px) brightness(0.7); }

/* Ken Burns: different direction per slide to add variety */
.slide.active .slide-bg { animation: kb-zoom 8s ease-out forwards; }
@keyframes kb-zoom {
  0%   { transform: scale(1)    translate(0, 0); }
  100% { transform: scale(1.12) translate(-1.5%, 0.8%); }
}
.slide:nth-child(even).active .slide-bg { animation: kb-zoom-r 8s ease-out forwards; }
@keyframes kb-zoom-r {
  0%   { transform: scale(1.05) translate(1%, 0); }
  100% { transform: scale(1.14) translate(-0.5%, -0.5%); }
}

/* Gradient overlay: heavy navy left, fades right */
.slide-gradient {
  position: absolute;
  inset: 0;
  background:
    linear-gradient(90deg,
      rgba(10,26,55,0.96)  0%,
      rgba(10,26,55,0.80) 35%,
      rgba(10,26,55,0.40) 60%,
      rgba(10,26,55,0.05) 85%,
      transparent         100%
    ),
    linear-gradient(0deg,
      rgba(10,26,55,0.55) 0%,
      transparent         40%
    );
}

/* ── Content ── */
.hero-content {
  position: relative;
  z-index: 10;
  height: 100%;
  display: flex;
  align-items: center;
  gap: 40px;
  padding-top: 80px;
}

.hero-left { flex: 1; max-width: 600px; }

/* ── Text transitions ── */
.slide-copy { margin-bottom: 36px; }

.slide-text-enter-active { transition: opacity 700ms 150ms ease-out, transform 700ms 150ms ease-out; }
.slide-text-leave-active  { transition: opacity 350ms ease-in,          transform 350ms ease-in; }
.slide-text-enter-from    { opacity: 0; transform: translateY(28px); }
.slide-text-leave-to      { opacity: 0; transform: translateY(-20px); }

.hero-eyebrow {
  display: inline-block;
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--teal);
  margin-bottom: 16px;
  padding: 4px 12px;
  border: 1px solid rgba(11,191,178,0.35);
  border-radius: 100px;
  background: rgba(11,191,178,0.1);
}
.hero-title {
  font-size: clamp(2.4rem, 5.5vw, 4rem);
  font-weight: 800;
  line-height: 1.1;
  color: white;
  margin-bottom: 20px;
  text-wrap: balance;
}
:deep(.accent) { color: var(--teal); position: relative; display: inline-block; }
:deep(.accent)::after {
  content: '';
  position: absolute;
  bottom: 2px;
  left: 0;
  right: 0;
  height: 3px;
  background: var(--teal);
  border-radius: 2px;
  opacity: 0.5;
}
.hero-sub {
  font-size: clamp(0.95rem, 1.5vw, 1.1rem);
  color: rgba(255,255,255,0.75);
  line-height: 1.7;
  max-width: 440px;
}

/* ── Actions ── */
.hero-actions {
  display: flex;
  gap: 14px;
  flex-wrap: wrap;
  margin-bottom: 32px;
}
.hero-btn-primary {
  background: var(--teal);
  color: white;
  border: none;
  box-shadow: 0 4px 20px rgba(11,191,178,0.4);
}
.hero-btn-primary:hover {
  background: var(--teal-dark);
  box-shadow: 0 6px 28px rgba(11,191,178,0.5);
  transform: translateY(-2px);
}
.hero-btn-ghost {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 13px 24px;
  border-radius: var(--radius-sm);
  font-size: 0.9rem;
  font-weight: 600;
  color: white;
  border: 1.5px solid rgba(255,255,255,0.3);
  background: rgba(255,255,255,0.08);
  backdrop-filter: blur(8px);
  transition: background var(--transition), border-color var(--transition);
}
.hero-btn-ghost:hover { background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.5); }

/* ── Trust pills ── */
.hero-trust { display: flex; flex-wrap: wrap; gap: 12px; }
.trust-pill {
  display: flex;
  align-items: center;
  gap: 7px;
  font-size: 0.78rem;
  font-weight: 500;
  color: rgba(255,255,255,0.6);
  padding: 5px 12px;
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.12);
  border-radius: 100px;
  backdrop-filter: blur(6px);
}
.trust-pill svg { stroke: var(--teal); flex-shrink: 0; }

/* ── Floating stat ── */
.stat-float {
  position: absolute;
  right: 8%;
  top: 50%;
  transform: translateY(-50%);
  width: 140px;
  height: 140px;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10;
}
.stat-float-inner {
  position: absolute;
  inset: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: rgba(10,26,55,0.65);
  border: 1px solid rgba(11,191,178,0.3);
  border-radius: 50%;
  backdrop-filter: blur(12px);
}
.sf-num {
  font-size: 1.6rem;
  font-weight: 800;
  color: var(--teal);
  line-height: 1;
}
.sf-num sup { font-size: 0.7em; }
.sf-label   { font-size: 0.65rem; color: rgba(255,255,255,0.6); margin-top: 4px; text-align: center; padding: 0 16px; }
.sf-ring {
  position: absolute;
  inset: -6px;
  animation: ring-spin 12s linear infinite;
}
@keyframes ring-spin { to { transform: rotate(360deg); } }
.sf-ring-svg { width: 100%; height: 100%; }

/* ── Controls ── */
.hero-controls {
  position: absolute;
  bottom: 36px;
  left: 0;
  right: 0;
  z-index: 20;
  display: flex;
  align-items: center;
  gap: 24px;
  padding: 0 max(24px, calc((100vw - 1200px) / 2 + 24px));
}

.slide-nav { display: flex; gap: 8px; }
.slide-dot-wrap {
  position: relative;
  width: 48px;
  height: 4px;
  border-radius: 2px;
  background: none;
  border: none;
  padding: 0;
  cursor: pointer;
  overflow: hidden;
}
.slide-dot-bg {
  position: absolute;
  inset: 0;
  background: rgba(255,255,255,0.2);
  border-radius: 2px;
}
.slide-dot-progress {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  background: var(--teal);
  border-radius: 2px;
  animation: dot-fill linear forwards;
}
@keyframes dot-fill { from { width: 0; } to { width: 100%; } }
.slide-dot-wrap.active .slide-dot-bg { background: rgba(255,255,255,0.35); }

.slide-counter { display: flex; align-items: center; gap: 4px; margin-left: auto; }
.sc-current { font-size: 1.4rem; font-weight: 800; color: white; line-height: 1; }
.sc-sep     { font-size: 0.85rem; color: rgba(255,255,255,0.3); margin: 0 2px; }
.sc-total   { font-size: 0.85rem; color: rgba(255,255,255,0.4); }

.slide-arrows { display: flex; gap: 8px; }
.arrow-btn {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: 1.5px solid rgba(255,255,255,0.25);
  background: rgba(255,255,255,0.08);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background var(--transition), border-color var(--transition), transform var(--transition);
  backdrop-filter: blur(6px);
}
.arrow-btn:hover { background: rgba(255,255,255,0.2); border-color: var(--teal); }
.arrow-btn:active { transform: scale(0.93); }

/* ── Scroll cue ── */
.scroll-cue {
  position: absolute;
  right: 40px;
  bottom: 36px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  z-index: 20;
}
.scroll-cue span {
  writing-mode: vertical-rl;
  font-size: 0.65rem;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: rgba(255,255,255,0.35);
}
.scroll-line {
  width: 1px;
  height: 48px;
  background: linear-gradient(to bottom, rgba(255,255,255,0.3), transparent);
  animation: scroll-pulse 2s ease-in-out infinite;
}
@keyframes scroll-pulse {
  0%,100% { opacity: 1; transform: scaleY(1); }
  50%      { opacity: 0.4; transform: scaleY(0.6); }
}

/* ── Responsive ── */
@media (max-width: 900px) {
  .stat-float  { display: none; }
  .hero-title  { font-size: clamp(2rem, 6vw, 3rem); }
  .slide-dot-wrap { width: 32px; }
  .scroll-cue  { display: none; }
}
@media (max-width: 600px) {
  .hero-slider { max-height: 720px; }
  .hero-content { padding-top: 96px; }
  .hero-actions { gap: 10px; }
  .hero-controls { padding: 0 20px; gap: 14px; }
  .slide-arrows { display: none; }
  .trust-pill:last-child { display: none; }
}
</style>

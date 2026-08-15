<template>
  <div class="portal-root">

    <!-- ═══════════ LOGIN ═══════════ -->
    <div v-if="!isLoggedIn" class="login-split">
      <div class="login-left">
        <div class="login-brand">
          <svg class="login-logo-mark" viewBox="0 0 100 100" fill="none">
            <ellipse cx="50" cy="62" rx="26" ry="22" stroke="#E02020" stroke-width="6" stroke-linecap="round"/>
            <ellipse cx="32" cy="36" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
            <ellipse cx="50" cy="29" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
            <ellipse cx="68" cy="36" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
            <ellipse cx="19" cy="50" rx="8" ry="11" stroke="#E02020" stroke-width="5" stroke-linecap="round"/>
            <ellipse cx="81" cy="50" rx="8" ry="11" stroke="#E02020" stroke-width="5" stroke-linecap="round"/>
            <path d="M50 72 C46 67, 38 63, 38 57 C38 52, 50 52, 50 59 C50 52, 62 52, 62 57 C62 63, 54 67, 50 72Z" stroke="#E02020" stroke-width="5" stroke-linejoin="round"/>
          </svg>
          <span class="login-brand-word">poseidonvet</span>
          <span class="login-brand-portal">Client Portal</span>
        </div>

        <div class="login-body">
          <template v-if="authLoading">
            <p class="text-light">Loading…</p>
          </template>

          <template v-else-if="authStage === 'identifier'">
            <h1>Sign in to your account</h1>
            <p>Access your pet's health records, appointments, and invoices.</p>

            <form @submit.prevent="handleIdentify" class="login-form">
              <div class="form-group">
                <label class="form-label">Phone number or email</label>
                <div class="input-icon-wrap">
                  <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                  <input class="form-control icon-left" v-model.trim="identifier" placeholder="0712 345 678 or you@example.com" required autofocus />
                </div>
              </div>

              <div v-if="authError" class="login-error">{{ authError }}</div>

              <button type="submit" class="btn btn-primary login-btn" :disabled="submitting">
                {{ submitting ? 'Checking…' : 'Continue' }}
              </button>
            </form>

            <div class="login-divider"><span>or</span></div>
            <RouterLink to="/appointments" class="btn btn-secondary" style="width:100%;justify-content:center">Book Without Account</RouterLink>
          </template>

          <template v-else-if="authStage === 'password'">
            <h1>Enter your password</h1>
            <p>{{ identifier }}</p>

            <form @submit.prevent="handleClientLogin" class="login-form">
              <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" v-model="password" placeholder="••••••••" required autofocus />
              </div>

              <div v-if="authError" class="login-error">{{ authError }}</div>

              <button type="submit" class="btn btn-primary login-btn" :disabled="submitting">
                {{ submitting ? 'Signing in…' : 'Sign In' }}
              </button>
            </form>

            <div class="otp-actions">
              <button type="button" class="link-btn" @click="backToIdentifier">← Use a different number/email</button>
              <button type="button" class="link-btn" :disabled="submitting" @click="handleRequestOtp">Forgot password? Use a code instead</button>
            </div>
          </template>

          <template v-else-if="authStage === 'otp'">
            <h1>Enter your code</h1>
            <p>We sent a 6-digit code to <strong>{{ maskedDestination }}</strong>.</p>

            <form @submit.prevent="handleVerifyOtp" class="login-form">
              <div class="form-group">
                <label class="form-label">6-digit code</label>
                <input
                  class="form-control otp-input"
                  v-model.trim="code"
                  inputmode="numeric"
                  maxlength="6"
                  placeholder="••••••"
                  required
                  autofocus
                />
              </div>

              <div v-if="authError" class="login-error">{{ authError }}</div>

              <button type="submit" class="btn btn-primary login-btn" :disabled="submitting">
                {{ submitting ? 'Verifying…' : 'Verify' }}
              </button>
            </form>

            <div class="otp-actions">
              <button type="button" class="link-btn" @click="backToIdentifier">← Use a different number/email</button>
              <button type="button" class="link-btn" :disabled="submitting" @click="handleRequestOtp">Resend code</button>
            </div>
          </template>

          <template v-else-if="authStage === 'set-password'">
            <h1>{{ hasPassword ? 'Reset your password' : 'Set your password' }}</h1>
            <p>{{ hasPassword ? 'Choose a new password for next time.' : 'Create a password so you can skip the code next time.' }}</p>

            <form @submit.prevent="handleSetPassword" class="login-form">
              <div class="form-group">
                <label class="form-label">New password</label>
                <input type="password" class="form-control" v-model="password" placeholder="At least 8 characters" required autofocus minlength="8" />
              </div>
              <div class="form-group">
                <label class="form-label">Confirm password</label>
                <input type="password" class="form-control" v-model="passwordConfirmation" placeholder="Repeat password" required minlength="8" />
              </div>

              <div v-if="authError" class="login-error">{{ authError }}</div>

              <button type="submit" class="btn btn-primary login-btn" :disabled="submitting">
                {{ submitting ? 'Saving…' : 'Save & Continue' }}
              </button>
            </form>
          </template>
        </div>
      </div>

      <div class="login-right">
        <img src="https://images.unsplash.com/photo-1770836037793-95bdbf190f71?auto=format&fit=crop&w=900&q=80" alt="" class="login-bg" />
        <div class="login-right-glass">
          <div class="login-stat-row">
            <div class="ls-stat" v-for="s in loginStats" :key="s.label">
              <strong>{{ s.num }}</strong><span>{{ s.label }}</span>
            </div>
          </div>
          <blockquote>"Poseidon Vet feels like a second home for our pets. The team genuinely cares."</blockquote>
          <div class="lq-author">
            <img src="https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?auto=format&fit=crop&w=80&h=80&q=80" alt="Aisha M" />
            <div><strong>Aisha M.</strong><span>Pet Owner · Langata, Nairobi</span></div>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════ DASHBOARD ═══════════ -->
    <div v-else class="dash-layout">

      <!-- Mobile sidebar backdrop -->
      <div v-if="mobileNavOpen" class="mobile-nav-backdrop" @click="mobileNavOpen = false"></div>

      <!-- Sidebar -->
      <aside class="sidebar" :class="{ 'mobile-open': mobileNavOpen }">
        <div class="sb-head">
          <svg class="sb-logo-mark" viewBox="0 0 100 100" fill="none">
            <ellipse cx="50" cy="62" rx="26" ry="22" stroke="#E02020" stroke-width="6" stroke-linecap="round"/>
            <ellipse cx="32" cy="36" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
            <ellipse cx="50" cy="29" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
            <ellipse cx="68" cy="36" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
            <ellipse cx="19" cy="50" rx="8" ry="11" stroke="#E02020" stroke-width="5" stroke-linecap="round"/>
            <ellipse cx="81" cy="50" rx="8" ry="11" stroke="#E02020" stroke-width="5" stroke-linecap="round"/>
            <path d="M50 72 C46 67, 38 63, 38 57 C38 52, 50 52, 50 59 C50 52, 62 52, 62 57 C62 63, 54 67, 50 72Z" stroke="#E02020" stroke-width="5" stroke-linejoin="round"/>
          </svg>
          <div class="sb-brand-text">
            <strong>poseidonvet</strong>
            <span>Client Portal</span>
          </div>
        </div>

        <nav class="sb-nav">
          <div class="sb-section-label">Main</div>
          <button v-for="item in mainNav" :key="item.id" :class="['sb-item', { active: activeSection === item.id }]" @click="activeSection = item.id">
            <span class="sb-item-icon">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path :d="item.icon"/></svg>
            </span>
            <span class="sb-item-label">{{ item.label }}</span>
            <span v-if="item.badge" class="sb-badge">{{ item.badge }}</span>
          </button>

          <div class="sb-section-label" style="margin-top:20px">Health</div>
          <button v-for="item in healthNav" :key="item.id" :class="['sb-item', { active: activeSection === item.id }]" @click="activeSection = item.id">
            <span class="sb-item-icon">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path :d="item.icon"/></svg>
            </span>
            <span class="sb-item-label">{{ item.label }}</span>
            <span v-if="item.badge" class="sb-badge">{{ item.badge }}</span>
          </button>

          <div class="sb-section-label" style="margin-top:20px">Account</div>
          <button v-for="item in accountNav" :key="item.id" :class="['sb-item', { active: activeSection === item.id }]" @click="activeSection = item.id">
            <span class="sb-item-icon">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path :d="item.icon"/></svg>
            </span>
            <span class="sb-item-label">{{ item.label }}</span>
            <span v-if="item.badge" class="sb-badge">{{ item.badge }}</span>
          </button>

          <div class="sb-section-label" style="margin-top:20px">Links</div>
          <RouterLink to="/shop" class="sb-item">
            <span class="sb-item-icon"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg></span>
            <span class="sb-item-label">Visit Shop</span>
          </RouterLink>
          <RouterLink to="/appointments" class="sb-item">
            <span class="sb-item-icon"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></span>
            <span class="sb-item-label">Book Appointment</span>
          </RouterLink>
        </nav>
      </aside>

      <!-- Main area -->
      <div class="dash-main">

        <!-- Top bar -->
        <header class="dash-topbar">
          <div class="topbar-left">
            <button class="dash-hamburger" aria-label="Open menu" @click="mobileNavOpen = true">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <div class="topbar-title-group">
              <p class="topbar-eyebrow">CLIENT PORTAL</p>
              <h2 class="topbar-title">{{ currentLabel }}</h2>
            </div>
          </div>
          <div class="topbar-right">
            <RouterLink to="/cart" class="tb-icon-btn" title="Cart">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </RouterLink>
            <button class="tb-icon-btn" title="Notifications" @click="activeSection = 'notifications'">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
              <span v-if="notifications.length" class="tb-dot"></span>
            </button>
            <div class="tb-user" @click="activeSection = 'settings'">
              <span class="tb-user-name">{{ client.full_name }}</span>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <button class="tb-signout-btn" @click="handleLogout" title="Sign out">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </button>
          </div>
        </header>

        <!-- Scrollable body -->
        <div class="dash-body">

          <!-- ── OVERVIEW ── -->
          <div v-if="activeSection === 'overview'" class="pane">

            <!-- Welcome banner -->
            <div class="welcome-banner">
              <div class="wb-bg"></div>
              <div class="wb-content">
                <p class="wb-eyebrow">WELCOME BACK</p>
                <h2>{{ greeting }}, {{ firstName }} 👋</h2>
                <p v-if="topNotification">
                  {{ topNotification.title }}.
                  <a href="#" @click.prevent="activeSection = 'notifications'" class="wb-link">View →</a>
                </p>
                <p v-else>Everything looks up to date with your pets.</p>
              </div>
              <button class="btn wb-btn" @click="activeSection='appointments'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                BOOK APPOINTMENT
              </button>
            </div>

            <!-- Quick actions -->
            <div class="quick-actions">
              <button class="qa-pill" v-for="qa in quickActions" :key="qa.label" @click="activeSection = qa.section">
                <span class="qa-icon" :style="{ background: qa.bg }">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" :style="{ stroke: qa.color }"><path :d="qa.icon"/></svg>
                </span>
                {{ qa.label }}
              </button>
            </div>

            <!-- KPI row -->
            <div class="kpi-row">
              <div class="kpi-card" v-for="k in kpiCards" :key="k.label" @click="activeSection = k.section">
                <div class="kpi-icon-wrap" :style="{ background: k.bg }">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" :style="{ stroke: k.color }" stroke-width="2"><path :d="k.icon"/></svg>
                </div>
                <div class="kpi-body">
                  <div class="kpi-num">{{ k.value }}</div>
                  <div class="kpi-label">{{ k.label }}</div>
                  <div class="kpi-sub" :style="{ color: k.color }">{{ k.sub }}</div>
                </div>
              </div>
            </div>

            <!-- Two-col panel -->
            <div class="ov-cols">

              <!-- My Pets -->
              <div class="ov-panel">
                <div class="ov-panel-head">
                  <h3>My Pets</h3>
                  <button class="panel-more" @click="activeSection = 'pets'">Manage →</button>
                </div>
                <div v-if="petsLoading" class="empty-hint">Loading…</div>
                <div v-else-if="!pets.length" class="empty-hint">No pets on file yet.</div>
                <div v-else class="pet-list">
                  <div class="pet-row" v-for="p in pets" :key="p.id" @click="activeSection = 'pets'">
                    <div class="pet-avatar-wrap pet-avatar-fallback">{{ p.name?.[0] || '?' }}</div>
                    <div class="pet-info">
                      <strong>{{ p.name }}</strong>
                      <small>{{ p.breed || p.species }} · {{ p.age || 'age unknown' }} · {{ sexLabel(p.sex) }}</small>
                    </div>
                    <span v-if="p.is_deceased" class="pet-vacc-badge due">DECEASED</span>
                  </div>
                </div>
              </div>

              <!-- Notifications -->
              <div class="ov-panel">
                <div class="ov-panel-head">
                  <h3>Notifications</h3>
                  <button class="panel-more" @click="activeSection = 'notifications'">View all →</button>
                </div>
                <div v-if="notificationsLoading" class="empty-hint">Loading…</div>
                <div v-else-if="!notifications.length" class="empty-hint">You're all caught up.</div>
                <div v-else class="reminder-list">
                  <div class="reminder-row" v-for="n in notifications.slice(0, 4)" :key="n.id">
                    <div class="reminder-dot" :class="urgencyClass(n.urgency)"></div>
                    <div class="reminder-info">
                      <strong>{{ n.title }}</strong>
                      <small>{{ n.body }}</small>
                    </div>
                  </div>
                </div>
              </div>

            </div>

            <!-- Upcoming appointments -->
            <div class="ov-panel" style="margin-top:20px">
              <div class="ov-panel-head">
                <h3>Upcoming Appointments</h3>
                <button class="panel-more" @click="activeSection = 'appointments'">View all →</button>
              </div>
              <div v-if="apptsLoading" class="empty-hint">Loading…</div>
              <div v-else-if="!upcomingAppts.length" class="empty-hint">No upcoming appointments. <RouterLink to="/appointments" class="wb-link">Book one →</RouterLink></div>
              <div v-else class="appt-list">
                <div class="appt-row" v-for="a in upcomingAppts.slice(0, 5)" :key="a.id">
                  <div class="appt-date-block">
                    <span class="appt-month">{{ formatMonth(a.appointment_date) }}</span>
                    <span class="appt-day">{{ formatDay(a.appointment_date) }}</span>
                  </div>
                  <div class="appt-info">
                    <strong>{{ a.service_type || 'Appointment' }}</strong>
                    <small>{{ formatTime(a.appointment_time) }} · {{ a.doctor ? `Dr. ${a.doctor.name}` : 'Doctor TBC' }} · {{ a.pet?.name }}</small>
                  </div>
                  <span :class="['status-chip', statusClass(a.status)]">{{ a.status.replace('_', ' ').toLowerCase() }}</span>
                </div>
              </div>
            </div>

          </div>

          <!-- ── MY PETS ── -->
          <div v-if="activeSection === 'pets'" class="pane">
            <div v-if="petsLoading" class="empty-hint">Loading pets…</div>
            <div v-else-if="!pets.length" class="empty-state-block">No pets on file yet. Give us a call and we'll add your pet's records.</div>
            <div v-else class="pets-detail-grid">
              <div class="pet-profile-card" v-for="p in pets" :key="p.id">
                <div class="pp-cover" :style="{ background: 'linear-gradient(135deg, #0BBFB2 0%, #0D2B4B 100%)' }">
                  <div class="pp-avatar pet-avatar-fallback pp-avatar-lg">{{ p.name?.[0] || '?' }}</div>
                  <span v-if="p.is_deceased" class="pp-health-badge due">Deceased</span>
                </div>
                <div class="pp-body">
                  <h3>{{ p.name }}</h3>
                  <p class="pp-sub">{{ p.species }}{{ p.breed ? ` · ${p.breed}` : '' }}</p>
                  <div class="pp-attrs">
                    <div class="pp-attr"><span class="pp-attr-key">Age</span><span class="pp-attr-val">{{ p.age || '—' }}</span></div>
                    <div class="pp-attr"><span class="pp-attr-key">Sex</span><span class="pp-attr-val">{{ sexLabel(p.sex) }}</span></div>
                    <div class="pp-attr"><span class="pp-attr-key">Weight</span><span class="pp-attr-val">{{ p.weight_kg ? `${p.weight_kg} kg` : '—' }}</span></div>
                    <div class="pp-attr"><span class="pp-attr-key">Colour</span><span class="pp-attr-val">{{ p.color || '—' }}</span></div>
                    <div class="pp-attr"><span class="pp-attr-key">Microchip</span><span class="pp-attr-val">{{ p.microchip || '—' }}</span></div>
                    <div class="pp-attr"><span class="pp-attr-key">Patient No.</span><span class="pp-attr-val">{{ p.patient_no || '—' }}</span></div>
                  </div>
                  <div class="pp-divider"></div>
                  <p class="pp-section-label">Allergies</p>
                  <p class="pp-notes">{{ p.allergies?.length ? p.allergies.join(', ') : 'None on file' }}</p>
                  <div class="pp-divider"></div>
                  <p class="pp-section-label">Chronic Conditions</p>
                  <p class="pp-notes">{{ p.chronic_conditions?.length ? p.chronic_conditions.join(', ') : 'None on file' }}</p>
                  <template v-if="p.notes">
                    <div class="pp-divider"></div>
                    <p class="pp-section-label">Notes</p>
                    <p class="pp-notes">{{ p.notes }}</p>
                  </template>
                </div>
              </div>
            </div>
          </div>

          <!-- ── APPOINTMENTS ── -->
          <div v-if="activeSection === 'appointments'" class="pane">
            <div class="tab-row">
              <button :class="['tab-btn', { active: apptTab === 'upcoming' }]" @click="apptTab = 'upcoming'">Upcoming ({{ upcomingAppts.length }})</button>
              <button :class="['tab-btn', { active: apptTab === 'past' }]"     @click="apptTab = 'past'">Past History ({{ pastAppts.length }})</button>
            </div>
            <div v-if="apptsLoading" class="empty-hint">Loading appointments…</div>
            <div v-else class="table-card">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Date &amp; Time</th><th>Service</th><th>Pet</th><th>Veterinarian</th><th>Status</th><th>Notes</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="a in (apptTab === 'upcoming' ? upcomingAppts : pastAppts)" :key="a.id">
                    <td><strong>{{ formatDate(a.appointment_date) }}</strong><small style="display:block;color:var(--text-light)">{{ formatTime(a.appointment_time) }}</small></td>
                    <td>{{ a.service_type || '—' }}</td>
                    <td><span class="pet-chip">{{ a.pet?.name || '—' }}</span></td>
                    <td>{{ a.doctor ? `Dr. ${a.doctor.name}` : '—' }}</td>
                    <td><span :class="['status-chip', statusClass(a.status)]">{{ a.status.replace('_', ' ').toLowerCase() }}</span></td>
                    <td style="font-size:0.8rem;color:var(--text-light)">{{ a.notes || '—' }}</td>
                  </tr>
                  <tr v-if="(apptTab === 'upcoming' ? upcomingAppts : pastAppts).length === 0">
                    <td colspan="6" class="empty-hint">No {{ apptTab }} appointments.</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <RouterLink to="/appointments" class="btn btn-primary" style="margin-top:20px;display:inline-flex;gap:8px">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Book New Appointment
            </RouterLink>
          </div>

          <!-- ── HEALTH RECORDS ── -->
          <div v-if="activeSection === 'records'" class="pane">
            <div v-if="recordsLoading" class="empty-hint">Loading health records…</div>
            <div v-else class="records-cols">
              <div class="records-left">
                <h3 class="records-heading">Health Timeline</h3>
                <div v-if="!medicalRecords.length" class="empty-hint">No finalized visit records yet.</div>
                <div v-else class="timeline">
                  <div class="tl-item" v-for="r in medicalRecords" :key="r.id">
                    <div class="tl-marker wellness"></div>
                    <div class="tl-body">
                      <p class="tl-date">{{ formatDate(r.visit_date) }}</p>
                      <div class="tl-card">
                        <div class="tl-card-head">
                          <strong>{{ r.chief_complaint }}</strong>
                        </div>
                        <p class="tl-meta">{{ r.pet?.name }}{{ r.doctor ? ` · Dr. ${r.doctor.name}` : '' }}</p>
                        <p class="tl-notes" v-if="r.assessment"><strong>Assessment:</strong> {{ r.assessment }}</p>
                        <p class="tl-notes" v-if="r.plan"><strong>Plan:</strong> {{ r.plan }}</p>
                        <div v-if="Object.keys(r.vitals || {}).length" class="tl-tags">
                          <span class="tl-tag" v-for="(val, key) in r.vitals" :key="key">{{ key.replace('_', ' ') }}: {{ val }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="records-right">
                <h3 class="records-heading">Pet Quick Facts</h3>
                <div class="vacc-schedule" v-for="p in pets" :key="p.id">
                  <div class="vs-pet-head">
                    <div class="pet-avatar-fallback vs-avatar">{{ p.name?.[0] || '?' }}</div>
                    <strong>{{ p.name }}</strong>
                  </div>
                  <div class="vs-rows">
                    <div class="vs-row"><span class="vs-name">Allergies</span><span class="vs-date">{{ p.allergies?.length ? p.allergies.join(', ') : 'None' }}</span></div>
                    <div class="vs-row"><span class="vs-name">Chronic conditions</span><span class="vs-date">{{ p.chronic_conditions?.length ? p.chronic_conditions.join(', ') : 'None' }}</span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- ── PRESCRIPTIONS ── -->
          <div v-if="activeSection === 'prescriptions'" class="pane">
            <div v-if="prescriptionsLoading" class="empty-hint">Loading prescriptions…</div>
            <div v-else-if="!prescriptions.length" class="empty-state-block">No prescriptions on file.</div>
            <div v-else class="rx-grid">
              <div class="rx-card" v-for="rx in prescriptions" :key="rx.id">
                <div class="rx-head">
                  <div class="rx-icon-wrap" :class="rx.status === 'dispensed' ? 'active' : 'expired'">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2z"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="12" y1="9" x2="12" y2="15"/></svg>
                  </div>
                  <div class="rx-head-info">
                    <strong>{{ rx.prescription_no }}</strong>
                    <small>For {{ rx.pet?.name }} · {{ formatDate(rx.issued_date) }}</small>
                  </div>
                  <span class="rx-chip" :class="rx.status === 'dispensed' ? 'active' : 'expired'">{{ rx.status }}</span>
                </div>
                <div class="rx-details">
                  <div class="rx-row" v-for="item in rx.items" :key="item.drug_name">
                    <span class="rx-key">{{ item.drug_name }}</span>
                    <span>{{ item.dosage }} · {{ item.frequency }} · {{ item.duration }}</span>
                  </div>
                  <p v-if="!rx.items?.length" class="text-light" style="font-size:0.8rem">No items listed.</p>
                  <p v-if="rx.notes" class="text-light" style="font-size:0.8rem;margin-top:4px">{{ rx.notes }}</p>
                </div>
                <div class="rx-footer">
                  <RouterLink v-if="rx.status === 'pending'" to="/shop" class="btn btn-primary btn-sm">Shop Related Products</RouterLink>
                </div>
              </div>
            </div>
          </div>

          <!-- ── NOTIFICATIONS ── -->
          <div v-if="activeSection === 'notifications'" class="pane">
            <div v-if="notificationsLoading" class="empty-hint">Loading notifications…</div>
            <div v-else-if="!notifications.length" class="empty-state-block">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:12px"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
              <p>You're all caught up — nothing needs your attention right now.</p>
            </div>
            <div v-else class="notif-feed">
              <div class="notif-card" v-for="n in notifications" :key="n.id">
                <div class="notif-icon" :class="urgencyClass(n.urgency)">
                  <svg v-if="n.type === 'appointment'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                  <svg v-else-if="n.type === 'invoice'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                  <svg v-else-if="n.type === 'prescription'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2z"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="12" y1="9" x2="12" y2="15"/></svg>
                  <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                </div>
                <div class="notif-content">
                  <strong>{{ n.title }}</strong>
                  <p>{{ n.body }}</p>
                  <small>{{ formatDate(n.date) }}</small>
                </div>
              </div>
            </div>
          </div>

          <!-- ── BILLING ── -->
          <div v-if="activeSection === 'billing'" class="pane">
            <div class="billing-cards">
              <div class="billing-summary">
                <div class="bs-row"><span>Total Invoiced</span><strong>{{ fmtMoney(billingTotals.invoiced) }}</strong></div>
                <div class="bs-row"><span>Total Paid</span><strong style="color:#16A34A">{{ fmtMoney(billingTotals.paid) }}</strong></div>
                <div class="bs-row"><span>Outstanding Balance</span><strong :style="{ color: billingTotals.balance > 0 ? 'var(--red)' : '#16A34A' }">{{ fmtMoney(billingTotals.balance) }}</strong></div>
              </div>
              <div v-if="invoicesLoading" class="empty-hint" style="margin-top:24px">Loading invoices…</div>
              <div v-else class="table-card" style="margin-top:24px">
                <table class="data-table">
                  <thead><tr><th>Invoice</th><th>Date</th><th>Total</th><th>Balance</th><th>Status</th><th></th></tr></thead>
                  <tbody>
                    <tr v-for="inv in invoices" :key="inv.id">
                      <td><span style="font-size:0.82rem;color:var(--teal);font-weight:600">{{ inv.invoice_no }}</span></td>
                      <td style="font-size:0.82rem">{{ formatDate(inv.invoice_date) }}</td>
                      <td style="font-weight:600">{{ fmtMoney(inv.total_amount) }}</td>
                      <td style="font-size:0.82rem">{{ inv.balance > 0 ? fmtMoney(inv.balance) : '—' }}</td>
                      <td><span class="status-chip" :style="{ background: inv.status_color + '22', color: inv.status_color }">{{ inv.status_label }}</span></td>
                      <td>
                        <button class="link-btn" @click="downloadInvoice(inv)">Download PDF</button>
                      </td>
                    </tr>
                    <tr v-if="!invoices.length"><td colspan="6" class="empty-hint">No invoices yet.</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- ── SETTINGS ── -->
          <div v-if="activeSection === 'settings'" class="pane">
            <div class="settings-grid">
              <div class="settings-panel">
                <h3>Profile</h3>
                <div class="profile-avatar-row">
                  <div class="profile-avatar">{{ userInitial }}</div>
                  <div><strong>{{ client.full_name }}</strong><p>{{ client.email || client.phone }}</p></div>
                </div>
                <div class="form-group"><label class="form-label">Client No.</label><input class="form-control" :value="client.client_no" disabled /></div>
                <div class="form-group" style="margin-top:12px"><label class="form-label">Email</label><input class="form-control" :value="client.email" type="email" disabled /></div>
                <div class="form-group" style="margin-top:12px"><label class="form-label">Phone</label><input class="form-control" :value="client.phone" type="tel" disabled /></div>
                <div class="form-group" style="margin-top:12px">
                  <label class="form-label">Address</label>
                  <textarea class="form-control" rows="2" v-model="profileForm.address"></textarea>
                </div>
                <p class="field-hint">To change your name, phone, or email, please contact the clinic directly.</p>
                <button class="btn btn-primary btn-sm" style="margin-top:12px" :disabled="savingProfile" @click="saveProfile">
                  {{ savingProfile ? 'Saving…' : 'Save Changes' }}
                </button>
                <span v-if="profileSaved" class="save-confirm">Saved ✓</span>
              </div>

              <div class="settings-panel">
                <h3>Notification Preferences</h3>
                <div class="notif-list">
                  <div class="notif-item">
                    <div><strong>SMS</strong><p>Appointment &amp; billing alerts via text</p></div>
                    <label class="toggle"><input type="checkbox" v-model="profileForm.consent_sms" @change="saveProfile" /><span class="toggle-track"></span></label>
                  </div>
                  <div class="notif-item">
                    <div><strong>Email</strong><p>Receipts, records, and reminders</p></div>
                    <label class="toggle"><input type="checkbox" v-model="profileForm.consent_email" @change="saveProfile" /><span class="toggle-track"></span></label>
                  </div>
                  <div class="notif-item">
                    <div><strong>WhatsApp</strong><p>Quick updates where you already chat</p></div>
                    <label class="toggle"><input type="checkbox" v-model="profileForm.consent_whatsapp" @change="saveProfile" /><span class="toggle-track"></span></label>
                  </div>
                </div>
              </div>

              <div class="settings-panel">
                <h3>Security</h3>
                <div class="security-list">
                  <div class="security-item">
                    <div><strong>Sign-in method</strong><p>One-time codes sent to your phone or email — no password to manage.</p></div>
                  </div>
                  <div class="security-item">
                    <div><strong>This session</strong><p>Signing in elsewhere automatically signs you out here.</p></div>
                    <button class="btn btn-secondary btn-sm" @click="handleLogout">Sign Out</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, reactive, watch } from 'vue'
import { usePortalAuth }         from '../composables/usePortalAuth.js'
import {
  usePortalPets, usePortalAppointments, usePortalMedicalRecords,
  usePortalPrescriptions, usePortalInvoices, usePortalNotifications,
} from '../composables/usePortalData.js'
import { portalApi } from '../lib/portalApi.js'
import { useToast }  from '../composables/useToast.js'

const {
  client, loading: authLoading, isLoggedIn,
  requestOtp, verifyOtp, login: clientLogin, setPassword,
  logout, updateProfile,
} = usePortalAuth()
const { success, error: toastError } = useToast()

/* ── Auth (password-first, OTP for setup/recovery) ── */
const authStage              = ref('identifier')
const identifier             = ref('')
const password                = ref('')
const passwordConfirmation    = ref('')
const code                     = ref('')
const submitting                = ref(false)
const authError                 = ref('')
const maskedDestination         = ref('')
const hasPassword               = ref(false)

function handleIdentify() {
  authStage.value = 'password'
  authError.value = ''
}

async function handleClientLogin() {
  submitting.value = true
  authError.value = ''
  try {
    await clientLogin(identifier.value, password.value)
  } catch (e) {
    if (e.data?.needs_setup) {
      password.value = ''
      await handleRequestOtp()
      return
    }
    authError.value = e.message
  } finally {
    submitting.value = false
  }
}

async function handleRequestOtp() {
  submitting.value = true
  authError.value = ''
  try {
    const res = await requestOtp(identifier.value)
    maskedDestination.value = res.masked_destination || 'your registered contact'
    authStage.value = 'otp'
  } catch (e) {
    authError.value = e.message
  } finally {
    submitting.value = false
  }
}

async function handleVerifyOtp() {
  submitting.value = true
  authError.value = ''
  try {
    const { hasPassword: existing } = await verifyOtp(identifier.value, code.value)
    hasPassword.value = existing
    code.value = ''
    authStage.value = 'set-password'
  } catch (e) {
    authError.value = e.message
  } finally {
    submitting.value = false
  }
}

async function handleSetPassword() {
  submitting.value = true
  authError.value = ''
  if (password.value !== passwordConfirmation.value) {
    authError.value = 'Passwords do not match.'
    submitting.value = false
    return
  }
  try {
    await setPassword(password.value, passwordConfirmation.value)
    authStage.value = 'identifier'
    identifier.value = ''
    password.value = ''
    passwordConfirmation.value = ''
  } catch (e) {
    authError.value = e.message
  } finally {
    submitting.value = false
  }
}

function backToIdentifier() {
  authStage.value = 'identifier'
  password.value = ''
  code.value = ''
  authError.value = ''
}

async function handleLogout() {
  await logout()
  activeSection.value = 'overview'
}

const userInitial = computed(() => client.value?.full_name?.[0]?.toUpperCase() || '?')
const firstName    = computed(() => client.value?.first_name || client.value?.full_name?.split(' ')[0] || 'there')
const greeting = computed(() => {
  const h = new Date().getHours()
  if (h < 12) return 'Good morning'
  if (h < 18) return 'Good afternoon'
  return 'Good evening'
})

/* ── Data (fetched once logged in) ── */
const { items: pets,             loading: petsLoading }           = usePortalPets()
const { items: appointments,     loading: apptsLoading }          = usePortalAppointments()
const { items: medicalRecords,   loading: recordsLoading }        = usePortalMedicalRecords()
const { items: prescriptions,    loading: prescriptionsLoading }  = usePortalPrescriptions()
const { items: invoices,         loading: invoicesLoading }       = usePortalInvoices()
const { items: notifications,    loading: notificationsLoading }  = usePortalNotifications()

const topNotification = computed(() => notifications.value[0] || null)

const today = new Date().toISOString().split('T')[0]
const upcomingAppts = computed(() =>
  appointments.value
    .filter(a => a.appointment_date >= today && !['CANCELLED', 'COMPLETED', 'NO_SHOW'].includes(a.status))
    .sort((a, b) => a.appointment_date.localeCompare(b.appointment_date))
)
const pastAppts = computed(() =>
  appointments.value
    .filter(a => !upcomingAppts.value.includes(a))
    .sort((a, b) => b.appointment_date.localeCompare(a.appointment_date))
)

const billingTotals = computed(() => {
  const invoiced = invoices.value.reduce((s, i) => s + i.total_amount, 0)
  const paid     = invoices.value.reduce((s, i) => s + i.paid_amount, 0)
  const balance  = invoices.value.reduce((s, i) => s + i.balance, 0)
  return { invoiced, paid, balance }
})

/* ── Profile form ── */
const profileForm  = reactive({ address: '', consent_sms: true, consent_email: true, consent_whatsapp: true })
const savingProfile = ref(false)
const profileSaved  = ref(false)

watch(client, (c) => {
  if (!c) return
  profileForm.address          = c.address || ''
  profileForm.consent_sms      = c.consent_sms
  profileForm.consent_email    = c.consent_email
  profileForm.consent_whatsapp = c.consent_whatsapp
}, { immediate: true })

async function saveProfile() {
  savingProfile.value = true
  profileSaved.value = false
  try {
    await updateProfile({ ...profileForm })
    profileSaved.value = true
    setTimeout(() => (profileSaved.value = false), 2000)
  } catch (e) {
    toastError(e.message)
  } finally {
    savingProfile.value = false
  }
}

async function downloadInvoice(invoice) {
  try {
    await portalApi.download(`/portal/invoices/${invoice.id}/pdf`, `${invoice.invoice_no}.pdf`)
  } catch (e) {
    toastError('Could not download that invoice right now.')
  }
}

/* ── Nav ── */
const activeSection  = ref('overview')
const mobileNavOpen  = ref(false)
watch(activeSection, () => { mobileNavOpen.value = false })
const currentLabel  = computed(() => {
  const all = [...mainNav, ...healthNav.value, ...accountNav.value]
  return all.find(n => n.id === activeSection.value)?.label ?? 'Overview'
})

const mainNav = [
  { id: 'overview',     label: 'Overview',     icon: 'M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z' },
  { id: 'pets',         label: 'My Pets',       icon: 'M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z' },
  { id: 'appointments', label: 'Appointments',  icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z' },
]
const healthNav = computed(() => [
  { id: 'records',       label: 'Health Records',  icon: 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z M14 2v6h6 M12 18v-6 M9 15h6' },
  { id: 'prescriptions', label: 'Prescriptions',   icon: 'M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2z M12 8v8 M8 12h8', badge: prescriptions.value.filter(p => p.status === 'pending').length || null },
])
const accountNav = computed(() => [
  { id: 'notifications', label: 'Notifications',      icon: 'M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9 M13.73 21a2 2 0 0 1-3.46 0', badge: notifications.value.length || null },
  { id: 'billing',       label: 'Billing & Invoices',  icon: 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z M14 2v6h6 M16 13H8 M16 17H8 M10 9H8' },
  { id: 'settings',      label: 'Settings',            icon: 'M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2 M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z' },
])

/* ── Quick actions ── */
const quickActions = [
  { label: 'Book Appointment', section: 'appointments',   icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z', bg: '#E3F9F8', color: '#0BBFB2' },
  { label: 'Prescriptions',    section: 'prescriptions',  icon: 'M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2z M12 8v8 M8 12h8', bg: '#DBEAFE', color: '#3B82F6' },
  { label: 'Notifications',    section: 'notifications',  icon: 'M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9', bg: '#F3E8FF', color: '#8B5CF6' },
  { label: 'Health Records',   section: 'records',        icon: 'M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4 M7 10l5 5 5-5 M12 15V3', bg: '#DCFCE7', color: '#16A34A' },
]

/* ── KPI cards ── */
const kpiCards = computed(() => [
  { label: 'ACTIVE PETS',           value: pets.value.filter(p => !p.is_deceased).length, sub: pets.value.map(p => p.name).slice(0, 2).join(' & ') || '—', section: 'pets', icon: 'M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z', bg: '#F0FDFC', color: '#0BBFB2' },
  { label: 'UPCOMING APPOINTMENTS', value: upcomingAppts.value.length, sub: upcomingAppts.value[0] ? `Next: ${formatDate(upcomingAppts.value[0].appointment_date)}` : 'None scheduled', section: 'appointments', icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z', bg: '#EFF6FF', color: '#3B82F6' },
  { label: 'OUTSTANDING BALANCE',   value: fmtMoney(billingTotals.value.balance), sub: billingTotals.value.balance > 0 ? 'Payment due' : 'All settled', section: 'billing', icon: 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z M14 2v6h6', bg: '#FFF8E1', color: '#F59E0B' },
  { label: 'NOTIFICATIONS',         value: notifications.value.length, sub: notifications.value.some(n => n.urgency === 'high') ? 'Needs attention' : 'Up to date', section: 'notifications', icon: 'M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9', bg: '#F5F3FF', color: '#8B5CF6' },
])

/* ── Appointments tab ── */
const apptTab = ref('upcoming')

/* ── Formatting helpers ── */
function formatDate(dateStr) {
  if (!dateStr) return '—'
  return new Date(dateStr + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}
function formatMonth(dateStr) {
  if (!dateStr) return ''
  return new Date(dateStr + 'T00:00:00').toLocaleDateString('en-US', { month: 'short' }).toUpperCase()
}
function formatDay(dateStr) {
  if (!dateStr) return ''
  return new Date(dateStr + 'T00:00:00').getDate().toString().padStart(2, '0')
}
function formatTime(timeStr) {
  if (!timeStr) return ''
  const [h, m] = timeStr.split(':')
  const hour = parseInt(h, 10)
  const period = hour >= 12 ? 'PM' : 'AM'
  const hour12 = hour % 12 || 12
  return `${hour12}:${m} ${period}`
}
function fmtMoney(n) {
  return 'KES ' + Number(n || 0).toLocaleString(undefined, { maximumFractionDigits: 0 })
}
function sexLabel(sex) {
  return { MALE: 'Male', FEMALE: 'Female', UNKNOWN: 'Unknown' }[sex] || sex || '—'
}
function statusClass(status) {
  const map = {
    CONFIRMED: 'confirmed', CHECKED_IN: 'confirmed', WAITING: 'pending',
    IN_CONSULTATION: 'pending', COMPLETED: 'completed', NO_SHOW: 'cancelled',
    CANCELLED: 'cancelled', RESCHEDULED: 'pending', DRAFT: 'pending',
  }
  return map[status] || 'completed'
}
function urgencyClass(urgency) {
  return { high: 'danger', normal: 'warn', low: 'ok' }[urgency] || 'ok'
}

/* ── Login page stats ── */
const loginStats = [
  { num: '4,200+', label: 'Pets treated' },
  { num: '15 yrs',  label: 'Experience'   },
  { num: '98%',     label: 'Satisfaction' },
]
</script>

<style scoped>
/* ══ Root ══ */
.portal-root { min-height: 100vh; background: var(--gray-50); }

/* ══ LOGIN ══ */
.login-split { display: grid; grid-template-columns: 460px 1fr; min-height: 100vh; }

.login-left {
  background: white;
  display: flex; flex-direction: column;
  padding: 0;
  overflow-y: auto;
}
.login-brand {
  display: flex; align-items: center; gap: 10px;
  padding: 32px 48px 0;
}
.login-logo-mark { width: 30px; height: 30px; flex-shrink: 0; }
.login-brand-word { font-size: 1.1rem; font-weight: 800; letter-spacing: -0.01em; color: var(--navy); }
.login-brand-portal { font-size: 0.68rem; color: var(--teal); font-weight: 700; letter-spacing: 0.04em; padding: 3px 9px; background: var(--teal-light); border-radius: 100px; margin-left: 4px; }

.login-body { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 40px 48px 48px; }
.login-body h1 { font-size: 1.6rem; margin-bottom: 8px; }
.login-body > p { color: var(--text-light); font-size: 0.88rem; margin-bottom: 28px; }

.input-icon-wrap { position: relative; }
.input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--gray-400); pointer-events: none; }
.form-control.icon-left { padding-left: 38px; }
.otp-input { font-size: 1.4rem; font-weight: 700; letter-spacing: 0.4em; text-align: center; }

.login-error { background: #FEE2E2; color: var(--red); font-size: 0.82rem; padding: 10px 14px; border-radius: var(--radius-sm); border-left: 3px solid var(--red); margin-top: 10px; }
.login-btn { width: 100%; justify-content: center; padding: 14px; margin-top: 18px; }
.login-divider { text-align: center; color: var(--gray-300); font-size: 0.75rem; margin: 18px 0; position: relative; }
.login-divider::before, .login-divider::after { content:''; position:absolute; top:50%; width:44%; height:1px; background:var(--border); }
.login-divider::before { left:0; } .login-divider::after { right:0; }

.otp-actions { display: flex; justify-content: space-between; margin-top: 16px; flex-wrap: wrap; gap: 8px; }
.link-btn { font-size: 0.8rem; color: var(--teal); font-weight: 600; }
.link-btn:hover { text-decoration: underline; }
.link-btn:disabled { opacity: 0.5; cursor: not-allowed; }

.login-right { position: relative; overflow: hidden; }
.login-bg    { width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; }
.login-right-glass {
  position: absolute; inset: 0;
  background: linear-gradient(to top, rgba(13,43,75,0.95) 0%, rgba(13,43,75,0.3) 55%, transparent 100%);
  display: flex; flex-direction: column; justify-content: flex-end;
  padding: 48px 56px;
}
.login-stat-row { display: flex; gap: 36px; margin-bottom: 40px; }
.ls-stat strong { display: block; font-size: 1.4rem; font-weight: 800; color: var(--teal); }
.ls-stat span   { font-size: 0.72rem; color: rgba(255,255,255,0.55); }
.login-right-glass blockquote { font-size: 1.15rem; font-style: italic; color: white; line-height: 1.65; max-width: 440px; margin-bottom: 20px; }
.lq-author { display: flex; align-items: center; gap: 12px; }
.lq-author img { width: 42px; height: 42px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,0.25); }
.lq-author strong { display: block; font-size: 0.88rem; color: white; }
.lq-author span   { font-size: 0.73rem; color: rgba(255,255,255,0.55); }

/* ══ DASHBOARD ══ */
.dash-layout { display: grid; grid-template-columns: 230px 1fr; min-height: 100vh; }

/* ── Sidebar ── */
.sidebar {
  background: #0A1628;
  display: flex; flex-direction: column;
  padding: 0;
  position: sticky; top: 0; height: 100vh; overflow-y: auto;
  scrollbar-width: none;
}
.sidebar::-webkit-scrollbar { display: none; }

.sb-head {
  display: flex; align-items: center; gap: 10px;
  padding: 22px 20px 20px;
  border-bottom: 1px solid rgba(255,255,255,0.07);
}
.sb-logo-mark { width: 30px; height: 30px; flex-shrink: 0; }
.sb-brand-text strong { display: block; font-size: 0.78rem; font-weight: 800; color: white; letter-spacing: 0.05em; }
.sb-brand-text span   { display: block; font-size: 0.62rem; color: rgba(255,255,255,0.35); }

.sb-nav { padding: 16px 10px; flex: 1; }
.sb-section-label {
  font-size: 0.58rem; font-weight: 700; letter-spacing: 0.14em;
  text-transform: uppercase; color: rgba(255,255,255,0.28);
  padding: 0 10px; margin-bottom: 4px;
}
.sb-item {
  display: flex; align-items: center; gap: 10px;
  width: 100%; padding: 9px 10px;
  border-radius: 8px;
  font-size: 0.8rem; font-weight: 500;
  color: rgba(255,255,255,0.5);
  text-align: left;
  transition: background 150ms ease, color 150ms ease;
  margin-bottom: 1px;
  text-decoration: none;
  position: relative;
}
.sb-item:hover { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.85); }
.sb-item.active {
  background: rgba(11,191,178,0.12);
  color: var(--teal);
  border-left: 2.5px solid var(--teal);
  padding-left: 7.5px;
}
.sb-item.active .sb-item-icon svg { stroke: var(--teal); opacity: 1; }
.sb-item-icon { width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.sb-item-icon svg { opacity: 0.55; transition: opacity 150ms ease; stroke: currentColor; }
.sb-item:hover .sb-item-icon svg { opacity: 0.9; }
.sb-badge {
  margin-left: auto; background: var(--teal); color: #0A1628;
  font-size: 0.6rem; font-weight: 800; border-radius: 100px;
  padding: 2px 7px; min-width: 18px; text-align: center; line-height: 1.4;
}

/* ── Top bar ── */
.dash-main { display: flex; flex-direction: column; min-height: 100vh; }
.dash-topbar {
  background: white;
  border-bottom: 1px solid var(--border);
  padding: 0 28px;
  height: 58px;
  display: flex; align-items: center; justify-content: space-between;
  position: sticky; top: 0; z-index: 20;
  box-shadow: 0 1px 0 var(--border);
}
.topbar-left { display: flex; align-items: center; gap: 12px; min-width: 0; }
.topbar-title-group { display: flex; flex-direction: column; min-width: 0; }
.dash-hamburger {
  display: none;
  align-items: center; justify-content: center;
  width: 34px; height: 34px; border-radius: 8px;
  color: var(--navy); flex-shrink: 0;
}
.dash-hamburger:hover { background: var(--gray-100); }
.topbar-eyebrow, .topbar-title { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.topbar-eyebrow { font-size: 0.58rem; font-weight: 700; letter-spacing: 0.12em; color: var(--text-light); text-transform: uppercase; }
.topbar-title   { font-size: 0.92rem; font-weight: 700; color: var(--navy); line-height: 1; }
.topbar-right   { display: flex; align-items: center; gap: 8px; }

.tb-icon-btn {
  width: 34px; height: 34px; border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  color: var(--gray-400); border: 1px solid var(--border);
  position: relative; transition: border-color var(--transition), color var(--transition);
  text-decoration: none;
}
.tb-icon-btn:hover { border-color: var(--teal); color: var(--teal); }
.tb-dot {
  position: absolute; top: 6px; right: 6px;
  width: 7px; height: 7px; background: var(--red);
  border-radius: 50%; border: 1.5px solid white;
}

.tb-user {
  display: flex; align-items: center; gap: 6px;
  padding: 6px 12px; border-radius: 8px; border: 1px solid var(--border);
  font-size: 0.82rem; font-weight: 600; color: var(--navy);
  cursor: pointer; transition: border-color var(--transition), background var(--transition);
}
.tb-user:hover { border-color: var(--teal); background: var(--teal-xlight); }

.tb-signout-btn {
  width: 34px; height: 34px; border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  color: rgba(192,57,43,0.5); border: 1px solid var(--border);
  transition: background var(--transition), color var(--transition), border-color var(--transition);
}
.tb-signout-btn:hover { background: #FEE2E2; color: var(--red); border-color: #FECACA; }

/* ── Body ── */
.dash-body { flex: 1; padding: 24px 28px; }
.pane { animation: page-in 220ms var(--ease-out) both; }
.empty-hint { text-align: center; color: var(--text-light); font-size: 0.85rem; padding: 20px 0; }
.empty-state-block { text-align: center; color: var(--text-light); font-size: 0.9rem; padding: 60px 20px; }

/* ── Welcome banner ── */
.welcome-banner {
  background: linear-gradient(130deg, #0A1628 0%, #0D3562 55%, #0A8075 100%);
  border-radius: 14px;
  padding: 28px 32px;
  display: flex; align-items: center; gap: 20px;
  margin-bottom: 18px;
  position: relative; overflow: hidden;
}
.wb-bg {
  position: absolute; right: -40px; top: -40px;
  width: 200px; height: 200px; border-radius: 50%;
  border: 40px solid rgba(255,255,255,0.04);
  pointer-events: none;
}
.wb-content { flex: 1; }
.wb-eyebrow { font-size: 0.6rem; font-weight: 700; letter-spacing: 0.14em; color: rgba(255,255,255,0.4); margin-bottom: 6px; }
.wb-content h2  { color: white; font-size: 1.25rem; margin-bottom: 6px; }
.wb-content > p { color: rgba(255,255,255,0.65); font-size: 0.85rem; }
.wb-link { color: var(--teal); font-weight: 600; }
.wb-btn {
  background: var(--teal); color: white;
  padding: 11px 20px; font-size: 0.78rem; font-weight: 700;
  letter-spacing: 0.04em; white-space: nowrap; flex-shrink: 0;
  border-radius: 8px;
}
.wb-btn:hover { background: var(--teal-dark); transform: translateY(-1px); box-shadow: 0 4px 16px rgba(11,191,178,0.35); }

/* ── Quick actions ── */
.quick-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 18px; }
.qa-pill {
  display: flex; align-items: center; gap: 9px;
  background: white; border: 1px solid var(--border);
  border-radius: 100px; padding: 8px 16px 8px 10px;
  font-size: 0.8rem; font-weight: 500; color: var(--navy);
  transition: border-color var(--transition), box-shadow var(--transition), transform var(--transition);
}
.qa-pill:hover { border-color: var(--teal); box-shadow: 0 2px 12px rgba(11,191,178,0.15); transform: translateY(-1px); }
.qa-icon {
  width: 26px; height: 26px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}

/* ── KPI row ── */
.kpi-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
.kpi-card {
  background: white; border: 1px solid var(--border);
  border-radius: 12px; padding: 18px 16px;
  display: flex; align-items: flex-start; gap: 14px;
  cursor: pointer;
  transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
  box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}
.kpi-card:hover { transform: translateY(-2px); box-shadow: 0 4px 20px rgba(0,0,0,0.08); border-color: rgba(11,191,178,0.25); }
.kpi-icon-wrap {
  width: 44px; height: 44px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.kpi-num   { font-size: 1.5rem; font-weight: 800; color: var(--teal); line-height: 1; }
.kpi-label { font-size: 0.6rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--text-light); margin: 4px 0 4px; }
.kpi-sub   { font-size: 0.72rem; font-weight: 500; }

/* ── Overview two-col ── */
.ov-cols { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.ov-panel {
  background: white; border: 1px solid var(--border);
  border-radius: 12px; padding: 18px 20px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.04);
}
.ov-panel-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
.ov-panel-head h3 { font-size: 0.88rem; font-weight: 700; }
.panel-more { font-size: 0.75rem; color: var(--teal); font-weight: 600; transition: opacity var(--transition); }
.panel-more:hover { opacity: 0.7; }

/* Pet list */
.pet-list { display: flex; flex-direction: column; gap: 8px; }
.pet-row {
  display: flex; align-items: center; gap: 12px;
  padding: 10px 12px; border-radius: 8px; background: var(--gray-50);
  cursor: pointer; transition: background var(--transition);
}
.pet-row:hover { background: var(--teal-xlight); }
.pet-avatar-wrap { width: 40px; height: 40px; border-radius: 50%; overflow: hidden; border: 2px solid var(--border); flex-shrink: 0; }
.pet-avatar-fallback {
  display: flex; align-items: center; justify-content: center;
  background: var(--teal-light); color: var(--teal-dark);
  font-weight: 800; font-size: 1rem;
}
.pp-avatar-lg { width: 80px; height: 80px; font-size: 1.8rem; border: 3px solid white; box-shadow: var(--shadow-md); }
.pet-info strong { display: block; font-size: 0.84rem; color: var(--navy); }
.pet-info small  { font-size: 0.72rem; color: var(--text-light); }
.pet-vacc-badge  {
  margin-left: auto; font-size: 0.6rem; font-weight: 700;
  padding: 3px 9px; border-radius: 100px;
  display: flex; align-items: center; gap: 4px;
}
.pet-vacc-badge.due { background: #FEE2E2; color: var(--red); }

/* Reminders */
.reminder-list { display: flex; flex-direction: column; gap: 8px; }
.reminder-row  { display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: 8px; background: var(--gray-50); }
.reminder-dot  { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.reminder-dot.danger { background: var(--red); }
.reminder-dot.warn   { background: #F59E0B; }
.reminder-dot.ok     { background: var(--teal); }
.reminder-info strong { display: block; font-size: 0.82rem; color: var(--navy); }
.reminder-info small  { font-size: 0.72rem; color: var(--text-light); }

/* Appointment list */
.appt-list { display: flex; flex-direction: column; gap: 8px; }
.appt-row  { display: flex; align-items: center; gap: 14px; padding: 12px; border-radius: 8px; background: var(--gray-50); }
.appt-date-block { background: var(--teal); color: white; border-radius: 8px; padding: 6px 10px; text-align: center; flex-shrink: 0; min-width: 44px; }
.appt-month { display: block; font-size: 0.55rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; }
.appt-day   { display: block; font-size: 1.1rem; font-weight: 800; line-height: 1; }
.appt-info strong { display: block; font-size: 0.86rem; color: var(--navy); }
.appt-info small  { font-size: 0.72rem; color: var(--text-light); }

/* Status chips */
.status-chip { font-size: 0.65rem; font-weight: 700; padding: 4px 10px; border-radius: 100px; text-transform: capitalize; margin-left: auto; }
.status-chip.confirmed { background: #DCFCE7; color: #166534; }
.status-chip.pending   { background: #FEF9C3; color: #854D0E; }
.status-chip.completed { background: var(--gray-100); color: var(--gray-600); }
.status-chip.paid      { background: #DCFCE7; color: #166534; }
.status-chip.cancelled { background: #FEE2E2; color: var(--red); }

/* ── My Pets ── */
.pets-detail-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
.pet-profile-card { background: white; border: 1px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
.pp-cover { height: 140px; position: relative; display: flex; align-items: flex-end; padding: 14px 20px; }
.pp-health-badge {
  position: absolute; top: 14px; right: 14px;
  font-size: 0.65rem; font-weight: 700; padding: 4px 12px; border-radius: 100px;
}
.pp-health-badge.due { background: rgba(254,226,226,0.9); color: var(--red); }
.pp-body { padding: 16px 20px; }
.pp-body h3  { font-size: 1.1rem; margin-bottom: 2px; }
.pp-sub  { font-size: 0.78rem; color: var(--text-light); margin-bottom: 16px; }
.pp-attrs { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px 16px; margin-bottom: 16px; }
.pp-attr  { display: flex; flex-direction: column; gap: 2px; }
.pp-attr-key { font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-light); font-weight: 600; }
.pp-attr-val { font-size: 0.82rem; color: var(--navy); font-weight: 600; }
.pp-divider  { height: 1px; background: var(--border); margin: 14px 0; }
.pp-section-label { font-size: 0.62rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--teal); font-weight: 700; margin-bottom: 10px; }
.pp-notes { font-size: 0.8rem; color: var(--text-light); line-height: 1.6; }

/* ── Tables ── */
.tab-row { display: flex; gap: 6px; margin-bottom: 16px; }
.tab-btn { padding: 7px 18px; border-radius: 100px; font-size: 0.8rem; font-weight: 600; color: var(--text-light); border: 1.5px solid var(--border); transition: all var(--transition); }
.tab-btn.active { background: var(--teal); color: white; border-color: var(--teal); }
.tab-btn:hover:not(.active) { border-color: var(--teal); color: var(--teal); }
.table-card { background: white; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.04); overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; font-size: 0.83rem; }
.data-table th { padding: 12px 16px; text-align: left; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-light); border-bottom: 1px solid var(--border); background: var(--gray-50); white-space: nowrap; }
.data-table td { padding: 12px 16px; border-bottom: 1px solid var(--border); vertical-align: middle; }
.data-table tr:last-child td { border-bottom: none; }
.data-table tr:hover td { background: var(--teal-xlight); }
.pet-chip { background: var(--teal-light); color: var(--teal-dark); font-size: 0.68rem; font-weight: 700; padding: 3px 10px; border-radius: 100px; }

/* ── Records ── */
.records-cols { display: grid; grid-template-columns: 1.4fr 1fr; gap: 32px; }
.records-heading { font-size: 0.92rem; margin-bottom: 18px; }
.timeline { display: flex; flex-direction: column; }
.tl-item  { display: flex; gap: 14px; }
.tl-marker { width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0; margin-top: 5px; }
.tl-marker.wellness  { background: var(--teal); }
.tl-body  { flex: 1; padding-bottom: 20px; }
.tl-date  { font-size: 0.7rem; color: var(--text-light); margin-bottom: 6px; }
.tl-card  { background: white; border: 1px solid var(--border); border-radius: 10px; padding: 12px 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); }
.tl-card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px; }
.tl-card-head strong { font-size: 0.86rem; }
.tl-meta  { font-size: 0.72rem; color: var(--text-light); margin-bottom: 6px; }
.tl-notes { font-size: 0.8rem; color: var(--text); line-height: 1.5; margin-top: 4px; }
.tl-tags  { display: flex; flex-wrap: wrap; gap: 5px; margin-top: 8px; }
.tl-tag   { background: var(--gray-100); color: var(--text-light); font-size: 0.65rem; padding: 2px 8px; border-radius: 4px; text-transform: capitalize; }

.vacc-schedule { margin-bottom: 20px; }
.vs-pet-head { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
.vs-avatar   { width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0; font-size: 0.8rem; }
.vs-pet-head strong { font-size: 0.86rem; }
.vs-rows { display: flex; flex-direction: column; gap: 5px; }
.vs-row  { display: flex; align-items: flex-start; gap: 8px; padding: 8px 12px; background: var(--gray-50); border-radius: 7px; font-size: 0.78rem; }
.vs-name { flex-shrink: 0; font-weight: 600; color: var(--navy); }
.vs-date { font-size: 0.75rem; color: var(--text-light); text-align: right; }

/* ── Prescriptions ── */
.rx-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px,1fr)); gap: 16px; }
.rx-card { background: white; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.04); }
.rx-head { display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-bottom: 1px solid var(--border); background: var(--gray-50); }
.rx-icon-wrap { width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.rx-icon-wrap.active  { background: var(--teal-light); color: var(--teal); }
.rx-icon-wrap.expired { background: var(--gray-100); color: var(--gray-400); }
.rx-icon-wrap svg { stroke: currentColor; }
.rx-head-info strong { display: block; font-size: 0.88rem; color: var(--navy); }
.rx-head-info small  { font-size: 0.72rem; color: var(--text-light); }
.rx-chip { margin-left: auto; font-size: 0.62rem; font-weight: 700; padding: 3px 10px; border-radius: 100px; text-transform: capitalize; }
.rx-chip.active  { background: #DCFCE7; color: #166534; }
.rx-chip.expired { background: var(--gray-100); color: var(--gray-400); }
.rx-details { padding: 14px 18px; display: flex; flex-direction: column; gap: 7px; }
.rx-row { display: flex; justify-content: space-between; gap: 12px; font-size: 0.8rem; }
.rx-key { color: var(--text-light); font-weight: 600; flex-shrink: 0; }
.rx-footer { display: flex; gap: 8px; padding: 12px 18px; border-top: 1px solid var(--border); }

/* ── Notifications feed ── */
.notif-feed { display: flex; flex-direction: column; gap: 10px; }
.notif-card {
  display: flex; gap: 14px; align-items: flex-start;
  background: white; border: 1px solid var(--border); border-radius: 12px;
  padding: 16px 18px; box-shadow: 0 1px 4px rgba(0,0,0,0.04);
}
.notif-icon {
  width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
}
.notif-icon.danger { background: #FEE2E2; color: var(--red); }
.notif-icon.warn   { background: #FEF3C7; color: #B45309; }
.notif-icon.ok     { background: var(--teal-light); color: var(--teal-dark); }
.notif-content strong { display: block; font-size: 0.88rem; color: var(--navy); margin-bottom: 2px; }
.notif-content p { font-size: 0.82rem; color: var(--text-light); margin-bottom: 4px; }
.notif-content small { font-size: 0.7rem; color: var(--gray-400); }

/* ── Billing ── */
.billing-summary {
  background: white; border: 1px solid var(--border); border-radius: 12px;
  padding: 20px; box-shadow: 0 1px 4px rgba(0,0,0,0.04);
  display: flex; gap: 32px; flex-wrap: wrap;
}
.bs-row { display: flex; flex-direction: column; gap: 4px; }
.bs-row span   { font-size: 0.72rem; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.06em; }
.bs-row strong { font-size: 1.1rem; color: var(--navy); }

/* ── Settings ── */
.settings-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
.settings-panel { background: white; border: 1px solid var(--border); border-radius: 12px; padding: 22px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); }
.settings-panel h3 { font-size: 0.92rem; margin-bottom: 18px; }
.profile-avatar-row { display: flex; align-items: center; gap: 14px; margin-bottom: 20px; }
.profile-avatar { width: 52px; height: 52px; border-radius: 50%; background: var(--teal); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.2rem; flex-shrink: 0; }
.profile-avatar-row strong { display: block; font-size: 0.9rem; color: var(--navy); }
.profile-avatar-row p { font-size: 0.75rem; color: var(--text-light); }
.field-hint { font-size: 0.72rem; color: var(--text-light); margin-top: 10px; }
.save-confirm { margin-left: 10px; font-size: 0.78rem; color: var(--teal-dark); font-weight: 600; }
.notif-list { display: flex; flex-direction: column; gap: 14px; }
.notif-item { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
.notif-item strong { display: block; font-size: 0.84rem; color: var(--navy); }
.notif-item p  { font-size: 0.72rem; color: var(--text-light); margin-top: 2px; }
.toggle { position: relative; display: inline-block; width: 40px; height: 22px; flex-shrink: 0; }
.toggle input { opacity: 0; width: 0; height: 0; }
.toggle-track { position: absolute; inset: 0; border-radius: 100px; background: var(--gray-200); cursor: pointer; transition: background var(--transition); }
.toggle-track::after { content: ''; position: absolute; left: 3px; top: 3px; width: 16px; height: 16px; border-radius: 50%; background: white; transition: transform var(--transition); box-shadow: var(--shadow-sm); }
.toggle input:checked + .toggle-track { background: var(--teal); }
.toggle input:checked + .toggle-track::after { transform: translateX(18px); }
.security-list { display: flex; flex-direction: column; gap: 12px; }
.security-item { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--border); }
.security-item:last-child { border-bottom: none; }
.security-item strong { display: block; font-size: 0.84rem; color: var(--navy); }
.security-item p  { font-size: 0.72rem; color: var(--text-light); margin-top: 2px; }

/* ── Responsive ── */
@media (max-width: 1100px) {
  .kpi-row { grid-template-columns: repeat(2,1fr); }
  .ov-cols { grid-template-columns: 1fr; }
  .settings-grid { grid-template-columns: 1fr; }
}
@media (max-width: 900px) {
  .login-split { grid-template-columns: 1fr; }
  .login-right { display: none; }
  .dash-layout { grid-template-columns: 1fr; }
  .dash-hamburger { display: flex; }

  /* Sidebar becomes a slide-in drawer, triggered by the topbar hamburger,
     instead of being hidden outright — otherwise most sections (billing,
     settings, health records...) become unreachable on mobile. */
  .sidebar {
    position: fixed;
    top: 0; left: 0; bottom: 0;
    width: 260px;
    height: 100vh;
    z-index: 1001;
    transform: translateX(-100%);
    transition: transform 260ms var(--ease-out);
  }
  .sidebar.mobile-open { transform: translateX(0); }
  .mobile-nav-backdrop {
    position: fixed; inset: 0;
    background: rgba(10,22,40,0.5);
    z-index: 1000;
  }

  .records-cols { grid-template-columns: 1fr; }
  .pets-detail-grid { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
  .login-body { padding: 28px 28px 36px; }
  .dash-body  { padding: 16px; }
  .dash-topbar { padding: 0 14px; }
  .tb-user-name { display: none; }
  .tb-user { padding: 6px 8px; }
  .kpi-row    { grid-template-columns: repeat(2,1fr); gap: 10px; }
  .quick-actions { gap: 6px; }
  .settings-grid { grid-template-columns: 1fr; }
}
</style>

<template>
  <div class="portal-root">

    <!-- ═══════════ LOGIN ═══════════ -->
    <div v-if="!loggedIn" class="login-split">
      <div class="login-left">
        <div class="login-brand">
          <img src="/Images/Poseidon/logo_horizontal_print_no_bg.png" alt="Poseidon Veterinary Clinic" class="login-brand-img" />
          <span class="login-brand-portal">Client Portal</span>
        </div>

        <div class="login-body">
          <h1>Sign in to your account</h1>
          <p>Access your pet's health records, appointments, and prescriptions.</p>

          <form @submit.prevent="login" class="login-form">
            <div class="form-group">
              <label class="form-label">Email address</label>
              <div class="input-icon-wrap">
                <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                <input class="form-control icon-left" type="email" v-model="creds.email" placeholder="you@example.com" autocomplete="email" required />
              </div>
            </div>

            <div class="form-group" style="margin-top:16px">
              <label class="form-label">Password</label>
              <div class="input-icon-wrap pw-wrap">
                <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <input :type="showPw ? 'text' : 'password'" class="form-control icon-left" v-model="creds.password" placeholder="••••••••" autocomplete="current-password" required />
                <button type="button" class="pw-toggle" @click="showPw = !showPw">
                  <svg v-if="!showPw" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  <svg v-else       width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
              </div>
            </div>

            <div class="login-meta">
              <label class="remember-label"><input type="checkbox" v-model="remember" /><span>Remember me</span></label>
              <a href="#" class="forgot-link">Forgot password?</a>
            </div>

            <div v-if="loginError" class="login-error">{{ loginError }}</div>

            <button type="submit" class="btn btn-primary login-btn">Sign In</button>
          </form>

          <div class="login-divider"><span>or</span></div>
          <RouterLink to="/appointments" class="btn btn-secondary" style="width:100%;justify-content:center">Book Without Account</RouterLink>

          <div class="demo-pill">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4m0-4h.01"/></svg>
            Demo — any email + password <code>demo1234</code>
          </div>
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

      <!-- Sidebar -->
      <aside class="sidebar">
        <div class="sb-head">
          <div class="sb-logo-mark">
            <svg viewBox="0 0 44 44" fill="none">
              <circle cx="22" cy="22" r="22" fill="var(--teal)"/>
              <ellipse cx="15" cy="14" rx="3.2" ry="4.2" fill="white" opacity="0.9"/>
              <ellipse cx="29" cy="14" rx="3.2" ry="4.2" fill="white" opacity="0.9"/>
              <ellipse cx="10" cy="21" rx="2.6" ry="3.6" fill="white" opacity="0.9"/>
              <ellipse cx="34" cy="21" rx="2.6" ry="3.6" fill="white" opacity="0.9"/>
              <path d="M22 20c-5.5 0-8.5 3.2-8.5 6.5s2.5 5.5 8.5 5.5 8.5-2.2 8.5-5.5-3-6.5-8.5-6.5z" fill="white"/>
            </svg>
          </div>
          <div class="sb-brand-text">
            <strong>Poseidon</strong>
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
            <p class="topbar-eyebrow">CLIENT PORTAL</p>
            <h2 class="topbar-title">{{ currentLabel }}</h2>
          </div>
          <div class="topbar-right">
            <RouterLink to="/cart" class="tb-icon-btn" title="Cart">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </RouterLink>
            <button class="tb-icon-btn" title="Notifications">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
              <span class="tb-dot"></span>
            </button>
            <div class="tb-user" @click="activeSection = 'settings'">
              <span class="tb-user-name">{{ userName }}</span>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <button class="tb-signout-btn" @click="loggedIn = false" title="Sign out">
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
                <h2>Good morning, {{ userName }} 👋</h2>
                <p>Max's dental check-up is due in 3 days. <a href="#" @click.prevent="activeSection='appointments'" class="wb-link">Book now →</a></p>
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
                <div class="pet-list">
                  <div class="pet-row" v-for="p in pets" :key="p.name" @click="activeSection = 'pets'">
                    <div class="pet-avatar-wrap" :style="{ background: p.avatarBg }">
                      <img :src="p.image" :alt="p.name" />
                    </div>
                    <div class="pet-info">
                      <strong>{{ p.name }}</strong>
                      <small>{{ p.breed }} · {{ p.age }} · {{ p.sex }}</small>
                    </div>
                    <span :class="['pet-vacc-badge', p.vaccStatus]">
                      <svg v-if="p.vaccStatus === 'vaccinated'" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                      {{ p.vaccStatus === 'vaccinated' ? 'VACCINATED' : 'DUE' }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Health Reminders -->
              <div class="ov-panel">
                <div class="ov-panel-head">
                  <h3>Health Reminders</h3>
                  <button class="panel-more" @click="activeSection = 'records'">View all →</button>
                </div>
                <div class="reminder-list">
                  <div class="reminder-row" v-for="r in reminders" :key="r.title">
                    <div class="reminder-dot" :class="r.urgency"></div>
                    <div class="reminder-info">
                      <strong>{{ r.title }}</strong>
                      <small>{{ r.detail }}</small>
                    </div>
                    <button class="reminder-book" @click="activeSection = 'appointments'">Book</button>
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
              <div class="appt-list">
                <div class="appt-row" v-for="a in upcomingAppts" :key="a.id">
                  <div class="appt-date-block">
                    <span class="appt-month">{{ a.month }}</span>
                    <span class="appt-day">{{ a.day }}</span>
                  </div>
                  <div class="appt-info">
                    <strong>{{ a.service }}</strong>
                    <small>{{ a.time }} · Dr. {{ a.vet }} · {{ a.pet }}</small>
                  </div>
                  <span :class="['status-chip', a.status]">{{ a.status }}</span>
                </div>
              </div>
            </div>

          </div>

          <!-- ── MY PETS ── -->
          <div v-if="activeSection === 'pets'" class="pane">
            <div class="pets-detail-grid">
              <div class="pet-profile-card" v-for="p in pets" :key="p.name">
                <div class="pp-cover" :style="{ background: p.headerGradient }">
                  <img :src="p.image" :alt="p.name" class="pp-avatar" />
                  <span :class="['pp-health-badge', p.vaccStatus]">
                    {{ p.vaccStatus === 'vaccinated' ? '✓ Vaccinated' : '! Due' }}
                  </span>
                </div>
                <div class="pp-body">
                  <h3>{{ p.name }}</h3>
                  <p class="pp-sub">{{ p.species }} · {{ p.breed }}</p>
                  <div class="pp-attrs">
                    <div class="pp-attr" v-for="(val, key) in p.attrs" :key="key">
                      <span class="pp-attr-key">{{ key }}</span>
                      <span class="pp-attr-val">{{ val }}</span>
                    </div>
                  </div>
                  <div class="pp-divider"></div>
                  <p class="pp-section-label">Vaccinations</p>
                  <div class="pp-vacc-list">
                    <div class="pp-vacc-item" v-for="v in p.vaccinations" :key="v.name">
                      <span>{{ v.name }}</span>
                      <div class="pp-vacc-right">
                        <span class="pp-vacc-date">{{ v.date }}</span>
                        <span :class="['pp-vacc-status', v.status]">{{ v.status.replace('-', ' ') }}</span>
                      </div>
                    </div>
                  </div>
                  <div class="pp-divider"></div>
                  <p class="pp-section-label">Allergies &amp; Notes</p>
                  <p class="pp-notes">{{ p.notes }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- ── APPOINTMENTS ── -->
          <div v-if="activeSection === 'appointments'" class="pane">
            <div class="tab-row">
              <button :class="['tab-btn', { active: apptTab === 'upcoming' }]" @click="apptTab = 'upcoming'">Upcoming ({{ upcomingAppts.length }})</button>
              <button :class="['tab-btn', { active: apptTab === 'past' }]"     @click="apptTab = 'past'">Past History</button>
            </div>
            <div class="table-card">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Date &amp; Time</th><th>Service</th><th>Pet</th><th>Veterinarian</th><th>Status</th><th>Notes</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="a in (apptTab === 'upcoming' ? upcomingAppts : pastAppts)" :key="a.id">
                    <td><strong>{{ a.date }}</strong><small style="display:block;color:var(--text-light)">{{ a.time }}</small></td>
                    <td>{{ a.service }}</td>
                    <td><span class="pet-chip">{{ a.pet }}</span></td>
                    <td>Dr. {{ a.vet }}</td>
                    <td><span :class="['status-chip', a.status]">{{ a.status }}</span></td>
                    <td style="font-size:0.8rem;color:var(--text-light)">{{ a.notes || '—' }}</td>
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
            <div class="records-cols">
              <div class="records-left">
                <h3 class="records-heading">Health Timeline</h3>
                <div class="timeline">
                  <div class="tl-item" v-for="r in medRecords" :key="r.id">
                    <div class="tl-marker" :class="r.type"></div>
                    <div class="tl-stem"></div>
                    <div class="tl-body">
                      <p class="tl-date">{{ r.date }}</p>
                      <div class="tl-card">
                        <div class="tl-card-head">
                          <strong>{{ r.title }}</strong>
                          <span :class="['type-chip', r.type]">{{ r.type }}</span>
                        </div>
                        <p class="tl-meta">{{ r.pet }} · Dr. {{ r.vet }}</p>
                        <p class="tl-notes">{{ r.notes }}</p>
                        <div v-if="r.findings" class="tl-tags">
                          <span class="tl-tag" v-for="f in r.findings" :key="f">{{ f }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="records-right">
                <h3 class="records-heading">Vaccination Schedule</h3>
                <div class="vacc-schedule" v-for="p in pets" :key="p.name">
                  <div class="vs-pet-head">
                    <img :src="p.image" :alt="p.name" class="vs-avatar" />
                    <strong>{{ p.name }}</strong>
                  </div>
                  <div class="vs-rows">
                    <div class="vs-row" v-for="v in p.vaccinations" :key="v.name">
                      <span class="vs-name">{{ v.name }}</span>
                      <span class="vs-date">{{ v.date }}</span>
                      <span :class="['vs-chip', v.status]">{{ v.status.replace('-', ' ') }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- ── PRESCRIPTIONS ── -->
          <div v-if="activeSection === 'prescriptions'" class="pane">
            <div class="rx-grid">
              <div class="rx-card" v-for="rx in prescriptions" :key="rx.id">
                <div class="rx-head">
                  <div class="rx-icon-wrap" :class="rx.status">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2z"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="12" y1="9" x2="12" y2="15"/></svg>
                  </div>
                  <div class="rx-head-info">
                    <strong>{{ rx.medication }}</strong>
                    <small>For {{ rx.pet }}</small>
                  </div>
                  <span :class="['rx-chip', rx.status]">{{ rx.status }}</span>
                </div>
                <div class="rx-details">
                  <div class="rx-row" v-for="(val, key) in rx.details" :key="key">
                    <span class="rx-key">{{ key }}</span><span>{{ val }}</span>
                  </div>
                </div>
                <div class="rx-footer">
                  <RouterLink v-if="rx.status === 'active'" to="/shop" class="btn btn-primary btn-sm">Refill via Shop</RouterLink>
                  <button class="btn btn-secondary btn-sm">Download PDF</button>
                </div>
              </div>
            </div>
          </div>

          <!-- ── MESSAGES ── -->
          <div v-if="activeSection === 'messages'" class="pane">
            <div class="messages-layout">
              <div class="msg-list">
                <div :class="['msg-thread', { unread: m.unread, active: activeMsg === m.id }]" v-for="m in messages" :key="m.id" @click="activeMsg = m.id">
                  <div class="msg-avatar" :style="{ background: m.avatarBg }">
                    <img :src="m.avatar" :alt="m.from" />
                  </div>
                  <div class="msg-preview">
                    <div class="msg-from-row">
                      <strong>{{ m.from }}</strong>
                      <span class="msg-time">{{ m.time }}</span>
                    </div>
                    <p class="msg-snippet">{{ m.snippet }}</p>
                  </div>
                  <div v-if="m.unread" class="msg-unread-dot"></div>
                </div>
              </div>
              <div class="msg-body" v-if="activeMsg">
                <div class="msg-full" v-for="m in messages.filter(x => x.id === activeMsg)" :key="m.id">
                  <div class="msg-full-head">
                    <img :src="m.avatar" :alt="m.from" class="msg-full-avatar" />
                    <div>
                      <strong>{{ m.from }}</strong>
                      <small>{{ m.role }}</small>
                    </div>
                    <span class="msg-time" style="margin-left:auto">{{ m.time }}</span>
                  </div>
                  <p class="msg-full-body">{{ m.body }}</p>
                  <div class="msg-reply-box">
                    <textarea class="form-control" rows="3" placeholder="Type your reply…"></textarea>
                    <button class="btn btn-primary btn-sm" style="margin-top:10px">Send Reply</button>
                  </div>
                </div>
                <div v-if="!activeMsg" class="msg-empty">Select a message to read</div>
              </div>
              <div class="msg-body msg-empty-state" v-else>
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <p>Select a conversation</p>
              </div>
            </div>
          </div>

          <!-- ── ORDERS ── -->
          <div v-if="activeSection === 'orders'" class="pane">
            <div class="orders-list">
              <div class="order-card" v-for="o in orders" :key="o.id">
                <div class="order-head">
                  <div><strong class="order-id">#{{ o.id }}</strong><span class="order-date"> · {{ o.date }}</span></div>
                  <span :class="['status-chip', o.status]">{{ o.status }}</span>
                </div>
                <div class="order-items">
                  <div class="order-item" v-for="item in o.items" :key="item.name">
                    <img :src="item.image" :alt="item.name" class="order-img" />
                    <div class="order-item-info"><strong>{{ item.name }}</strong><small>Qty: {{ item.qty }}</small></div>
                    <span class="order-price">KES {{ item.price.toLocaleString() }}</span>
                  </div>
                </div>
                <div class="order-foot">
                  <strong>Total: KES {{ o.total.toLocaleString() }}</strong>
                  <RouterLink to="/shop" class="btn btn-secondary btn-sm">Reorder</RouterLink>
                </div>
              </div>
            </div>
          </div>

          <!-- ── BILLING ── -->
          <div v-if="activeSection === 'billing'" class="pane">
            <div class="billing-cards">
              <div class="billing-summary">
                <div class="bs-row" v-for="b in billingSummary" :key="b.label">
                  <span>{{ b.label }}</span><strong :style="b.style ? { color: b.style } : {}">{{ b.value }}</strong>
                </div>
              </div>
              <div class="table-card" style="margin-top:24px">
                <table class="data-table">
                  <thead><tr><th>Invoice</th><th>Date</th><th>Description</th><th>Amount</th><th>Status</th></tr></thead>
                  <tbody>
                    <tr v-for="inv in invoices" :key="inv.id">
                      <td><span style="font-size:0.82rem;color:var(--teal);font-weight:600">{{ inv.id }}</span></td>
                      <td style="font-size:0.82rem">{{ inv.date }}</td>
                      <td style="font-size:0.82rem">{{ inv.desc }}</td>
                      <td style="font-weight:600">KES {{ inv.amount.toLocaleString() }}</td>
                      <td><span :class="['status-chip', inv.status]">{{ inv.status }}</span></td>
                    </tr>
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
                  <div><strong>{{ userName }}</strong><p>{{ creds.email }}</p></div>
                </div>
                <div class="form-2col">
                  <div class="form-group"><label class="form-label">First Name</label><input class="form-control" :value="userName.split(' ')[0]" /></div>
                  <div class="form-group"><label class="form-label">Last Name</label><input class="form-control" :value="userName.split(' ')[1] || ''" /></div>
                </div>
                <div class="form-group" style="margin-top:12px"><label class="form-label">Email</label><input class="form-control" :value="creds.email" type="email" /></div>
                <div class="form-group" style="margin-top:12px"><label class="form-label">Phone</label><input class="form-control" value="+254 712 345 678" type="tel" /></div>
                <button class="btn btn-primary btn-sm" style="margin-top:16px">Save Changes</button>
              </div>

              <div class="settings-panel">
                <h3>Notifications</h3>
                <div class="notif-list">
                  <div class="notif-item" v-for="n in notifPrefs" :key="n.label">
                    <div><strong>{{ n.label }}</strong><p>{{ n.desc }}</p></div>
                    <label class="toggle"><input type="checkbox" :checked="n.on" /><span class="toggle-track"></span></label>
                  </div>
                </div>
              </div>

              <div class="settings-panel">
                <h3>Security</h3>
                <div class="security-list">
                  <div class="security-item" v-for="s in securityItems" :key="s.label">
                    <div><strong>{{ s.label }}</strong><p>{{ s.desc }}</p></div>
                    <button class="btn btn-secondary btn-sm">{{ s.action }}</button>
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
import { ref, computed } from 'vue'

/* ── Auth ── */
const creds      = ref({ email: '', password: '' })
const loggedIn   = ref(false)
const showPw     = ref(false)
const remember   = ref(false)
const loginError = ref('')

function login() {
  if (creds.value.password !== 'demo1234') { loginError.value = 'Incorrect password. Hint: demo1234'; return }
  loginError.value = ''
  loggedIn.value   = true
}

const userName    = computed(() => creds.value.email.split('@')[0].replace(/[._-]/g, ' ').replace(/\b\w/g, c => c.toUpperCase()) || 'Martin')
const userInitial = computed(() => userName.value[0]?.toUpperCase() || 'M')

/* ── Nav ── */
const activeSection = ref('overview')
const currentLabel  = computed(() => {
  const all = [...mainNav, ...healthNav, ...accountNav]
  return all.find(n => n.id === activeSection.value)?.label ?? 'Overview'
})

const mainNav = [
  { id: 'overview',     label: 'Overview',     icon: 'M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z' },
  { id: 'pets',         label: 'My Pets',       icon: 'M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z' },
  { id: 'appointments', label: 'Appointments',  icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z' },
]
const healthNav = [
  { id: 'records',       label: 'Health Records',  icon: 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z M14 2v6h6 M12 18v-6 M9 15h6' },
  { id: 'prescriptions', label: 'Prescriptions',   icon: 'M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2z M12 8v8 M8 12h8', badge: 2 },
]
const accountNav = [
  { id: 'messages', label: 'Messages',         icon: 'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z', badge: 2 },
  { id: 'orders',   label: 'My Orders',        icon: 'M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z M3 6h18 M16 10a4 4 0 0 1-8 0' },
  { id: 'billing',  label: 'Billing & Invoices', icon: 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z M14 2v6h6 M16 13H8 M16 17H8 M10 9H8' },
  { id: 'settings', label: 'Settings',         icon: 'M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2 M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z' },
]

/* ── Quick actions ── */
const quickActions = [
  { label: 'Book Appointment',    section: 'appointments',  icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z', bg: '#E3F9F8', color: '#0BBFB2' },
  { label: 'Refill Prescription', section: 'prescriptions', icon: 'M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2z M12 8v8 M8 12h8', bg: '#DBEAFE', color: '#3B82F6' },
  { label: 'Message Vet',         section: 'messages',      icon: 'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z', bg: '#F3E8FF', color: '#8B5CF6' },
  { label: 'Order Supplies',      section: 'orders',        icon: 'M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z M3 6h18', bg: '#FFF8E1', color: '#F59E0B' },
  { label: 'Download Records',    section: 'records',       icon: 'M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4 M7 10l5 5 5-5 M12 15V3', bg: '#DCFCE7', color: '#16A34A' },
]

/* ── KPI cards ── */
const kpiCards = [
  { label: 'ACTIVE PETS',          value: '2', sub: 'Max & Luna',         section: 'pets',          icon: 'M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z', bg: '#F0FDFC', color: '#0BBFB2' },
  { label: 'UPCOMING APPOINTMENTS', value: '2', sub: 'Next: Jun 2',        section: 'appointments',  icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z', bg: '#EFF6FF', color: '#3B82F6' },
  { label: 'RECENT ORDERS',         value: '3', sub: '1 in transit',       section: 'orders',        icon: 'M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z M3 6h18', bg: '#FFF8E1', color: '#F59E0B' },
  { label: 'UNREAD MESSAGES',       value: '2', sub: 'From Dr. Akinyi',    section: 'messages',      icon: 'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z', bg: '#F5F3FF', color: '#8B5CF6' },
]

/* ── Pets ── */
const pets = [
  {
    name: 'Max', species: 'Dog', breed: 'Golden Retriever', age: '4 yrs', sex: 'Male',
    vaccStatus: 'vaccinated',
    headerGradient: 'linear-gradient(135deg, #0BBFB2 0%, #0D2B4B 100%)',
    avatarBg: '#E3F9F8',
    image: 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?auto=format&fit=crop&w=300&h=300&q=80',
    attrs: { Age: '4 years', Weight: '32 kg', Microchip: 'KE-2020-08321', Blood: 'DEA 1.1+', Neutered: 'Yes', Colour: 'Golden' },
    vaccinations: [
      { name: 'Rabies',     status: 'up-to-date', date: 'Jan 2026' },
      { name: 'DHPP',       status: 'up-to-date', date: 'Jan 2026' },
      { name: 'Bordetella', status: 'due-soon',   date: 'Aug 2026' },
    ],
    notes: 'No known allergies. Active dog, requires daily exercise. Prefers dry food.',
  },
  {
    name: 'Luna', species: 'Cat', breed: 'Domestic Shorthair', age: '3 yrs', sex: 'Female',
    vaccStatus: 'due',
    headerGradient: 'linear-gradient(135deg, #8B5CF6 0%, #0D2B4B 100%)',
    avatarBg: '#F5F3FF',
    image: 'https://images.unsplash.com/photo-1615789591457-74a63395c990?auto=format&fit=crop&w=300&h=300&q=80',
    attrs: { Age: '3 years', Weight: '3.8 kg', Microchip: 'KE-2021-14567', Blood: 'Type A', Neutered: 'Yes', Colour: 'Tabby' },
    vaccinations: [
      { name: 'Rabies', status: 'up-to-date', date: 'Mar 2026' },
      { name: 'FVRCP',  status: 'overdue',    date: 'Jun 2026' },
      { name: 'FeLV',   status: 'up-to-date', date: 'Mar 2026' },
    ],
    notes: 'Mild sensitivity to chicken-based food. On restricted diet per Dr. Zipporah.',
  },
]

/* ── Reminders ── */
const reminders = [
  { title: 'Dental clean due — Max',    detail: 'Overdue by 2 weeks',  urgency: 'danger' },
  { title: 'FVRCP booster — Luna',      detail: 'Overdue since Jun',   urgency: 'danger' },
  { title: 'Bordetella — Max',          detail: 'Due Aug 2026',        urgency: 'warn' },
  { title: 'Annual wellness — Max',     detail: 'Due Sep 2026',        urgency: 'ok' },
]

/* ── Appointments ── */
const apptTab = ref('upcoming')
const upcomingAppts = [
  { id:1, service:'Annual Wellness Check', date:'Jun 2, 2026',  month:'JUN', day:'02', time:'10:00 AM', vet:'Chesang',  pet:'Max',  status:'confirmed', notes:'Bring prior blood results' },
  { id:2, service:'FVRCP Vaccination',     date:'Jun 12, 2026', month:'JUN', day:'12', time:'2:30 PM',  vet:'Zipporah', pet:'Luna', status:'pending',   notes:'' },
]
const pastAppts = [
  { id:3, service:'Dental Cleaning',       date:'Apr 3, 2026',  month:'APR', day:'03', time:'11:00 AM', vet:'Sharon',   pet:'Max',  status:'completed', notes:'Minor tartar removed' },
  { id:4, service:'Emergency Consult',     date:'Jan 22, 2026', month:'JAN', day:'22', time:'8:00 PM',  vet:'Chesang',  pet:'Luna', status:'completed', notes:'Vomiting — resolved' },
  { id:5, service:'Wellness Exam',         date:'Sep 1, 2025',  month:'SEP', day:'01', time:'9:00 AM',  vet:'Kagucia',  pet:'Max',  status:'completed', notes:'All clear' },
]

/* ── Medical Records ── */
const medRecords = [
  { id:1, date:'Jun 2, 2026',  title:'Annual Wellness Exam',  type:'wellness',  pet:'Max',  vet:'Chesang',  notes:'Scheduled. All baseline metrics normal.', findings:['Weight stable','Heart rate normal'] },
  { id:2, date:'Apr 3, 2026',  title:'Dental Cleaning',       type:'procedure', pet:'Max',  vet:'Sharon',   notes:'Stage 1 dental disease. Minor tartar removed.', findings:['Stage 1 periodontal'] },
  { id:3, date:'Jan 22, 2026', title:'Emergency — Vomiting',  type:'emergency', pet:'Luna', vet:'Chesang',  notes:'Acute vomiting. IV fluids. Chicken allergy confirmed.', findings:['Dehydration (mild)','Chicken intolerance'] },
  { id:4, date:'Sep 1, 2025',  title:'Wellness Exam',         type:'wellness',  pet:'Max',  vet:'Kagucia',  notes:'Full bloodwork normal. Hip score 8/10.', findings:['Bloodwork normal','Hip 8/10'] },
]

/* ── Prescriptions ── */
const prescriptions = [
  { id:1, medication:'Apoquel (Oclacitinib)', pet:'Max',  status:'active',  details:{ Dosage:'16 mg', Frequency:'Once daily', Prescribed:'Dr. Chesang', Start:'Apr 3, 2026', Expiry:'Oct 3, 2026', Refills:'2 remaining', Instructions:'Give with food' } },
  { id:2, medication:'RC Hypoallergenic Diet', pet:'Luna', status:'active',  details:{ Dosage:'180 g/day', Frequency:'Split 2 meals', Prescribed:'Dr. Zipporah', Start:'Jan 25, 2026', Expiry:'Ongoing', Refills:'N/A', Instructions:'No chicken-based treats' } },
  { id:3, medication:'Metacam (Meloxicam)',    pet:'Max',  status:'expired', details:{ Dosage:'2.5 mg', Frequency:'Once daily × 5 days', Prescribed:'Dr. Sharon', Start:'Apr 3, 2026', Expiry:'Apr 8, 2026', Refills:'None', Instructions:'Post-dental pain. Completed.' } },
]

/* ── Messages ── */
const activeMsg = ref(null)
const messages = [
  { id:1, from:'Dr. Akinyi Odhiambo', role:'Veterinarian', avatar:'https://images.unsplash.com/photo-1594824476967-48c8b964273f?auto=format&fit=crop&w=80&h=80&q=80', avatarBg:'#E3F9F8', time:'Today 9:14 AM', snippet:"Luna's FVRCP booster is overdue — please book soon.", body:"Hi, just a reminder that Luna's FVRCP booster was due in June and is now overdue. This is important for her protection against respiratory viruses. Please call us or use the portal to book as soon as possible. Dr. Akinyi", unread:true },
  { id:2, from:'Poseidon Vet Clinic',  role:'System',       avatar:'https://images.unsplash.com/photo-1629001819038-87afe9fd3c9f?auto=format&fit=crop&w=80&h=80&q=80', avatarBg:'#EFF6FF', time:'Yesterday',      snippet:"Your appointment on Jun 2 has been confirmed.", body:"Your appointment for Max's Annual Wellness Check has been confirmed for June 2, 2026 at 10:00 AM with Dr. Chesang. Please arrive 5 minutes early. If you need to reschedule, contact us at least 24 hours in advance.", unread:true },
  { id:3, from:'Dr. Chesang',          role:'Chief Vet',    avatar:'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=80&h=80&q=80', avatarBg:'#FFF8E1', time:'Jun 10',           snippet:"Max's bloodwork results are ready.", body:"Good news — Max's bloodwork from his last visit came back completely normal. All values are within healthy ranges. I've added the detailed results to his health record in the portal. Keep up the good work with his diet!", unread:false },
]

/* ── Orders ── */
const orders = [
  { id:'PV-2026-0114', date:'Jun 20, 2026', status:'delivered', total:4750, items:[
    { name:'Apoquel 16mg × 30 tabs', qty:1, price:3500, image:'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?auto=format&fit=crop&w=80&h=80&q=80' },
    { name:"Hill's Science Diet Adult", qty:1, price:1250, image:'https://images.unsplash.com/photo-1568640347023-a616a30bc3bd?auto=format&fit=crop&w=80&h=80&q=80' },
  ]},
  { id:'PV-2026-0088', date:'Mar 5, 2026',  status:'delivered', total:6800, items:[
    { name:'RC Hypoallergenic 4kg ×2', qty:2, price:5600, image:'https://images.unsplash.com/photo-1568640347023-a616a30bc3bd?auto=format&fit=crop&w=80&h=80&q=80' },
    { name:'Cat Calming Collar',        qty:1, price:1200, image:'https://images.unsplash.com/photo-1615789591457-74a63395c990?auto=format&fit=crop&w=80&h=80&q=80' },
  ]},
]

/* ── Billing ── */
const billingSummary = [
  { label:'Total Paid (2026)',  value:'KES 18,450' },
  { label:'Outstanding Balance', value:'KES 0',      style:'#16A34A' },
  { label:'Next Invoice',        value:'After Jun 2 visit' },
]
const invoices = [
  { id:'INV-026-041', date:'Jun 20, 2026', desc:'Apoquel + Hill\'s Diet',   amount:4750, status:'paid' },
  { id:'INV-026-023', date:'Apr 3, 2026',  desc:'Dental Cleaning — Max',    amount:8200, status:'paid' },
  { id:'INV-026-009', date:'Jan 22, 2026', desc:'Emergency Consult — Luna', amount:5500, status:'paid' },
]

/* ── Settings ── */
const notifPrefs = [
  { label:'Appointment Reminders', desc:'24 hrs before your visit',       on:true  },
  { label:'Vaccination Alerts',    desc:'When a vaccine is due/overdue',  on:true  },
  { label:'Prescription Refills',  desc:'When running low',               on:true  },
  { label:'Promotions & Offers',   desc:'Deals from Poseidon Pet Shop',   on:false },
]
const securityItems = [
  { label:'Change Password',           desc:'Last changed 3 months ago',  action:'Update' },
  { label:'Two-Factor Authentication', desc:'Add an extra layer of security', action:'Enable' },
  { label:'Active Sessions',           desc:'1 active session',           action:'Manage' },
]

/* ── Login page stats ── */
const loginStats = [
  { num:'4,200+', label:'Pets treated' },
  { num:'15 yrs', label:'Experience'   },
  { num:'98%',    label:'Satisfaction' },
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
  display: flex; align-items: center; gap: 12px;
  padding: 32px 48px 0;
}
.login-brand-img { height: 34px; width: auto; display: block; }
.login-brand-portal { font-size: 0.68rem; color: var(--teal); font-weight: 700; letter-spacing: 0.04em; padding: 3px 9px; background: var(--teal-light); border-radius: 100px; }

.login-body { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 40px 48px 48px; }
.login-body h1 { font-size: 1.6rem; margin-bottom: 8px; }
.login-body > p { color: var(--text-light); font-size: 0.88rem; margin-bottom: 28px; }

.input-icon-wrap { position: relative; }
.input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--gray-400); pointer-events: none; }
.form-control.icon-left { padding-left: 38px; }
.pw-wrap .pw-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--gray-400); transition: color var(--transition); }
.pw-wrap .pw-toggle:hover { color: var(--teal); }

.login-meta { display: flex; align-items: center; justify-content: space-between; margin: 10px 0; }
.remember-label { display: flex; align-items: center; gap: 7px; font-size: 0.8rem; color: var(--text-light); cursor: pointer; }
.forgot-link { font-size: 0.8rem; color: var(--teal); }
.login-error { background: #FEE2E2; color: var(--red); font-size: 0.82rem; padding: 10px 14px; border-radius: var(--radius-sm); border-left: 3px solid var(--red); margin-top: 10px; }
.login-btn { width: 100%; justify-content: center; padding: 14px; margin-top: 18px; }
.login-divider { text-align: center; color: var(--gray-300); font-size: 0.75rem; margin: 18px 0; position: relative; }
.login-divider::before, .login-divider::after { content:''; position:absolute; top:50%; width:44%; height:1px; background:var(--border); }
.login-divider::before { left:0; } .login-divider::after { right:0; }
.demo-pill {
  display: flex; align-items: center; gap: 8px;
  background: var(--teal-xlight); border: 1px solid var(--teal-light);
  border-radius: var(--radius-sm); padding: 10px 14px;
  font-size: 0.76rem; color: var(--text-light); margin-top: 18px;
}
.demo-pill code { background: var(--teal-light); color: var(--teal-dark); padding: 1px 6px; border-radius: 4px; font-family: monospace; }

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
.sb-logo-mark { width: 34px; height: 34px; flex-shrink: 0; }
.sb-logo-mark svg { width: 100%; height: 100%; }
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
.topbar-left { display: flex; flex-direction: column; }
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
.kpi-num   { font-size: 1.8rem; font-weight: 800; color: var(--teal); line-height: 1; }
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
.pet-avatar-wrap img { width: 100%; height: 100%; object-fit: cover; }
.pet-info strong { display: block; font-size: 0.84rem; color: var(--navy); }
.pet-info small  { font-size: 0.72rem; color: var(--text-light); }
.pet-vacc-badge  {
  margin-left: auto; font-size: 0.6rem; font-weight: 700;
  padding: 3px 9px; border-radius: 100px;
  display: flex; align-items: center; gap: 4px;
}
.pet-vacc-badge.vaccinated { background: #DCFCE7; color: #16A34A; }
.pet-vacc-badge.due        { background: #FEE2E2; color: var(--red); }

/* Reminders */
.reminder-list { display: flex; flex-direction: column; gap: 8px; }
.reminder-row  { display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: 8px; background: var(--gray-50); }
.reminder-dot  { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.reminder-dot.danger { background: var(--red); }
.reminder-dot.warn   { background: #F59E0B; }
.reminder-dot.ok     { background: var(--teal); }
.reminder-info strong { display: block; font-size: 0.82rem; color: var(--navy); }
.reminder-info small  { font-size: 0.72rem; color: var(--text-light); }
.reminder-book {
  margin-left: auto; font-size: 0.72rem; font-weight: 600; color: var(--teal);
  border: 1px solid var(--teal-light); background: var(--teal-xlight);
  padding: 4px 12px; border-radius: 100px; white-space: nowrap;
  transition: background var(--transition), border-color var(--transition);
}
.reminder-book:hover { background: var(--teal-light); }

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
.status-chip.delivered { background: #DCFCE7; color: #166534; }
.status-chip.paid      { background: #DCFCE7; color: #166534; }
.status-chip.cancelled { background: #FEE2E2; color: var(--red); }

/* ── My Pets ── */
.pets-detail-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
.pet-profile-card { background: white; border: 1px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
.pp-cover { height: 140px; position: relative; display: flex; align-items: flex-end; padding: 14px 20px; }
.pp-avatar { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid white; box-shadow: var(--shadow-md); }
.pp-health-badge {
  position: absolute; top: 14px; right: 14px;
  font-size: 0.65rem; font-weight: 700; padding: 4px 12px; border-radius: 100px;
}
.pp-health-badge.vaccinated { background: rgba(220,252,231,0.9); color: #166534; }
.pp-health-badge.due        { background: rgba(254,226,226,0.9); color: var(--red); }
.pp-body { padding: 16px 20px; }
.pp-body h3  { font-size: 1.1rem; margin-bottom: 2px; }
.pp-sub  { font-size: 0.78rem; color: var(--text-light); margin-bottom: 16px; }
.pp-attrs { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px 16px; margin-bottom: 16px; }
.pp-attr  { display: flex; flex-direction: column; gap: 2px; }
.pp-attr-key { font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-light); font-weight: 600; }
.pp-attr-val { font-size: 0.82rem; color: var(--navy); font-weight: 600; }
.pp-divider  { height: 1px; background: var(--border); margin: 14px 0; }
.pp-section-label { font-size: 0.62rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--teal); font-weight: 700; margin-bottom: 10px; }
.pp-vacc-list { display: flex; flex-direction: column; gap: 7px; }
.pp-vacc-item { display: flex; align-items: center; font-size: 0.8rem; }
.pp-vacc-right { display: flex; align-items: center; gap: 8px; margin-left: auto; }
.pp-vacc-date   { font-size: 0.72rem; color: var(--text-light); }
.pp-vacc-status { font-size: 0.62rem; font-weight: 700; padding: 2px 8px; border-radius: 100px; text-transform: capitalize; }
.pp-vacc-status.up-to-date { background: #DCFCE7; color: #166534; }
.pp-vacc-status.due-soon   { background: #FEF9C3; color: #854D0E; }
.pp-vacc-status.overdue    { background: #FEE2E2; color: var(--red); }
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
.tl-marker.vaccine   { background: #3B82F6; }
.tl-marker.procedure { background: #8B5CF6; }
.tl-marker.emergency { background: var(--red); }
.tl-stem  { position: absolute; left: 5px; top: 18px; width: 2px; background: var(--border); bottom: -12px; display: none; }
.tl-body  { flex: 1; padding-bottom: 20px; }
.tl-date  { font-size: 0.7rem; color: var(--text-light); margin-bottom: 6px; }
.tl-card  { background: white; border: 1px solid var(--border); border-radius: 10px; padding: 12px 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); }
.tl-card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px; }
.tl-card-head strong { font-size: 0.86rem; }
.type-chip { font-size: 0.62rem; font-weight: 700; padding: 2px 8px; border-radius: 100px; text-transform: capitalize; }
.type-chip.wellness  { background: var(--teal-light);  color: var(--teal-dark); }
.type-chip.vaccine   { background: #DBEAFE;              color: #1D4ED8; }
.type-chip.procedure { background: #F3E8FF;              color: #6D28D9; }
.type-chip.emergency { background: #FEE2E2;              color: var(--red); }
.tl-meta  { font-size: 0.72rem; color: var(--text-light); margin-bottom: 6px; }
.tl-notes { font-size: 0.8rem; color: var(--text); line-height: 1.5; }
.tl-tags  { display: flex; flex-wrap: wrap; gap: 5px; margin-top: 8px; }
.tl-tag   { background: var(--gray-100); color: var(--text-light); font-size: 0.65rem; padding: 2px 8px; border-radius: 4px; }

.vacc-schedule { margin-bottom: 20px; }
.vs-pet-head { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
.vs-avatar   { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }
.vs-pet-head strong { font-size: 0.86rem; }
.vs-rows { display: flex; flex-direction: column; gap: 5px; }
.vs-row  { display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: var(--gray-50); border-radius: 7px; font-size: 0.78rem; }
.vs-name { flex: 1; }
.vs-date { font-size: 0.7rem; color: var(--text-light); }
.vs-chip { font-size: 0.6rem; font-weight: 700; padding: 2px 8px; border-radius: 100px; text-transform: capitalize; }
.vs-chip.up-to-date { background: #DCFCE7; color: #166534; }
.vs-chip.due-soon   { background: #FEF9C3; color: #854D0E; }
.vs-chip.overdue    { background: #FEE2E2; color: var(--red); }

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
.rx-chip { margin-left: auto; font-size: 0.62rem; font-weight: 700; padding: 3px 10px; border-radius: 100px; }
.rx-chip.active  { background: #DCFCE7; color: #166534; }
.rx-chip.expired { background: var(--gray-100); color: var(--gray-400); }
.rx-details { padding: 14px 18px; display: flex; flex-direction: column; gap: 7px; }
.rx-row { display: flex; justify-content: space-between; font-size: 0.8rem; }
.rx-key { color: var(--text-light); }
.rx-footer { display: flex; gap: 8px; padding: 12px 18px; border-top: 1px solid var(--border); }

/* ── Messages ── */
.messages-layout { display: grid; grid-template-columns: 280px 1fr; gap: 0; background: white; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; min-height: 500px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); }
.msg-list { border-right: 1px solid var(--border); overflow-y: auto; }
.msg-thread {
  display: flex; align-items: flex-start; gap: 10px;
  padding: 14px 16px; cursor: pointer; position: relative;
  border-bottom: 1px solid var(--border);
  transition: background var(--transition);
}
.msg-thread:hover { background: var(--gray-50); }
.msg-thread.active { background: var(--teal-xlight); }
.msg-thread.unread { background: var(--teal-xlight); }
.msg-avatar { width: 36px; height: 36px; border-radius: 50%; overflow: hidden; flex-shrink: 0; }
.msg-avatar img { width: 100%; height: 100%; object-fit: cover; }
.msg-preview { flex: 1; min-width: 0; }
.msg-from-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 3px; }
.msg-from-row strong { font-size: 0.8rem; color: var(--navy); }
.msg-time { font-size: 0.65rem; color: var(--text-light); white-space: nowrap; }
.msg-snippet { font-size: 0.75rem; color: var(--text-light); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.msg-unread-dot { width: 7px; height: 7px; background: var(--teal); border-radius: 50%; flex-shrink: 0; margin-top: 6px; }
.msg-body { padding: 24px; display: flex; flex-direction: column; }
.msg-full-head { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border); }
.msg-full-avatar { width: 42px; height: 42px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
.msg-full-head strong { display: block; font-size: 0.9rem; color: var(--navy); }
.msg-full-head small  { font-size: 0.72rem; color: var(--text-light); }
.msg-full-body { font-size: 0.86rem; color: var(--text); line-height: 1.7; flex: 1; }
.msg-reply-box { margin-top: 24px; border-top: 1px solid var(--border); padding-top: 16px; }
.msg-empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; color: var(--text-light); font-size: 0.85rem; }

/* ── Orders ── */
.orders-list { display: flex; flex-direction: column; gap: 16px; }
.order-card { background: white; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.04); }
.order-head { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-bottom: 1px solid var(--border); background: var(--gray-50); }
.order-id   { font-size: 0.85rem; font-weight: 700; color: var(--navy); }
.order-date { font-size: 0.75rem; color: var(--text-light); }
.order-items { padding: 14px 18px; display: flex; flex-direction: column; gap: 10px; }
.order-item  { display: flex; align-items: center; gap: 12px; }
.order-img   { width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 1px solid var(--border); flex-shrink: 0; }
.order-item-info strong { display: block; font-size: 0.84rem; color: var(--navy); }
.order-item-info small  { font-size: 0.7rem; color: var(--text-light); }
.order-price { margin-left: auto; font-weight: 700; font-size: 0.88rem; color: var(--teal); }
.order-foot  { display: flex; align-items: center; justify-content: space-between; padding: 12px 18px; border-top: 1px solid var(--border); font-size: 0.85rem; }

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
.form-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
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
  .sidebar     { display: none; }
  .records-cols { grid-template-columns: 1fr; }
  .pets-detail-grid { grid-template-columns: 1fr; }
  .messages-layout  { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
  .login-body { padding: 28px 28px 36px; }
  .dash-body  { padding: 16px; }
  .kpi-row    { grid-template-columns: repeat(2,1fr); gap: 10px; }
  .quick-actions { gap: 6px; }
  .settings-grid { grid-template-columns: 1fr; }
}
</style>

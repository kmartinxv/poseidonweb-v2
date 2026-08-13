<template>
  <div class="post-page" v-if="post">
    <div class="container" style="padding-top:136px;padding-bottom:80px">
      <div class="post-layout">

        <!-- Article -->
        <article class="article">
          <div class="article-meta-top">
            <RouterLink to="/blog" class="back-link">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
              All Articles
            </RouterLink>
            <span class="post-cat">{{ post.category }}</span>
          </div>

          <h1 style="margin:16px 0 20px">{{ post.title }}</h1>

          <div class="article-author-row">
            <img :src="post.authorAvatar" :alt="post.author" class="author-img" />
            <div>
              <strong>{{ post.author }}</strong>
              <small>{{ post.date }} · {{ post.readTime }}</small>
            </div>
          </div>

          <div class="article-hero-img">
            <img :src="post.image" :alt="post.title" />
          </div>

          <div class="article-body">
            <template v-for="(block, i) in post.body" :key="i">
              <p v-if="block.type === 'p'">{{ block.text }}</p>
              <h2 v-else-if="block.type === 'h2'">{{ block.text }}</h2>
              <blockquote v-else-if="block.type === 'quote'">{{ block.text }}</blockquote>
              <ul v-else-if="block.type === 'ul'">
                <li v-for="(item, j) in block.items" :key="j" v-html="item"></li>
              </ul>
            </template>
          </div>

          <div class="article-cta">
            <RouterLink to="/appointments" class="btn btn-primary btn-lg">
              Book a Checkup Now
            </RouterLink>
          </div>

          <div class="article-tags">
            <span class="tag" v-for="t in post.tags" :key="t">{{ t }}</span>
          </div>
        </article>

        <!-- Sidebar -->
        <aside class="article-sidebar">
          <div class="sidebar-card card">
            <h4>Book a Checkup</h4>
            <p>Ready to schedule your pet's annual visit? Our team is here for you.</p>
            <RouterLink to="/appointments" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:14px">Book Now</RouterLink>
          </div>

          <div class="sidebar-card card" style="margin-top:20px">
            <h4>Contact Us</h4>
            <p style="font-size:0.85rem;color:var(--text-light);margin-bottom:14px">Have a question? Our vets are happy to help.</p>
            <a href="tel:+254780415469" class="sidebar-contact">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.62 3.38 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.77a16 16 0 0 0 6.28 6.28l1.06-1.06a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.03z"/></svg>
              +254 780 415 469
            </a>
            <a href="mailto:info@poseidonvet.com" class="sidebar-contact" style="margin-top:8px">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              info@poseidonvet.com
            </a>
          </div>
        </aside>

      </div>
    </div>
  </div>
  <div v-else class="container" style="padding-top:160px;padding-bottom:120px;text-align:center">
    <h2>Article not found</h2>
    <p class="text-light" style="margin-top:8px">The article you're looking for doesn't exist or may have been moved.</p>
    <RouterLink to="/blog" class="btn btn-primary" style="margin-top:24px">Back to Blog</RouterLink>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute }  from 'vue-router'
import { posts }     from '../data/blog.js'

const route = useRoute()
const post  = computed(() => posts.find(p => p.slug === route.params.slug))
</script>

<style scoped>
.post-layout {
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 48px;
  align-items: start;
}
.article-meta-top { display: flex; align-items: center; gap: 16px; }
.back-link {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 0.82rem;
  color: var(--teal);
  font-weight: 600;
  transition: gap var(--transition);
}
.back-link:hover { gap: 2px; }
.post-cat { font-size: 0.7rem; font-weight: 700; color: var(--teal); text-transform: uppercase; letter-spacing: 0.1em; background: var(--teal-light); padding: 3px 10px; border-radius: 100px; }
.article-author-row { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
.author-img { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; }
.article-author-row strong { display: block; font-size: 0.9rem; color: var(--navy); }
.article-author-row small  { font-size: 0.78rem; color: var(--text-light); }
.article-hero-img {
  border-radius: var(--radius-lg);
  overflow: hidden;
  height: 400px;
  margin-bottom: 36px;
}
.article-hero-img img { width: 100%; height: 100%; object-fit: cover; }
.article-body p  { color: var(--text-light); line-height: 1.8; margin-bottom: 18px; }
.article-body h2 { color: var(--navy); font-size: 1.3rem; margin: 32px 0 12px; }
.article-body ul { padding-left: 24px; color: var(--text-light); line-height: 1.8; margin-bottom: 18px; }
.article-body li { margin-bottom: 6px; }
.article-body blockquote {
  border-left: 4px solid var(--teal);
  padding: 16px 24px;
  margin: 24px 0;
  background: var(--teal-xlight);
  border-radius: 0 var(--radius) var(--radius) 0;
  font-style: italic;
  color: var(--navy);
}
.article-cta { margin: 36px 0; }
.article-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 24px; }
.tag {
  padding: 5px 12px;
  background: var(--gray-100);
  border-radius: 100px;
  font-size: 0.75rem;
  font-weight: 500;
  color: var(--text-light);
  transition: background var(--transition), color var(--transition);
  cursor: pointer;
}
.tag:hover { background: var(--teal-light); color: var(--teal); }

.sidebar-card { padding: 22px; }
.sidebar-card h4 { margin-bottom: 8px; }
.sidebar-card p  { font-size: 0.85rem; color: var(--text-light); }
.sidebar-contact {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--navy);
  transition: color var(--transition);
}
.sidebar-contact:hover { color: var(--teal); }

@media (max-width: 900px) { .post-layout { grid-template-columns: 1fr; } }
</style>

<template>
  <div class="blog-page">
    <section class="page-hero">
      <div class="container">
        <span class="eyebrow">Pet Health Blog</span>
        <h1>Insights &amp; Advice<br>For Pet Owners</h1>
        <p>Expert tips, health guides, and clinic news from the Poseidon Vet team.</p>
      </div>
    </section>

    <section class="section">
      <div class="container">

        <!-- Featured post -->
        <div class="featured-post card" v-if="posts.length">
          <RouterLink :to="`/blog/${posts[0].slug}`" class="featured-img">
            <img :src="posts[0].image" :alt="posts[0].title" />
            <span class="featured-label">Featured</span>
          </RouterLink>
          <div class="featured-content">
            <span class="post-cat">{{ posts[0].category }}</span>
            <RouterLink :to="`/blog/${posts[0].slug}`"><h2>{{ posts[0].title }}</h2></RouterLink>
            <p>{{ posts[0].excerpt }}</p>
            <div class="post-meta">
              <img :src="posts[0].authorAvatar" :alt="posts[0].author" class="author-avatar" />
              <span>{{ posts[0].author }}</span>
              <span class="meta-sep">·</span>
              <span>{{ posts[0].date }}</span>
              <span class="meta-sep">·</span>
              <span>{{ posts[0].readTime }}</span>
            </div>
            <RouterLink :to="`/blog/${posts[0].slug}`" class="btn btn-primary" style="margin-top:20px">
              Read Article
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </RouterLink>
          </div>
        </div>

        <!-- Posts grid -->
        <div class="posts-grid" style="margin-top:48px">
          <div class="post-card card" v-for="post in posts.slice(1)" :key="post.slug">
            <RouterLink :to="`/blog/${post.slug}`" class="post-img-wrap">
              <img :src="post.image" :alt="post.title" loading="lazy" />
            </RouterLink>
            <div class="post-content">
              <span class="post-cat">{{ post.category }}</span>
              <RouterLink :to="`/blog/${post.slug}`"><h3>{{ post.title }}</h3></RouterLink>
              <p>{{ post.excerpt }}</p>
              <div class="post-meta">
                <img :src="post.authorAvatar" :alt="post.author" class="author-avatar" />
                <span>{{ post.author }}</span>
                <span class="meta-sep">·</span>
                <span>{{ post.readTime }}</span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- Newsletter -->
    <section class="newsletter-section">
      <div class="container">
        <div class="newsletter-card">
          <h2>Stay Updated</h2>
          <p>Get monthly pet care tips, clinic news, and exclusive shop discounts straight to your inbox.</p>
          <form v-if="!subscribed" class="newsletter-form" @submit.prevent="subscribed = true">
            <input type="email" v-model="newsletterEmail" class="form-control" placeholder="Your email address" style="flex:1" required />
            <button type="submit" class="btn btn-primary">Subscribe</button>
          </form>
          <p v-else class="newsletter-success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            You're subscribed! Watch your inbox for our next issue.
          </p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref }              from 'vue'
import { useScrollReveal } from '../composables/useScrollReveal.js'
import { posts }           from '../data/blog.js'
useScrollReveal()

const newsletterEmail = ref('')
const subscribed      = ref(false)
</script>

<style scoped>
.blog-page .page-hero { padding: 136px 0 72px; }
.featured-post {
  display: grid;
  grid-template-columns: 1.2fr 1fr;
  gap: 0;
  overflow: hidden;
  border-radius: var(--radius-xl);
}
.featured-img {
  display: block;
  position: relative;
  height: 420px;
  overflow: hidden;
}
.featured-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 400ms var(--ease-out); }
.featured-post:hover .featured-img img { transform: scale(1.04); }
.featured-label {
  position: absolute;
  top: 16px;
  left: 16px;
  background: var(--teal);
  color: white;
  font-size: 0.72rem;
  font-weight: 700;
  padding: 4px 12px;
  border-radius: 100px;
  letter-spacing: 0.06em;
}
.featured-content { padding: 40px; display: flex; flex-direction: column; }
.featured-content h2 { margin: 8px 0 14px; font-size: 1.5rem; }
.featured-content p  { color: var(--text-light); font-size: 0.95rem; line-height: 1.7; flex: 1; }
.post-cat { font-size: 0.7rem; font-weight: 700; color: var(--teal); text-transform: uppercase; letter-spacing: 0.1em; }
.post-meta { display: flex; align-items: center; gap: 8px; font-size: 0.78rem; color: var(--text-light); margin-top: 14px; flex-wrap: wrap; }
.author-avatar { width: 28px; height: 28px; border-radius: 50%; object-fit: cover; }
.meta-sep { color: var(--gray-300); }

.posts-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 28px; }
.post-card { overflow: hidden; }
.post-img-wrap { display: block; height: 220px; overflow: hidden; }
.post-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 350ms var(--ease-out); }
.post-card:hover .post-img-wrap img { transform: scale(1.05); }
.post-content { padding: 22px; }
.post-content h3 { font-size: 1.05rem; margin: 6px 0 10px; transition: color var(--transition); }
.post-content h3:hover { color: var(--teal); }
.post-content p { font-size: 0.85rem; color: var(--text-light); line-height: 1.6; }

.newsletter-section { background: var(--navy); padding: 72px 0; }
.newsletter-card { text-align: center; }
.newsletter-card h2 { color: white; margin-bottom: 10px; }
.newsletter-card p  { color: rgba(255,255,255,0.7); max-width: 480px; margin: 0 auto 28px; }
.newsletter-form { display: flex; gap: 12px; max-width: 480px; margin: 0 auto; }
.newsletter-form .form-control { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2); color: white; }
.newsletter-form .form-control::placeholder { color: rgba(255,255,255,0.4); }
.newsletter-success {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  max-width: 480px;
  margin: 0 auto;
  padding: 13px 20px;
  background: rgba(11,191,178,0.12);
  border: 1px solid rgba(11,191,178,0.3);
  border-radius: var(--radius-sm);
  color: white;
  font-size: 0.9rem;
  font-weight: 500;
}

@media (max-width: 900px) {
  .featured-post { grid-template-columns: 1fr; }
  .featured-img  { height: 280px; }
  .posts-grid { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
  .blog-page .page-hero { padding-top: 100px; }
  .newsletter-form { flex-direction: column; }
}
</style>

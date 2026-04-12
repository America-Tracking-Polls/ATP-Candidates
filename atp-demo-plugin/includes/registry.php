<?php
/**
 * ATP Demo Shortcode Registry
 * Each group has shortcodes with a tag, label, description, and default HTML.
 * Content is stored in WP options (atp_sc_{tag}) — editable from the admin page.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function atp_demo_get_registry() {
    return [

/* ══════════════════════════════════════════════════════════════
   GLOBAL
══════════════════════════════════════════════════════════════ */
[
'group' => 'Global',
'shortcodes' => [

[
'tag'   => 'atp_logo',
'label' => 'ATP Logo',
'desc'  => 'Logo image. PHP-powered. Attributes: variant="standard|blue-white|red-white" width="160px" class="" alt=""',
'default' => <<<'HTML'
<!-- [atp_logo] is PHP-powered. Use attributes to control it:
     [atp_logo variant="standard"]
     [atp_logo variant="red-white" width="120px"]
     [atp_logo variant="blue-white" width="200px"]
-->
<img src="{ATP_PLUGIN_URL}assets/images/ATP-Logo-Standard.png" alt="America Tracking Polls" style="width:160px;height:auto">
HTML,
],

]], // end Global


/* ══════════════════════════════════════════════════════════════
   DEMO HUB
══════════════════════════════════════════════════════════════ */
[
'group' => 'Demo Hub',
'shortcodes' => [

[
'tag'   => 'atp_demo_hub',
'label' => 'Demo Hub — Full Page',
'desc'  => 'The ATP demo hub landing page with all three demo cards. Self-contained (includes its own CSS).',
'default' => <<<'HTML'
<link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300&family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
.atp-hub-wrap{font-family:'Inter',sans-serif;background-color:#F9F9F7;color:#111111;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2rem}
.atp-hub-wrap *{margin:0;padding:0;box-sizing:border-box}
.atp-hub-container{max-width:1000px;width:100%;display:flex;flex-direction:column;align-items:center}
.atp-hub-logo{margin-bottom:3rem;transform:scale(1.2)}
.atp-hub-logo svg{width:180px;height:auto;filter:drop-shadow(0 4px 6px rgba(0,0,0,0.1))}
.atp-hub-header{text-align:center;margin-bottom:4rem;max-width:700px}
.atp-hub-header h1{font-family:'Merriweather',serif;font-size:2.5rem;font-weight:700;margin-bottom:1.5rem;letter-spacing:-0.01em;line-height:1.2;color:#0B1C33}
.atp-hub-header .highlight-red{color:#E60000;font-style:italic}
.atp-hub-header p{font-size:1.1rem;color:#555555;line-height:1.6}
.atp-hub-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(350px,1fr));gap:2rem;width:100%}
.atp-hub-card{background:#FFFFFF;border:1px solid #e0e0e0;border-top:4px solid #0B1C33;border-radius:4px;padding:3rem;text-decoration:none;color:inherit;transition:all 0.3s ease;position:relative;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 4px 6px rgba(0,0,0,0.02)}
.atp-hub-card:hover{transform:translateY(-5px);box-shadow:0 10px 20px rgba(0,0,0,0.05)}
.atp-hub-card .card-type{font-size:0.75rem;text-transform:uppercase;letter-spacing:0.1em;color:#E60000;font-weight:700;margin-bottom:1rem}
.atp-hub-card h2{font-family:'Merriweather',serif;font-size:1.75rem;margin-bottom:1rem;color:#0B1C33}
.atp-hub-card p{color:#555555;font-size:0.95rem;line-height:1.6;margin-bottom:2rem;flex-grow:1}
.atp-hub-card .feature-list{list-style:none;margin-bottom:2rem}
.atp-hub-card .feature-list li{display:flex;align-items:center;gap:0.5rem;font-size:0.9rem;color:#555555;margin-bottom:0.5rem}
.atp-hub-card .feature-list li svg{width:16px;height:16px;stroke:#0B1C33}
.atp-hub-card .btn-launch{display:inline-flex;align-items:center;justify-content:center;gap:0.5rem;background:transparent;border:2px solid #0B1C33;color:#0B1C33;padding:0.75rem 1.5rem;border-radius:2px;font-weight:700;font-size:0.9rem;transition:all 0.2s;text-transform:uppercase;letter-spacing:0.05em}
.atp-hub-card:hover .btn-launch{background:#0B1C33;color:white}
.atp-hub-card .aeo-badge{position:absolute;top:1rem;right:1rem;background:#F9F9F7;color:#0B1C33;font-size:0.7rem;padding:0.25rem 0.5rem;border-radius:2px;border:1px solid #ddd;font-weight:600;letter-spacing:0.05em}
.atp-hub-card.brand-card{background-color:#0B1C33;border-color:rgba(230,0,0,0.3)}
.atp-hub-card.brand-card h2{color:#FFFFFF}
.atp-hub-card.brand-card p,.atp-hub-card.brand-card .feature-list li{color:rgba(255,255,255,0.9)}
.atp-hub-card.brand-card .feature-list li svg{stroke:#E60000}
.atp-hub-card.brand-card .card-type{color:#FFFFFF}
.atp-hub-card.brand-card .btn-launch{background:rgba(255,255,255,0.1);border-color:transparent;color:#FFFFFF}
.atp-hub-card.brand-card:hover .btn-launch{background:#E60000;color:white}
.atp-hub-footer{margin-top:5rem;text-align:center;color:#555555;font-size:0.8rem;opacity:0.8}
</style>

<div class="atp-hub-wrap">
<div class="atp-hub-container">

  <div class="atp-hub-logo">
    <svg viewBox="0 0 200 160" xmlns="http://www.w3.org/2000/svg">
      <path d="M30 70 L34 82 L22 74 L38 74 L26 82 Z" fill="white" transform="translate(0,-10)"/>
      <path d="M75 55 L79 67 L67 59 L83 59 L71 67 Z" fill="white" transform="translate(0,-10)"/>
      <path d="M120 40 L124 52 L112 44 L128 44 L116 52 Z" fill="white" transform="translate(0,-10)"/>
      <path d="M165 25 L169 37 L157 29 L173 29 L161 37 Z" fill="white" transform="translate(0,-10)"/>
      <rect x="20" y="75" width="30" height="50" fill="#E60000"/>
      <rect x="65" y="60" width="30" height="65" fill="#E60000"/>
      <rect x="110" y="45" width="30" height="80" fill="#E60000"/>
      <rect x="155" y="30" width="30" height="95" fill="#E60000"/>
      <text x="100" y="155" font-family="'Space Grotesk',sans-serif" font-weight="900" font-size="70" fill="white" text-anchor="middle" letter-spacing="-2">ATP</text>
    </svg>
  </div>

  <div class="atp-hub-header">
    <h1>7 Proven Strategies to <span class="highlight-red">Win Elections</span></h1>
    <p>Leverage Answer Engine Optimization (AEO), real-time polling data, and AI-driven campaign launchpads to dominate the digital narrative.</p>
  </div>

  <div class="atp-hub-grid">

    <a href="/campaign-site/" class="atp-hub-card">
      <div class="aeo-badge">AEO OPTIMIZED</div>
      <div class="card-type">Campaign Solution</div>
      <h2>Voter Insights Dashboard</h2>
      <p>A data-first civic engagement platform designed for modern campaigns. Features real-time issue tracking, town hall integration, and comprehensive voter resource centers.</p>
      <ul class="feature-list">
        <li><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Multi-channel Data Stream</li>
        <li><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Civic Tool Integration</li>
        <li><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Patriotic Design System</li>
      </ul>
      <div class="btn-launch">Launch Campaign Demo</div>
    </a>

    <a href="/personal-site/" class="atp-hub-card">
      <div class="aeo-badge">BRAND AUTHORITY</div>
      <div class="card-type">Candidate Profile</div>
      <h2>Digital Identity Platform</h2>
      <p>Establish immediate authority with a professional digital footprint. Optimized for Answer Engines to ensure your tone of voice and core issues dominate search results.</p>
      <ul class="feature-list">
        <li><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Bio &amp; Narrative Optimization</li>
        <li><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> High-Impact Visuals</li>
        <li><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Direct Engagement Tools</li>
      </ul>
      <div class="btn-launch">Launch Candidate Demo</div>
    </a>

    <a href="/brand-guide/" class="atp-hub-card brand-card">
      <div class="card-type">Design System</div>
      <h2>ATP Brand Guide</h2>
      <p>Explore the visual language, color palette, typography, and interactive component library that powers the ATP ecosystem.</p>
      <ul class="feature-list">
        <li><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Interactive Components</li>
        <li><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Logo &amp; Asset Library</li>
      </ul>
      <div class="btn-launch">View Brand Guide</div>
    </a>

  </div>

  <div class="atp-hub-footer">
    &copy; 2026 America Tracking Polls. All Rights Reserved.<br>
    Powered by AI Campaign Launchpad Technology.
  </div>

</div>
</div>
HTML,
],

]], // end Demo Hub



/* ══════════════════════════════════════════════════════════════
   HOMEPAGE — STYLES & SCRIPTS
══════════════════════════════════════════════════════════════ */
[
'group' => 'Homepage — Styles & Scripts',
'shortcodes' => [

[
'tag'   => 'atp_hp_styles',
'label' => 'Homepage — Styles',
'desc'  => 'Google Fonts + GSAP CDN + all homepage CSS. Place this at the very top of any page using homepage sections.',
'default' => <<<'HTML'
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/TextPlugin.min.js"></script>
<style>
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
:root{
  --blue:#3C3B6E;--blue-mid:#2E2D5A;--blue-deep:#1A1940;--blue-dark:#111030;
  --red:#B22234;--red-bright:#D42E43;
  --white:#FFFFFF;--off-white:#F5F6FA;--gray:#8A8FA0;--dark:#1A1A2E;
  --display:'Bebas Neue',sans-serif;--body:'Libre Baskerville',Georgia,serif;
  --mono:'Space Mono',monospace;
}
html{scroll-behavior:smooth}
body{font-family:var(--body);color:var(--white);background:var(--blue-dark);overflow-x:hidden;-webkit-font-smoothing:antialiased}
.wrap{max-width:1240px;margin:0 auto;padding:0 48px}
@media(max-width:768px){.wrap{padding:0 20px}}

/* ═══ TOP POLL BAR ═══ */
.poll-bar{background:var(--red);height:36px;display:flex;align-items:center;justify-content:center;position:relative;z-index:600;overflow:hidden}
.poll-bar::before{content:'';position:absolute;inset:0;background:linear-gradient(90deg,var(--red),#9A1D2E,var(--red));opacity:0.6}
.pb-track{position:relative;height:100%;display:flex;align-items:center;overflow:hidden;width:100%;max-width:700px}
.pb-slide{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;gap:12px;opacity:0;transform:translateX(100%)}
.pb-slide.active{opacity:1;transform:translateX(0)}
.pb-badge{background:rgba(0,0,0,0.25);padding:3px 10px;border-radius:3px;font-family:var(--display);font-size:11px;letter-spacing:2px;color:var(--white)}
.pb-text{font-size:11px;color:rgba(255,255,255,0.95);letter-spacing:0.3px}
.pb-text strong{color:var(--white)}

/* ═══ HEADER ═══ */
.hdr{position:fixed;top:36px;left:0;right:0;z-index:500;height:60px;display:flex;align-items:center;transition:all 0.4s;background:rgba(17,16,48,0.95);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px)}
.hdr.scrolled{top:0;background:rgba(17,16,48,0.97);border-bottom:1px solid rgba(255,255,255,0.04)}
.hdr .wrap{display:flex;align-items:center;justify-content:space-between;width:100%}
.hdr-left{display:flex;align-items:center;gap:14px}
.hdr-mark{width:34px;height:30px;display:flex;align-items:center;justify-content:center}
.hdr-brand{font-family:var(--display);font-size:20px;letter-spacing:3px;line-height:1}
.hdr-brand .w{color:var(--white)}.hdr-brand .r{color:var(--red)}
.hdr-nav{display:flex;gap:24px}
.hdr-nav a{color:rgba(255,255,255,0.5);text-decoration:none;font-size:11px;letter-spacing:0.4px;transition:color 0.3s;text-transform:uppercase}
.hdr-nav a:hover{color:var(--white)}
.hdr-btn{background:var(--red);color:var(--white);border:none;padding:9px 20px;font-family:var(--body);font-size:10.5px;font-weight:700;border-radius:3px;cursor:pointer;transition:all 0.3s;white-space:nowrap}
.hdr-btn:hover{background:var(--red-bright);box-shadow:0 4px 20px rgba(178,34,52,0.5)}
@media(max-width:960px){.hdr-nav{display:none}}

/* ═══ HERO ═══ */
.hero{position:relative;overflow:hidden;background:#0e1235;display:flex;align-items:center;padding-top:140px;padding-bottom:100px;min-height:90vh}
#hero-canvas{position:absolute;inset:0;width:100%;height:100%;z-index:1}
.hero-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.04) 1px,transparent 1px);background-size:72px 72px;z-index:2;pointer-events:none}
.hero-chart-blur{position:absolute;bottom:0;left:0;right:0;height:26%;z-index:3;pointer-events:none;backdrop-filter:blur(2px);-webkit-backdrop-filter:blur(2px);mask-image:linear-gradient(to bottom,transparent 0%,black 30%);-webkit-mask-image:linear-gradient(to bottom,transparent 0%,black 30%)}
.hero-race-bar{position:absolute;bottom:0;left:0;right:0;height:3px;display:flex;z-index:4;pointer-events:none}
.hero-race-red{background:#d42b2b;transition:width 1.4s cubic-bezier(0.4,0,0.2,1)}
.hero-race-blue{background:#3a5fd9;flex:1}
.hero-inner{position:relative;z-index:10;width:100%}
.hero-inner .wrap{display:grid;grid-template-columns:1.2fr 0.8fr;gap:60px;align-items:center}
.hero-text{position:relative}
.h-tag{font-family:var(--mono);color:rgba(255,255,255,0.7);font-size:12px;letter-spacing:2px;margin-bottom:16px;display:inline-block;background:rgba(255,255,255,0.08);padding:4px 12px;border-radius:20px;border:1px solid rgba(255,255,255,0.12)}
.h-line{font-family:var(--display);letter-spacing:3px;line-height:1.1;opacity:0;transform:translateY(30px)}
.h-big{font-size:clamp(36px,4.5vw,56px);color:var(--white);text-shadow:0 2px 30px rgba(0,0,0,0.5)}
.h-red{color:var(--red)}
.hero-sub{margin-top:24px;font-size:15px;line-height:1.8;color:rgba(255,255,255,0.7);max-width:540px;opacity:0;transform:translateY(20px)}
.hero-sub strong{color:var(--white);font-weight:700}
.hero-btns{display:flex;gap:14px;margin-top:40px;flex-wrap:wrap;opacity:0;transform:translateY(20px)}
.btn-p{display:inline-flex;align-items:center;gap:8px;background:var(--red);color:var(--white);padding:14px 32px;font-family:var(--body);font-size:12px;font-weight:700;border-radius:4px;text-decoration:none;transition:all 0.3s;letter-spacing:1px;border:none;cursor:pointer}
.btn-p:hover{background:var(--red-bright);transform:translateY(-2px);box-shadow:0 8px 28px rgba(178,34,52,0.4)}
.btn-video{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:var(--white);padding:14px 28px;font-family:var(--body);font-size:12px;font-weight:700;border-radius:4px;text-decoration:none;transition:all 0.3s;letter-spacing:1px;cursor:pointer;backdrop-filter:blur(10px)}
.btn-video:hover{background:rgba(255,255,255,0.2);transform:translateY(-2px)}
.btn-video svg{width:18px;height:18px;fill:var(--white)}
/* Hero Form */
.hf{background:rgba(17,16,48,0.82);border:1px solid rgba(255,255,255,0.15);border-top:4px solid var(--red);border-radius:14px;padding:32px;backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);box-shadow:0 20px 60px rgba(0,0,0,0.6);opacity:1;transform:none}
.hf-t{font-family:var(--display);font-size:24px;letter-spacing:2px;color:var(--white);margin-bottom:8px}
.hf-s{font-size:12px;color:rgba(255,255,255,0.55);margin-bottom:24px;line-height:1.5}
.hf-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px}
.hf-i{width:100%;background:rgba(0,0,0,0.2);border:1px solid rgba(255,255,255,0.12);border-radius:6px;padding:12px 16px;color:var(--white);font-family:var(--body);font-size:13px;transition:border 0.3s}
.hf-i::placeholder{color:rgba(255,255,255,0.4)}
.hf-i.full{margin-bottom:12px}
.hf-i:focus{border-color:var(--red);outline:none}
.hf-ta{width:100%;background:rgba(0,0,0,0.2);border:1px solid rgba(255,255,255,0.12);border-radius:6px;padding:12px 16px;color:var(--white);font-family:var(--body);font-size:13px;margin-bottom:12px;transition:border 0.3s;resize:vertical;min-height:80px}
.hf-ta::placeholder{color:rgba(255,255,255,0.4)}
.hf-ta:focus{border-color:var(--red);outline:none}
.hf-check{display:flex;align-items:flex-start;gap:8px;margin-bottom:16px;font-size:10px;color:rgba(255,255,255,0.5);line-height:1.4}
.hf-check input{margin-top:2px;accent-color:var(--red)}
.hf-b{width:100%;background:var(--red);color:var(--white);border:none;padding:14px;font-family:var(--display);font-size:16px;letter-spacing:1px;border-radius:6px;cursor:pointer;transition:all 0.3s;margin-top:8px}
.hf-b:hover{background:var(--red-bright);box-shadow:0 6px 20px rgba(178,34,52,0.4)}
@media(max-width:960px){.hero-inner .wrap{grid-template-columns:1fr;text-align:center}.hero-sub{margin:24px auto 0}.hero-btns{justify-content:center}.hf{transform:translateX(0);transform:translateY(30px)}}

/* ═══ ABOUT ═══ */
.about-atp{background:var(--white);color:var(--dark);padding:120px 0 100px;position:relative;overflow:hidden}
.about-grid{display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center}
.about-text h2{font-family:var(--display);font-size:clamp(32px,3.5vw,42px);letter-spacing:2px;color:var(--blue-dark);margin-bottom:20px;line-height:1}
.about-text h2 .red{color:var(--red)}
.about-text p{font-size:15px;color:#5A5F6E;line-height:1.8;margin-bottom:20px}
.about-text p strong{color:var(--blue-dark)}
.about-video{display:flex;align-items:center;justify-content:center}
.video-placeholder{width:100%;aspect-ratio:4/3;background:var(--blue-dark);border-radius:14px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;cursor:pointer;transition:all 0.3s;box-shadow:0 20px 60px rgba(17,16,48,0.3);position:relative;overflow:hidden}
.video-placeholder::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(178,34,52,0.15),transparent 60%);pointer-events:none}
.video-placeholder:hover{transform:scale(1.02)}
.video-play-btn{position:relative;z-index:2;transition:transform 0.3s}
.video-placeholder:hover .video-play-btn{transform:scale(1.1)}
.video-caption{font-family:var(--display);font-size:18px;letter-spacing:2px;color:var(--white);position:relative;z-index:2}
@media(max-width:960px){.about-grid{grid-template-columns:1fr;gap:30px}}

/* ═══ TRUST ═══ */
.trust-sec{background:var(--white);color:var(--dark);padding:80px 0;border-top:1px solid rgba(0,0,0,0.04)}
.trust-hdr{text-align:center;margin-bottom:50px}
.trust-hdr h2{font-family:var(--display);font-size:clamp(36px,4vw,48px);letter-spacing:2px;color:var(--blue-dark);margin-bottom:12px}
.trust-hdr p{font-size:15px;color:#5A5F6E;max-width:500px;margin:0 auto;line-height:1.6}
.trust-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:30px}
.trust-card{background:var(--off-white);border:1px solid rgba(0,0,0,0.04);border-radius:12px;padding:30px 24px;text-align:center}
.trust-icon{margin-bottom:16px}
.trust-card h4{font-family:var(--display);font-size:18px;letter-spacing:1px;color:var(--blue-dark);margin-bottom:12px}
.trust-card p{font-size:12px;color:#5A5F6E;line-height:1.6}
@media(max-width:768px){.trust-grid{grid-template-columns:1fr}}

/* ═══ SURVEY ═══ */
.survey-sim{background:var(--off-white);padding:100px 0;color:var(--dark);overflow:hidden}
.survey-hdr{text-align:center;margin-bottom:60px}
.survey-hdr h2{font-family:var(--display);font-size:48px;letter-spacing:2px;color:var(--blue-dark)}
.survey-hdr p{font-size:16px;color:#5A5F6E;max-width:600px;margin:10px auto 0;line-height:1.6}
.survey-container{display:grid;grid-template-columns:auto 1fr;gap:60px;align-items:center;max-width:1100px;margin:0 auto}
.survey-text h3{font-family:var(--display);font-size:28px;letter-spacing:2px;color:var(--blue-dark);margin-bottom:20px}
.survey-text h4{font-family:var(--display);font-size:18px;letter-spacing:1px;color:var(--blue-dark);margin:28px 0 12px}
.survey-text p{font-size:15px;color:#5A5F6E;line-height:1.8;margin-bottom:16px}
.survey-text ul{list-style:none;padding:0;margin-bottom:16px}
.survey-text li{font-size:14px;color:#5A5F6E;line-height:1.7;padding-left:20px;position:relative;margin-bottom:6px}
.survey-text li::before{content:'';position:absolute;left:0;top:8px;width:8px;height:8px;background:var(--red);border-radius:2px}
.iphone{position:relative;width:320px;height:680px;background:#000;border-radius:44px;border:3px solid #333;box-shadow:0 30px 80px rgba(0,0,0,0.25),inset 0 0 0 2px #1a1a1a;margin:0 auto;overflow:hidden;display:flex;flex-direction:column}
.iphone-screen{flex:1;display:flex;flex-direction:column;background:#fff;margin:8px;border-radius:36px;overflow:hidden;position:relative;transition:background-color 0.3s}
.iphone-screen.dark-mode{background:var(--blue-dark)}
.iphone-dynamic-island{position:absolute;top:8px;left:50%;transform:translateX(-50%);width:90px;height:24px;background:#000;border-radius:20px;z-index:20}
.iphone-statusbar{display:flex;justify-content:space-between;align-items:center;padding:12px 24px 0;font-family:-apple-system,sans-serif;font-size:12px;font-weight:600;color:#000;z-index:10;transition:color 0.3s}
.iphone-statusbar.inverted{color:#fff}
.msg-header{display:flex;align-items:center;padding:8px 12px 12px;border-bottom:1px solid #E5E5EA}
.msg-avatar{width:32px;height:32px;background:linear-gradient(135deg,var(--blue),var(--blue-dark));border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-family:-apple-system,sans-serif;font-size:13px;font-weight:600;margin:0 8px}
.msg-contact-name{font-family:-apple-system,sans-serif;font-size:14px;font-weight:600;color:#000}
.msg-contact-label{font-family:-apple-system,sans-serif;font-size:10px;color:#8E8E93}
.msg-thread{flex:1;padding:12px;display:flex;flex-direction:column;gap:4px;background:#fff;overflow:hidden}
.msg-time{text-align:center;font-family:-apple-system,sans-serif;font-size:10px;color:#8E8E93;margin:8px 0}
.msg-bubble{max-width:80%;padding:10px 14px;font-family:-apple-system,sans-serif;font-size:14px;line-height:1.35}
.msg-incoming{background:#E9E9EB;color:#000;align-self:flex-start;border-radius:18px;border-bottom-left-radius:4px}
.msg-outgoing{background:var(--blue);color:#fff;align-self:flex-end;border-radius:18px;border-bottom-right-radius:4px}
.msg-delivered{font-family:-apple-system,sans-serif;font-size:10px;color:#8E8E93;text-align:right;margin-top:2px}
.msg-inputbar{display:flex;align-items:center;gap:8px;padding:8px 12px;border-top:1px solid #E5E5EA;background:#F8F8F8}
.msg-inputbar-plus{width:28px;height:28px;background:var(--blue);border-radius:50%;display:flex;align-items:center;justify-content:center}
.msg-inputbar-plus svg{width:16px;height:16px;fill:none;stroke:#fff;stroke-width:2.5;stroke-linecap:round}
.msg-inputbar-field{flex:1;background:#fff;border:1px solid #E5E5EA;border-radius:18px;padding:6px 12px;font-family:-apple-system,sans-serif;font-size:14px;color:#8E8E93}
.sms-tap-indicator{position:absolute;right:40px;bottom:30%;color:rgba(0,0,0,0.5);opacity:0;transform:scale(1.5);z-index:5}
.tf-question{opacity:0;visibility:hidden;transition:all 0.4s ease}
.tf-question.active{opacity:1;visibility:visible}
.tf-question.exit-up{opacity:0;visibility:hidden;transform:translateY(-30px)}
.tf-num{color:var(--red);font-weight:700;font-size:12px;margin-bottom:4px;font-family:var(--mono)}
.tf-text{font-size:16px;font-weight:400;color:#222;margin-bottom:14px;line-height:1.3}
.tf-input-wrap{position:relative;border-bottom:2px solid var(--red);padding-bottom:4px;margin-bottom:10px;display:flex;align-items:center}
.tf-input{border:none;outline:none;font-size:16px;color:var(--red);width:100%;background:transparent;font-family:inherit}
.tf-cursor{width:2px;height:20px;background:var(--red);animation:blink 1s infinite;display:none}
.tf-input-wrap.typing .tf-cursor{display:block}
.tf-ok{display:inline-flex;align-items:center;gap:6px;background:var(--red);color:white;padding:5px 12px;border-radius:4px;font-weight:700;font-size:12px;opacity:0;transition:opacity 0.3s}
.tf-ok.show{opacity:1}
.tf-options{display:flex;flex-direction:column;gap:8px}
.tf-opt{background:rgba(0,0,0,0.03);border:1px solid rgba(0,0,0,0.1);padding:8px 12px;border-radius:6px;font-size:13px;display:flex;align-items:center;gap:10px;transition:all 0.3s;font-family:-apple-system,sans-serif}
.tf-opt.selected{background:rgba(178,34,52,0.1);border-color:var(--red)}
.tf-key{background:#FFF;border:1px solid #DDD;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:700;color:#666;font-family:var(--mono)}
.tf-opt.selected .tf-key{border-color:var(--red);color:var(--red)}
@keyframes blink{0%,100%{opacity:1}50%{opacity:0}}
@media(max-width:960px){.survey-container{grid-template-columns:1fr}}

/* ═══ JOURNEY ═══ */
.journey{background:var(--white);color:var(--dark);padding:100px 0;position:relative;overflow:hidden}
.journey .wrap{position:relative;z-index:2}
.journey-hdr{text-align:center;margin-bottom:70px}
.journey-hdr h2{font-family:var(--display);font-size:clamp(40px,5vw,56px);letter-spacing:2px;color:var(--blue-dark);margin-bottom:16px}
.journey-hdr p{font-size:15px;color:#5A5F6E;max-width:600px;margin:0 auto;line-height:1.6}
.j-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;position:relative}
.j-grid::before{content:'';position:absolute;top:40px;left:50px;right:50px;height:2px;background:rgba(0,0,0,0.05);z-index:0}
.j-card{background:var(--off-white);border:1px solid rgba(0,0,0,0.05);border-radius:12px;padding:30px 20px;text-align:center;position:relative;z-index:1;box-shadow:0 10px 30px rgba(0,0,0,0.02);transition:all 0.3s}
.j-card:hover{transform:translateY(-8px);box-shadow:0 15px 40px rgba(0,0,0,0.06);border-color:rgba(178,34,52,0.1)}
.j-num{width:40px;height:40px;background:var(--white);border:2px solid var(--red);color:var(--red);font-family:var(--display);font-size:20px;display:flex;align-items:center;justify-content:center;border-radius:50%;margin:0 auto 20px;box-shadow:0 4px 10px rgba(178,34,52,0.15)}
.j-card h4{font-family:var(--display);font-size:20px;letter-spacing:1px;color:var(--blue-dark);margin-bottom:12px}
.j-card p{font-size:12px;line-height:1.6;color:#5A5F6E}
@media(max-width:1024px){.j-grid{grid-template-columns:repeat(3,1fr);gap:20px}.j-grid::before{display:none}}
@media(max-width:768px){.j-grid{grid-template-columns:1fr;gap:20px}}

/* ═══ PIPELINE ═══ */
.pipe-sec{background:var(--blue-deep);padding:120px 0;position:relative;overflow:hidden;border-top:1px solid rgba(255,255,255,0.05);border-bottom:1px solid rgba(255,255,255,0.05)}
.pipe-wrap{display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center;position:relative;z-index:2}
.pipe-text h2{font-family:var(--display);font-size:48px;letter-spacing:3px;margin-bottom:28px;line-height:1}
.pipe-text p{font-size:15px;color:rgba(255,255,255,0.65);line-height:1.9;margin-bottom:36px}
.pipeline-diagram{position:relative;width:100%;display:flex;flex-direction:column;align-items:center;gap:20px}
.p-node{position:relative;z-index:2;background:var(--blue-dark);border:2px solid rgba(255,255,255,0.1);border-radius:8px;padding:16px 24px;text-align:center;box-shadow:0 10px 30px rgba(0,0,0,0.3);width:280px;transition:border-color 0.3s}
.p-node-title{font-family:var(--display);font-size:22px;letter-spacing:1px;color:var(--white);margin-bottom:4px}
.p-node-desc{font-family:var(--mono);font-size:10px;color:var(--gray)}
.pipe-connector{width:2px;height:50px;background:rgba(255,255,255,0.1);position:relative;z-index:1}
.pipe-connector-active{position:absolute;top:0;left:0;width:100%;height:0;background:var(--red)}
.pipe-fan{position:relative;width:100%;height:40px;z-index:1}
.pipe-fan svg{width:100%;height:100%}
.p-branches{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;width:100%;z-index:2}
.p-branch{background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.1);border-radius:8px;padding:16px 8px 14px;text-align:center;transition:all 0.3s;opacity:0.5}
.p-branch.active{background:var(--red);border-color:var(--red-bright);opacity:1;transform:translateY(-5px)}
.p-branch-icon{margin-bottom:8px;display:flex;justify-content:center}
.p-branch-icon svg{width:24px;height:24px;fill:rgba(255,255,255,0.7)}
.p-branch.active .p-branch-icon svg{fill:#fff}
.p-branch-title{font-family:var(--display);font-size:16px;letter-spacing:1px;margin-bottom:4px}
.p-branch-desc{font-family:var(--mono);font-size:8px;color:rgba(255,255,255,0.4);line-height:1.3}
.p-branch.active .p-branch-desc{color:rgba(255,255,255,0.8)}
@media(max-width:960px){.pipe-wrap{grid-template-columns:1fr;gap:40px;text-align:center}.pipe-text h2{text-align:center}}

/* ═══ AEO ═══ */
.aeo-sec{background:var(--off-white);color:var(--dark);padding:120px 0;position:relative;overflow:hidden}
.aeo-wrap{display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center}
.aeo-text h2{font-family:var(--display);font-size:54px;letter-spacing:2px;line-height:1;margin-bottom:20px}
.aeo-text h2 .red{color:var(--red)}
.aeo-text p{font-size:16px;color:#5A5F6E;line-height:1.8;margin-bottom:24px}
.chatgpt-mockup{background:#212121;border-radius:16px;box-shadow:0 30px 80px rgba(0,0,0,0.25);overflow:hidden;display:flex;flex-direction:column;height:560px;width:100%;max-width:480px;margin:0 auto}
.cg-sidebar{display:flex;align-items:center;gap:10px;padding:14px 18px;border-bottom:1px solid #333}
.cg-sidebar-label{font-family:-apple-system,sans-serif;font-size:14px;font-weight:600;color:#fff}
.cg-model-pill{background:#333;padding:3px 10px;border-radius:12px;font-family:-apple-system,sans-serif;font-size:11px;color:#aaa;margin-left:auto}
.cg-body{flex:1;padding:20px;display:flex;flex-direction:column;gap:20px;overflow:hidden}
.cg-msg{display:flex;gap:12px;align-items:flex-start}
.cg-msg-user{justify-content:flex-end}
.cg-avatar{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.cg-avatar-ai{background:var(--blue)}
.cg-avatar-ai svg{width:16px;height:16px;fill:#fff}
.cg-avatar-user{background:var(--red)}
.cg-avatar-user svg{width:14px;height:14px;fill:#fff}
.cg-bubble{font-family:-apple-system,sans-serif;font-size:14px;line-height:1.55;max-width:85%}
.cg-bubble-user{background:#2F2F2F;color:#ECECEC;padding:10px 16px;border-radius:18px}
.cg-bubble-ai{color:#D1D5DB}
.cg-bubble-ai strong{color:#fff}
.cg-sources{display:flex;flex-wrap:wrap;gap:6px;margin-top:12px}
.cg-source{display:inline-flex;align-items:center;gap:5px;background:#2A2A2A;border:1px solid #444;border-radius:6px;padding:4px 10px;font-size:11px;color:#fff;font-weight:500;text-decoration:none}
.cg-source img{width:14px;height:14px;border-radius:2px}
.cg-typing-dots{display:flex;gap:4px;align-items:center;padding:8px 0}
.cg-typing-dots span{width:6px;height:6px;background:#888;border-radius:50%;animation:cgbounce 1.4s infinite ease-in-out}
.cg-typing-dots span:nth-child(1){animation-delay:-0.32s}
.cg-typing-dots span:nth-child(2){animation-delay:-0.16s}
@keyframes cgbounce{0%,80%,100%{transform:scale(0)}40%{transform:scale(1)}}
.cg-inputbar{display:flex;align-items:center;gap:10px;padding:14px 18px;border-top:1px solid #333;background:#2A2A2A}
.cg-inputbar-field{flex:1;background:#333;border:1px solid #444;border-radius:20px;padding:10px 16px;font-family:-apple-system,sans-serif;font-size:13px;color:#888}
.cg-inputbar-send{width:32px;height:32px;background:var(--blue);border-radius:50%;display:flex;align-items:center;justify-content:center;opacity:0.6}
.cg-inputbar-send svg{width:16px;height:16px;fill:#fff}
.aeo-badge-wrap{text-align:center;margin-top:16px}
.aeo-badge{display:inline-flex;align-items:center;gap:8px;background:var(--dark);color:var(--white);font-family:var(--mono);font-size:11px;padding:8px 16px;border-radius:20px;box-shadow:0 10px 20px rgba(0,0,0,0.15);opacity:0}
.aeo-badge.active{background:var(--blue)}
@media(max-width:960px){.aeo-wrap{grid-template-columns:1fr;text-align:center}.chatgpt-mockup{max-width:100%}}

/* ═══ INTAKE ═══ */
.intake-sec{background:var(--blue-deep);padding:100px 0;border-top:1px solid rgba(255,255,255,0.05)}
.intake-inner{max-width:700px;margin:0 auto;text-align:center}
.intake-inner h2{font-family:var(--display);font-size:48px;letter-spacing:2px;margin-bottom:12px}
.intake-inner .intake-sub{font-size:14px;color:rgba(255,255,255,0.5);margin-bottom:40px;line-height:1.6}
.intake-embed{background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:14px;min-height:500px;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.3);font-family:var(--mono);font-size:13px;padding:40px}

/* ═══ FOOTER ═══ */
.ftr{background:var(--blue-dark);border-top:1px solid rgba(255,255,255,0.05);padding:80px 0 30px}
.ftr-b{display:flex;justify-content:space-between;align-items:center;padding-top:20px;border-top:1px solid rgba(255,255,255,0.02);flex-wrap:wrap;gap:10px}
.ftr-l{display:flex;gap:16px;flex-wrap:wrap}
.ftr-l a{color:rgba(255,255,255,0.4);text-decoration:none;font-size:12px;transition:color 0.3s}
.ftr-l a:hover{color:var(--white)}
.ftr-c{font-size:12px;color:rgba(255,255,255,0.2)}
</style>
HTML,
],

[
'tag'   => 'atp_hp_scripts',
'label' => 'Homepage — Scripts',
'desc'  => 'All homepage JavaScript: poll bar animation, hero canvas chart, survey simulation, GSAP scroll animations. Place at bottom of page.',
'default' => <<<'HTML'
<script>
gsap.registerPlugin(ScrollTrigger, TextPlugin);

/* ═══ POLL BAR ═══ */
(function(){
  const slides=document.querySelectorAll('.pb-slide');
  let cur=0;
  setInterval(()=>{
    gsap.to(slides[cur],{opacity:0,x:-100,duration:0.4,onComplete:()=>{slides[cur].classList.remove('active');gsap.set(slides[cur],{x:100})}});
    cur=(cur+1)%slides.length;
    gsap.set(slides[cur],{x:100,opacity:0});
    slides[cur].classList.add('active');
    gsap.to(slides[cur],{opacity:1,x:0,duration:0.4,delay:0.1});
  },4000);
})();

/* ═══ HEADER SCROLL ═══ */
window.addEventListener('scroll',()=>{document.getElementById('hdr').classList.toggle('scrolled',window.scrollY>40)});

/* ═══ HERO ENTRANCE ═══ */
const htl=gsap.timeline({delay:0.2});
htl.to('.h-line',{opacity:1,y:0,duration:0.8,stagger:0.15,ease:'power3.out'})
   .to('.hero-sub',{opacity:1,y:0,duration:0.8,ease:'power2.out'},'-=0.4')
   .to('.hero-btns',{opacity:1,y:0,duration:0.5,ease:'back.out(1.5)'},'-=0.2')
   .to('.hf',{opacity:1,x:0,duration:0.8,ease:'power3.out'},'-=0.8');

/* ═══ HERO CANVAS CHART ═══ */
(function(){
  const canvas=document.getElementById('hero-canvas');
  if(!canvas) return;
  const ctx=canvas.getContext('2d');
  let W,H,t=0,shiftTimer=0;
  const POINTS=120;
  let redHistory=[],blueHistory=[];
  let redVal=48,targetRed=48,seed=48;
  for(let i=0;i<POINTS;i++){seed=Math.max(41,Math.min(59,seed+(Math.random()-0.48)*1.2));redHistory.push(seed);blueHistory.push(100-seed)}
  function resize(){const rect=canvas.parentElement.getBoundingClientRect();W=canvas.width=rect.width;H=canvas.height=rect.height}
  resize();window.addEventListener('resize',resize);
  function getY(val){const cT=H*0.78,cH=H*0.18;return cT+cH-((val-40)/22)*cH}
  function drawChart(){
    const len=redHistory.length,cT=H*0.78,cH=H*0.18,cB=cT+cH;
    [[redHistory,'rgba(212,43,43,0.15)','rgba(212,43,43,0)'],[blueHistory,'rgba(58,95,217,0.15)','rgba(58,95,217,0)']].forEach(([arr,cTop,cBot])=>{
      ctx.beginPath();arr.forEach((v,i)=>{const x=(i/(len-1))*W,y=getY(v);i===0?ctx.moveTo(x,y):ctx.lineTo(x,y)});ctx.lineTo(W,cB);ctx.lineTo(0,cB);ctx.closePath();
      const grad=ctx.createLinearGradient(0,cT,0,cB);grad.addColorStop(0,cTop);grad.addColorStop(1,cBot);ctx.fillStyle=grad;ctx.fill()});
    [[redHistory,'rgba(212,43,43,0.85)'],[blueHistory,'rgba(58,95,217,0.85)']].forEach(([arr,stroke])=>{
      ctx.beginPath();arr.forEach((v,i)=>{const x=(i/(len-1))*W,y=getY(v);i===0?ctx.moveTo(x,y):ctx.lineTo(x,y)});ctx.strokeStyle=stroke;ctx.lineWidth=2;ctx.stroke()});
  }
  function shiftPoll(){targetRed=Math.max(41,Math.min(59,targetRed+(Math.random()-0.48)*1.8))}
  function frame(){ctx.clearRect(0,0,W,H);drawChart();redVal+=(targetRed-redVal)*0.02;shiftTimer++;if(shiftTimer>60){shiftPoll();shiftTimer=0}
    if(Math.round(t*60)%120===0){redHistory.push(redVal);blueHistory.push(100-redVal);if(redHistory.length>POINTS){redHistory.shift();blueHistory.shift()}}
    const bar=document.getElementById('raceRed');if(bar)bar.style.width=redVal+'%';t+=0.016;requestAnimationFrame(frame)}
  frame();
})();

/* ═══ ABOUT / TRUST SCROLL ═══ */
gsap.from('.about-text',{scrollTrigger:{trigger:'.about-atp',start:'top 70%'},x:-30,opacity:0,duration:0.8,ease:'power2.out'});
gsap.from('.about-video',{scrollTrigger:{trigger:'.about-atp',start:'top 70%'},x:30,opacity:0,duration:0.8,ease:'power2.out',delay:0.2});
gsap.from('.trust-card',{scrollTrigger:{trigger:'.trust-sec',start:'top 70%'},y:30,opacity:0,duration:0.5,stagger:0.15,ease:'power2.out'});

/* ═══ SURVEY SIMULATION ═══ */
function runSurveySim(){
  var surveyTl=null;
  var inactivityTimer=null;

  function resetToSMS(){
    var smsView=document.getElementById('phone-sms-view');
    var formView=document.getElementById('phone-form-view');
    var statusBar=document.querySelector('.iphone-statusbar');
    if(formView)formView.style.display='none';
    if(smsView){smsView.style.display='flex';smsView.style.transform='';smsView.style.opacity='1'}
    if(statusBar){statusBar.classList.remove('inverted');var screen=statusBar.closest('.iphone-screen');if(screen)screen.classList.remove('dark-mode')}
    var link=document.getElementById('sms-link');if(link)link.style.color='var(--blue)';
    gsap.set('.sms-tap-indicator',{opacity:0,scale:1.5});
  }

  function startInactivityTimer(){
    clearTimeout(inactivityTimer);
    inactivityTimer=setTimeout(function(){
      resetToSMS();
      playAnimation();
    },10000);
  }

  function playAnimation(){
    if(surveyTl){surveyTl.kill()}
    var smsView=document.getElementById('phone-sms-view');
    var formView=document.getElementById('phone-form-view');
    var statusBar=document.querySelector('.iphone-statusbar');
    if(!smsView||!formView)return;

    resetToSMS();

    surveyTl=gsap.timeline({
      onComplete:function(){startInactivityTimer()}
    });

    // Pause on SMS, then show tap indicator
    surveyTl.to('.sms-tap-indicator',{opacity:1,scale:1,duration:0.5,ease:'back.out',delay:2})
      .to('.sms-tap-indicator',{scale:0.85,duration:0.1,yoyo:true,repeat:1},'+=0.4')
      .to('#sms-link',{color:'var(--red)',duration:0.2})
    // Slide SMS out, slide web view in
      .call(function(){
        formView.style.display='flex';
        if(statusBar){statusBar.classList.add('inverted');var screen=statusBar.closest('.iphone-screen');if(screen)screen.classList.add('dark-mode')}
      })
      .to(smsView,{x:'-100%',opacity:0,duration:0.4,ease:'power2.in',onComplete:function(){smsView.style.display='none'}})
      .from(formView,{x:'100%',opacity:0,duration:0.4,ease:'power2.out'});
    // Timeline ends here — inactivity timer (10s) starts via onComplete
  }

  playAnimation();
}
ScrollTrigger.create({trigger:'.survey-sim',start:'top 60%',once:true,onEnter:function(){runSurveySim()}});

/* ═══ JOURNEY ═══ */
gsap.set('.j-card',{y:40,opacity:0});
ScrollTrigger.create({trigger:'.journey',start:'top 80%',once:true,onEnter:()=>{gsap.to('.j-card',{y:0,opacity:1,duration:0.6,stagger:0.15,ease:'power2.out'})}});

/* ═══ PIPELINE ═══ */
const pipeTl=gsap.timeline({scrollTrigger:{trigger:'.pipe-sec',start:'top 60%',end:'bottom 80%',scrub:1}});
pipeTl.to('#node-1',{borderColor:'var(--red)',boxShadow:'0 10px 40px rgba(178,34,52,0.3)',duration:0.1})
  .to('#conn-1-fill',{height:'100%',duration:0.5})
  .to('#node-2',{borderColor:'var(--red)',boxShadow:'0 10px 40px rgba(178,34,52,0.3)',duration:0.1})
  .to('.pipe-fan-line',{strokeDashoffset:0,duration:1,stagger:0.08})
  .to('.p-branch',{backgroundColor:'var(--red)',borderColor:'var(--red-bright)',opacity:1,y:-5,duration:0.1,stagger:0.05});

/* ═══ CHATGPT AEO ═══ */
const cgTl=gsap.timeline({scrollTrigger:{trigger:'#chatgpt-trigger',start:'top 70%'}});
const aiText="Sarah Chen is a community leader and candidate for District 5. According to her official campaign platform, her key policy priorities include:\n\n\u2022 Expanding local education budgets and teacher pay\n\u2022 Fixing infrastructure on Route 9 and local roads\n\u2022 Lowering municipal taxes for small businesses\n\u2022 Improving healthcare access in underserved areas\n\nHer campaign emphasizes data-driven governance and direct voter engagement through community polling.";
cgTl.to('#cg-user',{opacity:1,y:0,duration:0.5})
  .to('#cg-typing',{opacity:1,duration:0.3},'+=0.3')
  .to('#cg-typing',{opacity:0,duration:0.2},'+=1.2')
  .to('#cg-response',{opacity:1,duration:0.3})
  .to('#cg-response-text',{text:aiText,duration:4,ease:'none'})
  .to('#cg-source',{opacity:1,duration:0.4,ease:'power2.out'})
  .to('#aeo-status',{opacity:1,duration:0.4},'-=0.2')
  .to('#aeo-status',{className:'aeo-badge active',duration:0.3},'+=0.5');
</script>
HTML,
],

]], // end Homepage Styles & Scripts


/* ══════════════════════════════════════════════════════════════
   HOMEPAGE — SECTIONS
══════════════════════════════════════════════════════════════ */
[
'group' => 'Homepage — Sections',
'shortcodes' => [

[
'tag'   => 'atp_hp_pollbar',
'label' => 'HP: Poll Bar',
'desc'  => 'The animated red ticker bar at the top with 5 rotating slide items.',
'default' => <<<'HTML'
<div class="poll-bar">
  <div class="pb-track">
    <div class="pb-slide active" id="pb-0"><span class="pb-badge">DATA INSIGHT</span><span class="pb-text"><strong>82% of voters</strong> search a candidate on their phone while in line to vote.</span></div>
    <div class="pb-slide" id="pb-1"><span class="pb-badge">7 STRATEGIES</span><span class="pb-text">ATP delivers <strong>7 proven strategies</strong> that win elections.</span></div>
    <div class="pb-slide" id="pb-2"><span class="pb-badge">95% REACH</span><span class="pb-text">Coordinated multi-channel outreach achieving <strong>95% voter reach</strong>.</span></div>
    <div class="pb-slide" id="pb-3"><span class="pb-badge">AEO</span><span class="pb-text">Control how <strong>AI describes your campaign</strong> to voters in real time.</span></div>
    <div class="pb-slide" id="pb-4"><span class="pb-badge">COMPLIANCE</span><span class="pb-text">Full <strong>TCPA & 10DLC</strong> compliant messaging built in.</span></div>
  </div>
</div>
HTML,
],

[
'tag'   => 'atp_hp_header',
'label' => 'HP: Header',
'desc'  => 'The fixed header with logo mark, brand name, nav links, and Get Started button.',
'default' => <<<'HTML'
<header class="hdr" id="hdr"><div class="wrap">
  <div class="hdr-left"><div class="hdr-mark"><img src="{ATP_PLUGIN_URL}assets/images/ATP-Logo-Red-White.png" alt="ATP" style="width:32px;height:auto"></div><div class="hdr-brand"><span class="w">AMERICA </span><span class="r">TRACKING </span><span class="w">POLLS</span></div></div>
  <nav class="hdr-nav">
    <a href="#about">About</a>
    <a href="#journey">The Path</a>
    <a href="#pipeline">The Pipeline</a>
    <a href="#aeo">AEO</a>
  </nav>
  <button class="hdr-btn" onclick="document.getElementById('intake').scrollIntoView({behavior:'smooth'})">Get Started</button>
</div></header>
HTML,
],

[
'tag'   => 'atp_hp_hero',
'label' => 'HP: Hero',
'desc'  => 'The hero section with canvas animation, grid overlay, race bar, headline text, and the contact form (.hf).',
'default' => <<<'HTML'
<section class="hero">
  <canvas id="hero-canvas"></canvas>
  <div class="hero-grid"></div>
  <div class="hero-chart-blur"></div>
  <div class="hero-race-bar">
    <div class="hero-race-red" id="raceRed" style="width:50%"></div>
    <div class="hero-race-blue"></div>
  </div>
  <div class="hero-inner"><div class="wrap">
    <div class="hero-text">
      <div class="h-tag">SYNCHRONIZED MULTI-CHANNEL CAMPAIGN SOLUTIONS</div>
      <div class="h-line h-big">WIN YOUR ELECTION</div>
      <div class="h-line h-big h-red">BEFORE ELECTION DAY</div>
      <div class="h-line h-big">REACHING 95%</div>
      <p class="hero-sub">Real voter insight in real time&mdash;so you know exactly what to say, who to target, and when it matters most.</p>
      <p class="hero-sub" style="opacity:1;transform:none"><strong>Real Insights. Targeted Delivery. Winning Results.</strong></p>
      <div class="hero-btns">
        <a href="#about" class="btn-video"><svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg> SEE HOW WE HELP YOU WIN</a>
      </div>
    </div>
    <div class="hf" style="padding:24px 24px 0;border:none;background:rgba(17,16,48,0.7);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);box-shadow:0 20px 60px rgba(0,0,0,0.5);border-radius:14px;border-top:4px solid var(--red);opacity:1;transform:none">
      <p style="font-family:var(--display);font-size:18px;letter-spacing:2px;color:var(--white);text-align:center;margin-bottom:4px">REQUEST YOUR PROPOSAL</p>
      <p style="font-size:11px;color:rgba(255,255,255,0.5);text-align:center;margin-bottom:16px;line-height:1.4">Fill out the form below and our team will build a custom strategy for your race.</p>
      <iframe src="https://atp.ameritrackpolls.com/to/nhPPYcJ8" style="width:100%;min-height:580px;border:0;border-radius:8px" loading="lazy"></iframe>
    </div>
  </div></div>
</section>
HTML,
],

[
'tag'   => 'atp_hp_about',
'label' => 'HP: About',
'desc'  => 'The "Why Work With America Tracking Polls?" white section.',
'default' => <<<'HTML'
<section class="about-atp" id="about"><div class="wrap"><div class="about-grid">
  <div class="about-text">
    <h2>WHY WORK WITH <span class="red">AMERICA TRACKING POLLS</span>?</h2>
    <p>ATP is a <strong>Florida-based election research and campaign strategy firm</strong> delivering voter engagement, compliance expertise, and data-driven best practices for local, statewide, and federal races.</p>
    <p>Our formula is simple: <strong>Intelligence + Engagement + Persistence + Conversion = Victory.</strong> We turn real voter insight into action&mdash;powering every piece of your campaign from your website to how AI defines you.</p>
  </div>
  <div class="about-video">
    <div style="width:100%;max-width:560px;margin:0 auto">
      <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:14px;box-shadow:0 20px 60px rgba(17,16,48,0.3)">
        <iframe src="https://www.youtube.com/embed/hhw5CgypgQE" style="position:absolute;top:0;left:0;width:100%;height:100%;border:0" allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture" allowfullscreen></iframe>
      </div>
      <p style="text-align:center;margin-top:16px;font-family:var(--display);font-size:14px;letter-spacing:2px;color:var(--blue-dark)">Data Driven Websites</p>
    </div>
  </div>
</div></div></section>
HTML,
],

[
'tag'   => 'atp_hp_survey',
'label' => 'HP: Survey Simulation',
'desc'  => 'The survey simulation section with the iPhone mockup and explanatory text.',
'default' => <<<'HTML'
<section class="survey-sim" id="survey-sim"><div class="wrap">
  <div class="survey-hdr">
    <h2><span style="color:var(--red)">INTELLIGENCE</span> DRIVES RESULTS</h2>
    <p>Reach voters where they are. Build real connections. Convert supporters into donors, volunteers, and votes.</p>
  </div>
  <div class="survey-container">
    <div class="iphone" style="flex-shrink:0">
      <div class="iphone-screen">
        <div class="iphone-dynamic-island"></div>
        <div class="iphone-statusbar"><span>9:41</span><div class="iphone-statusbar-icons"><svg width="16" height="12" viewBox="0 0 16 12"><rect x="0" y="3" width="3" height="9" rx="0.5" fill="#000"/><rect x="4.5" y="2" width="3" height="10" rx="0.5" fill="#000"/><rect x="9" y="0" width="3" height="12" rx="0.5" fill="#000"/><rect x="13.5" y="1" width="3" height="11" rx="0.5" fill="#000" opacity="0.3"/></svg><svg width="22" height="12" viewBox="0 0 22 12"><rect x="0" y="1" width="18" height="10" rx="2" stroke="#000" stroke-width="1.2" fill="none"/><rect x="1.5" y="2.5" width="12" height="7" rx="1" fill="#000"/><rect x="19" y="4" width="2" height="4" rx="0.5" fill="#000"/></svg></div></div>
        <div id="phone-sms-view" style="display:flex;flex-direction:column;flex:1;overflow:hidden">
          <div class="msg-header">
            <a class="msg-back"><svg width="10" height="18" viewBox="0 0 10 18" fill="none"><path d="M9 1L1 9L9 17" stroke="var(--blue)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
            <div class="msg-avatar">SC</div>
            <div style="display:flex;flex-direction:column"><div class="msg-contact-name">Sarah Chen Campaign</div><div class="msg-contact-label">SMS</div></div>
          </div>
          <div class="msg-thread">
            <div class="msg-time">Today 2:14 PM</div>
            <div class="msg-bubble msg-incoming">Hi! It&rsquo;s Sarah Chen&rsquo;s campaign. We want to best serve your needs. Please take 30 seconds to fill out this quick survey:</div>
            <div class="msg-bubble msg-incoming" style="margin-top:2px"><a href="#" id="sms-link" style="color:var(--blue);text-decoration:underline;font-family:-apple-system,sans-serif;font-size:14px">https://form.run/sarahchen</a></div>
            <div class="msg-delivered">Delivered</div>
          </div>
          <div class="msg-inputbar">
            <div class="msg-inputbar-plus"><svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div>
            <div class="msg-inputbar-field">Text Message</div>
            <div class="msg-inputbar-send"><svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" fill="var(--blue)"/></svg></div>
          </div>
          <div class="sms-tap-indicator"><svg viewBox="0 0 24 24" width="36" height="36"><path fill="rgba(0,0,0,0.6)" d="M9 11.24V7.5C9 6.12 10.12 5 11.5 5S14 6.12 14 7.5v3.74c1.21-.81 2-2.18 2-3.74C16 5.01 13.99 3 11.5 3S7 5.01 7 7.5c0 1.56.79 2.93 2 3.74zm9.84 4.63l-4.54-2.26c-.17-.07-.35-.11-.54-.11H13v-6c0-.83-.67-1.5-1.5-1.5S10 6.67 10 7.5v10.74l-3.43-.72c-.08-.01-.15-.03-.24-.03-.31 0-.59.13-.79.33l-.79.8 4.94 4.94c.27.27.65.44 1.04.44h6.79c.75 0 1.33-.55 1.44-1.28l.75-5.27c.01-.07.02-.14.02-.21 0-.69-.39-1.32-1.02-1.53z"/></svg></div>
        </div>
        <div id="phone-form-view" style="display:none;flex-direction:column;flex:1;overflow:hidden;background:#fff">
          <div style="background:var(--blue-dark);padding:10px 16px;display:flex;align-items:center;gap:10px">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="#fff"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
            <span style="font-family:-apple-system,sans-serif;font-size:14px;font-weight:600;color:#fff">Voter Insight Survey</span>
          </div>
          <iframe src="https://atp.ameritrackpolls.com/BenchmarkWeb" style="flex:1;width:100%;border:0" loading="lazy"></iframe>
        </div>
      </div>
    </div>
    <div class="survey-text">
      <h3>YOUR BENCHMARK SURVEY IS YOUR COMPETITIVE ADVANTAGE</h3>
      <p>Most campaigns guess. You won&rsquo;t. We deliver MMS surveys to registered voters across your district&mdash;linking to a fast, mobile-optimized survey with objective, unbiased questions.</p>
      <p>Within seconds, voters tell you exactly what matters. At the same time, you&rsquo;re tracking Name ID and favorability in real time&mdash;so you know not just what voters think, but how they see you.</p>
      <h4>WHAT YOUR OPPONENT DOESN&rsquo;T KNOW&mdash;YOU DO</h4>
      <ul>
        <li>Which issues actually move votes</li>
        <li>Your true Name ID&mdash;and how fast it&rsquo;s growing</li>
        <li>Who will show up&mdash;and who needs to be persuaded</li>
        <li>Where support is strong&mdash;and where you&rsquo;re losing ground</li>
        <li>Real sentiment data&mdash;not consultant assumptions</li>
      </ul>
      <h4>THIS ISN&rsquo;T POLLING. IT&rsquo;S CONTROL.</h4>
      <p>Every response immediately powers your campaign&mdash;shaping your messaging across MMS, digital, social, your website, print, and even how AI platforms define you.</p>
      <p><strong>They guess. You know. They spend. You convert. They hope. You win.</strong></p>
      <p style="margin-top:16px;font-family:var(--mono);font-size:11px;color:#999;letter-spacing:1px">FULLY TCPA &amp; 10DLC COMPLIANT</p>
    </div>
  </div>
</div></section>
HTML,
],

[
'tag'   => 'atp_hp_journey',
'label' => 'HP: Journey',
'desc'  => 'The 5-step Your Strategic Path to Victory journey section.',
'default' => <<<'HTML'
<section class="journey" id="journey"><div class="wrap">
  <div class="journey-hdr"><h2>YOUR STRATEGIC PATH TO VICTORY</h2><p>A proven, six-step system that transforms real-time voter intelligence into engagement, persuasion, and continuous fundraising&mdash;from day one through Election Day.</p></div>
  <div class="j-grid">
    <div class="j-card"><div class="j-num">1</div><h4>INTELLIGENCE</h4><p>We deploy synchronized surveys across MMS, digital, and QR-driven channels&mdash;capturing real voter sentiment while tracking Name ID persistently so you always know where you stand.</p></div>
    <div class="j-card"><div class="j-num">2</div><h4>AEO INTEGRATION</h4><p>We structure your biography, issues, and messaging for AI and search&mdash;ensuring platforms like ChatGPT and Google accurately define and amplify your campaign.</p></div>
    <div class="j-card"><div class="j-num">3</div><h4>ENGAGEMENT</h4><p>We meet voters where they are&mdash;through coordinated MMS, digital ads, social media, and QR-coded print&mdash;building ongoing, meaningful connections at scale.</p></div>
    <div class="j-card"><div class="j-num">4</div><h4>ANALYSIS</h4><p>Every interaction feeds your real-time dashboard&mdash;delivering instant insights, cross-tabs, and trends that drive smarter decisions.</p></div>
    <div class="j-card"><div class="j-num">5</div><h4>CONVERSION</h4><p>We refine messaging continuously&mdash;moving undecided voters to your side and turning support into donations and votes.</p></div>
    <div class="j-card"><div class="j-num">6</div><h4>FUNDING THE WIN</h4><p>Every engagement becomes an opportunity. As supporters are identified, they are seamlessly guided to contribute&mdash;creating persistent, scalable fundraising that powers your campaign every day.</p></div>
  </div>
  <p style="text-align:center;font-family:var(--display);font-size:24px;letter-spacing:2px;color:var(--blue-dark);margin-top:40px"><strong>Engage. Persuade. Fund. Win.</strong></p>
</div></section>
HTML,
],

[
'tag'   => 'atp_hp_pipeline',
'label' => 'HP: Pipeline',
'desc'  => 'The pipeline diagram section showing the data flow from voter polling to 5 output channels.',
'default' => <<<'HTML'
<section class="pipe-sec" id="pipeline"><div class="wrap pipe-wrap">
  <div class="pipe-text">
    <h2>FROM PERSISTENT FEEDBACK TO<br>VOTER ACTION</h2>
    <p>Every campaign output starts with real voter data. We survey your district, extract what matters most, and feed it directly into our strategy engine.</p>
    <p>That engine automatically powers every channel&mdash;your website, ads, mailers, texts, and AI presence all pivot based on the same intelligence. <strong>When the data shifts, your messaging shifts with it.</strong></p>
    <p>No more disconnected vendors. No more guessing. One source of truth, powering everything.</p>
  </div>
  <div class="pipeline-diagram">
    <div class="p-node" id="node-1"><div class="p-node-title">1. VOTER POLLING</div><div class="p-node-desc">RAW DATA &amp; SENTIMENT ANALYSIS</div></div>
    <div class="pipe-connector" id="conn-1"><div class="pipe-connector-active" id="conn-1-fill"></div></div>
    <div class="p-node" id="node-2"><div class="p-node-title">2. ATP STRATEGY ENGINE</div><div class="p-node-desc">CENTRALIZED CAMPAIGN HUB</div></div>
    <div class="pipe-fan"><svg viewBox="0 0 400 40" preserveAspectRatio="none"><path d="M200,0 L200,15 L40,15 L40,40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="2"/><path d="M200,0 L200,15 L120,15 L120,40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="2"/><path d="M200,0 L200,40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="2"/><path d="M200,0 L200,15 L280,15 L280,40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="2"/><path d="M200,0 L200,15 L360,15 L360,40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="2"/><path class="pipe-fan-line" d="M200,0 L200,15 L40,15 L40,40" fill="none" stroke="var(--red)" stroke-width="3" stroke-dasharray="200" stroke-dashoffset="200"/><path class="pipe-fan-line" d="M200,0 L200,15 L120,15 L120,40" fill="none" stroke="var(--red)" stroke-width="3" stroke-dasharray="200" stroke-dashoffset="200"/><path class="pipe-fan-line" d="M200,0 L200,40" fill="none" stroke="var(--red)" stroke-width="3" stroke-dasharray="200" stroke-dashoffset="200"/><path class="pipe-fan-line" d="M200,0 L200,15 L280,15 L280,40" fill="none" stroke="var(--red)" stroke-width="3" stroke-dasharray="200" stroke-dashoffset="200"/><path class="pipe-fan-line" d="M200,0 L200,15 L360,15 L360,40" fill="none" stroke="var(--red)" stroke-width="3" stroke-dasharray="200" stroke-dashoffset="200"/></svg></div>
    <div class="p-branches">
      <div class="p-branch" id="branch-1"><div class="p-branch-icon"><svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8h16v10z"/></svg></div><div class="p-branch-title">WEB</div><div class="p-branch-desc">VOTER SITES &amp;<br>LANDING PAGES</div></div>
      <div class="p-branch" id="branch-2"><div class="p-branch-icon"><svg viewBox="0 0 24 24"><path d="M3 3h18v2H3zm0 16h18v2H3zm0-8h18v2H3zm4-4h14v2H7zm0 8h14v2H7z"/></svg></div><div class="p-branch-title">ADS</div><div class="p-branch-desc">TARGETED<br>DIGITAL ADS</div></div>
      <div class="p-branch" id="branch-3"><div class="p-branch-icon"><svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg></div><div class="p-branch-title">MAIL</div><div class="p-branch-desc">DIRECT MAIL<br>&amp; PRINT</div></div>
      <div class="p-branch" id="branch-4"><div class="p-branch-icon"><svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H5.17L4 17.17V4h16v12z"/></svg></div><div class="p-branch-title">SMS</div><div class="p-branch-desc">TEXT VOTER<br>OUTREACH</div></div>
      <div class="p-branch" id="branch-5"><div class="p-branch-icon"><svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg></div><div class="p-branch-title">AEO</div><div class="p-branch-desc">AI ANSWER<br>ENGINE OPT.</div></div>
    </div>
  </div>
</div></section>
HTML,
],

[
'tag'   => 'atp_hp_aeo',
'label' => 'HP: AEO Section',
'desc'  => 'The AEO/ChatGPT mockup section demonstrating AI answer engine optimization.',
'default' => <<<'HTML'
<section class="aeo-sec" id="aeo"><div class="wrap aeo-wrap">
  <div class="aeo-text">
    <h2>THE VOTING LINE REALITY:<br><span class="red">AI IS THE NEW SEARCH</span></h2>
    <p>Voters are pulling out their smartphones while literally standing in line to vote. They aren't going to your website&mdash;they are asking ChatGPT, Gemini, or Google's AI Overview to summarize who you are.</p>
    <p><strong>If you haven't optimized for Answer Engines (AEO), the AI will scrape together outdated information, opponent attack ads, or simply say it doesn't know you.</strong></p>
    <p>ATP structures your campaign data so that Large Language Models correctly cite your platform, giving you control of the narrative at the exact moment a voter is deciding. Don't let AI define you. Define yourself.</p>
  </div>
  <div>
    <div class="chatgpt-mockup" id="chatgpt-trigger">
      <div class="cg-sidebar"><div class="cg-sidebar-dot"></div><div class="cg-sidebar-label">ChatGPT</div><div class="cg-model-pill">GPT-5.3</div></div>
      <div class="cg-body">
        <div class="cg-msg cg-msg-user" id="cg-user" style="opacity:0;transform:translateY(10px)"><div class="cg-bubble cg-bubble-user">Tell me about Sarah Chen running for District 5. What does she stand for?</div><div class="cg-avatar cg-avatar-user"><svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg></div></div>
        <div class="cg-msg" id="cg-typing" style="opacity:0"><div class="cg-avatar cg-avatar-ai"><svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" fill="none" stroke="white" stroke-width="2"/></svg></div><div class="cg-bubble cg-bubble-ai"><div class="cg-typing-dots"><span></span><span></span><span></span></div></div></div>
        <div class="cg-msg" id="cg-response" style="opacity:0"><div class="cg-avatar cg-avatar-ai"><svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" fill="none" stroke="white" stroke-width="2"/></svg></div><div class="cg-bubble cg-bubble-ai"><span id="cg-response-text"></span><div class="cg-sources" id="cg-source" style="opacity:0"><span class="cg-source"><img src="https://www.google.com/s2/favicons?domain=americatrackingpolls.com&sz=32" alt=""> americatrackingpolls.com</span><span class="cg-source"><img src="https://www.google.com/s2/favicons?domain=sarahchenfordistrict5.com&sz=32" alt=""> sarahchenfordistrict5.com</span><span class="cg-source"><img src="https://www.google.com/s2/favicons?domain=wikipedia.org&sz=32" alt=""> en.wikipedia.org</span><span class="cg-source"><img src="https://www.google.com/s2/favicons?domain=ballotpedia.org&sz=32" alt=""> ballotpedia.org</span></div></div></div>
      </div>
      <div class="cg-inputbar"><div class="cg-inputbar-field">Ask anything...</div><div class="cg-inputbar-send"><svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg></div></div>
    </div>
    <div class="aeo-badge-wrap"><div class="aeo-badge" id="aeo-status"><div style="width:8px;height:8px;background:currentColor;border-radius:50%"></div><span id="aeo-label">ATP AEO OPTIMIZED</span></div></div>
  </div>
</div></section>
HTML,
],

[
'tag'   => 'atp_hp_trust',
'label' => 'HP: Trust / Compliance',
'desc'  => 'The compliance-first 3-card trust section (TCPA, AI Ethics, Data Privacy).',
'default' => <<<'HTML'
<section class="trust-sec"><div class="wrap">
  <div class="trust-hdr"><h2>COMPLIANCE-FIRST. <span style="color:var(--red)">ALWAYS.</span></h2><p>We take regulatory compliance and ethical AI usage seriously. Every campaign we run meets the highest standards.</p></div>
  <div class="trust-grid">
    <div class="trust-card">
      <div class="trust-icon"><svg viewBox="0 0 24 24" width="28" height="28" fill="var(--red)"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg></div>
      <h4>TCPA &amp; 10DLC COMPLIANCE</h4>
      <p>Every text message we send meets Telephone Consumer Protection Act requirements and uses registered 10-Digit Long Code numbers. Full opt-in/opt-out management built in.</p>
    </div>
    <div class="trust-card">
      <div class="trust-icon"><svg viewBox="0 0 24 24" width="28" height="28" fill="var(--red)"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg></div>
      <h4>AI ETHICS PLEDGE</h4>
      <p>We have a public commitment to responsible AI usage. Our AEO content is factual, source-verified, and designed to inform&mdash;never to mislead voters or manipulate AI outputs.</p>
    </div>
    <div class="trust-card">
      <div class="trust-icon"><svg viewBox="0 0 24 24" width="28" height="28" fill="var(--red)"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg></div>
      <h4>DATA PRIVACY</h4>
      <p>Voter data is encrypted, stored securely, and never sold to third parties. We follow strict privacy protocols for every campaign we manage.</p>
    </div>
  </div>
</div></section>
HTML,
],

[
'tag'   => 'atp_hp_intake',
'label' => 'HP: Intake Form Section',
'desc'  => 'The intake embed placeholder section with heading and Typeform embed area.',
'default' => <<<'HTML'
<section class="intake-sec" id="intake"><div class="wrap">
  <div class="intake-inner">
    <h2>GET STARTED WITH ATP</h2>
    <p class="intake-sub">Whether you're launching a campaign, exploring a run, or building your public brand before Election Day&mdash;we'll create a custom strategy powered by real voter data. Fill out the form below and our team will reach out within 24 hours.</p>
    <p class="intake-sub" style="margin-bottom:40px;font-size:12px;opacity:0.6">Free consultation. No obligation. We serve local, statewide, and federal races.</p>
    <div class="intake-embed" style="background:transparent;border:none;display:block;padding:0">
      <iframe src="https://atp.ameritrackpolls.com/to/nhPPYcJ8" style="width:100%;min-height:600px;border:0;border-radius:14px" loading="lazy"></iframe>
    </div>
  </div>
</div></section>
HTML,
],

[
'tag'   => 'atp_hp_footer',
'label' => 'HP: Footer',
'desc'  => 'The dark footer with brand name, nav links, and copyright.',
'default' => <<<'HTML'
<footer class="ftr" id="footer"><div class="wrap">
  <div class="ftr-b">
    <div class="hdr-brand" style="font-size:16px"><span class="w">AMERICA </span><span class="r">TRACKING </span><span class="w">POLLS</span></div>
    <div class="ftr-l"><a href="#about">About ATP</a><a href="#journey">Strategy</a><a href="#aeo">AEO</a><a href="#intake">Get Started</a></div>
    <div class="ftr-c">&copy; 2026 America Tracking Polls LLC. Built by the Numbers.</div>
  </div>
</div></footer>
HTML,
],

]], // end Homepage Sections


/* ══════════════════════════════════════════════════════════════
   BRAND GUIDE — STYLES & SCRIPTS
══════════════════════════════════════════════════════════════ */
[
'group' => 'Brand Guide — Styles & Scripts',
'shortcodes' => [

[
'tag'   => 'atp_brand_styles',
'label' => 'Brand Guide: Styles',
'desc'  => 'Google Fonts link + all brand-guide.html CSS wrapped in <style> tags.',
'default' => <<<'HTML'
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
:root{
  --blue:#3C3B6E;--blue-mid:#2E2D5A;--blue-deep:#1A1940;--blue-dark:#111030;
  --hero-dark:#0e1235;
  --red:#B22234;--red-bright:#D42E43;
  --white:#FFFFFF;--off-white:#F5F6FA;--gray:#8A8FA0;--dark:#1A1A2E;
  --display:'Bebas Neue',sans-serif;--body:'Libre Baskerville',Georgia,serif;
  --mono:'Space Mono',monospace;
}
html{scroll-behavior:smooth}
body{font-family:var(--body);color:var(--white);background:var(--blue-dark);overflow-x:hidden;-webkit-font-smoothing:antialiased}
.brand-nav{position:fixed;top:0;left:0;right:0;z-index:500;height:60px;display:flex;align-items:center;background:rgba(17,16,48,0.95);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-bottom:1px solid rgba(255,255,255,0.04)}
.brand-nav .wrap{display:flex;align-items:center;justify-content:space-between;width:100%}
.nav-brand{font-family:var(--display);font-size:18px;letter-spacing:3px;color:var(--white);text-decoration:none}
.nav-brand .red{color:var(--red)}
.nav-links{display:flex;gap:20px;flex-wrap:wrap}
.nav-links a{color:rgba(255,255,255,0.5);text-decoration:none;font-size:10px;letter-spacing:0.5px;text-transform:uppercase;transition:color 0.3s;font-family:var(--body)}
.nav-links a:hover{color:var(--white)}
@media(max-width:768px){.nav-links{display:none}}
.brand-hero{position:relative;overflow:hidden;background:var(--hero-dark);display:flex;align-items:center;justify-content:center;padding:140px 0 80px;min-height:70vh;text-align:center}
.hero-logo{margin-bottom:24px}
.hero-inner{position:relative;z-index:10;width:100%}
h1.brand-title{font-family:var(--display);font-size:clamp(52px,7vw,80px);letter-spacing:4px;line-height:1.05;color:var(--white);margin-bottom:4px}
h1.brand-title .red{color:var(--red)}
.brand-subtitle{font-family:var(--mono);font-size:13px;letter-spacing:3px;color:rgba(255,255,255,0.5);text-transform:uppercase}
.hero-buttons{display:flex;gap:16px;justify-content:center;margin-top:40px;flex-wrap:wrap}
.skill-btn{display:inline-flex;align-items:center;gap:10px;background:var(--red);color:var(--white);padding:16px 32px;font-family:var(--display);font-size:16px;letter-spacing:2px;border-radius:4px;cursor:pointer;border:none;transition:all 0.3s}
.skill-btn:hover{background:var(--red-bright);transform:translateY(-2px);box-shadow:0 8px 28px rgba(178,34,52,0.4)}
.skill-btn.copied{background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3)}
.skill-btn svg{width:16px;height:16px;fill:currentColor}
.skill-btn-secondary{background:transparent;border:2px solid rgba(255,255,255,0.3);color:var(--white)}
.skill-btn-secondary:hover{background:rgba(255,255,255,0.1);border-color:rgba(255,255,255,0.5);transform:translateY(-2px)}
.wrap{max-width:1140px;margin:0 auto;padding:0 48px}
@media(max-width:768px){.wrap{padding:0 20px}}
.brand-section{padding:100px 0;border-top:1px solid rgba(255,255,255,0.06);text-align:center}
.brand-section:first-of-type{border-top:none}
.section-alt{background:var(--blue-deep)}
.section-light{background:var(--white);color:var(--dark)}
.section-offwhite{background:var(--off-white);color:var(--dark)}
h2.section-title{font-family:var(--display);font-size:clamp(36px,4.5vw,54px);letter-spacing:3px;line-height:1.1;margin-bottom:12px}
h2.section-title .red{color:var(--red)}
.section-light h2.section-title,.section-offwhite h2.section-title{color:var(--blue-dark)}
.section-desc{font-size:15px;line-height:1.8;color:rgba(255,255,255,0.65);max-width:700px;margin:0 auto 40px;text-align:center}
.section-light .section-desc,.section-offwhite .section-desc{color:#5A5F6E}
h3.sub-title{font-family:var(--display);font-size:28px;letter-spacing:2px;margin-bottom:12px;color:var(--white)}
.section-light h3.sub-title,.section-offwhite h3.sub-title{color:var(--blue-dark)}
.swatch-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:20px;margin-top:24px}
.swatch{border-radius:10px;overflow:hidden;border:1px solid rgba(0,0,0,0.08);box-shadow:0 4px 20px rgba(0,0,0,0.08);cursor:pointer;transition:transform 0.3s,box-shadow 0.3s;position:relative}
.swatch:hover{transform:translateY(-4px);box-shadow:0 8px 30px rgba(0,0,0,0.15)}
.swatch-color{height:120px;position:relative}
.swatch-info{padding:14px 16px;background:var(--white)}
.swatch-name{font-family:var(--display);font-size:16px;letter-spacing:1px;color:var(--blue-dark);margin-bottom:4px}
.swatch-hex{font-family:var(--mono);font-size:12px;color:#5A5F6E}
.swatch-role{font-size:10px;color:var(--gray);margin-top:4px;line-height:1.4}
.swatch-tooltip{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:rgba(0,0,0,0.85);color:var(--white);font-family:var(--mono);font-size:12px;letter-spacing:1px;padding:8px 16px;border-radius:4px;pointer-events:none;opacity:0;transition:opacity 0.3s;z-index:10}
.swatch-tooltip.show{opacity:1}
.font-specimen{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:40px;margin-bottom:24px;text-align:center}
.font-label{font-family:var(--mono);font-size:11px;letter-spacing:2px;color:rgba(255,255,255,0.5);text-transform:uppercase;margin-bottom:16px}
.font-sample-display{font-family:var(--display);color:var(--white);line-height:1.1;margin-bottom:12px}
.font-sample-body{font-family:var(--body);color:rgba(255,255,255,0.7);line-height:1.8;margin-bottom:12px}
.font-sample-mono{font-family:var(--mono);color:rgba(255,255,255,0.7);line-height:1.6;margin-bottom:12px}
.font-weights{display:flex;gap:24px;flex-wrap:wrap;margin-top:8px;justify-content:center}
.font-weight-item{font-size:12px;color:rgba(255,255,255,0.4)}
.logo-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:32px;margin-top:32px}
@media(max-width:960px){.logo-grid{grid-template-columns:1fr}}
.logo-asset{text-align:center}
.logo-asset-preview{display:flex;align-items:center;justify-content:center;padding:48px 32px;border-radius:12px;min-height:240px}
.logo-asset-label{font-family:var(--mono);font-size:12px;letter-spacing:1px;color:rgba(255,255,255,0.5);margin-top:16px}
.logo-download-btn{display:inline-block;margin-top:12px;padding:10px 24px;font-family:var(--display);font-size:14px;letter-spacing:2px;color:var(--white);background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);border-radius:4px;text-decoration:none;transition:all 0.3s}
.logo-download-btn:hover{background:rgba(255,255,255,0.12);border-color:rgba(255,255,255,0.25)}
.logo-dark{background:#0e1235;border:1px solid rgba(255,255,255,0.08)}
.logo-light{background:var(--white);border:1px solid rgba(0,0,0,0.08)}
.logo-label{font-family:var(--mono);font-size:10px;letter-spacing:1px;text-transform:uppercase;margin-top:20px}
.logo-dark .logo-label{color:rgba(255,255,255,0.4)}
.logo-light .logo-label{color:rgba(0,0,0,0.35)}
.guideline-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:24px}
@media(max-width:960px){.guideline-grid{grid-template-columns:1fr}}
.guideline-card{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:32px;transition:border-color 0.3s;text-align:center}
.guideline-card:hover{border-color:rgba(255,255,255,0.15)}
.guideline-card h4{font-family:var(--display);font-size:20px;letter-spacing:1px;color:var(--white);margin-bottom:12px}
.guideline-card p{font-size:13px;color:rgba(255,255,255,0.6);line-height:1.7}
.guideline-card code{font-family:var(--mono);font-size:11px;background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.7);padding:2px 8px;border-radius:3px}
.do-dont-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:24px}
@media(max-width:768px){.do-dont-grid{grid-template-columns:1fr}}
.do-col,.dont-col{border-radius:12px;padding:32px;text-align:left}
.do-col{background:rgba(58,95,217,0.08);border:1px solid rgba(58,95,217,0.2)}
.dont-col{background:rgba(212,43,43,0.08);border:1px solid rgba(212,43,43,0.2)}
.do-col h4,.dont-col h4{font-family:var(--display);font-size:20px;letter-spacing:1px;margin-bottom:16px;text-align:center}
.do-col h4{color:#3a5fd9}
.dont-col h4{color:#d42b2b}
.do-col ul,.dont-col ul{list-style:none;padding:0}
.do-col li,.dont-col li{font-size:13px;color:rgba(255,255,255,0.65);line-height:1.6;padding:6px 0;padding-left:20px;position:relative}
.do-col li::before{content:'+';position:absolute;left:0;color:#3a5fd9;font-family:var(--mono);font-weight:700}
.dont-col li::before{content:'\2013';position:absolute;left:0;color:#d42b2b;font-family:var(--mono);font-weight:700}
.section-light .do-col li,.section-light .dont-col li{color:#5A5F6E}
.bio-text{font-size:15px;line-height:1.9;color:rgba(255,255,255,0.7);max-width:800px;margin:0 auto;text-align:center}
.bio-text p{margin-bottom:20px}
.bio-text strong{color:var(--white);font-weight:700}
.bio-highlight{background:rgba(178,34,52,0.1);border-left:4px solid var(--red);padding:24px 28px;border-radius:0 8px 8px 0;margin:32px auto;font-family:var(--display);font-size:24px;letter-spacing:2px;color:var(--white);line-height:1.3;max-width:700px;text-align:center}
.bio-stats{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:20px;margin-top:40px}
.bio-stat{text-align:center;padding:24px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:10px}
.bio-stat-num{font-family:var(--display);font-size:42px;letter-spacing:2px;color:var(--white)}
.bio-stat-label{font-size:12px;color:rgba(255,255,255,0.5);margin-top:4px}
.social-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;margin-top:32px}
.social-item{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:20px;text-align:center;transition:border-color 0.3s}
.social-item:hover{border-color:var(--red)}
.social-item strong{display:block;font-family:var(--display);font-size:14px;letter-spacing:1px;color:var(--white);margin-bottom:6px}
.social-item a{color:var(--white);text-decoration:none;font-size:13px;word-break:break-all}
.social-item a:hover{color:rgba(255,255,255,0.7)}
.social-item span{color:rgba(255,255,255,0.6);font-size:13px}
.services-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;margin-top:24px}
.service-tag{background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:8px;padding:16px 20px;font-family:var(--mono);font-size:12px;color:rgba(255,255,255,0.7);letter-spacing:0.5px;transition:all 0.3s;text-align:center}
.service-tag:hover{border-color:var(--red);color:var(--white);background:rgba(178,34,52,0.1)}
.photo-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:32px}
@media(max-width:960px){.photo-grid{grid-template-columns:1fr}}
.photo-card{border-radius:12px;overflow:hidden;position:relative;height:240px}
.photo-card img{width:100%;height:100%;object-fit:cover;filter:grayscale(100%);display:block}
.photo-card.treated::after{content:'';position:absolute;inset:0;background:linear-gradient(to right,rgba(60,59,110,0.55),rgba(60,59,110,0));pointer-events:none}
.photo-card .photo-label{position:absolute;bottom:12px;left:12px;right:12px;font-family:var(--mono);font-size:10px;letter-spacing:1px;text-transform:uppercase;color:var(--white);z-index:2;background:rgba(0,0,0,0.5);padding:6px 10px;border-radius:4px;text-align:center}
.cta-demos{display:flex;flex-direction:column;gap:32px;margin-top:24px;align-items:center}
.cta-row{display:flex;align-items:center;gap:20px;flex-wrap:wrap;justify-content:center}
.cta-row-label{font-family:var(--mono);font-size:11px;letter-spacing:1px;color:rgba(255,255,255,0.4);text-transform:uppercase;min-width:120px;text-align:right}
.btn-primary-demo{display:inline-flex;align-items:center;gap:8px;background:var(--red);color:var(--white);padding:14px 32px;font-family:var(--body);font-size:12px;font-weight:700;border-radius:4px;text-decoration:none;transition:all 0.3s;letter-spacing:1px;border:none;cursor:pointer}
.btn-primary-demo:hover{background:var(--red-bright);transform:translateY(-2px);box-shadow:0 8px 28px rgba(178,34,52,0.4)}
.btn-secondary-demo{display:inline-flex;align-items:center;gap:8px;background:transparent;border:1px solid rgba(255,255,255,0.2);color:var(--white);padding:14px 28px;font-family:var(--body);font-size:12px;font-weight:700;border-radius:4px;text-decoration:none;transition:all 0.3s;letter-spacing:1px;cursor:pointer;backdrop-filter:blur(10px)}
.btn-secondary-demo:hover{background:rgba(255,255,255,0.1);transform:translateY(-2px)}
.btn-text-demo{display:inline-flex;align-items:center;gap:6px;background:transparent;border:none;color:rgba(255,255,255,0.7);padding:8px 0;font-family:var(--body);font-size:12px;font-weight:700;text-decoration:none;transition:all 0.3s;letter-spacing:1px;cursor:pointer}
.btn-text-demo:hover{color:var(--white)}
.brand-footer{background:var(--blue-dark);padding:60px 0;border-top:1px solid rgba(255,255,255,0.06);text-align:center}
.brand-footer p{font-size:12px;color:rgba(255,255,255,0.35);line-height:1.8}
.brand-footer a{color:var(--white);text-decoration:none}
.brand-footer a:hover{color:rgba(255,255,255,0.7)}
</style>
HTML,
],

[
'tag'   => 'atp_brand_scripts',
'label' => 'Brand Guide: Scripts',
'desc'  => 'The brand guide JavaScript — color swatches click-to-copy and copy skill buttons.',
'default' => <<<'HTML'
<script>
/* ═══ SKILL FILE CONTENTS (embedded for clipboard copy) ═══ */
const SKILL_BIO = `---
name: atp-brand-writing
description: Write content for America Tracking Polls (ATP) — a Florida-based political campaign marketing and polling firm. Use this skill when creating any written content, copy, emails, social posts, landing pages, blog posts, or marketing materials for ATP.
---

# ATP Brand Writing Guide

## Company Overview
America Tracking Polls (ATP) is a Florida-based (Ocoee, FL 34761) election research and full-service campaign marketing firm founded January 1, 2025. ATP provides voter engagement guidance, compliance resources, and data-driven campaign best practices for local, statewide, and federal races.

## The Victory Formula
**Intelligence + Engagement + Persistence + Conversion = Victory**

This is ATP's core positioning. Every piece of content should ladder back to this formula. ATP doesn't just poll — they use polling intelligence to power every marketing channel automatically.

## Key Differentiators (Always Emphasize)
- **Single platform** — No need for separate pollsters, web designers, ad agencies, mail houses. ATP is the campaign operating system.
- **Data-driven everything** — Every output (ads, websites, mail, SMS, AI presence) is powered by the same voter polling data.
- **7 Proven Strategies** — Data analytics, digital campaigns, social media, QR-coded print, SMS outreach, voter-driven websites, and AEO.
- **95% Voter Reach** — Coordinated, cost-effective multi-channel outreach.
- **AEO (Answer Engine Optimization)** — ATP structures campaign data so AI tools (ChatGPT, Gemini, Google AI Overview) correctly cite the candidate's platform.
- **VRM (Voter Relationship Management)** — CRM-like system specifically for tracking voter touchpoints.
- **Compliance-first** — Full TCPA & 10DLC compliant messaging. AI Ethics Pledge.

## Tone of Voice
- **Authoritative** — ATP speaks from data, not opinion. Use specific numbers and stats.
- **Direct** — Short sentences. No fluff. Lead with the benefit.
- **Urgent** — Elections have deadlines. Create urgency without being alarmist.
- **Candidate-focused** — Always address the candidate's goal: winning. Not abstract marketing speak.
- **Data-forward** — Prefer "82% of voters search on their phone in line" over "many voters use their phones."

## Words & Phrases to USE
- "Win your election"
- "Data-driven campaign marketing"
- "Voter intelligence"
- "Multi-channel distribution"
- "Answer Engine Optimization (AEO)"
- "Define yourself before the AI does"
- "The numbers never lie"
- "Polling-powered"
- "Campaign operating system"
- "From raw data to voter action"
- "Victory formula"
- "Real insights. Real distribution. Real results."

## Words & Phrases to AVOID
- "Revolutionary" / "cutting-edge" / "game-changing" (generic)
- "We think" / "We believe" (use data instead)
- "Social media marketing" alone (always pair with the full multi-channel suite)
- "AI-powered" as a buzzword (be specific: "AI answer engines correctly cite your platform")
- "Affordable" / "cheap" (position on value and results, not price)
- "One-stop shop" (use "campaign operating system" instead)`;

const SKILL_BRAND = `---
name: atp-brand-guide
description: Design and build visual assets for America Tracking Polls (ATP). Use this skill when creating websites, landing pages, graphics, presentations, or any visual content for ATP.
---

# ATP Visual Brand Guide

## Color Palette
- Navy Blue #3C3B6E — Primary brand color
- Red #B22234 — Primary CTA color, accent
- White #FFFFFF — Text on dark backgrounds
- Hero Dark #0e1235 — Hero sections
- Blue Dark #111030 — Page background, footer
- Blue Deep #1A1940 — Pipeline section
- Bright Red #D42E43 — Hover states
- Off-White #F5F6FA — Light section backgrounds

## Typography
- Display: Bebas Neue — headings, buttons, nav
- Body: Libre Baskerville — paragraphs, descriptions
- Mono: Space Mono — data labels, technical text`;

function copySkill(type) {
  const content = type === 'bio' ? SKILL_BIO : SKILL_BRAND;
  const btn = event.currentTarget;
  navigator.clipboard.writeText(content).then(() => {
    const originalText = btn.innerHTML;
    btn.classList.add('copied');
    btn.innerHTML = '<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg> COPIED!';
    setTimeout(() => {
      btn.classList.remove('copied');
      btn.innerHTML = originalText;
    }, 2000);
  });
}

const swatches = [
  { name: 'Navy Blue', hex: '#3C3B6E', role: 'Primary brand color. Headers, nav, trust elements.' },
  { name: 'Blue Mid', hex: '#2E2D5A', role: 'Alternate section backgrounds.' },
  { name: 'Blue Deep', hex: '#1A1940', role: 'Card backgrounds, overlays.' },
  { name: 'Blue Dark', hex: '#111030', role: 'Primary dark background. Body.' },
  { name: 'Hero Dark', hex: '#0e1235', role: 'Hero section background. Deepest dark.' },
  { name: 'Red', hex: '#B22234', role: 'Accent & CTA. Buttons, highlights, key data.' },
  { name: 'Bright Red', hex: '#D42E43', role: 'Hover states. Interactive feedback.' },
  { name: 'White', hex: '#FFFFFF', role: 'Text on dark backgrounds. Clean sections.' },
  { name: 'Off-White', hex: '#F5F6FA', role: 'Light section backgrounds. Card fills.' },
  { name: 'Gray', hex: '#8A8FA0', role: 'Secondary text. Captions.' },
  { name: 'Dark', hex: '#1A1A2E', role: 'Text on light backgrounds.' }
];

document.addEventListener('DOMContentLoaded', function() {
  const swatchGrid = document.getElementById('swatchGrid');
  if (!swatchGrid) return;
  swatches.forEach(s => {
    const needsBorder = s.hex === '#FFFFFF' || s.hex === '#F5F6FA';
    const div = document.createElement('div');
    div.className = 'swatch';
    div.onclick = () => {
      navigator.clipboard.writeText(s.hex).then(() => {
        const tooltip = div.querySelector('.swatch-tooltip');
        tooltip.classList.add('show');
        setTimeout(() => tooltip.classList.remove('show'), 1200);
      });
    };
    div.innerHTML = `
      <div class="swatch-color" style="background:${s.hex}${needsBorder ? ';border:1px solid #ddd' : ''}">
        <div class="swatch-tooltip">COPIED!</div>
      </div>
      <div class="swatch-info">
        <div class="swatch-name">${s.name}</div>
        <div class="swatch-hex">${s.hex}</div>
        <div class="swatch-role">${s.role}</div>
      </div>`;
    swatchGrid.appendChild(div);
  });
});
</script>
HTML,
],

]], // end Brand Guide Styles & Scripts


/* ══════════════════════════════════════════════════════════════
   BRAND GUIDE — SECTIONS
══════════════════════════════════════════════════════════════ */
[
'group' => 'Brand Guide — Sections',
'shortcodes' => [

[
'tag'   => 'atp_brand_nav',
'label' => 'Brand Guide: Nav',
'desc'  => 'The fixed navigation bar for the brand guide.',
'default' => <<<'HTML'
<nav class="brand-nav">
<div class="wrap">
  <a href="#top" class="nav-brand">ATP <span class="red">BRAND</span> GUIDE</a>
  <div class="nav-links">
    <a href="#bio">Company</a>
    <a href="#colors">Colors</a>
    <a href="#typography">Typography</a>
    <a href="#logo">Logo</a>
    <a href="#imagery">Photography</a>
    <a href="#animation">Animation</a>
    <a href="#tone">Tone</a>
    <a href="#cta">CTAs</a>
  </div>
</div>
</nav>
HTML,
],

[
'tag'   => 'atp_brand_hero',
'label' => 'Brand Guide: Hero',
'desc'  => 'The hero section with logo, title, subtitle, and copy-skill buttons.',
'default' => <<<'HTML'
<section class="brand-hero" id="top">
  <div class="hero-inner">
    <div class="wrap">
      <div class="hero-logo">
        <img src="{ATP_PLUGIN_URL}assets/images/ATP-Logo-Red-White.png" alt="ATP Logo" style="width:120px;height:auto">
      </div>
      <h1 class="brand-title">AMERICA <span class="red">TRACKING</span> POLLS</h1>
      <p class="brand-subtitle" style="margin-top:12px;font-size:20px;letter-spacing:5px;font-family:var(--display)">BRAND GUIDELINES</p>
      <p class="brand-subtitle" style="margin-top:16px">Intelligence + Engagement + Persistence + Conversion = Victory</p>
      <div class="hero-buttons" style="margin-top:36px">
        <button class="skill-btn" onclick="copySkill('bio')">
          <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2" fill="none" stroke="currentColor" stroke-width="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" fill="none" stroke="currentColor" stroke-width="2"/></svg>
          COPY BIO SKILL
        </button>
        <button class="skill-btn skill-btn-secondary" onclick="copySkill('brand')">
          <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2" fill="none" stroke="currentColor" stroke-width="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" fill="none" stroke="currentColor" stroke-width="2"/></svg>
          COPY BRAND GUIDE SKILL
        </button>
      </div>
    </div>
  </div>
</section>
HTML,
],

[
'tag'   => 'atp_brand_bio',
'label' => 'Brand Guide: Company Bio',
'desc'  => 'Company bio section with stats grid, services grid, and social links.',
'default' => <<<'HTML'
<section class="brand-section" id="bio">
<div class="wrap">
  <h2 class="section-title">COMPANY <span class="red">BIO</span></h2>
  <p class="section-desc">The official narrative of America Tracking Polls. Use this copy as the foundation for all marketing materials, press releases, and partner communications.</p>

  <div class="bio-text">
    <p><strong>America Tracking Polls (ATP)</strong> is a Florida-based election research and campaign services firm headquartered in Ocoee, FL 34761. Founded on January 1, 2025, ATP was built on a single conviction: campaigns that listen to voters win elections. While most political firms treat polling and marketing as separate disciplines, ATP fuses them into one continuous feedback loop that gives candidates an unfair advantage at the ballot box.</p>
    <p>ATP provides voter engagement guidance, compliance resources, and data-driven best practices for local, statewide, and federal races across the United States. We don't just collect data&mdash;we turn raw voter intelligence into actionable, multi-channel campaign strategies that reach voters where they are and move them to action.</p>
    <div class="bio-highlight">Intelligence + Engagement + Persistence + Conversion = Victory.</div>
    <p>This is our formula. We poll your voters to learn what they actually care about. Then we use those insights to power every piece of your campaign&mdash;from the way your website talks about issues, to how AI search engines describe you, to the direct mail piece that lands in a voter's mailbox with a QR code linking to a personalized survey.</p>
    <p><strong>Our services span the full campaign lifecycle.</strong> We start with <strong>Political Polling</strong>&mdash;rigorous, statistically valid research that reveals where you stand, what voters want, and where your opponent is vulnerable. From there, we deploy a suite of distribution tools: <strong>AEO (Answer Engine Optimization)</strong> ensures AI platforms and search engines accurately represent your positions. <strong>VRM (Voter Relationship Management)</strong> organizes your voter data into a system that tracks engagement, sentiment, and conversion across every touchpoint. <strong>Campaign Sites</strong> are built to convert&mdash;not just inform. <strong>SMS Outreach</strong> delivers your message directly into voters' hands, fully <strong>TCPA/10DLC compliant</strong>. <strong>Digital Ads</strong> target persuadable voters with data-backed messaging. And <strong>Direct Mail with QR-coded print</strong> bridges the physical and digital worlds, driving offline voters into your online ecosystem.</p>
    <p>ATP stands apart because we believe in <strong>7 Proven Strategies that win elections</strong>&mdash;a methodology refined across real campaigns, not academic theory. We deliver a <strong>95% Voter Reach</strong> rate by combining channels that reinforce each other rather than operating in silos.</p>
  </div>

  <div class="bio-stats">
    <div class="bio-stat"><div class="bio-stat-num">7</div><div class="bio-stat-label">Proven Strategies That Win</div></div>
    <div class="bio-stat"><div class="bio-stat-num">95%</div><div class="bio-stat-label">Voter Reach Rate</div></div>
    <div class="bio-stat"><div class="bio-stat-num">2025</div><div class="bio-stat-label">Year Founded</div></div>
    <div class="bio-stat"><div class="bio-stat-num">FL</div><div class="bio-stat-label">Ocoee, FL 34761</div></div>
  </div>

  <h3 class="sub-title" style="margin-top:48px">Services</h3>
  <div class="services-grid">
    <div class="service-tag">Political Polling</div>
    <div class="service-tag">AEO (Answer Engine Optimization)</div>
    <div class="service-tag">VRM (Voter Relationship Management)</div>
    <div class="service-tag">Campaign Sites</div>
    <div class="service-tag">SMS Outreach</div>
    <div class="service-tag">Digital Ads</div>
    <div class="service-tag">Direct Mail + QR Codes</div>
  </div>

  <h3 class="sub-title" style="margin-top:48px">Connect With Us</h3>
  <div class="social-grid">
    <div class="social-item"><strong>Twitter / X</strong><a href="https://twitter.com/AmericaTracking" target="_blank">@AmericaTracking</a></div>
    <div class="social-item"><strong>LinkedIn</strong><a href="https://linkedin.com/company/america-tracking-polls" target="_blank">linkedin.com/company/america-tracking-polls</a></div>
    <div class="social-item"><strong>YouTube</strong><a href="https://youtube.com/@AmericaTrackingPolls" target="_blank">@AmericaTrackingPolls</a></div>
    <div class="social-item"><strong>Website</strong><a href="https://americatrackingpolls.com" target="_blank">americatrackingpolls.com</a></div>
    <div class="social-item"><strong>Email</strong><a href="mailto:info@americatrackingpolls.com">info@americatrackingpolls.com</a></div>
    <div class="social-item"><strong>Phone</strong><a href="tel:+12028154637">+1-202-815-4637</a></div>
  </div>
</div>
</section>
HTML,
],

[
'tag'   => 'atp_brand_colors',
'label' => 'Brand Guide: Colors',
'desc'  => 'Color swatches section. JS populates swatches from a data array — include atp_brand_scripts on the same page.',
'default' => <<<'HTML'
<section class="brand-section section-light" id="colors">
<div class="wrap">
  <h2 class="section-title">BRAND <span class="red">COLORS</span></h2>
  <p class="section-desc">Our palette is rooted in American identity&mdash;navy, red, and white&mdash;with deep blue backgrounds that create a data-driven, authoritative atmosphere. Click any swatch to copy its hex code.</p>
  <h3 class="sub-title">Full Palette</h3>
  <div class="swatch-grid" id="swatchGrid">
    <!-- Swatches injected by JS from atp_brand_scripts -->
  </div>
</div>
</section>
HTML,
],

[
'tag'   => 'atp_brand_typography',
'label' => 'Brand Guide: Typography',
'desc'  => 'Typography section with three font specimens: Bebas Neue, Libre Baskerville, Space Mono.',
'default' => <<<'HTML'
<section class="brand-section" id="typography">
<div class="wrap">
  <h2 class="section-title"><span class="red">TYPOGRAPHY</span></h2>
  <p class="section-desc">Three typefaces create a clear hierarchy: bold display headings, refined body text, and technical data elements.</p>

  <div class="font-specimen">
    <div class="font-label">Display / Headings</div>
    <div class="font-sample-display" style="font-size:60px;margin-bottom:8px">BEBAS NEUE</div>
    <div class="font-sample-display" style="font-size:42px;margin-bottom:8px">ABCDEFGHIJKLMNOPQRSTUVWXYZ</div>
    <div class="font-sample-display" style="font-size:42px;margin-bottom:16px">0123456789</div>
    <div class="font-sample-display" style="font-size:28px;letter-spacing:3px">WIN YOUR ELECTION WITH DATA-DRIVEN CAMPAIGN MARKETING</div>
    <div class="font-weights"><span class="font-weight-item">Use for: Section titles, hero text, CTA labels, navigation, stat numbers</span></div>
    <div class="font-weights" style="margin-top:12px"><span class="font-weight-item">CSS: font-family: 'Bebas Neue', sans-serif</span></div>
  </div>

  <div class="font-specimen">
    <div class="font-label">Body Text</div>
    <div class="font-sample-body" style="font-size:24px;margin-bottom:8px;color:var(--white)">Libre Baskerville</div>
    <div class="font-sample-body" style="font-size:18px;margin-bottom:8px">ABCDEFGHIJKLMNOPQRSTUVWXYZ</div>
    <div class="font-sample-body" style="font-size:18px;margin-bottom:16px">abcdefghijklmnopqrstuvwxyz &middot; 0123456789</div>
    <div class="font-sample-body" style="font-size:15px">We poll your voters, learn what they care about, and distribute your message across every channel&mdash;digital ads, SMS, direct mail, voter websites, and AI-optimized content. Real insights. Real distribution. Real results.</div>
    <div class="font-weights">
      <span class="font-weight-item" style="font-family:var(--body);font-weight:400;color:rgba(255,255,255,0.7)">Regular 400</span>
      <span class="font-weight-item" style="font-family:var(--body);font-weight:700;color:rgba(255,255,255,0.7)">Bold 700</span>
      <span class="font-weight-item" style="font-family:var(--body);font-style:italic;color:rgba(255,255,255,0.7)">Italic 400</span>
    </div>
    <div class="font-weights" style="margin-top:12px"><span class="font-weight-item">CSS: font-family: 'Libre Baskerville', Georgia, serif</span></div>
  </div>

  <div class="font-specimen">
    <div class="font-label">Data / Technical</div>
    <div class="font-sample-mono" style="font-size:24px;margin-bottom:8px;color:var(--white)">Space Mono</div>
    <div class="font-sample-mono" style="font-size:16px;margin-bottom:8px">ABCDEFGHIJKLMNOPQRSTUVWXYZ</div>
    <div class="font-sample-mono" style="font-size:16px;margin-bottom:16px">abcdefghijklmnopqrstuvwxyz &middot; 0123456789 +-%$#@</div>
    <div class="font-sample-mono" style="font-size:13px">TCPA/10DLC COMPLIANT &bull; 95% VOTER REACH &bull; 7 PROVEN STRATEGIES</div>
    <div class="font-weights">
      <span class="font-weight-item" style="font-family:var(--mono);font-weight:400;color:rgba(255,255,255,0.7)">Regular 400</span>
      <span class="font-weight-item" style="font-family:var(--mono);font-weight:700;color:rgba(255,255,255,0.7)">Bold 700</span>
    </div>
    <div class="font-weights" style="margin-top:12px"><span class="font-weight-item">CSS: font-family: 'Space Mono', monospace</span></div>
  </div>
</div>
</section>
HTML,
],

[
'tag'   => 'atp_brand_logos',
'label' => 'Brand Guide: Logo Assets',
'desc'  => 'Logo assets section with three logo variants and download buttons.',
'default' => <<<'HTML'
<section class="brand-section section-alt" id="logo">
<div class="wrap">
  <h2 class="section-title"><span class="red">LOGO</span> ASSETS</h2>
  <p class="section-desc">Three official logo variations for different contexts. Click to download each file.</p>

  <div class="logo-grid">
    <div class="logo-asset">
      <div class="logo-asset-preview" style="background:rgba(200,210,230,0.12)">
        <img src="{ATP_PLUGIN_URL}assets/images/ATP-Logo-Standard.png" alt="ATP Standard Logo" style="width:160px;height:auto">
      </div>
      <p class="logo-asset-label">Standard &mdash; Red + Navy</p>
      <a href="{ATP_PLUGIN_URL}assets/images/ATP-Logo-Standard.png" download class="logo-download-btn">Download PNG</a>
    </div>
    <div class="logo-asset">
      <div class="logo-asset-preview" style="background:rgba(200,210,230,0.12)">
        <img src="{ATP_PLUGIN_URL}assets/images/ATP-Logo-Red-White.png" alt="ATP Red & White Logo" style="width:160px;height:auto">
      </div>
      <p class="logo-asset-label">Red &amp; White &mdash; Simplified</p>
      <a href="{ATP_PLUGIN_URL}assets/images/ATP-Logo-Red-White.png" download class="logo-download-btn">Download PNG</a>
    </div>
    <div class="logo-asset">
      <div class="logo-asset-preview" style="background:rgba(200,210,230,0.12)">
        <img src="{ATP_PLUGIN_URL}assets/images/ATP-Logo-Blue-White.png" alt="ATP Blue & White Logo" style="width:160px;height:auto">
      </div>
      <p class="logo-asset-label">Blue &amp; White &mdash; Alternate</p>
      <a href="{ATP_PLUGIN_URL}assets/images/ATP-Logo-Blue-White.png" download class="logo-download-btn">Download PNG</a>
    </div>
  </div>
</div>
</section>
HTML,
],

[
'tag'   => 'atp_brand_imagery',
'label' => 'Brand Guide: Photography & Imagery',
'desc'  => 'Photography guidelines section with treatment examples and Do/Do Not rules.',
'default' => <<<'HTML'
<section class="brand-section section-light" id="imagery">
<div class="wrap">
  <h2 class="section-title"><span class="red">PHOTOGRAPHY</span> &amp; IMAGERY</h2>
  <p class="section-desc">All photography in the ATP brand follows a strict treatment: black &amp; white base with a blue gradient overlay sweeping from one side. Never use full-color photography.</p>

  <h3 class="sub-title" style="margin-bottom:24px">Treatment Examples</h3>
  <div class="photo-grid">
    <div class="photo-card treated">
      <img src="https://images.unsplash.com/photo-1540910419892-4a36d2c3266c?w=600&h=400&fit=crop" alt="Voters at polling station" loading="lazy">
      <span class="photo-label">B&amp;W + Gradient Overlay</span>
    </div>
    <div class="photo-card treated">
      <img src="https://images.unsplash.com/photo-1494172961521-33799ddd43a5?w=600&h=400&fit=crop" alt="American flag at political event" loading="lazy">
      <span class="photo-label">B&amp;W + Gradient Overlay</span>
    </div>
    <div class="photo-card treated">
      <img src="https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?w=600&h=400&fit=crop" alt="Campaign rally crowd" loading="lazy">
      <span class="photo-label">B&amp;W + Gradient Overlay</span>
    </div>
  </div>

  <div class="do-dont-grid" style="margin-top:48px">
    <div class="do-col" style="background:rgba(58,95,217,0.05);border-color:rgba(58,95,217,0.15)">
      <h4 style="color:#3a5fd9">Do</h4>
      <ul>
        <li style="color:#5A5F6E">Convert all photos to grayscale using CSS <code style="font-size:11px;background:rgba(58,95,217,0.1);color:#3a5fd9;padding:2px 6px;border-radius:3px">filter: grayscale(100%)</code></li>
        <li style="color:#5A5F6E">Apply a gradient overlay: red to blue at 30% opacity using <code style="font-size:11px;background:rgba(58,95,217,0.1);color:#3a5fd9;padding:2px 6px;border-radius:3px">::after</code> pseudo-element</li>
        <li style="color:#5A5F6E">Feature images of voters, polling locations, campaigns, American landmarks</li>
        <li style="color:#5A5F6E">Maintain high contrast in the base B&amp;W image</li>
        <li style="color:#5A5F6E">Use images at 600px minimum width for cards, 1200px+ for heroes</li>
      </ul>
    </div>
    <div class="dont-col" style="background:rgba(212,43,43,0.05);border-color:rgba(212,43,43,0.15)">
      <h4 style="color:#d42b2b">Do Not</h4>
      <ul>
        <li style="color:#5A5F6E">Use full-color photography anywhere in the brand</li>
        <li style="color:#5A5F6E">Use stock photos with watermarks or generic corporate imagery</li>
        <li style="color:#5A5F6E">Apply other color treatments (sepia, duotone with off-brand colors)</li>
        <li style="color:#5A5F6E">Use low-resolution or pixelated source images</li>
        <li style="color:#5A5F6E">Feature partisan symbols (donkey/elephant) in branded materials</li>
      </ul>
    </div>
  </div>
</div>
</section>
HTML,
],

[
'tag'   => 'atp_brand_animation',
'label' => 'Brand Guide: Animation Guidelines',
'desc'  => 'Animation guidelines section covering canvas animation, scroll reveals, and transition philosophy.',
'default' => <<<'HTML'
<section class="brand-section" id="animation">
<div class="wrap">
  <h2 class="section-title"><span class="red">ANIMATION</span> GUIDELINES</h2>
  <p class="section-desc">Motion in the ATP brand is subtle, data-driven, and purposeful. It reinforces the polling intelligence theme without distracting from content.</p>

  <div class="guideline-grid">
    <div class="guideline-card">
      <h4>Hero Canvas Animation</h4>
      <p>Use canvas-based data visualizations showing competing red (<code>#d42b2b</code>) and blue (<code>#3a5fd9</code>) polling lines oscillating around a 50% midpoint. Gradient fills underneath each line at 15% opacity. Pulsing live dots at the right edge.</p>
    </div>
    <div class="guideline-card">
      <h4>Scroll Reveals (GSAP)</h4>
      <p>Use GSAP + ScrollTrigger for all scroll-based animations. Default: <code>fade up</code> with <code>y: 30, opacity: 0</code> to <code>y: 0, opacity: 1</code>. Stagger siblings at 0.15s. Easing: <code>power2.out</code>. Trigger once at top 70% viewport.</p>
    </div>
    <div class="guideline-card">
      <h4>Transition Philosophy</h4>
      <p>Motion should feel like data appearing&mdash;measured and confident. Prefer scroll-reveal (fade up, stagger) over flashy transitions. No spinning, flipping, bouncing, or elastic effects. Data-driven, not playful.</p>
    </div>
  </div>
</div>
</section>
HTML,
],

[
'tag'   => 'atp_brand_tone',
'label' => 'Brand Guide: Tone of Voice',
'desc'  => 'Tone of voice section with guidelines cards and Do/Do Not copy examples.',
'default' => <<<'HTML'
<section class="brand-section section-offwhite" id="tone">
<div class="wrap">
  <h2 class="section-title">TONE OF <span class="red">VOICE</span></h2>
  <p class="section-desc">ATP's voice is data-driven, authoritative, and direct. Every piece of copy should reinforce one thing: we help candidates WIN through polling intelligence and multi-channel distribution.</p>

  <div class="guideline-grid">
    <div class="guideline-card" style="background:rgba(0,0,0,0.02);border-color:rgba(0,0,0,0.06)">
      <h4 style="color:var(--blue-dark)">Data-Driven</h4>
      <p style="color:#5A5F6E">Lead with numbers, stats, and evidence. Never make vague claims. Instead of "We help campaigns," say "We deliver 95% voter reach across 7 proven channels."</p>
    </div>
    <div class="guideline-card" style="background:rgba(0,0,0,0.02);border-color:rgba(0,0,0,0.06)">
      <h4 style="color:var(--blue-dark)">Authoritative</h4>
      <p style="color:#5A5F6E">Speak with confidence. Use declarative sentences. "Our formula works." Not "We think our approach might help." ATP knows what wins elections.</p>
    </div>
    <div class="guideline-card" style="background:rgba(0,0,0,0.02);border-color:rgba(0,0,0,0.06)">
      <h4 style="color:var(--blue-dark)">Direct</h4>
      <p style="color:#5A5F6E">Short paragraphs. Active voice. No filler words. Every sentence should either inform or persuade. If it does neither, cut it.</p>
    </div>
  </div>

  <div class="do-dont-grid" style="margin-top:40px">
    <div class="do-col" style="background:rgba(58,95,217,0.05);border-color:rgba(58,95,217,0.15)">
      <h4 style="color:#3a5fd9">Write Like This</h4>
      <ul>
        <li style="color:#5A5F6E">"We poll your voters, learn what moves them, and put that intelligence to work across every channel."</li>
        <li style="color:#5A5F6E">"7 proven strategies. 95% voter reach. One goal: victory."</li>
        <li style="color:#5A5F6E">"Real insights. Real distribution. Real results."</li>
        <li style="color:#5A5F6E">"Data doesn't lie. Neither do we."</li>
      </ul>
    </div>
    <div class="dont-col" style="background:rgba(212,43,43,0.05);border-color:rgba(212,43,43,0.15)">
      <h4 style="color:#d42b2b">Not Like This</h4>
      <ul>
        <li style="color:#5A5F6E">"We're a full-service agency that helps campaigns achieve their goals."</li>
        <li style="color:#5A5F6E">"Our innovative solutions leverage synergies to drive engagement."</li>
        <li style="color:#5A5F6E">"We're passionate about making a difference in politics."</li>
        <li style="color:#5A5F6E">"Contact us to learn more about our exciting offerings!"</li>
      </ul>
    </div>
  </div>
</div>
</section>
HTML,
],

[
'tag'   => 'atp_brand_cta',
'label' => 'Brand Guide: CTA Guidelines',
'desc'  => 'CTA guidelines section with live button demos and specs for primary, secondary, and text CTAs.',
'default' => <<<'HTML'
<section class="brand-section section-alt" id="cta">
<div class="wrap">
  <h2 class="section-title">CALL-TO-<span class="red">ACTION</span></h2>
  <p class="section-desc">Every CTA should drive toward a consultation or strategy call. Primary CTAs use solid red buttons. Secondary CTAs use outline style. Never use generic labels like "Learn More."</p>

  <div class="cta-demos">
    <div class="cta-row">
      <span class="cta-row-label">Primary</span>
      <button class="btn-primary-demo">SCHEDULE A STRATEGY CALL <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg></button>
    </div>
    <div class="cta-row">
      <span class="cta-row-label">Primary Alt</span>
      <button class="btn-primary-demo">GET YOUR FREE STRATEGY SESSION</button>
    </div>
    <div class="cta-row">
      <span class="cta-row-label">Secondary</span>
      <button class="btn-secondary-demo"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M8 5v14l11-7z"/></svg> WATCH THE VIDEO</button>
    </div>
    <div class="cta-row">
      <span class="cta-row-label">Text Link</span>
      <button class="btn-text-demo">SEE OUR METHODOLOGY <svg viewBox="0 0 24 24" width="12" height="12" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg></button>
    </div>
  </div>

  <div class="guideline-grid" style="margin-top:48px">
    <div class="guideline-card" style="background:rgba(255,255,255,0.03);border-color:rgba(255,255,255,0.06)">
      <h4>Primary Button Specs</h4>
      <p>Background: <code>#B22234</code>. Hover: <code>#D42E43</code>. White text, Libre Baskerville 12px bold. Padding: 14px 32px. Border-radius: 4px. Hover lifts 2px with <code>box-shadow</code>.</p>
    </div>
    <div class="guideline-card" style="background:rgba(255,255,255,0.03);border-color:rgba(255,255,255,0.06)">
      <h4>Secondary Button Specs</h4>
      <p>Transparent background. Border: <code>1px solid rgba(255,255,255,0.2)</code>. White text. Hover fills to <code>rgba(255,255,255,0.1)</code> and lifts 2px.</p>
    </div>
    <div class="guideline-card" style="background:rgba(255,255,255,0.03);border-color:rgba(255,255,255,0.06)">
      <h4>CTA Copy Rules</h4>
      <p>Always action-oriented and specific. Uppercase. Lead to consultation. Good: "SCHEDULE A STRATEGY CALL". Bad: "LEARN MORE", "CLICK HERE", "SUBMIT".</p>
    </div>
  </div>
</div>
</section>
HTML,
],

[
'tag'   => 'atp_brand_footer',
'label' => 'Brand Guide: Footer',
'desc'  => 'The brand guide dark footer with contact info and social links.',
'default' => <<<'HTML'
<footer class="brand-footer">
<div class="wrap">
  <p style="font-family:var(--display);font-size:20px;letter-spacing:3px;color:var(--white);margin-bottom:12px">AMERICA <span style="color:var(--red)">TRACKING</span> POLLS</p>
  <p>Ocoee, FL 34761 &bull; <a href="mailto:info@americatrackingpolls.com">info@americatrackingpolls.com</a> &bull; <a href="tel:+12028154637">+1-202-815-4637</a></p>
  <p style="margin-top:8px"><a href="https://twitter.com/AmericaTracking">Twitter</a> &bull; <a href="https://linkedin.com/company/america-tracking-polls">LinkedIn</a> &bull; <a href="https://youtube.com/@AmericaTrackingPolls">YouTube</a> &bull; <a href="https://americatrackingpolls.com">Website</a></p>
  <p style="margin-top:20px;font-family:var(--mono);font-size:10px;letter-spacing:1px">&copy; 2025 AMERICA TRACKING POLLS LLC. BUILT BY THE NUMBERS.</p>
</div>
</footer>
HTML,
],

]], // end Brand Guide Sections


    ]; // end registry return
} // end function

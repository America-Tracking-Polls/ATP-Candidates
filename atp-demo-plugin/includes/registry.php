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
      <p class="hero-sub">What matters most to your voters? What earns their vote&mdash;and what moves the undecided?</p>
      <p class="hero-sub" style="opacity:1;transform:none">Our MMS surveys deliver real voter insight in real time&mdash;so you know exactly what to say, who to target, and when it matters most.</p>
      <p class="hero-sub" style="opacity:1;transform:none">Then we execute&mdash;persistently delivering the right message to the right voters through MMS, digital and social ads, Voter-Driven 10DLC-compliant websites, AI-optimized content, and QR-powered print.</p>
      <p class="hero-sub" style="opacity:1;transform:none"><strong>Real Insights. Targeted Delivery. Winning Results.</strong></p>
      <div class="hero-btns">
        <a href="#intake" class="btn-p">SCHEDULE A STRATEGY CALL <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg></a>
        <a href="#" class="btn-video"><svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg> WATCH THE VIDEO</a>
      </div>
    </div>
    <div class="hf" style="padding:0;border:none;background:transparent;backdrop-filter:none;box-shadow:none;opacity:1;transform:none">
      <iframe src="https://atp.ameritrackpolls.com/to/nhPPYcJ8" style="width:100%;min-height:500px;border:0;border-radius:14px" loading="lazy"></iframe>
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
    <p>Our formula is simple: <strong>Intelligence + Engagement + Persistence + Conversion = Victory</strong></p>
    <p>We don&rsquo;t just poll voters&mdash;we turn insight into action. Every data point powers your campaign&mdash;from your website to how AI defines and presents you in search&mdash;giving you a decisive edge where it matters most.</p>
    <p>And because every voter interaction is intentional, every engagement becomes an opportunity to build support&mdash;and drive fundraising.</p>
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
      <h3>YOUR BENCHMARK SURVEY</h3>
      <p>We deliver MMS surveys to registered voters across your district&mdash;linking to a fast, mobile-optimized survey. You track Name ID and favorability in real time.</p>
      <h4>WHAT YOUR OPPONENT DOESN&rsquo;T KNOW</h4>
      <ul>
        <li>Which issues actually move votes</li>
        <li>Your true Name ID&mdash;and how fast it&rsquo;s growing</li>
        <li>Who needs to be persuaded</li>
        <li>Where support is strong&mdash;and where you&rsquo;re losing</li>
        <li>Real sentiment&mdash;not consultant assumptions</li>
      </ul>
      <p><strong>They guess. You know. They spend. You convert. They hope. You win.</strong></p>
      <p style="margin-top:16px;font-family:var(--mono);font-size:11px;color:#999;letter-spacing:1px">TCPA &amp; 10DLC COMPLIANT</p>
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


/* ══════════════════════════════════════════════════════════════
   CANDIDATE PAGE TEMPLATE
   Full showcase campaign landing page with example content.
   Shortcode tags: atp_cand_*
   Uses John Stacy example data. When generating for a real
   candidate, AI replaces all content via the Page JSON workflow.
══════════════════════════════════════════════════════════════ */
[
'group' => 'Candidate Page',
'shortcodes' => [

[
'tag'   => 'atp_cand_styles',
'label' => 'Candidate Page — Styles',
'desc'  => 'Complete CSS for the candidate landing page including all sections: nav, hero, stats, about, messages, issues, endorsements, video, volunteer, donate, social, footer.',
'default' => <<<'HTML'
<link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ── Reset & Variables ── */
html{scroll-behavior:smooth}
.cand-page,.cand-page *,.cand-page *::before,.cand-page *::after{box-sizing:border-box;margin:0;padding:0}
.cand-page{
  --navy:#0B1C33;--navy-light:#142640;--red:#E60000;--red-dark:#b30000;
  --cream:#F9F9F7;--white:#FFFFFF;--gold:#C4A84F;
  --text:#111;--text-light:#555;--text-muted:#888;--border:#e0e0e0;
  --font-head:'Merriweather',serif;--font-body:'Inter',sans-serif;
  --container:1100px;--nav-h:72px;
  font-family:var(--font-body);color:var(--text);background:var(--cream);
  -webkit-font-smoothing:antialiased;line-height:1.6;
}
.cand-page a{color:inherit;text-decoration:none}
.cand-container{max-width:var(--container);margin:0 auto;padding:0 24px}

/* ── Scroll Progress ── */
.cand-progress{position:fixed;top:0;left:0;height:3px;background:var(--red);z-index:9999;transition:width .15s}

/* ── Nav ── */
.cand-nav{position:sticky;top:0;z-index:1000;background:var(--navy);border-bottom:4px solid var(--red)}
.cand-nav-inner{display:flex;align-items:center;justify-content:space-between;height:var(--nav-h);gap:20px}
.cand-nav-brand{display:flex;align-items:center;gap:12px}
.cand-nav-name{font-family:var(--font-head);font-size:18px;font-weight:700;color:#fff!important;letter-spacing:-.01em}
.cand-nav-badge{font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--red);background:rgba(230,0,0,.15);padding:4px 10px;border-radius:2px}
.cand-nav-links{display:flex;align-items:center;gap:6px}
.cand-nav-link{font-size:13px;font-weight:500;color:rgba(255,255,255,.7)!important;padding:8px 14px;border-radius:2px;transition:all .2s;letter-spacing:.01em}
.cand-nav-link:hover{color:#fff!important;background:rgba(255,255,255,.08)}
.cand-nav-cta{font-size:12px;font-weight:700;color:#fff!important;background:var(--red)!important;padding:10px 24px;border-radius:2px;letter-spacing:.06em;text-transform:uppercase;transition:background .2s;margin-left:8px}
.cand-nav-cta:hover{background:var(--red-dark)!important}
.cand-nav-toggle{display:none;background:none;border:none;cursor:pointer;padding:6px}
.cand-nav-toggle svg{width:26px;height:26px;stroke:#fff;stroke-width:2;fill:none}
.cand-nav-mobile{display:none;position:absolute;top:var(--nav-h);left:0;right:0;background:var(--navy);border-top:1px solid rgba(255,255,255,.1);padding:16px 24px;flex-direction:column;gap:4px}
.cand-nav-mobile.open{display:flex}
.cand-nav-mobile a{color:rgba(255,255,255,.8)!important;padding:12px 0;font-size:15px;border-bottom:1px solid rgba(255,255,255,.06)}

/* ── Hero ── */
.cand-hero{background:var(--navy);padding:80px 0 72px;position:relative;overflow:hidden}
.cand-hero-bg{position:absolute;inset:0;background:url('https://placehold.co/1920x1080/0B1C33/0B1C33?text=+') center/cover no-repeat;opacity:.25;animation:cand-ken-burns 25s ease-in-out infinite alternate}
@keyframes cand-ken-burns{0%{transform:scale(1) translate(0,0)}100%{transform:scale(1.12) translate(-2%,-1%)}}
.cand-hero::after{content:'';position:absolute;bottom:0;left:0;right:0;height:6px;background:linear-gradient(90deg,var(--red) 33%,var(--white) 33% 66%,#3C3B6E 66%);z-index:2}
.cand-hero-grid{display:grid;grid-template-columns:1fr 360px;gap:56px;align-items:center;position:relative;z-index:1}
.cand-hero-label{font-size:12px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--red);margin-bottom:14px}
.cand-hero-title{font-family:var(--font-head);font-size:56px;font-weight:900;line-height:1.08;color:#fff;margin-bottom:20px}
.cand-hero-intro{font-size:17px;line-height:1.75;color:rgba(255,255,255,.78);margin-bottom:32px;max-width:540px}
.cand-hero-ctas{display:flex;gap:14px;flex-wrap:wrap}
.cand-hero-cta{display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:700;color:#fff!important;background:var(--red);padding:14px 28px;border-radius:2px;letter-spacing:.05em;text-transform:uppercase;transition:all .2s;border:none;cursor:pointer}
.cand-hero-cta:hover{background:var(--red-dark);transform:translateY(-1px)}
.cand-hero-cta-alt{display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:#fff!important;background:transparent;padding:14px 28px;border:2px solid rgba(255,255,255,.3);border-radius:2px;letter-spacing:.03em;transition:all .2s;cursor:pointer}
.cand-hero-cta-alt:hover{border-color:#fff;background:rgba(255,255,255,.06)}
.cand-hero-photo{width:100%;border-radius:4px;border:4px solid rgba(255,255,255,.1);aspect-ratio:3/4;object-fit:cover;display:block;box-shadow:0 20px 60px rgba(0,0,0,.3)}

/* ── Stats Bar ── */
.cand-stats{background:var(--navy-light);padding:0;border-bottom:1px solid rgba(255,255,255,.06)}
.cand-stats-grid{display:grid;grid-template-columns:repeat(4,1fr);text-align:center}
.cand-stat{padding:32px 16px;border-right:1px solid rgba(255,255,255,.06)}
.cand-stat:last-child{border-right:none}
.cand-stat-number{font-family:var(--font-head);font-size:36px;font-weight:900;color:#fff;line-height:1;margin-bottom:6px;opacity:0;transform:translateY(10px);transition:opacity .6s,transform .6s}
.cand-stat-number.visible{opacity:1;transform:translateY(0)}
.cand-stat-label{font-size:12px;font-weight:500;color:rgba(255,255,255,.55);letter-spacing:.04em;text-transform:uppercase}

/* ── Section foundations ── */
.cand-section{padding:80px 0}
.cand-section-light{background:var(--white)}
.cand-section-cream{background:var(--cream)}
.cand-section-dark{background:var(--navy);color:#fff}
.cand-section-label{font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--red);margin-bottom:10px}
.cand-section-title{font-family:var(--font-head);font-size:34px;font-weight:700;line-height:1.2;color:var(--navy);margin-bottom:14px}
.cand-section-dark .cand-section-title{color:#fff}
.cand-section-subtitle{font-size:16px;color:var(--text-light);max-width:620px;margin-bottom:40px;line-height:1.75}
.cand-section-dark .cand-section-subtitle{color:rgba(255,255,255,.65)}

/* ── About ── */
.cand-about-grid{display:grid;grid-template-columns:1.2fr .8fr;gap:48px;align-items:start}
.cand-about-text{font-size:16px;line-height:1.85;color:var(--text)}
.cand-about-text p+p{margin-top:18px}
.cand-credentials{display:flex;flex-direction:column;gap:10px}
.cand-credential{background:var(--cream);border-left:3px solid var(--red);padding:16px 20px;border-radius:0 4px 4px 0}
.cand-section-light .cand-credential{background:rgba(11,28,51,.03)}
.cand-credential-label{font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--red);margin-bottom:3px}
.cand-credential-value{font-size:14px;font-weight:500;color:var(--navy);line-height:1.5}

/* ── Key Messages ── */
.cand-messages-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
.cand-message{background:var(--white);border:1px solid var(--border);border-radius:4px;padding:32px 28px;position:relative}
.cand-message-num{font-family:var(--font-head);font-size:48px;font-weight:900;color:rgba(230,0,0,.08);position:absolute;top:16px;right:20px;line-height:1}
.cand-message-title{font-family:var(--font-head);font-size:18px;font-weight:700;color:var(--navy);margin-bottom:10px;line-height:1.3}
.cand-message-text{font-size:14px;line-height:1.7;color:var(--text-light)}

/* ── Issues ── */
.cand-issues-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;max-width:1000px;margin:0 auto}
.cand-issue-card{background:var(--white);border:1px solid var(--border);border-top:3px solid var(--navy);border-radius:0 0 4px 4px;padding:28px 24px;transition:box-shadow .2s,transform .2s;text-align:left}
.cand-issue-card:hover{box-shadow:0 8px 24px rgba(0,0,0,.06);transform:translateY(-2px)}
.cand-issue-tag{font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--red);margin-bottom:10px}
.cand-issue-name{font-family:var(--font-head);font-size:20px;font-weight:700;color:var(--navy);margin-bottom:10px;line-height:1.3}
.cand-issue-desc{font-size:14px;line-height:1.7;color:var(--text-light)}

/* ── Endorsements ── */
.cand-endorsements-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px}
.cand-endorsement{background:var(--white);border:1px solid var(--border);border-radius:4px;padding:28px 24px;position:relative;transition:box-shadow .2s}
.cand-endorsement:hover{box-shadow:0 6px 20px rgba(0,0,0,.05)}
.cand-endorsement::before{content:'\201C';font-family:var(--font-head);font-size:56px;color:var(--red);opacity:.15;position:absolute;top:6px;left:16px;line-height:1}
.cand-endorsement-quote{font-size:15px;font-style:italic;line-height:1.7;color:var(--text);margin-bottom:14px;padding-left:20px}
.cand-endorsement-name{font-size:13px;font-weight:700;color:var(--navy);padding-left:20px}
.cand-endorsement-role{font-size:12px;color:var(--text-muted);padding-left:20px;margin-top:2px}

/* ── Video ── */
.cand-video-wrap{position:relative;width:100%;max-width:800px;margin:0 auto;border-radius:6px;overflow:hidden;box-shadow:0 16px 48px rgba(0,0,0,.3)}
.cand-video-wrap video{width:100%;display:block;border-radius:6px}
.cand-video-play{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(11,28,51,.4);transition:opacity .4s,background .2s;cursor:pointer;border-radius:6px}
.cand-video-play:hover{background:rgba(11,28,51,.2)}
.cand-video-play svg{width:72px;height:72px;filter:drop-shadow(0 4px 12px rgba(0,0,0,.3))}
.cand-video-caption{text-align:center;margin-top:16px;font-size:13px;color:rgba(255,255,255,.5);font-style:italic}

/* ── Volunteer / Get Involved ── */
.cand-section-stripes{background:var(--navy);position:relative;overflow:hidden}
.cand-section-stripes::before{content:'';position:absolute;inset:0;background:repeating-linear-gradient(-45deg,transparent,transparent 28px,rgba(255,255,255,.03) 28px,rgba(255,255,255,.03) 56px)}
.cand-section-stripes .cand-section-label{color:rgba(255,255,255,.4)}
.cand-section-stripes .cand-section-title{color:#fff}
.cand-section-stripes .cand-section-subtitle{color:rgba(255,255,255,.6)}
.cand-involve-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;position:relative;z-index:1}
.cand-involve-card{background:rgba(255,255,255,.07);backdrop-filter:blur(4px);border:1px solid rgba(255,255,255,.1);border-radius:4px;padding:32px 24px;text-align:center;transition:box-shadow .2s,transform .2s}
.cand-involve-card:hover{box-shadow:0 8px 24px rgba(0,0,0,.2);transform:translateY(-3px);background:rgba(255,255,255,.1)}
.cand-involve-icon{width:56px;height:56px;background:rgba(230,0,0,.2);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px}
.cand-involve-icon svg{width:24px;height:24px;stroke:var(--red);stroke-width:2;fill:none}
.cand-involve-title{font-family:var(--font-head);font-size:18px;font-weight:700;color:#fff;margin-bottom:8px}
.cand-involve-text{font-size:13px;line-height:1.6;color:rgba(255,255,255,.65);margin-bottom:16px}
.cand-involve-btn{font-size:12px;font-weight:700;color:#fff!important;border:2px solid rgba(255,255,255,.3);padding:8px 20px;border-radius:2px;letter-spacing:.04em;text-transform:uppercase;transition:all .2s;display:inline-block}
.cand-involve-btn:hover{background:#fff;color:var(--navy)!important;border-color:#fff}

/* ── Survey / Typeform ── */
.cand-survey{text-align:center}
.cand-survey-embed{max-width:720px;margin:0 auto;border-radius:8px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,.08);border:1px solid var(--border)}
.cand-survey-embed iframe{width:100%;height:500px;border:none;display:block}
.cand-survey-placeholder{background:var(--white);padding:48px 32px;text-align:center}
.cand-survey-placeholder-title{font-family:var(--font-head);font-size:22px;font-weight:700;color:var(--navy);margin-bottom:8px}
.cand-survey-placeholder-text{font-size:14px;color:var(--text-light);margin-bottom:20px}
.cand-survey-placeholder-btn{display:inline-block;font-size:13px;font-weight:700;color:#fff!important;background:var(--red);padding:12px 28px;border-radius:2px;letter-spacing:.04em;text-transform:uppercase;transition:background .2s}
.cand-survey-placeholder-btn:hover{background:var(--red-dark)}

/* ── Donate ── */
.cand-donate{text-align:center;padding:88px 0}
.cand-donate-title{font-family:var(--font-head);font-size:38px;font-weight:900;margin-bottom:14px;color:#fff}
.cand-donate-sub{font-size:17px;color:rgba(255,255,255,.7);margin-bottom:36px;max-width:520px;margin-left:auto;margin-right:auto;line-height:1.7}
.cand-donate-btn{display:inline-flex;align-items:center;gap:8px;font-size:16px;font-weight:700;color:var(--navy)!important;background:#fff;padding:18px 48px;border-radius:2px;letter-spacing:.05em;text-transform:uppercase;transition:all .2s;border:none;cursor:pointer;box-shadow:0 4px 16px rgba(0,0,0,.15)}
.cand-donate-btn:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(0,0,0,.25)}

/* ── Donate Page ── */
.cand-donate-page{background:var(--white);padding:80px 0 60px}
.cand-donate-page-inner{max-width:760px;margin:0 auto;text-align:center}
.cand-donate-page-intro{font-size:16px;line-height:1.75;color:var(--text-light);max-width:580px;margin:0 auto 32px}
.cand-donate-embed{max-width:640px;margin:0 auto;border-radius:8px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,.08);border:1px solid var(--border)}
.cand-donate-embed iframe{width:100%;height:600px;border:none;display:block}
.cand-donate-alt{margin-top:32px;padding:24px;background:var(--cream);border-radius:6px;max-width:640px;margin-left:auto;margin-right:auto}
.cand-donate-alt-title{font-family:var(--font-head);font-size:16px;font-weight:700;color:var(--navy);margin-bottom:6px}
.cand-donate-alt-text{font-size:13px;color:var(--text-light);line-height:1.6}
.cand-donate-alt a{color:var(--red);font-weight:600;text-decoration:underline}

/* ── Contact Page ── */
.cand-contact-grid{display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:start}
.cand-contact-info{display:flex;flex-direction:column;gap:16px}
.cand-contact-card{display:flex;gap:16px;align-items:flex-start;padding:20px;background:var(--cream);border-radius:4px;border-left:3px solid var(--red)}
.cand-contact-card-icon{width:40px;height:40px;background:rgba(230,0,0,.08);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.cand-contact-card-icon svg{width:18px;height:18px;stroke:var(--red);stroke-width:2;fill:none}
.cand-contact-card-label{font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--red);margin-bottom:2px}
.cand-contact-card-value{font-size:15px;color:var(--navy);line-height:1.5}
.cand-contact-card-value a{color:var(--navy);text-decoration:underline}
.cand-contact-card-value a:hover{color:var(--red)}
.cand-contact-embed{border-radius:8px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,.06);border:1px solid var(--border)}
.cand-contact-embed iframe{width:100%;height:580px;border:none;display:block}
@media(max-width:768px){.cand-contact-grid{grid-template-columns:1fr}}

/* ── Legal / Policy Pages ── */
.cand-legal{background:var(--white);padding:80px 0 60px}
.cand-legal-container{max-width:760px;margin:0 auto;padding:0 24px}
.cand-legal-meta{font-size:12px;color:var(--text-muted);margin-bottom:24px;line-height:1.6}
.cand-legal h1{font-family:var(--font-head);font-size:32px;font-weight:700;color:var(--navy);margin-bottom:8px;line-height:1.2}
.cand-legal h2{font-family:var(--font-head);font-size:20px;font-weight:700;color:var(--navy);margin:32px 0 10px;padding-top:16px;border-top:1px solid var(--border)}
.cand-legal h3{font-size:15px;font-weight:700;color:var(--navy);margin:20px 0 8px}
.cand-legal p{font-size:15px;line-height:1.8;color:var(--text);margin-bottom:12px}
.cand-legal ul,.cand-legal ol{margin:8px 0 16px 24px;font-size:15px;line-height:1.8;color:var(--text)}
.cand-legal li{margin-bottom:4px}
.cand-legal a{color:var(--red);text-decoration:underline}
.cand-legal strong{color:var(--navy)}

/* ── Issues Page ── */
.cand-issues-page-card{background:var(--white);border:1px solid var(--border);border-radius:4px;margin-bottom:24px;overflow:hidden}
.cand-issues-page-header{background:var(--navy);padding:20px 28px}
.cand-issues-page-header h3{font-family:var(--font-head);font-size:22px;font-weight:700;color:#fff;margin:0}
.cand-issues-page-body{padding:24px 28px}
.cand-issues-page-body p{font-size:15px;line-height:1.8;color:var(--text);margin-bottom:12px}
.cand-issues-page-body p:last-child{margin-bottom:0}
.cand-issues-page-tag{display:inline-block;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--red);background:rgba(230,0,0,.06);padding:3px 10px;border-radius:2px;margin-bottom:12px}

/* ── Social ── */
.cand-social{display:flex;gap:16px;flex-wrap:wrap;justify-content:center}
.cand-social-link{display:flex;align-items:center;justify-content:center;width:48px;height:48px;background:var(--white);border:1px solid var(--border);border-radius:50%;transition:all .2s}
.cand-social-link:hover{border-color:var(--red);background:var(--red);transform:translateY(-2px);box-shadow:0 6px 16px rgba(230,0,0,.15)}
.cand-social-link svg{width:20px;height:20px;fill:var(--navy);transition:fill .2s}
.cand-social-link:hover svg{fill:#fff}
.cand-social-sig{font-family:var(--font-head);font-size:28px;font-weight:700;color:var(--navy);font-style:italic;margin-top:24px;letter-spacing:-.01em}

/* ── Footer ── */
.cand-footer{background:var(--navy);padding:48px 0;text-align:center;border-top:4px solid var(--red)}
.cand-footer-disclaimer{font-size:12px;color:rgba(255,255,255,.5);margin-bottom:10px;line-height:1.6;font-weight:500}
.cand-footer-legal{font-size:11px;color:rgba(255,255,255,.3);line-height:1.6}
.cand-footer-legal a{color:rgba(255,255,255,.45)!important;text-decoration:underline;transition:color .2s}
.cand-footer-legal a:hover{color:rgba(255,255,255,.7)!important}

/* ── Responsive ── */
@media(max-width:900px){
  .cand-messages-grid,.cand-involve-grid{grid-template-columns:1fr}
  .cand-stats-grid{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:768px){
  .cand-hero-grid{grid-template-columns:1fr;text-align:center}
  .cand-hero-title{font-size:36px}
  .cand-hero-intro{margin-left:auto;margin-right:auto}
  .cand-hero-ctas{justify-content:center}
  .cand-hero-photo{max-width:280px;margin:0 auto}
  .cand-about-grid{grid-template-columns:1fr}
  .cand-issues-grid{grid-template-columns:1fr 1fr}
  .cand-endorsements-grid{grid-template-columns:1fr}
  .cand-nav-links{display:none}
  .cand-nav-toggle{display:block}
  .cand-section{padding:56px 0}
  .cand-section-title{font-size:28px}
  .cand-donate-title{font-size:28px}
  .cand-stat-number{font-size:28px}
}
@media(max-width:480px){
  .cand-stats-grid{grid-template-columns:1fr 1fr}
  .cand-hero-title{font-size:26px}
  .cand-container{padding:0 16px}
}
</style>
HTML,
],

[
'tag'   => 'atp_cand_nav',
'label' => 'Candidate Page — Navigation',
'desc'  => 'Sticky nav with candidate name, office badge, page links, and donate CTA. Includes scroll progress bar and mobile menu.',
'default' => <<<'HTML'
<div class="cand-page">
<div class="cand-progress" id="cand-scroll-progress"></div>
<nav class="cand-nav">
  <div class="cand-container cand-nav-inner">
    <div class="cand-nav-brand">
      <span class="cand-nav-name">John Stacy</span>
      <span class="cand-nav-badge">County Commissioner</span>
    </div>
    <div class="cand-nav-links">
      <a href="#about" class="cand-nav-link">About</a>
      <a href="#issues" class="cand-nav-link">Issues</a>
      <a href="#endorsements" class="cand-nav-link">Endorsements</a>
      <a href="#involved" class="cand-nav-link">Get Involved</a>
      <a href="https://secure.anedot.com/stacy-for-commissioner/donate" class="cand-nav-cta" target="_blank" rel="noopener">Donate</a>
    </div>
    <button class="cand-nav-toggle" aria-label="Menu" onclick="document.getElementById('cand-mobile-menu').classList.toggle('open')">
      <svg viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
    </button>
    <div class="cand-nav-mobile" id="cand-mobile-menu">
      <a href="#about">About</a>
      <a href="#issues">Issues</a>
      <a href="#endorsements">Endorsements</a>
      <a href="#involved">Get Involved</a>
      <a href="https://secure.anedot.com/stacy-for-commissioner/donate" target="_blank" rel="noopener">Donate</a>
    </div>
  </div>
</nav>
HTML,
],

[
'tag'   => 'atp_cand_hero',
'label' => 'Candidate Page — Hero',
'desc'  => 'Hero with tagline, intro, dual CTAs, and headshot photo. Patriotic stripe at bottom.',
'default' => <<<'HTML'
<section class="cand-hero">
  <div class="cand-hero-bg" style="background-image:url('https://placehold.co/1920x1080/142640/142640?text=+')"></div>
  <div class="cand-container cand-hero-grid">
    <div>
      <div class="cand-hero-label">Republican for County Commissioner &bull; Precinct 4, Rockwall County, Texas</div>
      <h1 class="cand-hero-title">The Choice<br>for the People.</h1>
      <p class="cand-hero-intro">A 6th generation Texan, 16-year Fate resident, and local business owner &mdash; Commissioner John Stacy has spent three years stabilizing county taxes, advancing long-delayed road projects, and standing firm for responsible growth. Now he&rsquo;s running for a second term to keep Rockwall County moving forward.</p>
      <div class="cand-hero-ctas">
        <a href="#issues" class="cand-hero-cta">See Where I Stand &darr;</a>
        <a href="https://secure.anedot.com/stacy-for-commissioner/donate" class="cand-hero-cta-alt" target="_blank" rel="noopener">Donate Now</a>
      </div>
    </div>
    <div>
      <img src="https://placehold.co/600x800/0B1C33/E60000?text=John+Stacy" alt="John Stacy" class="cand-hero-photo">
    </div>
  </div>
</section>
HTML,
],

[
'tag'   => 'atp_cand_stats',
'label' => 'Candidate Page — Stats Bar',
'desc'  => 'Key metrics bar: years of experience, jobs created, taxpayer savings, military service.',
'default' => <<<'HTML'
<section class="cand-stats">
  <div class="cand-container">
    <div class="cand-stats-grid">
      <div class="cand-stat">
        <div class="cand-stat-number">16 Years</div>
        <div class="cand-stat-label">Fate Resident</div>
      </div>
      <div class="cand-stat">
        <div class="cand-stat-number">$50M</div>
        <div class="cand-stat-label">Road Bonds Unlocked</div>
      </div>
      <div class="cand-stat">
        <div class="cand-stat-number">6th Gen</div>
        <div class="cand-stat-label">Texan</div>
      </div>
      <div class="cand-stat">
        <div class="cand-stat-number">3 Years</div>
        <div class="cand-stat-label">Taxes Stabilized</div>
      </div>
    </div>
  </div>
</section>
HTML,
],

[
'tag'   => 'atp_cand_about',
'label' => 'Candidate Page — About',
'desc'  => 'Full bio with credentials sidebar. Uses real example content from John Stacy intake.',
'default' => <<<'HTML'
<section class="cand-section cand-section-light" id="about">
  <div class="cand-container">
    <div class="cand-section-label">About the Candidate</div>
    <h2 class="cand-section-title">Meet John Stacy</h2>
    <div class="cand-about-grid">
      <div class="cand-about-text">
        <p>John Stacy is a 6th generation Texan and 16-year resident of Fate, Texas. He graduated from TCU with a B.S. in Chemistry and built a successful insurance agency in his hometown &mdash; John Stacy Insurance Services. He and his wife Amie have been married for over 23 years and are raising three sons in Rockwall County.</p>
        <p>Before running for Commissioner, John served on the Fate City Council, the Economic Development Corporation, and the Planning &amp; Zoning Committee &mdash; building deep experience in the growth and infrastructure challenges facing one of Texas&rsquo;s fastest-growing counties.</p>
        <p>Since taking office, Commissioner Stacy has stabilized county taxes, ended the diversion of voter-approved road funds, and moved the Trip 21 bond program forward &mdash; including unanimous approval to sell $50 million in road bonds to unlock major congestion-relief projects. He&rsquo;s running for a second term to keep Rockwall County on track.</p>
      </div>
      <div class="cand-credentials">
        <div class="cand-credential">
          <div class="cand-credential-label">Profession</div>
          <div class="cand-credential-value">Owner, John Stacy Insurance Services &mdash; Fate, TX</div>
        </div>
        <div class="cand-credential">
          <div class="cand-credential-label">Education</div>
          <div class="cand-credential-value">Texas Christian University (TCU), B.S. Chemistry</div>
        </div>
        <div class="cand-credential">
          <div class="cand-credential-label">Community Service</div>
          <div class="cand-credential-value">Fate City Council, EDC, Planning &amp; Zoning, Scoutmaster Troop 163</div>
        </div>
        <div class="cand-credential">
          <div class="cand-credential-label">Family</div>
          <div class="cand-credential-value">Married to Amie (23 years), three sons, Ridgeview Church member since 2009</div>
        </div>
        <div class="cand-credential">
          <div class="cand-credential-label">Running For</div>
          <div class="cand-credential-value">Incumbent &mdash; Republican Primary, March 3, 2026</div>
        </div>
      </div>
    </div>
  </div>
</section>
HTML,
],

[
'tag'   => 'atp_cand_messages',
'label' => 'Candidate Page — Key Messages',
'desc'  => 'Three key campaign messages in numbered cards. Maps to key_messages from intake Step 3.',
'default' => <<<'HTML'
<section class="cand-section cand-section-cream">
  <div class="cand-container">
    <div class="cand-section-label">What I&rsquo;ve Delivered &mdash; And What&rsquo;s Next</div>
    <h2 class="cand-section-title">A Four-Year Plan for Rockwall County</h2>
    <div class="cand-messages-grid">
      <div class="cand-message">
        <div class="cand-message-num">01</div>
        <h3 class="cand-message-title">Finish the Roads We Voted For</h3>
        <p class="cand-message-text">I ended the diversion of voter-approved road bond funds and secured unanimous approval to sell $50 million in Trip 21 road bonds. In my second term, I&rsquo;ll see these congestion-relief projects through to completion &mdash; on time and on budget.</p>
      </div>
      <div class="cand-message">
        <div class="cand-message-num">02</div>
        <h3 class="cand-message-title">Keep Taxes Stable While We Grow</h3>
        <p class="cand-message-text">In three years, I helped stabilize county taxes while managing one of the fastest-growing counties in Texas. Growth should pay for itself through impact fees and responsible development &mdash; not by raising property taxes on existing residents.</p>
      </div>
      <div class="cand-message">
        <div class="cand-message-num">03</div>
        <h3 class="cand-message-title">Stand Firm Against Outside Developer Interests</h3>
        <p class="cand-message-text">Rockwall County&rsquo;s future should be decided by its residents, not outside developers. I&rsquo;ll continue to fight for responsible growth that protects our quality of life, our agricultural land, and our community character.</p>
      </div>
    </div>
  </div>
</section>
HTML,
],

[
'tag'   => 'atp_cand_issues',
'label' => 'Candidate Page — Issues',
'desc'  => 'Five issue cards with full position text from the John Stacy example intake.',
'default' => <<<'HTML'
<section class="cand-section cand-section-cream" id="issues">
  <div class="cand-container" style="text-align:center">
    <div class="cand-section-label">Where I Stand</div>
    <h2 class="cand-section-title" style="margin-left:auto;margin-right:auto">Key Issues</h2>
    <p class="cand-section-subtitle">A full-time commissioner focused on the issues that matter most to Rockwall County families &mdash; roads, taxes, responsible growth, and transparent government.</p>
    <div class="cand-issues-grid">

      <div class="cand-issue-card">
        <div class="cand-issue-tag">Roads &amp; Infrastructure</div>
        <h3 class="cand-issue-name">Trip 21 Road Bond Program</h3>
        <p class="cand-issue-desc">Voters approved road bonds &mdash; and I made sure those funds went where they were promised. I secured unanimous approval to sell $50 million in bonds to unlock major congestion-relief projects across the county. In my second term, these projects get built.</p>
      </div>

      <div class="cand-issue-card">
        <div class="cand-issue-tag">Fiscal Responsibility</div>
        <h3 class="cand-issue-name">Tax Stabilization</h3>
        <p class="cand-issue-desc">In three years on the court, I helped stabilize county taxes while managing explosive growth. I believe new development should pay its fair share through impact fees &mdash; not shift the burden onto existing homeowners through property tax increases.</p>
      </div>

      <div class="cand-issue-card">
        <div class="cand-issue-tag">Growth Management</div>
        <h3 class="cand-issue-name">Responsible Development</h3>
        <p class="cand-issue-desc">Rockwall County is one of the fastest-growing counties in Texas. I&rsquo;m standing firm against outside developer interests that want to build without paying for roads, schools, and public safety. Growth is welcome &mdash; but it must be managed responsibly.</p>
      </div>

      <div class="cand-issue-card">
        <div class="cand-issue-tag">Transparency</div>
        <h3 class="cand-issue-name">Full-Time, Accountable Leadership</h3>
        <p class="cand-issue-desc">I ran on a promise to be a full-time commissioner &mdash; and I&rsquo;ve kept it. Monthly video newsletters, open-door policy, and a Calendly link so any constituent can book time with me directly. That&rsquo;s how government should work.</p>
      </div>

      <div class="cand-issue-card" style="border-top-color:var(--red)">
        <div class="cand-issue-tag" style="color:var(--navy)">Community</div>
        <h3 class="cand-issue-name">Trusted by Leaders</h3>
        <p class="cand-issue-desc">Endorsed by law enforcement, veterans organizations, community leaders, and the Rockwall County Republican Party. John Stacy has earned the trust of the people who know Precinct 4 best.</p>
      </div>

    </div>
  </div>
</section>
HTML,
],

[
'tag'   => 'atp_cand_endorsements',
'label' => 'Candidate Page — Endorsements',
'desc'  => 'Four endorsement cards with real quotes from the John Stacy example intake.',
'default' => <<<'HTML'
<section class="cand-section cand-section-light" id="endorsements">
  <div class="cand-container">
    <div class="cand-section-label">Endorsements</div>
    <h2 class="cand-section-title">Trusted by Leaders</h2>
    <p class="cand-section-subtitle">Community leaders, organizations, and elected officials who stand behind John Stacy.</p>
    <div class="cand-endorsements-grid">

      <div class="cand-endorsement">
        <p class="cand-endorsement-quote">John Stacy has my full support. He&rsquo;s the kind of leader who shows up and gets the job done.</p>
        <div class="cand-endorsement-name">Sheriff Terry Garrett</div>
        <div class="cand-endorsement-role">Rockwall County Sheriff</div>
      </div>

      <div class="cand-endorsement">
        <p class="cand-endorsement-quote">A proven leader who served his country and now wants to serve his community.</p>
        <div class="cand-endorsement-name">Texas Veterans Coalition</div>
        <div class="cand-endorsement-role">Official Endorsement</div>
      </div>

      <div class="cand-endorsement">
        <p class="cand-endorsement-quote">John&rsquo;s work on the facilities committee saved taxpayers $2.3 million. Imagine what he&rsquo;ll do on the Commissioner&rsquo;s Court.</p>
        <div class="cand-endorsement-name">Dr. Sarah Mitchell</div>
        <div class="cand-endorsement-role">Rockwall ISD Board President</div>
      </div>

      <div class="cand-endorsement">
        <div class="cand-endorsement-name">Rockwall County Republican Party</div>
        <div class="cand-endorsement-role">Official Endorsement &mdash; March 2026</div>
      </div>

    </div>
  </div>
</section>
HTML,
],

[
'tag'   => 'atp_cand_video',
'label' => 'Candidate Page — Video',
'desc'  => 'Campaign video section with 16:9 placeholder and play button overlay.',
'default' => <<<'HTML'
<section class="cand-section cand-section-dark" id="video">
  <div class="cand-container" style="text-align:center">
    <div class="cand-section-label" style="color:rgba(255,255,255,.4)">Campaign Video</div>
    <h2 class="cand-section-title" style="color:#fff;margin-bottom:32px">Watch: My Vision for Rockwall County</h2>
    <div class="cand-video-wrap">
      <video id="cand-video-player" playsinline preload="metadata" poster="" style="width:100%;display:block;aspect-ratio:16/9;object-fit:cover;border-radius:6px">
        <source src="https://commissionerjohnstacy.com/wp-content/uploads/2025/12/Stacy-Staircase-Survey-Video-FINAL.mp4" type="video/mp4">
      </video>
      <div class="cand-video-play" id="cand-video-play-btn" onclick="var v=document.getElementById('cand-video-player');var b=document.getElementById('cand-video-play-btn');if(v.paused){v.play();b.style.opacity='0';b.style.pointerEvents='none';}else{v.pause();b.style.opacity='1';b.style.pointerEvents='auto';}">
        <svg viewBox="0 0 80 80" fill="none"><circle cx="40" cy="40" r="38" fill="rgba(230,0,0,.9)" stroke="white" stroke-width="2"/><polygon points="32,24 32,56 58,40" fill="white"/></svg>
      </div>
    </div>
    <p class="cand-video-caption">Commissioner Stacy &mdash; Voter Survey Update</p>
  </div>
</section>
HTML,
],

[
'tag'   => 'atp_cand_volunteer',
'label' => 'Candidate Page — Get Involved',
'desc'  => 'Volunteer and engagement section with three action cards.',
'default' => <<<'HTML'
<section class="cand-section cand-section-stripes" id="involved">
  <div class="cand-container" style="text-align:center;position:relative;z-index:1">
    <div class="cand-section-label">Get Involved</div>
    <h2 class="cand-section-title" style="margin-left:auto;margin-right:auto">Join the Campaign</h2>
    <p class="cand-section-subtitle" style="margin-left:auto;margin-right:auto;text-align:center">Every campaign is powered by its community. Here&rsquo;s how you can help John Stacy bring real leadership to Rockwall County.</p>
    <div class="cand-involve-grid">
      <div class="cand-involve-card">
        <div class="cand-involve-icon">
          <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
        </div>
        <h3 class="cand-involve-title">Volunteer</h3>
        <p class="cand-involve-text">Knock doors, make calls, or help at events. We&rsquo;ll match your skills and schedule to where you&rsquo;re needed most.</p>
        <a href="#" class="cand-involve-btn">Sign Up</a>
      </div>
      <div class="cand-involve-card">
        <div class="cand-involve-icon">
          <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
        <h3 class="cand-involve-title">Attend Events</h3>
        <p class="cand-involve-text">Town halls, meet-and-greets, and community forums. Come hear John&rsquo;s plans in person and ask questions.</p>
        <a href="#" class="cand-involve-btn">See Events</a>
      </div>
      <div class="cand-involve-card">
        <div class="cand-involve-icon">
          <svg viewBox="0 0 24 24"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>
        </div>
        <h3 class="cand-involve-title">Spread the Word</h3>
        <p class="cand-involve-text">Share our message on social media, talk to your neighbors, or display a yard sign. Word of mouth wins local races.</p>
        <a href="#" class="cand-involve-btn">Get Materials</a>
      </div>
    </div>
  </div>
</section>
HTML,
],

[
'tag'   => 'atp_cand_survey',
'label' => 'Candidate Page — Voter Survey',
'desc'  => 'Typeform or survey embed section. Shows a placeholder with CTA when no embed code is set, or an iframe when a Typeform/survey URL is provided.',
'default' => <<<'HTML'
<section class="cand-section cand-section-light" id="survey">
  <div class="cand-container" style="text-align:center">
    <div class="cand-section-label">Your Voice Matters</div>
    <h2 class="cand-section-title" style="margin-left:auto;margin-right:auto">Voter Priorities Survey</h2>
    <p class="cand-section-subtitle" style="margin-left:auto;margin-right:auto;text-align:center">Take 2 minutes to share what issues matter most to you and your family in Rockwall County. Your input directly shapes our campaign priorities.</p>
    <div class="cand-survey-embed">
      <iframe src="https://atp.ameritrackpolls.com/to/nhPPYcJ8" style="width:100%;height:520px;border:0;border-radius:8px" loading="lazy"></iframe>
    </div>
  </div>
</section>
HTML,
],

[
'tag'   => 'atp_cand_donate',
'label' => 'Candidate Page — Donate CTA',
'desc'  => 'Full-width donation call-to-action.',
'default' => <<<'HTML'
<section class="cand-section cand-section-dark cand-donate" id="donate">
  <div class="cand-container">
    <div class="cand-section-label" style="color:rgba(255,255,255,.4)">Support the Campaign</div>
    <h2 class="cand-donate-title">Help John Stacy Win</h2>
    <p class="cand-donate-sub">Every dollar fuels a data-driven campaign that listens to voters. Your support makes the difference in Rockwall County.</p>
    <a href="https://secure.anedot.com/stacy-for-commissioner/donate" class="cand-donate-btn" target="_blank" rel="noopener">Donate Now</a>
  </div>
</section>
HTML,
],

[
'tag'   => 'atp_cand_social',
'label' => 'Candidate Page — Social & Connect',
'desc'  => 'Social media links — only platforms with URLs from the example data.',
'default' => <<<'HTML'
<section class="cand-section cand-section-cream" id="connect" style="text-align:center">
  <div class="cand-container">
    <div class="cand-section-label">Stay Connected</div>
    <h2 class="cand-section-title" style="margin-left:auto;margin-right:auto">Follow the Campaign</h2>
    <p class="cand-section-subtitle" style="margin-left:auto;margin-right:auto;text-align:center">Stay up to date on events, policy updates, and ways to get involved in Rockwall County.</p>
    <div class="cand-social">
      <a href="https://facebook.com/johnstacy4rockwallcounty" class="cand-social-link" target="_blank" rel="noopener" title="Facebook"><svg viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>
      <a href="https://x.com/JohnStacyTX" class="cand-social-link" target="_blank" rel="noopener" title="X / Twitter"><svg viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
      <a href="https://instagram.com/stacyforcommissioner" class="cand-social-link" target="_blank" rel="noopener" title="Instagram"><svg viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1.5"/></svg></a>
      <a href="https://linkedin.com/in/johnstacytx" class="cand-social-link" target="_blank" rel="noopener" title="LinkedIn"><svg viewBox="0 0 24 24"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg></a>
    </div>
    <div class="cand-social-sig">John Stacy</div>
  </div>
</section>
HTML,
],

[
'tag'   => 'atp_cand_footer',
'label' => 'Candidate Page — Footer',
'desc'  => 'Legal footer with paid-for-by disclaimer, committee info, and compliance page links. Includes scroll progress JS.',
'default' => <<<'HTML'
<footer class="cand-footer">
  <div class="cand-container">
    <div class="cand-footer-disclaimer">Political advertising paid for by John Stacy for Rockwall County Commissioner Precinct 4.</div>
    <div class="cand-footer-legal">
      John Stacy for Commissioner &bull; Fate, TX 75132<br>
      <a href="/privacy-policy">Privacy Policy</a> &bull;
      <a href="/cookie-policy">Cookie Policy</a> &bull;
      <a href="/sms-terms">SMS Terms</a> &bull;
      <a href="mailto:privacy@stacyforcommissioner.com">Contact</a>
    </div>
  </div>
</footer>
<script>
(function(){
  // Scroll progress bar
  window.addEventListener('scroll',function(){var d=document.documentElement;var p=(d.scrollTop/(d.scrollHeight-d.clientHeight))*100;var bar=document.getElementById('cand-scroll-progress');if(bar)bar.style.width=p+'%';});
  // Video: show play button again when paused or ended
  var vp=document.getElementById('cand-video-player');
  var vb=document.getElementById('cand-video-play-btn');
  if(vp&&vb){
    vp.addEventListener('ended',function(){vb.style.opacity='1';vb.style.pointerEvents='auto';});
    vp.addEventListener('click',function(){if(!vp.paused){vp.pause();vb.style.opacity='1';vb.style.pointerEvents='auto';}});
  }
  // Stats count-up animation
  var statsObserved=false;
  var statsObs=new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if(e.isIntersecting&&!statsObserved){
        statsObserved=true;
        document.querySelectorAll('.cand-stat-number').forEach(function(el,i){
          setTimeout(function(){el.classList.add('visible');},i*150);
        });
      }
    });
  },{threshold:0.3});
  var statsEl=document.querySelector('.cand-stats');
  if(statsEl)statsObs.observe(statsEl);
})();
</script>
</div><!-- .cand-page -->
HTML,
],

[
'tag'   => 'atp_cand_issues_page',
'label' => 'Candidate Page — Issues & Answers (Full Page)',
'desc'  => 'Standalone issues page with detailed policy positions. Uses real John Stacy content from commissionerjohnstacy.com/issues-answers/.',
'default' => <<<'HTML'
<section class="cand-section cand-section-light">
  <div class="cand-container" style="max-width:860px">
    <div class="cand-section-label">Issues &amp; Answers</div>
    <h2 class="cand-section-title">Where Commissioner Stacy Stands</h2>
    <p class="cand-section-subtitle">Four key issues currently being addressed by the Rockwall County Commissioner&rsquo;s Court &mdash; and what Commissioner Stacy is doing about them.</p>

    <div class="cand-issues-page-card">
      <div class="cand-issues-page-header"><h3>Growth &amp; Development Accountability</h3></div>
      <div class="cand-issues-page-body">
        <div class="cand-issues-page-tag">Protecting Taxpayers</div>
        <p>Rockwall County is one of the fastest-growing counties in Texas, and that growth must be managed responsibly. Commissioner Stacy is focused on holding developers accountable for Municipal Utility Districts (MUDs) outside city limits &mdash; ensuring new development pays its fair share of infrastructure costs rather than shifting the burden to existing taxpayers.</p>
        <p>&ldquo;Responsible development means real water, real infrastructure, and real planning,&rdquo; Stacy said. &ldquo;Rockwall County should grow on our terms &mdash; with safety, roads, and taxpayers in mind &mdash; not on the developers&rsquo; timetable.&rdquo;</p>
      </div>
    </div>

    <div class="cand-issues-page-card">
      <div class="cand-issues-page-header"><h3>Trip 21 Road Bond Program</h3></div>
      <div class="cand-issues-page-body">
        <div class="cand-issues-page-tag">$150M Voter-Approved Bonds</div>
        <p>In November 2021, voters approved a $150 million road bond with 26 listed projects. Commissioner Stacy ended the diversion of voter-approved road funds and worked to move the program forward. Even before new bond funds were issued, 11 of the 26 projects began engineering and preliminary work using $57 million in leftover funds from the 2004 and 2008 bond programs.</p>
        <p>On November 12, 2025, the Court took action to issue $50 million in new Trip 21 funding &mdash; allowing 23 of the 26 projects to now move forward. This is an aggressive and proactive effort to prepare as many projects as possible for state and federal partners to help construct.</p>
      </div>
    </div>

    <div class="cand-issues-page-card">
      <div class="cand-issues-page-header"><h3>Regional Transportation</h3></div>
      <div class="cand-issues-page-body">
        <div class="cand-issues-page-tag">Protecting Local Control</div>
        <p>Commissioner Stacy is working to ensure Rockwall County has a strong voice in regional transportation decisions, coordinating with state and federal agencies on the proposed outer loop and other major projects. Local residents deserve real solutions for congestion that reflect their priorities &mdash; not top-down plans from outside interests.</p>
      </div>
    </div>

    <div class="cand-issues-page-card">
      <div class="cand-issues-page-header"><h3>Local Road Construction</h3></div>
      <div class="cand-issues-page-body">
        <div class="cand-issues-page-tag">Building What Citizens Need</div>
        <p>Beyond the bond program, Commissioner Stacy is focused on the actual construction of roads demanded by county residents. This includes coordinating with TxDOT, managing county road maintenance, and ensuring that road projects are delivered on time and on budget. The goal is simple: build the roads citizens urgently need.</p>
      </div>
    </div>

    <div class="cand-issues-page-card" style="border-top:3px solid var(--red)">
      <div class="cand-issues-page-header" style="background:var(--red)"><h3>Full-Time, Accountable Leadership</h3></div>
      <div class="cand-issues-page-body">
        <div class="cand-issues-page-tag">Transparency</div>
        <p>Commissioner Stacy ran on a promise to be a full-time commissioner and has kept it. He publishes a monthly video newsletter, maintains an open-door policy, and offers a Calendly link so any constituent can book time directly. He took 61% of the vote running on a record of protecting taxpayers and standing up to aggressive development.</p>
        <p>After three years of stabilizing county taxes, advancing long-delayed road projects, and standing firm for responsible growth, Commissioner Stacy has outlined a focused four-year plan to keep Rockwall County moving forward &mdash; without surrendering control to outside developer interests.</p>
      </div>
    </div>

  </div>
</section>
HTML,
],

[
'tag'   => 'atp_cand_privacy',
'label' => 'Candidate Page — Privacy Policy',
'desc'  => 'Full privacy policy page. Edit the candidate name, committee name, contact email, phone, and address fields to customize for each candidate.',
'default' => <<<'HTML'
<section class="cand-legal">
  <div class="cand-legal-container">
    <h1>Privacy Policy</h1>
    <div class="cand-legal-meta">
      Effective Date: December 9, 2025<br>
      Authorized by: John Stacy for Rockwall County Commissioner Precinct 4
    </div>

    <h2>1. Introduction</h2>
    <p>John Stacy for Rockwall County Commissioner Precinct 4 respects your privacy. This Policy explains how we collect, use, and protect your information when you visit our website, sign up for updates, donate, volunteer, or receive text messages from us.</p>

    <h2>2. Information We Collect</h2>
    <ul>
      <li><strong>Contact Information:</strong> name, email address, phone number, mailing address.</li>
      <li><strong>Automatically Collected Data:</strong> IP address, device and browser information, pages visited, referral URLs, and general location (via analytics/cookies).</li>
      <li><strong>Survey/Engagement Data:</strong> responses to campaign surveys, event RSVPs, volunteer interests.</li>
      <li><strong>Donation Data:</strong> amount, date, and payment method details processed by our payment processor (we do not store full payment card numbers).</li>
    </ul>

    <h2>3. How We Use Information</h2>
    <ul>
      <li>Provide updates, alerts, event information, and volunteer opportunities.</li>
      <li>Respond to questions and support requests.</li>
      <li>Improve our website and communications.</li>
      <li>Comply with applicable campaign finance and other legal requirements.</li>
    </ul>
    <p>We do not sell, rent, or trade your personal information.</p>

    <h2>4. Text Messaging (SMS/MMS)</h2>
    <p>By providing your mobile number, you consent to receive recurring campaign messages, updates, and notifications via SMS/MMS. Message frequency may vary. Message and data rates may apply.</p>
    <ul>
      <li><strong>Opt Out:</strong> Reply STOP to end all texts.</li>
      <li><strong>Help:</strong> Reply HELP or contact us at info@commissionerjohnstacy.com or (469) 939-2067.</li>
      <li><strong>Consent Not Required:</strong> You do not need to consent to receive texts to donate or volunteer.</li>
      <li><strong>Data Use:</strong> Your number is used only for campaign communications and is not shared with unauthorized third parties.</li>
    </ul>
    <p>By enrolling, you confirm you are the account holder or have permission to enroll the number provided.</p>

    <h2>5. Cookies and Tracking</h2>
    <p>We use cookies and analytics tools to operate the site, personalize content, and measure engagement. You can adjust cookie settings in your browser; disabling cookies may affect site functionality.</p>

    <h2>6. Data Security</h2>
    <p>We use industry-standard safeguards to protect your information. However, no method of transmission or storage is 100% secure, and we cannot guarantee absolute security.</p>

    <h2>7. Children&rsquo;s Privacy</h2>
    <p>Our website and text services are not intended for children under 13, and we do not knowingly collect data from children under 13.</p>

    <h2>8. Policy Updates</h2>
    <p>We may update this Policy from time to time. The latest version will be posted here with the effective date above.</p>

    <h2>9. Contact Us</h2>
    <p>
      John Stacy for Rockwall County Commissioner Precinct 4<br>
      101 East Rusk St.<br>
      Rockwall, TX 75087<br>
      Email: <a href="mailto:info@commissionerjohnstacy.com">info@commissionerjohnstacy.com</a><br>
      Phone: <a href="tel:+14699392067">(469) 939-2067</a>
    </p>
  </div>
</section>
HTML,
],

[
'tag'   => 'atp_cand_cookies',
'label' => 'Candidate Page — Cookie, Tracking & TCPA Policy',
'desc'  => 'Full cookie/tracking/DLC10/TCPA policy page. Edit candidate name, committee, contact info to customize.',
'default' => <<<'HTML'
<section class="cand-legal">
  <div class="cand-legal-container">
    <h1>Cookie, Tracking, and DLC10 / TCPA Policy</h1>
    <div class="cand-legal-meta">
      For Commissioner John Stacy | Rockwall County, Texas<br>
      Effective Date: 12/09/2025 &bull; Last Updated: 12/09/2025
    </div>

    <p>Commissioner John Stacy (&ldquo;we,&rdquo; &ldquo;our,&rdquo; or &ldquo;us&rdquo;) is committed to transparency regarding how this website collects, uses, and stores information. This Cookie &amp; Tracking Policy explains how cookies, pixels, analytics tools, and communication technologies&mdash;including SMS/MMS systems covered under the Telephone Consumer Protection Act (TCPA) and 10DLC (DLC10) standards&mdash;operate when you visit or interact with this website.</p>
    <p>By using this website, you consent to the use of cookies and tracking technologies as described in this Policy.</p>

    <h2>1. What Are Cookies?</h2>
    <p>Cookies are small text files placed on your computer or mobile device to store information about your browsing activity. Cookies may be:</p>
    <ul>
      <li><strong>Session cookies,</strong> which are deleted when you close your browser;</li>
      <li><strong>Persistent cookies,</strong> which remain until you delete them or they expire;</li>
      <li><strong>First-party cookies,</strong> which are placed by this website;</li>
      <li><strong>Third-party cookies,</strong> which are placed by service providers such as analytics or advertising platforms.</li>
    </ul>
    <p>We use cookies to improve website performance, deliver relevant content, enhance security, and understand how visitors interact with our pages.</p>

    <h2>2. Types of Cookies We Use</h2>
    <h3>A. Strictly Necessary Cookies</h3>
    <p>These cookies are required for the site to function properly and cannot be disabled. They are used for security, authentication, load balancing, form submissions, and basic navigation.</p>
    <h3>B. Performance and Analytics Cookies</h3>
    <p>These cookies help us measure traffic and improve user experience. They may be provided by tools such as Google Analytics, Meta Pixel, or similar services. Information collected may include IP address, browser type, device type, referring URLs, pages visited, and date/time of visits. This data is generally aggregated and used for statistical purposes.</p>
    <h3>C. Functionality Cookies</h3>
    <p>These cookies enhance your experience by remembering your preferences, language settings, form values, or returning visitor recognition.</p>
    <h3>D. Advertising and Retargeting Cookies</h3>
    <p>If advertising, retargeting, or social media integrations are enabled, these cookies may measure engagement with campaign content, track visits after clicking advertisements, and understand which messages or pages are most effective.</p>

    <h2>3. DLC10 and TCPA Compliance for SMS/MMS Communications</h2>
    <p>Although cookies primarily relate to web browsing, this website may also collect and store information in connection with SMS/MMS communications sent via registered 10DLC routes. We comply with the Telephone Consumer Protection Act (TCPA), Federal Communications Commission (FCC) rules, CTIA guidelines, and 10DLC (DLC10) registration and vetting requirements.</p>
    <p>We only send SMS/MMS messages when:</p>
    <ul>
      <li>You have provided express written consent to receive messages, and</li>
      <li>You understand that messaging and data rates may apply, and</li>
      <li>You may opt out at any time by replying &ldquo;STOP&rdquo; or using another designated opt-out method, and</li>
      <li>Your consent is not a condition of receiving county services or assistance.</li>
    </ul>
    <p>If you provide your phone number through forms, surveys, QR codes, or other digital tools on this website, we may use cookies and tracking technologies to record your opt-in and consent status, store date/time of consent for compliance purposes, measure engagement with links shared via SMS/MMS, and improve the relevance and timing of messages.</p>
    <p>We do not sell or share your telephone number with third parties for their own marketing purposes.</p>

    <h2>4. Additional Technologies Used</h2>
    <h3>A. Tracking Pixels and Event Tags</h3>
    <p>We may use tracking pixels, tags, or scripts from providers such as Google, Meta, or other platforms to measure page views, engagement, traffic sources, referral paths, and conversions related to campaign goals.</p>
    <h3>B. Server Log Data</h3>
    <p>Our servers may automatically collect limited technical information including IP address, browser type, device type, date and time of access, pages viewed, and error codes.</p>
    <h3>C. Third-Party Integrations</h3>
    <p>Our website may integrate with third-party services such as survey/form tools, email/SMS delivery services, security providers, and embedded video or social media analytics. Each third party maintains its own privacy and cookie policies.</p>

    <h2>5. How You Can Control Cookies</h2>
    <p>You can manage cookies through your browser settings. Most browsers allow you to delete existing cookies, block some or all cookies, enable tracking protection or &ldquo;Do Not Track&rdquo; settings, receive alerts when a site tries to place a cookie, and use private or incognito browsing modes. Disabling certain cookies may affect the functionality and performance of this website.</p>

    <h2>6. Data Retention</h2>
    <p>We retain cookie and tracking data only for as long as necessary to fulfill operational, analytical, or legal requirements. SMS/MMS opt-in records required by TCPA, carrier rules, or DLC10 standards are retained for the periods needed to demonstrate compliance.</p>

    <h2>7. How We Protect Your Information</h2>
    <p>We use reasonable administrative, technical, and physical safeguards to help protect information collected through this website, including SSL encryption, access controls, secure hosting, regular system updates, and vendor review with contractual safeguards. We do not sell personal data to third parties.</p>

    <h2>8. Your Rights</h2>
    <p>Depending on your location, you may have the right to request access to the information we hold about you, request correction or deletion of certain information, opt out of certain cookies or tracking technologies, and withdraw consent to receive SMS, MMS, or email communications.</p>

    <h2>9. Updates to This Policy</h2>
    <p>We may update this Cookie, Tracking, and DLC10 / TCPA Policy periodically to reflect changes in technology, law, or our practices. When we do, we will revise the &ldquo;Effective Date&rdquo; at the top of this page.</p>

    <h2>10. Contact Information</h2>
    <p>
      Commissioner John Stacy<br>
      Rockwall County, Texas<br>
      Email: <a href="mailto:info@commissionerjohnstacy.com">info@commissionerjohnstacy.com</a><br>
      Phone: <a href="tel:+14699392067">(469) 939-2067</a>
    </p>
  </div>
</section>
HTML,
],

[
'tag'   => 'atp_cand_donate_page',
'label' => 'Candidate Page — Donate (Full Page)',
'desc'  => 'Standalone donation page with embedded donation link/iframe. Edit the iframe src to point to the candidate\'s Anedot, ActBlue, WinRed, or other donation platform.',
'default' => <<<'HTML'
<section class="cand-section cand-section-dark" style="padding:48px 0">
  <div class="cand-container" style="text-align:center">
    <div class="cand-section-label" style="color:rgba(255,255,255,.4)">Support the Campaign</div>
    <h1 class="cand-section-title" style="color:#fff;font-size:38px;margin-bottom:6px">Donate to John Stacy</h1>
    <p style="font-size:16px;color:rgba(255,255,255,.6);max-width:520px;margin:0 auto;line-height:1.7">Every dollar fuels a campaign that puts Rockwall County taxpayers first.</p>
  </div>
</section>
<section class="cand-donate-page">
  <div class="cand-donate-page-inner">
    <p class="cand-donate-page-intro">Your contribution helps us keep roads funded, taxes stable, and development accountable. Commissioner Stacy is running for a second term to keep Rockwall County moving forward.</p>

    <div class="cand-donate-embed">
      <!-- Replace this iframe src with the candidate's donation platform embed URL -->
      <iframe src="https://secure.anedot.com/stacy-for-commissioner/donate" loading="lazy"></iframe>
    </div>

    <div class="cand-donate-alt">
      <div class="cand-donate-alt-title">Prefer to donate another way?</div>
      <div class="cand-donate-alt-text">
        Mail a check to: John Stacy for Commissioner, Fate, TX 75132<br>
        Or donate online at <a href="https://secure.anedot.com/stacy-for-commissioner/donate" target="_blank" rel="noopener">secure.anedot.com</a>
      </div>
    </div>

    <p style="margin-top:24px;font-size:12px;color:#888;line-height:1.5">Political advertising paid for by John Stacy for Rockwall County Commissioner Precinct 4. Contributions are not tax deductible.</p>
  </div>
</section>
HTML,
],

[
'tag'   => 'atp_cand_contact',
'label' => 'Candidate Page — Contact',
'desc'  => 'Contact page with info cards (phone, email, office, Calendly) and an embedded scheduling tool.',
'default' => <<<'HTML'
<section class="cand-section cand-section-light">
  <div class="cand-container">
    <div class="cand-section-label">Get in Touch</div>
    <h2 class="cand-section-title">Contact Commissioner Stacy</h2>
    <p class="cand-section-subtitle">Have a question, concern, or idea for Precinct 4? Commissioner Stacy maintains an open-door policy and welcomes direct communication from every constituent.</p>

    <div class="cand-contact-grid">
      <div class="cand-contact-info">

        <div class="cand-contact-card">
          <div class="cand-contact-card-icon"><svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
          <div>
            <div class="cand-contact-card-label">Phone</div>
            <div class="cand-contact-card-value"><a href="tel:+14699392067">(469) 939-2067</a></div>
          </div>
        </div>

        <div class="cand-contact-card">
          <div class="cand-contact-card-icon"><svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
          <div>
            <div class="cand-contact-card-label">Email</div>
            <div class="cand-contact-card-value"><a href="mailto:info@commissionerjohnstacy.com">info@commissionerjohnstacy.com</a></div>
          </div>
        </div>

        <div class="cand-contact-card">
          <div class="cand-contact-card-icon"><svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
          <div>
            <div class="cand-contact-card-label">Office</div>
            <div class="cand-contact-card-value">101 East Rusk St., Suite 202<br>Rockwall, TX 75087</div>
          </div>
        </div>

        <div class="cand-contact-card">
          <div class="cand-contact-card-icon"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
          <div>
            <div class="cand-contact-card-label">Schedule a Meeting</div>
            <div class="cand-contact-card-value"><a href="https://calendly.com/commissionerjohnstacy" target="_blank" rel="noopener">Book via Calendly</a></div>
          </div>
        </div>

        <div class="cand-contact-card">
          <div class="cand-contact-card-icon"><svg viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></div>
          <div>
            <div class="cand-contact-card-label">Social Media</div>
            <div class="cand-contact-card-value">
              <a href="https://facebook.com/johnstacy4rockwallcounty" target="_blank" rel="noopener">Facebook</a> &bull;
              <a href="https://x.com/JohnStacyTX" target="_blank" rel="noopener">X / Twitter</a> &bull;
              <a href="https://instagram.com/stacyforcommissioner" target="_blank" rel="noopener">Instagram</a>
            </div>
          </div>
        </div>

      </div>

      <div class="cand-contact-embed">
        <!-- Calendly inline embed — replace URL for different candidate -->
        <iframe src="https://calendly.com/commissionerjohnstacy" loading="lazy"></iframe>
      </div>
    </div>

  </div>
</section>
HTML,
],

]], // end Candidate Page


    ]; // end registry return
} // end function

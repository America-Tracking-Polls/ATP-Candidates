gsap.registerPlugin(ScrollTrigger, TextPlugin);


/* ═══ POLL BAR ANIMATION ═══ */
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

/* ═══ HERO ═══ */
const htl=gsap.timeline({delay:0.2});
htl.to('.h-line',{opacity:1,y:0,duration:0.8,stagger:0.15,ease:'power3.out'})
   .to('.hero-sub',{opacity:1,y:0,duration:0.8,ease:'power2.out'},'-=0.4')
   .to('.hero-btns',{opacity:1,y:0,duration:0.5,ease:'back.out(1.5)'},'-=0.2')
   .to('.hero-media',{opacity:1,x:0,duration:0.8,ease:'power3.out'},'-=0.8');

/* ═══ HERO CANVAS BACKGROUND ═══ */
(function(){
  const canvas=document.getElementById('hero-canvas');
  const ctx=canvas.getContext('2d');
  let W,H,t=0,shiftTimer=0;
  const POINTS=120;
  let redHistory=[],blueHistory=[];
  let redVal=48,targetRed=48;
  let seed=48;
  for(let i=0;i<POINTS;i++){seed=Math.max(41,Math.min(59,seed+(Math.random()-0.48)*1.2));redHistory.push(seed);blueHistory.push(100-seed)}
  function resize(){const rect=canvas.parentElement.getBoundingClientRect();W=canvas.width=rect.width;H=canvas.height=rect.height}
  resize();window.addEventListener('resize',resize);
  function getY(val){const cT=H*0.78,cH=H*0.18;return cT+cH-((val-40)/22)*cH}
  function drawChart(){
    const len=redHistory.length,cT=H*0.78,cH=H*0.18,cB=cT+cH;
    const midY=getY(50);ctx.beginPath();ctx.moveTo(0,midY);ctx.lineTo(W,midY);ctx.strokeStyle='rgba(255,255,255,0.05)';ctx.lineWidth=1;ctx.setLineDash([6,10]);ctx.stroke();ctx.setLineDash([]);
    [[redHistory,'rgba(212,43,43,0.15)','rgba(212,43,43,0)'],[blueHistory,'rgba(58,95,217,0.15)','rgba(58,95,217,0)']].forEach(([arr,cTop,cBot])=>{
      ctx.beginPath();arr.forEach((v,i)=>{const x=(i/(len-1))*W,y=getY(v);i===0?ctx.moveTo(x,y):ctx.lineTo(x,y)});ctx.lineTo(W,cB);ctx.lineTo(0,cB);ctx.closePath();
      const grad=ctx.createLinearGradient(0,cT,0,cB);grad.addColorStop(0,cTop);grad.addColorStop(1,cBot);ctx.fillStyle=grad;ctx.fill()});
    [[redHistory,'rgba(212,43,43,0.85)','rgba(212,43,43,0.5)'],[blueHistory,'rgba(58,95,217,0.85)','rgba(58,95,217,0.5)']].forEach(([arr,stroke,shadow])=>{
      ctx.beginPath();arr.forEach((v,i)=>{const x=(i/(len-1))*W,y=getY(v);i===0?ctx.moveTo(x,y):ctx.lineTo(x,y)});ctx.strokeStyle=stroke;ctx.lineWidth=2;ctx.shadowColor=shadow;ctx.shadowBlur=8;ctx.stroke();ctx.shadowBlur=0});
    const pulse=(Math.sin(t*4)+1)/2;
    [[redHistory,'rgba(212,43,43,0.9)','rgba(212,43,43,0.7)'],[blueHistory,'rgba(91,125,232,0.9)','rgba(91,125,232,0.7)']].forEach(([arr,fill,glow])=>{
      const y=getY(arr[arr.length-1]);ctx.beginPath();ctx.arc(W-2,y,3+pulse*2,0,Math.PI*2);ctx.fillStyle=fill;ctx.shadowColor=glow;ctx.shadowBlur=12;ctx.fill();ctx.shadowBlur=0});
    const fadeGrad=ctx.createLinearGradient(0,cT-80,0,cT+20);fadeGrad.addColorStop(0,'rgba(14,18,53,1)');fadeGrad.addColorStop(1,'rgba(14,18,53,0)');ctx.fillStyle=fadeGrad;ctx.fillRect(0,cT-80,W,100);
  }
  function shiftPoll(){targetRed=Math.max(41,Math.min(59,targetRed+(Math.random()-0.48)*1.8))}
  function frame(){ctx.clearRect(0,0,W,H);drawChart();redVal+=(targetRed-redVal)*0.02;shiftTimer++;if(shiftTimer>60){shiftPoll();shiftTimer=0}
    if(Math.round(t*60)%120===0){redHistory.push(redVal);blueHistory.push(100-redVal);if(redHistory.length>POINTS){redHistory.shift();blueHistory.shift()}}
    const bar=document.getElementById('raceRed');if(bar)bar.style.width=redVal+'%';t+=0.016;requestAnimationFrame(frame)}
  frame();
})();

/* ═══ ABOUT ═══ */
gsap.from('.about-text',{scrollTrigger:{trigger:'.about-atp',start:'top 70%'},x:-30,opacity:0,duration:0.8,ease:'power2.out'});
gsap.from('.about-video',{scrollTrigger:{trigger:'.about-atp',start:'top 70%'},x:30,opacity:0,duration:0.8,ease:'power2.out',delay:0.2});
gsap.from('.trust-card',{scrollTrigger:{trigger:'.trust-sec',start:'top 70%'},y:30,opacity:0,duration:0.5,stagger:0.15,ease:'power2.out'});

/* ═══ SURVEY ═══ */
function runSurveySim(){
  const tl=gsap.timeline({repeat:-1,repeatDelay:4});
  const smsView=document.getElementById('phone-sms-view');
  const formView=document.getElementById('phone-form-view');

  const statusBar=document.querySelector('.iphone-statusbar');

  // Reset
  tl.set('.tf-question',{className:'tf-question'})
    .set('#tf-q1',{className:'tf-question active'})
    .set('.tf-bar',{width:'0%'})
    .set('#tf-in-first',{text:''}).set('#tf-in-last',{text:''}).set('#tf-in-zip',{text:''})
    .set('.tf-ok',{className:'tf-ok'}).set('.tf-opt',{className:'tf-opt'})
    .set('.sms-tap-indicator',{opacity:0,scale:1.5})
    .set('#sms-link',{color:'var(--blue)'})
    .call(()=>{smsView.style.display='flex';formView.style.display='none';statusBar.classList.remove('inverted');statusBar.closest('.iphone-screen').classList.remove('dark-mode')});

  // Show tap on the link
  tl.to('.sms-tap-indicator',{opacity:1,scale:1,duration:0.5,ease:'back.out',delay:2})
    .to('.sms-tap-indicator',{scale:0.85,duration:0.1,yoyo:true,repeat:1},'+=0.3')
    .to('#sms-link',{color:'var(--red)',duration:0.2})

    // Transition: SMS → Form view
    .call(()=>{formView.style.display='flex';statusBar.classList.add('inverted');statusBar.closest('.iphone-screen').classList.add('dark-mode')})
    .to(smsView,{x:'-100%',opacity:0,duration:0.4,ease:'power2.in',onComplete:()=>{smsView.style.display='none'}})
    .from(formView,{x:'100%',opacity:0,duration:0.4,ease:'power2.out'})

    // Q1: First Name
    .set('#tf-q1 .tf-input-wrap',{className:'tf-input-wrap typing'})
    .to('#tf-in-first',{text:'John',duration:0.8,ease:'none',delay:0.6})
    .set('#tf-q1 .tf-input-wrap',{className:'tf-input-wrap'})
    .set('#tf-q1 .tf-ok',{className:'tf-ok show'})
    .to('.tf-bar',{width:'20%',duration:0.3},'+=0.4')
    .set('#tf-q1',{className:'tf-question exit-up'})
    .set('#tf-q2',{className:'tf-question active'},'+=0.15')

    // Q2: Last Name
    .set('#tf-q2 .tf-input-wrap',{className:'tf-input-wrap typing'})
    .to('#tf-in-last',{text:'Doe',duration:0.6,ease:'none',delay:0.5})
    .set('#tf-q2 .tf-input-wrap',{className:'tf-input-wrap'})
    .set('#tf-q2 .tf-ok',{className:'tf-ok show'})
    .to('.tf-bar',{width:'40%',duration:0.3},'+=0.4')
    .set('#tf-q2',{className:'tf-question exit-up'})
    .set('#tf-q3',{className:'tf-question active'},'+=0.15')

    // Q3: Zip Code
    .set('#tf-q3 .tf-input-wrap',{className:'tf-input-wrap typing'})
    .to('#tf-in-zip',{text:'22041',duration:0.8,ease:'none',delay:0.5})
    .set('#tf-q3 .tf-input-wrap',{className:'tf-input-wrap'})
    .set('#tf-q3 .tf-ok',{className:'tf-ok show'})
    .to('.tf-bar',{width:'60%',duration:0.3},'+=0.4')
    .set('#tf-q3',{className:'tf-question exit-up'})
    .set('#tf-q4',{className:'tf-question active'},'+=0.15')

    // Q4: Issues
    .to('#tf-opt-b',{className:'tf-opt selected',duration:0.2,delay:0.8})
    .to('.tf-bar',{width:'80%',duration:0.3},'+=0.3')
    .set('#tf-q4',{className:'tf-question exit-up'})
    .set('#tf-q5',{className:'tf-question active'},'+=0.15')

    // Q5: Likelihood
    .to('#tf-opt-def',{className:'tf-opt selected',duration:0.2,delay:0.8})
    .to('.tf-bar',{width:'100%',duration:0.3},'+=0.3')
    .set('#tf-q5',{className:'tf-question exit-up'})
    .set('#tf-done',{className:'tf-question active'},'+=0.15')

    .to({},{duration:3});
  return tl;
}
ScrollTrigger.create({trigger:'.survey-sim',start:'top 60%',onEnter:()=>runSurveySim()});

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

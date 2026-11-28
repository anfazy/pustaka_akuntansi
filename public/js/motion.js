/* Apple Liquid Glass Motion Engine (iOS 26 / macOS Tahoe) */
(function(w,d){
'use strict';
if(w.LiquidGlassMotion)return;
const r=d.documentElement,redMQ=w.matchMedia&&w.matchMedia('(prefers-reduced-motion: reduce)'),hovMQ=w.matchMedia&&w.matchMedia('(hover: hover) and (pointer: fine)');
const SP_E='cubic-bezier(0.22,1.32,0.36,1)',EX_E='cubic-bezier(0.32,0,0.24,1)';
const PRESETS={snappy:{k:780,c:46,m:1},base:{k:540,c:38,m:1},soft:{k:360,c:32,m:1}};
const flags={motion:1,blurMaterialize:1,specular:1,tilt:1,pressLift:1,morphStretch:1,scrollCondense:1,reveal:1,countUp:1,viewTransitions:1,spotlightInertia:1};
const CSS_V={motion:'--lg-motion-enabled',blurMaterialize:'--lg-blur-scale',specular:'--lg-specular-opacity',tilt:'--lg-tilt-strength',pressLift:'--lg-press-lift',morphStretch:'--lg-morph-stretch',scrollCondense:'--lg-scroll-condense',reveal:'--lg-reveal-enabled',countUp:'--lg-countup-enabled',viewTransitions:'--lg-view-transitions',spotlightInertia:'--lg-spotlight-inertia'};

let tier=(redMQ&&redMQ.matches)?'reduced':(((navigator.hardwareConcurrency||8)<=4||(navigator.deviceMemory||8)<=4||(navigator.connection&&navigator.connection.saveData))?'lite':'full');
r.dataset.motionTier=tier;
const isRed=()=>tier==='reduced'||!flags.motion,isLite=()=>tier==='lite'||isRed(),can3D=()=>!isLite()&&flags.tilt&&Boolean(hovMQ&&hovMQ.matches);
const wc=(el,on)=>el&&el.classList.toggle('lg-animating',Boolean(on));

function setFlag(k,on){
  if(!(k in flags))return;
  flags[k]=on?1:0;
  if(CSS_V[k])r.style.setProperty(CSS_V[k],on?'1':'0');
}

const tasks=new Set(),winF=[];
let raf=0,lastT=0,cont=0;
function loop(now){
  raf=0;
  const raw=lastT?now-lastT:16.67;lastT=now;
  if(cont&&tier==='full'&&raw<=400){
    winF.push(raw>22?1:0);if(winF.length>10)winF.shift();
    if(winF.length===10&&winF.reduce((a,b)=>a+b,0)>=7){tier='lite';r.dataset.motionTier='lite';}
  }
  const dt=Math.min(raw,34)/1000,list=Array.from(tasks);
  for(let i=0;i<list.length;i++)if(list[i].read)list[i].read(dt);
  for(let i=0;i<list.length;i++){const t=list[i];if(t.step&&!t.step(dt)){tasks.delete(t);if(t.done)t.done();}}
  for(let i=0;i<list.length;i++)if(list[i].write)list[i].write(dt);
  if(tasks.size){cont=1;raf=requestAnimationFrame(loop);}else{cont=0;lastT=0;}
}
function enqueue(t){tasks.add(t);if(!raf){cont=0;lastT=performance.now();raf=requestAnimationFrame(loop);}return t;}

function stepPhys(s,tgt,p,dt){
  const n=Math.max(1,Math.ceil(dt/0.008)),h=dt/n;
  for(let i=0;i<n;i++){s.v+=((-p.k*(s.x-tgt)-p.c*s.v)/p.m)*h;s.x+=s.v*h;}
}

function createSpring(init,preset,onUpd,onDone){
  let p=PRESETS[preset]||PRESETS.base,st={x:init,v:0},tgt=init,act=null;
  function spin(){
    if(act)return;
    act=enqueue({
      step(dt){
        if(isRed()){st.x=tgt;st.v=0;return false;}
        stepPhys(st,tgt,p,dt);
        if(Math.abs(tgt-st.x)<0.015&&Math.abs(st.v)<0.08){st.x=tgt;st.v=0;return false;}
        return true;
      },
      write(){onUpd(st.x,st.v);},
      done(){act=null;onUpd(st.x,0);if(onDone)onDone(st.x);}
    });
  }
  return {
    get value(){return st.x;},get velocity(){return st.v;},
    setTarget(n,pr){if(pr&&PRESETS[pr])p=PRESETS[pr];tgt=n;if(isRed()){st.x=n;st.v=0;onUpd(st.x,0);if(onDone)onDone(st.x);return;}spin();},
    setVelocity(v){st.v=v;},
    snap(v){tgt=st.x=v;st.v=0;if(act){tasks.delete(act);act=null;}onUpd(st.x,0);},
    cancel(){if(act){tasks.delete(act);act=null;}}
  };
}

const aMap=new WeakMap();
function killAnim(el){const a=aMap.get(el);if(a){try{a.cancel();}catch(_){}aMap.delete(el);}}
function waapi(el,kf,opts,onEnd){
  killAnim(el);if(!el||!el.animate){if(onEnd)onEnd();return null;}
  wc(el,true);const a=el.animate(kf,opts);aMap.set(el,a);
  a.onfinish=()=>{wc(el,false);if(onEnd)onEnd();a.cancel();aMap.delete(el);};
  return a;
}

function initSpec(){
  if(d.querySelector('script[data-lg-spec]')||!w.HTMLScriptElement||!HTMLScriptElement.supports||!HTMLScriptElement.supports('speculationrules'))return;
  const s=d.createElement('script');s.type='speculationrules';s.setAttribute('data-lg-spec','1');
  s.textContent='{"prefetch":[{"where":{"and":[{"href_matches":"/*"},{"not":{"href_matches":"/admin*"}},{"not":{"href_matches":"/keluar*"}}]},"eagerness":"moderate"}]}';
  d.head.appendChild(s);
}

let scWait=false;
function initScroll(){
  if(w._lgSc)return;w._lgSc=true;let y=w.scrollY||0;
  const hdr=d.getElementById('appHeader'),topE=d.querySelector('.scroll-edge-top'),botE=d.querySelector('.scroll-edge-bottom');
  const writeScroll=()=>{
    scWait=false;
    const p=flags.scrollCondense&&!isRed()?Math.min(1,Math.max(0,y/130)):0;
    w._lgSpP=p;
    if(hdr){hdr.style.transform=p>0.005?`translate3d(0,${(-3*p).toFixed(2)}px,0)`:'';}
    const edgeOp=(0.45+0.55*p).toFixed(2);
    if(topE)topE.style.opacity=edgeOp;if(botE)botE.style.opacity=edgeOp;
  };
  w.addEventListener('scroll',()=>{
    if(scWait)return;scWait=true;
    enqueue({read(){y=w.scrollY||0;},step(){return false;},write:writeScroll});
  },{passive:true});
}

function measureSeg(tr){
  const map=new Map();
  tr.querySelectorAll(':scope>a,:scope>button').forEach((el)=>map.set(el,{left:el.offsetLeft,width:el.offsetWidth}));
  tr._lgRects=map;return map;
}

function morphSegmentThumb(tr,el,anim){
  if(!tr||!el)return;
  const tw=el.offsetWidth,tx=el.offsetLeft;
  if(tw<=0)return;
  let th=tr.querySelector('.segment-thumb');
  if(!th){th=d.createElement('span');th.className='segment-thumb';tr.classList.add('has-thumb');tr.prepend(th);}
  tr._activeSegmentEl=el;
  if(!tr._lgM){
    const st={tw,cw:tw,xS:null,wS:null};
    st.xS=createSpring(tx,'snappy',(x,vx)=>{
      const str=flags.morphStretch&&!isRed()?Math.min(0.24,Math.abs(vx)*0.00025):0;
      const sx=(st.cw/Math.max(1,st.tw))*(1+str),sy=1-str*0.16;
      th.style.transform=`translate3d(${x.toFixed(2)}px,0,0) scaleX(${sx.toFixed(4)}) scaleY(${sy.toFixed(4)})`;
    },()=>wc(th,false));
    st.wS=createSpring(tw,'snappy',(v)=>{st.cw=v;});
    tr._lgM=st;
  }
  const st=tr._lgM;
  if(!anim||isRed()){st.tw=st.cw=tw;th.style.width=tw+'px';st.wS.snap(tw);st.xS.snap(tx);wc(th,false);return;}
  const vw=st.wS.value;st.tw=tw;th.style.width=tw+'px';
  wc(th,true);st.wS.snap(vw);st.wS.setTarget(tw,'snappy');st.xS.setTarget(tx,'snappy');
}

function syncSidebar(anim){
  const nav=d.querySelector('#appSidebar nav'),act=nav&&(nav.querySelector('a.bg-white\\/95')||nav.querySelector('a.font-semibold'));
  if(!nav||!act)return;
  let ind=d.getElementById('sidebarMorphIndicator');
  if(!ind){ind=d.createElement('span');ind.id='sidebarMorphIndicator';nav.classList.add('has-morph-indicator');nav.prepend(ind);}
  const col=r.classList.contains('sidebar-collapsed')||d.body.classList.contains('sidebar-collapsed');
  const th=act.offsetHeight||38;
  const tw=col?38:(act.offsetWidth||nav.clientWidth||200);
  const tx=col?(nav.clientWidth<80?Math.max(0,Math.round((nav.clientWidth-tw)/2)):5):(act.offsetLeft||0);
  const ty=act.offsetTop;
  nav._lgSx=tx;
  if(!nav._lgInd){
    nav._lgInd=createSpring(ty,'snappy',(y,vy)=>{
      const str=flags.morphStretch&&!isRed()?Math.min(0.18,Math.abs(vy)*0.00022):0;
      ind.style.transform=`translate3d(${(nav._lgSx||0).toFixed(1)}px,${y.toFixed(2)}px,0) scale(${(1-str*0.08).toFixed(4)},${(1+str).toFixed(4)})`;
    },()=>wc(ind,false));
  }
  ind.style.opacity='1';ind.style.height=th+'px';ind.style.width=tw+'px';
  if(!anim||isRed()){nav._lgInd.snap(ty);wc(ind,false);}else{wc(ind,true);nav._lgInd.setTarget(ty,'snappy');}
}

function crossfadePanels(all,matchFn,cls){
  let outE=null,inE=null;
  const isGrid=cls==='grid';
  all.forEach((p)=>{
    if(matchFn(p))inE=p;
    else if(p.dataset.lgState==='active'||(!p.dataset.lgState&&!p.classList.contains('hidden')&&!p.classList.contains('is-parked')))outE=p;
    else{killAnim(p);wc(p,false);if(isGrid)p.dataset.lgState='parked';else{p.classList.remove('is-active','is-leaving',cls);p.classList.add('hidden');}}
  });
  if(outE&&outE!==inE){
    if(outE.parentElement)outE.parentElement.classList.add('lg-shelf-stage');
    outE.setAttribute('aria-hidden','true');
    if(!isRed()&&outE.animate){
      if(isGrid)outE.dataset.lgState='leaving';else{outE.classList.remove('is-active');outE.classList.add('is-leaving');}
      waapi(outE,[{opacity:1,transform:'scale(1)'},{opacity:0,transform:isLite()?'scale(1)':'scale(0.98)'}],{duration:70,easing:EX_E,fill:'forwards'},()=>{
        if(isGrid)outE.dataset.lgState='parked';else{outE.classList.remove('is-leaving',cls);outE.classList.add('hidden');}
      });
    }else{
      if(isGrid)outE.dataset.lgState='parked';else{outE.classList.remove('is-active',cls);outE.classList.add('hidden');}
    }
  }
  if(inE){
    if(inE.parentElement)inE.parentElement.classList.add('lg-shelf-stage');
    killAnim(inE);inE.removeAttribute('aria-hidden');
    if(isGrid)inE.dataset.lgState='active';
    else{inE.classList.remove('hidden','is-leaving');inE.classList.add('is-active',cls);}
    if(!isRed()){
      if(isLite()){
        waapi(inE,[{opacity:0},{opacity:1}],{duration:90,easing:'ease-out',fill:'both'});
      }else{
        Array.from(inE.children).slice(0,8).forEach((c,i)=>waapi(c,[{opacity:0,transform:'translate3d(0,6px,0) scale(0.97)'},{opacity:1,transform:'translate3d(0,0,0) scale(1)'}],{duration:170,delay:Math.min(i,7)*15,easing:SP_E,fill:'both'}));
      }
    }
  }
}

function setTabs(tr,btn){
  tr.querySelectorAll('button').forEach((b)=>b.setAttribute('aria-selected',b===btn?'true':'false'));
  morphSegmentThumb(tr,btn,true);
}

function hookTabs(){
  w.moveSegmentThumb=morphSegmentThumb;
  w.switchBerandaShelf=(key,btn)=>{
    const tr=d.getElementById('berandaShelfTabs');if(tr&&btn)setTabs(tr,btn);
    const f=d.querySelector('[data-shelf-panel]');if(f&&f.parentElement)f.parentElement.classList.add('lg-shelf-stage');
    crossfadePanels(d.querySelectorAll('[data-shelf-panel]'),(p)=>p.dataset.shelfPanel===key,'grid');
  };
  w.switchPinjamanTab=(key,btn)=>{
    const tr=d.getElementById('pinjamanTabTrack');if(tr&&btn)setTabs(tr,btn);
    crossfadePanels(d.querySelectorAll('[data-pinjaman-panel]'),(p)=>p.dataset.pinjamanPanel===key,'block');
    try{const u=new URL(w.location.href);u.searchParams.set('tab',key);history.replaceState(history.state,'',u.href);}catch(_){}
  };
}

function initSpotlight(){
  const deck=d.getElementById('spotlightDeck');if(!deck||deck._lgSp)return;deck._lgSp=true;
  const slides=Array.from(deck.querySelectorAll('[data-spotlight-slide]')),dots=Array.from(d.querySelectorAll('[data-spotlight-dot]')),ctr=d.getElementById('spotlightCounter'),N=slides.length;
  if(N<=1)return;
  let idx=0,width=deck.clientWidth||340;
  const relDist=(i)=>{let rel=i-idx,h=Math.floor(N/2);if(rel>h)rel-=N;if(rel<-h)rel+=N;return rel;};
  const spr=createSpring(0,'base',(dx)=>{
    const wPx=Math.max(200,width),pr=dx/wPx;
    for(let i=0;i<N;i++){
      const s=slides[i],rel=relDist(i);
      if(rel<-1||rel>1){s.classList.add('hidden');s.classList.remove('flex');continue;}
      s.classList.remove('hidden');s.classList.add('flex');
      s.style.position=rel===0?'relative':'absolute';s.style.inset=rel===0?'':'0';s.style.pointerEvents=rel===0?'auto':'none';
      const tx=(rel+pr)*100,dist=Math.min(1,Math.abs(rel+pr));
      s.style.transform=`translate3d(${tx.toFixed(2)}%,0,0) scale(${(1-dist*0.08).toFixed(4)})`;
      s.style.opacity=Math.max(0.15,1-dist*0.65).toFixed(3);
    }
  },()=>{
    slides.forEach((s,i)=>{
      wc(s,false);
      if(i!==idx){s.classList.add('hidden');s.classList.remove('flex','is-active');s.style.transform=s.style.opacity='';}
      else{s.classList.add('is-active','flex');s.classList.remove('hidden');s.style.position='relative';s.style.transform='';s.style.opacity='1';s.style.pointerEvents='auto';}
    });
  });

  function syncUI(dir){
    dots.forEach((dot,i)=>dot.setAttribute('aria-current',i===idx?'true':'false'));
    if(ctr){
      ctr.textContent=(idx+1)+' / '+N;
      if(!isRed()&&flags.morphStretch)waapi(ctr,[{transform:`translate3d(${dir*-6}px,0,0) scaleX(1.22)`,opacity:0.7},{transform:'translate3d(0,0,0) scaleX(1)',opacity:1}],{duration:360,easing:SP_E});
    }
  }
  function go(nIdx){
    width=deck.clientWidth||340;const nxt=((nIdx%N)+N)%N,prv=idx;if(nxt===prv&&Math.abs(spr.value)<1)return;
    const dir=nIdx===prv?1:Math.sign(nIdx-prv);idx=nxt;w.currentSpotlightIdx=idx;
    if(isRed()||isLite()){
      spr.snap(0);syncUI(dir);
      if(!isRed()&&slides[idx])waapi(slides[idx],[{opacity:0,transform:`translate3d(${dir*14}px,0,0)`},{opacity:1,transform:'translate3d(0,0,0)'}],{duration:180,easing:'ease-out'});
      if(typeof w.resetSpotlightAuto==='function')w.resetSpotlightAuto();
      return;
    }
    slides.forEach((s,i)=>{if(i===idx||i===prv)wc(s,true);});
    spr.snap(spr.value+dir*width*0.45);spr.setTarget(0,'base');syncUI(dir);
    if(typeof w.resetSpotlightAuto==='function')w.resetSpotlightAuto();
  }
  w.goSpotlight=(i)=>go(i);w.stepSpotlight=(dl)=>go(idx+dl);

  let drag=false,sx=0,lx=0,lt=0,vx=0;
  deck.addEventListener('pointerdown',(e)=>{
    if(!flags.spotlightInertia||isRed()||(e.target&&e.target.closest('button,a')))return;
    drag=true;width=deck.clientWidth||340;sx=lx=e.clientX;lt=performance.now();vx=0;
    spr.cancel();deck.classList.add('is-dragging');slides.forEach((s)=>wc(s,true));
    try{deck.setPointerCapture(e.pointerId);}catch(_){}
  },{passive:true});
  deck.addEventListener('pointermove',(e)=>{
    if(!drag)return;const now=performance.now();vx=(e.clientX-lx)/Math.max(1,now-lt);lx=e.clientX;lt=now;
    let dx=e.clientX-sx;if((idx===0&&dx>0)||(idx===N-1&&dx<0))dx=Math.sign(dx)*(Math.abs(dx)/(1+Math.abs(dx)/110));
    spr.snap(dx);
  },{passive:true});
  const endDrag=()=>{
    if(!drag)return;drag=false;deck.classList.remove('is-dragging');
    const dx=spr.value,proj=dx+vx*160,thr=width*0.24;
    if(proj<-thr&&idx<N-1){idx++;spr.snap(dx+width);spr.setVelocity(vx*1000);spr.setTarget(0,'base');syncUI(1);}
    else if(proj>thr&&idx>0){idx--;spr.snap(dx-width);spr.setVelocity(vx*1000);spr.setTarget(0,'base');syncUI(-1);}
    else{spr.setVelocity(vx*1000);spr.setTarget(0,'snappy');}
    w.currentSpotlightIdx=idx;if(typeof w.resetSpotlightAuto==='function')w.resetSpotlightAuto();
  };
  deck.addEventListener('pointerup',endDrag,{passive:true});deck.addEventListener('pointercancel',endDrag,{passive:true});
}

function getTilt(card){
  if(card._lgT)return card._lgT;
  const sheen=card.querySelector('.lg-specular-sheen');
  const s={rx:0,ry:0,sc:1,ty:0},v={rx:0,ry:0,sc:0,ty:0},tgt={rx:0,ry:0,sc:1,ty:0};
  let tsk=null;
  const apply=()=>{card.style.transform=`perspective(900px) translate3d(0,${s.ty.toFixed(2)}px,0) rotateX(${s.rx.toFixed(2)}deg) rotateY(${s.ry.toFixed(2)}deg) scale(${s.sc.toFixed(4)})`;};
  function spin(){
    if(tsk)return;wc(card,true);card.dataset.lgTiltActive='1';
    tsk=enqueue({
      step(dt){
        if(isRed()){Object.assign(s,tgt);return false;}
        const p=PRESETS.snappy;
        ['rx','ry','sc','ty'].forEach((k)=>{const t={x:s[k],v:v[k]};stepPhys(t,tgt[k],p,dt);s[k]=t.x;v[k]=t.v;});
        return Math.abs(tgt.rx-s.rx)>0.02||Math.abs(v.rx)>0.05||Math.abs(tgt.ry-s.ry)>0.02||Math.abs(v.ry)>0.05||Math.abs(tgt.sc-s.sc)>0.001||Math.abs(tgt.ty-s.ty)>0.02;
      },
      write:apply,
      done(){tsk=null;Object.assign(s,tgt);v.rx=v.ry=v.sc=v.ty=0;if(!tgt.rx&&!tgt.ry&&tgt.sc===1&&!tgt.ty){card.style.transform='';delete card.dataset.lgTiltActive;}else apply();wc(card,false);}
    });
  }
  return(card._lgT={
    move(cx,cy,down){
      const rc=card.getBoundingClientRect();if(!rc.width||!rc.height)return;
      const nx=Math.max(-0.5,Math.min(0.5,(cx-rc.left)/rc.width-0.5)),ny=Math.max(-0.5,Math.min(0.5,(cy-rc.top)/rc.height-0.5));
      tgt.ry=can3D()?nx*7.2:0;tgt.rx=can3D()?-ny*7.2:0;tgt.sc=down?1.032:1.018;tgt.ty=down?-2:-5;
      if(sheen&&!isLite()&&flags.specular){sheen.style.opacity='1';sheen.style.transform=`translate3d(${(nx*rc.width).toFixed(1)}px,${(ny*rc.height).toFixed(1)}px,0)`;}
      spin();
    },
    down(cx,cy){if(cx!==undefined&&can3D())this.move(cx,cy,true);else{tgt.sc=flags.pressLift?1.032:1;tgt.ty=flags.pressLift?-2:0;spin();}},
    reset(){tgt.rx=tgt.ry=tgt.ty=0;tgt.sc=1;if(sheen)sheen.style.opacity='0';spin();}
  });
}

function initSurfaces(){
  if(w._lgSurf)return;w._lgSurf=true;
  d.addEventListener('pointerdown',(e)=>{
    if(!e.target||typeof e.target.closest!=='function')return;
    const btn=e.target.closest('.btn-emerald,.btn-glass,.liquid-chip,.liquid-icon-btn,.btn-yellow,.btn-amber-gold');
    if(btn&&flags.pressLift&&!isRed()){
      const rc=btn.getBoundingClientRect();
      btn.style.setProperty('--tap-x',Math.round(e.clientX-rc.left)+'px');btn.style.setProperty('--tap-y',Math.round(e.clientY-rc.top)+'px');
      btn.classList.add('lg-press-surface','is-pressing');wc(btn,true);
    }
    const card=e.target.closest('.lg-book-card');if(card)getTilt(card).down(e.clientX,e.clientY);
  },{passive:true});
  const end=(e)=>{
    d.querySelectorAll('.lg-press-surface.is-pressing').forEach((el)=>{el.classList.remove('is-pressing');setTimeout(()=>wc(el,false),280);});
    if(e&&e.target&&typeof e.target.closest==='function'){const c=e.target.closest('.lg-book-card');if(c&&c._lgT)c._lgT.reset();}
  };
  d.addEventListener('pointerup',end,{passive:true});d.addEventListener('pointercancel',end,{passive:true});
  let prev=null,evt=null,wait=false;
  d.addEventListener('pointermove',(e)=>{
    if(e.pointerType!=='mouse'||isLite())return;
    evt=e;if(wait)return;wait=true;
    enqueue({step(){return false;},write(){
      wait=false;if(!evt||!evt.target||typeof evt.target.closest!=='function')return;
      const card=evt.target.closest('.lg-book-card');if(prev&&prev!==card&&prev._lgT)prev._lgT.reset();
      prev=card||null;if(card)getTilt(card).move(evt.clientX,evt.clientY,evt.buttons===1);
    }});
  },{passive:true});
}

function setOrigin(pop,trig){
  if(!pop||!trig)return;const p=pop.getBoundingClientRect(),t=trig.getBoundingClientRect();if(!p.width||!p.height)return;
  pop.style.setProperty('--lg-origin-x',Math.round(t.left+t.width/2-p.left)+'px');
  pop.style.setProperty('--lg-origin-y',Math.round(t.top+t.height/2-p.top)+'px');
}

function hookPopovers(){
  const orig=w.positionGlassPopover;
  if(orig&&!w._lgPop){w._lgPop=true;w.positionGlassPopover=(p,t)=>{orig(p,t);requestAnimationFrame(()=>setOrigin(p,t));};}
  if(!w._lgList){
    w._lgList=true;
    d.addEventListener('click',(e)=>{
      const tr=e.target&&e.target.closest&&e.target.closest('[data-listbox-trigger]');
      if(tr){const m=d.querySelector(`[data-listbox-menu="${tr.getAttribute('data-listbox-trigger')}"]`);if(m){m.setAttribute('data-lg-popover','1');requestAnimationFrame(()=>setOrigin(m,tr));}}
    },{passive:true});
  }
  const sh=d.getElementById('catalogMobileSheet');
  if(sh&&!sh._lgDr){
    sh._lgDr=true;const pan=sh.querySelector('[data-sheet-panel]'),hd=pan&&pan.firstElementChild;
    if(pan&&hd){
      let sy=0,dy=0,ly=0,lt=0,vy=0,dr=false;
      hd.addEventListener('pointerdown',(e)=>{dr=true;sy=ly=e.clientY;lt=performance.now();dy=vy=0;pan.style.transition='none';wc(pan,true);},{passive:true});
      hd.addEventListener('pointermove',(e)=>{if(!dr)return;const now=performance.now();vy=(e.clientY-ly)/Math.max(1,now-lt);ly=e.clientY;lt=now;const raw=e.clientY-sy;dy=raw>=0?raw:raw/(1+Math.abs(raw)/80);pan.style.transform=`translate3d(0,${dy.toFixed(1)}px,0)`;},{passive:true});
      const up=()=>{if(!dr)return;dr=false;pan.style.transition='';pan.style.transform='';wc(pan,false);if(dy>110||vy>0.45){const b=sh.querySelector('[data-sheet-close]');if(b)b.click();}};
      hd.addEventListener('pointerup',up,{passive:true});hd.addEventListener('pointercancel',up,{passive:true});
    }
  }
}

function initCountUp(){
  if(!flags.countUp||isRed()||!('IntersectionObserver'in w))return;
  const els=d.querySelectorAll('[data-lg-countup]:not([data-counted])');if(!els.length)return;
  const obs=new IntersectionObserver((ents)=>ents.forEach((en)=>{
    if(!en.isIntersecting)return;const el=en.target;obs.unobserve(el);if(el.dataset.counted)return;el.dataset.counted='1';
    const tgt=parseInt(el.dataset.lgCountup,10);if(!Number.isFinite(tgt)||tgt<=0)return;
    const pre=el.dataset.lgPrefix||'',suf=el.dataset.lgSuffix||'',st=performance.now();
    enqueue({step(){const t=Math.min(1,(performance.now()-st)/320);el.textContent=pre+Math.round(tgt*(1-Math.pow(1-t,3))).toLocaleString('id-ID')+suf;return t<1;}});
  }),{threshold:0.25});
  els.forEach((el)=>obs.observe(el));
}

let pendId=null,actVt=null;
function prepCoverVT(url){
  d.querySelectorAll('[data-lg-vt-cover]').forEach((el)=>{el.style.viewTransitionName='';el.removeAttribute('data-lg-vt-cover');});
  pendId=null;try{const m=new URL(url,w.location.origin).pathname.match(/^\/buku\/(\d+)/);if(m)pendId=m[1];}catch(_){}
  if(pendId){
    const box=d.querySelector(`a[href*="/buku/${pendId}"] [data-cover-box]`)||d.querySelector(`a[href$="/buku/${pendId}"] div.aspect-\\[5\\/7\\]`);
    if(box){box.style.viewTransitionName=`cover-${pendId}`;box.setAttribute('data-lg-vt-cover',pendId);}
  }
}
function stampIncomingCover(){
  if(!pendId)return;
  const el=d.querySelector(`#appMainStage [data-detail-cover="${pendId}"]`);
  if(el){el.style.viewTransitionName=`cover-${pendId}`;el.setAttribute('data-lg-vt-cover',pendId);}
}

// Cross-document MPA View Transitions cover support
w.addEventListener('pageswap',(e)=>{if(e.viewTransition&&e.navigation&&e.navigation.to)prepCoverVT(e.navigation.to.url);});
w.addEventListener('pagereveal',()=>{const m=location.pathname.match(/^\/buku\/(\d+)/);if(m){pendId=m[1];stampIncomingCover();}});

w.lgToast=(msg,type)=>{
  let host=d.getElementById('lgToastHost');if(!host){host=d.createElement('div');host.id='lgToastHost';host.setAttribute('aria-live','polite');d.body.appendChild(host);}
  const item=d.createElement('div');item.className='lg-toast';
  item.innerHTML=`<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="${type==='error'?'#e11d48':'#059669'}" stroke-width="2.4"><path class="lg-check-stroke" stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg><span></span>`;
  item.querySelector('span').textContent=msg;host.appendChild(item);
  if(!isRed())waapi(item,[{opacity:0,transform:'translate3d(0,-14px,0) scale(0.94)',filter:(flags.blurMaterialize&&!isLite())?'blur(4px)':'none'},{opacity:1,transform:'translate3d(0,0,0) scale(1)',filter:'blur(0px)'}],{duration:210,easing:SP_E,fill:'both'});
  setTimeout(()=>{
    if(!isRed()&&item.animate)waapi(item,[{opacity:1,transform:'translate3d(0,0,0) scale(1)'},{opacity:0,transform:'translate3d(0,-8px,0) scale(0.95)'}],{duration:105,easing:EX_E,fill:'forwards'},()=>item.remove());
    else item.remove();
  },2200);
};
w.lgShakeField=(el)=>{if(el&&!isRed()){el.classList.remove('lg-shake');void el.offsetWidth;el.classList.add('lg-shake');}};

function initPage(){
  hookTabs();initScroll();initSurfaces();initSpotlight();hookPopovers();initCountUp();syncSidebar(true);
  const svg=d.querySelector('#notifMenuWrapper button svg');
  if(svg&&d.querySelector('#notifMenuWrapper span')&&!isRed()){svg.classList.remove('lg-bell-ring');void svg.getBoundingClientRect();svg.classList.add('lg-bell-ring');}
  d.querySelectorAll('.segment-track').forEach((tr)=>{measureSeg(tr);const act=tr.querySelector('a.bg-white,button[aria-selected="true"],button.is-active');if(act)morphSegmentThumb(tr,act,false);});
}

d.addEventListener('DOMContentLoaded',()=>{initSpec();initPage();});
w.addEventListener('resize',()=>{d.querySelectorAll('.segment-track').forEach((tr)=>{measureSeg(tr);const act=tr.querySelector('a.bg-white,button[aria-selected="true"],button.is-active');if(act)morphSegmentThumb(tr,act,false);});syncSidebar(false);},{passive:true});

w.LiquidGlassMotion={
  get tier(){return tier;},setTier(t){tier=t;r.dataset.motionTier=t;},
  flags,setFlag,PRESETS,createSpring,initPage,morphSegmentThumb,syncSidebarIndicator:syncSidebar,
  prepareSharedCoverTransition:prepCoverVT,applyIncomingCoverName:stampIncomingCover,
  setActiveVt(vt){actVt=vt;},getActiveVt(){return actVt;},
  setNavProgress(p){r.style.setProperty('--lg-nav-progress',String(p));},
  toast:w.lgToast,shakeField:w.lgShakeField
};
})(window,document);

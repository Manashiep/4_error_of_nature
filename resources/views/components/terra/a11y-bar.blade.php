{{-- F24 taille du texte · F23 contraste élevé · F21 lecteur d'écran (aria-live). Choix mémorisé dans le navigateur. --}}
<div class="a11y" role="region" aria-label="Options d'accessibilité"
  x-data="{
    fs: 1, hc: false, msg: '',
    init(){ try{ this.fs=parseFloat(localStorage.getItem('tn-fs'))||1; this.hc=localStorage.getItem('tn-hc')==='1' }catch(e){} this.apply() },
    apply(){ const r=document.documentElement; r.style.setProperty('--fs',this.fs); r.classList.toggle('hc',this.hc);
      try{ localStorage.setItem('tn-fs',this.fs); localStorage.setItem('tn-hc',this.hc?'1':'0') }catch(e){} },
    size(d){ this.fs=d===0?1:Math.min(1.5,Math.max(.875,+(this.fs+d).toFixed(3))); this.apply(); this.msg='Taille du texte : '+Math.round(this.fs*100)+' %' },
    contrast(){ this.hc=!this.hc; this.apply(); this.msg=this.hc?'Contraste élevé activé':'Contraste élevé désactivé' }
  }">
  <span id="a11y-t">Affichage</span>
  <button type="button" @click="size(-.125)" aria-label="Réduire la taille du texte">A−</button>
  <button type="button" @click="size(0)" aria-label="Taille du texte par défaut">A</button>
  <button type="button" @click="size(.125)" aria-label="Agrandir la taille du texte">A+</button>
  <button type="button" @click="contrast()" :aria-pressed="hc.toString()">Contraste élevé</button>
  <span class="sr-only" role="status" aria-live="polite" x-text="msg"></span>
</div>

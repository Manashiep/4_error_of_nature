/* Accessibilité : taille du texte + contraste élevé, mémorisés dans le navigateur */
const $ = (s) => document.querySelector(s);
const root = document.documentElement, say = $('#a11y-msg');
let fs = 1, hc = false;
try { fs = parseFloat(localStorage.getItem('tn-fs')) || 1; hc = localStorage.getItem('tn-hc') === '1'; } catch (e) {}

const apply = (msg) => {
    root.style.setProperty('--fs', fs);
    root.classList.toggle('hc', hc);
    $('#a11y-hc')?.setAttribute('aria-pressed', hc);
    try { localStorage.setItem('tn-fs', fs); localStorage.setItem('tn-hc', hc ? '1' : '0'); } catch (e) {}
    if (msg && say) say.textContent = msg;
};
const size = (d) => {
    fs = d === 0 ? 1 : Math.min(1.5, Math.max(0.875, +(fs + d).toFixed(3)));
    apply('Taille du texte : ' + Math.round(fs * 100) + ' %');
};
$('#a11y-minus')?.addEventListener('click', () => size(-0.125));
$('#a11y-reset')?.addEventListener('click', () => size(0));
$('#a11y-plus')?.addEventListener('click', () => size(0.125));
$('#a11y-hc')?.addEventListener('click', () => { hc = !hc; apply(hc ? 'Contraste élevé activé' : 'Contraste élevé désactivé'); });
apply();

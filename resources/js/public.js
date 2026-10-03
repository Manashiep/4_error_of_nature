/* Nova Terra : fond (skyline néon) + options d'accessibilité */
const $ = (s, r = document) => r.querySelector(s);

/* 1. Fond généré, aléatoire fixé => identique sur toutes les pages */
const sc = $('#scene');
if (sc) {
    let a = 7;
    const R = () => (a = (a * 16807) % 2147483647) / 2147483647;
    const I = (lo, hi) => Math.floor(lo + R() * (hi - lo + 1));
    let s = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice"><defs><linearGradient id="s" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#050b1d"/><stop offset=".55" stop-color="#2b0f52"/><stop offset=".85" stop-color="#8a1f78"/><stop offset="1" stop-color="#ff5aa8"/></linearGradient><radialGradient id="p" cx=".35" cy=".35" r=".8"><stop offset="0" stop-color="#ffd0ec"/><stop offset=".45" stop-color="#e0449a"/><stop offset="1" stop-color="#3a0f5e"/></radialGradient><radialGradient id="g"><stop offset="0" stop-color="#ff2d8a" stop-opacity=".5"/><stop offset="1" stop-color="#ff2d8a" stop-opacity="0"/></radialGradient></defs><rect width="1600" height="900" fill="url(#s)"/>';
    for (let i = 0; i < 110; i++) s += `<circle cx="${I(0, 1600)}" cy="${I(0, 500)}" r="${[0.6, 0.9, 1.3][I(0, 2)]}" fill="#fff" opacity="${I(35, 90) / 100}"/>`;
    s += '<circle cx="1180" cy="250" r="300" fill="url(#g)"/><circle cx="1180" cy="250" r="150" fill="url(#p)"/><ellipse cx="1180" cy="250" rx="260" ry="34" fill="none" stroke="#ffd0ec" stroke-opacity=".6" stroke-width="3" transform="rotate(-14 1180 250)"/><circle cx="330" cy="170" r="38" fill="#7ad7ff" opacity=".55"/><circle cx="318" cy="160" r="38" fill="#050b1d" opacity=".5"/>';
    [['#1a1b4d', 420, ['#ff7ad0', '#7ad7ff']], ['#0a0e2e', 500, ['#ff2d8a', '#38e8ff', '#ffd36e']]].forEach(([f, mh, c]) => {
        for (let x = -20; x < 1620;) {
            const w = I(45, 105), t = 720 - I(180, mh - 120 + 120 * (f === '#0a0e2e' ? 0.5 : 0));
            s += `<rect x="${x}" y="${t}" width="${w}" height="${900 - t}" fill="${f}"/>`;
            for (let k = 0; k < 7; k++) s += `<rect x="${x + I(4, w - 6)}" y="${t + I(4, 150)}" width="3" height="5" fill="${c[I(0, c.length - 1)]}" opacity="${I(40, 95) / 100}"/>`;
            x += w;
        }
    });
    s += '<rect y="760" width="1600" height="140" fill="#07102e"/>';
    [775, 795, 825, 865].forEach((y) => (s += `<line x1="0" y1="${y}" x2="1600" y2="${y}" stroke="#38e8ff" stroke-opacity=".25"/>`));
    for (let i = 0; i <= 26; i++) s += `<line x1="${80 + i * 60}" y1="760" x2="${800 + (i * 60 - 720) * 4}" y2="900" stroke="#38e8ff" stroke-opacity=".35"/>`;
    sc.innerHTML = s + '<rect y="755" width="1600" height="5" fill="#38e8ff" opacity=".7"/></svg>';
}

/* 2. Accessibilité : taille du texte (F24) + contraste élevé (F23), mémorisés */
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

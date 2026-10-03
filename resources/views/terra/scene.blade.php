{{-- Fond : ciel violet, planète à anneau, deux couches de skyline, sol en grille néon (généré, déterministe) --}}
@php
  mt_srand(7);
  $layers=[['#1a1b4d',[420,300],['#ff7ad0','#7ad7ff']],['#0a0e2e',[500,360],['#ff2d8a','#38e8ff','#ffd36e']]];
@endphp
<div class="scene" aria-hidden="true">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice">
<defs>
<linearGradient id="s" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#050b1d"/><stop offset=".55" stop-color="#2b0f52"/><stop offset=".85" stop-color="#8a1f78"/><stop offset="1" stop-color="#ff5aa8"/></linearGradient>
<radialGradient id="p" cx=".35" cy=".35" r=".8"><stop offset="0" stop-color="#ffd0ec"/><stop offset=".45" stop-color="#e0449a"/><stop offset="1" stop-color="#3a0f5e"/></radialGradient>
<radialGradient id="g"><stop offset="0" stop-color="#ff2d8a" stop-opacity=".5"/><stop offset="1" stop-color="#ff2d8a" stop-opacity="0"/></radialGradient>
</defs>
<rect width="1600" height="900" fill="url(#s)"/>
@for($i=0;$i<110;$i++)<circle cx="{{ mt_rand(0,1600) }}" cy="{{ mt_rand(0,500) }}" r="{{ [0.6,0.9,1.3][mt_rand(0,2)] }}" fill="#fff" opacity="{{ mt_rand(35,90)/100 }}"/>@endfor
<circle cx="1180" cy="250" r="300" fill="url(#g)"/><circle cx="1180" cy="250" r="150" fill="url(#p)"/>
<ellipse cx="1180" cy="250" rx="260" ry="34" fill="none" stroke="#ffd0ec" stroke-opacity=".6" stroke-width="3" transform="rotate(-14 1180 250)"/>
<circle cx="330" cy="170" r="38" fill="#7ad7ff" opacity=".55"/><circle cx="318" cy="160" r="38" fill="#050b1d" opacity=".5"/>
@foreach($layers as [$fill,$h,$cols])
  @php $x=-20; @endphp
  @while($x<1620)
    @php $w=mt_rand(45,105); $top=720-mt_rand(180,$h[0]); @endphp
    <rect x="{{ $x }}" y="{{ $top }}" width="{{ $w }}" height="{{ 900-$top }}" fill="{{ $fill }}"/>
    @for($k=0;$k<7;$k++)<rect x="{{ $x+mt_rand(4,$w-6) }}" y="{{ $top+mt_rand(4,150) }}" width="3" height="5" fill="{{ $cols[mt_rand(0,count($cols)-1)] }}" opacity="{{ mt_rand(40,95)/100 }}"/>@endfor
    @php $x+=$w; @endphp
  @endwhile
@endforeach
<rect y="760" width="1600" height="140" fill="#07102e"/>
@foreach([775,795,825,865] as $y)<line x1="0" y1="{{ $y }}" x2="1600" y2="{{ $y }}" stroke="#38e8ff" stroke-opacity=".25"/>@endforeach
@for($i=0;$i<=26;$i++)<line x1="{{ 80+$i*60 }}" y1="760" x2="{{ 800+($i*60-720)*4 }}" y2="900" stroke="#38e8ff" stroke-opacity=".35"/>@endfor
<rect y="755" width="1600" height="5" fill="#38e8ff" opacity=".7"/>
</svg>
</div>

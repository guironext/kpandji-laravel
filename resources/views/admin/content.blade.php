@extends('admin.layout')
@section('title', 'Contenu du site')
@section('actions')<a href="{{ route('home') }}" target="_blank" class="text-xs text-white/60 hover:text-white">Voir le site ↗</a>@endsection
@section('content')
@php
  $in = 'mt-2 w-full rounded-lg border border-white/15 bg-black px-4 py-3 text-sm text-white outline-none focus:border-kp-gold';
  $lb = 'block text-xs uppercase tracking-[0.2em] text-white/60';
@endphp
<style>.kp-prose h2{font-size:1.3rem;font-weight:600}.kp-prose h3{font-size:1.1rem;font-weight:600}.kp-prose ul{list-style:disc;padding-left:1.2em}.kp-prose a{color:#c9a962;text-decoration:underline}</style>
<div class="mb-6 flex flex-wrap gap-2">
  @foreach($tabs as $k => $label)
    <a href="{{ route('admin.content', ['tab' => $k]) }}" class="rounded-full px-5 py-2 text-xs font-semibold uppercase tracking-[0.18em] transition {{ $tab === $k ? 'bg-kp-gold text-black' : 'border border-white/15 text-white/70 hover:border-kp-gold hover:text-white' }}">{{ $label }}</a>
  @endforeach
  <a href="{{ route('admin.pages.index') }}" class="rounded-full border border-dashed border-white/20 px-5 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-white/60 hover:border-kp-gold hover:text-white">Pages →</a>
</div>
<p class="mb-6 max-w-3xl text-sm text-white/50">Les valeurs affichées sont celles du site. Cochez « Rétablir » pour revenir au texte d'origine. Liens acceptés : <code class="text-kp-gold">/showroom</code>, <code class="text-kp-gold">#offres</code>, <code class="text-kp-gold">https://…</code>.
  @if($tab === 'promo') Pourcentage, dates et activation de la promotion : <a class="text-kp-gold underline" href="{{ route('admin.promo') }}">page Promotion</a>.@endif</p>

<form method="POST" enctype="multipart/form-data" action="{{ route('admin.content.save', $tab) }}" class="space-y-5">
  @csrf @method('PUT')
  @foreach($fields as $key => $f)
    @php
      [$g, $type, $label] = $f; $k = str_replace('.', '__', $key); $val = $values[$key]; $isDefault = $val === $f[3];
    @endphp
    <section class="rounded-2xl border border-white/10 bg-white/[0.02] p-5">
      <div class="flex items-start justify-between gap-4">
        <p class="{{ $lb }}">{{ $label }} <span class="ml-2 normal-case tracking-normal text-white/25">{{ $key }}</span></p>
        @unless($isDefault)<label class="flex shrink-0 items-center gap-2 text-[11px] text-white/45"><input type="checkbox" name="reset[{{ $k }}]" value="1" class="accent-kp-gold"> Rétablir l'original</label>@endunless
      </div>

      @if($type === 'text' || $type === 'url')
        <input name="c[{{ $k }}]" value="{{ old("c.$k", $val) }}" class="{{ $in }}" @if($type === 'url') placeholder="/page, #ancre ou https://…" @endif>
      @elseif($type === 'textarea')
        <textarea name="c[{{ $k }}]" rows="3" class="{{ $in }}">{{ old("c.$k", $val) }}</textarea>
      @elseif($type === 'richtext')
        @include('admin.partials.richtext', ['name' => "c[$k]", 'value' => old("c.$k", $val)])
      @elseif($type === 'image' || $type === 'video')
        <div x-data="{ preview: @js(cms_asset($val)) }" class="mt-3 grid gap-4 md:grid-cols-[260px_1fr]">
          <div class="flex aspect-video items-center justify-center overflow-hidden rounded-xl border border-white/10 bg-black">
            @if($type === 'image')<img :src="preview" class="max-h-full max-w-full object-contain" alt="">
            @else<video :src="preview" class="h-full w-full object-cover" muted autoplay loop playsinline></video>@endif
          </div>
          <div class="space-y-3 text-sm">
            <p class="text-white/45">Actuel : <code class="text-white/70">{{ $val }}</code></p>
            <label class="{{ $lb }}">Téléverser un nouveau fichier ({{ $type === 'image' ? 'JPG, PNG, WebP, GIF — 8 Mo max' : 'MP4/WebM — 200 Mo max' }})
              <input type="file" name="f[{{ $k }}]" accept="{{ $type === 'image' ? 'image/jpeg,image/png,image/webp,image/gif' : 'video/mp4,video/webm' }}" @change="const fl = $event.target.files[0]; if (fl) preview = URL.createObjectURL(fl)" class="mt-2 block w-full text-xs text-white/60 file:mr-3 file:rounded-full file:border-0 file:bg-kp-gold file:px-4 file:py-2 file:text-xs file:font-semibold file:text-black"></label>
            <label class="{{ $lb }}">…ou chemin / URL (laisser vide pour conserver le fichier actuel)
              <input name="c[{{ $k }}]" value="" placeholder="{{ $val }}" class="{{ $in }}"></label>
          </div>
        </div>
      @elseif($type === 'list')
        @php($cols = $f[4] ?? ['label' => 'Texte'])
        <div x-data="{ rows: @js(array_values((array) $val)), blank: @js(array_map(fn () => '', $cols)) }" class="mt-3">
          <input type="hidden" name="c[{{ $k }}]" :value="JSON.stringify(rows)">
          <template x-for="(row, i) in rows" :key="i">
            <div class="mb-2 flex flex-wrap items-end gap-2 rounded-xl border border-white/10 bg-black/40 p-3">
              @foreach($cols as $col => $colLabel)
                <label class="min-w-[90px] {{ in_array($col, ['code', 'n', 'visible']) ? 'w-24' : 'flex-1' }} text-[10px] uppercase tracking-[0.15em] text-white/40">{{ $colLabel }}
                  @if($col === 'visible')
                    <select x-model="row.{{ $col }}" class="{{ $in }} !mt-1 !py-2"><option value="1">Oui</option><option value="0">Non</option></select>
                  @else
                    <input x-model="row.{{ $col }}" class="{{ $in }} !mt-1 !py-2">
                  @endif
                </label>
              @endforeach
              <div class="flex gap-1 pb-1">
                <button type="button" @click="if(i>0){ const r = rows.splice(i,1)[0]; rows.splice(i-1,0,r) }" class="rounded-lg border border-white/15 px-2.5 py-2 text-xs hover:border-kp-gold" title="Monter">↑</button>
                <button type="button" @click="if(i<rows.length-1){ const r = rows.splice(i,1)[0]; rows.splice(i+1,0,r) }" class="rounded-lg border border-white/15 px-2.5 py-2 text-xs hover:border-kp-gold" title="Descendre">↓</button>
                <button type="button" @click="rows.splice(i,1)" class="rounded-lg border border-white/15 px-2.5 py-2 text-xs text-red-400 hover:border-red-500" title="Supprimer">✕</button>
              </div>
            </div>
          </template>
          <button type="button" @click="rows.push(Object.assign({}, blank, {{ isset($cols['visible']) ? "{visible:'1'}" : '{}' }}))" class="mt-1 rounded-full border border-dashed border-white/25 px-4 py-2 text-xs text-white/70 hover:border-kp-gold hover:text-white">+ Ajouter</button>
        </div>
      @endif
    </section>
  @endforeach
  <div class="sticky bottom-0 -mx-5 flex justify-end border-t border-white/10 bg-kp-black/95 px-5 py-4 backdrop-blur lg:-mx-10 lg:px-10">
    <button class="rounded-full bg-kp-gold px-8 py-3 text-xs font-bold uppercase tracking-[0.2em] text-black hover:bg-white">Enregistrer « {{ $tabs[$tab] }} »</button>
  </div>
</form>
@endsection

@extends('layouts.app')
@section('title','Accueil')
@section('content')
@include('partials.hero')
@if(config('promo.active', true))
@include('partials.promo')
@endif
@include('partials.parallax')
ut                                    vcv
<section class="mx-auto mt-24 max-w-[1400px] px-5 md:px-10">
  <div class="relative overflow-hidden rounded-3xl border border-kp-gold/30 bg-gradient-to-r from-[#1a160d] via-[#0e0e0e] to-black p-10 md:p-16">
    <div class="pointer-events-none absolute -right-20 -top-20 size-80 rounded-full bg-kp-gold/20 blur-3xl"></div>
    <div class="relative flex flex-col items-start justify-between gap-8 md:flex-row md:items-center">
      <div><p class="text-[11px] font-bold uppercase tracking-[0.3em] text-kp-gold">@content('home.banner_eyebrow')</p><h2 class="mt-3 font-serif text-3xl font-semibold text-white md:text-5xl">@content('home.banner_title')</h2></div>
      <a href="{{ cms_url(content('home.banner_url')) }}" class="rounded-full bg-kp-gold px-8 py-4 text-xs font-bold uppercase tracking-[0.22em] text-black transition hover:bg-white">@content('home.banner_cta')</a>
    </div>
  </div>
</section>
@endsection

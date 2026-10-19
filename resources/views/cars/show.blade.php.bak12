@extends('layouts.app')
@section('title',$car->name)
@section('content')
<section class="relative h-[60vh] overflow-hidden">
  <img src="{{ asset($car->hero_image ?? $car->image) }}" class="absolute inset-0 w-full h-full object-cover opacity-60" alt="{{ $car->name }}">
  <div class="absolute inset-0 bg-gradient-to-t from-kp-black via-kp-black/40 to-transparent"></div>
  <div class="relative max-w-7xl mx-auto px-6 h-full flex flex-col justify-end pb-12">
    <p class="text-kp-gold uppercase tracking-[0.3em] text-sm">{{ $car->category }}</p>
    <h1 class="font-serif text-5xl md:text-7xl font-semibold text-white">{{ $car->name }}</h1>
  </div>
</section>
<section class="max-w-7xl mx-auto px-6 mt-12 grid lg:grid-cols-3 gap-12">
  <div class="lg:col-span-2">
    <img src="{{ asset($car->image) }}" alt="{{ $car->name }}" class="w-full bg-kp-dark p-6 object-contain max-h-[420px]">
    <p class="mt-8 text-lg leading-relaxed text-kp-sand/90">{{ $car->description }}</p>
    <div class="grid sm:grid-cols-2 gap-4 mt-8">@foreach($car->highlights ?? [] as $h)<div class="border-l-2 border-kp-gold bg-kp-dark px-4 py-3 text-white">{{ $h }}</div>@endforeach</div>
    @if(!empty($car->versions))
    <h2 class="text-3xl font-extrabold text-white mt-14 mb-6">@content('model.versions_title')</h2>
    <div class="overflow-x-auto rounded-2xl border border-white/10">
      <table class="w-full min-w-[640px] text-sm">
        <thead class="bg-white/[0.04] text-left text-[10px] uppercase tracking-[0.2em] text-kp-gold">@php($th = collect(content('model.th'))->pluck('label')->values()->all())<tr>@foreach(['Version','Motorisation','Boîte','Transmission','Prix promo TTC','Entretien','Pack campagne'] as $i => $d)<th class="p-4{{ $i >= 4 ? ' text-right' : '' }}">{{ $th[$i] ?? $d }}</th>@endforeach</tr></thead>
        <tbody>@foreach($car->versions as $v)<tr class="border-t border-white/10"><td class="p-4 text-white">{{ $v['version'] }}</td><td class="p-4 text-white/70">{{ $v['motorisation'] }}</td><td class="p-4 text-white/70">{{ $v['boite'] }}</td><td class="p-4 text-white/70">{{ $v['transmission'] }}</td><td class="p-4 text-right font-semibold text-red-400">{{ \App\Models\Car::fcfa($v['promo_price']) }}</td><td class="p-4 text-right text-white/70">{{ \App\Models\Car::fcfa($v['maintenance_cost']) }}</td><td class="p-4 text-right text-white">{{ \App\Models\Car::fcfa($v['campaign_price']) }}</td></tr>@endforeach</tbody>
      </table>
    </div>
    @if($car->warranty)<p class="mt-3 text-xs text-white/50">{{ content('model.warranty') }} {{ $car->warranty }}</p>@endif
    @endif
    <h2 class="text-3xl font-extrabold text-white mt-14 mb-6">@content('model.specs_title')</h2>
    @foreach($car->specs ?? [] as $group => $rows)
      <h3 class="text-kp-gold uppercase tracking-wider text-sm mt-8 mb-2">{{ $group }}</h3>
      <table class="w-full text-sm">@foreach($rows as $k => $v)<tr class="border-b border-white/10"><td class="py-3 pr-4 text-kp-silver/70 w-1/3">{{ $k }}</td><td class="py-3 text-white">{{ $v }}</td></tr>@endforeach</table>
    @endforeach
  </div>
  <aside class="h-fit overflow-hidden rounded-2xl border border-kp-gold/40 bg-gradient-to-b from-[#17140c] to-[#0b0b0b] shadow-[0_30px_80px_-30px_rgba(201,169,98,0.45)] lg:sticky lg:top-32">
    <div class="bg-kp-gold px-6 py-2.5 text-center text-[10px] font-bold uppercase tracking-[0.3em] text-black">@content('model.box_header')</div>
    <div class="p-8">
      <p class="font-serif text-3xl font-semibold text-white">{{ $car->name }}</p>
      <p class="text-[11px] uppercase tracking-[0.25em] text-white/45">{{ $car->category }}</p>
      <div class="mt-6">@include('partials.price')</div>
      @if(config('promo.ends_at'))<p class="mt-4 text-xs uppercase tracking-[0.2em] text-white/50">{{ content('model.until') }} {{ \Carbon\Carbon::parse(config('promo.ends_at'))->format('d/m/Y') }}</p>@endif
      <p class="mt-4 text-sm text-white/60">{{ str_replace(':name', $car->name, content('model.box_text')) }}</p>
      <a href="{{ route('essai', ['modele' => $car->slug]) }}" class="mt-6 block rounded-full bg-kp-gold py-4 text-center text-xs font-bold uppercase tracking-[0.22em] text-black transition hover:bg-white">@content('model.cta')</a>
      @if($car->brochure)<a href="{{ asset($car->brochure) }}" target="_blank" class="mt-3 block rounded-full border border-white/20 py-3 text-center text-[11px] uppercase tracking-[0.2em] text-white/80 transition hover:border-kp-gold hover:text-kp-gold">@content('model.pdf')</a>@endif
    </div>
  </aside>

</section>
<section class="max-w-7xl mx-auto px-6 mt-24"><h2 class="text-2xl font-extrabold text-white mb-6">@content('model.others')</h2>
  <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">@foreach($others as $car) @include('partials.card') @endforeach</div></section>
@endsection

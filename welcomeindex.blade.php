@extends('layouts.app')

@section('title', 'Domov')

@section('content')

<!-- Banner -->
<section class="banner">
    <img src="{{ asset('images/Electrical-Project-Manager.jpg') }}" alt="Background Image" class="banner-bg">
    <div class="banner-content">
        <h1 class="fade-in" style="background-image: url('{{ asset('images/Electrical-Project-Manager.jpg') }}')">EMP s.r.o.</h1>
        <p class="fade-in">Inovatívne riešenia, moderné dizajny a profesionalita.</p>
        <a href="/services" class="fade-in btn">Viac</a>
        <a href="/orders" class="fade-in btn">Ponuka</a>
    </div>
</section>

<section class="section">
    <h2>O nás</h2>
    <p>Ponúkame projektové riešenia TEMP.</p>
    <a href="/kontakt" class="fade-in btn">Kontakt</a>
</section>

@endsection

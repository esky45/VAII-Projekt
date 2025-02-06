@extends('layouts.app')

@section('title', 'Služby')

@section('content')


<section class="services-titles">
    <h1>Naše Služby</h1>
    <div class="services-titles-grid">
        <div class="services-title-item">
            <h2>Elektroinštalácie - Kompletné elektroinštalácie pre nové a existujúce budovy.</h2>
        </div>
        <div class="services-title-item">
            <h2>Rozvody elektriny - Návrh a inštalácia efektívnych rozvodov elektrickej energie.</h2>
        </div>
        <div class="services-title-item">
            <h2>Opravy a Údržba - Rýchle a spoľahlivé opravy elektrických zariadení a ich pravidelná údržba.</h2>
        </div>
        <div class="services-title-item">
            <h2>Softwarové riešenia - Návrh a vývoj softwéru.</h2>
        </div>
    </div>
</section>

<section class="services">
    <h1>Elektroinštalácie</h1>
    <div class="services-grid-container">
        <div class="services-grid-item full-width">
            <div class="icon">
                <img src="{{ asset('bootstrap-icons-1.11.1/device-ssd-fill.svg') }}" alt="Service 1 Icon">
            </div>
            <div class="content">
                <h2>Kablovanie</h2>
                <p>TEMP text. kompletna realizacia od projektu po reviziu Bytovych a premyselnich objektov</p>
            </div>
        </div>
        <div class="services-grid-item">
            <div class="icon">
                <img src="{{ asset('bootstrap-icons-1.11.1/clock.svg') }}" alt="Service 2 Icon">
            </div>
            <div class="content">
                <h2>Dátove siete</h2>
                <p>Navrh a vyhotovenie Ethernet/optickej siete</p>
            </div>
        </div>
    </div>
</section>

@endsection

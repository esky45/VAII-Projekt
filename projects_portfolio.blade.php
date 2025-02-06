@extends('layouts.app')

@section('title', 'Projekty')

@section('content')

<section class="portfolio">
    <h1>Naše projekty</h1>
    <div class="grid-container">
        <div class="grid-item">
            <img src="{{ asset('images/Kramáre-skryna-abcelektroinstalacie-1024x683.jpg') }}" alt="Projekt Elektro inštalácia Kramáre">
            <div class="content">
                <h2>Projekt Elektro inštalácia Kramáre</h2>
                <p>Popis temp..</p>
            </div>
        </div>
        <div class="grid-item">
            <img src="{{ asset('images/NTC-Structured-Cabling-Blog-Header.png') }}" alt="Káblovanie pred a po">
            <div class="content">
                <h2>Káblovanie pred a po</h2>
                <p>Popis temp..</p>
            </div>
        </div>
        <div class="grid-item">
            <img src="{{ asset('images/download.jpg') }}" alt="Project 3 Thumbnail">
            <div class="content">
                <h2>Project Title TEMP 1</h2>
                <p>Popis temp.</p>
            </div>
        </div>
        <div class="grid-item">
            <img src="{{ asset('images/img07.jpg') }}" alt="Project 4 Thumbnail">
            <div class="content">
                <h2>Project Title TEMP 2</h2>
                <p>Popis temp..</p>
            </div>
        </div>
    </div>
</section>
@endsection

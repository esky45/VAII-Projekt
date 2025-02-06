@extends('layouts.app')

@section('title', 'Kontakt - EMP')

@section('content')
    <div class="px-4 py-5 my-5 text-center">
        <img class="d-block mx-auto mb-4" src="{{ asset('bootstrap-icons-1.11.1/mailbox-flag.svg') }}" alt="" width="72" height="57">
        <h1 class="display-5 fw-bold text-body-emphasis">Kontakt</h1>
        <div class="col-lg-6 mx-auto">
            <p class="lead mb-4">
                <section id="contact">
                    <h2>Kontaktujte Nás</h2>
                    <p>EMP<br>
                        Adresa: ADRESA Žilina <br>
                        Tel: +09 456 789 <br>
                        Email: info@emp.sk
                    </p>
                </section>
            </p>
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <button type="button" class="btn btn-primary btn-lg px-4 gap-3">Send Email</button>
              
            </div>
        </div>
    </div>
@endsection

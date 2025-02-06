@extends('layouts.app')

@section('title', 'Tester')

@section('content')
@vite(['resources/css/tester_style.css', 'resources/js/app.js'])

@if(auth()->check() && auth()->user()->role != 'admin')
<section class="my-5 pb-5">
    <div class="container text-center mt-5 py-5">
        <h3 style="color: red">!!!You don't have permission to view this Page!!!</h3>
    </div>
</section>
@endif

@if(auth()->check() && auth()->user()->role === 'admin')
<section class="my-5 pb-5">
    <div class="container text-center mt-5 py-5">
        <h3>Tester Interpreter</h3>
        <hr class="mx-auto">
    </div>

    <!-- Buttons Section -->
    <div class="row mx-auto container mb-4">
        <form id="login_form">
            <div class="alert alert-danger" role="alert" id="div-error-1" style="display: none"></div>

            <a class="btn btn-primary btn-sm" href="{{ route('tester.viewTest') }}">View Test</a>
            <a class="btn btn-primary btn-sm" href="{{ route('tester.output') }}">Output</a>
            <a class="btn btn-primary btn-sm" href="{{ route('tester.runTest') }}">Run Test</a>
        </form>
    </div>

    <div class="row">
        <!-- File Management Section (Left Side) -->
        <div class="col-md-6">
            <h4>File Management</h4>

            <!-- Upload Form -->
            <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                @csrf
                <input type="file" name="file" class="form-control form-control-sm" required>
                <button type="submit" class="btn btn-success btn-sm mt-2">Upload File</button>
            </form>

            <!-- File List -->
            <div class="mt-4">
                <h5>Uploaded Files</h5>
                <ul class="list-group">
                    @foreach($files as $file)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ asset('storage/' . $file->path) }}" target="_blank">{{ $file->name }}</a>
                            <form action="{{ route('files.destroy', $file->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Terminal Section (Right Side) -->
        <div class="col-md-6">
            <h4>Terminal</h4>
            <h4> php test.php --recursive </h4>
            <form id="terminal-form" method="POST" action="{{ route('tester.terminal.execute') }}">
                @csrf
                <div class="mb-3">
                    <input type="text" class="form-control form-control-sm" id="command" name="command" required>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Execute Command</button>
            </form>

            <!-- Terminal Output -->
            <div class="mt-3">
                <h5>Command Output:</h5>
                <pre id="terminal-output"></pre>
            </div>
        </div>
    </div>
</section>
@endif
@endsection

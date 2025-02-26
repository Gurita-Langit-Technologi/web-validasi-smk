@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Forgot Password</h2>
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <form action="{{ route('forgot-password') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Kirim Link Reset</button>
        </form>
    </div>
@endsection

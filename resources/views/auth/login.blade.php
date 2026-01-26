@extends('layouts.app')

@section('content')

<teste-component token_csrf="{{ @csrf_token() }}"></teste-component>
@endsection

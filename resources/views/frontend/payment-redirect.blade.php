@extends('layouts.frontend')

@section('title', 'Redirecting to Payment')

@section('content')
<section class="status-page">
    <h1>Redirecting to secure payment</h1>
    <p>Please wait while we send you to the payment gateway.</p>
    <form id="paymentRedirectForm" method="POST" action="{{ $data['paymentUrl'] }}">
        @foreach($data['formData'] as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <button class="btn-primary" type="submit">Continue to payment</button>
    </form>
</section>
<script>
    document.getElementById('paymentRedirectForm')?.submit();
</script>
@endsection

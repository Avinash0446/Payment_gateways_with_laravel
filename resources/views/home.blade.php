@extends('layouts.app')

@section('content')
    <div class="container py-4">
        {{-- Dashboard Card --}}
        <div class="row justify-content-center mb-4">
            <div class="col-md-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">{{ __('Dashboard') }}</h4>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <h5 class="mb-3">{{ __('You are logged in!') }}</h5>
                        {{-- User Role Section --}}
                        @if(auth()->user()->getRoleNames()->first())
                            <div class="alert alert-info">
                                <strong>Hi {{ auth()->user()->name }}</strong>
                                <br>Role:<span class="badge bg-dark">
                                    {{ auth()->user()->getRoleNames()->first() }}
                                </span>
                            </div>
                            {{-- Admin Upload Section --}}
                            @role('admin')
                            <div class="card mt-4 border-0 shadow-sm">
                                <div class="card-header bg-dark text-white">
                                    CSV Upload
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('admin.csv.upload') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="csv" class="form-label"> Upload CSV File</label>
                                            <input id="csv" type="file" class="form-control @error('csv') is-invalid @enderror"
                                                name="csv" required>
                                            @error('csv')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-success">
                                            Upload CSV
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endrole
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Section --}}
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Payment Gateway</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('payment.process') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input id="amount" type="number" class="form-control @error('payment') is-invalid @enderror"
                                    name="amount" placeholder="Enter amount" value="{{ old('amount') }}" required>
                                @error('amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="payment_method" class="form-label">
                                    Payment Method
                                </label>
                                <select id="payment_method" class="form-select" name="payment_method">
                                    <option value="stripe">Stripe</option>
                                    <option value="paypal"> PayPal</option>
                                    <option value="razorpay">RazorPay</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Process Payment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const success = @json(session('success'));
        const error = @json(session('error'));
        console.log("xxxxxxxxxx",success);
        console.log("yyyyyyyyyyyyyyyyyyyyyyy",error);

        if (success) {
            iziToast.success({
                title: 'Success',
                message: @json(session('success')),
                position: 'topRight',
                timeout: 3000
            });
        }

        if (error) {
            iziToast.error({
                title: 'error',
                message: @json(session('error')),
                position: 'topRight',
                timeout: 3000
            });
        }
    </script>
@endpush
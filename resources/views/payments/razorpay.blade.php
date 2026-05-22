<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
var options = {
    key: "{{ config('services.razorpay.key') }}",
    amount: "{{ $response['amount'] }}",
    currency: "{{ $response['currency'] }}",
    order_id: "{{ $response['order_id'] }}",

    handler: function(response){
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('payment.success',['gateway'=>'razorpay']) }}";

        form.innerHTML = `
            @csrf
            <input type="hidden"
            name="razorpay_payment_id"
            value="${response.razorpay_payment_id}">

            <input type="hidden"
            name="razorpay_order_id"
            value="${response.razorpay_order_id}">

            <input type="hidden"
            name="razorpay_signature"
            value="${response.razorpay_signature}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
};

new Razorpay(options).open();
</script>
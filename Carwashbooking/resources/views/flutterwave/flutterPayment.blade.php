<html>
    <body onload="document.forms['member_signup'].submit()">
        <form method="POST" name="member_signup" action="https://checkout.flutterwave.com/v3/hosted/pay">
            <input type="hidden" name="public_key" value="{{ App\PaymentSetting::first()->flutterwave_public_key }}" />
            <input type="hidden" name="customer[email]" value="{{ $order->user->email }}" />
            <input type="hidden" name="customer[phone_number]" value="{{ $order->user->phone }}" />
            <input type="hidden" name="customer[name]" value="{{ $order->user->name }}" />
            <input type="hidden" name="tx_ref" value="{{ $order->appointment_id }}" />
            <input type="hidden" name="amount" value="{{ $order->amount }}" />
            <input type="hidden" name="currency" value="{{ App\Setting::first()->currency }}" />
            {{-- <input type="hidden" name="meta[token]" value="20" /> --}}
            <input type="hidden" name="redirect_url" value="{{ url('transction_verify/'.$order->id) }}" />
        </form>
    </body>
</html>

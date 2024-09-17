{{-- <html>
    <body onload="document.forms['member_signup'].submit()">
        <form method="POST" name="member_signup" action="{{ route('pay') }}" accept-charset="UTF-8"
class="form-horizontal" role="form">
@csrf
<div class="row" style="margin-bottom:40px;">
    <div class="col-md-8 col-md-offset-2">
        <p>
            <div>
                Lagos Eyo Print Tee Shirt
                ₦ 2,950
            </div>
        </p>
        <input type="hidden" name="email" value="otemuyiwa@gmail.com">
        <input type="hidden" name="orderID" value="345">
        <input type="hidden" name="amount" value="800">
        <input type="hidden" name="quantity" value="3">
        <input type="hidden" name="currency" value="ZAR">
        <input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}">
        <input type="hidden" name="callback_url" value="{{ url('transctionPaystack') }}">
        <p>
            <button class="btn btn-success btn-lg btn-block" type="submit" value="Pay Now!">
                <i class="fa fa-plus-circle fa-lg"></i> Pay Now!
            </button>
        </p>
    </div>
</div>
</form>
</body>

</html> --}}

<html>
    <head>
        <script src="{{ url('assets/js/jquery.min.js')}}"></script>
        <script src="https://js.paystack.co/v1/inline.js"></script>
        <script>
            $(document).ready(function()
            {
                function payWithPaystack()
                {
                    alert("hi");
                    var handler = PaystackPop.setup({
                    key: 'sk_test_1b647b96eec331f4c65d4d2f6dc626777f27a649',
                    email: 'customer@email.com',
                    currency: 'ZAR',
                    amount: 10000,
                    ref: ''+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a
                    callback: function(response)
                    {
                    alert('success. transaction ref is ' + response.reference);
                    },
                    onClose: function(){
                    alert('window closed');
                    }
                    });
                    handler.openIframe();
                    }
            });
        </script>
    </head>
    <body>
        <button type="button" onload="payWithPaystack()"> Pay </button>
    </body>
</html>

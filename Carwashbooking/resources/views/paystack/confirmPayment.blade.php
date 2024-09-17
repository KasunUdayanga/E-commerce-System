<html>
    <body onload="document.forms['confirm_payment'].submit()">
        <form name="confirm_payment" action="{{ $result->data->authorization_url }}">
            <input type="hidden" name="callback_url" value="{{ url('transctionPaystack') }}">
        </form>
    </body>
</html>

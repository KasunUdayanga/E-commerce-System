{{-- <!DOCTYPE html>
<html >
<head>
    <meta charset="utf-8">
    <meta name="csrf-token">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
        *
        {
            margin: 0;
        }

        body {
        background-color: rgb(213, 221, 222);
        color: #222;
        font-family: "Open Sans", Helvetica, Arial, sans-serif;
        }

        .modal {
        position: relative;
        background-color: #fff;
        box-sizing: border-box;
        width: 90%;
        max-width: 460px;
        margin: 0 auto;
        margin-top: 100px;
        border-radius: 4px;
        padding: 105px 38px 20px 38px;
        text-align: center;
        box-shadow: 0px 1px 5px 0px rgba(0,0,0,0.25);
        }

        h1 {
        font-size: 37px;
        }

        .points {
        color: #999;
        font-size: 18px;
        }

        hr {
        border: none;
        height: 1px;
        background-color: rgb(221, 221, 221);
        margin: 20px auto;
        }

        .progress {
        margin-top: 20px;
        margin-bottom: 27px;

        }
        .progress rect {
            fill:#8BC34A;
        }

        #close-modal {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 11px;
        height: 11px;
        stroke: #222;
        cursor: pointer;
        }

        #success-icon {
        position: absolute;
        width: 110px;
        height: 110px;

        left: 50%;
        margin-left: -55px;
        top: -15%;
        background-color: #8BC34A;
        border-radius: 50%;
        box-sizing: border-box;
        border: solid 5px white;
        box-shadow:1px 0px 5px 2px rgba(160, 153, 153, 0.47);


        }
        .btn-primary{
        background: #8bc34a;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 19px;
            margin-top: 20px;
        }

        #success-icon div {
            position: absolute;
            top: 34%;
            left: 30%;
            transform: rotate(-45deg);
            border-bottom: solid 4px white;
            border-left: solid 4px white;
            height: 15%;
            width: 33%;
        }
</style>
</head>
<body >
        <div class="modal">
            <div id="success-icon">
                <div></div>
            </div>
            <h1><strong>{{__('Success!')}}</strong></h1>
            <svg class="progress" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 100 4.5">
                <rect height="4.5" width="100" rx="2" ry="2" />
            </svg>
            <p class="points">{{__('Payment Confirm')}}</p>
        </div>
</body>

<script src="{{url('js/jquery.min.js')}}"></script>
<script src="{{url('js/custom.js')}}"></script>
</html> --}}

{{-- <html>
  <head>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
  </head>
    <style>
      body {
        text-align: center;
        padding: 40px 0;
        background: #EBF0F5;
      }
        h1 {
          color: #88B04B;
          font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
          font-weight: 900;
          font-size: 40px;
          margin-bottom: 10px;
        }
        p {
          color: #404F5E;
          font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
          font-size:20px;
          margin: 0;
        }
      i {
        color: #9ABC66;
        font-size: 100px;
        line-height: 200px;
        margin-left:-15px;
      }
      .card {
        background: white;
        padding: 60px;
        border-radius: 4px;
        box-shadow: 0 2px 3px #C8D0D8;
        display: inline-block;
        margin: 0 auto;
      }
    </style>
    <body>
      <div class="card">
      <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
        <i class="checkmark">✓</i>
      </div>
        <h1>{{__('Success')}}</h1>
        <p>{{__('payment Successfully done..!!')}}</p>
      </div>
    </body>
</html> --}}

<html>
    <head>
        <style>
            @import url('https://fonts.googleapis.com/css?family=Raleway:300,600&display=swap');
            body {
                font-family: 'Raleway', sans-serif;
                letter-spacing: 0.075em;
                line-height: 1.5rem;
                font-weight: 300;
           }
            h2 {
                font-weight: 600;
                margin: 2rem 0 0;
           }
            .center {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, calc(-50% - 3.5rem));
                display: flex;
           }
            .card {
                position: relative;
                margin: 2rem;
                width: 16rem;
                text-align: center;
                padding: 4rem;
                color: white;
                border-radius: 0.25rem;
           }
            .card:after {
                content: '';
                top: 0;
                right: 20%;
                bottom: 0;
                left: 0;
                position: absolute;
                background: linear-gradient(-90deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0) 20%);
           }
            .card .icon {
                position: relative;
                width: 4rem;
                height: 4rem;
                border-radius: 50%;
                border: 1px solid white;
                margin: 0 auto;
           }
            .card .icon:after, .card .icon:before {
                position: absolute;
                top: 1rem;
                left: 50%;
                content: '';
                width: 2rem;
                height: 0.75rem;
                border: 1px solid white;
           }
            .card .button {
                position: absolute;
                top: 1rem;
                right: 1rem;
                bottom: -3.5rem;
                left: 1rem;
                background: rgba(255, 255, 255, 0.6);
                text-transform: uppercase;
                z-index: -1;
                padding-top: 20.5rem;
                color: #666;
                border-radius: 0.25rem;
                cursor: pointer;
                transition: transform 250ms cubic-bezier(0.25, 1, 0.75, 2);
                box-shadow: 0 0 4rem 0 rgba(0, 0, 0, 0.1), 0 4rem 4rem -3rem rgba(0, 0, 0, 0.1);
                backdrop-filter: blur(1rem);
           }
            .card .button:hover {
                transform: translateY(-0.5rem);
           }
            .success {
                background: linear-gradient(-15deg, rgba(12, 208, 208, 0.75), rgba(32, 211, 98, 0.75));
           }
            .success .icon:after {
                transform: translate(-50%, 50%) rotate(-45deg);
                border-width: 0 0 2px 2px;
           }
            .success .icon:before {
                content: none;
           }
            .error {
                background: linear-gradient(-15deg, rgba(241, 39, 17, 0.75), rgba(245, 175, 25, 0.75));
           }
            .error .icon:before, .error .icon:after {
                top: 50%;
                transform: translate(-50%, -50%) rotate(45deg);
                height: 0rem;
                border-width: 0 0 2px 0;
           }
            .error .icon:after {
                transform: translate(-50%, -50%) rotate(-45deg);
           }
            .background {
                position: fixed;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                background: linear-gradient(0deg, #f3f3f3, white);
           }
            /* .background:after {
                content: '';
                position: absolute;
                height: 300%;
                width: 20rem;
                top: 50%;
                left: 50%;
                background: linear-gradient(0deg, #fff, #dfdfdf);
                transform: translate(-50%, -50%) rotate(-135deg);
           } */
        </style>

    </head>
    <body>
        <div class="background"></div>
        <div class="center">
            @if ($msg == 'success')
            <div class="card success">
              <div class="icon"></div>
              <h2>Success!</h2>
              <p>Payment is successfully done.</p>
            </div>
            @endif
            @if ($msg == 'fail')
                <div class="card error">
                    <div class="icon"></div>
                    <p>Something went wrong! Please try again.</p>
                </div>
            @endif
        </div>
    </body>
</html>

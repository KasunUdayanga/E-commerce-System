<html>
    <head>
        <style>
            body {
	 display: flex;
	 justify-content: center;
	 align-items: center;
	 background: #EBF0F5;
}
 .container {
	 background-color: white;
	 height: 440px;
	 width: 275px;
	 display: flex;
	 justify-content: center;
	 align-items: center;
	 box-shadow: 0 2px 10px 10px rgba(0, 0, 0, 0.05);
}
 .circle, .circle-border {
	 width: 60px;
	 height: 60px;
	 border-radius: 50%;
}
 .circle {
	 z-index: 1;
	 position: relative;
	 background: white;
	 transform: scale(1);
	 animation: success-anim 700ms ease;
}
 .circle-border {
	 z-index: 0;
	 position: absolute;
	 transform: scale(1.1);
	 animation: circle-anim 400ms ease;
	 background: #f86;
}
 @keyframes success-anim {
	 0% {
		 transform: scale(0);
	}
	 30% {
		 transform: scale(0);
	}
	 100% {
		 transform: scale(1);
	}
}
 @keyframes circle-anim {
	 from {
		 transform: scale(0);
	}
	 to {
		 transform: scale(1.1);
	}
}
 .error::before, .error::after {
	 content: "";
	 display: block;
	 height: 4px;
	 background: #f86;
	 position: absolute;
}
 .error::before {
	 width: 40px;
	 top: 48%;
	 left: 16%;
	 transform: rotateZ(50deg);
}
 .error::after {
	 width: 40px;
	 top: 48%;
	 left: 16%;
	 transform: rotateZ(-50deg);
}

        </style>
    </head>
    <body>
        <div class="container">
            <div class="circle-border"></div>
            <div class="circle">
                <div class="error"></div>
            </div>
            {{-- <div class="div">
                <h3>Something went wrong..!!</h3>
            </div> --}}
        </div>
    </body>
</html>

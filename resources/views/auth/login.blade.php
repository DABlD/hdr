<html>
<head>
    <title>{{ env("APP_NAME") . " | " . "Login" }}</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
	{{-- <link rel="stylesheet" href="{{ asset('css/auth/util.css') }}"> --}}
	{{-- <link rel="stylesheet" href="{{ asset('css/auth/main.css') }}"> --}}
	<link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: url('{{ asset('images/BG.png') }}') no-repeat center center fixed;
            background-size: cover;
        }

        img{
        	margin: auto;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-blue-100">
    <div class="flex flex-col md:flex-row w-full max-w-8xl">
        <div class="w-full md:w-1/2 flex flex-col items-center justify-center text-center p-8 hidden md:block">
            <img alt="App Logo" class="mb-4" height="100" src="{{ asset('images/HDC App.png') }}" width="200"/>

            <h1 class="text-6xl text-red-600 mb-2">
            	Health Data 
            	<br>
            	Collection Web App
            </h1>

            <p class="text-lg text-gray-700 mb-4">By</p>
            <img alt="OneHealth Network Logo" class="mb-4" height="50" src="{{ asset('images/OHN LOGO.png') }}" width="200"/>
            <p class="text-lg text-gray-700 mb-8">
            	Pre Employment Examination and Annual Physical 
            	<br>
            	Checkup Data Report Application.
            </p>
            <p class="text-sm text-gray-700">Want To Become A OHN Member? Sign In to</p>
            <a class="text-sm text-blue-500" href="https://membership.onehealthnetwork.com.ph/">https://membership.onehealthnetwork.com.ph/</a>
        </div>
        <div class="w-full md:w-1/2 flex items-center justify-center">
            <div class="bg-white p-8 rounded-lg shadow-lg w-96">

            	<br>
                <h2 class="text-2xl font-bold text-red-600 mb-4 text-center">Welcome to OHN / HDC</h2>
            	<br>
                <p class="text-lg text-gray-700 mb-4 text-center">Login</p>
                <form method="POST" action="{{ route('login'); }}">
                	@csrf
                    <div class="mb-4">
                        {{-- <label class="block text-gray-700" for="username">Username</label> --}}
                        <input class="w-full px-3 py-2 border rounded-lg" name="username" placeholder="Username" type="text"/>
                    </div>
                    <div class="mb-4">
                        {{-- <label class="block text-gray-700" for="password">Password</label> --}}
                        <input class="w-full px-3 py-2 border rounded-lg" name="password" placeholder="Password" type="password"/>
                    </div>

                    <br>
                    <div class="mb-4 text-right">
                        <a class="text-sm text-gray-500" href="#" onclick="forgotpassword()">Forgot Your Password?</a>
                    </div>
                    <button class="w-full bg-red-600 text-white py-2 rounded-lg" type="submit">Login</button>
                </form>

                <br><br><br>
                <div class="mt-4 text-center">
                    {{-- <p class="text-sm text-gray-700">New User? Click Here to <a class="font-bold text-red-600" href="#">REGISTER</a></p> --}}
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/auth/tilt.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/auth/main.js') }}"></script>
    <script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
		@if($errors->all())
			Swal.fire({
				icon: 'error',
                html: `
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br/>
                    @endforeach
                `,
			});
		@endif

        function forgotpassword(){
            Swal.fire({
                text: "Contact admin to reset your password."
            })
        }
	</script>
</body>
</html>
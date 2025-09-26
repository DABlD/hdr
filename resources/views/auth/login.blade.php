<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{{ env("APP_NAME") . " | " . "Login" }}</title>

  <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">

  <style>
    body {
      background: url('{{ asset('images/bg.png') }}') no-repeat center center fixed !important;
      background-size: cover !important;
    }
  </style>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; background: linear-gradient(to right, #4facfe, #00f2fe); color: white; min-height: 100vh; display: flex; flex-direction: column;">

  <!-- Header (Centered with logo left of title) -->
  <div style="padding: 60px 50px 40px; display: flex; align-items: center; justify-content: center; gap: 30px;">
    <img src="{{ asset('images/hdr_logo_white.png') }}" alt="Logo" style="width: 100px; height: 100px; filter: drop-shadow(2px 2px 5px rgba(0,0,0,0.6));">
    <div>
      <h1 style="margin: 0; font-size: 70.4px; text-shadow: 2px 2px 6px rgba(0,0,0,0.5);">Health Data Repository</h1>
      <p style="text-align: center; margin: 10px 0 0; font-size: 22px; text-shadow: 1px 1px 4px rgba(0,0,0,0.4);">A Cohesive Medical Data Management Web Application.</p>
    </div>
  </div>

  <!-- Main Content -->
  <div style="flex: 1; display: flex; flex-direction: row; justify-content: center; align-items: flex-start; padding: 30px 50px 140px; gap: 80px; flex-wrap: wrap;">

    <!-- Column 1 (empty) -->
    <div style="flex: 1;"></div>

    <!-- Column 2: We Provide Section -->
    <div style="flex: 1; min-width: 360px;">
      <h2 style="font-size: 28.6px; margin-bottom: 40px; text-shadow: 2px 2px 5px rgba(0,0,0,0.5); text-align: center;">We Provide</h2>

      <div style="display: flex; align-items: center; margin-bottom: 24px; font-size: 30px; text-shadow: 1px 1px 4px rgba(0,0,0,0.5);">
        <img src="{{ asset('images/ape_icon.png') }}" style="margin-right: 15px; width: 80px; height: 80px; filter: drop-shadow(2px 2px 6px rgba(0,0,0,0.5));">
        <span>Annual Physical Examination</span>
      </div>

      <div style="display: flex; align-items: center; margin-bottom: 24px; font-size: 30px; text-shadow: 1px 1px 4px rgba(0,0,0,0.5);">
        <img src="{{ asset('images/ppe_icon.png') }}" style="margin-right: 15px; width: 80px; height: 80px; filter: drop-shadow(2px 2px 6px rgba(0,0,0,0.5));">
        <span>Pre Employment Examination</span>
      </div>

      <div style="display: flex; align-items: center; font-size: 30px; text-shadow: 1px 1px 4px rgba(0,0,0,0.5);">
        <img src="{{ asset('images/ecu_icon.png') }}" style="margin-right: 15px; width: 80px; height: 80px; filter: drop-shadow(2px 2px 6px rgba(0,0,0,0.5));">
        <span>Executive Check Up</span>
      </div>
    </div>

    <!-- Column 3: Login Form (scaled to 0.8×) -->
    <div style="flex: 1; min-width: 420px; width: 500px;">
      <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 6px 16px rgba(0,0,0,0.25); color: black; text-align: center; width: 280px;">
        <img src="{{ asset('images/hdr_logo.png') }}" alt="HDR Logo" style="margin-bottom: 20px; width: 60px;">
        <h2 style="margin: 0; font-size: 19.2px;">Welcome To HDR</h2>
        <p style="margin: 0 0 20px; font-size: 12.8px;">Login to your Account</p>

        <form method="POST" action="{{ route('login'); }}">
            @csrf
          <div style="text-align: left; margin-bottom: 10px;">
            <label style="font-size: 12.8px;">Username</label><br>
            <input type="text" name="username" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 12.8px;">
          </div>

          <div style="text-align: left; margin-bottom: 20px;">
            <label style="font-size: 12.8px;">Password</label><br>
            <input type="password" name="password" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 12.8px;">
          </div>

          <div style="text-align: right; margin-bottom: 10px;">
            <a href="#" style="font-size: 12px; color: #007bff; text-decoration: none;" onclick="forgotpassword()">Forgot Password?</a>
          </div>

          <button type="submit" style="width: 100%; background: #e53935; color: white; padding: 14px; border: none; border-radius: 6px; font-size: 19.2px; cursor: pointer;">Login</button>
        </form>
      </div>
    </div>

<!-- Footer (scaled to 0.8×) -->
<div style="background: #222; padding: 16px 16px; text-align: center; font-size: 12.8px; color: #ccc; position: fixed; bottom: 0; width: 100%;">
  <div>2025 Health Data Repository Web App. Medical Data Collection Platform.</div>
  <div>
    APE / PPE / ECU. Powered By <strong>OneHealth Network</strong> |
    Visit OHN at: <a href="https://onehealthnetwork.com.ph/" target="_blank" style="color: #4fc3f7;">https://onehealthnetwork.com.ph/</a>
  </div>
</div>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/sweetalert2.min.js') }}"></script>

<script >
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

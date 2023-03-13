<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />
    {{-- <link href="{{ asset('vendor/mdi/font/css/materialdesignicons.css') }}" rel="stylesheet" /> --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet"> --}}
    {{-- <script src="{{ mix('js/app.js') }}" defer></script> --}}
    <script src="{{ mix('js/app.js') }}" defer></script>
  </head>
  <body>
    {{-- <div id="app"> --}}
    @routes
      @inertia
    {{-- </div> --}}
   <!--Start of Tawk.to Script-->
    {{-- <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/608a744e62662a09efc33c1a/1f4edclc6';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script> --}}
    <!--End of Tawk.to Script-->
  </body>
</html>

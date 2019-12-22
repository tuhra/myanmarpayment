<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" type="text/css" href="{{asset('css/he.css')}}">
</head>
<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '624650578052506');
  fbq('track', 'TelenorHePage');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=624650578052506&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
<body>

<div class="loading" id="loadingDiv" style="display:none;">
    <div class="lds-ring">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 855.94 855.94" class="symbol"><defs><style>.aa13f5da-0f7a-4408-a3d8-ac3ee1e21d49{fill:#8b00f0;}</style></defs><title>Asset 1</title><g id="13e94170-5d2f-4dfc-8ac0-3df80c10ed07" data-name="Layer 2"><g id="cdae90da-7881-405f-b69b-eb174248b458" data-name="Layer 1"><path class="aa13f5da-0f7a-4408-a3d8-ac3ee1e21d49" d="M428,665.36c-130.9,0-237.39-106.49-237.39-237.39S297.07,190.58,428,190.58,665.36,297.07,665.36,428,558.87,665.36,428,665.36Zm0-370c-73.13,0-132.62,59.49-132.62,132.62S354.84,560.6,428,560.6,560.6,501.1,560.6,428,501.1,295.35,428,295.35Z"/><path class="aa13f5da-0f7a-4408-a3d8-ac3ee1e21d49" d="M428,855.94c-114.23,0-221.67-44.54-302.56-125.42S0,542.19,0,428C0,192,192,0,428,0,522.38,0,611.9,30.14,686.86,87.16c0,0,26.43,22.06,35.34,31-22.29,20.06-64.64,53.49-82.47,66.87-4.46-4.46-16.29-14.47-16.29-14.47A320.3,320.3,0,0,0,428,104.76c-178.21,0-323.2,145-323.21,323.21A323.46,323.46,0,0,0,428,751.18c174.72,0,317.5-139.38,323-312.79l-121,62.45-45.29-98.93,160.81-77.09,1.09-.5A74.88,74.88,0,0,1,851.32,380c3.07,18.09,4.62,34.23,4.62,48C855.94,664,664,855.94,428,855.94Z"/></g></g></svg>
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>
	
    <input type="hidden" name="url" id="url" data-url="{{url('/telenor/hesubscribe') }}">
    <script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/he.js')}}"></script>
    
</body>
</html>


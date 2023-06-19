<!DOCTYPE html>
<html>
<head>
    <title> تم تسجيل مطعم جديد </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>
<h1>{{ $details['title'] }}</h1>
<h3> الأسم : {{ $details['name'] }}</h3>
<h3> الهاتف : <a target="_blank" href="https://api.whatsapp.com/send?phone={{ $details['phone'] }}">
        واتساب
        <i style="font-size:24px" class="fa">&#xf232;</i>
    </a>
</h3>
<h3> الهاتف : 
    <a href="tel:{{$details['phone']}}">
        أتصال
        <i class="fa fa-phone"></i>
    </a>
</h3>
<h3> الأيميل : {{ $details['email'] }}</h3>
<h3> الباقة : {{ $details['package'] }}</h3>
<h3> الدولة : {{ $details['country'] }}</h3>



<p>Thank you</p>
</body>
</html>

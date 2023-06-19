<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>  الزميع </h2>
<div>
    <p>
        <span> السلام عليكم ورحمه الله  وبركاته : </span><br>
        {{ $contact->name  }}
    </p><br>
    <p>
        <span> رسالتك : </span><br>
        {{ $contact->message }}
    </p><br>
    <p>
        <span> رد الأدراه علي رسالتك : </span><br>
        {{ $contact->reply }}
    </p><br>
</div>
</body>
</html>

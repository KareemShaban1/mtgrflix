<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> متجر فليكس </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .container{
            text-align: center;
        }

        .logo {
            width: 100px;
        }

        .whatsapp-btn {
            background-color: #25d366;
            color: white;
            border-radius: 25px;
            padding: 15px 30px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .whatsapp-btn i {
            margin-right: 10px;
        }

       
    </style>
</head>
<body>

    <div class="container">
        <img src="{{ asset('frontend/assets/image') }}/maintain.png" alt="صورة" class="img-fluid mb-4" style="max-width: 300px;">
        <br>
        <img src="{{ asset('frontend/assets/image') }}/logo.avif" alt="اللوجو" class="logo mb-4">
        <p class="mb-4" style="font-size: 20px; color: #333; direction: rtl;">اهلا عميلنا العزيز نعتذر على الأزعاج حالياً يوجد صيانة بموقعنا لذلك نتشرف بخدمتك على الواتساب⬇</p>
        <a href="https://wa.me/+966551200896" class="whatsapp-btn" target="_blank">
            <i class="fab fa-whatsapp"></i> اضغط هنا للتواصل عبر واتساب
        </a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

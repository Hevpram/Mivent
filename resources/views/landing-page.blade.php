<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Landing Page</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav class=" flex justify-between px-[99px] py-[12px]">
        <img src="/assets/logo.png" alt="" class=" w-[164px] h-[27px]">
        <div class=" flex gap-10">
            <button>Beranda</button>
            <button>Jelajahi Event</button>
            <button>Event Diikuti</button>
            <button>FAQ</button>
        </div>
        <button class=" w-[130px] h-[43px] border border-[#7C9CBF] rounded-[6px] py-[8px] text-[#7C9CBF] font-bold font-Poppins">Login</button>
    </nav>
</body>
</html>
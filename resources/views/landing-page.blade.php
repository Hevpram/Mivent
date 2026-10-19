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
    <x-navbar/>
    <div class=" flex mr-[90px] ml-[98px] mt-[80px]" >
        <div> 
            <h1 class="w-[641px] justify-start text-indigo-300 text-6xl font-bold mb-[20px] text-gradient-mivent" >Semua Event dalam Satu Tempat</h1>
            <p class="self-stretch justify-start text-black text-xl font-normal mb-[20px]">Temukan seminar sains, kompetisi riset, olimpiade matematika, workshop laboratorium, dan kepanitiaan ormawa dengan perolehan poin SKP maksimal.</p>
            <form action="/jelajahi-event.blade.php" class=" justify-start items-center gap-2.5 bg-white rounded-[10px] shadow-[0px_0px_8px_0px_rgba(64,103,159,0.75)] inline-flex px-[24.5px] py-2">
                <div class=" gap-[16px] flex ">
                    <svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg" class=" gap-[16px]">
                    <path d="M10.7255 21.4509C7.72783 21.4509 5.19112 20.4125 3.11533 18.3356C1.03955 16.2587 0.00110092 13.722 8.73054e-07 10.7255C-0.00109917 7.72893 1.03735 5.19222 3.11533 3.11533C5.19332 1.03844 7.73003 0 10.7255 0C13.7209 0 16.2582 1.03844 18.3372 3.11533C20.4163 5.19222 21.4542 7.72893 21.4509 10.7255C21.4509 11.9355 21.2584 13.0768 20.8734 14.1494C20.4884 15.2219 19.9659 16.1707 19.3058 16.9957L28.5462 26.2361C28.8487 26.5386 29 26.9237 29 27.3912C29 27.8587 28.8487 28.2437 28.5462 28.5462C28.2437 28.8487 27.8587 29 27.3912 29C26.9237 29 26.5386 28.8487 26.2361 28.5462L16.9957 19.3058C16.1707 19.9659 15.2219 20.4884 14.1494 20.8734C13.0768 21.2584 11.9355 21.4509 10.7255 21.4509ZM10.7255 18.1508C12.7881 18.1508 14.5415 17.4292 15.9859 15.9859C17.4303 14.5426 18.1519 12.7892 18.1508 10.7255C18.1497 8.66177 17.4281 6.90885 15.9859 5.46669C14.5437 4.02452 12.7903 3.30234 10.7255 3.30014C8.66067 3.29794 6.90775 4.02012 5.46669 5.46669C4.02562 6.91325 3.30344 8.66617 3.30014 10.7255C3.29684 12.7848 4.01902 14.5382 5.46669 15.9859C6.91435 17.4336 8.66727 18.1552 10.7255 18.1508Z" fill="#40679F"/>
                    </svg>
            
                    <input type="text" id="fname" name="fname" value="Cari kepanitiaan, seminar, dll" class=" justify-start text-gray-400 text-sm font-light">
                    <p>|</p>
                </div>
                <div class=" flex gap-[28px]">
                    <select name="Kategori" id="">
                        <option value="">Semua Kategori</option>
                        <option value="">Seminar</option>
                        <option value="">Pengabdian</option>
                        <option value="">Perlombaan</option>
                        <option value="">PKKMB</option>
                        <option value="">Lainnya</option>
                    </select>
                    <p>|</p>
                    <button class="size- px-4 py-1 bg-slate-400 rounded-[5px] shadow-[0px_0px_8px_0px_rgba(64,103,159,0.75)] inline-flex justify-center items-center gap-2.5 justify-start text-white text-base font-bold">Cari Event</button>
                </div>
            </form>
            <div class=" flex gap-[20px]">
                <div class=" px-4 py-2.5 bg-white rounded-2xl shadow-[0px_0px_8px_0px_rgba(64,103,159,0.50)] inline-flex  justify-center items-center gap-2.5 mt-[20px]">
                    <svg width="19" height="14" viewBox="0 0 19 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.4411 0.47784C18.7851 0.796631 18.9859 1.23629 18.9993 1.70015C19.0127 2.16402 18.8376 2.6141 18.5125 2.95146L8.39482 13.4527C8.23084 13.6226 8.03379 13.7585 7.8153 13.8525C7.59682 13.9465 7.36134 13.9966 7.12277 13.9998C6.8842 14.0031 6.64738 13.9595 6.42631 13.8715C6.20523 13.7835 6.00439 13.653 5.83564 13.4877L0.479203 8.23707C0.163816 7.90529 -0.00788317 7.46646 0.00027817 7.01304C0.00843951 6.55962 0.195824 6.127 0.522955 5.80634C0.850085 5.48567 1.29142 5.30199 1.75398 5.29399C2.21654 5.28599 2.66421 5.45429 3.00268 5.76345L7.06166 9.73991L15.9176 0.547848C16.2429 0.210635 16.6914 0.0138295 17.1646 0.000701348C17.6378 -0.0124268 18.097 0.159198 18.4411 0.47784Z" fill="#40679F"/>
                    </svg>
                    <div class="justify-start text-black text-sm font-normal ">
                        Berbagai Event
                    </div>
                </div>
                <div class=" px-4 py-2.5 bg-white rounded-2xl shadow-[0px_0px_8px_0px_rgba(64,103,159,0.50)] inline-flex  justify-center items-center gap-2.5 mt-[20px]">
                    <svg width="19" height="14" viewBox="0 0 19 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.4411 0.47784C18.7851 0.796631 18.9859 1.23629 18.9993 1.70015C19.0127 2.16402 18.8376 2.6141 18.5125 2.95146L8.39482 13.4527C8.23084 13.6226 8.03379 13.7585 7.8153 13.8525C7.59682 13.9465 7.36134 13.9966 7.12277 13.9998C6.8842 14.0031 6.64738 13.9595 6.42631 13.8715C6.20523 13.7835 6.00439 13.653 5.83564 13.4877L0.479203 8.23707C0.163816 7.90529 -0.00788317 7.46646 0.00027817 7.01304C0.00843951 6.55962 0.195824 6.127 0.522955 5.80634C0.850085 5.48567 1.29142 5.30199 1.75398 5.29399C2.21654 5.28599 2.66421 5.45429 3.00268 5.76345L7.06166 9.73991L15.9176 0.547848C16.2429 0.210635 16.6914 0.0138295 17.1646 0.000701348C17.6378 -0.0124268 18.097 0.159198 18.4411 0.47784Z" fill="#40679F"/>
                    </svg>
                    <div class="justify-start text-black text-sm font-normal ">
                        Aman & terpercaya
                    </div>
                </div>
                <div class=" px-4 py-2.5 bg-white rounded-2xl shadow-[0px_0px_8px_0px_rgba(64,103,159,0.50)] inline-flex  justify-center items-center gap-2.5 mt-[20px]">
                    <svg width="19" height="14" viewBox="0 0 19 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.4411 0.47784C18.7851 0.796631 18.9859 1.23629 18.9993 1.70015C19.0127 2.16402 18.8376 2.6141 18.5125 2.95146L8.39482 13.4527C8.23084 13.6226 8.03379 13.7585 7.8153 13.8525C7.59682 13.9465 7.36134 13.9966 7.12277 13.9998C6.8842 14.0031 6.64738 13.9595 6.42631 13.8715C6.20523 13.7835 6.00439 13.653 5.83564 13.4877L0.479203 8.23707C0.163816 7.90529 -0.00788317 7.46646 0.00027817 7.01304C0.00843951 6.55962 0.195824 6.127 0.522955 5.80634C0.850085 5.48567 1.29142 5.30199 1.75398 5.29399C2.21654 5.28599 2.66421 5.45429 3.00268 5.76345L7.06166 9.73991L15.9176 0.547848C16.2429 0.210635 16.6914 0.0138295 17.1646 0.000701348C17.6378 -0.0124268 18.097 0.159198 18.4411 0.47784Z" fill="#40679F"/>
                    </svg>
                    <div class="justify-start text-black text-sm font-normal ">
                        SKP Maksimal
                    </div>
                </div>
            </div>
        </div>
        <img src="/assets/Group7.png" alt="">
    </div>

</body>
</html>
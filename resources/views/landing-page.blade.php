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
    <div class=" flex gap-[80px]">
        <div class=" mx-[98px] mt-[20px]">
            <h1 class=" w-[641px] justify-start text-indigo-300 text-6xl font-bold">Semua Event Dalam Satu Tempat</h1>
            <p class=" mt-[20px] text-xl">Temukan seminar sains, kompetisi riset, olimpiade matematika, workshop laboratorium, dan kepanitiaan ormawa dengan perolehan poin SKP maksimal.</p>
    
            <section>
                <div class="   px-[24.5px] py-[8px] bg-white rounded-[10px] shadow-[0px_0px_8px_0px_rgba(64,103,159,0.75)] self-stretch inline-flex  justify-start items-center gap-2.5  mt-[20px] ">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
    
                    <input class=" text-gray-400 text-sm font-light " name="fsrch" id="fsrch" placeholder="Cari Kepanitiaan, seminar, dll" class="flex-1 outline-none bg-transparent text-base ">
                    <p>|</p>
                    <select name="kategori" id="">
                        <option value="">Semua Kategori</option>
                        <option value="">Seminar</option>
                        <option value="">Pengabdian</option>
                        <option value="">Perlombaan</option>
                        <option value="">PKKMB</option>
                        <option value="">Lainnya</option>
                    </select>
                    <p>|</p>
                    <button class="bg-slate-400 inline-flex rounded-[5px] size- px-[16px] py-[4px] font-Poppins font-bold text-white">Cari Event</button>
    
                </div>
            </section>
    
        </div>
        <img class="w-[509px] h-96 left-0 top-0  rounded-[20px] mx-[91px]" src="/assets/Group7.png" alt="">
    </div>
    
</body>
</html>
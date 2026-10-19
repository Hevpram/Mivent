@vite(['resources/css/app.css', 'resources/js/app.js'])
    <header id="navbar"  class="sticky top-0 z-50 transition-all duration-300 font-poppins">
        <nav class ="px-[99px] py-3 flex justify-between items-center">
            <div class = "flex gap-3 items-center justify-between">
                <img id = "logo" src="assets/mivent_assets/logoColor.png"  class="w-[26.3px] h-[23.69px]">
                <h1 class= "logo_text text-gradient-mivent font-bold text-2xl">Mivent</h1>
            </div>
            <div class ="navbar_text font-medium flex gap-10 items-center text-black">
                <a href="#" class ="navbar_textIn border-hidde hover:border-solid">Beranda</a> 
                <a href="#" class ="navbar_textIn border-hidde hover:border-solid">Jelajahi Event</a>
                <a href="#" class ="navbar_textIn border-hidde hover:border-solid">Event Dikuti</a>
                <a href="#" class ="navbar_textIn border-hidde hover:border-solid">FAQ</a>
            </div>
            <a href="" class="navbar_button transition-all duration-300 w-32 py-2 rounded-md outline outline-2 outline-offset-[-1px] outline-primary-200 inline-flex justify-center items-center gap-2.5 text-primary-200 font-bold text-lg hover:bg-primary-200 hover:text-white">Login</a>
        </nav>
    </header>

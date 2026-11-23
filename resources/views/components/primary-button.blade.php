<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-[#D9541E] border border-transparent rounded-2xl font-bold text-sm text-white tracking-wide hover:bg-[#B84214] focus:bg-[#B84214] active:bg-[#B84214] focus:outline-none focus:ring-2 focus:ring-[#D9541E] focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm']) }}>
    {{ $slot }}
</button>

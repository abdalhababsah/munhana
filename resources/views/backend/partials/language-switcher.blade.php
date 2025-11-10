<!-- Language Switcher -->
<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" type="button" class="nav-link p-2 flex items-center gap-2" id="language-dropdown">
        <span class="flex items-center justify-center h-6 w-6">
            <i class="uil uil-language text-2xl"></i>
        </span>
        <span class="text-sm font-medium">{{ app()->getLocale() === 'ar' ? 'ع' : 'EN' }}</span>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 z-50"
         style="display: none;">

        <div class="py-1">
            <!-- Arabic Option -->
            <form method="POST" action="{{ route('language.switch', 'ar') }}" class="block">
                @csrf
                <button type="submit" class="w-full text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 flex items-center gap-3 {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : '' }}">
                    <i class="uil uil-check {{ app()->getLocale() === 'ar' ? 'text-primary-600' : 'text-transparent' }}"></i>
                    <span>العربية</span>
                </button>
            </form>

            <!-- Divider -->
            <div class="border-t border-slate-200 my-1"></div>

            <!-- English Option -->
            <form method="POST" action="{{ route('language.switch', 'en') }}" class="block">
                @csrf
                <button type="submit" class="w-full text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 flex items-center gap-3 {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : '' }}">
                    <i class="uil uil-check {{ app()->getLocale() === 'en' ? 'text-primary-600' : 'text-transparent' }}"></i>
                    <span>English</span>
                </button>
            </form>
        </div>
    </div>
</div>

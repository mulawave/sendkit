{{-- Sidebar (Admin) --}}
<aside
    data-sidebar="app"
    class="bg-[var(--sidebar-bg)] fixed inset-y-0 left-0 z-40 h-screen w-64 border-r border-gray-100 dark:border-admin-border flex flex-col transform -translate-x-full lg:translate-x-0 transition-all duration-200"
    :data-collapsed="sidebarCollapsed ? 'true' : 'false'"
    :class="[(sidebarOpen ? 'translate-x-0' : ''), (sidebarCollapsed ? 'lg:w-auto' : 'lg:w-64')]"
>
    <div class="flex h-full w-full flex-col overflow-visible">
        <div class="flex h-full w-full flex-col items-start relative overflow-visible">
            <div class="flex flex-col gap-4 items-start relative w-full flex-1 min-h-0 p-0">
                <div class="sidebar-header-row flex items-center justify-between w-full">
                    {{-- Logo --}}
                    @php
                        use Illuminate\Support\Facades\Storage;

                        try {
                            $appLogo = \App\Models\Setting::get('app_logo');
                            $appLogoDark = \App\Models\Setting::get('app_logo_dark');
                        } catch (\Throwable $e) {
                            $appLogo = null;
                            $appLogoDark = null;
                        }

                        $hasLogo = is_string($appLogo) && trim($appLogo) !== '';
                        $hasLogoDark = is_string($appLogoDark) && trim($appLogoDark) !== '';
                    @endphp

                    @if($hasLogo)
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-logo-area relative shrink-0">
                            <img
                                src="{{ (string) config('filesystems.branding_disk', 'public') === 'public' ? \Illuminate\Support\Facades\Storage::disk('public')->url(ltrim($appLogo, '/')) : Storage::disk((string) config('filesystems.branding_disk', 'public'))->url($appLogo) }}"
                                alt="{{ __('App Logo') }}"
                                class="sidebar-logo-img block dark:hidden h-auto object-contain w-[120px] mx-1 my-1"
                            />

                            @if($hasLogoDark)
                                <img
                                    src="{{ (string) config('filesystems.branding_disk', 'public') === 'public' ? \Illuminate\Support\Facades\Storage::disk('public')->url(ltrim($appLogoDark, '/')) : Storage::disk((string) config('filesystems.branding_disk', 'public'))->url($appLogoDark) }}"
                                    alt="{{ __('App Logo') }}"  
                                    class="sidebar-logo-img hidden dark:block h-auto object-contain w-[120px] mx-1 my-1"
                                />
                            @endif
                        </a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-logo-area relative shrink-0">
                            <svg width="100" height="23" viewBox="0 0 220 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M28.292 1.32781L28.7452 2.25219C28.9718 2.70628 29.5383 3.12793 30.04 3.22523L30.6551 3.32254C32.5002 3.63066 32.9372 4.99292 31.61 6.33895L31.0435 6.90656C30.6713 7.29577 30.4608 8.04177 30.5741 8.56072L30.6551 8.90127C31.1568 11.1393 29.9753 11.9988 28.033 10.8311L27.6122 10.5879C27.1105 10.296 26.3012 10.296 25.7995 10.5879L25.3786 10.8311C23.4202 12.015 22.2387 11.1393 22.7566 8.90127L22.8375 8.56072C22.9508 8.04177 22.7404 7.29577 22.3682 6.90656L21.8017 6.32273C20.4745 4.9767 20.9115 3.61445 22.7566 3.30632L23.3716 3.20901C23.8572 3.12793 24.4399 2.69006 24.6665 2.23598L25.1197 1.31159C25.9937 -0.439891 27.418 -0.439891 28.292 1.32781Z" fill="#1E5FEA"/>
                                <path d="M31.6748 13.1178C31.0921 13.5395 29.4574 14.3179 27.1267 13.1178C26.8677 12.9881 26.544 12.9719 26.2851 13.1178C25.3625 13.5881 24.5209 13.7827 23.8573 13.7827C22.8214 13.7827 22.0931 13.3773 21.737 13.1178C21.1381 12.6799 19.8757 11.4312 20.3127 8.78779C20.3612 8.5121 20.2803 8.2364 20.1023 8.02558C18.9855 6.74441 18.3704 5.02538 18.8074 3.67935C18.9693 3.14418 18.6294 2.43062 18.0791 2.43062H8.0927C3.23708 2.43062 0 4.86321 0 10.5393V21.8914C0 27.5674 3.23708 30 8.0927 30H24.2781C29.1337 30 32.3708 27.5674 32.3708 21.8914V13.4097C32.3708 13.1016 31.9338 12.9394 31.6748 13.1178ZM19.9728 16.3613C18.9045 17.2208 17.545 17.6424 16.1854 17.6424C14.8258 17.6424 13.4501 17.2208 12.398 16.3613L7.33198 12.3069C6.81405 11.8853 6.73312 11.1069 7.13776 10.5879C7.55858 10.069 8.31929 9.97165 8.83722 10.3933L13.9032 14.4476C15.1333 15.4369 17.2213 15.4369 18.4513 14.4476C18.9693 14.026 19.73 14.1071 20.1508 14.6422C20.5878 15.1612 20.5069 15.9396 19.9728 16.3613Z" fill="#1E5FEA"/>
                                <path d="M44.8093 28.3274V2.03285H51.7833L58.3619 17.7375H59.009L65.5516 2.03285H72.6334V28.3274H67.5647V5.88697L68.2118 5.95901L61.1299 22.9604H55.9174L48.7996 5.95901L49.4826 5.88697V28.3274H44.8093Z" fill="#1E5FEA"/>
                                <path d="M89.4609 28.3274V22.5282H88.6341V16.0806C88.6341 14.952 88.3585 14.1115 87.8073 13.5592C87.2561 13.0069 86.4053 12.7307 85.255 12.7307C84.6558 12.7307 83.9368 12.7428 83.098 12.7668C82.2592 12.7908 81.4085 12.8268 80.5457 12.8748C79.7069 12.8988 78.952 12.9349 78.281 12.9829V8.73254C78.8322 8.68452 79.4553 8.63649 80.1503 8.58846C80.8453 8.54044 81.5523 8.51642 82.2712 8.51642C83.0142 8.49241 83.7092 8.4804 84.3563 8.4804C86.3694 8.4804 88.035 8.74455 89.3531 9.27284C90.6952 9.80113 91.7017 10.6296 92.3727 11.7582C93.0678 12.8868 93.4152 14.3637 93.4152 16.1887V28.3274H89.4609ZM83.17 28.8316C81.756 28.8316 80.5098 28.5795 79.4313 28.0752C78.3768 27.5709 77.55 26.8505 76.9509 25.914C76.3757 24.9775 76.0881 23.8489 76.0881 22.5282C76.0881 21.0874 76.4356 19.9107 77.1306 18.9982C77.8496 18.0857 78.8442 17.4013 80.1143 16.9451C81.4085 16.4888 82.9183 16.2607 84.6438 16.2607H89.1733V19.2503H84.5719C83.4216 19.2503 82.5349 19.5385 81.9118 20.1148C81.3126 20.6671 81.013 21.3875 81.013 22.276C81.013 23.1645 81.3126 23.8849 81.9118 24.4372C82.5349 24.9895 83.4216 25.2657 84.5719 25.2657C85.2669 25.2657 85.902 25.1456 86.4772 24.9055C87.0764 24.6413 87.5676 24.2091 87.9511 23.6088C88.3585 22.9844 88.5862 22.1439 88.6341 21.0874L89.8564 22.4921C89.7365 23.8609 89.401 25.0135 88.8498 25.95C88.3226 26.8866 87.5796 27.607 86.621 28.1112C85.6863 28.5915 84.536 28.8316 83.17 28.8316Z" fill="#1E5FEA"/>
                                <path d="M98.6238 28.3274V8.76856H103.621V28.3274H98.6238ZM95.8918 12.5146V8.76856H103.621V12.5146H95.8918ZM100.457 6.42727C99.4746 6.42727 98.7437 6.17513 98.2644 5.67085C97.809 5.14256 97.5813 4.4942 97.5813 3.72578C97.5813 2.90933 97.809 2.24896 98.2644 1.74469C98.7437 1.24041 99.4746 0.988267 100.457 0.988267C101.44 0.988267 102.159 1.24041 102.614 1.74469C103.069 2.24896 103.297 2.90933 103.297 3.72578C103.297 4.4942 103.069 5.14256 102.614 5.67085C102.159 6.17513 101.44 6.42727 100.457 6.42727Z" fill="#1E5FEA"/>
                                <path d="M108.553 28.3274V2.03285H113.549V28.3274H108.553ZM106.108 5.77891V2.03285H113.549V5.77891H106.108Z" fill="#1E5FEA"/>
                                <path d="M129.637 20.2589V15.8285H134.741C135.772 15.8285 136.635 15.6243 137.33 15.2161C138.049 14.8079 138.588 14.2436 138.947 13.5232C139.331 12.8028 139.522 11.9743 139.522 11.0378C139.522 10.1013 139.331 9.27284 138.947 8.55244C138.588 7.83204 138.049 7.27974 137.33 6.89553C136.635 6.4873 135.772 6.28319 134.741 6.28319H129.637V1.85275H134.31C136.515 1.85275 138.384 2.21294 139.918 2.93334C141.476 3.65374 142.662 4.68631 143.477 6.03105C144.292 7.35178 144.699 8.92465 144.699 10.7497V11.326C144.699 13.151 144.292 14.7359 143.477 16.0806C142.662 17.4013 141.476 18.4339 139.918 19.1783C138.384 19.8987 136.515 20.2589 134.31 20.2589H129.637ZM125.251 28.3274V1.85275H130.356V28.3274H125.251Z" fill="#241D1D"/>
                                <path d="M154.089 28.9397C151.836 28.9397 150.087 28.1953 148.84 26.7065C147.618 25.2176 147.007 23.0084 147.007 20.0788V8.73254H152.004V20.511C152.004 21.7117 152.339 22.6722 153.01 23.3926C153.681 24.089 154.592 24.4372 155.743 24.4372C156.893 24.4372 157.827 24.065 158.546 23.3206C159.289 22.5762 159.661 21.5676 159.661 20.2949V8.73254H164.658V28.3274H160.703V20.0068H161.099C161.099 21.9759 160.847 23.6208 160.344 24.9415C159.841 26.2622 159.086 27.2588 158.079 27.9311C157.073 28.6035 155.814 28.9397 154.305 28.9397H154.089Z" fill="#241D1D"/>
                                <path d="M169.319 28.3274V8.76856H173.274V17.0531H173.166C173.166 14.2436 173.765 12.1184 174.963 10.6776C176.161 9.23682 177.923 8.51642 180.248 8.51642H180.966V12.8748H179.6C177.923 12.8748 176.617 13.3311 175.682 14.2436C174.771 15.1321 174.316 16.4288 174.316 18.1337V28.3274H169.319Z" fill="#241D1D"/>
                                <path d="M190.313 28.9397C187.629 28.9397 185.52 28.3754 183.986 27.2468C182.476 26.0941 181.685 24.5092 181.613 22.4921H186.107C186.179 23.1645 186.55 23.7768 187.221 24.3291C187.892 24.8815 188.947 25.1576 190.385 25.1576C191.583 25.1576 192.53 24.9415 193.225 24.5092C193.944 24.077 194.303 23.4887 194.303 22.7443C194.303 22.0959 194.027 21.5796 193.476 21.1954C192.949 20.8112 192.038 20.5591 190.744 20.439L189.019 20.2589C186.838 20.0188 185.16 19.3944 183.986 18.3859C182.812 17.3773 182.224 16.0326 182.224 14.3516C182.224 13.0069 182.56 11.8783 183.231 10.9658C183.902 10.0533 184.825 9.36889 185.999 8.91264C187.197 8.43238 188.563 8.19224 190.097 8.19224C192.494 8.19224 194.435 8.72054 195.921 9.77712C197.407 10.8337 198.186 12.3826 198.257 14.4237H193.764C193.716 13.7513 193.38 13.175 192.757 12.6947C192.134 12.2145 191.235 11.9743 190.061 11.9743C189.007 11.9743 188.192 12.1784 187.617 12.5867C187.041 12.9949 186.754 13.5232 186.754 14.1715C186.754 14.7959 186.982 15.2762 187.437 15.6123C187.916 15.9485 188.683 16.1767 189.738 16.2967L191.463 16.4768C193.764 16.7169 195.561 17.3533 196.855 18.3859C198.174 19.4184 198.833 20.8112 198.833 22.5642C198.833 23.8609 198.473 24.9895 197.754 25.95C197.059 26.8866 196.077 27.619 194.806 28.1473C193.536 28.6755 192.038 28.9397 190.313 28.9397Z" fill="#241D1D"/>
                                <path d="M210.617 29.0117C208.94 29.0117 207.466 28.7236 206.196 28.1473C204.95 27.5709 203.907 26.8025 203.068 25.842C202.253 24.8574 201.63 23.7648 201.199 22.5642C200.792 21.3635 200.588 20.1388 200.588 18.8901V18.2058C200.588 16.9091 200.792 15.6604 201.199 14.4597C201.63 13.235 202.253 12.1544 203.068 11.2179C203.907 10.2574 204.938 9.50097 206.16 8.94866C207.382 8.37234 208.796 8.08418 210.402 8.08418C212.511 8.08418 214.272 8.55244 215.686 9.48896C217.124 10.4015 218.203 11.6141 218.922 13.127C219.641 14.6158 220 16.2247 220 17.9536V19.7546H202.709V16.6929H216.872L215.327 18.2058C215.327 16.9571 215.147 15.8885 214.787 15C214.428 14.1115 213.877 13.4271 213.134 12.9469C212.415 12.4666 211.504 12.2265 210.402 12.2265C209.299 12.2265 208.365 12.4786 207.598 12.9829C206.831 13.4872 206.244 14.2196 205.836 15.1801C205.453 16.1166 205.261 17.2452 205.261 18.566C205.261 19.7906 205.453 20.8832 205.836 21.8438C206.22 22.7803 206.807 23.5247 207.598 24.077C208.389 24.6053 209.395 24.8694 210.617 24.8694C211.84 24.8694 212.834 24.6293 213.601 24.149C214.368 23.6448 214.859 23.0324 215.075 22.312H219.676C219.389 23.6568 218.838 24.8334 218.023 25.842C217.208 26.8505 216.166 27.631 214.895 28.1833C213.649 28.7356 212.223 29.0117 210.617 29.0117Z" fill="#241D1D"/>
                            </svg>
                        </a>

                    @endif

                    <div class="flex items-center gap-1 flex-shrink-0">
                        <button
                            type="button"
                            class="hidden lg:inline-flex items-center justify-center p-2 rounded-lg text-[var(--sidebar-nav-text)] hover:text-[var(--sidebar-nav-hover)] hover:bg-[var(--sidebar-nav-hover-bg)] transition-colors flex-shrink-0"
                            @click="toggleSidebarCollapsed()"
                            :aria-label="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'"
                        >
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <path d="M9 3v18"/>
                            </svg>
                        </button>

                        <button
                            type="button"
                            class="lg:hidden p-2 rounded-md text-[var(--sidebar-nav-text)] hover:text-[var(--sidebar-nav-hover)] hover:bg-[var(--sidebar-nav-hover-bg)]"
                            @click="sidebarOpen = false"
                            aria-label="{{ __('Close sidebar') }}"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="px-2 w-full shrink-0" x-show="!sidebarCollapsed" x-data @keydown.window.prevent.cmd.k="$refs.sidebarSearch.focus()" @keydown.window.prevent.ctrl.k="$refs.sidebarSearch.focus()">
                    <form action="{{ route('admin.search.index') }}" method="GET">
                        <div class="relative flex items-center">
                            <span class="pointer-events-none absolute left-3 flex items-center text-gray-400 dark:text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                            </span>
                            <input
                                x-ref="sidebarSearch"
                                type="search"
                                name="q"
                                placeholder="{{ __('Search') }}"
                                class="block h-9 w-full rounded-lg border-0 bg-transparent pl-9 pr-14 py-0 text-sm text-gray-700 placeholder:text-gray-400 outline-none dark:text-white dark:placeholder:text-gray-500"
                            >
                            <span class="pointer-events-none absolute right-2.5 flex items-center gap-0.5">
                                <kbd class="inline-flex h-5 items-center rounded border border-gray-300 bg-white px-1 font-sans text-[10px] font-medium text-gray-400 dark:border-gray-600 dark:bg-white/10 dark:text-gray-500">⌘</kbd>
                                <kbd class="inline-flex h-5 items-center rounded border border-gray-300 bg-white px-1 font-sans text-[10px] font-medium text-gray-400 dark:border-gray-600 dark:bg-white/10 dark:text-gray-500">K</kbd>
                            </span>
                        </div>
                    </form>
                </div>

                <nav class="flex flex-col gap-6 items-start relative w-full flex-1 min-h-0 overflow-y-auto">
                    <div class="flex flex-col gap-3 items-start relative shrink-0 w-full">
                        <p class="font-normal leading-[16px] relative shrink-0 text-[var(--sidebar-title-color)] text-xs tracking-[-0.36px] ml-2">{{ __('General') }}</p>

                        <div class="flex flex-col items-start relative shrink-0 w-full">
                            <a href="{{ route('admin.dashboard') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.dashboard') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                <div class="relative shrink-0 w-[16px] h-[16px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-gauge-icon lucide-gauge"><path d="m12 14 4-4"/><path d="M3.34 19a10 10 0 1 1 17.32 0"/></svg>
                                </div>
                                <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Dashboard') }}</p>
                            </a>

                            <a href="{{ route('admin.users.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.users.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                <div class="relative shrink-0 w-[16px] h-[16px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>
                                </div>
                                <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Users') }}</p>
                            </a>

                            @admincan('admin.roles.access')
                                <a href="{{ route('admin.roles.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.roles.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                    <div class="relative shrink-0 w-[16px] h-[16px]">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-icon lucide-shield"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                    </div>
                                    <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Roles') }}</p>
                                </a>
                            @endadmincan

                            @admincan('admin.support_tickets.access')
                                <a href="{{ route('admin.support-tickets.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.support-tickets.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                    <div class="relative shrink-0 w-[16px] h-[16px]">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square-icon lucide-message-square"><path d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z"/></svg>
                                    </div>
                                    <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Support') }}</p>
                                </a>
                            @endadmincan

                            @admincan('admin.settings.access')
                                @php
                                    $siteSettingsOpen = request()->routeIs('admin.settings.index')
                                        || request()->routeIs('admin.blog-posts.*')
                                        || request()->routeIs('admin.pages.*')
                                        || request()->routeIs('admin.menus.*')
                                        || request()->routeIs('admin.layouts.*');
                                @endphp

                                <div x-data="{ open: {{ $siteSettingsOpen ? 'true' : 'false' }} }" class="w-full">
                                    <button type="button" @click="open = !open" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ $siteSettingsOpen ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)] hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)]' }} transition-colors">
                                        <div class="relative shrink-0 w-[16px] h-[16px]">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-icon lucide-settings"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </div>
                                        <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px] flex-1 text-left">{{ __('Site Settings') }}</p>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down-icon lucide-chevron-down w-4 h-4" :class="open ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
                                    </button>

                                    <div x-cloak x-show="open" class="mt-1 ml-6 flex flex-col gap-1 border-l border-gray-200 dark:border-white/10 pl-4">
                                        @if((bool) \App\Models\Setting::get('blog_enabled', 1))
                                            @admincan('admin.blog_posts.access')
                                                <a href="{{ route('admin.blog-posts.index') }}" class="flex items-center w-full py-2 px-2.5 rounded-lg text-sm {{ request()->routeIs('admin.blog-posts.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)] hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)]' }} transition-colors">
                                                    {{ __('Blog') }}
                                                </a>
                                            @endadmincan
                                        @endif

                                        @admincan('admin.pages.access')
                                            <a href="{{ route('admin.pages.index') }}" class="flex items-center w-full py-2 px-2.5 rounded-lg text-sm {{ request()->routeIs('admin.pages.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)] hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)]' }} transition-colors">
                                                {{ __('Pages') }}
                                            </a>
                                        @endadmincan

                                        @admincan('admin.pages.access')
                                            <a href="{{ route('admin.layouts.index') }}" class="flex items-center w-full py-2 px-2.5 rounded-lg text-sm {{ request()->routeIs('admin.layouts.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)] hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)]' }} transition-colors">
                                                {{ __('Layout Builder') }}
                                            </a>
                                        @endadmincan

                                        <a href="{{ route('admin.menus.index') }}" class="flex items-center w-full py-2 px-2.5 rounded-lg text-sm {{ request()->routeIs('admin.menus.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)] hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)]' }} transition-colors">
                                            {{ __('Menus') }}
                                        </a>
                                    </div>
                                </div>
                            @endadmincan

                            @admincan('admin.settings.access')
                                <a href="{{ route('admin.site-pages.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ (request()->routeIs('admin.site-pages.*') || request()->routeIs('admin.homepages.*')) ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                    <div class="relative shrink-0 w-[16px] h-[16px]">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-list-icon lucide-layout-list"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/><path d="M14 4h7"/><path d="M14 9h7"/><path d="M14 15h7"/><path d="M14 20h7"/></svg>
                                    </div>
                                    <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Site Pages') }}</p>
                                </a>
                            @endadmincan

                            @admincan('admin.api.access')
                                <a href="{{ route('admin.api.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.api.index') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                    <div class="relative shrink-0 w-[16px] h-[16px]">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-webhook-icon lucide-webhook"><path d="M18 16.98h-5.99c-1.1 0-1.95.94-2.48 1.9A4 4 0 0 1 2 17c.01-.7.2-1.4.57-2"/><path d="m6 17 3.13-5.78c.53-.97.1-2.18-.5-3.1a4 4 0 1 1 6.89-4.06"/><path d="m12 6 3.13 5.73C15.66 12.7 16.9 13 18 13a4 4 0 0 1 0 8"/></svg>
                                    </div>
                                    <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('API') }}</p>
                                </a>
                            @endadmincan

                        </div>
                    </div>

                    {{-- Marketing --}}
                    <div class="flex flex-col gap-3 items-start relative shrink-0 w-full">
                        <p class="font-normal leading-[16px] relative shrink-0 text-[var(--sidebar-title-color)] text-xs tracking-[-0.36px] ml-3">{{ __('Marketing') }}</p>

                        <div class="flex flex-col items-start relative shrink-0 w-full">
                            <a href="{{ route('admin.campaigns.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.campaigns.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                <div class="relative shrink-0 w-[16px] h-[16px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-megaphone-icon lucide-megaphone"><path d="M11 6a13 13 0 0 0 8.4-2.8A1 1 0 0 1 21 4v12a1 1 0 0 1-1.6.8A13 13 0 0 0 11 14H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z"/><path d="M6 14a12 12 0 0 0 2.4 7.2 2 2 0 0 0 3.2-2.4A8 8 0 0 1 10 14"/><path d="M8 6v8"/></svg>
                                </div>
                                <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Campaigns') }}</p>
                            </a>

                            <a href="{{ route('admin.lists.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.lists.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                <div class="relative shrink-0 w-[16px] h-[16px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-check-icon lucide-list-check"><path d="M16 5H3"/><path d="M16 12H3"/><path d="M11 19H3"/><path d="m15 18 2 2 4-4"/></svg>
                                </div>
                                <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Email Lists') }}</p>
                            </a>

                            @php
                                $adminMenuUser = auth('admin')->user();
                                $adminMenuIsSuper = $adminMenuUser && method_exists($adminMenuUser, 'isSuperAdmin') && $adminMenuUser->isSuperAdmin();
                                $canManageTemplates = $adminMenuIsSuper || ($adminMenuUser && method_exists($adminMenuUser, 'hasAdminAbility') && $adminMenuUser->hasAdminAbility('admin.public_templates.access'));
                                $canManageTemplateCategories = $adminMenuIsSuper || ($adminMenuUser && method_exists($adminMenuUser, 'hasAdminAbility') && $adminMenuUser->hasAdminAbility('admin.public_template_categories.access'));
                            @endphp

                            @if($canManageTemplates || $canManageTemplateCategories)
                                @php
                                    $adminTemplatesMenuOpen = request()->routeIs('admin.public-templates.*')
                                        || request()->routeIs('admin.public-template-categories.*')
                                        || request()->routeIs('admin.built-in-templates.*');
                                @endphp

                                <div x-data="{ open: {{ $adminTemplatesMenuOpen ? 'true' : 'false' }} }" class="w-full">
                                    <button type="button" @click="open = !open" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ $adminTemplatesMenuOpen ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)] hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)]' }} transition-colors">
                                        <div class="relative shrink-0 w-[16px] h-[16px]">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-panel-left-icon lucide-layout-panel-left"><rect width="7" height="18" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/></svg>
                                        </div>
                                        <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px] flex-1 text-left">{{ __('Templates') }}</p>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down-icon lucide-chevron-down w-4 h-4" :class="open ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
                                    </button>

                                    <div x-cloak x-show="open" class="mt-1 ml-6 flex flex-col gap-1 border-l border-gray-200 dark:border-white/10 pl-4">
                                        @if($canManageTemplates)
                                            @php
                                                $templatesQueryType = request()->query('type');
                                                $templatesIsIndex = request()->routeIs('admin.public-templates.index') || request()->routeIs('admin.built-in-templates.*');
                                                $templatesEmailActive = $templatesIsIndex && !in_array($templatesQueryType, ['footer', 'signature'], true);
                                                $templatesFooterActive = $templatesIsIndex && $templatesQueryType === 'footer';
                                                $templatesSignatureActive = $templatesIsIndex && $templatesQueryType === 'signature';
                                            @endphp

                                            <a href="{{ route('admin.public-templates.index') }}" class="flex items-center w-full py-2 px-2.5 rounded-lg text-sm {{ $templatesEmailActive ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)] hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)]' }} transition-colors">
                                                {{ __('Email Templates') }}
                                            </a>
                                            <a href="{{ route('admin.public-templates.index', ['type' => 'footer']) }}" class="flex items-center w-full py-2 px-2.5 rounded-lg text-sm {{ $templatesFooterActive ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)] hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)]' }} transition-colors">
                                                {{ __('Footer templates') }}
                                            </a>
                                            <a href="{{ route('admin.public-templates.index', ['type' => 'signature']) }}" class="flex items-center w-full py-2 px-2.5 rounded-lg text-sm {{ $templatesSignatureActive ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)] hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)]' }} transition-colors">
                                                {{ __('Signature') }}
                                            </a>
                                        @endif

                                        @if($canManageTemplateCategories)
                                            <a href="{{ route('admin.public-template-categories.index') }}" class="flex items-center w-full py-2 px-2.5 rounded-lg text-sm {{ request()->routeIs('admin.public-template-categories.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)] hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)]' }} transition-colors">
                                                {{ __('Template Categories') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @admincan('admin.email_validation.access')
                                <a href="{{ route('admin.email-validation.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.email-validation.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                    <div class="relative shrink-0 w-[16px] h-[16px]">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-alert-icon lucide-shield-alert"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                                    </div>
                                    <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Email Validation') }}</p>
                                </a>
                            @endadmincan

                            <a href="{{ route('admin.customers.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.customers.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                <div class="relative shrink-0 w-[16px] h-[16px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>
                                </div>
                                <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Customers') }}</p>
                            </a>

                        </div>
                    </div>

                    {{-- Delivery --}}
                    <div class="flex flex-col gap-3 items-start relative shrink-0 w-full">
                        <p class="font-normal leading-[16px] relative shrink-0 text-[var(--sidebar-title-color)] text-xs tracking-[-0.36px] ml-3">{{ __('Delivery') }}</p>

                        <div class="flex flex-col items-start relative shrink-0 w-full">
                            <a href="{{ route('admin.delivery-servers.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.delivery-servers.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                <div class="relative shrink-0 w-[16px] h-[16px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck-electric-icon lucide-truck-electric"><path d="M14 19V7a2 2 0 0 0-2-2H9"/><path d="M15 19H9"/><path d="M19 19h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.62L18.3 9.38a1 1 0 0 0-.78-.38H14"/><path d="M2 13v5a1 1 0 0 0 1 1h2"/><path d="M4 3 2.15 5.15a.495.495 0 0 0 .35.86h2.15a.47.47 0 0 1 .35.86L3 9.02"/><circle cx="17" cy="19" r="2"/><circle cx="7" cy="19" r="2"/></svg>
                                </div>
                                <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Delivery Servers') }}</p>
                            </a>

                            <a href="{{ route('admin.sending-domains.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.sending-domains.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                <div class="relative shrink-0 w-[16px] h-[16px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe-lock-icon lucide-globe-lock"><path d="M15.686 15A14.5 14.5 0 0 1 12 22a14.5 14.5 0 0 1 0-20 10 10 0 1 0 9.542 13"/><path d="M2 12h8.5"/><path d="M20 6V4a2 2 0 1 0-4 0v2"/><rect width="8" height="5" x="14" y="6" rx="1"/></svg>
                                </div>
                                <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Sending Domains') }}</p>
                            </a>

                            <a href="{{ route('admin.tracking-domains.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.tracking-domains.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                <div class="relative shrink-0 w-[16px] h-[16px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scan-icon lucide-scan"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/></svg>
                                </div>
                                <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Tracking Domains') }}</p>
                            </a>

                            <a href="{{ route('admin.bounce-servers.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.bounce-servers.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                <div class="relative shrink-0 w-[16px] h-[16px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-volleyball-icon lucide-volleyball"><path d="M11 7a16 16 20 0 1 10.98 4.362"/><path d="M12 12a13 13 0 0 1-8.66 5"/><path d="M16.83 13.634a16 16 0 0 1-9.267 7.328"/><path d="M20.66 17A13 13 0 0 0 12 12a13 13 0 0 1 0-10"/><path d="M8.17 15.366a16 16 0 0 1-1.713-11.69"/><circle cx="12" cy="12" r="10"/></svg>
                                </div>
                                <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Bounce Servers') }}</p>
                            </a>

                            <a href="{{ route('admin.reply-servers.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.reply-servers.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                <div class="relative shrink-0 w-[16px] h-[16px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square-reply-icon lucide-message-square-reply"><path d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z"/><path d="m10 8-3 3 3 3"/><path d="M17 14v-1a2 2 0 0 0-2-2H7"/></svg>
                                </div>
                                <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Reply Servers') }}</p>
                            </a>

                            <a href="{{ route('admin.bounced-emails.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.bounced-emails.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                <div class="relative shrink-0 w-[16px] h-[16px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-off-icon lucide-shield-off"><path d="m2 2 20 20"/><path d="M5 5a1 1 0 0 0-1 1v7c0 5 3.5 7.5 7.67 8.94a1 1 0 0 0 .67.01c2.35-.82 4.48-1.97 5.9-3.71"/><path d="M9.309 3.652A12.252 12.252 0 0 0 11.24 2.28a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1v7a9.784 9.784 0 0 1-.08 1.264"/></svg>
                                </div>
                                <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Bounce Email') }}</p>
                            </a>
                        </div>
                    </div>

                    {{-- Leads --}}
                    @if(\App\Models\Addon::isActive('super-scrape'))
                    <div class="flex flex-col gap-3 items-start relative shrink-0 w-full">
                        <p class="font-normal leading-[16px] relative shrink-0 text-[var(--sidebar-title-color)] text-xs tracking-[-0.36px] ml-3">{{ __('Leads') }}</p>
                        <div class="flex flex-col items-start relative shrink-0 w-full">
                            <a href="{{ route('admin.super-scrape.settings') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.super-scrape.settings') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                <div class="relative shrink-0 w-[16px] h-[16px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-icon lucide-settings"><path d="M9.671 4.136a2.34 2.34 0 0 0 3.713 0 1.668 1.668 0 0 1 2.22-1.044 2.34 2.34 0 0 0 3.147 1.395 1.668 1.668 0 0 1 1.81 1.81 2.34 2.34 0 0 0 1.395 3.147 1.668 1.668 0 0 1-1.044 2.22 2.34 2.34 0 0 0 0 3.713 1.668 1.668 0 0 1 1.044 2.22 2.34 2.34 0 0 0-1.395 3.147 1.668 1.668 0 0 1-1.81 1.81 2.34 2.34 0 0 0-3.147 1.395 1.668 1.668 0 0 1-2.22-1.044 2.34 2.34 0 0 0-3.713 0 1.668 1.668 0 0 1-2.22 1.044 2.34 2.34 0 0 0-3.147-1.395 1.668 1.668 0 0 1-1.81-1.81 2.34 2.34 0 0 0-1.395-3.147 1.668 1.668 0 0 1 1.044-2.22 2.34 2.34 0 0 0 0-3.713 1.668 1.668 0 0 1-1.044-2.22 2.34 2.34 0 0 0 1.395-3.147 1.668 1.668 0 0 1 1.81-1.81 2.34 2.34 0 0 0 3.147-1.395 1.668 1.668 0 0 1 2.22 1.044Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </div>
                                <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Enrichment Settings') }}</p>
                            </a>
                        </div>
                    </div>
                    @endif

                    {{-- Channels (SMS / WhatsApp / AI Assistant addons) --}}
                    @if(\App\Models\Addon::isActive('sms') || \App\Models\Addon::isActive('whatsapp') || \App\Models\Addon::isActive('ai-assistant'))
                    <div class="flex flex-col gap-3 items-start relative shrink-0 w-full">
                        <p class="font-normal leading-[16px] relative shrink-0 text-[var(--sidebar-title-color)] text-xs tracking-[-0.36px] ml-3">{{ __('Channels') }}</p>
                        <div class="flex flex-col items-start relative shrink-0 w-full">
                            @if(\App\Models\Addon::isActive('sms'))
                                <a href="{{ route('admin.sms.settings') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.sms.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                    <div class="relative shrink-0 w-[16px] h-[16px]">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                    </div>
                                    <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('SMS Settings') }}</p>
                                </a>
                            @endif
                            @if(\App\Models\Addon::isActive('whatsapp'))
                                <a href="{{ route('admin.whatsapp.settings') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.whatsapp.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                    <div class="relative shrink-0 w-[16px] h-[16px]">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                                    </div>
                                    <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('WhatsApp Settings') }}</p>
                                </a>
                            @endif
                            @if(\App\Models\Addon::isActive('ai-assistant'))
                                <a href="{{ route('admin.ai-assistant.settings') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.ai-assistant.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                    <div class="relative shrink-0 w-[16px] h-[16px]">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg>
                                    </div>
                                    <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('AI Assistant Settings') }}</p>
                                </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Monetization --}}
                    <div class="flex flex-col gap-3 items-start relative shrink-0 w-full">
                        <p class="font-normal leading-[16px] relative shrink-0 text-[var(--sidebar-title-color)] text-xs tracking-[-0.36px] ml-3">{{ __('Monetization') }}</p>

                        <div class="flex flex-col items-start relative shrink-0 w-full">
                            <a href="{{ route('admin.invoices.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.invoices.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                <div class="relative shrink-0 w-[16px] h-[16px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-spreadsheet-icon lucide-file-spreadsheet"><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"/><path d="M14 2v5a1 1 0 0 0 1 1h5"/><path d="M8 13h2"/><path d="M14 13h2"/><path d="M8 17h2"/><path d="M14 17h2"/></svg>
                                </div>
                                <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Invoices') }}</p>
                            </a>

                            <a href="{{ route('admin.manual-payments.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.manual-payments.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                <div class="relative shrink-0 w-[16px] h-[16px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hand-coins-icon lucide-hand-coins"><path d="M11 15h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 17"/><path d="m7 21 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"/><path d="m2 16 6 6"/><circle cx="16" cy="9" r="2.9"/><circle cx="6" cy="5" r="3"/></svg>
                                </div>
                                <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Manual Payments') }}</p>
                            </a>

                            <a href="{{ route('admin.coupons.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.coupons.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                <div class="relative shrink-0 w-[16px] h-[16px]">
                                    <svg class="block w-full h-full" fill="none" preserveAspectRatio="none" viewBox="0 0 18 18">
                                        <path d="M1.84829 7.00781C1.66188 7.00781 1.49178 6.85672 1.50036 6.65921C1.55054 5.50265 1.69115 4.74973 2.0851 4.15413C2.31175 3.81147 2.59328 3.51344 2.91696 3.27351C3.79185 2.625 5.02608 2.625 7.49454 2.625H10.5055C12.974 2.625 14.2082 2.625 15.0832 3.27351C15.4068 3.51344 15.6883 3.81147 15.915 4.15413C16.3089 4.74967 16.4495 5.50249 16.4997 6.65882C16.5083 6.85656 16.338 7.00781 16.1514 7.00781C15.112 7.00781 14.2695 7.89975 14.2695 9C14.2695 10.1003 15.112 10.9922 16.1514 10.9922C16.338 10.9922 16.5083 11.1434 16.4997 11.3412C16.4495 12.4976 16.3089 13.2503 15.915 13.8459C15.6883 14.1885 15.4068 14.4865 15.0832 14.7265C14.2082 15.375 12.974 15.375 10.5055 15.375H7.49454C5.02608 15.375 3.79185 15.375 2.91696 14.7265C2.59328 14.4865 2.31175 14.1885 2.0851 13.8459C1.69115 13.2503 1.55054 12.4973 1.50036 11.3408C1.49178 11.1433 1.66188 10.9922 1.84829 10.9922C2.88763 10.9922 3.73018 10.1003 3.73018 9C3.73018 7.89975 2.88763 7.00781 1.84829 7.00781Z" stroke="currentColor" stroke-linejoin="round" stroke-width="1.5" />
                                        <path d="M7.12505 10.875L10.8751 7.125" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.125" />
                                        <path d="M7.12505 7.125H7.13347M10.8666 10.875H10.8751" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
                                    </svg>
                                </div>
                                <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Coupons') }}</p>
                            </a>

                            <a href="{{ route('admin.plans.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.plans.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                <div class="relative shrink-0 w-[16px] h-[16px]">
                                    <svg class="block w-full h-full" fill="none" preserveAspectRatio="none" viewBox="0 0 18 18">
                                        <path d="M9.66068 5.26242L13.2403 6.21619M8.89335 8.11005L10.6831 8.5869M8.98237 13.4748L9.69832 13.6656C11.7232 14.2051 12.7358 14.4749 13.5334 14.017C14.331 13.559 14.6023 12.5522 15.1449 10.5387L15.9122 7.6911C16.4548 5.67754 16.7261 4.67076 16.2656 3.87762C15.8051 3.08448 14.7926 2.81471 12.7676 2.27518L12.0517 2.08443C10.0267 1.54489 9.01425 1.27513 8.21662 1.73305C7.41897 2.19097 7.14767 3.19775 6.60508 5.21131L5.83774 8.0589C5.29515 10.0724 5.02386 11.0792 5.48437 11.8723C5.94489 12.6655 6.95738 12.9353 8.98237 13.4748Z" stroke="currentColor" stroke-linecap="round" stroke-width="1.5" />
                                        <path d="M9 15.7096L8.28578 15.9041C6.26552 16.4542 5.25542 16.7293 4.45964 16.2624C3.66388 15.7955 3.39322 14.769 2.8519 12.7159L2.08637 9.81246C1.54505 7.75941 1.27439 6.73287 1.73383 5.92418C2.13125 5.22463 3 5.2501 4.125 5.25001" stroke="currentColor" stroke-linecap="round" stroke-width="1.5" />
                                    </svg>
                                </div>
                                <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Plans') }}</p>
                            </a>

                            <a href="{{ route('admin.payment-methods.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.payment-methods.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                <div class="relative shrink-0 w-[16px] h-[16px]">
                                    <svg class="block w-full h-full" fill="none" preserveAspectRatio="none" viewBox="0 0 18 18">
                                        <path d="M12.3305 16.0108L12.7447 15.4932C13.1086 15.0383 13.8814 15.075 14.1888 15.5616C14.5586 16.1471 15.4719 16.0307 15.7252 15.4922C15.7384 15.4642 15.7054 15.5342 15.7336 15.2919C15.7617 15.0496 15.75 14.9942 15.7265 14.8836L14.2842 8.07308C13.9217 6.36146 13.7405 5.50565 13.117 5.00282C12.4936 4.5 11.6136 4.5 9.8538 4.5H8.1462C6.38638 4.5 5.50646 4.5 4.883 5.00282C4.25953 5.50565 4.07828 6.36146 3.71579 8.07308L2.27348 14.8836C2.25003 14.9942 2.23831 15.0496 2.26646 15.2919C2.2946 15.5342 2.26166 15.4642 2.27483 15.4922C2.52813 16.0307 3.44142 16.1471 3.81124 15.5616C4.11864 15.075 4.8914 15.0383 5.25532 15.4932L5.66951 16.0108C6.19136 16.663 7.28114 16.663 7.803 16.0108L7.8681 15.9294C8.42183 15.2374 9.57818 15.2374 10.1319 15.9294L10.197 16.0108C10.7189 16.663 11.8087 16.663 12.3305 16.0108Z" stroke="currentColor" stroke-linejoin="round" stroke-width="1.125" />
                                        <path d="M1.86252 7.125C1.42354 6.66899 1.50618 6.0817 1.50618 4.61382C1.50618 3.14595 1.50618 2.41202 1.94517 1.95601C2.38415 1.5 3.09068 1.5 4.50373 1.5H13.4964C14.9094 1.5 15.616 1.5 16.055 1.95601C16.4939 2.41202 16.4939 3.14595 16.4939 4.61382C16.4939 6.0817 16.5761 6.66899 16.1371 7.125" stroke="currentColor" stroke-linecap="round" stroke-width="1.125" />
                                        <path d="M9 7.5H6.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.125" />
                                        <path d="M10.5 10.5H6" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.125" />
                                    </svg>
                                </div>
                                <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Payment Method') }}</p>
                            </a>

                            <a href="{{ route('admin.vat-tax.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.vat-tax.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                <div class="relative shrink-0 w-[16px] h-[16px]">
                                    <svg class="block w-full h-full" fill="none" preserveAspectRatio="none" viewBox="0 0 18 18">
                                        <path d="M1.84829 7.00781C1.66188 7.00781 1.49178 6.85672 1.50036 6.65921C1.55054 5.50265 1.69115 4.74973 2.0851 4.15413C2.31175 3.81147 2.59328 3.51344 2.91696 3.27351C3.79185 2.625 5.02608 2.625 7.49454 2.625H10.5055C12.974 2.625 14.2082 2.625 15.0832 3.27351C15.4068 3.51344 15.6883 3.81147 15.915 4.15413C16.3089 4.74967 16.4495 5.50249 16.4997 6.65882C16.5083 6.85656 16.338 7.00781 16.1514 7.00781C15.112 7.00781 14.2695 7.89975 14.2695 9C14.2695 10.1003 15.112 10.9922 16.1514 10.9922C16.338 10.9922 16.5083 11.1434 16.4997 11.3412C16.4495 12.4976 16.3089 13.2503 15.915 13.8459C15.6883 14.1885 15.4068 14.4865 15.0832 14.7265C14.2082 15.375 12.974 15.375 10.5055 15.375H7.49454C5.02608 15.375 3.79185 15.375 2.91696 14.7265C2.59328 14.4865 2.31175 14.1885 2.0851 13.8459C1.69115 13.2503 1.55054 12.4973 1.50036 11.3408C1.49178 11.1433 1.66188 10.9922 1.84829 10.9922C2.88763 10.9922 3.73018 10.1003 3.73018 9C3.73018 7.89975 2.88763 7.00781 1.84829 7.00781Z" stroke="currentColor" stroke-linejoin="round" stroke-width="1.125" />
                                        <path d="M7.12505 10.875L10.8751 7.125" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.125" />
                                        <path d="M7.12505 7.125H7.13347M10.8666 10.875H10.8751" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
                                    </svg>
                                </div>
                                <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Vat/Tax') }}</p>
                            </a>

                            <a href="{{ route('admin.affiliates.index') }}" class="flex gap-2.5 items-center relative w-full shrink-0 px-2.5 py-2 {{ request()->routeIs('admin.affiliates.*') ? 'text-[var(--sidebar-nav-active-text)] bg-[var(--sidebar-nav-active-bg)]' : 'text-[var(--sidebar-nav-text)]' }} hover:bg-[var(--sidebar-nav-hover-bg)] hover:text-[var(--sidebar-nav-hover)] transition-colors">
                                <div class="relative shrink-0 w-[16px] h-[16px]">
                                    <svg class="block w-full h-full" fill="none" preserveAspectRatio="none" viewBox="0 0 18 18">
                                        <path d="M3.75 15.75V3.75C3.75 2.92157 4.42157 2.25 5.25 2.25H12.75C13.5784 2.25 14.25 2.92157 14.25 3.75V15.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.125" />
                                        <path d="M6 6.75H12" stroke="currentColor" stroke-linecap="round" stroke-width="1.125" />
                                        <path d="M6 9.75H12" stroke="currentColor" stroke-linecap="round" stroke-width="1.125" />
                                        <path d="M6 12.75H10.5" stroke="currentColor" stroke-linecap="round" stroke-width="1.125" />
                                    </svg>
                                </div>
                                <p class="font-normal leading-[22px] relative shrink-0 text-sm tracking-[-0.48px]">{{ __('Affiliation') }}</p>
                            </a>
                        </div>
                    </div>

                </nav>

         </div>
     </div>
 </aside>

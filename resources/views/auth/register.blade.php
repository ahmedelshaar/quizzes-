<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <x-input-label for="first_name" value="الاسم الاول" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <div class="mb-3">
            <x-input-label for="last_name" value="الاسم الاخير" />
            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autofocus />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <div class="mb-3">
            <x-input-label for="mobile" value="رقم الهاتف" />
            <x-text-input id="mobile" class="block mt-1 w-full" type="number" name="mobile" :value="old('mobile')" required autofocus />
            <x-input-error :messages="$errors->get('mobile')" class="mt-2" />
        </div>

        <div class="mb-3">
            <x-input-label for="username" value="اسم المستخدم" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <div class="mb-3">
            <x-input-label for="email" value="الايميل" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mb-3">
            <x-input-label for="password" value="رقم السري" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <x-input-label for="password_confirmation" value="تاكيد الرقم السري" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <x-primary-button >
                تسجيل
            </x-primary-button>
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                انت مسجل بالفعل ؟
            </a>
        </div>
    </form>
</x-guest-layout>

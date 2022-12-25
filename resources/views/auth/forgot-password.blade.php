<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        هل نسيت كلمة المرور؟ لا مشكلة. ما عليك سوى إعلامنا بعنوان بريدك الإلكتروني وسنرسل إليك عبر البريد الإلكتروني رابطًا لإعادة تعيين كلمة المرور يسمح لك باختيار كلمة مرور جديدة.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="الايميل" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center mt-4">
            <x-primary-button>
                اعادة تعيين الرقم السري
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

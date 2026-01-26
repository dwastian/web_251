@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="flex h-full items-center justify-center p-4">
        <div
            class="w-full max-w-md transform rounded-3xl bg-white/90 p-8 shadow-2xl backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-3xl">
            <div class="mb-8 text-center">
                <div
                    class="mb-4 inline-flex items-center justify-center rounded-full bg-blue-600 p-4 shadow-lg ring-4 ring-blue-100">
                    <i class="fa fa-lock text-3xl text-white"></i>
                </div>
                <h3 class="text-3xl font-extrabold tracking-tight text-gray-900">Selamat Datang</h3>
                <p class="mt-2 text-sm font-medium text-gray-500">Silakan masuk untuk mengelola pengiriman</p>
            </div>

            <form id="login-form" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Email Address</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3 text-gray-400">
                            <i class="fa fa-envelope"></i>
                        </span>
                        <input type="email" name="email"
                            class="w-full rounded-xl border-0 bg-gray-100 py-3 pl-10 pr-4 text-gray-900 ring-2 ring-transparent transition-all focus:bg-white focus:outline-none focus:ring-blue-500"
                            placeholder="admin@admin.com" required autofocus>
                    </div>
                    <div class="mt-1 text-xs text-red-600" id="error-email"></div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Password</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3 text-gray-400">
                            <i class="fa fa-key"></i>
                        </span>
                        <input type="password" name="password"
                            class="w-full rounded-xl border-0 bg-gray-100 py-3 pl-10 pr-4 text-gray-900 ring-2 ring-transparent transition-all focus:bg-white focus:outline-none focus:ring-blue-500"
                            placeholder="••••••••" required>
                    </div>
                    <div class="mt-1 text-xs text-red-600" id="error-password"></div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2 cursor-pointer group">
                        <input type="checkbox" name="remember" id="remember"
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-600 group-hover:text-gray-900">Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" id="btn-login"
                    class="group relative flex gap-2 w-full items-center justify-center rounded-xl bg-blue-600 py-3 px-4 text-sm font-bold text-white shadow-xl transition-all hover:bg-blue-700 hover:shadow-blue-200 focus:outline-none focus:ring-4 focus:ring-blue-300 active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed">
                    <span id="btn-text" class="flex items-center">
                        <i class="fa fa-sign-in-alt mr-2 group-hover:translate-x-1 transition-transform"></i>
                        Log In
                    </span>
                    <i id="btn-spinner" class="fa fa-spinner fa-spin absolute !hidden"></i>
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#login-form').on('submit', function (e) {
                    e.preventDefault();

                    const btn = $('#btn-login');
                    const btnText = $('#btn-text');
                    const btnSpinner = $('#btn-spinner');

                    // Reset errors
                    $('[id^="error-"]').text('');
                    $('input').removeClass('ring-red-500').addClass('ring-transparent');

                    btn.prop('disabled', true);
                    btnText.addClass('invisible');
                    btnSpinner.removeClass('hidden');

                    const formData = {
                        _token: '{{ csrf_token() }}',
                        email: $('input[name="email"]').val(),
                        password: $('input[name="password"]').val(),
                        remember: $('input[name="remember"]').is(':checked')
                    };

                    fetch('/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(formData)
                    })
                        .then(async res => {
                            const data = await res.json();
                            if (!res.ok) {
                                if (res.status === 422) {
                                    Object.keys(data.errors).forEach(key => {
                                        $(`#error-${key}`).text(data.errors[key][0]);
                                        $(`input[name="${key}"]`).addClass('ring-red-500').removeClass('ring-transparent');
                                    });
                                    throw new Error('Validasi gagal');
                                }
                                throw new Error(data.message || 'Login gagal');
                            }
                            return data;
                        })
                        .then(data => {
                            window.location.href = data.redirect;
                        })
                        .catch(err => {
                            console.error(err);
                            if (err.message !== 'Validasi gagal') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Login Gagal',
                                    text: err.message,
                                    confirmButtonColor: '#2563eb'
                                });
                            }
                            btn.prop('disabled', false);
                            btnText.removeClass('invisible');
                            btnSpinner.addClass('!hidden');
                        });
                });
            });
        </script>
    @endpush
@endsection
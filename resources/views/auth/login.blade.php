<x-guest-layout>
    <div class="min-vh-100 d-flex align-items-center justify-content-center bg-gradient" style="background: linear-gradient(to right, #6366F1, #8B5CF6); padding: 2rem 1rem;">
        <div class="card shadow rounded-4 w-100 animate__animated animate__fadeInDown" style="max-width: 400px;"> {{-- Added animate__animated and animate__fadeInDown --}}
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <img src="https://smkn2sumedang.sch.id/images/profil/sejarah.png"
                         alt="Logo SMKN 2 Sumedang"
                         class="img-fluid mb-3"
                         style="height: 60px;">

                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135755.png"
                         alt="Login Illustration"
                         class="img-fluid mb-3"
                         style="height: 80px;">

                    <h2 class="h5 fw-bold text-dark">Selamat Datang Kepala Kelas 👋</h2> {{-- Changed "Siswa" to "Kepala Kelas" --}}
                    <p class="text-muted small">Masuk ke akunmu untuk mengelola agenda dan kehadiran guru mata pelajaran.</p> {{-- Updated description --}}
                </div>

                @if (session('status'))
                    <div class="alert alert-info text-center small">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-start mb-3"> {{-- Changed justify-content-between to start --}}
                        <div class="form-check">
                            <input type="checkbox" id="remember_me" name="remember" class="form-check-input">
                            <label for="remember_me" class="form-check-label small">Ingat saya</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mb-3"> {{-- Added py-2 and mb-3 --}}
                        Masuk
                    </button>

                    @if (Route::has('password.request'))
                        <div class="text-center">
                            <a class="text-muted small">
                                Lupa kata sandi? Hubungi Admin.
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="navbar-collapse" id="navbarSupportedContent">
                    <!-- Navbar Items -->
                    <ul class="navbar-nav">
                        @auth
                            {{-- メニュー項目 --}}
                            @if (auth()->user()->role === 'student')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('student.daily_logs.index') }}">マイ連絡帳</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('student.calendar') }}">提出カレンダー</a>
                                </li>
                            @endif

                            @if (auth()->user()->role === 'teacher')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('teacher.daily_logs.index') }}">
                                        提出状況（{{ $teacherAssignedClasses ?? '担当クラス' }}）
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('teacher.students.index') }}">生徒別過去記録</a>
                                </li>
                            @endif

                            @if (auth()->user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.users.index') }}">ユーザー管理</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.classrooms.index') }}">クラス管理</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Spacer -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item">
                                <span class="nav-link text-muted">未ログイン</span>
                            </li>
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                        @endguest
                        
                        @auth
                            {{-- ログイン状態 --}}
                            <li class="nav-item">
                                <span class="nav-link text-success">ログイン中: {{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                            </li>
                            {{-- マニュアル --}}
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('manual.show') }}">マニュアル</a>
                            </li>
                            {{-- ログアウト --}}
                            <li class="nav-item">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="nav-link btn btn-link text-decoration-none" style="padding: 0.5rem 1rem;">ログアウト</button>
                                </form>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    
    <!-- Bootstrap JS は Vite 経由（resources/js/app.js 内で import） -->
</body>
</html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDTrace - Hệ thống Truy xuất nguồn gốc</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0A2540',
                        accent: '#10B981',
                        'accent-dark': '#059669',
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50 flex flex-col min-h-screen">

    @include('layouts.header')

    <main class="flex-grow pt-24">
        @yield('content')
    </main>

    @include('layouts.footer')

</body>
</html>
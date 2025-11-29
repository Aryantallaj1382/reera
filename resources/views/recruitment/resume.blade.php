<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Resume</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gray-100 font-sans antialiased">

<!-- HEADER -->
<header class="bg-gradient-to-r from-blue-900 to-blue-400 text-white py-12 shadow-lg rounded-b-3xl relative">
    <div class="max-w-4xl mx-auto px-6 flex flex-col md:flex-row items-center gap-6">
        <img src="https://api.reera.co/public/ad_images/1761833376_photo-0.jpg" alt="Profile"
             class="w-32 h-32 rounded-full border-4 border-white shadow-2xl object-cover ring-4 ring-blue-200">
        <div>
            <h1 class="text-4xl font-bold drop-shadow-lg">{{$user->name}}</h1>
            <p class="text-blue-100 mt-2"> سن : {{$user->age}}</p>
            <p class="text-blue-100"> موبایل : {{$user->mobile}}</p>
        </div>
    </div>
</header>
<div class="absolute top-5 right-5">
    <a href="{{ route('resume.pdf', $user->id) }}"
       class="bg-white text-blue-600 font-semibold px-4 py-2 rounded-lg shadow hover:bg-blue-50 transition">
        دانلود PDF
    </a>
</div>

<!-- MAIN CONTENT -->
<main class="max-w-4xl mx-auto -mt-10 bg-white rounded-2xl shadow-2xl p-8 space-y-10 relative z-10">

    <!-- Languages -->
    <section>
        <h2 class="text-2xl font-semibold text-gray-800 border-b-2 border-blue-500 pb-2 mb-4">Languages</h2>
        <ul class="space-y-2">
            @foreach($languages as $lang)
                <li class="flex justify-between">
                    <span class="font-medium text-gray-700">{{$lang->language->title}}</span>
                    <span class="text-gray-500"> {{$lang->level}}</span>
                </li>
            @endforeach

        </ul>
    </section>

    <!-- Work Experience -->
    <section>
        <h2 class="text-2xl font-semibold text-gray-800 border-b-2 border-blue-500 pb-2 mb-4">Work Experience</h2>
        <div class="space-y-4">
            @foreach($works as $work)

                <div class="border rounded-xl p-5 hover:shadow-md transition bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">{{$work->title}}</h3>
                    <p class="text-gray-600">                    {{ $work->company_name . ' ' . $work->start_year . '-' . ($work->is_current ? 'تا اکنون' : $work->end_year) }}
                    </p>
                    <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                        {{$work->description}}
                    </p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Education -->
    <section>
        <h2 class="text-2xl font-semibold text-gray-800 border-b-2 border-blue-500 pb-2 mb-4">Education</h2>
        <div class="space-y-4">
            @foreach($educations  as $education )
                <div class="border rounded-xl p-5 hover:shadow-md transition bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">{{$education->major .' ('. $education->degree.')'}}</h3>
                    <p class="text-gray-600"> {{ $work->university_name . ' ' . $work->start_year . '-' . ($work->is_current ? 'تا اکنون' : $work->end_year) }}</p>
                    <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                        {{$work->description}}
                    </p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Skills -->
    <section>
        <h2 class="text-2xl font-semibold text-gray-800 border-b-2 border-blue-500 pb-2 mb-4">Skills</h2>
        <div class="flex flex-wrap gap-2">
            @foreach($skills as $skill)
            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">{{$skill->name}}</span>
            @endforeach

        </div>
    </section>
</main>

<footer class="text-center text-gray-500 text-sm mt-10 mb-6">
    © 2025 - User Resume Page
</footer>

</body>
</html>

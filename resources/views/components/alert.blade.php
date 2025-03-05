@session('error')
    <div class="w-full bg-red-300 text-red-800 p-4 rounded-md">
        {{ session('error') }}
    </div>
@endsession
@session('success')
    <div class="w-full bg-green-300 text-green-800 p-4 rounded-md">
        {{ session('success') }}
    </div>
@endsession

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Train Booking Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <strong class="font-bold">Security Alert!</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <h3 class="text-lg font-bold mb-4">Book Your Ticket</h3>
                
                <form action="{{ route('book.ticket') }}" method="POST">
                    @csrf <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Select Train:</label>
                        <select name="train_id" class="w-full border rounded px-3 py-2 text-gray-700">
                            @foreach($trains as $train)
                                <option value="{{ $train->id }}">
                                    {{ $train->name }} to {{ $train->destination }} 
                                    ({{ $train->seats_available }} seats left)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">
                            Passport Number (Format: A12345678):
                        </label>
                        <input type="text" name="passport_number" class="w-full border rounded px-3 py-2 text-gray-700" placeholder="A12345678">
                        <p class="text-xs text-gray-500 mt-1">Must start with a letter followed by 7-9 numbers.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Number of Seats:</label>
                        <input type="number" name="seats" class="w-full border rounded px-3 py-2 text-gray-700" min="1" max="5">
                    </div>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Secure Checkout
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
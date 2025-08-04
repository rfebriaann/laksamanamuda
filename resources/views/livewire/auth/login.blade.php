<div class="flex items-center justify-center h-screen bg-gray-100">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center">Login</h2>

        @if (session()->has('error'))
            <div class="mb-4 text-red-500">{{ session('error') }}</div>
        @endif

        <form wire:submit.prevent="login">
            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                <input type="email" wire:model="email" id="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-200" />
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                <input type="password" wire:model="password" id="password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-200" />
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full p-2 mt-4 text-white bg-blue-600 rounded-lg hover:bg-blue-700">Login</button>
        </form>
    </div>
</div>
<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Title('Page User')] class extends Component {
    use WithFileUploads, WithPagination, WithoutUrlPagination;

    public $name = '';
    public $email = '';
    public $password = '';
    public $avatar;

    #[Computed]
    public function users()
    {
        return User::latest()->simplePaginate(5);
    }

    public function createUser()
    {
        $validated = $this->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email:dns', 'unique:users,email'],
            'password' => ['required', 'min:3'],
            'avatar' => ['image', 'max:5000', 'nullable'],
        ]);

        if ($this->avatar) {
            $validated['avatar'] = $this->avatar->store('avatars', 'public');
        }

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'avatar' => $validated['avatar'],
        ];

        User::create($userData);

        $this->reset(['name', 'email', 'password', 'avatar']);

        $this->resetPage();

        session()->flash('success', 'New user has been created');
    }
};
?>

<div class="flex justify-center gap-10">
    <div class="my-10 w-1/3">
        <h1 class="font-bold text-gray-900 text-2xl/9 text-center tracking-tight">Create New User</h1>

        @if (@session('success'))
            <div
                x-data="{ show: true }"
                x-init="
                    setTimeout(() => {
                        show = false;
                    }, 3000)
                "
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                class="bg-green-50 my-4 p-4 rounded-lg text-green-800 text-sm"
                role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-3">
            <form action="#" class="space-y-6" wire:submit.prevent="createUser">
                @csrf

                <div>
                    <label for="name" class="block font-medium text-gray-900 text-sm/6">Name</label>
                    <div class="mt-2">
                        <input
                            type="text"
                            wire:model="name"
                            name="name"
                            id="name"
                            autocomplete="name"
                            class="block bg-white px-3 py-1.5 rounded-md outline-1 outline-gray-300 focus:outline-2 focus:outline-indigo-600 outline-offset-1 focus:-outline-offset-2 w-full text-gray-900 placeholder:text-gray-400 sm:text-sm/6 text-base" />
                        @error ('name')
                            <p class="mt-2 font-medium text-red-600 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="email" class="block font-medium text-gray-900 text-sm/6">Email</label>
                    <div class="mt-2">
                        <input
                            type="email"
                            wire:model="email"
                            name="email"
                            id="email"
                            autocomplete="email"
                            class="block bg-white px-3 py-1.5 rounded-md outline-1 outline-gray-300 focus:outline-2 focus:outline-indigo-600 outline-offset-1 focus:-outline-offset-2 w-full text-gray-900 placeholder:text-gray-400 sm:text-sm/6 text-base" />
                        @error ('email')
                            <p class="mt-2 font-medium text-red-600 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="password" class="block font-medium text-gray-900 text-sm/6">Password</label>
                    <div class="mt-2">
                        <input
                            type="password"
                            wire:model="password"
                            name="password"
                            id="password"
                            autocomplete="current-password"
                            class="block bg-white px-3 py-1.5 rounded-md outline-1 outline-gray-300 focus:outline-2 focus:outline-indigo-600 outline-offset-1 focus:-outline-offset-2 w-full text-gray-900 placeholder:text-gray-400 sm:text-sm/6 text-base" />
                        @error ('password')
                            <p class="mt-2 font-medium text-red-600 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-span-full">
                    <label for="profile-picture" class="block font-medium text-gray-900 text-sm/6"
                        >Profile Picture</label
                    >
                    <div class="flex justify-center mt-2 px-6 py-6 border border-gray-900/25 border-dashed rounded-lg">
                        <div class="text-center">
                            <svg
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                data-slot="icon"
                                aria-hidden="true"
                                class="mx-auto size-12 text-gray-300">
                                <path
                                    d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z"
                                    clip-rule="evenodd"
                                    fill-rule="evenodd" />
                            </svg>
                            <div class="flex mt-4 text-gray-600 text-sm/6">
                                <label
                                    for="avatar"
                                    class="relative bg-transparent rounded-md focus-within:outline-2 focus-within:outline-indigo-600 focus-within:outline-offset-2 font-semibold text-indigo-600 hover:text-indigo-500 cursor-pointer">
                                    <span>Upload a file</span>
                                    <input
                                        id="avatar"
                                        type="file"
                                        name="avatar"
                                        class="sr-only"
                                        accept="image/png, image/jpg, image/jpeg"
                                        wire:model="avatar" />
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-gray-600 text-xs/5">PNG, JPG to 5MB</p>
                        </div>
                    </div>
                    @error ('avatar')
                        <p class="mt-2 font-medium text-red-600 text-xs">{{ $message }}</p>
                    @enderror
                </div>

                <div
                    wire:loading.flex
                    wire:target="avatar"
                    class="flex justify-center items-center bg-gray-100 border border-gray-300 rounded-lg w-20 h-20 font-medium text-blue-700 text-xs shrink-0">
                    <div
                        class="bg-blue-200 px-2 py-px rounded-full ring-1 ring-blue-200 ring-inset font-medium text-blue-800 text-xs animate-pulse">
                        loading...
                    </div>
                </div>

                @if ($avatar)
                    <img src="{{ $avatar->temporaryUrl() }}" class="block rounded w-20 h-20 object-cover" />
                @endif

                <button
                    type="submit"
                    class="flex justify-center bg-indigo-700 hover:bg-indigo-600 py-1 rounded-md w-full font-bold text-white text-sm cursor-pointer">
                    <svg
                        wire:loading
                        wire:target="createUser"
                        aria-hidden="true"
                        class="self-center fill-blue-600 me-2 w-4 h-4 text-gray-400 animate-spin"
                        viewBox="0 0 100 101"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="currentColor" />
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentFill" />
                    </svg>
                    <span wire:loading wire:target="createUser">Loading...</span>
                    <span wire:loading.remove wire:target="createUser">Create User</span>
                </button>
            </form>
        </div>
    </div>

    <div class="my-10 w-1/3">
        <h1 class="font-bold text-gray-900 text-2xl/9 text-center tracking-tight">User List</h1>
        <ul id="paginated-users" role="list" class="divide-y divide-gray-100">
            @foreach ($this->users as $user)
                <li class="flex justify-between gap-x-6 py-5">
                    <div class="flex gap-x-4 min-w-0">
                        <img
                            src="{{ $user->avatar ? Storage::url($user->avatar) : asset('img/default-ava.png') }}"
                            alt=""
                            class="flex-none bg-gray-50 rounded-full size-12" />
                        <div class="flex-auto min-w-0">
                            <p class="font-semibold text-gray-900 text-sm/6">{{ $user->name }}</p>
                            <p class="mt-1 text-gray-500 text-xs/5 truncate">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="hidden sm:flex sm:flex-col sm:items-end self-center shrink-0">
                        <p class="mt-1 text-gray-500 text-xs/5">Joined {{ $user->created_at->diffForHumans() }}</p>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-6 pt-4 border-gray-100 border-t">
            {{
                $this->users->links(
                    data: ['scrollTo' => '#paginated-users'],
                )
            }}
        </div>
    </div>
</div>

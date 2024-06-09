<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MLP To-Do</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/images/favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <noscript>
        <style>
            .js-disabled {display:none!important;}
        </style>
    </noscript>

</head>

<body class="min-h-screen">
    <header class="container mx-auto px-4 mt-4 mb-4">
        <img class="px-4" src="{{ asset('storage/images/logo.png') }}" alt="Logo">
    </header>
    <div class="container mx-auto px-4">
        <div class="flex">
            <div class="md:w-1/2 max-w-xs mx-auto mt-10 px-4">
                <form id="add-task-form" action="/" method="post">
                    @csrf
                    <div class="mb-4">
                        <input
                            class="appearance-none border border-gray-400 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="name" type="text" name="name" placeholder="Insert task name">
                    </div>
                    <div class="flex items-center justify-between">
                        <button
                            class="w-full bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            type="submit">
                            Add
                        </button>
                    </div>
                </form>
            </div>
            <div class="w-full mx-auto mt-10 px-4">
                <div class="bg-white px-4 pb-4 border border-gray-400">
                    <table id="task-list" class="w-full border-collapse">
                        <thead>
                            <tr class="border-bottom border-b-4 border-grey-custom">
                                <th class="py-2 px-4 w-10 text-left font-normal">#</th>
                                <th class="py-2 px-4  text-left font-normal">Task</th>
                                <th class="py-2 px-4 w-36"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $index => $task)
                                <tr id="task-{{ $task->id }}"
                                    class="text-gray-700 text-base @if ($index === count($tasks) - 1) border-bottom-0 @else border-bottom border-b-2 border-grey-custom @endif">
                                    <td class="py-2 px-4">{{ $task->id }}</td>
                                    <td class="py-2 px-4">
                                        <a href="#"
                                            class="js-disabled edit-link hover:text-blue-500 @if ($task->completed) line-through @endif"
                                            data-id="{{ $task->id }}" data-name="{{ $task->name }}"
                                            data-completed="{{ $task->completed }}">{{ $task->name }}</a>
                                        <noscript>
                                            <p class="@if ($task->completed) line-through @endif">{{ $task->name }}</p>
                                        </noscript>
                                    </td>
                                    <td class="py-2 px-4">
                                        <div class="actions flex flex-row justify-center">
                                            <button type="submit"
                                                class="js-disabled complete-task bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline mr-2 @if ($task->completed) opacity-50 cursor-not-allowed @endif"
                                                data-id="{{ $task->id }}" data-name="{{ $task->name }}"
                                                data-completed="{{ $task->completed }}"
                                                @if ($task->completed) disabled @endif>
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                            <noscript>
                                                <form id="complete-task-form-{{ $task->id }}" action="{{ route('tasks.complete', $task->id) }}" method="post">
                                                    @csrf
                                                    <button type="submit"
                                                        class="complete-task bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline mr-2 @if ($task->completed) opacity-50 cursor-not-allowed @endif"
                                                        data-id="{{ $task->id }}" data-name="{{ $task->name }}"
                                                        data-completed="{{ $task->completed }}"
                                                        @if ($task->completed) disabled @endif>
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </noscript>

                                            <button type="button"
                                                class="js-disabled delete-task bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline"
                                                data-id="{{ $task->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                            <noscript>
                                                <form id="delete-task-form-{{ $task->id }}" action="{{ route('tasks.deleteTask', $task->id) }}" method="post">
                                                    @csrf
                                                    <button type="submit"
                                                        class="delete-task bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline"
                                                        data-id="{{ $task->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </noscript>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="edit-modal"
            class="hidden fixed top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-4 rounded">
                <h2 class="text-2xl mb-4">Edit Task</h2>
                <form id="edit-form">
                    <input type="hidden" id="edit-id">
                    <input type="text" id="edit-name" class="border p-2 w-full mb-2">
                    <div class="flex items-center">
                        <input type="checkbox" id="edit-completed" class="mr-2">
                        <label for="edit-completed" class="text-gray-700">Completed</label>
                    </div>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4">Save</button>
                </form>
            </div>
        </div>
    </div>

    <footer class="sticky top-[100vh] my-2">
        <p class="w-full py-2 text-center">Copyright &copy; {{ now()->year }} All Rights Reserved.</p>
    </footer>
</body>

</html>

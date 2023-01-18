<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in!
                </div>
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form class="form-inline" id="gen-token">
                    <div class="form-group mx-sm-3 mb-2">
                        <label for="token" class="sr-only">API Token</label>
                        <input type="text" class="form-control" id="token" value="{{$api_token}}" placeholder="API Token" >
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Generate Token</button>
                </form>
        </div>
    </div>

</x-app-layout>

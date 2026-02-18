@props(['method' => 'POST'])

<form
    method="{{ strtoupper($method) === 'GET' ? 'GET' : 'POST' }}"
    {{ $attributes }}
>
    @unless(strtoupper($method) === 'GET')
        @csrf

        @if(in_array(strtoupper($method), ['PUT', 'PATCH', 'DELETE']))
            @method(strtoupper($method))
        @endif
    @endunless

    {{ $slot }}
</form>

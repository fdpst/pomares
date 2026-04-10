<tr>
    <td class="header">
        <a href="{{ url('/') }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
                <img src="{{ URL::asset('logo_menu.png') }}" class="logo" style="width:auto !important" alt="Martí Pomares, S.L">
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>

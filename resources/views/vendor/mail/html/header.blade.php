<tr>
    <td class="header">
        <a href="https://app.fidifactu.com/" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
                <img src="{{ URL::asset('logo_menu.png') }}" class="logo" style="width:auto !important" alt="Fidifactu">
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>

@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            
            <img src="{{ asset('images/logo4.png') }}" alt="QuestLog" style="height: 50px;">
            
            @if (trim($slot) === 'Laravel')
                <span style="margin-left: 10px;">QuestLog</span>
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>

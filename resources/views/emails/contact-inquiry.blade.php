<x-mail::message>
# New inquiry received via varidianlab.com

<x-mail::table>
| | |
|:--|:--|
| **Name** | {{ $data['name'] }} |
| **Phone / WhatsApp** | {{ $data['phone'] }} |
| **Email** | {{ $data['email'] ?? 'Not provided' }} |
| **Organisation** | {{ $data['organisation'] ?? 'Not provided' }} |
| **Product interest** | {{ $data['product_interest'] }} |
</x-mail::table>

**Message:**

<x-mail::panel>
{{ $data['message'] }}
</x-mail::panel>

Received: {{ now()->format('d M Y, H:i T') }}<br>
Reply to this email or WhatsApp: +260 97 1864421
</x-mail::message>

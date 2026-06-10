New inquiry received via varidianlab.com
========================================

Name:             {{ $data['name'] }}
Phone / WhatsApp: {{ $data['phone'] }}
Email:            {{ $data['email'] ?? 'Not provided' }}
Organisation:     {{ $data['organisation'] ?? 'Not provided' }}
Product interest: {{ $data['product_interest'] }}

Message:
--------
{{ $data['message'] }}

========================================
Received: {{ now()->format('d M Y, H:i T') }}
Reply to this email or WhatsApp: +260 97 1864421

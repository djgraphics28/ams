<div>
    @php
        $qrCode = QrCode::size(200)->generate($qr_code); // Adjust the data as needed
    @endphp

    <div>
        {!! $qrCode !!}
    </div>
</div>

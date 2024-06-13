<div>
    @php
        $qrCode = QrCode::size(200)->generate('asdjklahdakjhdsajkhdkjsahd'); // Adjust the data as needed
    @endphp

    <div>
        {!! $qrCode !!}
    </div>
</div>

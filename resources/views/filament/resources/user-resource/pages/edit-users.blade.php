<x-filament-panels::page>
{{--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">--}}
    <h1>Thông tin người dùng</h1>
    <div>
        <p style="display: block">Chứng minh nhân dân mặt trước</p>
{{--        {{dd($record)}}--}}
        <img src="{{ $record['user_identifications']['image_front'] }}" alt="front_identity_card" style="width: 200px; height: 200px">
    </div>

</x-filament-panels::page>

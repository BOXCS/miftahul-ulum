<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Perizinan Santri</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f3f4f6; }
        .text-center { text-align: center; }
        .header { margin-bottom: 30px; }
        .header h2 { margin: 0; padding: 0; }
    </style>
</head>
<body>
    <div class="header text-center">
        <h2>Riwayat Perizinan Santri</h2>
        <p>Pondok Pesantren Miftahul Ulum<br>Tanggal Export: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Santri</th>
                <th>Kelas</th>
                <th>Jenis Izin</th>
                <th>Tgl Mulai</th>
                <th>Tgl Selesai</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permissions as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->student?->name ?? 'N/A' }}</td>
                <td>{{ $p->student?->class ?? '-' }}</td>
                <td>{{ ucfirst($p->jenis) }}</td>
                <td>{{ $p->tanggal_mulai->format('d/m/Y') }}</td>
                <td>{{ $p->tanggal_selesai->format('d/m/Y') }}</td>
                <td>{{ ucfirst($p->status) }}</td>
                <td>{{ $p->keterangan ?? '-' }}
                    @if($p->status == 'ditolak' && $p->catatan)
                        <br><small style="color:red;"><i>Tolak: {{ $p->catatan }}</i></small>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

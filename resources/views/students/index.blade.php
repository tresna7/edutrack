<h1>Daftar Siswa EduTrack</h1>
<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>NIS</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Level Risiko</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($students as $s)
        <tr>
            <td>{{ $s->nis }}</td>
            <td>{{ $s->name }}</td>
            <td>{{ $s->class }}</td>
            <td><strong>{{ $s->risk_level }}</strong></td>
        </tr>
        @endforeach
    </tbody>
</table>
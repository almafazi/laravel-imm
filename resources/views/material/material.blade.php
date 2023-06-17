<table border=1>
    <thead>
        <th>name</th>
        <th>criteria_1</th>
        <th>criteria_2</th>
        <th>information</th>
        <th>grade</th>
        <th>created at</th>
    </thead>
    <tbody>
        @foreach ($materials as $material)
            <tr>
                <td>{{ $material->name }}</td>
                <td>{{ $material->criteria_1 }}</td>
                <td>{{ $material->criteria_2 }}</td>
                <td>{{ $material->information }}</td>
                <td>{{ $material->grade }}</td>
                <td>{{ $material->created_at->format('d m Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<a href="{{ route('material.create') }}">Tambah Data</a>
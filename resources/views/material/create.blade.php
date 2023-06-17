<form action="{{ route('material.store') }}" method="post">
    @csrf
    <input type="text" name="name" placeholder="input name"> <br>
    <input type="text" name="criteria_1" placeholder="input criteria 1"> <br>
    <input type="text" name="criteria_2" placeholder="input criteria 2"> <br>
    <textarea name="information" id="" name="information" cols="30" rows="10"></textarea> <br>
    <select name="grade" id="">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
    </select> <br>
    <button type="submit">Simpan Data</button>
</form>
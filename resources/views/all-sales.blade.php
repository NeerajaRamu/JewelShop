
<script src="//code.jquery.com/jquery-1.12.3.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script
    src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet"
    href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet"
    href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">

<table class="table" id="table">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">Date</th>
            <th class="text-center">User</th>
            <th class="text-center">Region</th>
            <th class="text-center">Total sold</th>
            <th class="text-center">Total Cost</th>
            <th class="text-center">Hours Spent</th>
        </tr>
    </thead>
    <tbody>
        <tbody>
@foreach($data as $item)
<tr class="item{{$item->id}}">
    <td>{{$item->id}}</td>
    <td>{{$item->date}}</td>
    <td>{{$item->time_in}}</td>
    <td>{{$item->time_out}}</td>
    <td>{{$item->total_gold_sold}}</td>
    <td>{{$item->total_amount}}</td>
    <td>{{$item->total_hours_spent}}</td>

</tr>
@endforeach
</tbody>
    </tbody>
</table>
<script>
//  $(document).ready(function() {
//    $('#table').DataTable();
//} );
 $(function() {
        $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: 'https://datatables.yajrabox.com/eloquent/basic-data'
        });
    });
 </script>


<style>
    p, h2, h3 {padding:0px; margin: 0px;}
</style>
@if($traite)
<table>
    <tr>
        <td colspan="2">
            <h2>{{ $traite['first_name'].' '.$traite['last_name'] }}  
                ({{ ($traite['traite_overall']/(count($traite['traites'])*5))*100 }} %)
            </h2>
        </td>
    </tr>
    @foreach ($traite['traites'] as $value)
    <tr>
        <td>
            <h4>{{ $value['title'] }} </h4>
        </td>
        <td>
            <h4>{{ $value['rating'] }} / 5 ({{ ($value['rating']/5)*100 }} %)</h4>
        </td>
    </tr>
    @endforeach
</table>
<hr />
@else
@endif
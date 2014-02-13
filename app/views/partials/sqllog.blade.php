<hr>
<div class="sql-log">
    <table class="table table-bordered">
        <tr>
            <th>Query</th>
            <th>Bindings</th>
            <th>Time (ms)</th>
        </tr>
        <?php $sqlLog = DB::getQueryLog(); ?>
        <?php $totalTime = 0; ?>
        @foreach($sqlLog as $sql)
            <tr>
                <td>{{ $sql['query'] }}</td>
                <td>{{ implode(",", $sql['bindings']) }}</td>
                <td>{{ $sql['time'] }}</td>
            </tr>
            <?php $totalTime += $sql['time']; ?>
        @endforeach
        <tr class="info">
            <td>Total Time</td>
            <td>-</td>
            <td>{{ $totalTime }}</td>
        <tr/>
    </table>
</div>
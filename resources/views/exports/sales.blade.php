@php
	$b = "font-weight: bold;";
	$c = "text-align: center;";
	$bc = "$b $c";
@endphp

<table>
	<thead>
		<tr>
			<th style="text-align: center; {{ $b }} background-color: yellow;">#</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Date</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Company</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Package Name</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Amount</th>
		</tr>
	</thead>
	<tbody>
		@foreach($sales as $date => $data)
			<tr>
				<td style="text-align: center;">{{ $loop->index + 1 }}</td>
				<td style="text-align: center">{{ now()->parse($date)->format("F j, Y") }}</td>
			</tr>

			@foreach($data as $sale)
				<tr>
					<td></td>
					<td></td>
					<td style="text-align: center;">{{ $sale['company'] }}</td>
					<td style="text-align: center;">{{ $sale['name'] }}</td>
					<td style="text-align: center;">{{ $sale['amount'] }}</td>
				</tr>
			@endforeach

			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td style="text-align: right; font-weight: bold;">Total:</td>
				<td style="text-align: center;">=SUM(INDIRECT(ADDRESS(ROW()-{{ sizeof($data) > 0 ? sizeof($data) : 1 }},COLUMN())&#38;":"&#38;ADDRESS(ROW()-1,COLUMN())))</td>
			</tr>
		@endforeach

		<tr></tr>
		<tr></tr>

		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td style="text-align: right;">Grandtotal:</td>
			<td style="text-align: center; color: red; font-weight: bold;">=SUM(INDIRECT(ADDRESS(1,COLUMN())&#38;":"&#38;ADDRESS(ROW()-1,COLUMN())))</td>
		</tr>
	</tbody>
</table>
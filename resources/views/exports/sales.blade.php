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
			<th style="text-align: center; {{ $b }} background-color: yellow;">Amount</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data['dates'] as $key => $date)
			<tr>
				<td style="text-align: center;">{{ $loop->index+1 }}</td>
				<td style="text-align: center;">{{ $date }}</td>
				<td style="text-align: right;">₱{{ isset($data['sales'][$date]) ? number_format($data['sales'][$date], 2) : "0.00" }}</td>
			</tr>
		@endforeach
		<tr>
			<td></td>
			<td style="{{ $c }}">Total</td>
			<td style="{{ $bc }}">₱{{ number_format($data['total'], 2); }}</td>
		</tr>
	</tbody>
</table>
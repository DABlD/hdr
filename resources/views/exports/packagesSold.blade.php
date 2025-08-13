@php
	$b = "font-weight: bold;";
	$c = "text-align: center;";
	$bc = "$b $c";

	$amount = 0;
@endphp

<table>
	<thead>
		<tr>
			<th style="text-align: center; {{ $b }} background-color: yellow;">#</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Patient ID</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Surname</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">First Name</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Gender</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Birthday</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Age</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Company</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Package Name</th>
			{{-- <th style="text-align: center; {{ $b }} background-color: yellow;">Amount</th> --}}
			<th style="text-align: center; {{ $b }} background-color: yellow;">Type</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Date</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Status</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Classification</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Assessment</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $row)
			@php
				$amount += $row->package->amount;
			@endphp
			<tr>
				<td style="text-align: center;">{{ $loop->index+1 }}</td>
				<td style="text-align: center;">{{ $row->user->patient->patient_id }}</td>
				<td style="text-align: center;">{{ $row->user->lname }}</td>
				<td style="text-align: center;">{{ $row->user->fname }}</td>
				<td style="text-align: center;">{{ $row->user->gender }}</td>
				<td style="text-align: center;">{{ isset($row->user->birthday) ? $row->user->birthday->format('M j, Y') : "-" }}</td>
				<td style="text-align: center;">{{ isset($row->user->birthday) ? now()->diffInYears($row->user->birthday) : "-" }}</td>
				<td style="text-align: center;">{{ $row->package->company }}</td>
				<td style="text-align: center;">{{ $row->package->name }}</td>
				{{-- <td style="text-align: center;">₱{{ $row->package->amount }}</td> --}}
				<td style="text-align: center;">{{ $row->type == "PEE" ? "PPE" : $row->type }}</td>
				<td style="text-align: center;">{{ $row->created_at->format('M j, Y') }}</td>
				<td style="text-align: center;">{{ $row->status }}</td>
				<td style="text-align: center;">
					@if($row->classification == "Fit to work")
						A
					@elseif($row->classification == "Physically fit with minor illness")
						B
					@elseif($row->classification == "Employable but with certain impairments or conditions requiring follow-up treatment (employment is at employer's discretion)")
						C
					@elseif($row->classification == "Unfit to work")
						D
					@else
						
					@endif
				</td>
				<td style="text-align: center;">{{ $row->c_remarks }}</td>
			</tr>
		@endforeach

		<tr>
			<td colspan="9"></td>
			<td colspan="1" style="{{ $bc }}">₱{{ number_format($amount, 2) }}</td>
			<td colspan="3"></td>
		</tr>
	</tbody>
</table>
@php
	$b = "font-weight: bold;";
	$c = "text-align: center;";
	$bc = "$b $c";
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
			<th style="text-align: center; {{ $b }} background-color: yellow;">Amount</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Type</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Date</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $row)
			<tr>
				<td style="text-align: center;">{{ $loop->index+1 }}</td>
				<td style="text-align: center;">{{ $row->user->patient->patient_id }}</td>
				<td style="text-align: center;">{{ $row->user->lname }}</td>
				<td style="text-align: center;">{{ $row->user->fname }}</td>
				<td style="text-align: center;">{{ $row->user->gender }}</td>
				<td style="text-align: center;">{{ isset($row->user->birthday) ? $row->user->birthday->format('F j, Y') : "-" }}</td>
				<td style="text-align: center;">{{ isset($row->user->birthday) ? now()->diffInYears($row->user->birthday) : "-" }}</td>
				<td style="text-align: center;">{{ $row->package->company }}</td>
				<td style="text-align: center;">{{ $row->package->name }}</td>
				<td style="text-align: center;">₱{{ $row->package->amount }}</td>
				<td style="text-align: center;">{{ $row->type == "PEE" ? "PPE" : $row->type }}</td>
				<td style="text-align: center;">{{ $row->created_at->format('F j, Y') }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
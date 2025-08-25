@php
	$b = "font-weight: bold;";
	$c = "text-align: center;";
	$bc = "$b $c";

	$amount = 0;

	$totalPackage = array();
	$totalGender = array();
	$totalStatus = array();
	$totalType = array();
	$totalClassification = array();
@endphp

<table>
	<thead>
		<tr>
			<th style="text-align: center; {{ $b }} background-color: yellow;">#</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Patient ID</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Date</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Surname</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">First Name</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Gender</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Birthday</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Age</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Company</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Package Name</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Type</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Assessment</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Classification</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Status</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $row)
			@php
				$amount += $row->package->amount;

				isset($totalPackage[strtoupper($row->package->name)]) ? $totalPackage[strtoupper($row->package->name)]++ : $totalPackage[strtoupper($row->package->name)] = 1;
				isset($totalGender[strtoupper($row->user->gender)]) ? $totalGender[strtoupper($row->user->gender)]++ : $totalGender[strtoupper($row->user->gender)] = 1;
				isset($totalStatus[strtoupper($row->status)]) ? $totalStatus[strtoupper($row->status)]++ : $totalStatus[strtoupper($row->status)] = 1;
				isset($totalType[strtoupper($row->type)]) ? $totalType[strtoupper($row->type)]++ : $totalType[strtoupper($row->type)] = 1;
				isset($totalClassification[strtoupper($row->classification)]) ? $totalClassification[strtoupper($row->classification)]++ : $totalClassification[strtoupper($row->classification)] = 1;
			@endphp
			<tr>
				<td style="text-align: center;">{{ $loop->index+1 }}</td>
				<td style="text-align: center;">{{ $row->user->patient->patient_id }}</td>
				<td style="text-align: center;">{{ $row->created_at->format('M j, Y') }}</td>
				<td style="text-align: center;">{{ $row->user->lname }}</td>
				<td style="text-align: center;">{{ $row->user->fname }}</td>
				<td style="text-align: center;">{{ $row->user->gender }}</td>
				<td style="text-align: center;">{{ isset($row->user->birthday) ? $row->user->birthday->format('M j, Y') : "-" }}</td>
				<td style="text-align: center;">{{ isset($row->user->birthday) ? now()->diffInYears($row->user->birthday) : "-" }}</td>
				<td style="text-align: center;">{{ $row->package->company }}</td>
				<td style="text-align: center;">{{ strtoupper($row->package->name) }}</td>
				<td style="text-align: center;">{{ $row->type == "PEE" ? "PPE" : $row->type }}</td>
				<td style="text-align: center;">{{ $row->c_remarks }}</td>
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
						Pending
					@endif
				</td>
				<td style="text-align: center;">{{ $row->status }}</td>
			</tr>
		@endforeach

		@for($i = 0; $i < max(array_map('count', [$totalPackage, $totalGender, $totalStatus, $totalType, $totalClassification])); $i++)
			<tr>
				<td></td>
				<td>{{ array_keys($totalPackage)[$i] }}</td>
				<td>{{ $totalPackage[array_keys($totalPackage)[$i]] }}</td>
				<td></td>
				@if(isset(array_keys($totalStatus)[$i]))
					<td>{{ array_keys($totalStatus)[$i] }}</td>
					<td>{{ $totalStatus[array_keys($totalStatus)[$i]] }}</td>
				@else
					<td></td>
					<td></td>
				@endif
				<td></td>
				@if(isset(array_keys($totalClassification)[$i]))
					<td>{{ array_keys($totalClassification)[$i] }}</td>
					<td>{{ $totalClassification[array_keys($totalClassification)[$i]] }}</td>
				@else
					<td></td>
					<td></td>
				@endif
				<td></td>
				@if(isset(array_keys($totalType)[$i]))
					<td>{{ array_keys($totalType)[$i] }}</td>
					<td>{{ $totalType[array_keys($totalType)[$i]] }}</td>
				@else
					<td></td>
					<td></td>
				@endif
				<td></td>
				@if(isset(array_keys($totalGender)[$i]))
					<td>{{ array_keys($totalGender)[$i] }}</td>
					<td>{{ $totalGender[array_keys($totalGender)[$i]] }}</td>
				@else
					<td></td>
					<td></td>
				@endif
				<td></td>
			</tr>
		@endfor
	</tbody>
</table>
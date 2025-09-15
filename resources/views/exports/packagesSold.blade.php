@php
	$b = "font-weight: bold;";
	$c = "text-align: center;";
	$bc = "$b $c";

	$amount = 0;

	$totalPackage = $data->totalPackage;
	$totalGender = $data->totalGender;
	$totalStatus = $data->totalStatus;
	$totalType = $data->totalType;
	$totalClassification = $data->totalClassification;
	$ageGroups = $data->ageGroups;
@endphp

<table>
	<thead>
		<tr>
			<th style="text-align: center; {{ $b }} background-color: yellow;">#</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Patient ID</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Date</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;" colspan="2">Surname</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;" colspan="2">First Name</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Gender</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Birthday</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Age</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;" colspan="6">Company</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;" colspan="8">Package Name</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Type</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Assessment</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Classification</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Status</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $row)
			<tr>
				<td style="text-align: center;">{{ $loop->index+1 }}</td>
				<td style="text-align: center;">{{ $row->user->patient->patient_id }}</td>
				<td style="text-align: center;">{{ $row->created_at->format('M d, Y') }}</td>
				<td style="text-align: center;" colspan="2">{{ $row->user->lname }}</td>
				<td style="text-align: center;" colspan="2">{{ $row->user->fname }}</td>
				<td style="text-align: center;">{{ $row->user->gender }}</td>
				<td style="text-align: center;">{{ isset($row->user->birthday) ? $row->user->birthday->format('M d, Y') : "-" }}</td>
				<td style="text-align: center;">{{ isset($row->user->birthday) ? now()->diffInYears($row->user->birthday) : "-" }}</td>
				<td style="text-align: center;" colspan="6">{{ $row->package->company }}</td>
				<td style="text-align: center;" colspan="8">{{ strtoupper($row->package->name) }}</td>
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

		<tr>
			<td colspan="13"></td>
		</tr>

		<tr>
			<td></td>
			<td colspan="4">Total Packages:</td>
			<td></td>
			<td colspan="5">Total Classification:</td>
			<td></td>
			<td colspan="2">Age Group:</td>
			<td></td>
			<td colspan="2">Total Per Exam Type:</td>
			<td></td>
			<td colspan="2">Total Status:</td>
			<td></td>
			<td colspan="2">Total Gender:</td>
		</tr>

		@for($i = 0; $i < $data->maxLength; $i++)
			<tr>
				<td></td>
				<td colspan="3">{{ isset(array_keys($totalPackage)[$i]) ? array_keys($totalPackage)[$i] : "" }}</td>
				<td style="text-align: center;">{{ isset(array_keys($totalPackage)[$i]) ? $totalPackage[array_keys($totalPackage)[$i]] : "" }}</td>
				<td></td>
				@if(isset(array_keys($totalClassification)[$i]))
					<td colspan="4">{{ array_keys($totalClassification)[$i] != "" ? array_keys($totalClassification)[$i] : "PENDING"}}</td>
					<td style="text-align: left;">{{ $totalClassification[array_keys($totalClassification)[$i]] }}</td>
				@else
					<td colspan="4"></td>
					<td></td>
				@endif
				<td></td>
				@if(isset(array_keys($ageGroups)[$i]))
					<td>{{ array_keys($ageGroups)[$i] }}</td>
					<td style="text-align: left;">{{ $ageGroups[array_keys($ageGroups)[$i]] }}</td>
				@else
					<td colspan="4"></td>
					<td></td>
				@endif
				<td></td>
				@if(isset(array_keys($totalType)[$i]))
					<td>{{ array_keys($totalType)[$i] }}</td>
					<td style="text-align: left;">{{ $totalType[array_keys($totalType)[$i]] }}</td>
				@else
					<td></td>
					<td></td>
				@endif
				<td></td>
				@if(isset(array_keys($totalStatus)[$i]))
					<td>{{ ucfirst(strtolower(array_keys($totalStatus)[$i])) }}</td>
					<td style="text-align: left;">{{ $totalStatus[array_keys($totalStatus)[$i]] }}</td>
				@else
					<td></td>
					<td></td>
				@endif
				<td></td>
				@if(isset(array_keys($totalGender)[$i]))
					<td>{{ ucfirst(strtolower(array_keys($totalGender)[$i])) }}</td>
					<td style="text-align: left;">{{ $totalGender[array_keys($totalGender)[$i]] }}</td>
				@else
					<td></td>
					<td></td>
				@endif
				<td></td>
			</tr>
		@endfor

		<tr>
			<td colspan="25"></td>
		</tr>
	</tbody>
</table>
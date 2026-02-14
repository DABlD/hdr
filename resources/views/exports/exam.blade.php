@php
	$b = "font-weight: bold;";
	$c = "text-align: center;";
	$bc = "$b $c";

	$ageGroups = $data->ageGroups;
	$totalPackage = $data->totalPackage;
	$totalGender = $data->totalGender;
@endphp

<table style="border: 1px solid black;">
	<thead>
		<tr>
			<th style="text-align: center; {{ $b }} background-color: yellow;" colspan="5">Company Name</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Patient ID</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Surname</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;" colspan="3">First Name</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;" colspan="2">Gender</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Birthday</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Age</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Package Name</th>
			{{-- <th style="text-align: center; {{ $b }} background-color: yellow;">Amount</th> --}}
			<th style="text-align: center; {{ $b }} background-color: yellow;">Date</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $examinee)
			@php
				// $amount = 0;
				$latestPackage = "-";

				if(sizeof($examinee->user->patient->exams)){
					$temp = json_decode($examinee->user->patient->exams->last()->details);

					if(!in_array($temp->name, ['Personal Medical History', 'Medical Examination Report'])){
						// $amount = $temp->amount;
						$latestPackage = $temp->name;
					}
				}
			@endphp
			<tr>
				<td style="text-align: center;" colspan="5">{{ $examinee->user->patient->company_name }}</td>
				<td style="text-align: center;">{{ $examinee->user->patient->patient_id }}</td>
				<td style="text-align: center;">{{ $examinee->user->lname }}</td>
				<td style="text-align: center;" colspan="3">{{ $examinee->user->fname }}</td>
				<td style="text-align: center;" colspan="2">{{ $examinee->user->gender }}</td>
				<td style="text-align: center;">{{ isset($examinee->user->birthday) ? $examinee->user->birthday->format('F j, Y') : "-" }}</td>
				<td style="text-align: center;">{{ isset($examinee->user->birthday) ? now()->diffInYears($examinee->user->birthday) : "-" }}</td>
				<td style="text-align: center;">{{ $latestPackage }}</td>
				{{-- <td style="text-align: center;">₱{{ $amount }}</td> --}}
				<td style="text-align: center;">{{ $examinee->created_at->format('F j, Y') }}</td>
			</tr>
		@endforeach

		<tr>
			<td colspan="9"></td>
		</tr>

		<tr>
			<td></td>
			<td colspan="2">Age Group:</td>
			<td></td>
			<td colspan="4">Total Packages:</td>
			<td></td>
			<td colspan="3">Total Gender:</td>
		</tr>

		@for($i = 0; $i < $data->maxLength; $i++)
			<tr>
				<td></td>
				<td>{{ isset(array_keys($ageGroups)[$i]) ? array_keys($ageGroups)[$i] : "" }}</td>
				<td style="text-align: left;">{{ isset(array_keys($ageGroups)[$i]) ? $ageGroups[array_keys($ageGroups)[$i]] : "" }}</td>
				<td></td>
				<td colspan="3">{{ isset(array_keys($totalPackage)[$i]) ? array_keys($totalPackage)[$i] : "" }}</td>
				<td style="text-align: center;">{{ isset(array_keys($totalPackage)[$i]) ? $totalPackage[array_keys($totalPackage)[$i]] : "" }}</td>
				<td></td>
				@if(isset(array_keys($totalGender)[$i]))
					<td colspan="2">{{ ucfirst(strtolower(array_keys($totalGender)[$i])) }}</td>
					<td style="text-align: left;">{{ $totalGender[array_keys($totalGender)[$i]] }}</td>
				@else
					<td></td>
					<td></td>
				@endif
				<td></td>
			</tr>
		@endfor
	</tbody>
</table>
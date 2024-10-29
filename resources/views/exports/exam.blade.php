@php
	$b = "font-weight: bold;";
	$c = "text-align: center;";
	$bc = "$b $c";
@endphp

<table style="border: 1px solid black;">
	<thead>
		<tr>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Company Name</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Patient ID</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Surname</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">First Name</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Gender</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Birthday</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Age</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Package Name</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Amount</th>
			<th style="text-align: center; {{ $b }} background-color: yellow;">Date</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $examinee)
			@php
				$amount = 0;
				$latestPackage = "-";

				if(sizeof($examinee->user->patient->exams)){
					$temp = json_decode($examinee->user->patient->exams->last()->details);
					$amount = $temp->amount;
					$latestPackage = $temp->name;
				}
			@endphp
			<tr>
				<td style="text-align: center;">{{ $examinee->user->patient->company_name }}</td>
				<td style="text-align: center;">{{ $examinee->user->patient->patient_id }}</td>
				<td style="text-align: center;">{{ $examinee->user->lname }}</td>
				<td style="text-align: center;">{{ $examinee->user->fname }}</td>
				<td style="text-align: center;">{{ $examinee->user->gender }}</td>
				<td style="text-align: center;">{{ isset($examinee->user->birthday) ? $examinee->user->birthday->format('F j, Y') : "-" }}</td>
				<td style="text-align: center;">{{ isset($examinee->user->birthday) ? now()->diffInYears($examinee->user->birthday) : "-" }}</td>
				<td style="text-align: center;">{{ $latestPackage }}</td>
				<td style="text-align: center;">₱{{ $amount }}</td>
				<td style="text-align: center;">{{ $examinee->created_at->format('F j, Y') }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
@php
	$answers = $data->questions_with_answers;
	$details = json_decode($data->details);

	// dd($data->doctor);
@endphp

<table>	
	<tr>
		<td></td>
		<td colspan="5" style="text-align: center;">
			<img id="logo" src="{{ $settings['logo'] }}" alt="No Logo Uploaded" width="400" height="65">
		</td>
	</tr>

	<tr>
		<td colspan="7">{{ $settings['address'] }}</td>
	</tr>

	<tr>
		<td colspan="7">Phone #: {{ $settings['contact_no'] }}</td>
	</tr>

	<tr>
		<td colspan="7">Email: medhealthdiagnostics3@gmail.com</td>
	</tr>

	<tr>
		<td colspan="7">MEDICAL EXAMINATION REPORT</td>
	</tr>

	<tr>
		<td rowspan="2">NAME:</td>
		<td rowspan="2">{{ $data->user->lname }}, {{ $data->user->fname }} {{ substr($data->user->mname ?? "", 0, 1) }}{{ $data->user->mname ? "." : "" }}</td>
		<td>EXAM TYPE:</td>
		<td>{{ $data->type }}</td>
		<td>CONTROL #:</td>
		<td colspan="2">-</td>
	</tr>

	<tr>
		<td>COMPANY</td>
		<td colspan="4">{{ $data->package->company }}</td>
	</tr>

	<tr>
		<td colspan="2">CURRENT PHYSICAL COMPLAINT/MEDICINE TAKEN:</td>
		<td rowspan="2">CIVIL STATUS:</td>
		<td rowspan="2">{{ $data->user->civil_status }}</td>
		<td rowspan="2">BIRTHDATE:</td>
		<td rowspan="2">{{ $data->user->birthday ? $data->user->birthday->format('d/m/Y') : "-" }}</td>
		<td rowspan="3">SMOKER: -</td>
	</tr>

	<tr>
		<td rowspan="2" colspan="2"></td>
	</tr>

	<tr>
		<td>GENDER:</td>
		<td>{{ $data->user->gender }}</td>
		<td>AGE:</td>
		<td>{{ $data->user->birthday ? $data->user->birthday->age : "-" }}</td>
	</tr>

	<tr>
		<td colspan="2">SURGICAL OPERATIONS:</td>
		<td colspan="4">ALLERGIES:</td>
		<td rowspan="2"></td>
	</tr>

	<tr>
		<td colspan="2">-</td>
		<td colspan="4">-</td>
	</tr>

	<tr>
		<td colspan="2">FAMILY MEDICAL HISTORY:</td>
		<td colspan="4">PERSONAL MEDICAL HISTORY:</td>
		<td>ALCOHOL:</td>
	</tr>

	<tr>
		<td rowspan="3" colspan="2">-</td>
		<td rowspan="3" colspan="4">-</td>
		<td rowspan="3">-</td>
	</tr>

	<tr></tr>
	<tr></tr>

	<tr>
		<td colspan="7">VITAL SIGNS:</td>
	</tr>

	<tr>
		<td>WEIGHT:</td>
		<td>-</td>
		<td colspan="3">BLOOD PRESSURE:</td>
		<td colspan="2">LAST MENSTRUAL DATE:</td>
	</tr>

	<tr>
		<td>HEIGHT:</td>
		<td>-</td>
		<td colspan="3">-</td>
		<td colspan="2">-</td>
	</tr>

	<tr>
		<td>PULSE RATE:</td>
		<td>-</td>
		<td colspan="3">RESPIRATORY RATE:</td>
		<td colspan="2"></td>
	</tr>

	<tr>
		<td>BMI:</td>
		<td>-</td>
		<td colspan="3">UNDERWEIGHT:</td>
		<td colspan="2"></td>
	</tr>

	<tr>
		<td>TEMP:</td>
		<td>-</td>
		<td colspan="3"></td>
		<td colspan="2"></td>
	</tr>

	<tr>
		<td colspan="2">PHYSICAL EXAMINATION:</td>
		<td>DETAILS</td>
		<td colspan="2">CONDITION</td>
		<td colspan="2">REMARKS</td>
	</tr>

	<tr>
		<td colspan="7">ASSESSMENT</td>
	</tr>

	<tr>
		<td colspan="2"></td>
		<td>RIGHT EYE:</td>
		<td>-</td>
		<td></td>
		<td colspan="2">-</td>
	</tr>

	<tr>
		<td colspan="2"></td>
		<td>LEFT EYE:</td>
		<td>-</td>
		<td></td>
		<td colspan="2">-</td>
	</tr>

	<tr>
		<td colspan="2"></td>
		<td>-</td>
		<td>-</td>
		<td></td>
		<td colspan="2">-</td>
	</tr>

	<tr>
		<td colspan="2"></td>
		<td>-</td>
		<td>-</td>
		<td></td>
		<td colspan="2">-</td>
	</tr>

	<tr>
		<td colspan="7">DIAGNOSTIC RESULTS</td>
	</tr>

	<tr>
		<td>CBC:</td>
		<td>-</td>
		<td colspan="5">XRAY: -</td>
	</tr>

	<tr>
		<td rowspan="5">URINALYSIS:</td>
		<td>PUS CELL: -</td>
		<td colspan="5">-</td>
	</tr>

	<tr>
		<td>EPITHELIAL CELLS: -</td>
		<td>FECALYSIS: -</td>
		<td colspan="4">-</td>
	</tr>

	<tr>
		<td>BACTERIA: -</td>
		<td></td>
		<td colspan="4"></td>
	</tr>

	<tr>
		<td>MUCUS THREADS: -</td>
		<td></td>
		<td colspan="4"></td>
	</tr>

	<tr>
		<td>AMORPHOUS URATES: -</td>
		<td></td>
		<td colspan="4"></td>
	</tr>

	<tr>
		<td colspan="2">ASSESSMENT:</td>
		<td colspan="5">RECOMMENDATION:</td>
	</tr>

	<tr>
		<td colspan="2" rowspan="5">
			{!! $data->clinical_assessment !!}
		</td>
		<td colspan="5" rowspan="5">
			{!! $data->recommendation !!}
		</td>
	</tr>

	<tr></tr>
	<tr></tr>
	<tr></tr>
	<tr></tr>

	<tr>
		<td colspan="2">EXAMINING PHYSICIAN:</td>
		<td colspan="5">DATE RE-EXAMINED:</td>
	</tr>

	<tr>
		<td colspan="2">
			Dr. 
			{{ $data->doctor ? $data->doctor->fname . " " . $data->doctor->lname : "-" }}</td>
		<td rowspan="2" colspan="5" style="font-family: DejaVu Sans, sans-serif; font-size: 8px;">
			&#8205;{{ now()->format('d/m/Y') }}
		</td>
	</tr>

	<tr>
		<td colspan="2">LIC. NO. {{ $data->doctor ? $data->doctor->doctor->license_number : "-" }}</td>
	</tr>

	<tr>
		<td colspan="2">ASSESSING PHYSICIAN:</td>
		<td colspan="5">CLASSIFICATION:</td>
	</tr>

	<tr>
		<td colspan="2">
			Dr. 
			{{ $data->doctor ? $data->doctor->fname . ' ' . $data->doctor->lname : "-" }}
		</td>
		<td rowspan="2">
			@if($data->classification == "Fit to work")
				A
			@elseif($data->classification == "Physically fit with minor illness")
				B
			@elseif($data->classification == "Employable but with certain impairments or conditions requiring follow-up treatment (employment is at employer's discretion)")
				C
			@elseif($data->classification == "Unfit to work")
				D
			@elseif($data->classification == "Pending")
				Pending
			@else
				-
			@endif
		</td>
		<td rowspan="2" colspan="4" style="font-family: DejaVu Sans, sans-serif; font-size: 8px;">
			&#8205;{{ $data->classification ?? "-" }}
		</td>
	</tr>

	<tr>
		<td colspan="2">LIC. NO. {{ $data->doctor ? $data->doctor->doctor->license_number : "-" }}</td>
	</tr>
</table>
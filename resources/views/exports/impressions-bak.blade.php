@php
	$answers = $data->questions_with_answers;
	// dd($ids, $data->questions);
	// dd($data);
@endphp

<style type="text/css">
	footer {
	    position: fixed; 
	    bottom: 25px; 
	    left: 0px; 
	    right: 0px;
	    height: 40px; 
	}

	.texts{
		text-align: center;
	}
</style>

<table style="width: 100%;">
	<tr style="height: 10px;">
		<td colspan="2">
			<img src="{{ $settings['logo'] }}" alt="No Logo Uploaded" width="150px">
		</td>
		<td colspan="6" class="texts">
			<span style="font-weight: bold; font-size: 20px;">{{ $settings['clinic_name'] }}</span>
			<br>
			{{ $settings['address'] }}
			<br>
			{{ $settings['contact_no'] }}
			<br>
			Accredited by the Department of Health (DOH)
			<br>
			Philippines Overseas Employement Administration (POEA)
			<br>
			ISO Certified
		</td>
	</tr>

	<tr><td colspan="8" style="height: 50px;"></td></tr>

	<tr>
		<td colspan="6"></td>
		<td colspan="2">
			Date: {{ now()->format('F j, Y') }}	
		</td>
	</tr>

	<tr>
		<td colspan="6"></td>
		<td colspan="2">
			{{-- X-ray No.: --}}
		</td>
	</tr>

	<tr><td colspan="8"s style="height: 50px;"></td></tr>

	<tr>
		<td colspan="5">
			Name: {{ $data->user->fname }} {{ $data->user->mname }} {{ $data->user->lname }}
		</td>
		<td colspan="3">
			Examination Done: {{ $data->created_at->format('F j, Y') }}
		</td>
	</tr>

	<tr>
		<td colspan="2">
			Age: {{ now()->parse($data->user->birthday)->age }}
		</td>
		<td colspan="3">
			Gender: {{ $data->user->gender }}
		</td>
		<td colspan="3">
			Company: {{ $data->user->patient->company_name }}
		</td>
	</tr>

	<tr><td colspan="8" style="height: 50px;"></td></tr>

	{{-- <tr>
		<td colspan="8">
			{!! $data->remarks !!}
		</td>
	</tr> --}}
</table>

@foreach($data->questions[""] as $category)
	<h3>{{ $category["name"] }}</h3>
	<table style="width: 100%;">

	@php
		$array = $data->questions[$category['id']];
		$newArray = array_chunk($array, ceil(sizeof($array) / 2));
	@endphp

	@foreach($newArray[0] as $key => $question)
		<tr>
			{{-- if medication history --}}
			@if(in_array($question['id'], [131,132,133]))
				@if($question['id'] == 131)
					<td style="font-size: 10px; width: 30%;">
						{{ $key+1 }}.) {{ $data->answers[130]['answer']->all }}
					</td>
					{{ $key++ }}
				@endif
			@else
				<td style="font-size: 10px; width: 30%;">
					{{ $key+1 }}.) {{ $question['name'] }}
				</td>
				<td style="font-size: 10px; text-align: left; width: 20%;">
					@if($question['type'] == "Dichotomous")
						@if($data->answers[$question['id']]['answer'])
							Yes
						@else
							No
						@endif
					@else
						{{ $data->answers[$question['id']]['answer'] != "" ? $data->answers[$question['id']]['answer'] : "-" }}
					@endif
				</td>

				@if(isset($newArray[1][$key]))
					<td style="font-size: 10px; width: 30%;">
						{{ sizeof($newArray[0]) + ($key + 1) }}.) {{ $newArray[1][$key]['name'] }}
					</td>
					<td style="font-size: 10px; text-align: left; width: 20%;">
						@if($newArray[1][$key]['type'] == "Dichotomous")
							@if($data->answers[$newArray[1][$key]['id']]['answer'])
								Yes
							@else
								No
							@endif
						@else
							{{ $data->answers[$newArray[1][$key]['id']]['answer'] != "" ? $data->answers[$newArray[1][$key]['id']]['answer'] : "-" }}
						@endif
					</td>
				@endif
			@endif
		</tr>
	@endforeach
	</table>
@endforeach

@php
	$defaultText = "Currently, there are no available results. Please check back later or contact your healthcare provider for further assistance.";
@endphp

<h3>Remarks</h3>
{!! !in_array($data->remarks, ["", "<p><br></p>"]) ? $data->remarks : $defaultText !!}

<h3>Clinical Assessment</h3>
{!! !in_array($data->clinical_assessment, ["", "<p><br></p>"]) ? $data->clinical_assessment : $defaultText !!}

<h3>Recommendation</h3>
{!! !in_array($data->recommendation, ["", "<p><br></p>"]) ? $data->recommendation : $defaultText !!}

<br>
<h3>Classification</h3>
{{ $data->classification ?? $defaultText }}
@if($data->c_remarks != "")
<br>
<br>
<b>Remarks:</b> {!! $data->c_remarks !!}
<br>
<br>
@endif

<br>
<table style="width: 100%">
	<tr>
		<td colspan="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td colspan="3" style="text-decoration: underline; text-align: center; font-weight: bold;">
			@if(in_array(auth()->user()->role, ["Admin", "Doctor"]))
				{{ auth()->user()->fname }} {{ auth()->user()->mname }} {{ auth()->user()->lname }}, MD, {{ auth()->user()->doctor->title }}
			@endif
		</td>
	</tr>
	<tr>
		<td colspan="5"></td>
		<td colspan="3" style="text-align: center; font-weight: bold;">
			@if(in_array(auth()->user()->role, ["Admin", "Doctor"]))
				{{ auth()->user()->doctor->specialization ?? "Doctor" }}
			@endif
		</td>
	</tr>
	<tr>
		<td colspan="5"></td>
		<td colspan="3" style="text-align: center;">
			@if(in_array(auth()->user()->role, ["Admin", "Doctor"]))
				Lic. No. <span style="text-decoration: underline; font-family: DejaVu Sans, sans-serif;">&#8205;&#8205; </span><span style="text-decoration: underline;">{{ auth()->user()->doctor->license_number }}<span style="text-decoration: underline; font-family: DejaVu Sans, sans-serif;">&#8205;&#8205; </span></span>
			@endif
		</td>
	</tr>
</table>

{{-- PAGE BREAK --}}
<div style="page-break-after: always;"></div>
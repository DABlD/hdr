@php
	$answers = $data->questions_with_answers;
	// dd($ids, $data->questions);
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
			X-ray No.:
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
	<table>
	@foreach($data->questions[$category['id']] as $key => $question)
		<tr>
			<td style="font-size: 10px;">
				{{ $key+1 }}.) {{ $question['name'] }}
			</td>
			<td></td>
			<td style="font-size: 10px; text-align: center;">
				@if($question['type'] == "Dichotomous")
					@if($data->answers[$question['id']]['answer'])
						Yes
					@else
						No
					@endif
				@else
					{{ $data->answers[$question['id']]['answer'] }}
				@endif
			</td>
		</tr>
	@endforeach
	</table>
@endforeach

<h3>Remarks</h3>
{!! $data->remarks !!}

<h3>Clinical Assessment</h3>
{!! $data->clinical_assessment !!}

<h3>Recommendation</h3>
{!! $data->recommendation !!}

<h3>Classification</h3>
{{ $data->classification }}

<br>
<table style="width: 100%">
	<tr>
		<td colspan="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td colspan="3" style="text-decoration: underline; text-align: center; font-weight: bold;">
			{{ auth()->user()->fname }} {{ auth()->user()->mname }} {{ auth()->user()->lname }}, {{ auth()->user()->doctor->title }}
		</td>
	</tr>
	<tr>
		<td colspan="5"></td>
		<td colspan="3" style="text-align: center; font-weight: bold;">
			{{ auth()->user()->doctor->specialization ?? "Doctor" }}
		</td>
	</tr>
</table>
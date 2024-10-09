<style type="text/css">
	footer {
	    position: fixed; 
	    bottom: 25px; 
	    left: 0px; 
	    right: 0px;
	    height: 40px; 
	}
</style>

<table>
	<tr>
		<td colspan="8">
			<img src="{{ public_path('images/letterhead.png') }}" height="130px" width="730px">
		</td>
	</tr>

	<tr><td colspan="8" style="height: 30px;"></td></tr>

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
			Examination Done: ---
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

	<tr><td colspan="8"s style="height: 50px;"></td></tr>

	<tr>
		<td colspan="8">
			{!! $data->remarks !!}
		</td>
	</tr>
</table>

<footer>
	<table style="width: 100%">
		<tr>
			<td colspan="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td colspan="3" style="text-decoration: underline; text-align: center;">
				{{ auth()->user()->fname }} {{ auth()->user()->mname }} {{ auth()->user()->lname }}
			</td>
		</tr>
		<tr>
			<td colspan="5"></td>
			<td colspan="3" style="text-align: center;">
				Doctor
			</td>
		</tr>
	</table>
</footer>
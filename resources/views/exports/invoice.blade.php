@php
	$b = "font-weight: bold;";
	$c = "text-align: center;";
	$bc = "$b $c";

	$pDetails = json_decode($data->details);
	// dd($pDetails);
@endphp

<style>
	td{
		font-size: 12px;
	}

	.bb{
		border-bottom: 1px solid rgba(128, 128, 128, 0.3);
		height: 30px;
	}

	.peso{
		font-family: DejaVu Sans, sans-serif;
	}
</style>

<table style="width: 100%;">
	<tr>
		<td colspan=5>
			<img src="{{ $settings['logo'] }}" alt="No Logo Uploaded" width="150px" height="70px">
		</td>
	</tr>

	<br>
	<br>

	<tr>
		<td rowspan="2" colspan="3" style="{{ $b }} font-size: 30px;">INVOICE</td>
		<td colspan="2">
			Service Request No. <span style="{{ $b }}">OHN-{{ str_pad($data->id, 9, '0', STR_PAD_LEFT) }}</span>
		</td>
	</tr>

	<tr>
		<td colspan="2">
			Invoice Date: <span style="{{ $b }}">{{ now()->format("Y-m-d") }}</span>
		</td>
	</tr>

	<br>
	<br>

	<tr>
		<td colspan="2" style="font-size: 20px;">Provider</td>
		<td colspan="3" style="font-size: 20px;">Patient</td>
	</tr>

	<br>

	<tr>
		<td colspan="2" style="{{ $b }}">OneHealth Network PH</td>
		<td colspan="3" style="{{ $b }}">{{ $data->user->fname }} {{ $data->user->mname }} {{ $data->user->lname }}</td>
	</tr>

	<tr>
		<td colspan="2">Clinic: {{ $settings['clinic_name'] }}</td>
		<td colspan="3">Gender / Age: {{ $data->user->gender }} / {{ isset($data->user->birthday) ? $data->user->birthday->age : "-" }}</td>
	</tr>

	<tr>
		<td colspan="2">Doctor's name: {{ isset($data->doctor) ? $data->doctor->fname . ' ' . $data->doctor->mname . ' ' . $data->doctor->lname . ', ' . $data->doctor->doctor->title : "-" }}</td>
		<td colspan="3">Company name: {{ $data->package->company }}</td>
	</tr>

	<tr>
		<td colspan="2"></td>
		<td colspan="3">Patient's signature:</td>
	</tr>

	<br>
	<br>

	<tr>
		<td class="bb" style="{{ $b }};">Description</td>
		<td class="bb"></td>
		<td class="bb"></td>
		<td class="bb" style="{{ $b }} text-align: right;">Price</td>
		<td class="bb" style="{{ $b }} text-align: right;">Sub Total</td>
	</tr>

	<tr>
		<td>{{ $pDetails->name }}</td>
		<td></td>
		<td class="bb"></td>
		<td class="bb" style="{{ $c }} text-align: right;"><span class="peso">₱</span>{{ number_format($pDetails->amount, 2) }}</td>
		<td class="bb" style="text-align: right;"><span class="peso">₱</span>{{ number_format($pDetails->amount, 2) }}</td>
	</tr>

	<tr>
		<td></td>
		<td></td>
		<td class="bb" colspan="2" style="{{ $c }} text-align: right;">Total discount ({{ $pDetails->discount ?? 0 }}%)</td>
		<td class="bb" style="text-align: right;"><span class="peso">₱</span>{{ number_format($pDetails->amount * ($pDetails->discount / 100), 2) }}</td>
	</tr>

	<tr>
		<td></td>
		<td></td>
		<td class="bb" colspan="2" style="{{ $c }} text-align: right;">Total amount</td>
		<td class="bb" style="{{ $b }} text-align: right;"><span class="peso">₱</span>{{ number_format($pDetails->amount - ($pDetails->amount * ($pDetails->discount / 100)), 2) }}</td>
	</tr>

	<br>
	<br>

	<tr>
		<td>Notes:</td>
	</tr>

	<tr>
		<td>Payment Type: CASH</td>
	</tr>
</table>
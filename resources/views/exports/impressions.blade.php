@php
	$answers = $data->answers;
	$details = json_decode($data->details);

	// 113 = PERSONAL MEDICAL HISTORY
	// 130 = MEDICATION HISTORY -> N/A
	// 134 = FAMILIY HISTORY AND PERSONAL-SOCIAL HISTORY
	// 144 = SMOKING HISTORY
	// 147 = DRINKING HISTORY
	// 154 = MENSTRUAL HISTORY
	// 159 = OBSTERICAL HISTORY
	// 163 = VITAL SIGNS
	// 170 = ANTHROPOMETRICS
	// 175 = VISUAL ACUITY
	// 179 = SYSTEMATIC EXAMINATION
	// 198 = DIAGNOSTIC EXAMINATION

	function toArray($string){
		return explode("\n", $string);
	}

	// dd($data->questions['134'], $data->answers['135'], $data->answers['136'], $data->answers['137'], $data->answers['138'], $data->answers['139'], $data->answers['140']);
@endphp

<table>	
	<tr>
		<td style="text-align: center;">
			<img id="logo" src="{{ $settings['logo'] }}" alt="No Logo Uploaded" height="55">
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
		<td colspan="2">MEDICATION HISTORY:</td>
		<td rowspan="2">CIVIL STATUS:</td>
		<td rowspan="2">{{ $data->user->civil_status }}</td>
		<td rowspan="2">BIRTHDATE:</td>
		<td rowspan="2">{{ $data->user->birthday ? $data->user->birthday->format('d/m/Y') : "-" }}</td>
		<td>SMOKER:</td>
	</tr>

	<tr>
		<td rowspan="4" colspan="2">
			@php
				$temp = toArray($answers[130]['answer']->all);
				foreach($temp as $line){
					echo $line . '<br>';
				}
			@endphp
		</td>
		<td rowspan="4">
			@php
				//145 = PREVIOUS SMOKER / 146 = CURRENT SMOKER / 301 = COMPUTATION
				$questions = array_combine(array_column($data->questions[144], 'id'), $data->questions[144]);

				echo "Prev: " . ((isset($answers[145]) && $answers[145]['answer']) ? ($answers[145]['remark'] != "" ? $answers[145]['remark'] : "Yes")  : "No") . '<br>';
				echo "Cur: " . ((isset($answers[146]) && $answers[146]['answer']) ? ($answers[146]['remark'] != "" ? $answers[146]['remark'] : "Yes")  : "No") . '<br>';
				echo $answers[271]['answer'] . ' / ' . $answers[272]['answer'] . '<br>';
				echo $answers[301]['answer'];
			@endphp
		</td>
	</tr>

	<tr>
		<td>GENDER:</td>
		<td>{{ $data->user->gender }}</td>
		<td>AGE:</td>
		<td>{{ $data->user->birthday ? $data->user->birthday->age : "-" }}</td>
	</tr>

	<tr>
		<td colspan="4">PERSONAL MEDICAL HISTORY:</td>
	</tr>

	<tr>
		<td rowspan="5" colspan="4">
			@php
				$questions = array_combine(array_column($data->questions[113], 'id'), $data->questions[113]);
				$ids = array_column($questions, 'id');
				
				foreach($ids as $id){
					if(isset($answers[$id]) && $answers[$id]['answer']){
						echo $questions[$id]['name'] . ($answers[$id]['remark'] != "" ? ": " . $answers[$id]['remark'] : "") . '<br>';
					}
				}
			@endphp
		</td>
	</tr>

	<tr>
		<td colspan="2">FAMILY MEDICAL HISTORY:</td>
		<td>ALCOHOL:</td>
	</tr>

	<tr>
		<td rowspan="3" colspan="2">
			@php
				$questions = array_combine(array_column($data->questions[134], 'id'), $data->questions[134]);
				$ids = array_column($questions, 'id');
				
				foreach($ids as $id){
					if($answers[$id]['answer']){
						echo $questions[$id]['name'] . ($answers[$id]['remark'] != "" ? ": " . $answers[$id]['remark'] : "") . '<br>';
					}
				}
			@endphp
		</td>
		<td rowspan="3">
			@php
				//148 = DRINKING CLASSIFICATION / 278 = USUAL SHOTS / 279 = USUAL NUMBER OF BOTTLE ALCOHOL
				$questions = array_combine(array_column($data->questions[147], 'id'), $data->questions[147]);

				echo $answers[148]['answer'] . '<br>';
				echo $answers[278]['answer'] . '<br>';
				echo $answers[279]['answer'] . '<br>';
			@endphp
		</td>
	</tr>

	<tr></tr>
	<tr></tr>

	<tr>
		<td colspan="7">VITAL SIGNS:</td>
	</tr>

	<tr>
		<td colspan="2" rowspan="5">
			@php
				//164-166 = 1st-3rd BP / 167 = Pulse Rate / 168 = Respiratory Rate / 169 - Temperature / 274 - 02 saturation
				$questions = array_combine(array_column($data->questions[163], 'id'), $data->questions[163]);

				echo "Blood Pressure: \t";
				echo $answers[164]['answer'] . ($answers[165]['answer'] != "" ? " - " . $answers[165]['answer'] : "") . ($answers[166]['answer'] != "" ? " - " . $answers[166]['answer'] : "") . '<br>';
				echo "Pulse Rate: ";
				echo $answers[167]['answer'] . '<br>';
				echo "Respiratory Rate: ";
				echo $answers[168]['answer'] . '<br>';
				echo "Temperature: ";
				echo $answers[169]['answer'] . '<br>';
				echo "O2 Saturation: ";
				echo $answers[274]['answer'];
			@endphp
		</td>
		<td rowspan="5" colspan="3">
			@php
				//171 = Height / 172 = Weight / 173 - BMI / 174 - Weight Class / 275 = IBW
				$questions = array_combine(array_column($data->questions[170], 'id'), $data->questions[170]);

				echo "Height: ";
				echo $answers[171]['answer'] . '<br>';
				echo "Weight: ";
				echo $answers[172]['answer'] . '<br>';
				echo "BMI: ";
				echo $answers[173]['answer'] . '<br>';
				echo "Weight Class: ";
				echo $answers[174]['answer'] . '<br>';
				echo "IBW: ";
				echo $answers[275]['answer'];
			@endphp
		</td>
		<td rowspan="5" colspan="2">

		</td>
	</tr>

	<tr></tr>
	<tr></tr>
	<tr></tr>
	<tr></tr>

	<tr>
		<td colspan="7">PHYSICAL EXAMINATION:</td>
		{{-- <td>DETAILS</td>
		<td colspan="2">CONDITION</td>
		<td colspan="2">REMARKS</td> --}}
	</tr>

	<tr>
		<td colspan="2" rowspan="5">
		@php
			$questions = array_combine(array_column($data->questions[179], 'id'), $data->questions[179]);
			$ids = array_column($questions, 'id');

			$ctr = 0;
			
			foreach($ids as $id){
				// IF NOT SET, WAIVED
				if(!isset($answers[$id])){
					echo $questions[$id]['name'] . ': Waived' . '<br>';
				}
				elseif(!$answers[$id]['answer']){
					echo $questions[$id]['name'] . ($answers[$id]['remark'] != "" ? ": " . $answers[$id]['remark'] : "") . '<br>';
					$ctr++;

					if($ctr % 4 == 0){
						if($ctr < 7){
							echo "</td>";
							echo "<td colspan='3' rowspan='4'>";
						}
						else{
							echo "</td>";
							echo "<td colspan='2' rowspan='4'>";
						}
					}
				}
			}
		@endphp
		</td>
	</tr>

	<tr></tr>
	<tr></tr>
	<tr></tr>
	<tr></tr>

	<tr>
		<td colspan="7">DIAGNOSTIC RESULTS</td>
	</tr>

	<tr>
		<td rowspan="6" colspan="2">
			@php
				// 201,202,203,205,206,208
				$questions = array_combine(array_column($data->questions[198], 'id'), $data->questions[198]);
				$ids = array_column($questions, 'id');

				$array = [201,202,203,205,206,208];

				$ctr = 0;
				
				foreach($ids as $id){
					if(in_array($id, $array))
					// IF NOT SET, WAIVED
					if(!isset($answers[$id])){
						echo $questions[$id]['name'] . ': Waived' . '<br>';
					}
					elseif(!$answers[$id]['answer']){
						if($answers[$id]['answer'] != ""){
							echo $questions[$id]['name'] . ($answers[$id]['remark'] != "" ? ": " . $answers[$id]['remark'] : "") . '<br>';
						}
					}
				}
			@endphp
		</td>
		<td rowspan="6" colspan="5">
			@php
				// 276,283
				$array = [276,283];

				$ctr = 0;
				
				foreach($array as $id){
					$temp = toArray($answers[$id]['answer']);

					echo $questions[$id]['name'] . ': ';
					foreach($temp as $line){
						echo $line . '<br>';
					}
				}
			@endphp
		</td>
	</tr>

	<tr></tr>
	<tr></tr>
	<tr></tr>
	<tr></tr>
	<tr></tr>

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
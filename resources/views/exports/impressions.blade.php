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

	function toArray2($string){
		return explode("<br>", $string);
	}

	$ct = function($text){
		$text = str_replace('s (eg. PCOS, Endometriosis, etc)', '', $text);
		return str_replace('&', '&#38;', $text);
	};

	// dd($data->questions['134'], $data->answers['135'], $data->answers['136'], $data->answers['137'], $data->answers['138'], $data->answers['139'], $data->answers['140']);
@endphp

<table>	
	<tr>
		<td style="text-align: center;">
			<img id="logo" src="{{ $settings['logo'] }}" alt="No Logo Uploaded" height="55">
		</td>
	</tr>

	<tr>
		<td colspan="8">{{ $settings['address'] }}</td>
	</tr>

	<tr>
		<td colspan="8">Phone #: {{ $settings['contact_no'] }}</td>
	</tr>

	<tr>
		<td colspan="8">Email: medhealthdiagnostics3@gmail.com</td>
	</tr>

	<tr>
		<td colspan="8">MEDICAL EXAMINATION REPORT</td>
	</tr>

	<tr>
		<td colspan="2">
			Name: {{ $data->user->lname }}, {{ $data->user->fname }} {{ substr($data->user->mname ?? "", 0, 1) }}{{ $data->user->mname ? "." : "" }}
		</td>
		<td colspan="2">Exam Type: {{ $data->type }}</td>
		<td colspan="3">Control #: </td>
		<td>Exam Date: {{ $data->created_at->format('M d, Y') }}</td>
	</tr>

	<tr>
		<td>Age: {{ $data->user->birthday ? $data->user->birthday->age : "-" }}</td>
		<td>Gender: {{ $data->user->gender }}</td>
		<td colspan="2">Birthdate: {{ $data->user->birthday ? $data->user->birthday->format('d/m/Y') : "-" }}</td>
		<td colspan="3">Civil Status: {{ $data->user->civil_status }}</td>
		<td>Contact #: {{ $data->user->contact ?? "-" }}</td>
	</tr>

	<tr>
		<td colspan="2">Address: {{ $ct($data->user->address) }}</td>
		<td colspan="3">Company: {{ $ct($data->package->company) }}</td>
		<td colspan="3">Position/Department: {{ $ct($data->user->patient->company_position) }}</td>
	</tr>

	<tr>
		<td colspan="8">Medical History</td>
	</tr>

	@php
		$dh = 65;
		$dh2 = 65;

		$questions = array_combine(array_column($data->questions[113], 'id'), $data->questions[113]);
		$ids = array_column($questions, 'id');
		$pmhString = "";
		$ctr = 0;
		
		foreach($ids as $id){
			if(isset($answers[$id]) && $answers[$id]['answer']){
				$pmhString .= $questions[$id]['name'] . ($answers[$id]['remark'] != "" ? ": " . $answers[$id]['remark'] : "") . '<br>';

				$ctr++;
				if($ctr >= 4){
					$dh += 15;
				}
			}
		}

		$questions = array_combine(array_column($data->questions[134], 'id'), $data->questions[134]);
		$ids = array_column($questions, 'id');
		$fhString = "";
		$ctr2 = 0;
		
		foreach($ids as $id){
			if(isset($answers[$id]) && $answers[$id]['answer']){
				$fhString .= $questions[$id]['name'] . ($answers[$id]['remark'] != "" ? ": " . $answers[$id]['remark'] : "") . '<br>';

				$ctr2++;
				if($ctr2 >= 4){
					$dh2 += 15;
				}
			}
		}

		$dh = $dh > $dh2 ? $dh : $dh2;
	@endphp

	<tr>
		<td colspan="2" style="height: {{ $dh }};">
			Personal Medical History:<br>
			{!! $ct($pmhString) !!}
		</td>
		<td colspan="4" style="height: {{ $dh }};">
			Family History:<br>
			{!! $ct($fhString) !!}
		</td>
		<td rowspan="2" colspan="2" style="height: {{ $dh }};">
			@php
				if($data->user->gender == "Female"){
					echo "Obsterical &#38; Menstrual History:<br>";
					$questions = array_combine(array_column($data->questions[159], 'id'), $data->questions[159]);
					$ids = array_column($questions, 'id');
					
					foreach($ids as $id){
						$answers[$id]['answer'] = isset($answers[$id]) ? $answers[$id]['answer'] : "";
						echo $ct($questions[$id]['name'] . ': ' . ($answers[$id]['answer'] != "" ? $answers[$id]['answer'] : "") . '<br>');
					}

					$questions = array_combine(array_column($data->questions[154], 'id'), $data->questions[154]);
					$ids = array_column($questions, 'id');
					
					foreach($ids as $id){
						$answers[$id]['answer'] = isset($answers[$id]) ? $answers[$id]['answer'] : "";
						echo $ct($questions[$id]['name'] . ': ' . ($answers[$id]['answer'] != "" ? $answers[$id]['answer'] : "") . '<br>');
					}
				}
			@endphp
		</td>
	</tr>

	@php
		$dh = 65;

		$mhString = "";
		$ctr = 0;
		
		$temp = toArray($answers[130]['answer']->all);
		foreach($temp as $line){
			$mhString .= $line . '<br>';
			$ctr++;
			if($ctr >= 4){
				$dh += 15;
			}
		}
	@endphp

	<tr>
		<td colspan="2" style="height: {{ $dh }};">
			Medication History:<br>
		</td>
		<td colspan="2" style="height: {{ $dh }};">
			Smoking History:<br>
			@php
				//145 = PREVIOUS SMOKER / 146 = CURRENT SMOKER / 301 = COMPUTATION
				$questions = array_combine(array_column($data->questions[144], 'id'), $data->questions[144]);

				echo "Previous: " . ((isset($answers[145]) && $answers[145]['answer']) ? ($answers[145]['remark'] != "" ? $answers[145]['remark'] : "Yes")  : "No") . '<br>';
				echo "Current: " . ((isset($answers[146]) && $answers[146]['answer']) ? ($answers[146]['remark'] != "" ? $answers[146]['remark'] : "Yes")  : "No") . '<br>';

				echo "Sticks per day: " . $answers[271]['answer'] . '<br>';
				echo "For how many years: " . $answers[272]['answer'] . '<br>';

				echo isset($answers[301]) ? $answers[301]['answer'] : "";
			@endphp
		</td>
		<td colspan="2" style="height: {{ $dh }};">
			Drinking History:<br>
			@php
				//148 = DRINKING CLASSIFICATION / 278 = USUAL SHOTS / 279 = USUAL NUMBER OF BOTTLE ALCOHOL
				$questions = array_combine(array_column($data->questions[147], 'id'), $data->questions[147]);

				echo $answers[148]['answer'] . '<br>';
				// echo $answers[278]['answer'] . '<br>';
				// echo $answers[279]['answer'] . '<br>';
			@endphp
		</td>
	</tr>

	<tr>
		<td colspan="8">Physical Examination</td>
	</tr>

	@php
		$height = 95;
	@endphp
	<tr>
		<td colspan="2" style="height: {{ $height }}px;">
			Vital Signs<br>
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
		<td colspan="3" style="{{ $height }}px;">
			Anthropometrics<br>
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
		<td colspan="3" style="{{ $height }}px;">
			Visual Acuity<br>
			@php
				// 176-178
				$questions = array_combine(array_column($data->questions[175], 'id'), $data->questions[175]);
					
				echo $questions[176]['name'] . ': ' . $answers[176]['answer'] . '<br>';
				echo $questions[177]['name'] . ': ' . $answers[177]['answer'] . '<br>';
				echo $questions[178]['name'] . ': ' . ((isset($answers[178]) && $answers[178]['answer']) ? "Yes" : "No") . (isset($answers[178]) ? $answers[178]['remark'] : "") . '<br>';
			@endphp
		</td>
	</tr>

	@php
		$questions = array_combine(array_column($data->questions[179], 'id'), $data->questions[179]);
		$ids = array_column($questions, 'id');
		$ids = array_chunk($ids, ceil(count($ids) / 2));
	@endphp

	@for($i = 0; $i < sizeof($ids[0]); $i++)
		<tr>
			<td>{{ $questions[$ids[0][$i]]['name'] }}</td>
			<td colspan="3">
				@if(isset($answers[$ids[0][$i]]))
					@if($answers[$ids[0][$i]]['answer'])
						Essentially Normal
						@if($answers[$ids[0][$i]]['remark'] != "")
							 ({{ $answers[$ids[0][$i]]['remark'] }})
						@endif
					@else
						{{ $answers[$ids[0][$i]]['remark'] }}
					@endif
				@else
					{{ isset($answers[$ids[0][$i]]) ? $answers[$ids[0][$i]]['remark'] : "" }}
				@endif
			</td>
			<td colspan="2">{{ $questions[$ids[1][$i]]['name'] }}</td>
			<td colspan="2">
				@if(isset($answers[$ids[1][$i]]))
					@if($answers[$ids[1][$i]]['answer'])
						Essentially Normal
						@if($answers[$ids[1][$i]]['remark'] != "")
							({{ $answers[$ids[1][$i]]['remark'] }})
						@endif
					@else
						{{ $answers[$ids[1][$i]]['remark'] }}
					@endif
				@else
					{{ isset($answers[$ids[1][$i]]) ? $answers[$ids[1][$i]]['remark'] : "" }}
				@endif
			</td>
		</tr>
	@endfor

	<tr>
		<td colspan="8">Diagnostic Examination</td>
	</tr>

	@php
		$dh = 80;
		
		// 201 - ECG			206 - ECG
		// 202 - URINALYSIS		276 - BLOOD CHEM
		// 203 - FECALYSIS		283 - OTHERS
		// 205 - CHEST X-RAY
		// 208 - PAPSMEAR
		$questions = array_combine(array_column($data->questions[198], 'id'), $data->questions[198]);
		$ids = [
			[201,202,203,205,208],
			[206,276,283]
		];

		$leftString = "";
		$ctr = 0;

		// dd($questions[276],$answers[276]);
		
		foreach($ids[0] as $id){
			if(isset($answers[$id])){
				if($answers[$id]['answer'] == 0){
					$leftString .= $questions[$id]['name'] . ': ' . ($answers[$id]['remark'] != "" ? $answers[$id]['remark'] : $answers[$id]['answer']) . '<br>';
				}
				elseif($answers[$id]['answer'] == 1){
					$leftString .= $questions[$id]['name'] . ': Normal' . '<br>';
				}
			}
		}

		$rightString = "";
		
		foreach($ids[1] as $id){
			if(isset($answers[$id]) && ($answers[$id]['answer'] == 0 || strlen($answers[$id]['answer']) > 1)){
				$temp = toArray($answers[$id]['remark'] != "" ? $answers[$id]['remark'] : $answers[$id]['answer']);

				$rightString .= $questions[$id]['name'] . ':<br>';
				$ctr++;
				foreach($temp as $line){
					$rightString .= $line . '<br>';
					$ctr++;
				}

				if($ctr > 4){
					$dh += 15;
				}
			}
		}

		$leftString = substr($leftString, 0, -4);
		$rightString = substr($rightString, 0, -4);
	@endphp

	<tr>
		<td colspan="3" style="height: {{ $dh }}px;">{!! $leftString !!}</td>
		<td colspan="5" style="height: {{ $dh }}px;">{!! $rightString !!}</td>
	</tr>

	<tr>
		<td colspan="8" style="height: 5px;"></td>
	</tr>

	@php
		$dh = 60;
		$dh2 = 60;

		$assessment = "";
		$ctr = 0;
		
		$temp = toArray($data->clinical_assessment);
		foreach($temp as $line){
			$assessment .= $line . '<br>';
			$ctr++;
			if($ctr >= 4){
				$dh += 15;
			}
		}

		$recommendation = "";
		$ctr2 = 0;
		
		$temp = toArray2($data->recommendation);

		foreach($temp as $line){
			$recommendation .= $line . '<br>';
			$ctr2++;
			if($ctr2 >= 4){
				$dh2 += 15;
			}
		}

		$dh = $dh > $dh2 ? $dh : $dh2;
	@endphp

	<tr>
		<td colspan="3">Assessment:</td>
		<td colspan="5">Recommendation:</td>
	</tr>

	<tr>
		<td colspan="3" style="height: {{ $dh }}px;">{!! $assessment !!}</td>
		<td colspan="5" style="height: {{ $dh }}px;">{!! $recommendation !!}</td>
	</tr>

	<tr>
		<td colspan="8" style="height: 5px;"></td>
	</tr>

	<tr>
		<td colspan="3">
			Examining Physician:
			@if($data->doctor->doctor->signature)
				<img src="{{ $data->doctor->doctor->signature }}" width="50px">
			@endif
		</td>
		<td colspan="5">
			Assessing Physician:
			@if($data->doctor->doctor->signature)
				<img src="{{ $data->doctor->doctor->signature }}" width="50px">
			@endif
		</td>
	</tr>

	<tr>
		<td colspan="3">
			Dr. 
			{{ $data->doctor ? $data->doctor->fname . " " . $data->doctor->lname : "-" }}
		</td>
		<td colspan="5">
			Dr. 
			{{ $data->doctor ? $data->doctor->fname . " " . $data->doctor->lname : "-" }}
		</td>
	</tr>

	<tr>
		<td>Classification:</td>
		<td colspan="7">
			@if($data->classification == "Fit to work")
				A
			@elseif($data->classification == "Physically fit with minor illness")
				B
			@elseif($data->classification == "Employable but with certain impairments or conditions requiring follow-up treatment (employment is at employer's discretion)")
				C
			@elseif($data->classification == "Unfit to work")
				D
			@endif
			 - {{ $data->classification ?? "-" }}
		</td>
	</tr>

	<tr>
		<td></td>
		<td colspan="7">{{ $data->c_remarks }}</td>
	</tr>
</table>
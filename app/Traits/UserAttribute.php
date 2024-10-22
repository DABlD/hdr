<?php

namespace App\Traits;

trait UserAttribute{
	public function getActionsAttribute(){
		$id = $this->id;
		$action = "";

		if($this->deleted_at){
			$action .= 	"<a class='btn btn-info' data-toggle='tooltip' title='Restore' onClick='res($id)'>" .
					        "<i class='fas fa-undo'></i>" .
					    "</a>&nbsp;";
		}
		else{
			$action = 	"<a class='btn btn-success' data-toggle='tooltip' title='View' onClick='view($id)'>" .
					        "<i class='fas fa-search'></i>" .
					    "</a>&nbsp;";

			if($this->role == "Patient"){
				$action .= 	"<a class='btn btn-info' data-toggle='tooltip' title='Edit' onClick='edit($id)'>" .
						        "<i class='fas fa-pencil'></i>" .
						    "</a>&nbsp;";

				$action .= 	"<a class='btn btn-primary' data-toggle='tooltip' title='History' onClick='medicalHistory($id)'>" .
						        "<i class='fas fa-clipboard-prescription'></i>" .
						    "</a>&nbsp;";	

				$action .= 	"<a class='btn btn-warning' data-toggle='tooltip' title='QR' onClick='qr($id)'>" .
						        "<i class='fas fa-qrcode'></i>" .
						    "</a>&nbsp;";	
			}

			if(auth()->user()->role == "Super Admin"){
				$action .= 	"<a class='btn btn-primary' data-toggle='tooltip' title='Themes' onClick='themes($id)'>" .
						        "<i class='fas fa-palette'></i>" .
						    "</a>&nbsp;";	
			}

			$action .= 	"<a class='btn btn-danger' data-toggle='tooltip' title='Delete' onClick='del($id)'>" .
					        "<i class='fas fa-trash'></i>" .
					    "</a>&nbsp;";

		}


		return $action;
	}

	public function getMedicalAttribute(){
		$id = $this->id;
		$action = "";

		$action .= 	"<a class='btn btn-primary' data-toggle='tooltip' title='Add Record' onClick='takeExam($id)'>" .
				        "<i class='fas fa-notes-medical'></i>" .
				    "</a>&nbsp;";

		$action .= 	"<a class='btn btn-info' data-toggle='tooltip' title='Request List' onClick='requestList($id)'>" .
				        "<i class='fas fa-list'></i>" .
				    "</a>&nbsp;";

		$action .= 	"<a class='btn btn-danger' data-toggle='tooltip' title='Delete' onClick='deleteType($id)'>" .
				        "<i class='fas fa-trash'></i>" .
				    "</a>&nbsp;";

		return $action;
	}
}
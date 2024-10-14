<?php

namespace App\Traits;

trait PatientAttribute{
	public function getActionsAttribute(){
		$id = $this->id;
		$uid = $this->user_id;
		$action = "";

		$action = 	"<a class='btn btn-success' data-toggle='tooltip' title='View' onClick='view($uid)'>" .
				        "<i class='fas fa-search'></i>" .
				    "</a>&nbsp;";

	    $action .= 	"<a class='btn btn-primary' data-toggle='tooltip' title='History' onClick='medicalHistory($uid)'>" .
			        "<i class='fas fa-clipboard-prescription'></i>" .
			    "</a>&nbsp;";
	    // $action .= 	"<a class='btn btn-primary' data-toggle='tooltip' title='History' onClick='medicalHistory($uid)'>" .
	    // 		        "<i class='fas fa-clipboard-prescription'></i>" .
	    // 		    "</a>&nbsp;";

		return $action;
	}
}
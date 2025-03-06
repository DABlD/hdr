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

	    // $action .= 	"<a class='btn btn-primary' data-toggle='tooltip' title='History' onClick='medicalHistory($uid)'>" .
		// 	        "<i class='fas fa-clipboard-prescription'></i>" .
		// 	    "</a>&nbsp;";
		$action .= 	"<a class='btn btn-primary' data-toggle='tooltip' title='Edit' onClick='edit($uid)'>" .
				        "<i class='fas fa-pencil'></i>" .
				    "</a>&nbsp;";

	    $action .= 	"<a class='btn btn-info' data-toggle='tooltip' title='Request List' onClick='requestList($id)'>" .
		        "<i class='fas fa-list'></i>" .
		    "</a>&nbsp;";
	    // $action .= 	"<a class='btn btn-primary' data-toggle='tooltip' title='History' onClick='medicalHistory($uid)'>" .
	    // 		        "<i class='fas fa-clipboard-prescription'></i>" .
	    // 		    "</a>&nbsp;";

		$action .= 	"<a class='btn btn-warning' data-toggle='tooltip' title='QR' onClick='qr($id)'>" .
				        "<i class='fas fa-qrcode'></i>" .
				    "</a>&nbsp;";	

		return $action;
	}
}
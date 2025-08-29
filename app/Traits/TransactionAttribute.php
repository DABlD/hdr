<?php

namespace App\Traits;

trait TransactionAttribute{
	public function getActionsAttribute(){
		$id = $this->id;
		$action = "-";

		if($this->status == "Ongoing"){
			$action = 	"<a class='btn btn-success' data-toggle='tooltip' title='Complete Transaction' onClick='complete($id)'>" .
					        "<i class='fas fa-check'></i>" .
					    "</a>&nbsp;";	
		}

		return $action;
	}
}
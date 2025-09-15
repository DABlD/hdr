<?php

namespace App\Traits;

trait TransactionAttribute{
	public function getActionsAttribute(){
		$id = $this->id;
		$action = "";

		if($this->status == "Ongoing"){
			$action = 	"<a class='btn btn-success' data-toggle='tooltip' title='Complete Transaction' onClick='complete($id)'>" .
					        "<i class='fas fa-check'></i>" .
					    "</a>&nbsp;";	
		}

		if($this->completed == 0 && $this->status == "Ongoing"){
			$action .= 	"<a class='btn btn-primary' data-toggle='tooltip' title='Edit' onClick='edit($id)'>" .
				        "<i class='fas fa-pencil'></i>" .
				    "</a>&nbsp;";

			$action .= 	"<a class='btn btn-danger' data-toggle='tooltip' title='Cancel' onClick='cancel($id)'>" .
					        "<i class='fas fa-trash'></i>" .
					    "</a>";	
		}

		return $action;
	}
}
<?php

namespace App\Traits;

trait WellnessAttribute{
	public function getActionsAttribute(){
		$id = $this->id;
		$action = "-";

		$action = 	"<a class='btn btn-success' data-toggle='tooltip' title='Complete Transaction' onClick='view($id)'>" .
				        "<i class='fas fa-search'></i>" .
				    "</a>&nbsp;";
		$action .= 	"<a class='btn btn-warning' data-toggle='tooltip' title='Send To Portal' onClick='send($id)'>" .
				        "<i class='fas fa-send'></i>" .
				    "</a>&nbsp;";

		return $action;
	}
}
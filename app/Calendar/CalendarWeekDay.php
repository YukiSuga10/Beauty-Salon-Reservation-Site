<?php
namespace App\Calendar;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CalendarWeekDay {
	protected $carbon;

	function __construct($date){
		$this->carbon = new Carbon($date);
	}

	function getClassName(){
		return "day-" . strtolower($this->carbon->format("D"));
	}

	/**
	 * @return 
	 */
	function render(){
	    $id = Auth::guard('admin')->user()->id;
	    

	    
		return '<a class="day_block" href = "/admin/'.$id."/".$this->carbon->format("Y-m-d").'">' . $this->carbon->format("j"). '</a>';
	}
}

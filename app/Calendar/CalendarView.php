<?php
namespace App\Calendar;

use Carbon\Carbon;


class CalendarView {

	private $carbon;

	function __construct($date){
		
		$this->carbon = new Carbon($date);
		
	}
	
	public function getTitle(){
		
		return $this->carbon->format('Y年n月');
	}
	
	public function getTitle_pre(){
		$month = date("n",strtotime(session()->get("month")));
		
		$now_month = Carbon::now()->format("n");

		$this->carbon = $this->carbon->subMonthsNoOverflow(abs($month-$now_month));

		return $this->carbon->format('Y年n月');
	}
	
	public function getTitle_next(){
		$month = date("n",strtotime(session()->get("month")));
		
		$now_month = Carbon::now()->format("n");

		$this->carbon = $this->carbon->addMonthsWithOverflow(abs($month-$now_month));

		return $this->carbon->format('Y年n月');
	}

	
	function render(){
		$html = [];
		$html[] = '<div class="calendar">';
		$html[] = '<table class="table">';
		$html[] = '<thead>';
		$html[] = '<tr>';
		$html[] = '<th>月</th>';
		$html[] = '<th>火</th>';
		$html[] = '<th>水</th>';
		$html[] = '<th>木</th>';
		$html[] = '<th>金</th>';
		$html[] = '<th class="saturday">土</th>';
        $html[] = '<th class="sunday">日</th>';
		$html[] = '</tr>';
		$html[] = '</thead>';
		$html[] = '<tbody>';
		
		

		$weeks = $this->getWeeks();
		foreach($weeks as $week){
			$html[] = '<tr class="'.$week->getClassName().'">';
			$days = $week->getDays();
			foreach($days as $day){
				$html[] = '<td class="'.$day->getClassName().'">';
				$html[] = $day->render();
				$html[] = '</td>';
			}
			$html[] = '</tr>';
		}
		
		$html[] = '</tbody>';
		$html[] = '</table>';
		$html[] = '</div>';
		return implode("", $html);
	}
	
	
	
	
	protected function getWeeks(){
		$weeks = [];
		

		//初日
		$firstDay = $this->carbon->copy()->firstOfMonth();

		//月末まで
		$lastDay = $this->carbon->copy()->lastOfMonth();

		//1週目
		$week = new CalendarWeek($firstDay->copy());
		$weeks[] = $week;

		//作業用の日
		$tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();

		//月末までループさせる
		while($tmpDay->lte($lastDay)){
			//週カレンダーViewを作成する
			$week = new CalendarWeek($tmpDay, count($weeks));
			$weeks[] = $week;
			
            //次の週=+7日する
			$tmpDay->addDay(7);
		}
		
		

		return $weeks;
	}
	
	
	
	
}
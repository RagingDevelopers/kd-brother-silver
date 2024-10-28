<?php  
class ExceptionHook{

	public function SetExceptionHandler(){
		set_exception_handler(array($this,'HandleException'));
	}

	public function HandleException($exception){
		$mes = "Exception occurred".$exception->getMessage()."<br>";
		$mes .= "Line No ".$exception->getLine()."<br>";
		$mes .= "Class No ".get_class($exception)."<br>";
		$mes .= "File ".$exception->getFile()."<br>";
		$mes .= "Code ".$exception->getCode()."<br>";
		$mes .= "Trace ".$exception->getTraceAsString()."<br>";
		log_message('error', $mes);
	}
}
?>

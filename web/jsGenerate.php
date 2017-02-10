<?php
	class js{
		public $response;
		private $lines = array(
			"create{variable:string}",
			"create{variable:array}",
			"create{variable:json}",
			"create{variable:int}",
			"{variable} = {variable:int||int} {operation} {variable:int||int}",
			"{variable:int}++",
			"{variable:int}--",
			"{variable} = {variable:int} {condition} {int} ? {int||string} : {int||string}",
			"{comment}",
		);

		private $controls = array(
			"if",
			"for",
			"function"
		);

		private $operations = array(
			"+",
			"-",
			"/",
			"*",
			"%"
		);

		private $conditions = array(
			"==",
			"<=",
			">=",
			"!="
		);

		private $types = array(
			"string",
			"int",
			"array",
			"json"
		);

		private $defined = array();

		private function generateName($length){
			$rname = "";
			$sesli = "aeiou";
			$sessiz = "bcdfghjklmnprstvyz";
			$rname = rand(1,2) == 1?$sessiz[rand(0,strlen($sessiz)-1)]:$sesli[rand(0,strlen($sesli)-1)];
			for($n=0;$n<$length;$n++){
				if(in_array($rname[strlen($rname)-1], str_split($sesli))){
					$rname .= $sessiz[rand(0,strlen($sessiz)-1)];
				}else{
					$rname .= $sesli[rand(0,strlen($sesli)-1)];
				}
			}
			return $rname;
		}

		public function createString($write=false){
			$var = array(
				"name" => $this->generateName(rand(5,10)),
				"value" => $this->generateName(rand(5,500)),
				"type" => "string"
			);
			if($write == true){
				$this->response .= $var["name"].' = "'.$var["value"].'";'."\n";
				$this->defined[] = $var;
			}
			return $var;
		}

		public function createInt($write=false){
			$var = array(
				"name" => $this->generateName(rand(5,10)),
				"value" => rand(0,9999999),
				"type" => "int"
			);
			if($write == true){
				$this->response .= $var["name"].' = '.$var["value"].';'."\n";
				$this->defined[] = $var;
			}
			return $var;
		}

		public function createArray($write=false){
			$value = array();
			for($i=0;$i<rand(5,10);$i++){
				$value[] = $this->generateName(rand(5,8));
			}
			$value = json_encode($value,JSON_PRETTY_PRINT+JSON_UNESCAPED_SLASHES);
			$var = array(
				"name" => $this->generateName(rand(5,10)),
				"value" => $value,
				"type" => "array"
			);
			if($write == true){
				$this->response .= $var["name"].' = '.$var["value"].';'."\n";
				$this->defined[] = $var;
			}
			return $var;
		}

		public function createJSON($write=false){
			$value = array();
			for($i=0;$i<rand(5,10);$i++){
				$value[$this->generateName(rand(5,8))] = $this->generateName(rand(5,8));
			}
			$value = json_encode($value,JSON_PRETTY_PRINT+JSON_UNESCAPED_SLASHES);
			$var = array(
				"name" => $this->generateName(rand(5,10)),
				"value" => $value,
				"type" => "json"
			);
			if($write == true){
				$this->response .= $var["name"].' = '.$var["value"].';'."\n";
				$this->defined[] = $var;
			}
			return $var;
		}

		private function createComment(){
			$comment = "//";
			for($i=0;$i<rand(5,10);$i++){
				$comment .= $this->generateName(rand(4,8))." ";
			}
			return $comment;
		}

		private function createFor(){
			$min = rand(0,25);
			$max = $min + rand(10,250);
			$code = "for(i=".$min.";i<".$max.";i++){\n";
			for($i=0;$i<rand(2,10);$i++){
				$line = $this->createLine();
				if(trim($line) != ";"){
					$code .= $line;
				}
			}
			$code .= "}\n";
			return $code;
		}

		private function createIf(){
			$type = $this->types[array_rand($this->types)];
			$condition = $this->conditions[array_rand($this->conditions)];
			if($type == "int"){
				$code = "if(".$this->getDefinedVariable("int")." ".$condition." ".rand(10,500)."){\n";
			}elseif($type == "string"){
				$codes = array(
					$this->getDefinedVariable("string").' == "'.$this->generateName(rand(5,20)).'"',
					$this->getDefinedVariable("string").'.length '.$condition.' '.rand(20,50),
					$this->getDefinedVariable("string").'.indexOf("'.$this->generateName(rand(5,10)).'") '.$condition.' '.rand(20,50)
				);
				$code = "if(".$codes[array_rand($codes)]."){\n";
			}elseif($type == "array") {
				$index = rand(0,222);
				$var = $this->getDefinedVariable("array");
				$codes = array(
					$var.'['.$index.'] && '.$var.'['.$index.'] == "'.$this->generateName(rand(5,20)).'"',
					$var.'.length '.$condition.' '.rand(20,50),
					$var.'.indexOf("'.$this->generateName(rand(5,10)).'") '.$condition.' '.rand(20,50)
				);
				$code = "if(".$codes[array_rand($codes)]."){\n";
			}elseif($type == "json") {
				$index = $this->generateName(2,9);
				$var = $this->getDefinedVariable("json");
				$codes = array(
					$var.'.'.$index.' && '.$var.'.'.$index.' == "'.$this->generateName(rand(5,20)).'"',
					$var.'.'.$index.' && '.$var.'.'.$index.' == '.rand(5,9999).'',
					'!'.$var.'.'.$index,
					$var.'.'.$index
				);
				$code = "if(".$codes[array_rand($codes)]."){\n";
			}
			for($i=0;$i<rand(5,10);$i++){
				$line = $this->createLine(false);
				if(trim($line) != ";"){
					$code .= $line;
				}
			}
			$code .= "}\n";
			return $code;
		}

		private function createFunction(){
			$code = "function ".$this->generateName(rand(5,12))."(){\n";
			for($i=0;$i<rand(9,20);$i++){
				$line = $this->createLine(false);
				if(trim($line) != ";"){
					$code .= $line;
				}
			}
			$code .= "}\n";
			return $code;
		}

		public function getDefinedVariable($type){
			shuffle($this->defined);
			$var = array();
			foreach ($this->defined as $defined) {
				if($defined["type"] == $type){
					$var = $defined;
					break;
				}
			}
			if($var == array()){
				if($type == "string"){
					$var = $this->createString(true);
				}elseif($type == "int") {
					$var = $this->createInt(true);
				}elseif($type == "array") {
					$var = $this->createArray(true);
				}elseif($type == "int") {
					$var = $this->createJSON(true);
				}
			}
			return $var["name"];
		}

		private function createLine($create=true){
			$template = $this->lines[array_rand($this->lines)];
			if($create == false){
				while (strpos($template, "create") !== false) {
					$template = $this->lines[array_rand($this->lines)];
				}
			}
			$var = array();
			$line = "";
			if($template == "create{variable:string}"){
				$var = $this->createString();
			}elseif($template == "create{variable:array}"){
				$var = $this->createArray();
				$line = $var["name"].' = '.$var["value"];
				$this->defined[] = $var;
			}elseif($template == "create{variable:json}"){
				$var = $this->createJSON();
				$line = $var["name"].' = '.$var["value"];
				$this->defined[] = $var;
			}elseif($template == "create{variable:int}"){
				$var = $this->createInt();
				$line = $var["name"].' = '.$var["value"];
				$this->defined[] = $var;
			}else{
				$line = $template;
				$line = str_replace("{variable:int}", $this->getDefinedVariable("int"), $line);
				$line = str_replace("{variable:int||int}", rand(1,2) == 1 ? $this->getDefinedVariable("int"):rand(1,99999), $line);
				$line = str_replace("{variable}", $this->generateName(rand(5,9)), $line);
				$line = str_replace("{operation}",  $this->operations[array_rand($this->operations)], $line);
				$line = str_replace("{condition}",  $this->conditions[array_rand($this->conditions)], $line);
				$line = str_replace("{int}",  rand(10,99999), $line);
				$line = str_replace("{string}",  '"'.$this->generateName(rand(20,200)).'"', $line);
				$line = str_replace("{int||string}",  rand(1,2) == 1?('"'.$this->generateName(rand(5,15)).'"'):rand(5,99999), $line);
				$line = str_replace("{comment}",  $this->createComment(), $line);
			}
			$line .= ";\n";
			return $line;
		}

		private function createControl(){
			$template = $this->controls[array_rand($this->controls)];
			$code = "";
			if($template == "for"){
				$code = $this->createFor();
			}elseif($template == "if") {
				$code = $this->createIf();
			}elseif($template == "function") {
				$code = $this->createFunction();
			}
			return $code;
		}

		public function createCode(){
			$this->createJSON(true);
			$this->createInt(true);
			$this->createString(true);
			$this->createArray(true);
			for($l=0;$l<rand(10,15);$l++){
				$this->response .= $this->createLine();
			}
			for($c=0;$c<rand(50,120);$c++){
				$type = rand(1,2) == 1 ? "line":"control";
				if($type == "line"){
					$line = $this->createLine();
					if(trim($line) != ";"){
						$this->response .= $line;
					}
				}elseif ($type == "control") {
					$this->response .= $this->createControl();
				}
			}
			return $this->response;
		}
	}
?>

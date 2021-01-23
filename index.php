<?php
                                                    //задание 1
$categories = array(
	array(
   	"id" => 1,
   	"title" =>  "Обувь",
   	'children' => array(
    		   	array(
    		       	'id' => 2,
    		       	'title' => 'Ботинки',
    		       	'children' => array(
    		           	array('id' => 3, 'title' => 'Кожа'),
    		           	array('id' => 4, 'title' => 'Текстиль'),
    		       	),
    		   	),
    		   	array('id' => 5, 'title' => 'Кроссовки',),
   			)
		),
		array(
   		"id" => 6,
   		"title" =>  "Спорт",
   		'children' => array(
    	   	array(
    	       	'id' => 7,
    	       	'title' => 'Мячи'
    	   	)
   		)
	),
);
function searchCategory($categories, $id){
	foreach ($categories as $key => $value) {
		if(is_array($value)){
			//если value массив, то рекурсия
			$e = searchCategory($value, $id);
			if($e != null) return $e;
		}

		if(isset($categories['id'])){
			if($categories['id']==$id){
				return $categories['title'];
			}
		}
	}
	return null;
}
echo searchCategory($categories, 5);

										//задание 2

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "astrio_test";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
$sql="SELECT `worker`.`first_name`, `worker`.`last_name`, `child`.`name`, `car`.`model` FROM `worker` LEFT JOIN `child` on  `worker`.`id`=`child`.`user_id` LEFT JOIN `car` on `worker`.`id`=`car`.`user_id` WHERE `car`.`model`='null' or `car`.`model`<>'';";
if (mysqli_query($conn, $sql)) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
mysqli_close($conn);

									//задание 3

$array_true = array('<a>', '<div>', '</div>', '</a>', '<span>', '</span>');
$array_false = array('<a>', '<div>', '</a>', '</div>');
function do_arr($array){
	$open_tegs = array();
	$val = str_ireplace("<", "", $array);
	foreach($val as $value){
		if($value[0]=='/'){
			//нашли закрывающийся тэг
			$clouse = substr($value, 1);
			if(end($open_tegs)==$clouse){
				//сравниваем последний открытый тэг с закрывающимся
				//если они одинаковые, удаляем из массива открытых тэгов
				unset($open_tegs[array_key_last($open_tegs)]);
			}else{
				//здесь можно проверять исключения на тэги 
				//которые не имеют закравающихся тегов, например <img>
			}
		}else{
			//нашли открывающийся тэг, положим его в массив открытых тэгов
			array_push($open_tegs, $value);
		}
	}
	if(empty($open_tegs)){
		//массив пуст
		echo "правильный".'<br>';
		return true; 
	}else{
		//в массиве что то осталось
		echo "неправильный";
		return false;
	}
}
do_arr($array_true);
do_arr($array_false);

								//задание 4


interface Box{
	public function setData($key, $value);
	public function getData($key);
	public function save();
	public function load();
}

abstract class AbstractBox implements Box{
	// protected $arr = [];
	protected $str = "";
	public function setData($key, $value){
		$this->str = $value;
	}
	public function getData($key){
		return $this->str??null;
	}
	public abstract function save();
	public abstract function load();
}

class FileBox extends AbstractBox{
	private $file;
	public function __construct($file){
		$this->file=$file;
	}
	public function save(){
		file_put_contents($this->file, $this->str);
	}
	public function load(){
		$this->str=file_get_contents($this->file);
	}
}
$fileBox = new FileBox("abc.txt");
$fileBox->setData(2, 'wty');
$fileBox->save();

class DbBox extends AbstractBox {
	public function __construct($servername,$username,$password,$dbname){
		$this->link = mysqli_connect($servername, $username, $password, $dbname);
	}

	public function save(){
		$sql = "INSERT INTO `box`(`val`) VALUES ('".$this->str."');";
		mysqli_query($this->link,$sql);
	}
 
	public function load(){
		$result="";
		$sql = "select * from `box`;";
		$q=mysqli_query($this->link,$sql);
		if ($q && mysqli_num_rows($q) > 0) {
			for ($i = 0; $i < mysqli_num_rows($q); $i++) {
				$data = @mysqli_fetch_array($q);
				$result.= $data['val']."<br>";
			}
		}
		return $result;
	}

}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "astrio";
$dbBox = new DbBox($servername,$username,$password,$dbname);
$dbBox->setData(2, 'test');
$dbBox->save();
echo $dbBox->load();
?>
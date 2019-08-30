<?php
	//Скачивание файла
	function getDataFile($url, $path, $fileName){
		if (checkUrl($url)){
			$path = $_SERVER['DOCUMENT_ROOT'] . $path . $fileName;
			file_put_contents($path, file_get_contents($url));
			return file_get_contents($path);
		} else {
			echo "Файл не найден";
		}
	}

	//Проверка наличия файла по указанному адресу
	function checkUrl($url){
		if (@fopen($url, "r")) {
			return true;
		} else {
			return false;
		}
	}
	
	$dataFileJson = getDataFile('http://sknt.ru/job/frontend/data.json','/task-tariff/', 'data.json');

	//Декодированные данные
	$output_result_json = json_decode($dataFileJson);
	
?>


<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="user-scalable=no, width=device-width,initial-scale=1.0"/>
		<link rel="stylesheet" href="css/style.css">
		<title>Тарифы</title>
	</head>
	<body>
		<!-- группы тарифов -->
		<section id ="groupTarif">
			<div class="container">
				<?php foreach($output_result_json->tarifs as $item):?>

				<div class="tarifsItem">	
					<div class="tarifsTitle">
						<h2>Тариф "<?php echo $item->title;?>"</h2>
					</div>
					<hr>
					<div class = "tarifsContent">
						<div class ="tarifsSpeed"
						id="<?php 

						  switch ($item->speed) {
						    case '50':
						      echo "greenSpeed";
						      break;
						    case '100':
						      echo "blueSpeed";
						      break;
						    case '200':
						      echo "redSpeed";
						      break;
						  }
						?>"
						>
						<?php echo $item->speed;?>
								Мбит/c
						</div>
						<div>
							<b>
								<?php echo(($item->tarifs[3]->price/$item->tarifs[3]->pay_period).' – '.($item->tarifs[0]->price/$item->tarifs[0]->pay_period)) ;?> &#8381/мес
							</b>
						</div>
						<div class = "arrow-right">
							<a href ="#<?php echo $item->tarifs[1]->ID;?>"> > </a>
						</div>
						<div>
							<p>
								<?php 
									if(isset($item->free_options) ){
										foreach($item->free_options as $val)
										echo $val."<br>";}?>
							</p>
						</div>
					</div>
					<hr>
					<div class = "tarifsFooter">
						<div class = "buttons-column">
							<a href = "<?php echo $item->link;?>">Узнать подробнее на сайте www.sknt.ru</a>
						</div>
					</div>
				</div>

			<?php endforeach;?>
			</div>
		</section>

		<!--  варанты тарифов -->
		<section id ="varTarif">
			<a name="varTarif"></a>
			<?php foreach($output_result_json->tarifs as $item):?>
				
				<div class="Title">
					<a name="<?php echo $item->tarifs[1]->ID;?>"></a>
					<h2>Тариф <?php echo $item->title;?></h2>
					<div class = "arrow-left">
						<a href = "#"> < </a>
					</div>
				</div>

				<div class = "container">
					<?php foreach($item->tarifs as $item2):?>
					<div class="tarifsItem">
						<div class="tarifsTitle">
							<h3><?php echo $item2->title;?> </h3>
						</div>
						<hr>
						<div class="tarifsContent">
							<div>
								<b><?php echo ($item2->price/$item2->pay_period);?> &#8381/мес</b>
							</div>
							<div>
								<p>Разовый платёж – <?php echo $item2->price;?> &#8381
								<br>
								Скидка – &#8381</p>
							</div>
							<div class = "arrow-right">
								<a href = "#par<?php echo $item2->ID;?>"> > </a>
							</div>
						</div>
					</div>
					<?php endforeach;?>
				</div>

		<?php endforeach;?>
		</section>

		<!-- параметры выбранного тарифа -->
		<section id="parTarif">
			<div class="Title">
					<h2>Выбор тарифа</h2>
					<div class = "arrow-left">
						<a href = "#varTarif"> < </a>
					</div>
			</div>

			<div class="container">
				<?php foreach($output_result_json->tarifs as $item):?>
					<?php foreach($item->tarifs as $item2):?>
						<div class="tarifsItem">
							<a name= "par<?php echo $item2->ID;?>"></a>
							<div class="tarifsTitle">
								<h3><?php echo $item2->title;?> </h3>
							</div>
							<hr>
							<div>
								<b>Период оплаты – <?php echo $item2->pay_period;?> мес
								<br>
								<?php echo ($item2->price/$item2->pay_period);?> &#8381/мес</b>
							</div>
							<div>
								<p>разовый платёж – <?php echo $item2->price;?> &#8381
								<br>
								со счёта спишется – <?php echo $item2->price;?> &#8381</p>
							</div>
							<div>
								<p>вступит в силу – сегодня
								<br>
								активно до - 
								<?php 
									$str = substr("$item2->new_payday",0,-5);
									echo date('Y-m-d',(int)$str);
									?>
								</p>
							</div>
							<hr>
							<button class = "tarifBtn">Выбрать</button>
						</div>

					<?php endforeach;?>
				<?php endforeach;?>
				</div>	
		</section>

	</body>
</html>

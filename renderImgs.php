<?php

echo '<div id="fotopagina">';

//get $mainfolder, array of top-level folders to use, (lente, zomer, herfst, winter, history..... , machines)
//get $subfolder,  array of wich subfolders to use and in what order. onderwerpen en gewassen
foreach($mainfolder as $thismainfolder){
	//if there is more than one main folder, make a header and a division for each folder. And print a small description for this header
	if(count($mainfolder)>1){
		echo "<div class=\"imgblock\"><h3>$thismainfolder</h3>";
		/*
		$headdescfileaddr = "beelden/$thismainfolder/headdesc.txt";
		$headdescfile = file_exists($headdescfileaddr)?fopen($headdescfileaddr,'r'):NULL;
		if($headdescfile){
			$line = fgets($descfile);
			while($line != false){
				$line = fgets($descfile);
				echo $line;
			}
		}*/
	}
	//fill every mainfolder with the contents of all subfolders that it contains
	foreach($subfolder as $thissubfolder){
		//the location of the files
		$location = "beelden/$thismainfolder/$thissubfolder/";

		//		echo $location;
		//$filenames is an array of all filenames in 'location' with .jpg as extension
		//Glob is a function to obtain all files in a specified folder ($location), with a specified filename (*, so not specified here) and extension (.jpg)
		//if(isset($_GET['noCache'])||!file_exists($location.'.txt')){
			$filenames = glob($location.'{*.jpg,*.mp4}', GLOB_BRACE|GLOB_NOSORT);
			//print_r($filenames);
			asort($filenames);
			$filenames = (array_values($filenames));
		//}else{
		//	$filenames = 
		//}
		$numimgs = count($filenames);
		//look if there is a file named 'desc.txt' that contains the descriptions for each image
		$descfileaddr = '/'.$location.'desc.txt';
		$descfile = file_exists($descfileaddr)?fopen($descfileaddr,'r'):NULL;
		//if there are more than one subfolders, make a header and a division for each folder. And print a small description for this header
		if(count($subfolder)>1){
			echo "<div class=\"imgblock\"><h3>$thissubfolder</h3>";
			/*
			
					$headdescfileaddr = "beelden/$thismainfolder/$thissubfolder/headdesc.txt";
		$headdescfile = file_exists($headdescfileaddr)?fopen($headdescfileaddr,'r'):NULL;
		if($descfile){
			$line = fgets($headdescfile);
			while($line != false){
				$line = fgets($descfile);
				echo $line;
			}
		}*/
			
		}
		//check if there ar images in the folder
		if($numimgs==0){
			echo 'Geen foto\'s';
		}else{
		$alt="";$desconpage="";$style="";
		for($pic = 0; $pic<$numimgs;$pic = $pic+2){
			//build the html for this img. The first image is the large one 800*600px, the second is the small one 200*150px.
			//this works if every image has a unique name, and is both in small and large format in the folder. 
			//Both image start with the same filename, but the small one ends with a 's' and the large one ends with 'l'
			// Because $filenames is in alphabetic order, the large one is just in front of the small one
			$largeimgsrc = '/'.$filenames[$pic];
			$smallimgsrc = '/'.$filenames[$pic+1];
			//if there is a file with a description for each image.
			//Every line in the textfile is the description for one image, in alphabetical order.
			if($descfile){
				$desc = fgets($descfile);
				if($desc == " \n" || $desc == "\r\n" || $desc == "\r" || $desc == "\n" || $desc == false){
					//the description is empty
					$alt = $thismainfolder.' -> '.$thissubfolder; //alt text
					$desconpage = "<div></div>"; //the visible description on the page.
					$title = NULL; //title text
				}else{
				//The file contains a description for this image
				$desconpage = "<div>$desc</div>";
				$title = " title=\"$desc\"";
				$alt = $desc;
				}
			}else{
				//use a part of the filename as description
				/*if($fnamedesc){
					$startpos = rightchar('/');//vind de laatste /
					$desc = substr($largeimgsrc,$startpos,$fnamedesc);
					
					$desconpage = "<div>$desc</div>";
					$title = " title=\"$desc\"";
					$alt = $desc;
					
				}else{*/
				
				//No description file present
					$alt = $thismainfolder.' -> '.$thissubfolder;
					$desconpage = NULL;
					$title = NULL;
				//}
			}
			//if a custom width or height is set.
			if($width || $height){	
				$style = 'style="width:'.$width.'px; height:'.$height.'px;"';
			}
			//if this is a video, show a play button
			if(substr($largeimgsrc, -3) == 'mp4'){
				$video = 'data-height="360" data-width="640" type="video/mp4"';
				$overlay = '<img class="overlay" src="/beelden/playbutton3.png" />';	
			}else{
				$overlay = "";
				$video = "";
			}
			//Make the HTML to show the image and description

			echo "		
			
			<a href=\"$largeimgsrc\" rel=\"lightbox\" class=\"html5lightbox\" data-group=\"$thismainfolder\" $title $video>
				<div class=\"floatimg\" $style>
						
						<img src=\"$smallimgsrc\" alt=\"$alt\"	 />
						$overlay
						$desconpage
				</div>
			</a>
			
			";
		}}
		//Close descfile
		if($descfile)
			fclose($descfile);
		//Close HTML
		if(count($subfolder)>1)
		echo '</div>';
	}
	
	if(count($mainfolder)>1){
		echo '</div>';
		}
}
echo '</div>';


?>


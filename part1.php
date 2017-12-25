<?php
// Part1
// Created by Mihkel Tiganik 20.12.2017
// Input: "Input Data.txt"
// Output: "Output Data.txt"


// Open file
$inputData = fopen("Input Data.txt", "r") or die("unable to open file!");
$outputData = fopen("Output Data.txt", "w") or die("unable to open file!");

// Get values to $Arr
$stringGetter;
$Arr = array();
while(!feof($inputData)){
	$stringGetter = fgets($inputData);
	$stringGetter .= "|-1"; 			//Add non-initialized padding level element. 
	array_push($Arr, explode("|",$stringGetter));
}
fclose($inputData);

// Get Elements with parent_id = 0 as first ones of Array.
$i = 0;		// parameter to count 0 level padding elements
$temp;
for($row = 0; $row < count($Arr); $row++){
	if($Arr[$row][1] == 0)
	{
		$temp = $Arr[$i];
		$Arr[$i] = $Arr[$row];
		$Arr[$row] = $temp;
		$Arr[$i][3] = 0;  	// Add padding level
		$i++;
	}
}

// Loop for making 0 level elements to correct order.
for($row = 1; $row <= $i; $row++){
		if($row != 0)
		{
			if($Arr[$row][1] == $Arr[$row-1][1] && $Arr[$row][0] < $Arr[$row-1][0])
			{
				$temp = $Arr[$row-1];
				$Arr[$row-1] = $Arr[$row];
				$Arr[$row] = $temp;
			}
		}
	}

// Current Array
//  $Arr = {
//	Array(1, 0, Electronics, 0)
//	Array(2, 0, Video,	 0)
//	Array(3, 0, Photo,	 0)
//	Array(12,11,20D,	-1)
//	Array(6, 4, iPod,	-1)
//	Array(4, 1, MP3 player,	-1)
//	Array(10,9, Nikon,	-1)
//	Array(11,9, Canon,	-1)
//	Array(7, 6, Shuffle,	-1)
//	Array(8, 3, SLR,	-1)
//	Array(5, 1, TV,		-1)
//	Array(9, 8, DSLR,	-1)
// }


// This Loop does it all
$firstLoopCount = count($Arr);					// first For statement max count (12)
for($row = 0; $row < $firstLoopCount; $row++)			// first For statement
{
	for($col = $row; $col < count($Arr); $col++)		// second For statement
	{
		if( $Arr[$row][0] == $Arr[$col][1])		// search for child elements
		{
			for($j = $col; $j < count($Arr); $j++)	                	// Without this sub-loop 
			{                                                     		// 5|1|TV would be before
				if($Arr[$col][1] == $Arr[$j][1] 			// 4|1|MP3 Player	
				&& $Arr[$j][0] > $Arr[$col][0]) 			// 
				{                                                   	// This loop puts 
					array_splice($Arr, $row+1, 0, array($Arr[$j])); // 5|1|TV behind 
					$Arr[$row + 1][3] = $Arr[$row][3] + 1;          // 1|0|Electronics 
					$j++;                                           // 
					$Arr[$j] = null;                                // 
					$col++;                                         // 
					                                                //
				}                                                   	//
			}

				array_splice($Arr, $row + 1, 0, array($Arr[$col])); 	// With these commands we bring
				$Arr[$row + 1][3] = $Arr[$row][3] + 1;              	// 4|1|MP3 behind Electronics again
				$col++;                                             	//
				$Arr[$col] = null;                                  	//
		}
	}
}

function getPaddingLevel($input){
	$x = "";
	while(0 < $input){
		$x .= "-";
		$input--;
	}
	return $x;
}


// output to file
foreach($Arr as $index => $indexArray){
	$txt = getPaddingLevel($indexArray[3]) . " " . $indexArray[2];
	fwrite($outputData, $txt);
}


fclose($outputData);
echo 'output saved to "Output Data.txt"';

?>

<?php

declare(strict_types=1);
namespace Phpml\Tests\Clustering;
require 'vendor/autoload.php';
use Phpml\Clustering\KMeans;
use Phpml\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
error_reporting(E_ERROR | E_PARSE);
set_time_limit(100000);
ini_set('memory_limit', '-1');
class KMeansTest extends TestCase
{
    public function testKMeansSamplesClustering(): void
    {
        /*$samples = [
		[230, 9500,0, "Yes","No", "Yes", "A", "Damascus", "Primary"],
		[220, 7000,3, "No","Yes", "No", "A", "Damascus", "Secondary"],
		[225, 10000,1, "Yes","Yes", "No", "C", "Aleppo", "University"],
		[190, 9500,2, "Yes","Yes", "Yes", "D", "Aleppo", "Secondary"]
		];	*/
	$servername = "127.0.0.1";
    $username = "root";
    $password = "root";
    $database = "movieclick";

    $conn = new \mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
	#$sql = "SELECT * FROM `customers` join movies on customers.CustomerID=movies.CustomerID";
	$sql = "SELECT * FROM `customers`";
	$result = $conn->query($sql);	
	$array_ = Array();
	$customer_id = Array();
	if ($result->num_rows > 0) {
		
		while ($row = $result->fetch_assoc()) {
	if (!empty($row['Age'])&!empty($row['Education Level'])&
	!empty($row['Gender'])&!empty($row['Home Ownership'])&
	!empty($row['Internet Connection'])&!empty($row['Marital Status'])&
	!empty($row['Num Bathrooms'])&!empty($row['Num Bedrooms'])&
	!empty($row['Num Cars'])&!empty($row['Num Children'])&
	!empty($row['Num TVs'])&!empty($row['PPV Freq'])&
	!empty($row['Buying Freq'])&!empty($row['Format'])&
	!empty($row['Renting Freq'])&!empty($row['Viewing Freq'])&
	!empty($row['Theater Freq'])&!empty($row['TV Movie Freq'])&
	!empty($row['TV Signal'])&!empty($row['Movie Selector'])
			){
			array_push($customer_id,$row['CustomerID']);
			array_push($array_, 
        array(
            $row['Age'],
			$row['Education Level'],
			$row['Gender'],
			$row['Home Ownership'],
			$row['Internet Connection'],
			$row['Marital Status'],
			#$row['Num Bathrooms'],
			#$row['Num Bedrooms'],
			#$row['Num Cars'],
			$row['Num Children'],
			#$row['Num TVs'],
			$row['PPV Freq'],
			$row['Buying Freq'],
			$row['Format'],
			$row['Renting Freq'],
			#$row['Viewing Freq'],
			#$row['CustomerID'],
			$row['Theater Freq'],
			$row['TV Movie Freq'],
			$row['TV Signal']
			#$row['Movie Selector']
        )
    );
		}
	}
	}
		$samples=$array_;

        $kmeans = new KMeans(3);
        $clusters = $kmeans->cluster($samples);


      /*  foreach ($samples as $index => $sample) {
            if (in_array($sample, $clusters[0], true) || in_array($sample, $clusters[1], true)) {
                unset($samples[$index]);
            }

        }*/
$lines = [];
$movie = Array();
$class_movie = Array();
$file = fopen("result.csv","w");
$file_movie_class = fopen("movie_class.csv","w");
$i=0;
foreach ($clusters as $key => $cluster) {
    foreach ($cluster as $sample) {
		$id=$customer_id[$i];
		$sql = "SELECT * FROM `customers` join movies on customers.CustomerID=movies.CustomerID WHERE movies.CustomerID='$id'";
		$result = $conn->query($sql);	
		array_push($sample, $id);
		if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			array_push($sample, $row["Movie"]);
			array_push($sample, $id);
			array_push($movie, $row["Movie"]);
				fputcsv($file_movie_class, array($row["Movie"],$key));
		}
		}
        $lines[] = sprintf(
		'%s;%s;%s;%s;%s;s;%s;%s;%s;%s;s;%s;%s;%s;%s;s;%s;%s;%s;%s
		', $key, $sample[0], $sample[1]
		, $sample[2], $sample[3], $sample[4], $sample[5], $sample[6], $sample[7]
		, $sample[8], $sample[9], $sample[10], $sample[11], $sample[12], $sample[13]
		, $sample[14], $sample[15], $sample[16], $sample[17], $sample[18], $sample[19]
		, $sample[20], $sample[21]
		);
		#echo $key." ". $sample[0]." ".$sample[1];
		#echo "<br>";
		array_push($sample, $key);
		#array_push($sample, $movie);
		fputcsv($file, $sample );
$i=$i+1;

    }
		array_push($movie,$key);
		#fputcsv($file_movie_class, $movie );
		$movie = Array();
		#array_push($class_movie,array_push($movie,$key));
		#array_push($class_movie,$key);
		#fputcsv($file_movie_class, $class_movie );

}

    }

    public function testKMeansSamplesLabeledClustering(): void
    {
        $samples = [
            'label1' => [1, 1,1],
            'label2' => [8, 7,1],

        ];

        $kmeans = new KMeans(2);
        $clusters = $kmeans->cluster($samples);

        self::assertCount(2, $clusters);

        foreach ($samples as $index => $sample) {
            if (in_array($sample, $clusters[0], true) || in_array($sample, $clusters[1], true)) {
                self::assertArrayHasKey($index, $clusters[0] + $clusters[1]);
                unset($samples[$index]);
            }
        }

        self::assertCount(0, $samples);
		foreach ($clusters as $key => $cluster) {
    foreach ($cluster as $sample) {
        $lines[] = sprintf('%s;%s;%s', $key, $sample[0], $sample[1]);
		echo $key." ". $sample[0]." ".$sample[1];
		echo "<br>";
    }
}
    }

    public function testKMeansInitializationMethods(): void
    {
        $samples = [
            [180, 155], [186, 159], [119, 185], [141, 147], [157, 158],
            [176, 122], [194, 160], [113, 193], [190, 148], [152, 154],
            [162, 146], [188, 144], [185, 124], [163, 114], [151, 140],
            [175, 131], [186, 162], [181, 195], [147, 122], [143, 195],
            [171, 119], [117, 165], [169, 121], [159, 160], [159, 112],
            [115, 122], [149, 193], [156, 135], [118, 120], [139, 159],
            [150, 115], [181, 136], [167, 162], [132, 115], [175, 165],
            [110, 147], [175, 118], [113, 145], [130, 162], [195, 179],
            [164, 111], [192, 114], [194, 149], [139, 113], [160, 168],
            [162, 110], [174, 144], [137, 142], [197, 160], [147, 173],
        ];

        $kmeans = new KMeans(4, KMeans::INIT_KMEANS_PLUS_PLUS);
        $clusters = $kmeans->cluster($samples);
        self::assertCount(4, $clusters);

        $kmeans = new KMeans(4, KMeans::INIT_RANDOM);
        $clusters = $kmeans->cluster($samples);
        self::assertCount(4, $clusters);
    }

    public function testThrowExceptionOnInvalidClusterNumber(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new KMeans(0);
    }
}
$A=new KMeansTest();
$A->testKMeansSamplesClustering();
?>
<?php

declare(strict_types=1);

namespace Phpml\Tests\Classification;
require 'vendor/autoload.php';
use Phpml\Classification\DecisionTree;
use Phpml\ModelManager;
use PHPUnit\Framework\TestCase;

class DecisionTreeTest extends TestCase
{
    /**
     * @var array
     */
  /*private $data = [
        ['sunny',       85,    85,    'false',    'Dont_play'],
        ['sunny',       80,    90,    'true',     'Dont_play'],
        ['overcast',    83,    78,    'false',    'Play'],
        ['rain',        70,    96,    'false',    'Play'],
        ['rain',        68,    80,    'false',    'Play'],
        ['rain',        65,    70,    'true',     'Dont_play'],
        ['overcast',    64,    65,    'true',     'Play'],
        ['sunny',       72,    95,    'false',    'Dont_play'],
        ['sunny',       69,    70,    'false',    'Play'],
        ['rain',        75,    80,    'false',    'Play'],
        ['sunny',       75,    70,    'true',     'Play'],
        ['overcast',    72,    90,    'true',     'Play'],
        ['overcast',    81,    75,    'false',    'Play'],
        ['rain',        71,    80,    'true',     'Dont_play'],
    ];*/

	  /*
	    private $data = [
        ['sunny',       'Hot',    'high',    'false',    'Dont_play'],
        ['sunny',       'Hot',    'high',    'true',     'Dont_play'],
        ['overcast',    'Hot',    'high',    'false',    'Play'],
        ['rain',        'mild',    'high',    'false',    'Play'],
        ['rain',        'cool',    'normal',    'false',    'Play'],
        ['rain',        'cool',    'normal',    'true',     'Dont_play'],
        ['overcast',    'cool',    'normal',    'true',     'Play'],
        ['sunny',       'mild',    'high',    'false',    'Dont_play'],
        ['sunny',       'cool',    'normal',    'false',    'Play'],
        ['rain',        'mild',    'normal',    'false',    'Play'],
        ['sunny',       'mild',    'normal',    'true',     'Play'],
        ['overcast',    'mild',    'high',    'true',     'Play'],
        ['overcast',    'Hot',    'normal',    'false',    'Play'],
        ['rain',        'mild',    'high',    'true',     'Dont_play'],
    ];
	
		*/

    /**
     * @var array
     */
    private $extraData = [
        ['scorching',   90,     95,     'false',   'Dont_play'],
        ['scorching',  100,     93,     'true',    'Dont_play'],
    ];

    public function testPredictSingleSample(): void
    {
        [$data, $targets] = $this->getData($this->data);
        $classifier = new DecisionTree(4);
        $classifier->train($data, $targets);
		echo $classifier->predict(['overcast', 'Hot', 'normal', 'true']);
		#self::assertEquals('Dont_play', $classifier->predict(['sunny', 78, 72, 'false']));
        #self::assertEquals('Play', $classifier->predict(['overcast', 60, 60, 'false']));
        #self::assertEquals('Dont_play', $classifier->predict(['rain', 60, 60, 'true']));

        #[$data, $targets] = $this->getData($this->extraData);
        #$classifier->train($data, $targets);
        #self::assertEquals('Dont_play', $classifier->predict(['scorching', 95, 90, 'true']));
        #self::assertEquals('Play', $classifier->predict(['overcast', 60, 60, 'false']));
    }

    public function testSaveAndRestore(): void
    {
        [$data, $targets] = $this->getData($this->data);
        $classifier = new DecisionTree(5);
        $classifier->train($data, $targets);

        $testSamples = [['sunny', 78, 72, 'false'], ['overcast', 60, 60, 'false']];
        $predicted = $classifier->predict($testSamples);

        $filename = 'decision-tree-test-'.random_int(100, 999).'-'.uniqid('', false);
        echo $filename; 
		$filepath = (string) tempnam(sys_get_temp_dir(), $filename);
        $modelManager = new ModelManager();
        $modelManager->saveToFile($classifier, $filepath);

        $restoredClassifier = $modelManager->restoreFromFile($filepath);
        self::assertEquals($classifier, $restoredClassifier);
        self::assertEquals($predicted, $restoredClassifier->predict($testSamples));
		
    }
	
	public function testSave(): void
    {
	$servername = "127.0.0.1";
    $username = "root";
    $password = "root";
    $database = "movieclick";

    $conn = new \mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
	$sql = "SELECT * FROM `customers`";
	$result = $conn->query($sql);	
	$array_ = Array();
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
			array_push($array_, 
        array(
            $row['Age'],
			$row['Education Level'],
			$row['Gender'],
			$row['Home Ownership'],
			$row['Internet Connection'],
			$row['Marital Status'],
			$row['Num Bathrooms'],
			$row['Num Bedrooms'],
			$row['Num Cars'],
			$row['Num Children'],
			$row['Num TVs'],
			$row['PPV Freq'],
			$row['Buying Freq'],
			$row['Format'],
			$row['Renting Freq'],
			$row['Viewing Freq'],
			$row['Theater Freq'],
			$row['TV Movie Freq'],
			$row['TV Signal'],
			$row['Movie Selector']
        )
    );
		}
	}
	}
		$data=$array_;
        [$data, $targets] = $this->getData($data);
        $classifier = new DecisionTree(sizeof($data[0]));
        $classifier->train($data, $targets);

		$testSamples = [['62', "Master's Degree", 'Male', 'Own',
		'Cable Modem', "Married", '2.5', '5',
		'5', "2", '3', 'Rarely',
		'Weekly', "DVD", 'Rarely', 'Weekly',
		"Monthly", 'Daily', "Don't watch TV"
		]];
        #$testSamples = [['sunny', 78, 72, 'false'], ['overcast', 60, 60, 'false']];
        $predicted = $classifier->predict($testSamples);
		echo $predicted[0];
		$filepath = "classifier.txt";
        $modelManager = new ModelManager();
        $modelManager->saveToFile($classifier, $filepath);

    }
	    public function testRestore(): void
    {
        $testSamples = [['62', "Master's Degree", 'Male', 'Own',
		'Cable Modem', "Married", '2.5', '5',
		'5', "2", '3', 'Rarely',
		'Weekly', "DVD", 'Rarely', 'Weekly',
		'918025', "Monthly", 'Daily', "Don't watch TV"

		]];
		$filepath = "classifier.txt";
        $modelManager = new ModelManager();
        $restoredClassifier = $modelManager->restoreFromFile($filepath); 
		echo $restoredClassifier->predict($testSamples)[0];
    }

    public function testTreeDepth(): void
    {
        [$data, $targets] = $this->getData($this->data);
        $classifier = new DecisionTree(5);
        $classifier->train($data, $targets);
        self::assertTrue($classifier->actualDepth <= 5);
    }

    private function getData(array $input): array
    {
        $targets = array_column($input, (sizeof($input[0])-1));
        array_walk($input, function (&$v): void {
            array_splice($v,   sizeof($v)-1, 1);
        });

        return [$input, $targets];
    }
}
$A=new DecisionTreeTest();
$A->testSave();
#$A->testRestore();


?>
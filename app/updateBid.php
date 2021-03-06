<?php

require_once 'include/common.php';
require_once 'include/protect.php';
include 'menu.php';

$roundDAO = new RoundDAO();
$bidDAO = new BidDAO();
$sectionDAO = new SectionDAO();

$currentRnd = $roundDAO->retrieveCurrentRound();
$rndStatus = $roundDAO->retrieveRoundStatus();

if($rndStatus=='completed')
{
    echo "<h3>The round has ended</h3><br>";
}
elseif($rndStatus=='Begin')
{
    echo "<h3>The round has not begin</h3><br>";
}
else{
$student = $_SESSION['student'];
$name = $student->getName();
$userId = $student->getUserId();
$bidList = [];
$allBid = [];
$bids = $bidDAO->retrieveCourseIdSectionIdBidded($userId);
$_SESSION['errors']=[];



foreach($bids as $bid)

{   
    $code = $bid[0];
    $section = $bid[1];
    $bidAmt = $bidDAO->retrieveBiddedAmt($userId, $code, $section);
    $vacancy = $sectionDAO->retrieveSectionSize($code,$section);
    $winList = $bidDAO->winBids($code, $section, $vacancy, 2);
    $minBidAmt = $bidDAO->minBid($code, $section, $vacancy, 2, $winList);

    if($currentRnd == '2' && $rndStatus == 'active')
	{
        if($bid[3]=='2')
        {
            $bidList[] = [$code, $section, $bidAmt];
        }
    }
    else
    {
        $bidList[] = [$code, $section, $bidAmt];
    }

    $allBid[] = $bidAmt;
}


echo "<form action = 'updateBid.php' method = 'POST'>";
echo "<h2>Your Current Bidding(s)</h2>";
echo "<table cellspacing='10px' cellpadding='3px'>
<tr>
<th>S/N</th>
<th>Course ID</th>
<th>Section ID</th>
<th>Bid Amount</th>
<th>New Bidding Amount</th>
</tr>";
$count = 0;
$updatedList = [];
foreach($bidList as $bidDisplay) {
    $displayCode = $bidDisplay[0];
    $displaySection = $bidDisplay[1];
    $displayBid = $bidDisplay[2];
    $count++;
    echo "
    <tr>
        <td>
            $count
        </td>
        <td>
            $displayCode
            <input type = 'hidden' name = 'courseId[]' value = '{$displayCode}'>
        </td>
        <td>
            $displaySection
            <input type = 'hidden' name = 'sectionId[]' value = '{$displaySection}'>
        </td>
        <td>
            $displayBid
        </td>
        <td>
            <input type = 'text' name = 'newAmt[]'>
        </td>
    </tr>";

}

// echo "</table>";
// echo "<input type = 'submit' name = 'update' value = 'Update Bid'>";

echo "</table>";
		if(count($bidList)>0){
			echo ' 
			<input type="submit" name="Submit">
			</form>';
		}
		//else{
		//	echo "<a href='welcome.php'>Go back to Home</a>";
		//}
	
echo "</form><br>";
}

// var_dump($_POST['Submit']);
if(isset($_POST['Submit']))
{
    $code = $_POST['courseId'];
    $section = $_POST['sectionId'];
    $newAmt = $_POST['newAmt'];
    $StudentDAO = new StudentDAO();
    $Student=$StudentDAO->retrieveStudentByUserId($userId);
    $studentAmt=0;
    for ($i=0; $i < count($code); $i++) { 
        $sectionBidAmt=$bidDAO->retrieveBiddedAmt($userId, $code[$i], $section[$i]);
        $studentAmt+=$sectionBidAmt;
    }
    $studentAmt+=$Student->getEdollar();

    $count = 0;
    foreach($newAmt as $amount){
        if(ctype_alpha($amount)){
            $_SESSION['errors'][] = "Invalid amount";
            break;
        }

        if ($amount == '') {
            $newAmt[$count] = $sectionBidAmt=$bidDAO->retrieveBiddedAmt($userId, $code[$count], $section[$count]);
        }
        elseif ($amount<10 || !(preg_match('/^(?:[0-9]{0,3})\.{0,1}\d{0,2}$/', $amount)) || $amount>999) {
            // if($amount<10||$amount!=number_format($amount,2,'.','')||$amount>999){
            $_SESSION['errors'][] = "Invalid amount";
            break;
        }
        // $studentAmt-=$amount;
        $count ++;
    }

    if(empty($_SESSION['errors'])){
    $totalBid=0;
    foreach($newAmt as $oneBid){
        $totalBid += $oneBid;
    }
    // echo $studentAmt.$totalBid;
    if($studentAmt<$totalBid){
        $_SESSION['errors'][]='Exceed e-dollar amount';
    }

    if($amount<$minBidAmt  && count($winList)==$vacancy)
    {
        $_SESSION['errors'][]= "Insufficient bidded amount";
    }

    if(!empty($_SESSION['errors'])){
        printErrors();
    }
    else{    

    echo "<h2>Your Updated Bid(s):</h2>";
    echo "<table cellspacing='10px' cellpadding='3px'>
    <tr>
    <th>S/N</th>
    <th>Course ID</th>
    <th>Section ID</th>
    <th>New Bidding Amount</th>
    </tr>";

    foreach($newAmt as $int => $value){
        if ($value === "") $newAmt[$int] = $allBid[$int][0];
    }
    $i = 0;
    $dis_i = 1;

    while($i < sizeof($code))
    {
        $courseId = $code[$i];
        $sectionId = $section[$i];
        $newAmount = $newAmt[$i];
        $oldAmt = $bidDAO->retrieveBiddedAmt($userId, $courseId, $sectionId);
        $StudentDAO->addEdollar($userId, $oldAmt);
        $StudentDAO->deductEdollar($userId, $newAmount);
        

        $newAmount = number_format($newAmount, 2);
        echo "<tr>";
        echo "<td>{$dis_i}</td>";
        echo "<td>{$courseId}</td>";
        echo "<td>{$sectionId}</td>";
        echo "<td>{$newAmount}</td>";
        echo "</tr>";

        $updated = $bidDAO->updateBid($userId, $courseId, $sectionId, $newAmount);
        if($currentRnd == '2' && $rndStatus == 'active')
		{
            $vacancy = $sectionDAO->retrieveSectionSize($courseId,$sectionId);
            $winList = $bidDAO->winBids($courseId, $sectionId, $vacancy, 2);
            // var_dump($winList);
            $allRoundTwo = $bidDAO->retrieveAllByCourseSection($courseId, $sectionId, 2);
            for ($index=0; $index < count($allRoundTwo); $index++) 
            {
                if(!in_array($index, $winList))
                {
                    $bidDAO->updateStatus($allRoundTwo[$index][0], $allRoundTwo[$index][1], $allRoundTwo[$index][2], 'out');
                } 
                else
                {
                    $bidDAO->updateStatus($allRoundTwo[$index][0], $allRoundTwo[$index][1], $allRoundTwo[$index][2], 'in');
                }
            }
        }
        $i++;
        $dis_i++;
    
    }
    
    echo "</table>";
    

}
}
else{
    printErrors();
}
}


echo "<br>";
echo "<a href = 'welcome.php'>Go back to Home</a>";
?>
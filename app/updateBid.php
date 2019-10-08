<?php

require_once 'include/common.php';

$student = $_SESSION['student'];
$name = $student->getName();
$userId = $student->getUserId();

$bidDAO = new BidDAO();
$bidList = [];
$allBid = [];
$bids = $bidDAO->retrieveCourseIdSectionIdBidded($userId);

foreach($bids as $bid)

{   
    $code = $bid[0];
    $section = $bid[1];
    $bidAmt = $bidDAO->retrieveBiddedAmt($userId, $code, $section);

    $bidList[] = [$code, $section, $bidAmt];
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
    $displayBid = $bidDisplay[2][0];

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

echo "</table>";
echo "<input type = 'submit' name = 'update' value = 'Update Bid'>";
echo "</form><br>";

if(isset($_POST['update']))
{
    echo "<h2>Your Updated Bid(s):</h2>";
    echo "<table cellspacing='10px' cellpadding='3px'>
    <tr>
    <th>S/N</th>
    <th>Course ID</th>
    <th>Section ID</th>
    <th>New Bidding Amount</th>
    </tr>";

    $code = $_POST['courseId'];
    $section = $_POST['sectionId'];
    $newAmt = $_POST['newAmt'];

    foreach($newAmt as $int => $value){
        if ($value === "") $newAmt[$int] = $allBid[$int][0];
    }

    $i = 0;

    while($i < sizeof($code))
    {
        $courseId = $code[$i];
        $sectionId = $section[$i];
        $newAmount = $newAmt[$i];

        $i++;

        echo "<tr>";
        echo "<td>{$i}</td>";
        echo "<td>{$courseId}</td>";
        echo "<td>{$sectionId}</td>";
        echo "<td>{$newAmount}</td>";
        echo "</tr>";

        $updated = $bidDAO->updateBid($userId, $courseId, $sectionId, $newAmount);
    
    }
    
    echo "</table>";
    

}

echo "<br>";
echo "<a href = 'welcome.php'>Go back to Home</a>";

?>
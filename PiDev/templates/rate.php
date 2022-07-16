<?php
$conn = new mysqli('localhost', 'root', '', 'projet');

if (isset($_POST['save'])) {
    $uID = $conn->real_escape_string($_POST['uID']);
    $ratedIndex = $conn-real_escape_string($_POST['ratedIndex']);
    $ratedIndex++;

    if (!$uID) {
        $conn->query("INSERT INTO rating (rateIndex) VALUES ('$ratedIndex')");
        $sql = $conn->query("SELECT id FROM stars ORDER BY id DESC LIMIT 1");
        $uData = $sql->fetch_assoc();
        $uID = $uData['id'];
    } else
        $conn->query("UPDATE stars SET rateIndex='$ratedIndex' WHERE id='$uID'");

    exit(json_encode(array('id' => $uID)));
}

$sql = $conn->query("SELECT id FROM stars");
$numR = $sql->num_rows;

$sql = $conn->query("SELECT SUM(rateIndex) AS total FROM stars");
$rData = $sql->fetch_array();
$total = $rData['total'];

$avg = $total / $numR;
?>
<?php
$conn = new mysqli('localhost', 'root', '', 'projet');

if (isset($_POST['save'])) {
    $uID = $conn->real_escape_string($_POST['uID']);
    $ratedIndex = $conn->real_escape_string($_POST['ratedIndex']);
    $ratedIndex++;

    if (!$uID) {
        $conn->query("INSERT INTO stars (rateIndex) VALUES ('$ratedIndex')");
        $sql = $conn->query("SELECT id FROM stars ORDER BY id DESC LIMIT 1");
        $uData = $sql->fetch_assoc();
        $uID = $uData['id'];
    } else
        $conn->query("UPDATE stars SET rateIndex='$ratedIndex' WHERE id='$uID'");

    exit(json_encode(array('id' => $uID)));
}

$sql = $conn->query("SELECT id FROM stars");
$numR = $sql->num_rows;

$sql = $conn->query("SELECT SUM(rateIndex) AS total FROM stars");
$rData = $sql->fetch_array();
$total = $rData['total'];

$avg = $total / $numR;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rating System</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
</head>
<body>
<div align="center" style="background: #000; padding: 50px;color:white;">
    <i class="fa fa-star fa-2x" data-index="0"></i>
    <i class="fa fa-star fa-2x" data-index="1"></i>
    <i class="fa fa-star fa-2x" data-index="2"></i>
    <i class="fa fa-star fa-2x" data-index="3"></i>
    <i class="fa fa-star fa-2x" data-index="4"></i>
    <br><br>
    <?php echo round($avg,2) ?>
</div>

<script src="http://code.jquery.com/jquery-3.4.0.min.js" integrity="sha256-BJeo0qm959uMBGb65z40ejJYGSgR7REI4+CW1fNKwOg=" crossorigin="anonymous"></script>
<script>
    var ratedIndex = -1, uID = 0;

    $(document).ready(function () {
        resetStarColors();

        if (localStorage.getItem('ratedIndex') != null) {
            setStars(parseInt(localStorage.getItem('ratedIndex')));
            uID = localStorage.getItem('uID');
        }

        $('.fa-star').on('click', function () {
            ratedIndex = parseInt($(this).data('index'));
            localStorage.setItem('ratedIndex', ratedIndex);
            saveToTheDB();
        });

        $('.fa-star').mouseover(function () {
            resetStarColors();
            var currentIndex = parseInt($(this).data('index'));
            setStars(currentIndex);
        });

        $('.fa-star').mouseleave(function () {
            resetStarColors();

            if (ratedIndex != -1)
                setStars(ratedIndex);
        });
    });

    function saveToTheDB() {
        $.ajax({
            url: "index.php",
            method: "POST",
            dataType: 'json',
            data: {
                save: 1,
                uID: uID,
                ratedIndex: ratedIndex
            }, success: function (r) {
                uID = r.id;
                localStorage.setItem('uID', uID);
            }
        });
    }

    function setStars(max) {
        for (var i=0; i <= max; i++)
            $('.fa-star:eq('+i+')').css('color', 'green');
    }

    function resetStarColors() {
        $('.fa-star').css('color', 'white');
    }
</script>
</body>
</html>
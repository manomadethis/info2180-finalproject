<?php
    session_start();
    require_once('conn.php');

    $allowedMethods = array('POST');

    $nregtest = "/^[A-Za-z.\s-]+$/";
    $regtest = "/^[0-9a-zA-Z]+$/";
    $eregtest = "/.{1,}@[^.]{1,}/";

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $query = filter_input(INPUT_POST, "query", FILTER_SANITIZE_STRING);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $email = $_SESSION['email']; 
        $newidsql = $conn->query("SELECT `id` FROM `users` WHERE email = '$email'");
        $my_id = $newidsql->fetchAll(PDO::FETCH_ASSOC);
        

        if($_POST["query"] == "all"){      

            $sql = $conn->query("SELECT s.id, s.title, s.type, s.status , u.firstname, u.lastname, s.created 
            FROM issues s JOIN users u on s.assigned_to = u.id ");
            
        }
        elseif ($_POST["query"] == "open")
        {      

            $sql = $conn->query("SELECT s.id, s.title, s.type, s.status , u.firstname, u.lastname, s.created 
            FROM issues s JOIN users u on s.assigned_to = u.id 
            WHERE s.status = 'Open'");
            
        }
        elseif ($_POST["query"] == "my"){      

            $id =intval($_POST["id"]);
            
            $_SESSION['id'] = $id;
            $sql = $conn->query("SELECT s.id, s.title, s.type, s.status , u.firstname, u.lastname, s.created 
            FROM issues s JOIN users u on s.assigned_to = u.id 
            WHERE u.id = $id");
            
        }

        $results = $sql->fetchAll(PDO::FETCH_ASSOC);
        $conn = null;
    } 
    catch (PDOException $pe) 
    {
        die("Could not connect to the database $dbname :" . $pe->getMessage());
    }
?>


<?php foreach ($results as $row): ?>
    <tr>
        <td ><b>#<?= $row['id']?></b>  <a href="../html/FullDetailsPagehtml.php?query=<?= $row['id']?>"><?=$row['title']; ?></a>   </td>
        <td scope="col"><?= $row['type']; ?></td>
        <? if ($row['status'] == 'Open'):?>
            <td scope="col" class="center"><p id="open"><?= $row['status']; ?></p></td>
        <? elseif($row['status'] == 'In Progress'):?>
            <td scope="col" class="center"><p id="progress"><?= $row['status']; ?></p></td>
            <? elseif($row['status'] == 'Closed'):?>
            <td scope="col" class="center"><p id="closed"><?= $row['status']; ?></p></td>
        <? endif;?>
        <td scope="col"><?= $row['firstname'];?> <?=$row['lastname']; ?></td>
        <td scope="col"><?= $row['created']; ?></td>
    </tr>
<?php endforeach; ?> 

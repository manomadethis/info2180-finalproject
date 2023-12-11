<?php
session_start();
if (!isset($_SESSION["user"])) {
    $_SESSION["message"] = "You need to login first!";
    header("Location: login.php");
    exit();
}

include 'db-connect.php';

$query = isset($_GET['query']) ? $_GET['query'] : null;

if ($query == 'Assigned to me') {
    $userId = $_SESSION['user'];

    $stmt = $db->prepare('SELECT * FROM contacts WHERE assigned_to = :userId');
    $stmt->execute(['userId' => $userId]);
} else if ($query) {
    $stmt = $db->prepare('SELECT * FROM contacts WHERE type = :query');
    $stmt->execute(['query' => $query]);
} else {
    $stmt = $db->prepare('SELECT * FROM contacts');
    $stmt->execute();
}

$contacts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/users.css">
    <link rel="stylesheet" href="../css/card.css">
    <link rel="stylesheet" href="../css/table.css">
    <script src="../js/scripts.js"></script>
</head>

<body>

    <!-- Header -->
    <header>
        <div class="header-container">
            <img class="dolphin-img" src="../img/dolphin.jpg" alt="Dolphin CRM Logo">
            <p>Dolphin CRM</p>
        </div>
    </header>

    <!-- Body -->
    <main>
        <div class="content-container">

            <!-- Sidebar -->
            <section id="sidebar">
                <aside>
                    <ul>
                        <li>
                            <a href="#" class="tablink" onclick="openTab(event, 'Home')">
                                <img src="../img/home.png" alt="Home Icon"> Home
                            </a>
                        </li>
                        <li>
                            <a href="#" class="tablink" onclick="openTab(event, 'new-contact')">
                                <img src="../img/contact.png" alt="Contact Icon"> New Contact
                            </a>
                        </li>
                        <li>
                            <a href="#" class="tablink" onclick="openTab(event, 'users')">
                                <img src="../img/users.png" alt="Users Icon"> Users
                            </a>
                        </li>
                    </ul>
                    <hr>
                    <ul>
                        <li>
                            <a href="logout.php" class="tablink">
                                <img src="../img/logout.png" alt="Logout Icon"> Logout
                            </a>
                        </li>
                    </ul>
                </aside>
            </section>

            <!-- Main Content -->
            <section id="main-content">

                <!-- Dashboard Tab -->
                <div id="Home" class="tabcontent active">
                    <div id="dashboard-card-header" class="card-header">
                        <h1>Dashboard</h1>
                        <button id="dashboard-card-add-button" class="add-button" onclick="openTab(event, 'new-contact')">Add Contact</button>
                    </div>

                    <!-- Dashboard Card -->
                    <div id="dashboard card" class="card">

                        <!-- Filter -->
                        <?php
                        $query = isset($_GET['query']) ? $_GET['query'] : null;
                        ?>

                        <div id="dashboard-filter" class="filter">
                            <img src="../img/filter.png" alt="Filter Image">
                            <h4>Filter By:</h4>
                            <span><a href="index.php" class="<?php echo $query === null ? 'selected' : '' ?>">All</a></span>
                            <span><a href="index.php?query=Sales Lead" class="<?php echo $query === 'Sales Lead' ? 'selected' : '' ?>">Sales Lead</a></span>
                            <span><a href="index.php?query=Support" class="<?php echo $query === 'Support' ? 'selected' : '' ?>">Support</a></span>
                            <span><a href="index.php?fetch=<?= $_SESSION['user'] ?>">Assigned to me</a></span>
                        </div>

                        <!-- Table -->
                        <table id="contacts-table" class="table">
                            <thead>
                                <tr>
                                    <th id = "name-column"> Name </th>
                                    <th id = "email-colomn"> Email </th>
                                    <th id = "company-column"> Company </th>
                                    <th id = "type-column"> Type </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                                <!-- PHP Queries for Data -->
                                <?php
                                $host = "localhost";
                                $username = "root";
                                $password = "";
                                $dbname = "schema";
                                $conn = new mysqli($host,$username,$password,$dbname);

                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }

                                $stmt = null;
                                if(!isset($_GET['fetch'])){
                                    if(!isset($_GET['query'])){
                                        $stmt = $conn->prepare("SELECT * FROM contacts");
                                    } else {
                                        $filter = $_GET['query'];
                                        $stmt = $conn->prepare("SELECT * FROM contacts WHERE type = ?");
                                        $stmt->bind_param("s", $filter);
                                    }
                                } else {
                                    $user = $_GET['fetch'];
                                    $stmt = $conn->prepare("SELECT * FROM contacts WHERE assigned_to = ?");
                                    $stmt->bind_param("s", $user);
                                }

                                $stmt->execute();
                                $results = $stmt->get_result();

                                while($row = $results->fetch_assoc()){
                                    $typeClass = $row["type"] == "Sales Lead" ? "sales-lead" : "support";
                                    echo "<tr>
                                    <td>".$row["title"]." ".$row["firstname"]." ".$row["lastname"]."</td>
                                    <td class='email-data'>".$row["email"]."</td>
                                    <td class='company-data'>".$row["company"]."</td>
                                    <td><span class='type-data ".$typeClass."'>".$row["type"]."</span></td>
                                    <td class='view-data'><a href='view.php?id=$row[id]'class = 'btn btn-primary'>View</a></td>
                                    </tr>";
                                }

                                $stmt->close();
                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- New Contact Tab -->
                <div id="new-contact" class="tabcontent">
                    <div id="contact-card-header" class="card-header">
                    <h1>New Contact</h1>
                    </div>

                    <!-- Contact Card -->
                    <div id="new-contact-card" class="card">

                        <div id="form" class="form-container">
                            <form action="../pages/contacts.php" method="post">

                                <!-- Title -->
                                <div class="title-container">
                                    <div>
                                        <label for="title" id="title-label">Title</label>
                                    </div>
                                    <br>
                                    <div>
                                        <select name="titles" id="title">
                                            <option value="Mr." selected="selected">Mr.</option>
                                            <option value="Mrs.">Mrs.</option>
                                            <option value="Ms.">Ms.</option>
                                            <option value="Dr">Dr</option>
                                            <option value="Prof">Prof</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Name -->
                                <div class="name-container">
                                    <div>
                                        <label for="firstname" id="fname-label">First Name</label>
                                        <input id="firstname" type="text" aria-required="true" required name="firstname" placeholder="Jane" />
                                    </div>
                                    <div>
                                        <label for="lastname" id="lname-label">Last Name</label>
                                        <input id="lastname" type="text" aria-required="true" required name="lastname" placeholder="Doe" />
                                    </div>
                                </div>

                                <!-- Email and Telephone -->
                                <div class="email-container">
                                    <div>
                                        <label for="emailaddr" id="email-label">Email</label>
                                        <input id="emailaddr" type="email" aria-required="true" required name="emailaddr" placeholder="something@example.com" autocomplete="email" />
                                    </div>
                                    <div>
                                        <label for="tele" id="tele-label">Telephone</label>
                                        <input id="tele" type="tel" aria-required="true" required name="tele" placeholder="123-456-7890"/>
                                    </div>
                                </div>

                                <!-- Company and Type -->
                                <div class="company-container">
                                    <div>
                                        <label for="company" id="company-label">Company</label>
                                        <input id="company" aria-required="true" required name="company" autocomplete="organization" />
                                    </div>
                                    <div>
                                        <label for="types" id="type-label">Type</label>
                                        <select name="types" id="types">
                                            <option value="Sales Lead" selected="selected">Sales Lead</option>
                                            <option value="Support">Support</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Assigned To -->
                                <div class="assigned-container">
                                    <label for="assigned" id="assigned-label">Assigned To</label>
                                    <br>
                                    <br>
                                    <select name="assigned" id="assigned">
                                    <?php
                                        $host = "localhost";
                                        $username = "root";
                                        $password = "";
                                        $dbname = "schema";
                                        $conn = new mysqli($host,$username,$password,$dbname);

                                        // Check connection
                                        if ($conn->connect_error) {
                                            die("Connection failed: " . $conn->connect_error);
                                        }

                                        // Prepare a select statement
                                        $stmt = $conn->prepare("SELECT firstname, lastname FROM users");

                                        // Execute the statement
                                        $stmt->execute();

                                        // Bind the result variables
                                        $stmt->bind_result($firstName, $lastName);

                                        // Fetch values and output data of each row
                                        while($stmt->fetch()) {
                                            echo "<option value='" . $firstName . " " . $lastName . "'>" . $firstName . " " . $lastName . "</option>";
                                        }

                                        // Close statement
                                        $stmt->close();
                                        ?>
                                    </select>
                                </div>

                                <button type="submit" id="save-button">Save</button>

                            </form>
                        </div>
                    </div>
                </div>

                <!-- New User Tab -->
                <div id="new-user" class="tabcontent">
                    <div id="user-card-header" class="card-header">
                        <h1>New User</h1>
                    </div>

                    <!-- New User Card -->
                    <div id="new-user-card" class="card">

                        <div id="form" class="form-container">
                            <form action="../pages/add-user.php" method="post">

                                <!-- Name -->
                                <div class="name-container">
                                    <div>
                                        <label for="firstname" id="fname-label">First Name</label>
                                        <input id="firstname" type="text" aria-required="true" required name="firstname" placeholder="Jane" />
                                    </div>
                                    <div>
                                        <label for="lastname" id="lname-label">Last Name</label>
                                        <input id="lastname" type="text" aria-required="true" required name="lastname" placeholder="Doe" />
                                    </div>
                                </div>

                                <!-- Email and Password -->
                                <div class="email-container">
                                    <div>
                                        <label for="emailaddr" id="email-label">Email</label>
                                        <input id="emailaddr" type="email" aria-required="true" required name="emailaddr" placeholder="something@example.com" autocomplete="email" />
                                    </div>
                                    <div>
                                        <label for="password" id="password-label">Password</label>
                                        <input id="password" type="password" aria-required="true" required name="password" placeholder="********" autocomplete="new-password" />
                                    </div>
                                </div>

                                <!-- Role -->
                                <div class="role-container">
                                    <label for="role" id="role-label">Role</label>
                                    <br>
                                    <br>
                                    <select name="role" id="role">
                                        <option value="Member">Member</option>
                                        <option value="Admin" selected="selected">Admin</option>
                                    </select>
                                </div>

                                <button type="submit" id="save-button">Save</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Users Tab -->
                <div id="users" class="tabcontent">
                    <div id="users-card-header" class="card-header">
                        <h1>Users</h1>
                        <button id="users-card-add-button" class="add-button" onclick="openTab(event, 'new-user')">Add User</button>
                    </div>

                    <!-- Users Card -->
                    <div id="user-card" class="card">

                        <!-- Table -->
                        <table id="users-table" class="table">
                            <thead>
                                <tr>
                                    <th id = "name-column"> Name </th>
                                    <th id = "email-colomn"> Email </th>
                                    <th id = "role-column"> Role </th>
                                    <th id = "created-column"> Type </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                                <!-- PHP Queries for Data -->
                                <?php
                                $host = "localhost";
                                $username = "root";
                                $password = "";
                                $dbname = "schema";
                                $conn = new mysqli($host,$username,$password,$dbname);

                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }

                                // Prepare a select statement
                                $stmt = $conn->prepare("SELECT firstName, lastName, email, role, created_at FROM users");

                                // Execute the statement
                                $stmt->execute();

                                // Bind the result variables
                                $stmt->bind_result($firstName, $lastName, $email, $role, $created);

                                // Fetch values and output data of each row
                                while($stmt->fetch()) {
                                    echo "<tr>";
                                    echo "<td>" . $firstName . " " . $lastName . "</td>";
                                    echo "<td>" . $email . "</td>";
                                    echo "<td>" . $role . "</td>";
                                    echo "<td>" . $created . "</td>";
                                    echo "</tr>";
                                }

                                // Close statement and connection
                                $stmt->close();
                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>

    </main>


</body>

</html>


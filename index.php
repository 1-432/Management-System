<?php
// Include the database connection file
include 'db_connect.php'; 

$message = "";

// 1. Handle form submission for assignment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['assign'])) {
    // Sanitize and escape inputs for security
    $employee_id = $conn->real_escape_string($_POST['employee_id']);
    $project_id = $conn->real_escape_string($_POST['project_id']);

    // Check if the assignment already exists
    $check_sql = "SELECT * FROM assignments WHERE employee_id = '$employee_id' AND project_id = '$project_id'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        $message = "<p class='error-message'>Error: This employee is already assigned to this project.</p>";
    } else {
        $sql_insert = "INSERT INTO assignments (employee_id, project_id) VALUES ('$employee_id', '$project_id')";

        if ($conn->query($sql_insert) === TRUE) {
            $message = "<p class='success-message'>Assignment successful!</p>";
        } else {
            $message = "<p class='error-message'>Error inserting assignment: " . $conn->error . "</p>";
        }
    }
}

// 2. Fetch data for dropdowns (Employees and Projects)
// Note: We reset the pointers for use in the HTML section later.
$employees_result = $conn->query("SELECT employee_id, name FROM employees ORDER BY name ASC");
$projects_result = $conn->query("SELECT project_id, project_name FROM projects ORDER BY project_name ASC");

// 3. Fetch current assignments for display
$assignments_result = $conn->query("
    SELECT e.name AS employee_name, p.project_name, p.status
    FROM assignments a
    JOIN employees e ON a.employee_id = e.employee_id
    JOIN projects p ON a.project_id = p.project_id
    ORDER BY a.assignment_date DESC
");

?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <title>Employee Project Assignment System</title>
     <link rel="stylesheet" href="style.css">
</head>

<body>
     <h1>🚀 Tech Co. Project Assignment Dashboard</h1>

     <div id="messages"><?php echo $message; ?></div>

     <section>
          <h2>Assign Employee to Project</h2>
          <form action="index.php" method="POST" id="assignmentForm">
               <label for="employee_id">Employee:</label>
               <select name="employee_id" id="employee_id" required>
                    <option value="">-- Select Employee --</option>
                    <?php while($row = $employees_result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['employee_id']); ?>">
                         <?php echo htmlspecialchars($row['name']); ?>
                    </option>
                    <?php endwhile; ?>
               </select>

               <label for="project_id">Project:</label>
               <select name="project_id" id="project_id" required>
                    <option value="">-- Select Project --</option>
                    <?php while($row = $projects_result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['project_id']); ?>">
                         <?php echo htmlspecialchars($row['project_name']); ?>
                    </option>
                    <?php endwhile; ?>
               </select>

               <button type="submit" name="assign">Assign</button>
          </form>
     </section>

     <hr>

     <section>
          <h2>Current Project Assignments</h2>
          <table>
               <thead>
                    <tr>
                         <th>Employee Name</th>
                         <th>Project Name</th>
                         <th>Status</th>
                    </tr>
               </thead>
               <tbody>
                    <?php if ($assignments_result->num_rows > 0): ?>
                    <?php while($row = $assignments_result->fetch_assoc()): ?>
                    <tr>
                         <td><?php echo htmlspecialchars($row['employee_name']); ?></td>
                         <td><?php echo htmlspecialchars($row['project_name']); ?></td>
                         <td><?php echo htmlspecialchars($row['status']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <tr>
                         <td colspan="3">No projects are currently assigned.</td>
                    </tr>
                    <?php endif; ?>
               </tbody>
          </table>
     </section>

     <script src="script.js"></script>
</body>

</html>
<?php
// Close the database connection
$conn->close(); 
?>
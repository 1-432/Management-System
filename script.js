document.addEventListener('DOMContentLoaded', function() {
     const form = document.getElementById('assignmentForm');
     
     if (form) {
         form.addEventListener('submit', function(event) {
             const employeeSelect = document.getElementById('employee_id');
             const projectSelect = document.getElementById('project_id');
 
             // Basic validation to ensure a selection was made
             if (employeeSelect.value === "" || projectSelect.value === "") {
                 alert('Please select both an Employee and a Project for the assignment.');
                 event.preventDefault(); // Stop form submission
             }
             // Note: PHP handles the server-side validation for duplicate assignments.
         });
     }
 
     // Optional: Fade out success/error messages after a few seconds
     const messages = document.getElementById('messages');
     if (messages && messages.textContent.trim() !== "") {
         setTimeout(() => {
             messages.style.transition = 'opacity 1s';
             messages.style.opacity = '0';
         }, 3000); 
     }
 });
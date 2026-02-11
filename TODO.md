# TODO: Design Add User Page with Two Sections

## Information Gathered
- **add_user.php**: Contains the form for adding a new user with fields for Username, Password, Email, Role. Includes client-side validation and a toggle for password visibility. Only admins can access.
- **users.php**: Displays a table of all users with columns for #, Username, Password (masked by default with eye icon to toggle), Role, and Actions (Edit, Delete). Passwords are hashed in DB but displayed masked. Uses Bootstrap for styling.
- **php_action/createUser.php**: Handles user creation via POST, inserts into DB, and redirects to users.php on success. Uses password_hash for security.
- **php_action/removeUser.php**: Handles user deletion via GET, deletes from DB, and redirects to Users.php (note: capital U, likely a typo).
- **Database (users table)**: Fields include user_id (primary), username, password (hashed), email, role. Missing status and created_date as per task requirements (e.g., Status, Created Date).
- **Notifications**: Project uses SweetAlert (assets/js/lib/sweetalert/sweetalert.init.js) for alerts/toasters.
- **Dependencies**: Relies on constant/connect.php for DB connection, and includes head.php, header.php, sidebar.php, footer.php for layout.

## Plan
- **Database Updates**:
  - Alter users table to add `status` (VARCHAR(20), default 'active') and `created_date` (TIMESTAMP, default CURRENT_TIMESTAMP).
- **Backend Modifications**:
  - Update php_action/createUser.php: Include status and created_date in insert; set session message for success/error; redirect to add_user.php instead of users.php.
  - Update php_action/removeUser.php: Fix redirect to add_user.php (lowercase); set session message for success/error.
- **Frontend Modifications (add_user.php)**:
  - Restructure page into two sections: Top section for "Add New User" form (existing form, enhanced with better styling); Bottom section for "User List" table (copied/adapted from users.php, with added Status and Created Date columns).
  - Add session-based toaster notifications using SweetAlert for add/delete success/errors.
  - Implement auto-refresh of user table after add/delete (use AJAX for delete to avoid full page reload; for add, reload page after redirect).
  - Ensure table is responsive, professional, with masked passwords (eye icon to view), and action buttons (View eye icon, Edit, Delete).
  - Add proper spacing, alignment, icons (FontAwesome), and consistent colors (Bootstrap classes).
- **Security/Usability**:
  - Maintain admin-only access.
  - Secure password handling (hashed in DB, masked in UI).
  - Client-side validation for form.
  - Confirm delete with SweetAlert before proceeding.

## Dependent Files to be Edited
- constant/connect.php: No changes needed.
- php_action/createUser.php: Update insert query, add session messages, change redirect.
- php_action/removeUser.php: Fix redirect, add session messages.
- add_user.php: Major restructure to include both sections, add table, JS for toasters and table refresh.

## Followup Steps
- Run SQL to alter users table.
- Test add user functionality (form submit, toaster, table refresh).
- Test delete user functionality (confirm dialog, toaster, table refresh).
- Verify responsive design and UI consistency.
- Ensure no security vulnerabilities (e.g., SQL injection prevention via prepared statements).

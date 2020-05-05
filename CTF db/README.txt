Using the db:

1) Create a new user for mysql with a password
2) Open the file CTF/php_web/include/connector.php 
3) the first argument is the db server address, you could probably just leave that
   the second argument is the username, you would want to change this to the username of the new user you created
   the third argument is the password, you would want to change this to the password of the new user you created as well
   the fourth argument is the database name, which you could probably leave as well

   The website should work fine now.

4) To add questions, use the questions table,
   Insert rows with values for columns 'qsn','ans', and 'score', not for 'id'
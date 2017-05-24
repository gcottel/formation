## Exercices

# 0 - Build the full FrameWork from OCRoom &#10004;

# 1 - Add Feature : Allow logged user to logout &#10004;
* Add a link in menu to logout the connected user. 
* Make a new action in ConnexionController.
* After logout, redirect to homepage. 

# 2 - Refactoring user management &#10004;
* Store user in Database : Create table(s) according to DreamCentury database naming convention.
* Add a new Manager to work the new table(s) (according to the Framework Manager naming convention).
* Update your ConnexionController code.
* Adding a subscribtion form : Input list [ login, passwod, password confirmation, email, email confirmation ].

## 2.1 - Two types of user &#10004;
* The users who subscribe with subscription form are now simple writers. They are not a full administrator and they can only edit their news.

# 3 - CSS : Fix the CSS error when text is too long &#10004;
* Try to enter a very long text in title and text of a News and observe the result. (Long text without space)
* Fix visual problem by adding some CSS rules.

# 4 - Fix SQL Injection &#10004;
* If you have good memory, you know that SQL Injection mean ! 
* Remove all SQL Injection vulnerabilities. 

# 5 - Fix JavaScript Injection 
* Google is your best friend.
* Remove all JavaScript Injection vulnerabilities. 

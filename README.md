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

# 5 - Fix JavaScript Injection &#10004;
* Google is your best friend.
* Remove all JavaScript Injection vulnerabilities. 

# 6 - Improve your code : Url and Link &#10004;
Actually, you need to enter manually the value of a href attribute according to the route.xml file.  
What happens if tomorrow I decided to edit a route ?  
All your code break down.  
This part consists to add a functionnality that ask for a route to the Controller and an Action using a function. Replace the manually entered href by a call of this function.

# 7 - Add Feature : Ajax ! Flower Party :) 
* Change the comportment of the form to add a comment in a news page.
* The form are include in the same page of the news. On top and bottom of comment list.
* Form to add new comment must work now with ajax. So when user submit his form, don't reload the page but post an ajax query to valid the form.
* Show errors or add new comment directly if there are no error.
* The HTTP Response of any Ajax call must be a JSON Object.

# 7 - Lecture vidéo youtube des commentaires
* detection d'une url youtube (regexp)
* récupérer le code de la vidéo
* création de l'url de l'image miniature
* insérer le code de la vidéo dans le template (code youtube) 
* affichage de la miniature
* sur click: lecture de la vidéo (code youtube)

<object type="application/x-shockwave-flash" style="width:425px; height:344px;" data="http://www.youtube.com/v/brcDk4DOBP4" >
   <param name="movie" value="http://www.youtube.com/v/brcDk4DOBP4" />
   <param name="allowFullScreen" value="true" />
</object>

<object width="425" height="344">
<param name="movie" value="http://www.youtube.com/v/nnlSfD1KT_w&hl=fr&fs=1&"></param>
<param name="allowFullScreen" value="true"></param>
<param name="allowscriptaccess" value="always"></param>
<embed src="http://www.youtube.com/v/nnlSfD1KT_w&hl=fr&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed>
</object>
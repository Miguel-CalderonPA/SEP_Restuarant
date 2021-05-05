# SEP_Restuarant
Software Engineering Project
Dan Kline, Shivane Rathore, & Miguel Calderon				       5/5/2021
Admin/User/Developer Documentation						          Professor Cornell

---------------------------------------------------------------------------------------------------

Admin Documentation                                                .
For Access to server (needed for both maintenance and installation)
Must have a valid AWS key downloaded for you to login (usually .pem file)
Server must have a record of your login credentials within your user’s home. In the .ssh folder, in the authorized_key file . Should start with ssh -rsa and end with a key name.
To login
On linux: use your terminal, logging in with your SSH client and key. 
Using command ssh -i [key.pem]
On windows: use putty to login with a key in the form of a .ppk file (converted from .pem)
Another option is to login through AWS site. Once logged in click EC2 Dashboard → click Instances (Running) → click Instance ID → click Connect → click Connect again. You should be automatically logged in
For Installing updates and new libraries
Must have access to server
Once on server you must be part of the proper group or if necessary sudo command
Then type in sudo yum [flags]

For maintenance 
There are more options for maintenance for the website compared to installation
First you must have access to server
Then you can use any software that allows SFTP to login to the server. You must have your AWS key on your software program, your username, the hostname for the server, and go through port 22. If necessary set Timeout to your likings and change automatic authentication to your key value.
To access files on the server, you must go to the root directory → var → www → html → efs-mount-point → sampledir → then your files will be located here (CSS, HTML, PHP, Images)
Once you are on the server you will either download the file locally or on the server. Use your editor of choice, some options are visual code, notepad++, vi, nano, emacs etc.

For Google API
When logging in with your credentials you will see an overview of used APIs.
Click on API in use and number of requests and errors will be displayed
If necessary you can check your billings on the same page below the “Requests by API” graph. You may need to click the billing report.

---------------------------------------------------------------------------------------------------

User Documentation                                                    .

USER REGISTRATION
First go the login page @ http://ec2-52-14-132-218.us-east-2.compute.amazonaws.com/efs-mount-point/sampledir/HTML/login.html
Click on Create an account below the Login button in red
Once on (*<)homper Registration Form 
Click in User Name box and type in your username
Click in First Name box and type in your first name
Click in Last Name box and type in your last name
Click in Email box and type in your email
Click in Password and type in your password (reminder you password by itself will not be saved)
Click in Re-type Password and type in your password again (again, your password by itself will not be saved)
Click on Register button in red → you should be redirected to login page
On login page
Type your Username in the Enter Username box
Type your password in the Enter Password box. 
Click the Login button in red → You should be redirected to Fruip Dashboard page.

FRUIP REGISTRATION OR JOINING

You should be on a Fruip Dashboard page, this is the page you will see all of your Fruips (Groups of people who like to dine with each other) You have a couple of options in order to obtain a Fruip.
If you want to create your own Fruip Click the Add link in the sidebar it should be the 1st bullet
On Fruip Registration Form
Type your new Fruip name in Fruip Name box name must not contain special characters or whitespace
Click Register button in red → you should be redirected to Fruip Dashboard page
If you want to join a Fruip 
Go to the 4th bullet point on the sidebar
 Click in Please enter a Fruip name box and enter a Fruip name. 
If you need to look up Fruips to join
Click on the 2nd bullet on side bar named Browse Fruips for Fruip names
Click Send Join Request button in gray
If successful request sent, you will receive a notification on the top of your screen
Once the owner of the Fruip accepts you, you will be part of that Fruip

Managing Fruips

On the Fruip Dashboard page, there should be a table labeled Fruips with the columns of Fruip / Currently Voting? / Voting Status / Results / Manage 
Underneath Manage Click Manage button in gray → this will direct you to Fruip Management page
On the Fruip Management page you will see two tables
The first table will show your member’s Name / Status / Remove? Option. Do not worry about status yet, that will be in the next section.
To remove a member from the Fruip you must be an owner. 
You will know if you are an owner if you can see the Fruip Management page
To remove the member you find their name on the first table. Go over two columns past Status and to the remove Column. 
Click Remove 
 this will remove the member from the Fruip
The Second table shows requests made to join the Fruip with the columns being Name / Approve/Disapprove   
To add a member, you have two options
First: send a member an add request 
(TO BE CONTINUED & UPDATED)
Second: approve a request from a user on the second table, that is in second column underneath Approve/Disapprove click Approve 
To reject a member request go to the second table, second column underneath Approve/Disapprove click Disapprove 

To Delete a Fruip

Go to the Fruip management page on the 3rd section of the side click Delete 
You will get a notification saying Permanently delete Fruip? 
Clicking Cancel will cancel the operation.
Clicking Okay will give another notification saying Are you sure?
Clicking Cancel will cancel the operation.
Clicking Okay will Delete the Fruip → and direct you to Fruip Dashboard
Clicking away from the prompt will cancel the operation!
To return to Fruip Dashboard click the 4th section link on the sidebar named Back to Dashboard

Voting

As a Owner
Go to Fruip Management Page
The 1st section on the sidebar type in the zipcode you wish to use in your restaurant search in Please enter the zip code box 
Once zipcode is confirmed, you will get a notification stating Voting initiated Successfully! 
As the Owner, your vote will be the trigger to end the vote and thus you need to wait for your members to vote. 
Side note: This also gives you deciding power in the case of a tie.
Voting directions are underneath the “As a member” section below
Once the results are in, you can reset the vote by resetting the zipcode on the Fruip Management page
As a member
After the Owner has initiated the vote for your fruip you will see a new button appear on the Fruip Dashboard under the table column name Voting Status, the button will be called Voting Status
Click Vote 
Once you are on the voting page, you will see an image with a restaurant picture, the restaurant name, and the restaurant address
If you like the restaurant shown then Click the Heart button 
If you do not like the restaurant shown then click the X button
You will repeat this for around 20 restaurants
Side Note: You can resume picking at a later time, but you cannot go back to previous restaurants. 
Once you have voted for all the restaurants Click Back to Dashboard button in pink
Wait for Owner to vote to end voting period
If you are the owner please refer to “As a Owner” section for voting
On the Fruip Dashboard in the table underneath the column Currently Voting? You will see the amount of people still needed to vote
Once owner has voted a new button will appear underneath the Results column on the Fruip Dashboard
Click Results
The Restaurants that got the most votes will be displayed on the page.

---------------------------------------------------------------------------------------------------

Developer Documentation                                          
Front-end
The front-end of the server is on Amazon Web Services as explained in the Administration Documentation you will need access to the server. 
This allows you to edit the code in whatever program works for you.
 The two options the original developers used were 
Putty 
Notepad++ with NppFTP extension loaded on.
 All pages should have loaded documentation showing what each page does. In terms of specific code used for this project. Some sections may require additional knowledge such as jquery, others probably will not as we use HTML5, CSS4 technically, but CSS3 code is used and Javascript.
JQuery in the future may be a dying front-end framework, but still lives on in many front-end websites. If you do not know JQuery your two options are to replace it or learn JQuery. There are plenty of guides online teaching JQuery in the case that a front-end update is needed. Further explanations for our use of JQuery can be found in the jquery-1.11.1.js file on the server which includes documentation on how to use it. 
Javascript is used tremendously throughout the program as a connector between the back-end and front-end will require communications with the back-end crew in order to fully implement. Any questions about base javascript can be found on W3Schools @ https://www.w3schools.com/js/default.asp
HTML is pretty self explainable. We don’t use any non-vanilla HTML tags. Questions about our HTML can almost be completely found on W3Schools @ https://www.w3schools.com/html/default.asp
CSS is also pretty self explainable. Our only advanced CSS is our hover modes which can be found many places on the web. We recommend most of what we use is pretty standard and can also be found on W3Schools @ https://www.w3schools.com/css/default.asp
SCSS is hardly used in our project. The file we tested it on may be removed before you even see this. Either way, SCSS are a special type of CSS that uses Ruby to enhance CSS to do cool things. It adds variables, nesting and other features that allow advanced features like moving backgrounds that move with the cursor. It should be known that the SCSS files translate to CSS so the browser can understand what to do. 

Back-end

The front-end of the server is on Amazon Web Services as explained in the Administration Documentation you will need access to the server. 
This allows you to edit the code in whatever program works for you.
 The two options the original developers used were 
Putty 
Notepad++ with NppFTP extension loaded on.
All pages should have loaded documentation showing what each page does. In terms of specific code used for this project. Some sections may require additional knowledge such as XML, PDO for postgreSQL and some JSON. Other sections may not require additional knowledge such as the PHP and some HTML required for output onto a HTML page.
XML is a different markup language like HTML and we use it to extract data from our Google Places API. When using file_get_contents from the XML page you MUST pass in a few parameters. The URL of the XML data, a false boolean because you will not provide google with their source path and lastly you need to pass in the $context to tell the google API to give you XML. We didn’t use it everywhere, but in the API pages. You will need to know that XML needs to be converted to a string then to PHP array upon getting data from API. Also, when data is retrieved from the database votes come back as a boolean. These boolean values will need to be converted to an integer (likely 0 or 1) so the PHP program can recognize them properly. 
PDO (stands for PHP Data Object) is a lightweight PHP extension that acts like an API in the sense it can be used with multiple different databases. That being said, we do have to use a database-specific driver. PDO wasn’t our first choice, but AWS wasn’t friendly towards other methods. Lastly, it should be noted PDO doesn’t provide abstraction, so PostgreSQL statements must be used as if you were calling them in a regular SQL statement. With PDO you must bind the values in the table with the values in the PHP code if those values are needed to be placed in the table. Examples of that are in the code, but generally look like this PDO->bindValue(‘:tableVal’, $phpVal);
PostgreSQL acts very similarly to other SQL languages. It's incredibly popular and similar to MySQL as well. PostgreSQL is simple and easy to learn. If needed here is a link for PostgreSQL help @ https://www.postgresqltutorial.com/
JSON is not used all that extensively in the project, however, we do store the array of votes in the database as a JSON array. Make sure you know how the function in orgVotes.php converts a PHP array to a JSON array to be stored.
PHP can be self explainable as well and most questions about our PHP can be answered by going to https://www.w3schools.com/php/default.asp
HTML can be found in the “front-end” section of this document. 

Database
We go over database syntax and usage in the back-end section of this document however knowledge of the database still lacks. 
Below is the current state of the databases with one example to show how data is stored.

USERS
Example
ID  / fname / lname / email         / usename / hash         / salt       / 
123 / Rob   / Boss  / rBoss@aol.com / theBoss /6ef0MSVG45YyQ / 8b0165931b /
96kb for 1 Row of data

Salt is randomized and used to hash original password, so passwords themselves are not stored

FRUIPS
Example
gname  / owner   / voting              / radius* / zip   / 
smiles / theBoss / t(true)or f (false) / 500     / 99999 /
96kb for 1 Row of data

*Radius was the original parameter used to gather restaurants, now it is the zip. Voting stays true if still active. gname is for the Fruips and stands for group name

MEMBERSHIPS
Example
usename / gname  / pending / reqadd / votes           / voted / currplace
theBoss / smiles / T or F  / T or F / {t,f,t,f,t,f... / T or F / 20
64kb for 1 Row of data

Pending shows whether an invitation was sent for them to join the fruip. Reqadd is if the user has requested to join the fruip. Votes shows in which order the user voted for the restaurants. They stay in the same order for every user. Allowing for vote calculation. Voted means whether or not the user has voted on every possible restaurant or not. Currplace is used to determine how many votes the user still has left and to display the current restaurant for specific users. 

PLACES
Example
gname  / name         / image                                                                                                               / location / 
smiles / Waffle House  https://maps.googleapis.com/maps/api/place/photo?/maxwidth=400&maxheight=200&photorefernce={Reference code from API} / 123 TestArea Rd. New York, NY, United States
64kb for 1 Row of data

When a Fruip initiates a vote their restaurants get stored here. The gname is used as a foreign key for the other tables while the rest are used to populate the image you see on the swipe.html page. The google API supplies the reference and you plug it into the baseURL to get the photo.

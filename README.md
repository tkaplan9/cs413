In "src" there is the java file, in "cs413" there are the php files.

Java file is needed to initialize the database and insert tuples. You don't need to do anything with that file if you don't want to insert new employees or companies. If you do, then you need to download jdbc driver (https://dev.mysql.com/downloads/connector/j/) and add it to your java project as an external library before you can run this java file.

The php part is basically the UI part which is what we will show in the demo, to run it, first download XAMPP (https://www.apachefriends.org/tr/download.html) then add everything inside the cs413 folder to xampp/htdocs, run apache on xampp and go to localhost/cs413 on your browser.

Example demo use:
Enter username "Tarik" and password "qwerty"
You will see google is applied and some other companies are available to be applied
Apply to some other company
Cancel an application
Milsoft is a company registered to our system but in the available companies section you won't see it, that's because it's quota is reached.
Search "milsoft" on home page
It should come up, try to register to it, you can't, quota message will be displayed.

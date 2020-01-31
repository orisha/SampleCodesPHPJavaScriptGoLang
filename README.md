# Sample Codes 


WARN : 

_ This is only a " HOW I CODE " Sample . 

_ The files will probably not run because they have many NOT INCLUDED dependencies


All the scripts here are a piece of code from an essential part in a huge management system


We can see the system working here :

BackEnd : http://18.216.194.20/fibootkt/MY_app_dev.php/login/pt ( click on goforit to login )

Ticket Sell : http://18.216.194.20/fibootkt/MY_app_dev.php/Shopping

UX Study : http://18.216.194.20/fibootkt/MY_app_dev.php/pt



<b>PHP - Symfony </b>

How the system is multiclient and multiplatafform, I had to manage differents sessions from each client/user/platafform


The way to handle that was to Wrap under the Symfony's Session Component in a way that 
$Session will be actually $Session[CLIENT], but, the way to code will still be only $Session  


To achieve that, I basically overide the Symfony Session component re-writing a few methods, doing some action before call the original method  




<b>GoLang</b> 

Golang has taken an important hole in the system. Many new modules were built using golang as controller or API 

To make my life easier, I have been structing the go codes same way symfony does. Also, trying to replicate Doctrine.

I have an Insert function that takes an struct which represents a database table.

That struct will be transformed into an Insert or Update SQL statement according to its ID field 

Simple and abstract !


<b>JavaScript - jQuery</b>

I have combined many jQuery native plugins to achieve best user experience when searching a product

the <b>MyAutoCompleteModal</b> uses 

_ Dialog
_ AutoComplete
_ InfiniteScroll
_ Ajax 

Creating an elegant and efficient way to better use of the search field



<b>External Seller </b>:

It will inject ( in this case ) the ticket listing to be displayed and put on sell into the clients website

Basically, adding one line into the website source code 
( < script type="text/javascript" src="link_to_public_file" >)

the script will inject all content need to perform a sell !




Kotlin :

By need, I have study a bit o mobile development, and I chose Kotlin over Java ....
Heres a sample code from an app that reads a NFC tag nad then makes a request to an API 



Async Api Request :


/**

 sample use

 var task = AsyncNFCAPI(this)

 val req =
 NFCAPIRequest (
 BaseUrl = "http://192.169.9.168:9663/",
 action = "SellerStatement",
 payload = mapOf("seller" to 1, "first" to 0, "last" to 5),
 method = "POST"

 )

 GlobalScope.launch( context = Dispatchers.Main){

    task?.execute(req)
}

 GlobalScope.launch( context = Dispatchers.Main){

    println( "Here gets the result from async task " +  task?.get().toString() )
}


 */

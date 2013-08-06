googlePhotosImageFinder
=======================
Since 2013 (I guess), Picasa has been merged on Google+

Now with Google+, all images <= 2048pixels are free of charge. All images wider will count on your Google account quota.
If you need to find such images, just use this small PHP script. It will tell you which albums has this kind of data.

Output example
--------------
Call script with your Google ID. You can find it in the URL when you list all your albums : 
https://plus.google.com/photos/< this is your ID >/albums

	$ php findpicasagooglebigimages.php <my google ID>

	Found 53 albums
	There is 26 albums with images larger than 2048 pixels. This is 869 pics on a total of 2800.
	  9 photos larger - http://plus.google.com/photos/XXX/albums/5827401518537866641
	  8 photos larger - http://plus.google.com/photos/XXX/albums/5826260862028886737
	  5 photos larger - http://plus.google.com/photos/XXX/albums/5825278058751377441
	[...]


TODO
----
Handle private albums by just using auth on Picasa API.

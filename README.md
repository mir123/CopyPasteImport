# CopyPasteImport

This PHP + jQuery (though easily done with pure Javascript) takes a bunch of columns and rows copied and pasted from a spreadsheet (i.e. OpenOffice Calc, Excel or Google Spreadsheets) and imports them into a database.

Some validation is included: max number of columns, making sure field names are not duplicated and some basic regexp for the example fields (date of birth and email).  You need to add your own database writing code.

This was made for a ships crew management application we developed here at Greenpeace which we will release soon. It was inspired by something I saw at MailChimp and then by an answer on Stack Overflow by by Tatu Ulmanen (http://stackoverflow.com/users/198707/tatu-ulmanen) at http://stackoverflow.com/questions/2006468/copy-paste-from-excel-to-a-web-page/2006514#2006514 

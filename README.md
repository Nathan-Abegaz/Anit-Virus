# Anti-Virus Scanner
The idea is to create a web-based Antivirus application that allows the users to upload a file (of any type) to check if it contains malicious content.
That is, if it is a Malware or not.


## The webpage will do the following: 

The following functionality is completed:

- [x] Allows the user to submit a putative infected file and shows if it is infected or not.
- [x] Lets authenticate an Admin and allows him/her to submit a Malware file, plus the name of the uploaded Malware (ex, Winwebsec).
- [x] Reads a file uploaded in input by an Admin, per bytes, and stores a sequence of bytes from the file, say, the first 20 bytes (signature), in a database (Note: an Admin uploads only Malware files).
- [x] Reads a file uploaded by a normal user in input, per bytes, and searches within the file for one of the strings stored in the database (Note: a normal user will always upload putative infected files). 

## MySQL database:
- [x] Stores the information regarding the infected files in input, such as name of the malware (not the name of the file) and the sequence of bytes
- [x] Stores the information related to the Admin with username and password, in the most secure way of your knowledge.

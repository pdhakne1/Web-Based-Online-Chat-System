{\rtf1\ansi\ansicpg1252\cocoartf1347\cocoasubrtf570
{\fonttbl\f0\fswiss\fcharset0 Helvetica;\f1\froman\fcharset0 Times-Roman;\f2\fnil\fcharset0 Menlo-Regular;
}
{\colortbl;\red255\green255\blue255;}
\margl1440\margr1440\vieww10800\viewh8400\viewkind0
\pard\tx720\tx1440\tx2160\tx2880\tx3600\tx4320\tx5040\tx5760\tx6480\tx7200\tx7920\tx8640\pardirnatural

\f0\fs24 \cf0 CREATE DATABASE chatSystem;\
USE chatSystem;\
\pard\pardeftab720

\f1 \cf0 \expnd0\expndtw0\kerning0
CREATE TABLE Users(UserID int NOT NULL AUTO_INCREMENT, UserName varchar(255), Password varchar(255), IsLogin char(1) DEFAULT \'92N\'92, PRIMARY KEY (UserID)); \
INSERT INTO Users ( UserName, Password) VALUES (\'91Pallavi\'92,\'92pallavi\'92); \
INSERT INTO Users ( UserName, Password) VALUES (\'91Friend1\'92,\'92friend1\'92); \
INSERT INTO Users ( UserName, Password) VALUES (\'91Friend2\'92,\'92friend2\'92); \
INSERT INTO Users ( UserName, Password) VALUES (\'91Friend3\'92,\'92friend3\'92); \
INSERT INTO Users ( UserName, Password) VALUES (\'91Friend4\'92,\'92friend4\'92); \
\pard\tx560\tx1120\tx1680\tx2240\tx2800\tx3360\tx3920\tx4480\tx5040\tx5600\tx6160\tx6720\pardirnatural

\f2\fs22 \cf0 \kerning1\expnd0\expndtw0 \CocoaLigature0 Create Table Friends(UserID int, FriendID int, LogFile varchar(50), PRIMARY KEY(UserID, FriendID), Foreign key (UserID) references Users(UserID), Foreign Key (FriendID) references Users(UserID));\
Insert into Friends(UserID, FriendID,LogFile) Values(1,2,'chat12');\
Insert into Friends(UserID, FriendID,LogFile) Values(1,3,\'92chat13\'92);\
Insert into Friends(UserID, FriendID,LogFile) Values(1,4,\'92chat14\'92);\
Insert into Friends(UserID, FriendID,LogFile) Values(1,5,\'92chat15\'92);\
Insert into Friends(UserID, FriendID,LogFile) Values(2,1,\'92chat12');\
Insert into Friends(UserID, FriendID,LogFile) Values(2,3,\'92chat23\'92);\
Insert into Friends(UserID, FriendID,LogFile) Values(3,2,\'92chat23\'92);\
Insert into Friends(UserID, FriendID,LogFile) Values(3,4,\'92chat34\'92);\
Insert into Friends(UserID, FriendID,LogFile) Values(4,3,\'92chat34\'92);\
Insert into Friends(UserID, FriendID,LogFile) Values(4,5,\'92chat45\'92);\
Insert into Friends(UserID, FriendID,LogFile) Values(5,4,\'92chat45\'92);\
Insert into Friends(UserID, FriendID,LogFile) Values(5,1,\'92chat15\'92);\
Insert into Friends(UserID, FriendID,LogFile) Values(3,1,\'92chat13\'92);\
Insert into Friends(UserID, FriendID,LogFile) Values(4,1,\'92chat14\'92);\
Insert into Friends(UserID, FriendID,LogFile) Values(5,1,\'92chat15\'92);}
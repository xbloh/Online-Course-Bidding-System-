drop schema if exists BOSSB;
create schema BOSSB;
use BOSSB;

create table STUDENT (
    userid varchar(128) not null,
	password varchar(128) not null,
    name varchar(100) not null,
    school varchar(100) not null,
    edollar	decimal(5,2) not null,
	CONSTRAINT STUDENT_PK primary key (userid)
);

create table COURSE (
    courseID varchar(100) not null,
    school varchar(100) not null,
    title varchar(100) not null,
    description varchar(1000),
    examDate date not null,
	examStart time not  null,
	examEnd time not  null,
	CONSTRAINT COURSE_PK primary key (courseID)
);

create table SECTION (
    courseID varchar(100) not null,
	sectionID varchar(2) not null,
    day int not null,
    start time not null,
    end	time not null,
    instructor varchar(100) not null,
	venue varchar(100) not  null,
	size int not  null,
	CONSTRAINT SECTION_PK primary key (courseID,sectionID),
	CONSTRAINT SECTION_FK1 foreign key(courseID) references COURSE(courseID)
);


create table PREREQUISITE (
    course varchar(100) not null,
	prerequisite varchar(100) not null,
	CONSTRAINT PREREQUISITE_PK primary key (course,prerequisite),
	CONSTRAINT PREREQUISITE_FK1 foreign key(course) references COURSE(courseID),
	CONSTRAINT PREREQUISITE_FK2 foreign key(prerequisite) references COURSE(courseID)
);

create table COURSE_COMPLETED (
    userid varchar(128) not null,
    code varchar(100) not null,
	CONSTRAINT COURSE_COMPLETED_PK primary key (userid,code),
	CONSTRAINT COURSE_COMPLETED_FK1 foreign key(userid) references STUDENT(userid),
	CONSTRAINT COURSE_COMPLETED_FK2 foreign key(code) references COURSE(courseID)
);

create table BID (
    userid varchar(128) not null,
    amount decimal(5,2) not null,
    code varchar(100),
    section varchar(2) not null,
	CONSTRAINT BID_PK primary key (userid,code,section),
	CONSTRAINT BID_FK1 foreign key(userid) references STUDENT(userid),
	CONSTRAINT BID_FK2 foreign key(code,section) references SECTION(courseID,sectionID)
);

create table SUCCESSFUL_BID (
    userid varchar(128) not null,
    amount decimal(5,2) not null,
    code varchar(100),
    section varchar(2) not null,
	CONSTRAINT SUCCESSFUL_BID_PK primary key (userid,code,section),
	CONSTRAINT SUCCESSFUL_BID_FK1 foreign key(userid) references STUDENT(userid),
	CONSTRAINT SUCCESSFUL_BID_FK2 foreign key(code,section) references SECTION(courseID,sectionID)
);

create table ROUNDS (
    round int not null,
    status varchar(10) not null
);

INSERT INTO ROUNDS (round, status) VALUES (0, 'Begin');

LOAD DATA LOCAL INFILE 'C:/Users/Tricia/Documents/GitHub/project-g4t4/app/sql/sample_data/course.csv' INTO TABLE COURSE FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE 'C:/Users/Tricia/Documents/GitHub/project-g4t4/app/sql/sample_data/prerequisite.csv' INTO TABLE PREREQUISITE FIELDS TERMINATED BY ',' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE 'C:/Users/Tricia/Documents/GitHub/project-g4t4/app/sql/sample_data/section.csv' INTO TABLE SECTION FIELDS TERMINATED BY ',' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE 'C:/Users/Tricia/Documents/GitHub/project-g4t4/app/sql/sample_data/student.csv' INTO TABLE STUDENT FIELDS TERMINATED BY ',' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE 'C:/Users/Tricia/Documents/GitHub/project-g4t4/app/sql/sample_data/bid.csv' INTO TABLE BID FIELDS TERMINATED BY ',' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE 'C:/Users/Tricia/Documents/GitHub/project-g4t4/app/sql/sample_data/course_completed.csv' INTO TABLE COURSE_COMPLETED FIELDS TERMINATED BY ',' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;


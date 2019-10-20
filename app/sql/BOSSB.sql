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
	CONSTRAINT SECTION_PK primary key (courseID,sectionID)
);


create table PREREQUISITE (
    course varchar(100) not null,
	prerequisite varchar(100) not null,
	CONSTRAINT PREREQUISITE_PK primary key (course,prerequisite)
);

create table COURSE_COMPLETED (
    userid varchar(128) not null,
    code varchar(100) not null,
	CONSTRAINT COURSE_COMPLETED_PK primary key (userid,code)
);

create table BID (
    userid varchar(128) not null,
    amount decimal(5,2) not null,
    code varchar(100),
    section varchar(2) not null,
    result varchar(3) not null,
    round int not null,
	CONSTRAINT BID_PK primary key (userid,code,section)
);

create table SUCCESSFUL_BID (
    userid varchar(128) not null,
    amount decimal(5,2) not null,
    code varchar(100),
    section varchar(2) not null,
	CONSTRAINT SUCCESSFUL_BID_PK primary key (userid,code,section)
);

create table ROUNDS (
    round int not null,
    status varchar(10) not null
);

INSERT INTO ROUNDS (round, status) VALUES (0, 'Begin');

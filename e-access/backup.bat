REM @echo off
(	set /p host=
	set /p user=
	set /p password=
	set /p dbname=
	)<..\content.txt
	
cd C:\xampp\mysql\bin
mysqldump --host=%host% -u%user% -p%password% %dbname% > C:\xampp\htdocs\e-access\%dbname%.sql

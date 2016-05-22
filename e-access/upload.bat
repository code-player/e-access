@echo off
(	set /p host=
	set /p user=
	set /p password=
	set /p dbname=
	)<..\content.txt

for /f "tokens=*" %%x in (..\content.txt) do set loc=%%x

cd C:\xampp\mysql\bin
mysql --host=%host% -u%user% -p%password% %dbname% < %loc%

# usage: ./set_db_passwd.sh <database_username> <database_pwd> <database_name> <panel_user> <panel_pwd> <salt>
$hashed_pass = $(echo -n $5$6 | sha256sum)
myqsl -u $1 -p $2 $3 -e "INSERT INTO auth (username, password) VALUES ("$4", "$hashed_pass")"
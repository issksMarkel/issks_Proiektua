
# Taldekide izenak
Iker Diez Henales  
Ibai Gonzalez Tejedor  
Markel Valle Vivanco  

## Martxan jartzeko instrukzioak
1. Inportatu SQL datu-basearen edukiontzira:  
	
		'$ docker-compose exec -T db mysql -u admin -ptest database < database.sql'
		
2. Behin repositorioa behin klonatuta:  
	
		'$ docker-compose build'

3. Docker-a hasi:  

		'$docker-compose up -d'

Bi komando horiekin sistema martxan jarri da, orain web orria eta datu basea ikusteko  
	4. Nabigatzailean sartu eta:  

		'http://localhost:81'

##Web orria zabaldu da  
	5. Nabigatzailean sartu:  

		'http://localhost:8890'
##Datu-basea zabaldu da

##Sistema amatatzeko:

		'$ docker-compose down'


###Datu-basea ez bada guztiz zabaltzen hurrengoa idatzi:

		'$docker volume rm issks_proiektua_db_data'

# Proje Kurulumu 


 * Dosyayı Git Hubtan Çekiyoruz
    ```
    git clone https://github.com/umut-ramazan/docker-en-uygun.git  
    ```
  

 * Dosya içerisindeyken terminale kodu yazıyoruz
    ```
    docker-compose up -d -build
    ```

 * Docker kurulumu yaptıktan sonra php docker container idsini alıyoruz
      ```
      docker ps
      ``` 
    Aldığımız idyi "phpid" yerine yazan yere yazıyoruz
     ```
     docker exec -it phpid bash
     ``` 
  
  * Php terminalindeyken kodunu yazıp gerekli paketleri indiriyoruz.
       ```
       composer install
       ``` 
 * Kurulum tamamlandı Giriş bilgileri
    
      #### User Bilgileri
       - Kullanıcı adı : musteri@gmail.com
       - Şifre : 123456
       - adres : http://localhost:8000/client
        
        
      #### Admin Bilgileri
       - Kullanıcı adı : admnin@gmail.com
       - Şifre : 123456
       - adres : http://localhost:8000/admin
        
      ### Giriş sayfası
      - adres : http://localhost:8000/login
      
      ### Phpmyadmin ve Kibana Sayfası
      - Phpmyadmin adres : http://localhost:8082/
      - Kibana adres :  http://localhost:5601/app/kibana
  
 

# Proje tanıtımı

Kullanımı   [documentation](doc/use.md)


 

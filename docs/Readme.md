# Proje Kurulumu 


 * Dosyayı Git Hubtan Çekiyoruz
    ```
    git clone https://github.com/umut-ramazan/En-Uygun-Bitirme-Projesi
    ```
  

 * Dosya içerisindeyken terminale kodu yazıyoruz
    ```
    docker-compose up -d -build
    ```

 * Docker kurulumu yaptıktan sonra php docker container idsini alıyoruz
      ```
      docker ps
      ``` 
      ![Docker Ps](https://user-images.githubusercontent.com/68502979/183945206-255f0783-3b4d-4924-84ec-ee7b331ae955.png)
  
   Aldığımız idyi "phpid" yerine yazan yere yazıyoruz
     ```
     docker exec -it phpid bash
     ``` 
  
  * Php terminalindeyken kodunu yazıp gerekli paketleri indiriyoruz.
       ```
       composer install
       ``` 
     ![composer instal](https://user-images.githubusercontent.com/68502979/183945777-3479a751-0a3f-404c-81d1-2eaa09ed2011.png)
     
     
 * Kurulum tamamlandı Giriş bilgileri
    
      #### User Bilgileri
       - Kullanıcı adı : musteri@gmail.com
       - Şifre : 123456
       - adres : http://localhost:8000/client
        
        
      #### Admin Bilgileri
       - Kullanıcı adı : admin@gmail.com
       - Şifre : 123456
       - adres : http://localhost:8000/admin
        
      ### Giriş sayfası
      - adres : http://localhost:8000/login
      
      ### Phpmyadmin ve Kibana Sayfası
      - Phpmyadmin adres : http://localhost:8082/
      - Kibana adres :  http://localhost:5601/app/kibana
      
      
      #### Databasenin Backup Dosyası "app.sql" 'dir.
  
 

 

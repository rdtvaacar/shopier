#  LARAVEL - ACR FİLE -- FİLE UPLOAD CLASS

[Query-File-Upload](https://github.com/blueimp/jQuery-File-Upload): Paketi refarans alarak oluşturulmuştur.

## Kurulum:
#### composer json : 
```
"acr/file": "dev-file"
```
### CONFİG

#### Providers
```
Acr\Ftr\AcrFtrServiceProviders::class
```
#### Aliases
```
'AcrFtr'      => Acr\Ftr\Facades\AcrFtr::class
```
### acr_file_id

```php 
PHP
$acr_file_id = AcrFtr::create($acr_file_id); 
```
acr_file_id: ilişkili tablodan gelmeli örneğin ürünler için kullanacaksanız urun tablonuzda acr_file_id stunu olmalı, acr_file_id değişkeni null gelirse : $acr_file_id = AcrFtr::create($acr_file_id) yeni bir acr_file_id oluşturur.
```php 
PHP
 echo AcrFtr::css();  
```
CSS dosyalarını yükler.
```php 
PHP
echo AcrFtr::form()
```
Formu yükler
```php 
PHP
echo AcrFtr::js($acr_file_id)
```
Java script dosylarını yükler.

```sql 
Mysql Tablosu

CREATE TABLE `acr_files` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(66) COLLATE utf8_turkish_ci DEFAULT NULL,
  `file_dir` varchar(50) COLLATE utf8_turkish_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sil` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `acr_files_childs`
--

CREATE TABLE `acr_files_childs` (
  `id` int(11) NOT NULL,
  `acr_file_id` int(11) DEFAULT NULL,
  `file_name` varchar(200) COLLATE utf8_turkish_ci DEFAULT NULL,
  `file_name_org` varchar(200) COLLATE utf8_turkish_ci DEFAULT NULL,
  `fize_size` varchar(25) COLLATE utf8_turkish_ci DEFAULT NULL,
  `file_type` varchar(10) COLLATE utf8_turkish_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sil` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

ALTER TABLE `acr_files`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `acr_files_childs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `acr_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `acr_files_childs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
```
Dosya yolu  /acr_files/acr_file_id

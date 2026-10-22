
   50  wget -O equipaments.csv "https://opendata-ajuntament.barcelona.cat/data/dataset/f36b60f2-9541-4d08-b0f9-b0a9313fab3d/resource/29d9ff10-6892-4f16-9012-d5c4997857e7/download" file equipaments.csv
   51  ls
   52  cat equipaments.csv 
   53  clear
   54  head -n 3 equipaments.csv
   55  head -n 1 equipaments.csv
   56  register_id,name,institution_id,institution_name,created,modified,addresses_roadtype_id,addresses_roadtype_name,addresses_road_id,addresses_road_name,addresses_start_street_number,addresses_end_street_number,addresses_neighborhood_id,addresses_neighborhood_name,addresses_district_id,addresses_district_name,addresses_zip_code,addresses_town,addresses_main_address,addresses_type,values_id,values_attribute_id,values_category,values_attribute_name,values_value,values_outstanding,values_description,secondary_filters_id,secondary_filters_name,secondary_filters_fullpath,secondary_filters_tree,secondary_filters_asia_id,geo_epgs_25831_x,geo_epgs_25831_y,geo_epgs_4326_lat,geo_epgs_4326_lon,estimated_dates,start_date,end_date,timetable
   57  clear
   58  (echo "CREATE TABLE equipaments ("; head -n 1 equipaments.csv | sed; 's/^\xEF\xBB\xBF//' | tr -d '"\r' | sed 's/,/ TEXT, /g; s/$/ TEXT/'; echo; ");") > crear_tabla.sql
   59  ls
   60  cat crear_tabla.sql 
   61  sudo nano crear_tabla.sql 
   62  ls
   63  sudo rm equipaments.csv 
   64  ls
   65  (echo "CREATE TABLE equipaments ("; head -n 1 equipaments.csv | sed; 's/^\xEF\xBB\xBF//' | tr -d '"\r' | sed 's/,/ TEXT, /g; s/$/ TEXT/'; echo; ");") > crear_tabla.sql
   66  ls
   67  clear
   68  ls
   69  clear
   70  wget -O equipaments.csv "https://opendata-ajuntament.barcelona.cat/data/dataset/f36b60f2-9541-4d08-b0f9-b0a9313fab3d/resource/29d9ff10-6892-4f16-9012-d5c4997857e7/download" file equipaments.csv
   71  ls
   72  clear
   73  sudo rm crear_tabla.sql 
   74  ls
   75  clea
   76  clear
   77  ls
   78  (echo "CREATE TABLE equipaments ("; head -n 1 equipaments.csv | sed 's/^\xEF\xBB\xBF//' | tr -d '"\r' | sed 's/,/ TEXT, /g; s/$/ TEXT/'; echo ");") > crear_tabla.sql
   79  cat crear_tabla.sql
   80  ls
   81  sudo mysql equipaments < crear_tabla.sql
   82  iconv -f UTF-16 -t UTF-8 equipaments.csv -o equipaments_utf8.csv 2>/dev/null || tr -d '\0' < equipaments.csv > equipaments_utf8.csv
   83  mv equipaments_utf8.csv equipaments.csv
   84  (echo "CREATE TABLE equipaments ("; head -n 1 equipaments.csv | sed 's/^\xEF\xBB\xBF//' | tr -d '"\r' | sed 's/,/ TEXT, /g; s/$/ TEXT/'; echo ");") > crear_tabla.sql
   85  cat crear_tabla.sql
   86  sudo mysql equipaments < crear_tabla.sql
   87  tr -d '\0' < equipaments.csv > equipaments_clean.csv
   88  mv equipaments_clean.csv equipaments.csv
   89  cat crear_tabla.sql
   90  sudo mv equipaments.csv /var/lib/mysql-files/
   91  sudo mysql equipaments
   92  history

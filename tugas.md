Mutofa Kerjakan ini :
1. Buat Database yang berisi tabel sebagai Berikut :
   1. Tabel users dengan kolom id,name,email,password,created_at,updated_at
   2. Tabel roles dengan kolom id,name,created_at,updated_at
   3. Tabel model_has_roles dengan kolom role_id,model_type,model_id
   4. Tabel education_levels dengan kolom id,name,created_at,updated_at
   5. Tabel educational_institutions dengan kolom id,name,education_level_id,created_at,updated_at
   6. Tabel teachers dengan kolom id,user_id,educational_institution_id,created_at,updated_at
   7. Tabel students dengan kolom id,user_id,educational_institution_id,created_at,updated_at
   8. Tabel classes dengan kolom id,name,educational_institution_id,created_at,updated_at
   9. Tabel subjects dengan kolom id,name,educational_institution_id,created_at,updated_at
   10. Tabel class_subjects dengan kolom class_id,subject_id,educational_institution_id,created_at,updated_at
   11. Tabel scores dengan kolom id,student_id,class_id,subject_id,score,educational_institution_id,created_at,updated_at



Yang Mulia ust mufid
1.buat migrations   
2.buat models
3.buat controllers
4.buat views
5.buat routes
6.buat controllers
7.buat views
8.buat routes
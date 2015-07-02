/*======================
========= ISSUE : #8220, #8230
========================*/
ALTER TABLE "public"."rs_student"
ADD COLUMN "faculty_name" INT4,
ADD COLUMN "schedule_date" TIMESTAMP;

/*======================
========= ISSUE : #8226
========================*/
ALTER TABLE "public"."rs_seminar"
ADD COLUMN "lecturer" VARCHAR(50),
ADD COLUMN "outline" VARCHAR(400),
ADD COLUMN "location_url" VARCHAR(50),
ADD COLUMN "apply_from_date" TIMESTAMP,
ADD COLUMN "apply_to_date" TIMESTAMP;

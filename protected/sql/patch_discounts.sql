--        ALTER TABLE `forbac_test`.`users` 
-- ADD COLUMN `price_dis_id` INT(11) NULL DEFAULT NULL AFTER `group_id`;

-- ALTER TABLE `forbac_test`.`prices` 
-- ADD COLUMN `name1` FLOAT(6,2) NULL DEFAULT NULL AFTER `portable_price`,
-- ADD COLUMN `portable_price1` FLOAT(6,2) NULL DEFAULT NULL AFTER `name1`;

ALTER TABLE `forbac_test`.`prices` 
ADD COLUMN `name2` FLOAT(6,2) NULL DEFAULT NULL AFTER `portable_price1`,
ADD COLUMN `portable_price2` FLOAT(6,2) NULL DEFAULT NULL AFTER `name2`;

ALTER TABLE `forbac_test`.`prices` 
ADD COLUMN `name3` FLOAT(6,2) NULL DEFAULT NULL AFTER `portable_price2`,
ADD COLUMN `portable_price3` FLOAT(6,2) NULL DEFAULT NULL AFTER `name3`;

ALTER TABLE `forbac_test`.`prices` 
ADD COLUMN `name4` FLOAT(6,2) NULL DEFAULT NULL AFTER `portable_price3`,
ADD COLUMN `portable_price4` FLOAT(6,2) NULL DEFAULT NULL AFTER `name4`;

ALTER TABLE `forbac_test`.`prices` 
ADD COLUMN `name5` FLOAT(6,2) NULL DEFAULT NULL AFTER `portable_price4`,
ADD COLUMN `portable_price5` FLOAT(6,2) NULL DEFAULT NULL AFTER `name5`;
